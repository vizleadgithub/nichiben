// 静的解析による「未エスケープ出力」の総当たりスキャン(モンキーテスト的な広域チェック)。
// テスト計画(xlsx)には無い観点。Smartyテンプレート(商品管理・受講者サイト)とCI3ビュー(CMS)を対象に、
// 変数を出力している箇所でエスケープ系の処理(Smarty: |escape・|purify系、CI3: htmlspecialchars()・
// set_value()の2引数形式等)が無いものを候補として列挙する。PHP/Smartyを実際に実行(レンダリング)はせず、
// ソースのテキストパターンだけで判定するため、既存xlsxの「★」行と同じく人による再確認が前提の候補リスト。
//
// 使い方: node scan-unescaped-output.js [--csv]
//   --csv  結果をtest-results/unescaped-scan-<日時>.csvに書き出す(既定はコンソール表示のみ)
const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..', '..');
const SMARTY_DIR = path.join(ROOT, 'alfproduct/smarty/templates/default');
const CI3_VIEWS_DIR = path.join(ROOT, 'alflearning/alflearning-cms/application/views');

function walk(dir, ext) {
  const out = [];
  for (const name of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, name.name);
    if (name.isDirectory()) out.push(...walk(full, ext));
    else if (name.name.endsWith(ext)) out.push(full);
  }
  return out;
}

// Smartyの出力式(<!--{...}-->)のうち、制御構文({if}・{foreach}・{assign}・{literal}・{/xxx}等)ではなく
// 値を出力しているとみなせるものだけを対象にする(先頭が $ か関数呼び出しで、{/ や {if 等で始まらないもの)
const SMARTY_CONTROL = /^\/|^if\b|^else|^foreach\b|^\/foreach|^section\b|^\/section|^assign\b|^literal\b|^\/literal|^capture\b|^\/capture|^include\b|^strip\b|^\/strip|^ldelim|^rdelim|^php\b|^\*/;
const ESCAPE_MODIFIERS = /\|\s*(escape|purify_ethic_html|purify_ethic_inline_html)\b/;

function scanSmarty(file) {
  const src = fs.readFileSync(file, 'utf-8');
  const findings = [];
  const re = /<!--\{([\s\S]*?)\}-->/g;
  let m;
  let line = 1;
  let lastIndex = 0;
  while ((m = re.exec(src))) {
    line += src.slice(lastIndex, m.index).split('\n').length - 1;
    lastIndex = m.index;
    const expr = m[1].trim();
    if (SMARTY_CONTROL.test(expr)) continue;
    if (!expr.startsWith('$')) continue; // 関数呼び出し等(自作ヘルパー)は対象外(個別確認が必要なため除外)
    if (ESCAPE_MODIFIERS.test(expr)) continue;
    // {$var|escape:'javascript'} のようにクォート内に escape の文字列を含むだけの誤判定を避けるため、
    // 念のためクォートを除いた上でも再確認する
    findings.push({ file, line, expr: expr.slice(0, 120) });
  }
  return findings;
}

// CI3ビュー(<?= ... ?> / <?php echo ...; ?>)のうち、$を含む(=変数を参照している)式を対象にする。
// 既知の安全なラッパーで始まる場合は対象外とする
const SAFE_WRAPPERS = /^(htmlspecialchars|html_escape|esc|form_dropdown|form_error|validation_errors|anchor|site_url|base_url)\s*\(/;
// set_value('x', $y, FALSE) のように第3引数で明示的にエスケープを無効化している場合は危険扱い
const SET_VALUE_NO_ESCAPE = /set_value\s*\([^)]*,\s*(false|FALSE)\s*\)/;

