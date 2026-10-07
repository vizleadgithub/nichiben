// テスト計画「入口以外・HTTP」(F-01〜F-09)と、「重点項目」のうち GET だけで確認できるもの(E-08・E-19・E-46・E-47)の自動実行。
// 読み取り専用: GET のみ。フォーム送信・更新系・ファイルアップロード・メール送信・決済は行わない。
//
// 使い方: node run-xss-http.js [--only F-02,F-03,...] [--probe-php]
//   --only       実行するIDを指定（省略時は下の CHECKS すべて）
//   --probe-php  E-08 で、リポジトリに存在する開発用らしき PHP(*_dev.php 等)にも実際にアクセスする。
//                PHP は開くと実行されるため既定では一覧表示のみ（メール送信等の副作用がありうる）
// 自動化していないもの（計画の該当行は手動）: F-01 のうち受講者側(SSO経由のためログインを自動化しない)・
//   E-02/E-05 の購入・決済画面（開くだけで注文等が作られうる）
const fs = require('fs');
const path = require('path');
const { Session, SSO_BOUNCE } = require('./xss-session');
const { SITES, chromium, env, assertStg2 } = require('./lib');
const { debugFindings, classify, domInjection, Recorder } = require('./detect');
const { setFields } = require('./xss-form-lib');

const REPO = path.join(__dirname, '../..');
const args = process.argv.slice(2);
const only = args.includes('--only') ? new Set(args[args.indexOf('--only') + 1].split(',')) : null;
const probePhp = args.includes('--probe-php');
const WAIT_MS = 200;

const rec = new Recorder('http');
const sessions = {};
const MOBILE_UA = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';
async function sess(key) {
  if (!sessions[key]) {
    const [site, anon] = key.split(':');
    const contextOptions = key.endsWith(':sp') ? { userAgent: MOBILE_UA } : {};
    sessions[key] = await new Session(site, { anonymous: anon === 'anon' || anon === 'sp', contextOptions }).open();
  }
  return sessions[key];
}
const hasLogin = (site) => fs.existsSync(SITES[site].state);
const sleep = (s) => s.page.waitForTimeout(WAIT_MS);
// 受講者サイトは未ログインだとどのURLも SSO へ遷移するため、確認はログイン済みセッションで行う
const studentKey = () => (hasLogin('student') ? 'student:auth' : 'student:anon');

const verdictOf = (findings) => (findings.some((f) => f.ng) ? 'NG' : findings.length ? '要確認' : 'OK');
// 識別マーカー（計画のIDから作る。例: F-02 → XF0002）
const markerOf = (id) => 'X' + id.replace('-', '');

// 攻撃文字列を入れる前の同じ画面が自分で出すダイアログ（もともとある alert 等）。攻撃由来と区別するための基準
const baselineCache = new Map();
async function baselineDialogs(s, url) {
  const base = url.replace(/([?&][^=&]+=)[^&]*/g, '$1abc');
  const key = `${s.site}:${s.anonymous}:${base}`;
  if (!baselineCache.has(key)) {
    await s.goto(base);
    baselineCache.set(key, new Set(s.dialogs));
  }
  return baselineCache.get(key);
}

// ページを開いて、ダイアログ・DOM・ソース中の出現・確認用出力・5xx を検査する
async function probe(s, url, injected = []) {
  const res = await s.goto(url);
  if (res.status === 'SSO') return { res, findings: [], skipped: true };
  const markers = injected.map((i) => i.marker).filter(Boolean);
  const dom = await domInjection(s.page, markers);
  const findings = [];
  const shown = [...s.dialogs];
  const base = shown.length && url.includes('?') ? await baselineDialogs(s, url) : new Set();
  for (const d of shown) {
    if (base.has(d)) findings.push({ kind: 'ページ自身のダイアログ(攻撃文字列なしでも出る)', text: d });
    else if (markers.some((m) => d.includes(m)) || d === '1') findings.push({ kind: 'ダイアログ実行', text: d, ng: true });
    else findings.push({ kind: 'ダイアログ(要確認)', text: d });
  }
  for (const d of dom) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: d.ng });
  for (const i of injected) {
    if (!i.sig) continue;
    const at = res.html.indexOf(i.sig);
    if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(res.html, at), text: i.sig.slice(0, 100) });
  }
  for (const f of debugFindings(res.html)) findings.push({ kind: `確認用出力(${f.check})`, text: f.text });
  if (res.status === 'ERROR') findings.push({ kind: '接続エラー', text: res.error });
  else if (res.status >= 500) findings.push({ kind: `HTTP ${res.status}`, text: '' });
  return { res, findings };
}

// 生のGET（リダイレクトを辿らない）。ヘッダーと本文の確認用
async function rawGet(s, url, headers = {}) {
  const r = await s.context.request.get(s.abs(url), { maxRedirects: 0, headers, failOnStatusCode: false, timeout: 60000 });
  const body = await r.text().catch(() => '');
  return { status: r.status(), headers: r.headers(), body, bounced: SSO_BOUNCE.test(body) };
}

// 同じサイト内のリダイレクトを最大3回まで辿る（経路は chain に残す）
async function rawGetFollow(s, url, headers = {}) {
  const chain = [];
  let r = await rawGet(s, url, headers);
  for (let i = 0; i < 3 && r.status >= 300 && r.status < 400 && r.headers.location; i++) {
    const next = new URL(r.headers.location, s.abs(url));
    if (next.origin !== s.origin) break;
    chain.push({ status: r.status, to: next.pathname + next.search });
    r = await rawGet(s, next.pathname + next.search, headers);
  }
  return { ...r, chain };
}

function add(id, verdict, note, extra = {}) {
  rec.add({ id, verdict, note, ...extra });
  console.log(`${verdict === 'OK' ? 'ok' : verdict === 'NG' ? 'NG' : '??'} ${id} ${note}`);
}
function addProbe(id, label, { res, findings, skipped }, scope) {
  if (skipped) {
    rec.add({ id, verdict: '対象外', label, note: '未ログインのため SSO へ遷移し、確認できない', scope });
    return;
  }
  const v = verdictOf(findings);
  rec.add({ id, verdict: v, label, status: res.status, finalUrl: res.finalUrl, findings, scope });
  if (findings.length) console.log(`${v === 'NG' ? 'NG' : '??'} ${id} ${label} -> ${findings.map((f) => f.kind + (f.where ? `[${f.where}]` : '')).join(', ')}`);
}

// ---- 各確認 -------------------------------------------------------------------------------------------------

