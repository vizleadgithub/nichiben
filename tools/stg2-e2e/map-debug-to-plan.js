// check-debug-output.js の巡回結果(debug-output-*.json)を、テスト計画(plan.json)の行IDに対応付けて
// report-xss.js が読める形式(xss-http-*.json互換)で保存する。
// 「表示専用の値(DB・他画面の入力で登録された値)」「表示(エスケープ処理あり、まとめ)」系の行は、
// 個別の攻撃文字列ではなく「この画面に確認用出力(SQL・var_dump・PHPエラー)が出ていないか」を見る観点のため、
// 既存の広域巡回(観点3)の結果をそのまま転用できる。
// あわせて、S12で登録済みのマーカー(test-results/s12-manifest.json)が、巡回で見つかったページに
// 出現していないかも確認する(確認画面(/confirm等)の行に対応)。
//
// 使い方: node map-debug-to-plan.js <debug-output-*.jsonのパス> [site]  (site省略時はcms)
const fs = require('fs');
const path = require('path');
const { RESULT_DIR } = require('./lib');
const { domInjection } = require('./detect');

const inputFile = process.argv[2];
const site = process.argv[3] || 'cms';
if (!inputFile) throw new Error('使い方: node map-debug-to-plan.js <debug-output-*.jsonのパス> [site]');

const plan = require('./test-plan/plan.json');
const debug = JSON.parse(fs.readFileSync(inputFile, 'utf-8'));

const siteLabel = { cms: 'CMS', student: '受講者サイト' }[site];
const planRows = plan.rows.filter((r) => r.site === siteLabel && r.url);

// plan.jsonのurl("/cms_xxx/confirm"や"/alfproduct/product/index.php")を、
// 巡回結果のURL(フルURL)のpathnameと突き合わせる。完全一致のみ(誤爆防止)
const byPath = new Map();
for (const r of planRows) {
  const p = r.url.replace(/\/$/, '') || '/';
  if (!byPath.has(p)) byPath.set(p, []);
  byPath.get(p).push(r);
}

const markers = fs.existsSync('test-results/s12-manifest.json')
  ? JSON.parse(fs.readFileSync('test-results/s12-manifest.json', 'utf-8'))
  : [];

const entries = [];
let matched = 0;
for (const res of debug.results) {
  let u;
  try { u = new URL(res.url); } catch { continue; }
  const p = u.pathname.replace(/\/$/, '') || '/';
  const rows = byPath.get(p);
  if (!rows) continue;
  matched++;
  const html = res.html || '';
  const markerHits = [];
  for (const m of markers) {
    if (m.site !== site) continue;
    const i = html.indexOf(m.marker);
    if (i < 0) continue;
    const after = html[i + m.marker.length];
    if (['"', "'", '<', '>'].includes(after)) markerHits.push({ kind: `S12マーカー(${m.id})が未エスケープで出現`, ng: true });
  }
  for (const r of rows) {
    const findings = [...(res.findings || []).map((f) => ({ kind: `確認用出力(${f.check})`, text: f.text })), ...markerHits];
    if (res.status >= 500) findings.push({ kind: `HTTP ${res.status}`, text: '' });
    const verdict = findings.some((f) => f.ng || /HTTP 5|確認用出力/.test(f.kind)) ? (findings.some((f) => f.ng) ? 'NG' : '要確認') : 'OK';
    entries.push({ id: r.id, verdict, url: res.url, findings, note: findings.length ? findings.map((f) => f.kind).join(',') : '確認用出力・既知マーカーの未エスケープ出現なし' });
  }
}

const stamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
const out = path.join(RESULT_DIR, `xss-http-planmap-${stamp}.json`);
fs.writeFileSync(out, JSON.stringify({ entries }, null, 1));
console.log(`巡回${debug.results.length}画面中、plan.jsonの行に一致: ${matched}画面 / 対応した行: ${entries.length}件`);
const c = { OK: 0, NG: 0, '要確認': 0 };
entries.forEach((e) => c[e.verdict]++);
console.log('OK:', c.OK, '/ NG:', c.NG, '/ 要確認:', c['要確認']);
console.log('結果:', out);
