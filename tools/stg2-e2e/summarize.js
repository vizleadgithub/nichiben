// 結果JSONを「指摘ごと」に集計して表示する。使い方: node summarize.js <結果JSON>
const r = require(require('path').resolve(process.argv[2]));
const agg = {};
const errors = [];
for (const x of r.results) {
  const p = new URL(x.url).pathname;
  if (x.status === 'ERROR' || x.status >= 400) errors.push(`${x.status} ${p} ${x.error || ''}`);
  for (const f of x.findings) {
    const m = f.text.match(f.check.startsWith('確認用コメント') ? (f.check.includes('値入り') ? /<!--\[[^\]\n]*:[^\]\n]*\]-->|<!--\[\s*[\d.,\s]*\]-->/ : /<!--\[[^\]:\n]*\]-->/) : /.{0,120}/);
    const k = `${f.check} | ${m ? m[0] : f.text.slice(0, 120)}`;
    (agg[k] = agg[k] || new Set()).add(p);
  }
}
console.log(`[${r.site}] 巡回 ${r.visited} 画面`);
for (const [k, v] of Object.entries(agg).sort()) console.log(`- ${k}  (${v.size}画面: ${[...v].slice(0, 4).join(' ')})`);
if (errors.length) { console.log('エラー/4xx/5xx:'); errors.forEach((e) => console.log('  ' + e)); }