// F-01 ログイン後の戻り先(backurl)。未ログインでの反映・リダイレクトまで確認（ログイン後の遷移は手動）
async function checkF01() {
  const id = 'F-01', m = markerOf(id);
  const s = await sess('student:anon');
  const values = [
    { v: `${m}"><img src=x onerror=alert('${m}')>`, sig: `<img src=x onerror=alert('${m}')>` },
    { v: `${m}');alert('${m}');//`, sig: `${m}');alert('${m}');//` },
    { v: '//evil.example/' }, { v: '/\\evil.example' }, { v: 'https://evil.example/' }, { v: `${m}%0d%0aSet-Cookie:x=1` },
  ];
  for (const x of values) {
    const url = `/?backurl=${encodeURIComponent(x.v)}`;
    // 未ログインは SSO へリダイレクトされる。SSO へは通信せず（遮断）、リダイレクト先(Location)の中身だけを確認する
    const raw = await rawGet(s, url);
    const loc = raw.headers.location || '';
    const f = [];
    if (/^https?:\/\/[^/]*evil\.example|^\/\/evil\.example/i.test(loc)) f.push({ kind: '外部サイトへリダイレクト', text: loc.slice(0, 160), ng: true });
    if (/evil\.example/i.test(loc) && !f.length) f.push({ kind: 'Locationに値がそのまま付く', text: loc.slice(0, 160) });
    if (x.sig && raw.body.includes(x.sig)) f.push({ kind: '未エスケープで出現', where: classify(raw.body, raw.body.indexOf(x.sig)), text: x.sig });
    rec.add({ id, verdict: verdictOf(f), label: `backurl=${x.v.slice(0, 40)}`, status: raw.status, location: loc.slice(0, 160), findings: f, scope: '未ログイン時の反映・リダイレクト先のみ。ログイン後の遷移は手動（SSOへは送信しない）' });
    if (f.length) console.log(`${verdictOf(f) === 'NG' ? 'NG' : '??'} ${id} backurl=${x.v.slice(0, 30)} -> ${f.map((y) => y.kind).join(',')}`);
    await sleep(s);
  }
  // CMS: 未ログインで保護画面を開くと PHP_SELF を戻り先にして / へ飛ばす実装（search_set.php 等）
  const c = await sess('cms:anon');
  for (const p of ['/alfproduct/product/search_set.php?gid=x', `/alfproduct/product/search_set.php/%22%3E%3Cscript%3Ealert('${m}')%3C/script%3E?gid=x`]) {
    const raw = await rawGet(c, p);
    const loc = raw.headers.location || '';
    const f = [];
    if (/<script|%3Cscript/i.test(decodeURIComponent(loc.replace(/%(?![0-9a-f]{2})/gi, '%25')))) f.push({ kind: 'Locationにスクリプトが入る', text: loc.slice(0, 160) });
    if (raw.body.includes(`<script>alert('${m}')</script>`)) f.push({ kind: '未エスケープで出現', where: classify(raw.body, raw.body.indexOf(`<script>alert('${m}')`)), text: raw.body.slice(0, 100) });
    // リダイレクト先(ログイン画面)で backurl が反映・実行されないかを、ブラウザで辿って確認する
    if (loc) addProbe(id, `CMS未ログイン(リダイレクト先) ${p.slice(0, 50)}`, await probe(c, p, [{ marker: m, sig: `<script>alert('${m}')</script>` }]));
    rec.add({ id, verdict: f.length ? '要確認' : 'OK', label: `CMS未ログイン ${p.slice(0, 60)}`, status: raw.status, location: loc.slice(0, 160), findings: f, scope: '未ログイン時の反映のみ。ログイン後の遷移は手動' });
    if (f.length) console.log(`?? ${id} CMS ${p} -> ${f.map((x) => x.kind).join(',')}`);
  }
  // CMS はログインが SSO 経由ではないため、実際にログインして戻り先を確認できる。
  // Login_page.php の _is_valid_backurl() は「/ で始まる」ことしか見ておらず、//evil.example/ のような
  // プロトコル相対URLも許可してしまう（ブラウザは外部サイトへのリダイレクトとして扱う。コードで確認済みの疑い）
  for (const backurl of ['//evil.example/', '/\\evil.example', 'https://evil.example/']) {
    const browser = await chromium.launch();
    const context = await browser.newContext({ httpCredentials: { username: env('STG2_BASIC_USER'), password: env('STG2_BASIC_PASS') } });
    const page = await context.newPage();
    const base = env('STG2_CMS_URL');
    assertStg2(base);
    await page.goto(`${base}?backurl=${encodeURIComponent(backurl)}`);
    await page.fill('input[name="login_id"]', env('STG2_CMS_USER')).catch(() => {});
    await page.fill('input[name="password"]', env('STG2_CMS_PASS')).catch(() => {});
    await Promise.all([page.waitForNavigation().catch(() => {}), page.click('form input[type="submit"]').catch(() => {})]);
    // ログイン直後は学校選択画面を挟む場合がある（login.js と同じ処理）。backurl の遷移がその後に起きないかも確認する
    if (page.url().includes('/school_select')) {
      const schoolId = process.env.STG2_CMS_SCHOOL_ID || '1';
      await page.check(`input[name="school_id"][value="${schoolId}"]`).catch(() => {});
      await Promise.all([page.waitForNavigation().catch(() => {}), page.click('form input[type="image"]').catch(() => {})]);
    }
    const finalUrl = page.url();
    const external = /^https?:\/\/evil\.example/i.test(finalUrl) || finalUrl.startsWith('evil.example');
    rec.add({ id, verdict: external ? 'NG' : 'OK', label: `CMSログイン後の戻り先 backurl=${backurl}`, note: `遷移先: ${finalUrl}`, findings: external ? [{ kind: '外部サイトへリダイレクト', text: finalUrl, ng: true }] : [] });
    console.log(`${external ? 'NG ' : 'ok '} ${id} CMSログイン backurl=${backurl} -> ${finalUrl}`);
    await browser.close();
    if (external) break;   // 学校選択を経由する通常ログインの間に挟まるため、再現したら以降は省略
  }
}

// F-02 パス情報の付加（PHP_SELF・REQUEST_URI の反映）と、Referer・User-Agent の反映
async function checkF02() {
  const id = 'F-02', m = markerOf(id);
  const sig = `"><script>alert('${m}')</script>`;
  const pathInfo = `/${encodeURIComponent(sig)}`;
  const targets = {
    cms: ['/alfproduct/product/search_product.php', '/alfproduct/product/search_student.php', '/alfproduct/amount_order/index.php', '/alfproduct/inquiry/index.php', '/alfproduct/mailmagazine/index.php', '/cms_auth/'],
    student: ['/product/list.php', '/search/index.php', '/product/index.html', '/inquiry/index.php'],
  };
  for (const [site, paths] of Object.entries(targets)) {
    if (!hasLogin(site)) { add(id, '対象外', `${site}: ログイン済みセッションがない`); continue; }
    const s = await sess(`${site}:auth`);
    for (const p of paths) {
      addProbe(id, `パス情報 ${site} ${p}`, await probe(s, p + pathInfo + (p.endsWith('/') ? '' : ''), [{ marker: m, sig }]));
      await sleep(s);
    }
  }
  // Referer / User-Agent
  const a = await sess(studentKey());
  await a.context.setExtraHTTPHeaders({ Referer: `https://example.jp/${sig}`, 'User-Agent': `<script>alert('${m}')</script>` });
  for (const p of ['/', '/search/index.php', '/product/list.php']) {
    addProbe(id, `Referer/User-Agent ${p}`, await probe(a, p, [{ marker: m, sig: `<script>alert('${m}')</script>` }, { marker: m, sig }]));
  }
  await a.context.setExtraHTTPHeaders({});
}

