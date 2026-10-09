// テスト計画の登録系テストセット（S1〜S7・S11）の自動実行【段階A: 入力 → 確認画面まで】。観点1・2・6の一部。
// 入力画面の各項目に、項目ごとの識別マーカー入りの攻撃文字列を入れて「確認」ボタンを押し、確認画面・検証エラー時の再表示を検査する。
//
// 既定ではデータベースへ書き込まない: CMS(CodeIgniter)の確認画面は、検証済みの値をセッションに保存するだけで、DB へ書き込むのは
//   登録(commit)の段階。このスクリプトは登録・更新・削除ボタンを押さない（xss-form-lib.js の submitGuarded で、ボタンのラベル・
//   画像名・フォームの送信先から判定し、確認・検索系以外は送信しない）。
//   登録した値が他の画面にどう表示されるか（S12・F-10）は段階B（書き込みあり。DBダンプが必要）で扱う。
//   --accept-writes を付けると、newdata→confirmの中間段階が無く入力からそのまま登録される画面(cms_issue等)でも、
//   登録・更新系のボタンを探して実際に押す(書き込みを伴う。決済・メール送信を伴う画面は対象外。DBダンプが必要)。
//
// 使い方: node run-xss-form.js [オプション]   (事前に node login.js cms と python test-plan/export-plan.py を実行)
//   --sets S1,S3,S5   対象のテストセット（既定: S1,S2,S3,S4,S5,S6,S7,S9,S10,S11）
//                     S9(ファイルのアップロード)は項目種別が file の欄を対象に、setInputFiles で名前・中身に攻撃文字列を入れる。
//                     S10(パスワード)は、確認画面・エラー時の再表示に平文のまま残っていないかを追加で確認する(通常の往復確認とは逆)
//   --only A-0018,...  計画のIDを指定
//   --url cms_auth/edit,cms_video/edit   対象画面のURLパスを指定（先頭の / は省略可。カンマ区切りで複数）
//   --full            各攻撃文字列の全バリエーションを使う（既定は代表のみ）
//   --isolated        実行中の別の自動テストと並行するとき、別のログイン状態で動かす
//   --check-pages     攻撃文字列は送らず、通常の値で確認画面まで進めるかだけを調べる（form-defaults.json を整える下調べ用）
//   --accept-writes   確認画面が無く直接登録される画面でも、登録ボタンを押して実際に書き込む(段階B相当)。
//                     攻撃文字列ごとに実データが作られる(件数が多い)。実行前にstg2のDBをダンプし、確認後に復元すること
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
const { setFields, fieldForms, fillBaseline, submitGuarded, inspect, resolveRoundtrip, markBestForm } = require('./xss-form-lib');

