// テスト計画の登録系テストセット（S1〜S7・S11）の自動実行【段階A: 入力 → 確認画面まで】。観点1・2・6の一部。
// 入力画面の各項目に、項目ごとの識別マーカー入りの攻撃文字列を入れて「確認」ボタンを押し、確認画面・検証エラー時の再表示を検査する。
//
// データベースへは書き込まない: CMS(CodeIgniter)の確認画面は、検証済みの値をセッションに保存するだけで、DB へ書き込むのは
//   登録(commit)の段階。このスクリプトは登録・更新・削除ボタンを押さない（xss-form-lib.js の submitGuarded で、ボタンのラベル・
//   画像名・フォームの送信先から判定し、確認・検索系以外は送信しない）。
//   登録した値が他の画面にどう表示されるか（S12・F-10）は段階B（書き込みあり。DBダンプが必要）で扱う。
//
// 使い方: node run-xss-form.js [オプション]   (事前に node login.js cms と python test-plan/export-plan.py を実行)
//   --sets S1,S3,S5   対象のテストセット（既定: S1,S2,S3,S4,S5,S6,S7,S11）
//   --only A-0018,...  計画のIDを指定
//   --url cms_auth/edit,cms_video/edit   対象画面のURLパスを指定（先頭の / は省略可。カンマ区切りで複数）
//   --full            各攻撃文字列の全バリエーションを使う（既定は代表のみ）
//   --isolated        実行中の別の自動テストと並行するとき、別のログイン状態で動かす
//   --check-pages     攻撃文字列は送らず、通常の値で確認画面まで進めるかだけを調べる（form-defaults.json を整える下調べ用）
//   --limit N         対象の行数を N 行に制限（動作確認用）
//   --site cms|product|student   対象サイト（既定 cms）。product=商品管理(Smarty)、student=受講者サイト（要 node login.js student）。
//                     product は確認ボタンが onclick の formSubmit(…,'confirm') で動く。確認の分岐は DB へ書き込まない（書き込みは 'complete'）
// 画面ごとに検証を通る値が必要なとき（必須項目・日付の形式など）は、test-plan/form-defaults.json に
//   { "/cms_xxx/edit": { "項目名": "値" } } の形で指定する。確認画面まで進めなかった画面は、実行後の一覧に出る。
const fs = require('fs');
const path = require('path');
const { expand, fill } = require('./xss-payloads');
const { Session } = require('./xss-session');
const { Recorder } = require('./detect');
const { setFields, fieldForms, fillBaseline, submitGuarded, inspect, resolveRoundtrip } = require('./xss-form-lib');

const WAIT_MS = 300;
const args = process.argv.slice(2);
const opt = (name) => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] : undefined; };
const sets = new Set((opt('--sets') || 'S1,S2,S3,S4,S5,S6,S7,S11').split(','));
const only = opt('--only') ? new Set(opt('--only').split(',')) : null;
// 先頭の / は省略可（Git Bash がパスに変換するため）。カンマ区切りで複数指定できる
const onlyUrls = opt('--url') ? new Set(opt('--url').split(',').map((u) => '/' + u.replace(/^\/+/, ''))) : null;
const full = args.includes('--full');
const isolated = args.includes('--isolated');   // 別のログイン状態(.auth/cms-inspect.json)を使う。他の実行と並行するとき用
const planOnly = args.includes('--plan-only');   // ブラウザを開かず、対象になる行・ならない行（理由つき）を一覧する。実行前の確認用
const checkPages = args.includes('--check-pages');   // 攻撃文字列は送らず、通常の値で確認画面まで進めるかだけを画面ごとに調べる
const limit = opt('--limit') ? Number(opt('--limit')) : Infinity;
// 対象サイト。cms: CMS(CodeIgniter)  product: 商品管理(Smarty。CMS と同じログイン)  student: 受講者サイト（--sheet product は旧指定）
const PROFILES = {
  cms: { sheet: '管理_CMS', session: 'cms', kind: 'form-cms' },
  product: { sheet: '管理_商品管理', session: 'cms', kind: 'form-product' },
  student: { sheet: 'フロント_受講者サイト', session: 'student', kind: 'form-student' },
};
const siteName = opt('--site') || (opt('--sheet') === 'product' ? 'product' : 'cms');
const profile = PROFILES[siteName];
if (!profile) throw new Error('--site は cms / product / student のどれかを指定してください');
const sheet = profile.sheet;
// 受講者サイトで、開く・確認ボタンを押すだけでも副作用がありうる画面（受験・視聴・決済・会員登録・メール送信）は自動では触らない
const STUDENT_SKIP = /^\/(player|exam|exam2|ethic_treaning|settlement|login|logout|reminder|member|data1|data1_sp|engine1|engine1_sp)(\/|$)|\/(send|regist|complete|download)\w*\.php/i;
const marker = (id) => 'X' + id.replace('-', '');

