// テスト計画「入口以外・HTTP」(F-01〜F-09)と、「重点項目」のうち GET だけで確認できるもの(E-08・E-19・E-46・E-47)の自動実行。
// 読み取り専用: GET のみ。フォーム送信・更新系・ファイルアップロード・メール送信・決済は行わない。
//
// 使い方: node run-xss-http.js [--only F-02,F-03,...] [--probe-php]
//   --only       実行するIDを指定（省略時は下の CHECKS すべて）
//   --probe-php  E-08 で、リポジトリに存在する開発用らしき PHP(*_dev.php 等)にも実際にアクセスする。
//                PHP は開くと実行されるため既定では一覧表示のみ（メール送信等の副作用がありうる）
// 自動化していないもの（計画の該当行は手動）: F-01 のログイン後の遷移・F-04 の Host ヘッダー本体・F-09 のセッションID再生成・
//   E-02/E-03/E-05 の購入・決済画面（開くだけで注文等が作られうる）・E-45 の JSON 画面
const fs = require('fs');
const path = require('path');
const { Session, SSO_BOUNCE } = require('./xss-session');
const { SITES } = require('./lib');
const { debugFindings, classify, domInjection, Recorder } = require('./detect');

const REPO = path.join(__dirname, '../..');
const args = process.argv.slice(2);
const only = args.includes('--only') ? new Set(args[args.indexOf('--only') + 1].split(',')) : null;
const probePhp = args.includes('--probe-php');
const WAIT_MS = 200;

const rec = new Recorder('http');
const sessions = {};
async function sess(key) {
  if (!sessions[key]) {
    const [site, anon] = key.split(':');
    sessions[key] = await new Session(site, { anonymous: anon === 'anon' }).open();
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

// F-04 Host / X-Forwarded-Host（Host 本体の差し替えは curl --resolve 等で手動）
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
    rec.add({ id, verdict: f.length ? 'NG' : 'OK', label: `X-Forwarded-Host ${p}`, status: r.status, findings: f, scope: 'X-Forwarded-Host のみ。Hostヘッダー本体は手動' });
    if (f.length) console.log(`NG ${id} ${p} -> ${f.map((x) => x.kind).join(',')}`);
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
  const DEV = /(_dev|_test|_mst|_old|_bak|_bk|_copy|\d{8})(\.|\/|$)|^\/(test|info|phpinfo)\.php$|(^|\/)test\d*\.php$/i;
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

const CHECKS = { 'F-01': checkF01, 'F-02': checkF02, 'F-03': checkF03, 'F-04': checkF04, 'F-05': checkF05, 'F-06': checkF06, 'F-07': checkF07, 'F-08': checkF08, 'F-09': checkF09, 'E-08': checkE08, 'E-19': checkE19, 'E-47': checkE47, 'D-0005': checkD0005 };

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
