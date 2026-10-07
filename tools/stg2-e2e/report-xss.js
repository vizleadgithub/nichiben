// run-xss-*.js の結果を、テスト計画のID単位に集計する。OK/NG の記入（xlsx）に転記するための CSV を出力する。
// 判定は「NG候補」。計画の判定基準どおり、NG・要確認は人が再現確認して最終判定する（誤検知の除外・文脈の確認）。
// 使い方: node report-xss.js [結果JSON ...]   （省略時は、種類ごとに最新の xss-*.json を使う）
const fs = require('fs');
const path = require('path');
const { RESULT_DIR } = require('./lib');
const { RANK } = require('./detect');

const planPath = path.join(__dirname, 'test-plan/plan.json');
const plan = fs.existsSync(planPath) ? new Map(JSON.parse(fs.readFileSync(planPath, 'utf-8')).rows.map((r) => [r.id, r])) : new Map();

let files = process.argv.slice(2);
if (!files.length) {
  // 全ての結果を古い順に読み、同じ計画IDは新しい実行で置き換える（一部のIDだけ再実行しても、他のIDの結果が残る）
  files = fs.readdirSync(RESULT_DIR).filter((n) => /^xss-(search-\w+|http|form-\w+|student-exam)-.*\.json$/.test(n)).sort().map((n) => path.join(RESULT_DIR, n));
}
if (!files.length) throw new Error('結果JSONがありません。先に run-xss-search.js / run-xss-http.js を実行してください');

const byId = new Map();
for (const f of files) {
  const run = require(path.resolve(f)).entries;
  // 下調べ(--check-pages)の実行は、攻撃文字列を送っていない（pid のある記録がない）ため、結果として数えない
  if (/xss-form-/.test(f) && !run.some((e) => e.pid)) continue;
  const runKind = path.basename(f).replace(/^xss-(.+?)-\d{4}-.*$/, '$1');
  for (const id of new Set(run.map((e) => e.id))) {
    // 同じ種類(search-cms / search-student / http)の古い結果は、このIDについて置き換える
    byId.set(id, [...(byId.get(id) || []).filter((e) => e.kind !== runKind),
      ...run.filter((e) => e.id === id).map((e) => ({ ...e, kind: runKind, file: path.basename(f) }))]);
  }
}

const summarize = (entries) => {
  const bad = entries.filter((e) => e.verdict === 'NG' || e.verdict === '要確認');
  const lines = bad.slice(0, 6).map((e) => {
    const f = (e.findings || []).map((x) => `${x.kind}${x.where ? `[${x.where}]` : ''}${x.text ? ': ' + String(x.text).slice(0, 80) : ''}`).join(' / ');
    const what = [e.pid && `${e.pid}.${e.idx}`, e.label].filter(Boolean).join(' ');
    return `${e.verdict}: ${what} ${f || e.note || (e.missing || []).join(', ')}`.trim();
  });
  const rest = bad.length - lines.length;
  const scopes = [...new Set(entries.map((e) => e.scope).filter(Boolean))];
  return [...lines, rest > 0 ? `ほか ${rest} 件` : '', scopes.length ? `※自動確認の範囲: ${scopes.join(' / ')}` : '']
    .filter(Boolean).join('\n');
};

const rows = [['ID', '項目', '判定候補', '実行件数', 'NG', '要確認', '備考(自動テストの結果)']];
const total = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
for (const [id, entries] of [...byId].sort(([a], [b]) => a.localeCompare(b))) {
  const worst = entries.reduce((w, e) => (RANK[e.verdict] > RANK[w] ? e.verdict : w), '対象外');
  total[worst]++;
  const p = plan.get(id);
  rows.push([id, p ? `${p.func} / ${p.page} / ${p.item}` : '', worst, entries.length,
    entries.filter((e) => e.verdict === 'NG').length, entries.filter((e) => e.verdict === '要確認').length, summarize(entries)]);
}

const csv = rows.map((r) => r.map((c) => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\r\n');
const out = path.join(RESULT_DIR, `xss-report-${new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19)}.csv`);
fs.writeFileSync(out, '﻿' + csv);   // BOM 付き UTF-8（Excel でそのまま開ける）
console.log(`集計対象: ${files.map((f) => path.basename(f)).join(', ')}`);
console.log(`計画の行 ${byId.size}: NG ${total.NG} / 要確認 ${total['要確認']} / OK ${total.OK} / 対象外 ${total['対象外']}`);
console.log(`CSV: ${out}`);
