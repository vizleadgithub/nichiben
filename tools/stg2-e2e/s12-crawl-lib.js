// run-xss-s12.js 専用: 登録したマーカーが、別の画面に未エスケープで出ていないかを広く巡回して確認する。
// check-debug-output.js の巡回ロジック（スキップ規則・パターン制限・CMS自動再ログイン）と同じ考え方。
// 既存の check-debug-output.js は動作実績があるため変更せず、ここでは同じ方式を複製して使う。
const { classify, domInjection } = require('./detect');

const MAX_PER_PATTERN = 3;   // 同じ形のURL(IDだけ違う等)は3件まで
const WAIT_MS = 250;

// 開くだけで副作用がありうるURL（check-debug-output.js と同じ）
const SKIP_COMMON = /logout|login|school_select|delete|del_|_del\b|remove|commit|regist|insert|update|exec|complete|save|upload|import|download|csv|export|send|sync|bat_|api_|cancel|reset|approve|clear|copy|_set\b|set\.php|\.(pdf|zip|csv|xlsx?|docx?|pptx?|jpe?g|png|gif|svg|mp4|mp3|css|js)(\?|$)/i;
const SKIP_PATH_BY_SITE = {
  cms: null,
  student: /^\/(player|exam|exam2|ethic_treaning|settlement|member|login|logout|engine1|engine1_sp|data1|data1_sp|question)(\/|$)|^\/mypage\/(favorite|edit|refusal|receipt_download|ticket_download|input_zip|viewing)\.php|^\/product\/complete\.php|download\.php/i,
};

const pattern = (u) => u.pathname.replace(/\/[^/]*\d[^/]*(?=\/|$)/g, '/{n}') + '?' + [...u.searchParams.keys()].sort().join('&');

// サイト内を巡回し、各ページで onPage(url, html, status) を呼ぶ。戻り値は { visited, skipped }
async function crawl(session, { maxPages = 300, seedUrls = [], onPage }) {
  const site = session.site;
  const origin = session.origin;
  const queue = [session.baseUrl, ...seedUrls];
  const seen = new Set();
  const perPattern = new Map();
  const skipped = new Set();
  const visitedUrls = [];
  let visited = 0;

  while (queue.length && visited < maxPages) {
    const url = queue.shift();
    if (seen.has(url)) continue;
    seen.add(url);
    let u;
    try { u = new URL(url); } catch { continue; }
    if (u.origin !== origin) continue;
    const key = pattern(u);
    if ((perPattern.get(key) || 0) >= MAX_PER_PATTERN) continue;
    perPattern.set(key, (perPattern.get(key) || 0) + 1);

    const res = await session.goto(url);
    if (res.status === 'ERROR' || res.status === 'SSO') continue;
    visited++;
    visitedUrls.push(url);
    await onPage(url, res.html, res.status);

    const hrefs = await session.page.$$eval('a[href]', (as) => as.map((a) => a.href)).catch(() => []);
    for (const h of hrefs) {
      let v;
      try { v = new URL(h); } catch { continue; }
      v.hash = '';
      if (v.origin !== origin) continue;
      const target = v.pathname + v.search;
      if (SKIP_COMMON.test(target) || (SKIP_PATH_BY_SITE[site] && SKIP_PATH_BY_SITE[site].test(v.pathname))) { skipped.add(v.pathname); continue; }
      const s = v.toString();
      if (!seen.has(s)) queue.push(s);
    }
    await session.page.waitForTimeout(WAIT_MS);
  }
  return { visited, skipped: [...skipped].sort(), visitedUrls };
}

// ページの HTML・DOM から、各マーカーの出現を探して分類する。
// markers: [{ id, marker, sig }]  sig があれば「攻撃文字列がそのまま出た」ことも見る（P02等の未エスケープ検出と同じ考え方）
async function findMarkerOccurrences(session, html, markers) {
  const present = markers.filter((m) => html.includes(m.marker));
  if (!present.length) return [];
  const dom = await domInjection(session.page, present.map((m) => m.marker));
  return present.map((m) => {
    const findings = [];
    for (const d of session.dialogs) if (d.includes(m.marker) || d === '1') findings.push({ kind: 'ダイアログ実行', text: d, ng: true });
    for (const d of dom) if (d.value.includes(m.marker) || d.ng) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
    if (m.sig) {
      const at = html.indexOf(m.sig);
      if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(html, at), text: m.sig.slice(0, 120) });
    }
    // 文字として出ているだけ（エスケープ済み）の場合は、見つかったことの記録のみ残す（OK の根拠）
    if (!findings.length) {
      const at = html.indexOf(m.marker);
      findings.push({ kind: '文字として表示(エスケープ済み)', where: classify(html, at), text: html.slice(Math.max(0, at - 20), at + m.marker.length + 20) });
    }
    return { id: m.id, marker: m.marker, findings };
  });
}

module.exports = { crawl, findMarkerOccurrences, SKIP_COMMON, SKIP_PATH_BY_SITE };