function loadRows() {
  const planPath = path.join(__dirname, 'test-plan/plan.json');
  if (!fs.existsSync(planPath)) throw new Error('先に python test-plan/export-plan.py を実行してください');
  const { rows } = JSON.parse(fs.readFileSync(planPath, 'utf-8'));
  const out = [];
  for (const r of rows) {
    if (r.sheet !== sheet || !sets.has(r.set) || (only && !only.has(r.id)) || (onlyUrls && !onlyUrls.has(r.url))) continue;
    // hidden項目(まとめ) は、備考の「対象: a, b」を項目に展開する。CSRFトークンは改ざんすると拒否されるだけなので除く
    const fields = r.set === 'S11' && !r.field
      ? ((r.note.match(/対象:\s*([^\n]+)/) || [])[1] || '').split(/[,、]\s*/).map((s) => s.trim()).filter((s) => s && !/csrf|token/i.test(s))
      : [r.field].filter(Boolean);
    out.push({ ...r, fields });
  }
  return out.slice(0, limit);
}

// 検証エラーのメッセージ（確認画面へ進めない画面で、form-defaults.json に何を足すかの手がかり）
const validationErrors = (page) => page.evaluate(() => [...new Set([...document.querySelectorAll('.error, .err, .errors, [class*=error], [class*=err_], font[color=red], [style*="color:red"], [style*="color: red"], [style*="background-color:red"]')]
  .map((e) => e.textContent.replace(/\s+/g, ' ').trim()).filter(Boolean))].slice(0, 6));

