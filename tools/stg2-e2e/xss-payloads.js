// 攻撃文字列の実行用定義。正本は secure_report/XSS対応_脆弱性テスト計画_*.xlsx の「付録_攻撃文字列」(P01〜P20)。
// {m} は項目ごとの識別マーカー（例: XB0012）。sig は「未エスケープで画面のソースに出た」ことを示す文字列で、
// 付いているものは、レスポンス中にそのまま出現したら NG 候補とする。
// quick: 既定で使う代表（1 P-ID につき 1 文字列）。full: --full 指定時に全バリエーションを使う。
const P = (id, name, variants, opts = {}) => ({ id, name, variants, ...opts });

const PAYLOADS = {
  P01: P('P01', '基本のスクリプト', [
    { v: "<script>alert('{m}')</script>", sig: "<script>alert('{m}')</script>" },
  ]),
  P02: P('P02', '属性からの脱出(二重引用符)', [
    { v: "{m}\"><img src=x onerror=alert('{m}')>", sig: "<img src=x onerror=alert('{m}')>" },
  ]),
  P03: P('P03', '属性からの脱出(単一引用符)', [
    { v: "{m}'><svg/onload=alert('{m}')>", sig: "<svg/onload=alert('{m}')>" },
  ]),
  P04: P('P04', '属性の追加(イベント属性)', [
    { v: "{m}\" onmouseover=\"alert('{m}')\" x=\"", sig: "\" onmouseover=\"alert('{m}')\"" },
    { v: "{m}' onfocus='alert(1)' autofocus='", sig: "' onfocus='alert(1)'" },
  ]),
  P05: P('P05', 'JavaScript文字列からの脱出', [
    { v: "{m}');alert('{m}');//", sig: "{m}');alert('{m}');//" },
    { v: "{m}\";alert('{m}');//", sig: "{m}\";alert('{m}');//" },
    { v: "{m}</script><script>alert('{m}')</script>", sig: "</script><script>alert('{m}')</script>" },
  ]),
  P06: P('P06', 'HTMLコメントからの脱出', [
    { v: "{m}--><img src=x onerror=alert('{m}')><!--", sig: "--><img src=x onerror=alert('{m}')>" },
    { v: '{m}--!><svg/onload=alert(1)>', sig: '--!><svg/onload=alert(1)>' },
  ]),
  P07: P('P07', 'コンテナ要素からの脱出', [
    { v: "{m}</textarea><img src=x onerror=alert('{m}')>", sig: "</textarea><img src=x onerror=alert('{m}')>" },
    { v: "{m}</title><img src=x onerror=alert('{m}')>", sig: "</title><img src=x onerror=alert('{m}')>" },
    { v: "{m}</select></option><img src=x onerror=alert('{m}')>", sig: "</select></option><img src=x onerror=alert('{m}')>" },
    { v: "{m}</style><img src=x onerror=alert('{m}')>", sig: "</style><img src=x onerror=alert('{m}')>" },
    { v: "{m}</a></td></tr></table><img src=x onerror=alert('{m}')>", sig: "</table><img src=x onerror=alert('{m}')>" },
  ]),
  P09: P('P09', '記号の見え方(往復)', [
    { v: '{m}<b>M&A "引用" \'x\' &amp; &lt; &nbsp; ＜全角＞ \\ % _ ~ # ?=&a=b', sig: '<b>M&A', roundtrip: true },
  ]),
  P10: P('P10', '二重エスケープ検出', [
    { v: '{m}&lt;script&gt;alert(1)&lt;/script&gt;', roundtrip: true },
    { v: '{m}&amp;lt;b&amp;gt;', roundtrip: true },
  ]),
  P13: P('P13', '長さ・特殊文字', [
    { v: "{m}\\ ' \" ` % _ ; -- /* */", roundtrip: true },
    { v: '{m}' + 'あ'.repeat(256) },
    { v: '{m}𠮷😀' },
  ]),
  P14: P('P14', 'SQLの記号(参考)', [
    { v: "{m}' OR '1'='1", errCheck: true },
    { v: "{m}'; -- ", errCheck: true },
    { v: "{m}%' OR '%'='", errCheck: true },
    { v: "{m}\\' OR 1=1 -- ", errCheck: true },
    { v: '{m}1 UNION SELECT NULL-- ', errCheck: true },
  ]),
  P17: P('P17', 'ヘッダー・リダイレクト', [
    { v: '{m}%0d%0aSet-Cookie:x=1' },
    { v: 'https://evil.example/' },
    { v: '//evil.example/' },
    { v: '/\\evil.example' },
  ]),
  P19: P('P19', '数値・ID・選択値の型破り', [
    { v: 'abc' }, { v: '-1' }, { v: '0' }, { v: '99999999999999999999' }, { v: '1.5' }, { v: '1e3' }, { v: '0x1' },
    { v: '1 OR 1=1', errCheck: true },
    { v: "{m}\"><svg/onload=alert(1)>", sig: '<svg/onload=alert(1)>' },
  ]),
};

// セットごとの適用 P-ID（付録「テストセット」シートと同じ）
const SETS = {
  S8: ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P09', 'P10', 'P13', 'P14'],
  S6: ['P19'],
};

// 既定(quick)は各 P-ID の先頭バリエーションだけ。P07 は textarea/title/select の3種を使う（文脈が違うため）
const QUICK_PICK = { P07: [0, 1, 2] };

function expand(setId, { full = false } = {}) {
  const out = [];
  for (const pid of SETS[setId] || []) {
    const p = PAYLOADS[pid];
    const picks = full ? p.variants.map((_, i) => i) : (QUICK_PICK[pid] || [0]);
    picks.forEach((i) => out.push({ pid, idx: i, ...p.variants[i] }));
  }
  return out;
}

const fill = (s, marker) => s.replace(/\{m\}/g, marker);

module.exports = { PAYLOADS, SETS, expand, fill };
