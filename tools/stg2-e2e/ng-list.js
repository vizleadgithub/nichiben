// NG候補(NG・要確認)だけを、計画(xlsx由来のplan.json)のURL・期待結果と突き合わせて一覧にする。
// report-xss.js と同じ集計ロジック(同じ種類の結果は新しい実行で置き換える)を使う。
// 使い方: node ng-list.js  →  test-results/ng-list-<日時>.csv
//        node ng-list.js --escape-only  → 「未エスケープで出現」の findings だけに絞る(修正担当への引き継ぎ用。
//        タイムアウト等のハーネス起因のノイズ、日付欄の正常な整形等を除外し、本当に直すべきXSS候補だけにする)
const escapeOnly = process.argv.includes('--escape-only');
const fs = require('fs');
const path = require('path');
const { RESULT_DIR } = require('./lib');
const { RANK } = require('./detect');

const planPath = path.join(__dirname, 'test-plan/plan.json');
const plan = new Map(JSON.parse(fs.readFileSync(planPath, 'utf-8')).rows.map((r) => [r.id, r]));

const files = fs.readdirSync(RESULT_DIR).filter((n) => /^xss-(search-\w+|http|form-\w+|student-exam|s12|data-linked)-.*\.json$/.test(n)).sort().map((n) => path.join(RESULT_DIR, n));

const byId = new Map();
for (const f of files) {
  const run = require(path.resolve(f)).entries;
  if (/xss-form-/.test(f) && !run.some((e) => e.pid)) continue;   // --check-pages は数えない
  const runKind = path.basename(f).replace(/^xss-(.+?)-\d{4}-.*$/, '$1');
  for (const id of new Set(run.map((e) => e.id))) {
    byId.set(id, [...(byId.get(id) || []).filter((e) => e.kind !== runKind),
      ...run.filter((e) => e.id === id).map((e) => ({ ...e, kind: runKind, file: path.basename(f) }))]);
  }
}

// 1件の乖離を1行にする（findings があればそれを、無ければ note を使う）。escapeOnly時は
// 「未エスケープで出現」の finding だけを対象にする(他の finding・note は無視する)
const deviationOf = (e) => {
  const findings = escapeOnly ? (e.findings || []).filter((x) => x.kind === '未エスケープで出現') : (e.findings || []);
  const f = findings.map((x) => `${x.kind}${x.where ? `[${x.where}]` : ''}${x.text ? ': ' + String(x.text).slice(0, 100) : ''}`).join(' / ');
  const what = [e.pid && `${e.pid}.${e.idx}`, e.field].filter(Boolean).join(' ');
  return [what, f || (escapeOnly ? '' : e.note || (e.missing || []).join(', '))].filter(Boolean).join(' ');
};

const rows = [['ID', 'URL', '期待結果', '期待結果との乖離(NG候補の内容)']];
for (const [id, entries] of [...byId].sort(([a], [b]) => a.localeCompare(b))) {
  let bad = entries.filter((e) => e.verdict === 'NG' || e.verdict === '要確認');
  if (escapeOnly) bad = bad.filter((e) => (e.findings || []).some((x) => x.kind === '未エスケープで出現'));
  if (!bad.length) continue;
  const p = plan.get(id);
  const url = p ? p.url : '';
  const expected = p ? p.expected : '';
  // 同じIDでも複数の乖離がありうる(項目違い・payload違い)ため、重複を除いて改行でまとめる
  const uniq = [...new Set(bad.map(deviationOf))].filter(Boolean);
  rows.push([id, url, expected, uniq.join('\n')]);
}

const csv = rows.map((r) => r.map((c) => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\r\n');
const out = path.join(RESULT_DIR, `ng-list${escapeOnly ? '-escape' : ''}-${new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19)}.csv`);
fs.writeFileSync(out, '﻿' + csv);
console.log(`対象ID: ${rows.length - 1} 件`);
console.log(`CSV: ${out}`);