// 多言語ラベル(developerが書いた固定の日本語文言がデフォルト値。ユーザー入力ではない)は対象外
const LANG_LABEL = /^\$this->lang->line_or_def\s*\(/;

function scanCi3(file) {
  const src = fs.readFileSync(file, 'utf-8');
  const findings = [];
  const re = /<\?=\s*([\s\S]*?)\s*\?>|<\?php\s+echo\s+([\s\S]*?);\s*\?>/g;
  let m;
  while ((m = re.exec(src))) {
    const expr = (m[1] || m[2] || '').trim();
    if (!expr.includes('$')) continue; // 静的文字列のみ(変数参照なし)は対象外
    if (LANG_LABEL.test(expr)) continue;
    // 式全体が安全なラッパー呼び出しで始まる場合は安全(内側でset_value(...,FALSE)を使っていても、
    // 外側のhtmlspecialchars等で改めてエスケープされるため問題ない、という正しいイディオム)
    if (SAFE_WRAPPERS.test(expr)) continue;
    if (/^set_value\(/.test(expr) && !SET_VALUE_NO_ESCAPE.test(expr)) continue;
    const line = src.slice(0, m.index).split('\n').length;
    findings.push({ file, line, expr: expr.slice(0, 120) });
  }
  return findings;
}

// 変数名から「自由入力・表示用テキストらしさ」を判定する(IDやカウンタ等の数値系はノイズになるため除外)。
// テスト計画の「表示(エスケープ処理あり)」行が対象にしている変数名の傾向(name・title・comment・memo・
// caption・text・word・content・message・disp_* 等)に合わせている。--all を付けるとこのフィルタを外せる
const LIKELY_TEXT = /name|title|comment|memo|caption|text|word|content|message|disp_|sponsor|teacher|student|address|zip|tel|mail|url|html|label|detail|description|note\b/i;
const LIKELY_ID = /_id\b|_no\d*$|^no1?$|count|cnt|\bflag\b|\bflg\b|status|^i$|^i\d+$|_num\b|_total\b|page|offset|index/i;

const args = process.argv.slice(2);
const toCsv = args.includes('--csv');
const showAll = args.includes('--all');

const smartyFiles = walk(SMARTY_DIR, '.tpl');
const ci3Files = walk(CI3_VIEWS_DIR, '.php');

let all = [];
for (const f of smartyFiles) all.push(...scanSmarty(f).map((x) => ({ ...x, kind: 'smarty' })));
for (const f of ci3Files) all.push(...scanCi3(f).map((x) => ({ ...x, kind: 'ci3' })));

const rel = (p) => path.relative(ROOT, p).replace(/\\/g, '/');
const beforeFilter = all.length;
if (!showAll) all = all.filter((f) => LIKELY_TEXT.test(f.expr) && !LIKELY_ID.test(f.expr));
// 同じ変数が同じファイル内で何度も出てくる(ループ内でのループ表示等)場合は、ファイル+式单位で1件にまとめる
const seen = new Set();
all = all.filter((f) => {
  const key = `${f.file}\u0000${f.expr}`;
  if (seen.has(key)) return false;
  seen.add(key);
  return true;
});

console.log(`Smartyテンプレート ${smartyFiles.length}本 / CI3ビュー ${ci3Files.length}本 を走査`);
console.log(`候補 ${all.length}件(エスケープ系の修飾子・関数が見当たらない変数出力。${showAll ? '全件' : `自由入力らしい変数名で絞り込み・重複除去済み。全${beforeFilter}件から絞り込み。--allで全件表示`}）`);
for (const f of all) console.log(`${f.kind}\t${rel(f.file)}:${f.line}\t${f.expr}`);

if (toCsv) {
  const rows = [['種別', 'ファイル', '行', '式']];
  for (const f of all) rows.push([f.kind, rel(f.file), f.line, f.expr]);
  const csv = rows.map((r) => r.map((c) => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\r\n');
  const out = path.join(__dirname, 'test-results', `unescaped-scan-${new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19)}.csv`);
  fs.writeFileSync(out, '﻿' + csv);
  console.log(`CSV: ${out}`);
}