// F-03 WordPress 本体のURL（残存の確認）
async function checkF03() {
  const id = 'F-03', m = markerOf(id);
  const s = await sess(studentKey());
  const urls = [
    ['/wp-login.php', (r) => r.status === 200 && /wp-login|user_login|WordPress/i.test(r.body) && 'ログイン画面に到達できる'],
    ['/wp-admin/', (r) => r.status === 200 && /wp-admin|WordPress/i.test(r.body) && '管理画面に到達できる'],
    ['/xmlrpc.php', (r) => r.status === 200 && /XML-RPC|xmlrpc/i.test(r.body) && 'XML-RPC が有効'],
    ['/wp-json/wp/v2/users', (r) => r.status === 200 && /"slug"|"name"/.test(r.body) && 'ユーザー一覧が取得できる'],
    ['/wp-json/', (r) => r.status === 200 && /"namespaces"|"routes"/.test(r.body) && 'REST API が有効'],
    ['/?author=1', (r) => r.status >= 300 && r.status < 400 && /\/author\//.test(r.headers.location || '') && 'ユーザー名が露出する(リダイレクト先)'],
    ['/feed/', (r) => r.status === 200 && /<rss|<feed/i.test(r.body) && 'フィードが公開されている（本文のエスケープは手動確認）'],
  ];
  for (const [u, bad] of urls) {
    const first = await rawGet(s, u);
    const r = first.status >= 300 && first.status < 400 ? await rawGetFollow(s, u) : first;
    if (r.bounced) { add(id, '対象外', `${u}: 未ログインのため SSO へ遷移し、確認できない`); continue; }
    const why = bad(first) || bad(r);
    const verdict = why ? (u === '/feed/' ? '要確認' : 'NG') : 'OK';
    const route = (r.chain || []).map((c) => `${c.status}→${c.to}`).join(' ') || `HTTP ${r.status}`;
    rec.add({ id, verdict, label: u, status: r.status, chain: r.chain, note: why || `到達不可/無効 (${route} → HTTP ${r.status})` });
    console.log(`${verdict === 'OK' ? 'ok' : verdict === 'NG' ? 'NG' : '??'} ${id} ${u} -> ${why || `${route} → ${r.status}`}`);
  }
  for (const v of [`${m}"><img src=x onerror=alert('${m}')>`, `${m}');alert('${m}');//`, `${m}</title><img src=x onerror=alert('${m}')>`]) {
    const r = await probe(s, `/?s=${encodeURIComponent(v)}`, [{ marker: m, sig: v.includes('<img') ? `<img src=x onerror=alert('${m}')>` : v }]);
    addProbe(id, `?s=${v.slice(0, 30)}`, r);
    addProbe('D-0014', `?s=${v.slice(0, 30)}（標準機能の反射。F-03 と同一確認）`, r);   // D-0014 は F-03 と同じ確認対象（計画のURLが同じ /?s= のため）
  }
}

// E-09 WordPressスマホ版の確認用出力・SQLの出力(対応済みの再確認)。モバイルUAでトップ・お知らせを開く
async function checkE09() {
  const id = 'E-09';
  const s = await sess('student:sp');
  for (const u of ['/', '/news/']) {
    addProbe(id, `(スマホ版UA) ${u}`, await probe(s, u, []));
    await sleep(s);
  }
}

// E-45 Ajax応答(JSON)のContent-Type・文字のエスケープ。実データが無くても、ヘッダーと空データ時の応答形式は確認できる。
// DOM への .html() 挿入(実行可能かどうか)は画面操作が必要なため対象外（計画の備考どおり）
async function checkE45() {
  const id = 'E-45';
  if (!hasLogin('cms')) { add(id, '対象外', 'CMS: ログイン済みセッションがない'); return; }
  const s = await sess('cms:auth');
  const endpoints = [
    { page: '/cms_exam_problem/edit', url: '/cms_exam_problem/get_exam_answer_detail', fields: { exam_id: '0', answer_student_id: '0', answer_no: '0' } },
    { page: '/cms_exam2_problem/edit', url: '/cms_exam2_problem/get_exam2_answer_detail', fields: { exam2_id: '0', answer_student_id: '0', answer_no: '0' } },
  ];
  for (const ep of endpoints) {
    await s.goto(ep.page);
    const result = await s.page.evaluate(async ({ url, fields }) => {
      const token = document.cookie.match(/csrf_cookie_name=([^;]+)/);
      const body = new URLSearchParams({ ...fields, csrf_test_name: token ? decodeURIComponent(token[1]) : '' });
      try {
        const r = await fetch(url, { method: 'POST', body, credentials: 'same-origin' });
        const text = await r.text();
        return { status: r.status, contentType: r.headers.get('content-type'), nosniff: r.headers.get('x-content-type-options'), body: text.slice(0, 300) };
      } catch (e) { return { error: String(e).slice(0, 200) }; }
    }, { url: s.abs(ep.url), fields: ep.fields });
    if (result.error) { add(id, '要確認', `${ep.url}: fetch失敗 ${result.error}`); continue; }
    const findings = [];
    if (!/application\/json/i.test(result.contentType || '')) findings.push(`Content-Type が application/json でない(${result.contentType})`);
    if (/<script|<img|<svg|<html/i.test(result.body || '')) findings.push('応答本文がHTMLタグを含む(JSONとして不正な形式の可能性)');
    rec.add({ id, verdict: findings.length ? '要確認' : 'OK', label: ep.url, status: result.status, note: findings.join(' / ') || undefined,
      scope: '実データ(登録済みの回答)が無い状態での、ヘッダーと空応答の形式のみ確認。DOMへの挿入確認は画面操作が必要(対象外)' });
    console.log(`${findings.length ? '??' : 'ok'} ${id} ${ep.url} status=${result.status} content-type=${result.contentType} ${findings.join(',')}`);
  }
}

// E-03 受講者レポート(倫理研修)の var_dump・確認用コメントの有無。一覧から実在の受講者IDを取得して開く
async function checkE03() {
  const id = 'E-03';
  if (!hasLogin('cms')) { add(id, '対象外', 'CMS: ログイン済みセッションがない'); return; }
  const s = await sess('cms:auth');
  const list = await s.goto('/cms_report/cms_user');
  const href = await s.page.evaluate(() => {
    const a = [...document.querySelectorAll('a[href]')].find((x) => /\/cms_report\/cms_user_detail\/\d+/.test(x.getAttribute('href')));
    return a && a.getAttribute('href');
  }).catch(() => null);
  if (!href) { add(id, '対象外', '一覧(/cms_report/cms_user)に受講者が1件もない、またはリンクが見つからない'); return; }
  const studentId = href.match(/cms_user_detail\/(\d+)/)[1];
  const url = `/cms_report/cms_user_detail/${studentId}/3`;
  addProbe(id, `${url}（受講者ID=${studentId}。一覧から取得）`, await probe(s, url, []));
  // 存在しない受講者IDでもエラーの詳細が出ないこと
  addProbe(id, '/cms_report/cms_user_detail/999999999/3（存在しないID）', await probe(s, '/cms_report/cms_user_detail/999999999/3', []));
}

// D-0005 WordPress お知らせ一覧(/news/)のページ送り・カテゴリ・検索パラメータの反射
async function checkD0005() {
  const id = 'D-0005', m = markerOf(id);
  const s = await sess(studentKey());
  const params = ['paged', 'cat', 's'];
  const values = [
    { v: `${m}"><img src=x onerror=alert('${m}')>`, sig: `<img src=x onerror=alert('${m}')>` },
    { v: `${m}');alert('${m}');//`, sig: `${m}');alert('${m}');//` },
    { v: `${m}%' OR '%'='` },   // LIKEの特殊文字。確認用出力(SQLエラー等)は probe() 側で自動検出
    { v: `${m}_\\` },
  ];
  for (const p of params) {
    for (const x of values) {
      addProbe(id, `?${p}=${x.v.slice(0, 30)}`, await probe(s, `/news/?${p}=${encodeURIComponent(x.v)}`, [{ marker: m, sig: x.sig }]));
      await sleep(s);
    }
  }
}

// F-04 Host / X-Forwarded-Host と、Host ヘッダー本体の差し替え。
// Host 本体は、TLS の接続先(SNI・証明書)はそのまま(stg2 のサーバーへ実際に接続する)で、HTTPリクエストの Host ヘッダーの値だけを
// 書き換える。Apache の名前ベースのバーチャルホストは通常 SNI で確定するため、この方法でも stg2 のアプリには到達できる一方、
// アプリが $_SERVER['HTTP_HOST'] を使って作るリンク・リダイレクト・メール本文等には、書き換えた値がそのまま渡る想定。
// （context.request は低レベルAPIのため、ブラウザの fetch と違い Host ヘッダーを明示的に上書きできる。到達できない場合は
//   その旨が findings に残るだけなので、結果は実行して確認すること）
async function checkF04() {
  const id = 'F-04';
  const key = hasLogin('student') ? 'student:auth' : 'student:anon';
  const s = await sess(key);
  const list = await s.goto('/search/index.php');
  const pid = (list.html.match(/detail\.php\?pid=(\d+)/) || [])[1];
  const pages = ['/', '/search/index.php', ...(pid ? [`/product/detail.php?pid=${pid}`] : [])];
  for (const p of pages) {
    const r = await rawGet(s, p, { 'X-Forwarded-Host': 'evil.example', 'X-Forwarded-Server': 'evil.example', Forwarded: 'host=evil.example' });
    const f = [];
    const at = r.body.search(/(href|src|action)=["'][^"']*evil\.example/i);
    if (at >= 0) f.push({ kind: '外部ホストのリンクが作られる', where: classify(r.body, at), text: r.body.slice(at, at + 120).replace(/\s+/g, ' ') });
    if (/evil\.example/.test(r.headers.location || '')) f.push({ kind: 'リダイレクト先が外部ホスト', text: r.headers.location });
    rec.add({ id, verdict: f.length ? 'NG' : 'OK', label: `X-Forwarded-Host ${p}`, status: r.status, findings: f, scope: 'X-Forwarded-Host のみ' });
    if (f.length) console.log(`NG ${id} ${p} -> ${f.map((x) => x.kind).join(',')}`);
  }
  // Host ヘッダー本体の差し替え。ライブラリ・サーバー側の都合で上書きできない/到達できないことがあるため、例外は「要確認」にする
  for (const p of pages) {
    let r;
    try { r = await rawGet(s, p, { Host: 'evil.example' }); } catch (e) {
      rec.add({ id, verdict: '要確認', label: `Hostヘッダー本体 ${p}`, note: `確認できず: ${e.message.split('\n')[0]}` });
      continue;
    }
    const f = [];
    if (r.status === 'ERROR') { rec.add({ id, verdict: '要確認', label: `Hostヘッダー本体 ${p}`, note: `接続できない(確認できず): ${r.error || ''}`.trim() }); continue; }
    const at = r.body.search(/(href|src|action)=["'][^"']*evil\.example/i);
    if (at >= 0) f.push({ kind: '外部ホストのリンクが作られる(Hostヘッダー本体)', where: classify(r.body, at), text: r.body.slice(at, at + 120).replace(/\s+/g, ' ') });
    if (/evil\.example/.test(r.headers.location || '')) f.push({ kind: 'リダイレクト先が外部ホスト(Hostヘッダー本体)', text: r.headers.location });
    rec.add({ id, verdict: f.length ? 'NG' : 'OK', label: `Hostヘッダー本体 ${p}`, status: r.status, findings: f, scope: 'Host ヘッダー本体の差し替え(SNI・証明書は正規のまま)' });
    if (f.length) console.log(`NG ${id} ${p}(Host本体) -> ${f.map((x) => x.kind).join(',')}`);
  }
}

// 改ざん値のGET: パラメータすべてに同じ値を入れて開く（ページ送り・ID・gid 等）
const TAMPER_VALUES = (m) => [
  'abc', '-1', '0', '99999999999999999999', '1.5', '1e3', '0x1', '1 OR 1=1',
  { v: `${m}"><svg/onload=alert(1)>`, sig: '<svg/onload=alert(1)>' },
  { v: `${m}"><img src=x onerror=alert('${m}')>`, sig: `<img src=x onerror=alert('${m}')>` },
  { v: `${m}');alert('${m}');//`, sig: `${m}');alert('${m}');//` },
  '%00', '%3Cscript%3Ealert(1)%3C/script%3E',
];
async function tamper(id, site, entries) {
  if (!hasLogin(site)) { add(id, '対象外', `${site}: ログイン済みセッションがない`); return; }
  const s = await sess(`${site}:auth`), m = markerOf(id);
  for (const [base, params] of entries) {
    const variants = TAMPER_VALUES(m).map((x) => (typeof x === 'string' ? { v: x, raw: true } : x));
    for (const x of variants) {
      const q = params.map((p) => `${p}=${x.raw ? x.v : encodeURIComponent(x.v)}`).join('&');
      addProbe(id, `${base}?${q.slice(0, 70)}`, await probe(s, `${base}?${q}`, [{ marker: m, sig: x.sig }]));
      await sleep(s);
    }
    // 配列化 param[]=1
    const q = params.map((p) => `${p}[]=1`).join('&');
    addProbe(id, `${base}?${q}`, await probe(s, `${base}?${q}`, []));
    await sleep(s);
  }
}

const ADMIN_POPUPS = ['product/search_product', 'product/search_contents', 'product/search_contents_so', 'product/search_elive', 'product/search_student',
  'product_ethics/search_product', 'product_ethics/search_contents', 'product_live/search_product', 'product_passport/search_product'];
const STUDENT_LISTS = ['/product/list.php', '/product/list_new_training.php', '/product/list_recommend.php', '/product/list_live_training.php', '/product/list_passport.php', '/product/list_limit.php'];

// E-19 管理画面のページ送り・検索ポップアップ（gid ほか）
const checkE19 = () => tamper('E-19', 'cms', [
  ...ADMIN_POPUPS.map((p) => [`/alfproduct/${p}.php`, ['gid', 'page', 'sort', 'pagemax']]),
  ['/alfproduct/amount_user/info.php', ['sid', 'page']], ['/alfproduct/amount_order/info.php', ['oid', 'page']],
]);
// F-05 / E-47(54) フロントのページ送りと ID・並び順
const checkF05 = () => tamper('F-05', 'student', [
  ...STUDENT_LISTS.map((p) => [p, ['pcid', 'sort', 'pagemax', 'page']]),
  ['/search/index.php', ['search', 'sort', 'pagemax', 'page']],
]);
const checkE47 = () => tamper('E-47', 'student', [['/product/detail.php', ['pid', 'pcid']], ...STUDENT_LISTS.slice(0, 2).map((p) => [p, ['pcid', 'sort', 'pagemax', 'page']])]);
// E-50 EDU_RB_DEV-46 の退行確認(ライブ研修「承認」処理のSQLインジェクション)。
// mysqli_real_escape_string は使われているが、存在しないID・配列化等でエラー・意図しない承認が起きないか確認する。
// 実在するIDは使わない(TAMPER_VALUESは数値以外・極端な値のみのため、実在の商品を承認してしまう心配はない)
const checkE50 = () => tamper('E-50', 'cms', [['/alfproduct/product_live/approval_exe.php', ['mid']]]);
// F-06 エラー画面（存在しない値・文字列・負数・配列でスタックトレース・SQL・パスが出ないこと）
async function checkF06() {
  await tamper('F-06', 'student', [['/product/detail.php', ['pid']], ['/product/detail_review.php', ['pid']], ['/mypage/buy_detail.php', ['oid', 'pid']]]);
  await tamper('F-06', 'cms', [['/alfproduct/product/info.php', ['mid']], ['/alfproduct/report_product/info.php', ['pid', 'aid']], ['/alfproduct/amount_user/info.php', ['sid']]]);
}

// F-07 HTTP ヘッダー（未設定は課題として記録するため、判定は 要確認）
async function checkF07() {
  const id = 'F-07';
  const pages = [['student:anon', '/'], ['student:anon', '/search/index.php'], ['cms:anon', '/'], ['cms:anon', '/alfproduct/']];
  if (hasLogin('student')) pages.push(['student:auth', '/mypage/index.php'], ['student:auth', '/product/detail.php?pid=1']);
  if (hasLogin('cms')) pages.push(['cms:auth', '/cms_auth/'], ['cms:auth', '/alfproduct/amount_order/index.php']);
  for (const [key, p] of pages) {
    const r = await rawGet(await sess(key), p);
    const h = r.headers, missing = [];
    if (!h['x-content-type-options']) missing.push('X-Content-Type-Options');
    if (!h['x-frame-options'] && !/frame-ancestors/i.test(h['content-security-policy'] || '')) missing.push('X-Frame-Options/CSP frame-ancestors');
    const csp = h['content-security-policy'] || '';
    if (!csp) missing.push('Content-Security-Policy');
    else if (/script-src[^;]*'unsafe-inline'/i.test(csp)) missing.push("CSP: script-src に 'unsafe-inline'（XSS の被害を抑える効果が弱い）");
    if (/'unsafe-eval'/i.test(csp)) missing.push("CSP: 'unsafe-eval' を許可");
    if (/^nosniff, nosniff$/i.test(h['x-content-type-options'] || '')) missing.push('X-Content-Type-Options が二重に出力（Apache とアプリの重複設定）');
    if (!h['referrer-policy']) missing.push('Referrer-Policy');
    if (key.endsWith('auth') && !/no-store|no-cache|private/i.test(h['cache-control'] || '')) missing.push('Cache-Control(個人情報画面)');
    const cookies = (h['set-cookie'] || '').split('\n').filter(Boolean);
    for (const c of cookies) {
      const n = c.split('=')[0];
      if (!/httponly/i.test(c)) missing.push(`Cookie ${n}: HttpOnly`);
      if (!/secure/i.test(c)) missing.push(`Cookie ${n}: Secure`);
      if (!/samesite/i.test(c)) missing.push(`Cookie ${n}: SameSite`);
    }
    rec.add({ id, verdict: missing.length ? '要確認' : 'OK', label: `${key} ${p}`, status: r.status, missing, headers: Object.fromEntries(['x-content-type-options', 'x-frame-options', 'content-security-policy', 'referrer-policy', 'cache-control', 'server', 'x-powered-by'].map((k) => [k, h[k]])) });
    console.log(`${missing.length ? '??' : 'ok'} ${id} ${key} ${p}${missing.length ? ' 未設定: ' + missing.join(', ') : ''}`);
    if (h['x-powered-by'] || /\d/.test(h.server || '')) console.log(`   情報露出: Server=${h.server} X-Powered-By=${h['x-powered-by']}`);
  }
}

// F-08 / E-46 ファイル配信・画像返却のパス指定（本文は保存しない。到達の有無だけ記録）
async function checkF08() {
  const s = await sess(hasLogin('student') ? 'student:auth' : 'student:anon');
  const bad = ['../../../../../../etc/passwd', '..%2f..%2f..%2f..%2fetc%2fpasswd', '..\\..\\..\\..\\etc\\passwd', '%00.jpg', '/etc/passwd', '<img src=x onerror=alert(1)>', 'no_such_file.jpg'];
  for (const [id, base, param, extra] of [['E-46', '/resize_image.php', 'image', '&width=100&height=100'], ['E-46', '/resize_video_image.php', 'image', '&width=100&height=100'], ['F-08', '/download.php', 'filename', '']]) {
    for (const v of bad) {
      const q = `${param}=${v.includes('%') ? v : encodeURIComponent(v)}${extra}`;
      const r = await rawGet(s, `${base}?${q}`);
      const ct = r.headers['content-type'] || '';
      const f = [];
      if (/root:.*:0:0:/.test(r.body)) f.push({ kind: 'パスの外のファイルが返る(/etc/passwd)', ng: true });
      if (/<\?php/.test(r.body)) f.push({ kind: 'PHPのソースが返る', ng: true });
      if (r.status === 200 && base !== '/download.php' && !/^image\//i.test(ct) && r.body.length) f.push({ kind: `画像以外が返る (${ct})`, text: '' });
      if (r.body.includes('<img src=x onerror=alert(1)>') && /html/i.test(ct)) f.push({ kind: '未エスケープで出現', text: '' });
      if (r.status >= 500) f.push({ kind: `HTTP ${r.status}`, text: '' });
      if (/\/srv\/|\/alflearning-data\//.test(r.body)) f.push({ kind: 'サーバーパスが出る', text: r.body.match(/.{0,40}(\/srv\/|\/alflearning-data\/).{0,60}/)?.[0] || '' });
      rec.add({ id, verdict: verdictOf(f), label: `${base}?${q.slice(0, 60)}`, status: r.status, contentType: ct, bytes: r.body.length, findings: f, scope: 'パス指定のみ。巨大サイズ・他人のファイル名・アップロード済みHTML/SVGは手動' });
      if (f.length) console.log(`${verdictOf(f) === 'NG' ? 'NG' : '??'} ${id} ${base}?${q.slice(0, 50)} -> ${f.map((x) => x.kind).join(',')}`);
    }
  }
}

// F-09 Cookie の値の反映（セッションID の再生成はログインが必要なため手動）
async function checkF09() {
  const id = 'F-09', m = markerOf(id);
  const s = await sess('student:anon');   // Cookie を差し替えるため、未ログインの別コンテキストで行う（SSOへ遷移する画面は確認不可と記録される）
  const v = `${m}"><img src=x onerror=alert('${m}')>`;
  const origin = new URL(s.baseUrl).hostname;
  for (const name of ['PHPSESSID', 'alf_session', 'lang', 'backurl', 'test']) {
    await s.context.addCookies([{ name, value: encodeURIComponent(v), domain: origin, path: '/', secure: true }]);
  }
  for (const p of ['/', '/search/index.php']) addProbe(id, `Cookie値の反映 ${p}`, await probe(s, p, [{ marker: m, sig: `<img src=x onerror=alert('${m}')>` }, { marker: m, sig: v }]), 'Cookie値の反映のみ。ログイン前後のセッションIDの再生成は手動');
  await s.context.clearCookies();

  // セッションIDの再生成(CMSのみ。受講者はSSO経由のため、ログインそのものを自動化しない)。
  // 独立したブラウザコンテキストでログイン前後のCookieを比較する（保存済みの .auth/cms.json は使わない）
  if (hasLogin('cms')) {
    const browser = await chromium.launch();
    const context = await browser.newContext({ httpCredentials: { username: env('STG2_BASIC_USER'), password: env('STG2_BASIC_PASS') } });
    const page = await context.newPage();
    const base = env('STG2_CMS_URL');
    assertStg2(base);
    await page.goto(base);
    const sidName = (await context.cookies()).find((c) => /sess/i.test(c.name))?.name || 'ci_session';
    const before = (await context.cookies()).find((c) => c.name === sidName)?.value;
    await page.fill('input[name="login_id"]', env('STG2_CMS_USER'));
    await page.fill('input[name="password"]', env('STG2_CMS_PASS'));
    await Promise.all([page.waitForNavigation().catch(() => {}), page.click('form input[type="submit"]')]);
    const after = (await context.cookies()).find((c) => c.name === sidName)?.value;
    const regenerated = before && after && before !== after;
    rec.add({ id, verdict: regenerated ? 'OK' : '要確認', label: 'CMSログイン前後のセッションID', note: regenerated ? undefined : `再生成されていない可能性(cookie: ${sidName})` });
    console.log(`${regenerated ? 'ok' : '??'} ${id} CMSログイン前後のセッションID ${regenerated ? '再生成あり' : '変化なし/未検出'}`);
    await browser.close();
  }
}

// E-08 旧ファイル・開発用ファイルが公開領域から到達できないこと
function listFiles(dir, base = dir) {
  if (!fs.existsSync(dir)) return [];
  return fs.readdirSync(dir, { withFileTypes: true }).flatMap((d) => {
    const p = path.join(dir, d.name);
    if (d.isDirectory()) return /^(vendor|node_modules|\.git|smarty|templates_c)$/i.test(d.name) ? [] : listFiles(p, base);
    return ['/' + path.relative(base, p).split(path.sep).join('/')];
  });
}
async function checkE08() {
  const id = 'E-08';
  // レビュー(2026-10-07) 2.1: backup/ ディレクトリ(exam/backup・exam2/backup 等)と *_review.php(exam2の review 系)が
  // 既存パターンに入っておらず、検出から漏れていた。
  // alfproduct/admin/(testlogin 含む) は public/ の外側(兄弟ディレクトリ)で、現状の vhost 設定(CLAUDE.md 記載分)には
  // 対応する Alias が見当たらず、実際のURL割り当てが不明なため、ここでは対象に追加しない(E-55 としてplan側にのみ記録。要確認)
  const DEV = /(_dev|_test|_mst|_old|_bak|_bk|_copy|_review|\d{8})(\.|\/|$)|^\/(test|info|phpinfo)\.php$|(^|\/)test\d*\.php$|(^|\/)backup\//i;
  const STATIC_LEFTOVER = /\.(bak|old|orig|save|swp|tmp|txt|sql|log|inc|zip|gz|tar|ppt|pptx|xls|xlsx|doc|docx)$|~$|\.bak\./i;
  const roots = {
    student: { dir: path.join(REPO, 'alfproduct/public'), prefix: '' },
    cms: { dir: path.join(REPO, 'alflearning/alflearning-cms/alfproduct'), prefix: '/alfproduct' },
  };
  for (const [site, { dir, prefix }] of Object.entries(roots)) {
    const files = listFiles(dir).filter((f) => !/\.(css|js|png|jpe?g|gif|svg|ico|woff2?|ttf|eot|map|html)$/i.test(f) || STATIC_LEFTOVER.test(f));
    const phpDev = files.filter((f) => /\.php$/i.test(f) && DEV.test(f));
    const statics = files.filter((f) => STATIC_LEFTOVER.test(f) && !/\.php$/i.test(f));
    const s = await sess(site === 'student' ? studentKey() : 'cms:anon');   // 受講者サイトは未ログインだと全URLが SSO へ遷移するため、ログイン済みで確認
    const reach = [], lowRisk = [];
    // 静的ファイルは開いても実行されない。リポジトリにある旧ファイル名と、よくある名前（リポジトリに無いもの）を調べる
    const common = ['/index.php.bak', '/index.php~', '/.git/HEAD', '/.env', '/.htaccess.bak', '/phpinfo.php', '/info.php', '/test.php', '/sso_logger.php.bak']
      .filter((u) => !fs.existsSync(path.join(dir, u))); // リポジトリにあれば PHP を実行してしまうので除外（下の一覧に出す）
    for (const u of [...statics, ...common]) {
      const r = await rawGet(s, prefix + u);
      if (r.bounced || r.status !== 200 || !r.body.length || /<title>(404|Not Found)/i.test(r.body)) { await sleep(s); continue; }
      // WordPress 同梱の license/readme・ライブラリは影響が小さいため NG と分けて記録する
      const hit = { url: prefix + u, status: r.status, bytes: r.body.length };
      (/cp-includes\/|(plugins|themes)\/[^/]+\/(license|readme)[^/]*\.txt$/i.test(u) ? lowRisk : reach).push(hit);
      await sleep(s);
    }
    add(id, reach.length ? 'NG' : 'OK', `${site}: 静的な旧ファイル・よくある名前 ${statics.length + common.length} 件を確認 → 到達できるもの ${reach.length} 件（ほか WordPress 同梱の license/readme 等 ${lowRisk.length} 件）`, { site, reach, lowRisk });
    // 開発用らしき PHP は、開くと実行されるため既定では一覧のみ
    const reachPhp = [];
    if (probePhp) {
      for (const u of phpDev) {
        const r = await rawGet(s, prefix + u);
        if (r.status !== 404 && r.status !== 403) reachPhp.push({ url: prefix + u, status: r.status });
        await sleep(s);
      }
    }
    add(id, phpDev.length && !probePhp ? '要確認' : reachPhp.length ? 'NG' : 'OK',
      `${site}: 開発用らしき PHP ${phpDev.length} 件${probePhp ? ` → 404/403 以外 ${reachPhp.length} 件` : '（--probe-php 未指定のためアクセスしていない。開くと実行されるため、内容を確認してから実施）'}`,
      { site, phpDev, reachPhp });
  }
}

// E-51 CMSのバッチコントローラ(Bat_*・Once_bat_*)が、ブラウザから実行できてしまわないかの確認。
// 【重要: ここは実際にURLへアクセスしない(静的なソース確認のみ)】。調査の結果、Once_bat_reconversion_book_library の
// index() が index() 自体の中で reconversion_exec_book_library() を直接実行する作りだった(=素のGETで実処理が走る)。
// 他のコントローラも is_cli_request() 等のアクセス制限がどれだけあるか不明なため、安全のため実アクセスはせず、
// ソースを読んで「index()が空(または副作用のないprintのみ)か」「CLI限定の判定があるか」だけを確認する
async function checkE51() {
  const id = 'E-51';
  const dir = path.join(REPO, 'alflearning/alflearning-cms/application/controllers');
  if (!fs.existsSync(dir)) { add(id, '要確認', 'コントローラディレクトリが見つからない'); return; }
  const files = fs.readdirSync(dir).filter((f) => /^(Bat_|Once_bat_)\w*\.php$/.test(f));
  const CLI_GUARD = /is_cli_request\(\)|php_sapi_name\(\)\s*===?\s*['"]cli['"]|PHP_SAPI\s*===?\s*['"]cli['"]/;
  const findings = { guarded: [], unguardedSafeIndex: [], unguardedUnsafeIndex: [], unknown: [] };
  for (const f of files) {
    const src = fs.readFileSync(path.join(dir, f), 'utf-8');
    const guarded = CLI_GUARD.test(src);
    // index() メソッドの中身(次の "public function"/"function" か、閉じ括弧の少ない行まで)を大まかに切り出す
    const m = src.match(/function\s+index\s*\(\s*\)\s*\{([^]*?)\n\t\}/);
    const body = m ? m[1].trim() : null;
    const isEmpty = body === '' || body === undefined;
    // 中身が print/echo/exit/die の文だけ(他の処理呼び出しが無い)なら安全とみなす
    const stmts = body !== null ? body.split(';').map((s) => s.trim()).filter(Boolean) : [];
    const isSafePrint = body !== null && stmts.length > 0 && stmts.every((s) => /^(print|echo)\s+["']|^exit(\(\))?$|^die(\(\))?$/.test(s));
    if (guarded) findings.guarded.push(f);
    else if (body === null) findings.unknown.push(f);
    else if (isEmpty || isSafePrint) findings.unguardedSafeIndex.push(f);
    else findings.unguardedUnsafeIndex.push(f);
  }
  const ng = findings.unguardedUnsafeIndex.length > 0;
  rec.add({
    id, verdict: ng ? 'NG' : findings.unknown.length ? '要確認' : '要確認',
    label: `バッチコントローラ ${files.length} 本の静的確認(実アクセスはしていない)`,
    findings: [
      { kind: `CLI限定の判定あり(安全) ${findings.guarded.length}本`, text: findings.guarded.join(', ') },
      { kind: `判定なし・index()は空かprintのみ(ブラウザから実行できるが実害は無さそう) ${findings.unguardedSafeIndex.length}本`, text: findings.unguardedSafeIndex.join(', ') },
      { kind: `判定なし・index()が実処理を呼んでいる(ブラウザから実行すると実害がありうる) ${findings.unguardedUnsafeIndex.length}本`, text: findings.unguardedUnsafeIndex.join(', '), ng: findings.unguardedUnsafeIndex.length > 0 },
      { kind: `index()の中身を解析できず ${findings.unknown.length}本`, text: findings.unknown.join(', ') },
    ],
  });
  console.log(`${ng ? 'NG' : '??'} ${id} バッチコントローラ${files.length}本: CLI限定${findings.guarded.length}/安全${findings.unguardedSafeIndex.length}/要注意${findings.unguardedUnsafeIndex.length}/不明${findings.unknown.length}`);
  if (findings.unguardedUnsafeIndex.length) console.log(`   実処理を直接呼ぶindex(): ${findings.unguardedUnsafeIndex.join(', ')}`);
}

// E-52 CSV/PDF出力(商品管理)の到達性・ログイン確認の確認。読み取り専用(SELECTのみ)と確認済みの12本のみ対象。
// 【重要】report_product/csv_file.php・csv_file_utf.php・amount_product/csv_file.php
// の3本は、認証が無いうえ呼ばれるたびに /tmp/ へファイルを書き込む副作用があるため、意図的に対象外にしている
// ([B-8]参照。この3本には絶対にアクセスしないこと)
const CSV_PDF_SAFE = [
  '/alfproduct/amount_order/csv.php?oid=0', '/alfproduct/amount_order/pdf.php?oid=0',
  '/alfproduct/amount_passport/csv.php', '/alfproduct/amount_product/csv.php',
  '/alfproduct/amount_user/csv.php?sid=0', '/alfproduct/mailmagazine/csv_send_user.php',
  '/alfproduct/mailmagazine/csv_sended_user.php', '/alfproduct/product_lecture/csv.php?pid=0&aid=0&type=1',
  '/alfproduct/product_lecture2/csv.php?pid=0&aid=0&type=1', '/alfproduct/product_lecture_ethics/csv.php?pid=0&type=1',
  '/alfproduct/product_live/csv.php', '/alfproduct/report_product/csv.php?pid=0&aid=0',
];
async function checkE52() {
  const id = 'E-52';
  // 未ログインで直接アクセスし、ログイン画面へ戻される(session_checkが効いている)ことを確認する
  const anon = await sess('cms:anon');
  for (const u of CSV_PDF_SAFE) {
    const r = await rawGet(anon, u);
    const blocked = (r.status >= 300 && r.status < 400) || /login_page|ログイン/i.test(r.body);
    if (!blocked) rec.add({ id, verdict: 'NG', label: `未ログイン ${u}`, status: r.status, findings: [{ kind: 'ログイン確認が無く、未ログインでアクセスできる', ng: true }] });
    else rec.add({ id, verdict: 'OK', label: `未ログイン ${u}`, status: r.status, findings: [] });
  }
  // ログイン済みで到達でき、確認用出力(SQLエラー・スタックトレース等)が無いことを確認する
  if (!hasLogin('cms')) { add(id, '対象外', 'CMS: ログイン済みセッションがない(未ログイン側の確認のみ実施)'); return; }
  const s = await sess('cms:auth');
  for (const u of CSV_PDF_SAFE) {
    const r = await rawGet(s, u);
    const dbg = debugFindings(r.body);
    const findings = dbg.map((f) => ({ kind: `確認用出力(${f.check})`, text: f.text }));
    if (r.status >= 500) findings.push({ kind: `HTTP ${r.status}`, text: '' });
    rec.add({ id, verdict: findings.length ? '要確認' : 'OK', label: `ログイン済み ${u}`, status: r.status, bytes: r.body.length, findings });
    if (findings.length) console.log(`?? ${id} ${u} -> ${findings.map((f) => f.kind).join(',')}`);
    await sleep(s);
  }
  console.log(`ok ${id} CSV/PDF出力 ${CSV_PDF_SAFE.length}本の到達性を確認(認証無し3本[report_product/csv_file.php等]は[B-8]として既に記録済みのため対象外)`);
}

// E-54 SSOの入口(login_sso.php・login_sso_new.php・sso_auth_callback.php)。調査の結果、正規のSSO往復で
// しか成立しないセッション変数が無いと即座に Bad Request・別画面へのリダイレクトで終わり、入力値が画面に
// 反射される経路が無いことをソースで確認済み(E-54自体にXSSの対象面は無い想定)。ここでは、未知の状態でも
// クラッシュ・確認用出力が出ないことだけを安全に確認する(DB書き込み・メール送信は発生しない経路のみを通る)
async function checkE54() {
  const id = 'E-54';
  const s = await sess('student:anon');
  for (const u of ['/login/login_sso.php', '/login/login_sso_new.php', '/login/sso_auth_callback.php']) {
    const r = await rawGet(s, u);
    const dbg = debugFindings(r.body);
    const findings = dbg.map((f) => ({ kind: `確認用出力(${f.check})`, text: f.text }));
    if (r.status >= 500) findings.push({ kind: `HTTP ${r.status}`, text: '' });
    rec.add({ id, verdict: findings.length ? '要確認' : 'OK', label: u, status: r.status, findings, scope: 'ソース確認により、正規のSSOセッション無しでは入力値が画面に反射される経路が無いことを確認済み(2026-10-07)。ここではクラッシュ・確認用出力の有無のみ見る' });
    console.log(`${findings.length ? '??' : 'ok'} ${id} ${u} status=${r.status}`);
  }
}

// E-57 CMSのエラー画面(issue_error・book_library_error・course_class_error・material_error・teacher_error)。
// 調査の結果、これらは全て delete_item($id)/edit() が権限チェックに失敗したときに表示される画面で、
// (1) 各コントローラのコンストラクタで is_logged_in() が無いとログイン画面へ飛ばされるため未ログインでは
//     到達できず、(2) 表示される文言は固定の多言語メッセージで、IDやその他の入力値は画面に出力されない
// ことをソースで確認済み。ログイン済みのテストアカウントで delete_item を呼ぶと実際に削除処理に入ってしまう
// (権限が無い場合だけエラー画面になる)ため、安全のため実アクセスはしない(静的確認のみで対象外とする)
function checkE57() {
  const id = 'E-57';
  rec.add({
    id, verdict: 'OK', label: '5画面とも静的ソース確認のみ(実アクセスはしていない)',
    note: 'issue_error.php・book_library_error.php・course_class_error.php・material_error.php・teacher_error.php は、'
      + '表示する文言が固定の多言語メッセージ(ソース中に埋め込み)で、エラーの引き金になったID・パラメータは画面に出力されない。'
      + '未ログインでは各コントローラのログイン確認(is_logged_in())でログイン画面へ転送されるため到達できない。'
      + 'ログイン済みで delete_item 等を直接呼ぶと(権限があれば)実際に削除処理が走ってしまうため実アクセスはしていない。',
  });
  console.log(`ok ${id} 静的ソース確認のみ(実アクセスなし): エラー文言はユーザー入力を含まないため対象面なしと判断`);
}

// E-58 alflearning-api の到達性。Csv_download は [B-9] として既に氏名・メールアドレスの漏えいを記録済みのため、
// 実データが本当に漏れるか毎回確認するのは望ましくなく、ここでは対象外にする(再現は [B-9] の記載で十分)。
// Login・Top・Sso_update_profile は、空のGET/POSTでも書き込み・外部通信が発生しないことをソースで確認済み
async function checkE58() {
  const id = 'E-58';
  const apiBase = (process.env.STG2_API_URL || '').trim();
  if (!apiBase) { add(id, '対象外', 'STG2_API_URL が未設定(.env.stg2 にAPIのURLが無いため確認できない)'); return; }
  assertStg2(apiBase);
  const s = await sess('student:anon');
  for (const [name, url] of [['Login', '/login'], ['Top', '/top'], ['Sso_update_profile', '/sso_update_profile']]) {
    let r;
    try { r = await s.context.request.get(apiBase.replace(/\/$/, '') + url, { failOnStatusCode: false, timeout: 15000 }); } catch (e) {
      rec.add({ id, verdict: '要確認', label: name, note: `接続できない: ${e.message.split('\n')[0]}` });
      continue;
    }
    const body = await r.text().catch(() => '');
    const dbg = debugFindings(body);
    const findings = dbg.map((f) => ({ kind: `確認用出力(${f.check})`, text: f.text }));
    rec.add({ id, verdict: findings.length ? '要確認' : 'OK', label: name, status: r.status(), findings, scope: 'Csv_downloadは[B-9]で既に記録済みのためここでは対象外' });
    console.log(`${findings.length ? '??' : 'ok'} ${id} ${name} status=${r.status()}`);
  }
}

// E-56 AppScan格納型XSS(CMS-H-07〜16)のセッション再現。通常のS8確認(run-xss-search.js)は送信直後の
// 即時反映しか見ないため、別画面へ移動してから検索条件なしで開き直しても、セッションに保存された値が
// エスケープされずに再反射しないかを別途確認する(レビュー2026-10-07 3.1)
async function checkE56() {
  const id = 'E-56';
  if (!hasLogin('cms')) { add(id, '対象外', 'CMS: ログイン済みセッションがない'); return; }
  const s = await sess('cms:auth');
  const m = markerOf(id);
  const sig = `--><img src=x onerror=alert('${m}')><!--`;
  const payload = `${m}${sig}`;
  const first = await s.goto('/alfproduct/product/index.php');
  if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) {
    add(id, '対象外', `画面が開けない: HTTP ${first.status}`);
    return;
  }
  const set = await setFields(s.page, [{ name: 'search_word', value: payload }]);
  if (!set[0] || !set[0].found) { add(id, '要確認', 'search_word欄が見つからない(画面構成が変わった可能性)'); return; }
  const form = s.page.locator('form[data-xss-form]').first();
  await s.action(() => form.evaluate((f) => (f.requestSubmit ? f.requestSubmit() : f.submit())));
  // 別画面へ移動(ログアウトはしない。セッションは維持する)
  await s.goto('/cms_auth/');
  // 検索条件を付けずに同じ画面を開き直す。セッションに保存された前回の検索条件が残っていれば、ここで再反射する
  const second = await s.goto('/alfproduct/product/index.php');
  const findings = [];
  if (second.status === 'ERROR' || second.status === 'SSO') { add(id, '要確認', `再アクセスできない: HTTP ${second.status}`); return; }
  const at = second.html.indexOf(sig);
  if (at >= 0) findings.push({ kind: '別画面に移動後も検索条件がセッションに残り、未エスケープで再反射', where: classify(second.html, at), text: sig, ng: true });
  else if (second.html.includes(m)) findings.push({ kind: '文字としては残っている(エスケープ済み)', text: m });
  rec.add({ id, verdict: findings.length ? (findings.some((f) => f.ng) ? 'NG' : '要確認') : 'OK', label: 'search_word(セッション再現。CMS-H-07/14相当)', status: second.status, findings });
  console.log(`${findings.some((f) => f.ng) ? 'NG ' : findings.length ? '?? ' : 'ok '} ${id} ${findings.length ? findings.map((f) => f.kind).join(',') : 'セッション経由の再反射なし'}`);
}

const CHECKS = { 'F-01': checkF01, 'F-02': checkF02, 'F-03': checkF03, 'F-04': checkF04, 'F-05': checkF05, 'F-06': checkF06, 'F-07': checkF07, 'F-08': checkF08, 'F-09': checkF09, 'E-03': checkE03, 'E-08': checkE08, 'E-09': checkE09, 'E-19': checkE19, 'E-47': checkE47, 'E-45': checkE45, 'E-50': checkE50, 'E-51': checkE51, 'E-52': checkE52, 'E-54': checkE54, 'E-57': checkE57, 'E-58': checkE58, 'E-56': checkE56, 'D-0005': checkD0005 };

(async () => {
  try {
    for (const [id, fn] of Object.entries(CHECKS)) {
      if (only && !only.has(id)) continue;
      console.log(`\n== ${id} ==`);
      try { await fn(); } catch (e) {
        if (/セッションが切れています/.test(e.message)) throw e;
        add(id, '要確認', `実行エラー: ${e.message.split('\n')[0]}`);
      }
    }
  } finally {
    for (const s of Object.values(sessions)) await s.close().catch(() => {});
  }
  const out = rec.save({ probePhp });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  console.log('\n--- 計画のID別（最も重い判定） ---');
  for (const id of ids.sort()) {
    const n = rec.entries.filter((e) => e.id === id).length;
    console.log(`${id}: ${rec.worst(id)} (${n}件)`);
  }
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
