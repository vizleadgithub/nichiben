// テストの観点3: 各画面の HTML ソースに確認用出力（SQL・var_dump・<!--[ 等）や PHP エラーが出ていないことを確認する。
// 読み取り専用: リンクを GET でたどるだけで、フォーム送信はしない。更新系・ログアウト・決済等の URL はスキップする。
// 使い方: node check-debug-output.js [cms|student] [最大ページ数] [--seeds]   (事前に node login.js でセッションを保存しておく)
const fs = require('fs');
const path = require('path');
const { chromium, assertStg2, SITES, RESULT_DIR, newContext } = require('./lib');
const { loginCms } = require('./login');
const { CHECKS, snippet } = require('./detect');

const MAX_PER_PATTERN = 3;   // 同じ形のURL(IDだけ違う等)は3件まで
const WAIT_MS = 300;         // サーバー負荷を避けるための間隔

// 開くだけで副作用がありうるURL（パス・クエリに含まれていればスキップ）
const SKIP_COMMON = /logout|login|school_select|delete|del_|_del\b|remove|commit|regist|insert|update|exec|complete|save|upload|import|download|csv|export|send|sync|bat_|api_|cancel|reset|approve|clear|copy|_set\b|set\.php|\.(pdf|zip|csv|xlsx?|docx?|pptx?|jpe?g|png|gif|svg|mp4|mp3|css|js)(\?|$)/i;
// 受講者サイトは開くだけで視聴履歴・解答・申込等が記録される画面があるため、パス単位で追加スキップ
const SKIP_PATH_BY_SITE = {
  cms: null,
  student: /^\/(player|exam|exam2|ethic_treaning|settlement|member|login|logout|engine1|engine1_sp|data1|data1_sp|question)(\/|$)|^\/mypage\/(favorite|edit|refusal|receipt_download|ticket_download|input_zip|viewing)\.php|^\/product\/complete\.php|download\.php/i,
};

const pattern = (u) => u.pathname.replace(/\/[^/]*\d[^/]*(?=\/|$)/g, '/{n}') + '?' +
  [...u.searchParams.keys()].sort().join('&');

// --seeds: リンクで辿れない画面も確認するため、リポジトリのファイル構成から巡回の起点URLを作る
const REPO = path.join(__dirname, '../..');
function listFiles(dir, re, base = dir) {
  if (!fs.existsSync(dir)) return [];
  return fs.readdirSync(dir, { withFileTypes: true }).flatMap((d) => {
    const p = path.join(dir, d.name);
    if (d.isDirectory()) return /^(backup|js|css|custom_pages|vendor|images?|img)$/i.test(d.name) ? [] : listFiles(p, re, base);
    return re.test(d.name) ? ['/' + path.relative(base, p).split(path.sep).join('/')] : [];
  });
}
function seedUrls(site, origin) {
  const test = /_test|test\.php|_old|_bk|\d{8}/i;
  let paths;
  if (site === 'student') {
    paths = listFiles(path.join(REPO, 'alfproduct/public'), /\.php$/);
  } else {
    const ctl = path.join(REPO, 'alflearning/alflearning-cms/application/controllers');
    paths = fs.readdirSync(ctl).filter((f) => /^(Cms_|Admin_top)\w*\.php$/.test(f)).map((f) => '/' + f.replace(/\.php$/, '').toLowerCase());
    paths.push(...listFiles(path.join(REPO, 'alflearning/alflearning-cms/alfproduct'), /^index\.php$/).map((p) => '/alfproduct' + p.replace(/index\.php$/, '')));
  }
  return paths.filter((p) => !test.test(p)).map((p) => origin + p);
}