const WAIT_MS = 300;
const args = process.argv.slice(2);
const opt = (name) => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] : undefined; };
const sets = new Set((opt('--sets') || 'S1,S2,S3,S4,S5,S6,S7,S9,S10,S11').split(','));
const only = opt('--only') ? new Set(opt('--only').split(',')) : null;
// 先頭の / は省略可（Git Bash がパスに変換するため）。カンマ区切りで複数指定できる
const onlyUrls = opt('--url') ? new Set(opt('--url').split(',').map((u) => '/' + u.replace(/^\/+/, ''))) : null;
const full = args.includes('--full');
const isolated = args.includes('--isolated');   // 別のログイン状態(.auth/cms-inspect.json)を使う。他の実行と並行するとき用
const planOnly = args.includes('--plan-only');   // ブラウザを開かず、対象になる行・ならない行（理由つき）を一覧する。実行前の確認用
const checkPages = args.includes('--check-pages');   // 攻撃文字列は送らず、通常の値で確認画面まで進めるかだけを画面ごとに調べる
// newdata→confirmの中間段階が無く、入力からそのまま登録される画面(cms_issue等)向け。実際に登録(書き込み)まで行う(段階B)。
// 決済・メール送信を伴う画面は対象外(settlementはSTUDENT_SKIPで除外済み。他は呼び出し側で個別に確認すること)
const acceptWrites = args.includes('--accept-writes');
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
// 受講者サイトの除外（決済以外は自動化する方針。コードを個別に確認した理由をそれぞれ付ける）。
// - settlement: 決済そのもの（対象外とする方針）
// - exam・exam2・ethic_treaning: 「確認」画面に見える画面(answer_check*.php・index2.php・confirm2.php 等)が、
//   表示に進んだ時点で回答・受講履歴を書き込む(DbConnect::execute() を grep で確認)。CMSの confirm と違い
//   書き込みなしで確認画面に到達する経路がないため、書き込みを許容する設計(段階B)が別途必要。ここでは対象外にする
// - login・logout: 受講者の認証は外部SSO(member.nichibenren.or.jp)経由のみで、ローカルのログイン処理はない
// player(動画プレイヤー)は、2026-10-07 の方針変更により対象外から外した。再生開始で視聴履歴が記録される
// (player/insert_report_user_video_viewed.php)副作用はあるが、専用のテストデータ・テスト用受講者に限定し、
// submitGuarded のガード（確認・検索系以外のボタンは押さない。再生ボタンはどちらにも該当しないため押されない）
// にも守られるため許容する。記録された履歴は、書き込みテスト後のDB復元で元に戻す
const STUDENT_SKIP = /^\/(exam|exam2|ethic_treaning|settlement|login|logout)(\/|$)/i;
// 計画のURLはファイル名からの仮称を含み、現在のリポジトリに実体がないものがある(実画面で要確認)。
// login/*・reminder/*: ログインは上記のとおりSSOのみ。reminder は該当する物理ファイルがコードベースに見当たらない
//   (テンプレートと /reminder/ へのリンクはあるが、処理する PHP が存在しない。機能が無効化されている可能性)
// member/zip.php: 実ファイルは member/input_zip.php（郵便番号→住所のAjax）で、URL名が実画面と異なる
const STUDENT_NOT_FOUND = new Map([
  [/^\/login\//i, 'ログインはSSO経由のみで、該当するローカルの処理がない'],
  [/^\/reminder\//i, '該当する物理ファイルがコードベースに見当たらない（要確認）'],
  [/^\/member\/zip\.php$/i, '実ファイルは member/input_zip.php（Ajax。フォーム項目なし）'],
  [/^\/member\/regist_confirm\.php$/i, 'regist.php の確認画面(同一ファイル)。regist.php の実行で併せて確認される'],
]);
const notFoundReason = (url) => { for (const [re, why] of STUDENT_NOT_FOUND) if (re.test(url)) return why; return null; };
// ソースを読んで確認済みで、反射先(HTML・CSV等への出力)が無いと分かっている項目は対象外にする(実行してもエラーを
// 検出できない上、この画面(cms_exam2_download/edit)はボタンを押すとCSVダウンロード応答を返す設計のため、
// 既定値を整えても(フィルタ対象の講座を1件選んでも)サーバー側の処理が重く安定してタイムアウトする。
// Model_exam2_export::get_exam2_problem_export_data() を確認し、product_name・product_code は
// escape_like_str() 済みのSQLのLIKE条件としてのみ使われ(SQLi対策済み)、画面・CSVのどちらにも値そのものが
// 出力されない(絞り込み条件としてのみ使う)ことを確認したため、XSSの反射経路が無いと判断した(2026-10-08)
const NO_REFLECTION = new Map([
  ['/cms_exam2_download/edit', new Set(['product_name', 'product_code'])],
]);
// 確認・登録画面そのもの（ファイル名で判定）。「regist」は、CMS 側の末尾なし(…/regist)や
// info_user_regist.php・regist_confirm.php のような action 系だけに絞る。member/regist.php・product/add.php の
// ように、1本のスクリプトが act=confirm/complete で分岐する入力画面自体は、ファイル名だけでは判定できないため
// ここでは対象外にしない（submitGuarded が、送信するボタン・フォームの送信先を見て書き込みを防ぐ）
const CONFIRM_SCREEN_URL = /(confirm|confirm_\w+|commit|\w*_regist\w*|regist_\w+|complete)(\.php)?$/;
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

// file 型の欄（S9 の対象以外も含む）に、検証を通す無害なダミーファイルを設定する（必須のアップロード欄があると確認画面へ進めないため）。
// S9 の対象項目には、この後で実際の攻撃文字列(ファイル名・中身)を上書きする。
// 既定はPDFだが、拡張子チェックがある欄（CSV取り込み画面等）は form-defaults.json で項目名に "csv" を指定する
// （例: { "local_file": "csv" }）と、CSV形式のダミーファイルになる
const attrEscape = (s) => String(s).replace(/(["\\])/g, '\\$1');
const BENIGN_FILES = {
  pdf: { name: 'xsstest_baseline.pdf', mimeType: 'application/pdf', buffer: Buffer.from('%PDF-1.4\n% xsstest baseline\n') },
  csv: { name: 'xsstest_baseline.csv', mimeType: 'text/csv', buffer: Buffer.from('xsstest_col1,xsstest_col2\n1,2\n') },
};
async function fillBenignFiles(page, overrides = {}) {
  const names = await page.evaluate(() => [...document.querySelectorAll('input[type=file]')].map((el) => el.name).filter(Boolean));
  for (const n of names) {
    const file = BENIGN_FILES[overrides[n]] || BENIGN_FILES.pdf;
    await page.locator(`[name="${attrEscape(n)}"]`).setInputFiles(file).catch(() => {});
  }
  return names;
}

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
    const why = (url) => (siteName === 'student' && notFoundReason(url) ? `対象外: ページが見つからない（${notFoundReason(url)}）`
      : siteName === 'student' && STUDENT_SKIP.test(url) ? '対象外: 書き込み・副作用を伴わずに到達できない画面'
      : CONFIRM_SCREEN_URL.test(url.split('?')[0]) ? '対象外: 確認・登録画面そのもの（段階B）'
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
  console.log(`${rows.length} 行 / ${byUrl.size} 画面 / セット ${[...sets].join(',')}${acceptWrites ? ' / --accept-writes(確認画面が無い画面は実際に登録します)' : ''}`);

  for (const [url, urlRows] of byUrl) {
   try {
    // 受講者サイトで、計画のURLに実体がない画面（SSOログイン・機能が見当たらない等）
    if (siteName === 'student' && notFoundReason(url)) {
      const why = notFoundReason(url);
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: `ページが見つからない: ${why}`, url });
      console.log(`skip ${url} (${urlRows.length} 行) ページが見つからない: ${why}`);
      continue;
    }
    // 受講者サイトの、確認画面に書き込みなしで到達できない画面は自動では触らない（コメント参照。段階Bで扱う）
    if (siteName === 'student' && STUDENT_SKIP.test(url)) {
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: '確認画面に見える画面が表示の時点で書き込みを行うため、自動では触らない（手動・段階Bで確認）', url });
      console.log(`skip ${url} (${urlRows.length} 行) 書き込み・副作用を伴わずに到達できない画面`);
      continue;
    }
    // 確認・登録画面そのものは、edit 画面から POST されて初めて意味を持つ（直接開いても値がない）。登録(commit)側は段階Bで扱う
    // {ID} を含む URL は、CMS は対象外（edit/{ID} は入口の探索で扱う）。商品管理・受講者サイトは一覧から実在の ID のリンクを探す
    let startUrl = url;
    if (/[{}]/.test(url) && siteName !== 'cms') {
      startUrl = await resolveTemplate(session, url);
      if (startUrl) console.log(`  ${url}: ${startUrl}`);
    }
    if (CONFIRM_SCREEN_URL.test(url.split('?')[0]) || (/[{}]/.test(url) && !startUrl)) {
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: 'edit→confirm の途中・登録後の画面。edit 画面の入力として確認される／登録(commit)を伴うため段階B', url });
      console.log(`skip ${url} (${urlRows.length} 行)`);
      continue;
    }
    const allNames = [...new Set(urlRows.flatMap((r) => r.fields))];
    // 項目名(name)が計画にない行だけの画面は、開く前に対象外にする（副作用のある画面を不要に開かないため）
    if (!allNames.length) {
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: '項目名(name)が計画にない', url });
      console.log(`skip ${url} (${urlRows.length} 行) 項目名(name)が計画にない`);
      continue;
    }
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
    // 商品管理の info.php 等、ID(mid・pid・sid)が必須の詳細画面。{ID} の記載がなくても、項目が見つからなければ
    // 同じ階層の index.php から実在するIDを探す（CMSの edit/{ID} と同じ考え方）。index.php が検索結果一覧では
    // なく検索フォームだけを返す画面(amount_user・product・product_ethics・product_passport等)もあるため、
    // GETでリンクが見つからない場合は search_form を空検索のまま送信してから再度探す(2026-10-08発見)。
    // mid無しで開いても act・csrf_token・mid 自体は空の値で骨組みだけ表示される画面があり、hasTargets()
    // (項目名があるかどうかだけを見る)では「実データが無い空の状態」を検出できなかったため、IDらしき
    // 項目(mid・pid・sid)の値が空でないかも合わせて確認する
    const hasRealId = async () => {
      const vals = await session.page.evaluate((ks) => ks.map((k) => { const el = document.querySelector(`[name="${k}"]`); return el ? el.value : null; }), ['mid', 'pid', 'sid']);
      return vals.some((v) => v && v !== '0');
    };
    if (!(first.status < 400 && await hasTargets() && (!/\/info(_review)?\.php$/.test(url) || await hasRealId())) && siteName === 'product' && /\/info(_review)?\.php$/.test(url)) {
      for (const key of ['mid', 'pid', 'sid']) {
        const dir = url.replace(/[^/]*$/, '');
        let r = await session.goto(`${dir}index.php`);
        if (r.status >= 400) continue;
        const findHref = (k) => session.page.evaluate((kk) => { const a = [...document.querySelectorAll('a[href]')].find((x) => new RegExp(`[?&]${kk}=\\d+`).test(x.getAttribute('href'))); return a && a.href; }, k);
        let href = await findHref(key);
        if (!href) {
          const hasSearchForm = await session.page.evaluate(() => !!document.forms['search_form']).catch(() => false);
          if (hasSearchForm) {
            r = await session.action(() => session.page.evaluate(() => document.forms['search_form'].requestSubmit()));
            href = await findHref(key);
          }
        }
        if (!href) continue;
        const u = new URL(href);
        const r2 = await session.goto(u.pathname.replace(/[^/]*$/, url.split('/').pop()) + u.search);
        if (r2.status < 400 && await hasTargets()) { pageUrl = u.pathname.replace(/[^/]*$/, url.split('/').pop()) + u.search; first = r2; console.log(`  ${url}: 一覧から実在のID(${key})を取得 ${pageUrl}`); break; }
      }
    }
    // product_lecture・product_lecture2・product_lecture_ethics の info_user.php・info_user_import.php は、
    // 単純な index.php → 対象ファイル直行では到達できない。親画面 info.php をまず実在の pid で開き、
    // (1) info_user.php: info.php 上の「管理」リンク(kanri_flg が立つ行のみ。href に info_user.php?...aid=数字 を含む)
    // (2) info_user_import.php: info_user.php 上の「CSV取り込み」ボタン(info_user_import_form を送信)、
    //     ただし product_lecture_ethics には info_user.php 自体が無く、info.php から直接 info_user_import_form を
    //     送信する作りのため aid は不要(2026-10-08発見)
    if (!(first.status < 400 && await hasTargets()) && siteName === 'product' && /\/info_user(_import)?\.php$/.test(url)) {
      const dir = url.replace(/[^/]*$/, '');
      const wantImport = /info_user_import\.php$/.test(url);
      let infoHref = null;
      for (const key of ['pid', 'mid', 'sid']) {
        let r = await session.goto(`${dir}index.php`);
        if (r.status >= 400) continue;
        const findHref = (k) => session.page.evaluate((kk) => { const a = [...document.querySelectorAll('a[href]')].find((x) => x.getAttribute('href').includes('info.php') && new RegExp(`[?&]${kk}=\\d+`).test(x.getAttribute('href'))); return a && a.href; }, k);
        let href = await findHref(key);
        if (!href) {
          const hasSearchForm = await session.page.evaluate(() => !!document.forms['search_form']).catch(() => false);
          if (hasSearchForm) {
            r = await session.action(() => session.page.evaluate(() => document.forms['search_form'].requestSubmit()));
            href = await findHref(key);
          }
        }
        if (href) { infoHref = href; break; }
      }
      if (infoHref) {
        const u = new URL(infoHref);
        const rInfo = await session.goto(u.pathname + u.search);
        if (rInfo.status < 400) {
          const hasImportFormHere = await session.page.evaluate(() => !!document.forms['info_user_import_form']).catch(() => false);
          if (wantImport && hasImportFormHere) {
            // product_lecture_ethics: info.php 自身に CSV 取り込みフォームがある（aid 不要）
            const r3 = await session.action(() => session.page.evaluate(() => document.forms['info_user_import_form'].requestSubmit()));
            if (r3.status < 400 && await hasTargets()) {
              const fu = new URL(r3.finalUrl);
              pageUrl = fu.pathname + fu.search; first = r3; console.log(`  ${url}: 親画面(info.php)のCSV取り込みフォーム経由で取得 ${pageUrl}`);
            }
          } else {
            // product_lecture・product_lecture2: info.php 上の「管理」リンク(aid付き)で info_user.php へ
            const userHref = await session.page.evaluate(() => { const a = [...document.querySelectorAll('a[href]')].find((x) => /info_user\.php\?.*aid=\d+/.test(x.getAttribute('href') || '')); return a && a.href; });
            if (userHref) {
              const uu = new URL(userHref);
              const r3 = await session.goto(uu.pathname + uu.search);
              if (r3.status < 400 && await hasTargets()) {
                if (!wantImport) { pageUrl = uu.pathname + uu.search; first = r3; console.log(`  ${url}: 一覧から実在のID(aid)を取得 ${pageUrl}`); }
                else {
                  const hasImportForm = await session.page.evaluate(() => !!document.forms['info_user_import_form']).catch(() => false);
                  if (hasImportForm) {
                    const r4 = await session.action(() => session.page.evaluate(() => document.forms['info_user_import_form'].requestSubmit()));
                    if (r4.status < 400 && await hasTargets()) {
                      const fu = new URL(r4.finalUrl);
                      pageUrl = fu.pathname + fu.search; first = r4; console.log(`  ${url}: 親画面(info_user.php)のCSV取り込みフォーム経由で取得 ${pageUrl}`);
                    }
                  }
                }
              }
            }
          }
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
    // 対象項目(allNames)を最も多く含むフォームに先に印を付ける。項目名(pid・aid等)が複数の小さな
    // フォームに重複する画面(product_lecture/info_user.php等)で、本体と無関係な小さなフォームを
    // 誤って送信対象にしてしまうことを防ぐ(2026-10-08発見)
    await markBestForm(session.page, allNames);
    const info = new Map((await fieldForms(session.page, allNames)).map((f) => [f.name, f]));

    // 項目ごとのテスト対象を作る
    const targets = [];
    for (const r of urlRows) {
      if (!r.fields.length) { rec.add({ id: r.id, verdict: '対象外', note: '項目名(name)が計画にない', url }); continue; }
      for (const f of r.fields) {
        const i = info.get(f);
        if (NO_REFLECTION.get(url)?.has(f)) rec.add({ id: r.id, field: f, verdict: '対象外', note: 'ソース確認済み: SQLのLIKE条件としてのみ使われ(escape_like_str済み)、画面・CSVに値が出力されないため反射経路が無い(Model_exam2_export::get_exam2_problem_export_data)', url });
        else if (!i || !i.found) rec.add({ id: r.id, field: f, verdict: '対象外', note: '画面に項目がない（コントローラで受け取る値など。入力欄の有無を要確認）', url });
        else if (i.form < 0) rec.add({ id: r.id, field: f, verdict: '対象外', note: 'フォームの外の項目', url });
        else targets.push({ row: r, field: f, type: i.type });
      }
    }
    if (!targets.length) { console.log(`done ${url} (対象項目なし)`); continue; }

    // 通常の値だけで確認画面まで進めるか（進めない画面は、確認画面の検査ができないため form-defaults.json の追加が必要）
    await session.goto(pageUrl);
    await markBestForm(session.page, allNames);   // goto で読み込み直した新しいページにも、先に本体フォームの印を付け直す
    // 検索系の画面(一覧・検索)には「確認」画面自体がなく、isConfirmScreen は常に false になる。
    // 一方で、送信前から常に出ている入力欄の説明文(※半角入力 等)を validationErrors が拾ってしまうため、
    // 送信前後の差分だけを「新しく出たエラー」として扱う
    const errsBefore = await validationErrors(session.page);
    await fillBaseline(session.page, targets[0].field, overrides, true);   // 対象項目も通常の値で埋める
    await fillBenignFiles(session.page, overrides);   // file 型の必須欄があれば、無害なダミーファイルで埋める(S9)
    await setFields(session.page, [{ name: targets[0].field, value: null }]);   // 通常の値のまま（目印だけ付ける）
    const base = await submitGuarded(session, { acceptWrites });
    if (base.refused) {
      for (const t of targets) rec.add({ id: t.row.id, field: t.field, verdict: '対象外', note: base.refused, url });
      pages.push({ url, baseline: '送信できない', note: base.refused });
      console.log(`skip ${url}: ${base.refused}`);
      continue;
    }
    const errsAfter = await validationErrors(session.page);
    const newErrs = errsAfter.filter((e) => !errsBefore.includes(e));
    const isConfirm = await isConfirmScreen(session.page);
    // 確認画面に進んだ場合はもちろん成功。確認画面がない画面(検索・一覧系)は、新しい検証エラーが出ていなければ成功とみなす
    const reached = isConfirm || (!isConfirm && newErrs.length === 0 && !/[{}]/.test(url));
    const errs = reached ? [] : [...newErrs, ...session.dialogs.map((d) => `ダイアログ: ${d}`)];
    pages.push({ url, entry: pageUrl, baseline: reached ? '確認画面まで進める' : `確認画面へ進めない(${new URL(base.finalUrl).pathname})`, errors: errs, hasConfirmScreen: isConfirm });
    if (checkPages) { console.log(`${reached ? 'ok ' : 'NG '} ${url}  ${pages[pages.length - 1].baseline}${errs.length ? `\n      エラー: ${errs.join(' / ')}` : ''}`); continue; }
    if (!reached) console.log(`注意: ${url} は通常の値でも確認画面へ進めない（検証エラーの再表示だけを検査）→ form-defaults.json で必須項目の値を指定してください`);

    for (const t of targets) {
      const payloads = expand(t.row.set, { full });
      for (const p of payloads) {
       try {
        await session.goto(pageUrl);
        await markBestForm(session.page, allNames);
        await fillBaseline(session.page, t.field, overrides);
        await fillBenignFiles(session.page, overrides);   // 対象以外の file 欄。対象が file 型の場合は直後に攻撃文字列で上書きする
        let value, set;
        if (t.type === 'file') {
          // S9: ファイル名、または中身(HTML・SVG)に攻撃文字列を入れる。中身の場合はファイル名自体は無害にする
          const isContent = p.file === 'content';
          value = isContent ? `xsstest_${marker(t.row.id)}${p.ext || '.pdf'}` : fill(p.v, marker(t.row.id));
          const content = isContent ? fill(p.v, marker(t.row.id)) : `%PDF-1.4\n% XSSTEST ${marker(t.row.id)}\n`;
          try {
            await session.page.locator(`[name="${attrEscape(t.field)}"]`).setInputFiles({ name: value, mimeType: p.mime || 'application/pdf', buffer: Buffer.from(content) });
          } catch (e) {
            rec.add({ id: t.row.id, field: t.field, pid: p.pid, idx: p.idx, verdict: '要確認', note: `ファイルを設定できない: ${e.message.split('\n')[0]}`, url });
            continue;
          }
          await session.page.evaluate((n) => { const el = document.querySelector(`[name="${CSS.escape(n)}"]`); if (el && el.form) el.form.setAttribute('data-xss-form', '1'); }, t.field);
          set = [{ name: t.field, found: true, type: 'file' }];
        } else {
          value = fill(p.v, marker(t.row.id));
          set = await setFields(session.page, [{ name: t.field, value }]);
        }
        const injected = [{ id: t.row.id, marker: marker(t.row.id), field: t.field, payload: p, type: set[0].type }];
        const res = await submitGuarded(session, { acceptWrites });
        if (res.refused) { rec.add({ id: t.row.id, field: t.field, verdict: '対象外', note: res.refused, url }); break; }
        const [raw] = await inspect(session, res, injected, baseDialogs);
        let findings = await resolveRoundtrip(session, res, raw);
        // S10(パスワード): 通常の往復確認(値が戻らないと「異なる」と検出する)とは期待が逆なので、その検出は使わず、
        // 代わりに「送信した値がそのまま画面に残っている(=漏れている)」ことを検出する
        if (t.type === 'password') {
          findings = findings.filter((f) => f.kind !== '再表示された値が入力と異なる');
          if (res.html && value && res.html.includes(value)) findings = [...findings, { kind: 'パスワードが画面に平文で残っている(想定外)', text: value.slice(0, 60), ng: true }];
        }
        const ng = findings.some((f) => f.ng);
        const stage = (await isConfirmScreen(session.page)) ? '確認画面' : isConfirm ? '再表示(検証エラー等)' : '検索結果・一覧(確認画面なし)';
        rec.add({
          id: t.row.id, field: t.field, pid: p.pid, idx: p.idx, marker: marker(t.row.id), status: res.status, url, finalUrl: res.finalUrl, stage,
          verdict: ng ? 'NG' : findings.length ? '要確認' : 'OK', findings, jsErrors: session.jsErrors.slice(0, 3),
        });
        if (findings.length) console.log(`${ng ? 'NG ' : '?? '} ${t.row.id} ${t.field} ${p.pid}.${p.idx} [${stage}] ${findings.map((f) => f.kind + (f.where ? `[${f.where}]` : '')).join(', ')}`);
        await session.page.waitForTimeout(WAIT_MS);
       } catch (e) {
        // この1件でレンダラーがクラッシュした、またはページが応答しなくなった(タイムアウト)可能性がある。
        // ブラウザを起動し直して次の攻撃文字列へ進む(1件の失敗で画面全体のテストを失わないため。2026-10-08発見:
        // cms_book_library で複数回再現したがペイロードを変えても別の箇所で再現したため、特定の攻撃文字列が
        // 原因ではなくヘッドレスChromiumの偶発的な不安定さと判明。cms_exam2_download(CSV応答の画面。
        // download イベントの未処理が原因だった。Session側で対応済み)でも別途発生していた)
        const brief = e.message.split('\n')[0].slice(0, 150);
        const recoverable = /crash|timeout.*exceeded/i.test(brief);
        rec.add({ id: t.row.id, field: t.field, pid: p.pid, idx: p.idx, verdict: '要確認', note: `実行エラー${recoverable ? '(ブラウザの復旧後、次の項目へ進めた。偶発的な可能性があるため要確認)' : ''}: ${brief}`, url });
        console.log(`${recoverable ? 'recover' : 'error'} ${t.row.id} ${t.field} ${p.pid}.${p.idx}: ${brief}`);
        if (recoverable) await session.recreatePage();
        else throw e;
       }
      }
    }
    console.log(`done ${url}  (${targets.length} 項目)`);
   } catch (e) {
    if (/セッションが切れています/.test(e.message)) throw e;
    // 画面単位の失敗（タイムアウト等）は、その画面の未記録の項目を「要確認」にして次へ進む。
    // id単位ではなく(id, field)単位で判定する: 1つの行(id)が複数項目(field)を持つ場合(S11「まとめ」行等)、
    // 一部の項目だけ記録された状態でタイムアウトすると、id単位の判定では「記録済み」と誤認し、
    // 残りの項目が「要確認」にすらならず記録が欠落していた(2026-10-08発見。product/info.php(B-0211)で確認)
    const done = new Set(rec.entries.filter((x) => x.url === url).map((x) => `${x.id}::${x.field || ''}`));
    const brief = e.message.split('\n')[0].slice(0, 100);
    for (const r of byUrl.get(url)) {
      for (const f of r.fields.length ? r.fields : ['']) {
        if (!done.has(`${r.id}::${f}`)) rec.add({ id: r.id, field: f || undefined, verdict: '要確認', note: `実行エラー: ${brief}`, url });
      }
    }
    console.log(`error ${url}: ${brief}`);
   }
  }
  // ブラウザがクラッシュした状態でも、ここまで記録した結果は失わずに保存する
  await session.close().catch((e) => console.log(`session.close() エラー(無視): ${e.message.split('\n')[0]}`));
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
  if (acceptWrites) console.log('※--accept-writesにより、確認画面が無い画面では実際にデータを登録しました。確認後、stg2のDBをダンプから復元してください。');
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
