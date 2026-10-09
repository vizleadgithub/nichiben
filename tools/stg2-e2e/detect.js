// 検出ロジックの共通部品（観点1〜3）。check-debug-output.js と run-xss-*.js から使う。
const fs = require('fs');
const path = require('path');
const { RESULT_DIR } = require('./lib');

// 観点3: 確認用出力・PHPエラー等
const CHECKS = [
  { name: 'SQL文', re: /\b(SELECT\s[^<>]{0,300}?\sFROM\s+[`\w]+|INSERT\s+INTO\s+[`\w]+|UPDATE\s+[`\w]+\s+SET\s|DELETE\s+FROM\s+[`\w]+)/i },
  { name: 'var_dump', re: /\barray\(\d+\)\s*\{|\bobject\([\w\\]+\)#\d+\s*\(\d+\)\s*\{|\bstring\(\d+\)\s*"/ },
  { name: 'print_r', re: /\bArray\s*\(\s*\[[^\]]+\]\s*=>/ },
  // <!--[key:値]--> や <!--[123]--> のように値が入っているもの（情報漏えいの可能性あり）
  { name: '確認用コメント(値入り)', re: /<!--\[(?!if\s)(?:[^\]\n]{0,80}:[^\]\n]*|\s*[\d.,\s]*)\]-->/ },
  // <!--[page.php]--> <!--[Test11]--> 等の固定文字列だけの目印（データは出ないが残骸）
  { name: '確認用コメント(固定文字列)', re: /<!--\[(?!if\s)(?=[^\]\n]*[^\d.,\s\]])[^\]:\n]{1,80}\]-->/ },
  { name: 'PHPエラー', re: /<b>(Warning|Notice|Deprecated|Fatal error|Parse error)<\/b>:|A PHP Error was encountered|Unable to load the requested file|(Warning|Notice|Deprecated|Fatal error): .{0,200} on line \d+/ },
  { name: 'サーバーパス', re: /\/srv\/alf\w+|\/var\/www\/html\// },
];

function snippet(text, idx, len = 160) {
  return text.slice(Math.max(0, idx - 40), idx + len).replace(/\s+/g, ' ');
}

// 各 CHECKS を最大3件ずつ検出して返す
function debugFindings(html) {
  const findings = [];
  for (const c of CHECKS) {
    const re = new RegExp(c.re.source, c.re.flags.includes('g') ? c.re.flags : c.re.flags + 'g');
    let m, n = 0;
    while ((m = re.exec(html)) && n < 3) { findings.push({ check: c.name, text: snippet(html, m.index) }); n++; }
  }
  return findings;
}

// ソース中の位置が「どの文脈か」を大まかに分類する（NG候補の判定の目安。最終判断は人が行う）
function classify(html, idx) {
  const before = html.slice(0, idx);
  if (before.lastIndexOf('<script') > before.lastIndexOf('</script')) return 'script内';
  if (before.lastIndexOf('<!--') > before.lastIndexOf('-->')) return 'コメント内';
  if (before.lastIndexOf('<') > before.lastIndexOf('>')) return 'タグ内(属性)';
  return '要素内(テキスト)';
}

// 画面のDOMに、実行されうる形で入り込んだ要素（on*属性・javascript: のリンクに alert( や識別マーカーがある）を探す。
// ng: 識別マーカーを含む、または攻撃文字列そのもの(alert(1))のとき。もともと画面にある alert( は ng=false（要確認）
function domInjection(page, markers) {
  return page.evaluate((ms) => {
    // マーカーの直後に、無害化されていない "・'・<・> が続く場合だけ「危険」とみなす。
    // " (JS文字列エスケープ)・&quot; (HTML実体参照) 等で無害化された状態でも、
    // マーカー文字列自体は部分一致してしまうため、直後の1文字まで見て判定する
    // (2026-10-09発見。cms_videoのタグ候補一覧(set_tag())で、2段階エスケープ済みの値を
    // 誤ってNGと判定していた)
    const hasUnescapedMarker = (v, m) => {
      let i = -1;
      while ((i = v.indexOf(m, i + 1)) !== -1) {
        if (['"', "'", '<', '>'].includes(v[i + m.length])) return true;
      }
      return false;
    };
    const hits = [];
    for (const el of document.querySelectorAll('*')) {
      for (const a of el.attributes) {
        const v = a.value;
        const hasAlert = /alert\(/.test(v);
        const mine = ms.some((m) => hasUnescapedMarker(v, m)) || /^\s*(javascript:)?\s*alert\(1\)\s*;?\s*$/.test(v);
        const dangerousOn = /^on/i.test(a.name) && (hasAlert || mine);
        const jsUrl = /^(href|src|action|formaction|data)$/i.test(a.name) && /^\s*javascript:/i.test(v) && (hasAlert || mine);
        if (dangerousOn || jsUrl) hits.push({ tag: el.tagName.toLowerCase(), attr: a.name, value: v.slice(0, 120), ng: mine });
      }
    }
    return hits.slice(0, 10);
  }, markers).catch(() => []);
}

// 結果の記録。計画のID単位で、最も重い判定をまとめる
const RANK = { NG: 3, '要確認': 2, OK: 1, '対象外': 0 };
class Recorder {
  constructor(kind) {
    this.kind = kind;
    this.entries = [];
    this.stamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
  }
  add(entry) { this.entries.push(entry); }
  worst(id) {
    return this.entries.filter((e) => e.id === id).reduce((w, e) => (RANK[e.verdict] > RANK[w] ? e.verdict : w), '対象外');
  }
  save(extra = {}) {
    fs.mkdirSync(RESULT_DIR, { recursive: true });
    const out = path.join(RESULT_DIR, `xss-${this.kind}-${this.stamp}.json`);
    fs.writeFileSync(out, JSON.stringify({ kind: this.kind, stamp: this.stamp, ...extra, entries: this.entries }, null, 2));
    return out;
  }
}

module.exports = { CHECKS, snippet, debugFindings, classify, domInjection, Recorder, RANK };