(async () => {
  const site = process.argv[2] || 'cms';
  const maxPages = Number(process.argv[3] || 200);
  if (!SITES[site]) throw new Error('site は cms か student を指定してください');
  if (!fs.existsSync(SITES[site].state)) throw new Error(`先に node login.js ${site} を実行してください`);
  const start = SITES[site].baseUrl();
  assertStg2(start);
  const origin = new URL(start).origin;

  const browser = await chromium.launch();
  let context, page;
  const openContext = async () => {
    context = await newContext(browser, site);
    page = await context.newPage();
    page.on('dialog', (d) => d.dismiss());   // alert 等は閉じる（観点1は別スクリプトで扱う）
  };
  await openContext();
  let relogins = 0;

  const queue = [start];
  if (process.argv.includes('--seeds')) {
    for (const s of seedUrls(site, origin)) {
      const v = new URL(s);
      const isSkip = SKIP_COMMON.test(v.pathname) || (SKIP_PATH_BY_SITE[site] && SKIP_PATH_BY_SITE[site].test(v.pathname));
      if (!isSkip) queue.push(s);
    }
    console.log(`起点URL ${queue.length} 件`);
  }
  const seen = new Set();
  const perPattern = new Map();
  const skipped = new Set();
  const results = [];

  while (queue.length && results.length < maxPages) {
    const url = queue.shift();
    if (seen.has(url)) continue;
    seen.add(url);
    const u = new URL(url);
    const key = pattern(u);
    if ((perPattern.get(key) || 0) >= MAX_PER_PATTERN) continue;
    perPattern.set(key, (perPattern.get(key) || 0) + 1);

    let res, html = '', status = 0, finalUrl = url;
    try {
      res = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 90000 });
      status = res ? res.status() : 0;
      finalUrl = page.url();
      try {
        html = res ? await res.text() : '';
      } catch {
        // 本文がブラウザのキャッシュから消えている場合は、同じセッションで取り直す
        html = await (await context.request.get(finalUrl)).text();
      }
    } catch (e) {
      results.push({ url, status: 'ERROR', error: e.message.split('\n')[0], findings: [] });
      continue;
    }
    // ログイン画面・SSOへ飛ばされた場合はセッション切れ
    if (/\/login_page|member\.nichibenren\.or\.jp/.test(finalUrl)) {
      // CMS は自動で再ログインして続行する。受講者は本番共用 SSO のため自動再ログインはしない
      if (site === 'cms' && relogins < 3) {
        relogins++;
        console.error(`セッション切れのため CMS に再ログインします（${relogins}回目）`);
        await context.close();
        await loginCms(browser);
        await openContext();
        seen.delete(url);
        perPattern.set(key, perPattern.get(key) - 1);
        queue.unshift(url);
        continue;
      }
      console.error(`セッションが切れています（${finalUrl}）。node login.js ${site} を再実行してください`);
      break;
    }

    const findings = [];
    for (const c of CHECKS) {
      const re = new RegExp(c.re.source, c.re.flags.includes('g') ? c.re.flags : c.re.flags + 'g');
      let m, n = 0;
      while ((m = re.exec(html)) && n < 3) { findings.push({ check: c.name, text: snippet(html, m.index) }); n++; }
    }
    results.push({ url, status, findings });
    console.log(`${findings.length ? 'NG' : 'ok'} ${status} ${u.pathname}${u.search}${findings.length ? '  <- ' + [...new Set(findings.map(f => f.check))].join(',') : ''}`);

    // 同一オリジンのリンクを収集
    const hrefs = await page.$$eval('a[href]', (as) => as.map((a) => a.href)).catch(() => []);
    for (const h of hrefs) {
      let v;
      try { v = new URL(h); } catch { continue; }
      v.hash = '';
      if (v.origin !== origin) continue;
      const s = v.toString();
      const target = v.pathname + v.search;
      if (SKIP_COMMON.test(target) || (SKIP_PATH_BY_SITE[site] && SKIP_PATH_BY_SITE[site].test(v.pathname))) { skipped.add(v.pathname); continue; }
      if (!seen.has(s)) queue.push(s);
    }
    await page.waitForTimeout(WAIT_MS);
  }
  // CMS はセッションIDが定期的に更新されるため、最新の Cookie を保存しておく
  await context.storageState({ path: SITES[site].state });
  await browser.close();

  fs.mkdirSync(RESULT_DIR, { recursive: true });
  const stamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
  const out = path.join(RESULT_DIR, `debug-output-${site}-${stamp}.json`);
  fs.writeFileSync(out, JSON.stringify({ site, start, visited: results.length, skipped: [...skipped].sort(), results }, null, 2));
  const ng = results.filter((r) => r.findings.length || r.status === 'ERROR' || r.status >= 500);
  console.log(`\n巡回 ${results.length} 画面 / 検出 ${ng.length} 画面 / スキップしたパス ${skipped.size} 種類`);
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
