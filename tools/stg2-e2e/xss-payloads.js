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
  P08: P('P08', 'URL・リンク先のスキーム', [
    { v: "javascript:alert('{m}')" },
    { v: "JaVaScRiPt:alert('{m}')" },
    { v: 'java	script:alert(1)' },
    { v: ' javascript:alert(1)' },
    { v: '&#106;avascript:alert(1)' },
    { v: '%6Aavascript:alert(1)' },
    { v: 'data:text/html,<script>alert(1)</script>' },
    { v: 'vbscript:msgbox(1)' },
    { v: '//evil.example/' },
    { v: '/\evil.example/' },
    { v: 'https://evil.example@example.jp/' },
    { v: 'https://example.jp/" onmouseover="alert(1)', sig: '" onmouseover="alert(1)' },
  ]),
  P11: P('P11', 'タグの入れ子・崩し・属性の抜け道', [
    { v: '{m}<ScRiPt>alert(1)</sCrIpT>', sig: '<ScRiPt>alert(1)</sCrIpT>' },
    { v: '{m}<scr<script>ipt>alert(1)</scr</script>ipt>', sig: '<script>ipt>alert(1)' },
    { v: '{m}<img src=x onerror=alert(1)//', sig: '<img src=x onerror=alert(1)//' },
    { v: '{m}<svg><script>alert(1)</script></svg>', sig: '<svg><script>alert(1)</script></svg>' },
    { v: '{m}<math><mtext><table><mglyph><style><img src=x onerror=alert(1)>', sig: '<style><img src=x onerror=alert(1)>' },
    { v: '{m}<a href="javascript:alert(1)">x</a>', sig: '<a href="javascript:alert(1)">' },
    { v: '{m}<iframe srcdoc="<script>alert(1)</script>">', sig: '<iframe srcdoc=' },
    { v: '{m}<object data="javascript:alert(1)">', sig: '<object data=' },
    { v: '{m}<form action="javascript:alert(1)"><button>x', sig: '<form action="javascript' },
    { v: '{m}<meta http-equiv="refresh" content="0;url=javascript:alert(1)">', sig: '<meta http-equiv' },
    { v: '{m}<base href="//evil.example/">', sig: '<base href=' },
    { v: '{m}<link rel=stylesheet href="//evil.example/x.css">', sig: '<link rel=stylesheet' },
    { v: '{m}<div style="background:url(javascript:alert(1))">', sig: '<div style="background:url(javascript' },
    { v: '{m}<div style="position:fixed;top:0;left:0;width:100%;height:100%">', sig: '<div style="position:fixed' },
    { v: "{m}<style>@import 'http://evil.example/x.css';</style>", sig: '<style>@import' },
    { v: '{m}<img src="http://evil.example/x.png">', sig: '<img src="http://evil.example/x.png">' },
    { v: '{m}<input autofocus onfocus=alert(1)>', sig: '<input autofocus onfocus=alert(1)>' },
    { v: '{m}<details open ontoggle=alert(1)>', sig: '<details open ontoggle=alert(1)>' },
  ]),
  P12: P('P12', 'エンコード・文字種の回避', [
    { v: '{m}%3Cscript%3Ealert(1)%3C/script%3E', sig: '<script>alert(1)</script>' },
    { v: '{m}\u003cscript\u003ealert(1)\u003c/script\u003e', sig: '<script>alert(1)</script>' },
    { v: '{m}＜script＞alert(1)＜/script＞', sig: '<script>alert(1)</script>' },
    { v: '{m}+ADw-script+AD4-alert(1)+ADw-/script+AD4-', sig: '<script>alert(1)</script>' },
    { v: '{m}&#x3C;script&#x3E;alert(1)&#x3C;/script&#x3E;', sig: '<script>alert(1)</script>' },
    { v: '{m}\x3cscript\x3ealert(1)\x3c/script\x3e', sig: '<script>alert(1)</script>' },
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
  P15: P('P15', 'テンプレート・サーバー側の式', [
    { v: '{m}{{7*7}}', sig: '{m}49' },
    { v: '{m}${7*7}', sig: '{m}49' },
    { v: '{m}<?php echo 7*7; ?>', sig: '{m}49' },
    { v: '{m}{$smarty.version}' },
  ]),
  P17: P('P17', 'ヘッダー・リダイレクト', [
    { v: '{m}%0d%0aSet-Cookie:x=1' },
    { v: 'https://evil.example/' },
    { v: '//evil.example/' },
    { v: '/\\evil.example' },
  ]),
  PDATE: P('PDATE', '不正な日付', [
    { v: '2026-02-30' }, { v: '0000-00-00' }, { v: '12345-01-01' }, { v: '２０２６-０１-０１' }, { v: '2026-12-31/2026-01-01' },
  ]),
  PMAIL: P('PMAIL', 'メールの特殊形式', [
    { v: '"a<b>"@x.jp' }, { v: "a'b@x.jp" }, { v: 'a+b@x.jp' },
    { v: '{m}"><img src=x onerror=alert(1)>@x.jp', sig: '<img src=x onerror=alert(1)>' },
  ]),
  P19: P('P19', '数値・ID・選択値の型破り', [
    { v: 'abc' }, { v: '-1' }, { v: '0' }, { v: '99999999999999999999' }, { v: '1.5' }, { v: '1e3' }, { v: '0x1' },
    { v: '1 OR 1=1', errCheck: true },
    { v: "{m}\"><svg/onload=alert(1)>", sig: '<svg/onload=alert(1)>' },
  ]),
  // ファイルのアップロード(S9)。file: 'name' はファイル名に攻撃文字列を入れる（中身は無害なダミー）。
  // file: 'content' はファイル名は無害にし、中身を HTML・SVG(script入り)にする（保存後に配信された場合の実行を見る）
  P18: P('P18', 'アップロードファイル名・中身', [
    { v: "{m}\"><img src=x onerror=alert('{m}')>.pdf", sig: "<img src=x onerror=alert('{m}')>", file: 'name' },
    { v: "{m}';alert(1);//.pdf", sig: "{m}';alert(1);//", file: 'name' },
    { v: 'x.php.pdf', file: 'name' },
    { v: '{m}' + 'あ'.repeat(250) + '.pdf', file: 'name' },
    { v: "<html><body><script>alert('{m}')</script></body></html>", sig: "<script>alert('{m}')</script>", file: 'content', ext: '.html', mime: 'text/html' },
    { v: '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(\'{m}\')"></svg>', sig: 'onload="alert(\'{m}\')"', file: 'content', ext: '.svg', mime: 'image/svg+xml' },
  ]),
};

// セットごとの適用 P-ID（付録「テストセット」シートと同じ）
const SETS = {
  S1: ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P09', 'P10', 'P11', 'P12', 'P13', 'P15'],
  S2: ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P09', 'P10', 'P11', 'P12', 'P13', 'P15'],
  S3: ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P08', 'P09', 'P10', 'P11', 'P12', 'P13'],
  S4: ['P08', 'P17', 'P02', 'P03', 'P13'],
  S5: ['PMAIL', 'P02', 'P03', 'P05', 'P06', 'P09', 'P13', 'P14'],
  S6: ['P19'],
  S7: ['P19', 'P02', 'P13', 'PDATE'],
  S8: ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P09', 'P10', 'P13', 'P14'],
  S11: ['P19', 'P02', 'P05', 'P08', 'P17'],
  S12: ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P09', 'P10', 'P11', 'P12', 'P13'],
  S9: ['P18'],
  // パスワード(S10)。計画の「ほか: P09,P13,P02,P05」どおり既存の P-ID を流用する
  S10: ['P02', 'P05', 'P09', 'P13'],
};

// 既定(quick)は各 P-ID の先頭バリエーションだけ。P07 は textarea/title/select の3種を使う（文脈が違うため）
const QUICK_PICK = { P07: [0, 1, 2], P08: [0, 1, 8], P11: [0, 2, 5, 6, 16], P12: [0, 4], P15: [0, 1], P18: [0, 4] };

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