// 確認画面かどうか: 登録(commit)へ進むフォームがある画面。検証エラー時は同じ URL(…/confirm)で入力画面が再表示されるため、URL では判別できない
const isConfirmScreen = (page) => page.evaluate(() => {
  if ([...document.forms].some((f) => /commit|regist|send\.php|complete/i.test(f.getAttribute('action') || ''))) return true;
  // 商品管理など: 登録ボタンが onclick の引数（'complete'・'regist'）で動く画面
  return [...document.querySelectorAll('[onclick], a[href^="javascript:"]')].some((e) => /['"](complete|regist\w*|commit)['"]/i.test(`${e.getAttribute('onclick') || ''} ${e.getAttribute('href') || ''}`));
});

const defaultsPath = path.join(__dirname, 'test-plan/form-defaults.json');
const overridesFor = (url) => (fs.existsSync(defaultsPath) ? (JSON.parse(fs.readFileSync(defaultsPath, 'utf-8'))[url] || {}) : {});

// {ID} を含む URL（例: /alfproduct/product/info.php?mid={ID}）の ID を、同じ階層の一覧画面から探す。見つからなければ null
async function resolveTemplate(session, url) {
  if (/[・\s]|\.\.\./.test(url)) return null;
  const [pathPart, query = ''] = url.split('?');
  const key = (query.match(/([a-z_0-9]+)=\{[^}]*\}/i) || [])[1];
  if (!key) return null;
  const dir = pathPart.replace(/[^/]*$/, '');
  const file = pathPart.slice(dir.length);
  const lists = siteName === 'student' ? [`${dir}index.php`, '/product/list.php', '/search/index.php', '/mypage/index.php'] : [`${dir}index.php`, dir];
  for (const l of lists) {
    const r = await session.goto(l);
    if (r.status === 'ERROR' || r.status === 'SSO' || r.status >= 400) continue;
    const href = await session.page.evaluate(([f, k]) => {
      const a = [...document.querySelectorAll('a[href]')].find((x) => x.getAttribute('href').includes(f) && new RegExp(`[?&]${k}=\d+`).test(x.getAttribute('href')));
      return a && a.href;
    }, [file, key]);
    if (href) { const u = new URL(href); return u.pathname + u.search; }
  }
  return null;
}

(async () => {
  const rows = loadRows();
  if (!rows.length) throw new Error('対象の行がありません（plan.json・--sets・--only を確認してください）');
  if (planOnly) {
    // 画面ごとの扱いを、実行時の判定（STUDENT_SKIP・確認画面・{ID}）と同じ規則で出す。項目が画面にあるかは、実行しないとわからない
    const why = (url) => (siteName === 'student' && STUDENT_SKIP.test(url) ? '対象外: 副作用がありうる画面'
      : /(confirm|confirm_\w+|commit|regist\w*|complete)(\.php)?$/.test(url.split('?')[0]) ? '対象外: 確認・登録画面そのもの（段階B）'
      : /[{}]/.test(url) ? (siteName === 'cms' ? '対象外: {ID} を含む（CMS）' : '実行: 一覧から実在のIDを探して開く')
      : '実行');
    const g = new Map();
    for (const r of rows) { const k = `${why(r.url)}`; g.set(k, [...(g.get(k) || []), r]); }
    console.log(`[${siteName}] 対象 ${rows.length} 行 / ${new Set(rows.map((r) => r.url)).size} 画面`);
    for (const [k, rs] of [...g].sort()) console.log(`  ${k}: ${rs.length} 行 / ${new Set(rs.map((r) => r.url)).size} 画面${k === '実行' ? '' : '  例: ' + [...new Set(rs.map((r) => r.url))].slice(0, 3).join(' , ')}`);
    const noField = rows.filter((r) => !r.fields.length);
    console.log(`  項目名(name)が計画にない行: ${noField.length}（${noField.slice(0, 5).map((r) => r.id).join(',')}）`);
    return;
  }
  const rec = new Recorder(checkPages ? 'form-check' : profile.kind);   // 下調べの結果は集計(report-xss.js)に含めない
  let statePath = null;
  if (isolated) { const insp = require('./inspect-form'); await insp.login(); statePath = insp.STATE; }
  const session = await new Session(profile.session, { statePath: profile.session === 'cms' ? statePath : null }).open();
  const byUrl = new Map();
  for (const r of rows) {
    if (!byUrl.has(r.url)) byUrl.set(r.url, []);
    byUrl.get(r.url).push(r);
  }
  const pages = [];
  console.log(`${rows.length} 行 / ${byUrl.size} 画面 / セット ${[...sets].join(',')}`);

  for (const [url, urlRows] of byUrl) {
   try {
    // 受講者サイトの、副作用がありうる画面は自動では触らない
    if (siteName === 'student' && STUDENT_SKIP.test(url)) {
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: '受験・視聴・決済・会員登録・メール送信などの副作用がありうる画面のため、自動では触らない（手動で確認）', url });
      console.log(`skip ${url} (${urlRows.length} 行) 副作用がありうる画面`);
      continue;
    }
    // 確認・登録画面そのものは、edit 画面から POST されて初めて意味を持つ（直接開いても値がない）。登録(commit)側は段階Bで扱う
    // {ID} を含む URL は、CMS は対象外（edit/{ID} は入口の探索で扱う）。商品管理・受講者サイトは一覧から実在の ID のリンクを探す
    let startUrl = url;
    if (/[{}]/.test(url) && siteName !== 'cms') {
      startUrl = await resolveTemplate(session, url);
      if (startUrl) console.log(`  ${url}: ${startUrl}`);
    }
    if (/(confirm|confirm_\w+|commit|regist\w*|complete)(\.php)?$/.test(url.split('?')[0]) || (/[{}]/.test(url) && !startUrl)) {
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: 'edit→confirm の途中・登録後の画面。edit 画面の入力として確認される／登録(commit)を伴うため段階B', url });
      console.log(`skip ${url} (${urlRows.length} 行)`);
      continue;
    }
    const allNames = [...new Set(urlRows.flatMap((r) => r.fields))];
    const hasTargets = async () => (await fieldForms(session.page, allNames)).some((f) => f.found);
    // 入口の探索。edit を直接開いても入力画面にならない画面（「セッションエラー」・一覧へ戻される）があるため、順に試す:
    //  1) そのまま開く  2) /…/newdata（新規登録。セッションの初期化と入力画面の表示だけで、DB へは書き込まない）
    //  3) 一覧の最初の edit/{ID}（既存レコードの編集画面）。確認画面までは何も書き込まないため、既存レコードを使っても変更されない
    let pageUrl = startUrl;
    let first = await session.goto(startUrl);
    if (!(first.status < 400 && await hasTargets()) && /\/edit$/.test(url)) {
      const nd = url.replace(/edit$/, 'newdata');
      const r = await session.goto(nd);
      if (r.status < 400 && await hasTargets()) { pageUrl = nd; first = r; console.log(`  ${url}: 新規登録の入口 ${nd}`); }
      else {
        await session.goto(url.replace(/edit$/, ''));
        const href = await session.page.evaluate(() => { const a = [...document.querySelectorAll('a[href]')].find((x) => /\/edit\/\d+/.test(x.getAttribute('href'))); return a && a.href; });
        if (href) {
          const r2 = await session.goto(new URL(href).pathname);
          if (r2.status < 400 && await hasTargets()) { pageUrl = new URL(href).pathname; first = r2; console.log(`  ${url}: 既存レコードの編集画面 ${pageUrl}`); }
        }
      }
    }
    if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) {
      const why = `HTTP ${first.status} ${first.error || ''}`.trim();
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: `画面が開けない: ${why}`, url });
      console.log(`skip ${why} ${url}`);
      continue;
    }
    const baseDialogs = new Set(session.dialogs);
    const overrides = overridesFor(url);
    const info = new Map((await fieldForms(session.page, allNames)).map((f) => [f.name, f]));

    // 項目ごとのテスト対象を作る
    const targets = [];
    for (const r of urlRows) {
      if (!r.fields.length) { rec.add({ id: r.id, verdict: '対象外', note: '項目名(name)が計画にない', url }); continue; }
      for (const f of r.fields) {
        const i = info.get(f);
        if (!i || !i.found) rec.add({ id: r.id, field: f, verdict: '対象外', note: '画面に項目がない（コントローラで受け取る値など。入力欄の有無を要確認）', url });
        else if (i.form < 0) rec.add({ id: r.id, field: f, verdict: '対象外', note: 'フォームの外の項目', url });
        else targets.push({ row: r, field: f, type: i.type });
      }
    }
    if (!targets.length) { console.log(`done ${url} (対象項目なし)`); continue; }

    // 通常の値だけで確認画面まで進めるか（進めない画面は、確認画面の検査ができないため form-defaults.json の追加が必要）
    await session.goto(pageUrl);
    await fillBaseline(session.page, targets[0].field, overrides, true);   // 対象項目も通常の値で埋める
    await setFields(session.page, [{ name: targets[0].field, value: null }]);   // 通常の値のまま（目印だけ付ける）
    const base = await submitGuarded(session);
    if (base.refused) {
      for (const t of targets) rec.add({ id: t.row.id, field: t.field, verdict: '対象外', note: base.refused, url });
      pages.push({ url, baseline: '送信できない', note: base.refused });
      console.log(`skip ${url}: ${base.refused}`);
      continue;
    }
    const reached = await isConfirmScreen(session.page);
    // 画面の検証エラーに加え、JavaScript の入力チェックがダイアログで送信を止めた場合はそのメッセージも手がかりとして残す
    const errs = reached ? [] : [...await validationErrors(session.page), ...session.dialogs.map((d) => `ダイアログ: ${d}`)];
    pages.push({ url, entry: pageUrl, baseline: reached ? '確認画面まで進める' : `確認画面へ進めない(${new URL(base.finalUrl).pathname})`, errors: errs });
    if (checkPages) { console.log(`${reached ? 'ok ' : 'NG '} ${url}  ${pages[pages.length - 1].baseline}${errs.length ? `\n      エラー: ${errs.join(' / ')}` : ''}`); continue; }
    if (!reached) console.log(`注意: ${url} は通常の値でも確認画面へ進めない（検証エラーの再表示だけを検査）→ form-defaults.json で必須項目の値を指定してください`);

    for (const t of targets) {
      const payloads = expand(t.row.set, { full });
      for (const p of payloads) {
        await session.goto(pageUrl);
        await fillBaseline(session.page, t.field, overrides);
        const value = fill(p.v, marker(t.row.id));
        const set = await setFields(session.page, [{ name: t.field, value }]);
        const injected = [{ id: t.row.id, marker: marker(t.row.id), field: t.field, payload: p, type: set[0].type }];
        const res = await submitGuarded(session);
        if (res.refused) { rec.add({ id: t.row.id, field: t.field, verdict: '対象外', note: res.refused, url }); break; }
        const [raw] = await inspect(session, res, injected, baseDialogs);
        const findings = await resolveRoundtrip(session, res, raw);
        const ng = findings.some((f) => f.ng);
        const stage = (await isConfirmScreen(session.page)) ? '確認画面' : '再表示(検証エラー等)';
        rec.add({
          id: t.row.id, field: t.field, pid: p.pid, idx: p.idx, marker: marker(t.row.id), status: res.status, url, finalUrl: res.finalUrl, stage,
          verdict: ng ? 'NG' : findings.length ? '要確認' : 'OK', findings, jsErrors: session.jsErrors.slice(0, 3),
        });
        if (findings.length) console.log(`${ng ? 'NG ' : '?? '} ${t.row.id} ${t.field} ${p.pid}.${p.idx} [${stage}] ${findings.map((f) => f.kind + (f.where ? `[${f.where}]` : '')).join(', ')}`);
        await session.page.waitForTimeout(WAIT_MS);
      }
    }
    console.log(`done ${url}  (${targets.length} 項目)`);
   } catch (e) {
    if (/セッションが切れています/.test(e.message)) throw e;
    // 画面単位の失敗（タイムアウト等）は、その画面の未記録の行を「要確認」にして次へ進む
    const done = new Set(rec.entries.filter((x) => x.url === url).map((x) => x.id));
    const brief = e.message.split('\n')[0].slice(0, 100);
    for (const r of byUrl.get(url)) if (!done.has(r.id)) rec.add({ id: r.id, verdict: '要確認', note: `実行エラー: ${brief}`, url });
    console.log(`error ${url}: ${brief}`);
   }
  }
  await session.close();
  const out = rec.save({ sets: [...sets], full, sheet, pages });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  const c = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
  ids.forEach((id) => c[rec.worst(id)]++);
  console.log(`\n計画の行 ${ids.length}: NG ${c.NG} / 要確認 ${c['要確認']} / OK ${c.OK} / 対象外 ${c['対象外']}`);
  const stuck = pages.filter((p) => !/確認画面まで進める/.test(p.baseline));
  if (stuck.length) {
    console.log(`確認画面まで進めなかった画面 ${stuck.length} / ${pages.length}:`);
    stuck.forEach((p) => console.log(`  ${p.url}  ${p.baseline}${(p.errors || []).length ? `\n      ${p.errors.join(' / ')}` : ''}`));
  }
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
