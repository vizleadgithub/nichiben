// テスト計画 S12(表示専用の値。他の入口で登録された値)と F-10(入力→全画面の自動追跡)の自動実行【段階B。書き込みあり】。
//
// 考え方: S12・F-10 は「どこかの画面で攻撃文字列を登録 → 全く別の画面で、その値がエスケープされて表示されるか」を見る観点で、
//   1画面・1項目ずつの確認では足りない（「他システムでの表示先」をすべて回る必要がある）。
//   そこで、(1) test-plan/s12-entries.json に書かれた画面で実際に値を登録し、(2) CMS・受講者サイトを広く巡回して、
//   登録した識別マーカーがどこに・どんな形で出現するかを機械的に探す。
//
// 書き込みを伴う: 登録した値は削除せずそのまま残る（手動で削除する運用。CLAUDE.md の「テスト用と分かる名前」の方針に従い、
//   項目名には [XSSTEST] を含める）。実行前に stg2 の DB をダンプすること。
//
// 使い方: node run-xss-s12.js [--register-only|--crawl-only] [--max-pages N] [--only S12-cat,...]
//   既定（オプション無し）は 登録→巡回 を通しで行う。
//   --register-only  登録だけ行い、manifest(test-results/s12-manifest.json) を保存する（巡回は別途 --crawl-only で）
//   --crawl-only     登録はせず、保存済みの manifest を使って巡回だけ行う（登録済みデータが残っている前提）
//   --max-pages N    巡回するページ数の上限（既定 300。CMS・受講者サイトそれぞれに適用）
//   --only S12-cat,S12-cource  登録するエントリを絞り込む(test-plan/s12-entries.json の id)
//
// まだ実行していない（コードのみ。テスト実行は別途の指示を待つ）。
const fs = require('fs');
const path = require('path');
const { fill } = require('./xss-payloads');
const { Session } = require('./xss-session');
const { Recorder } = require('./detect');
const { setFields, fieldForms, fillBaseline, submitGuarded, submitComplete } = require('./xss-form-lib');
const { crawl, findMarkerOccurrences } = require('./s12-crawl-lib');

const args = process.argv.slice(2);
const opt = (name) => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] : undefined; };
const registerOnly = args.includes('--register-only');
const crawlOnly = args.includes('--crawl-only');
const maxPages = opt('--max-pages') ? Number(opt('--max-pages')) : 300;
const only = opt('--only') ? new Set(opt('--only').split(',')) : null;

const ENTRIES_PATH = path.join(__dirname, 'test-plan/s12-entries.json');
const MANIFEST_PATH = path.join(__dirname, 'test-results/s12-manifest.json');
const DEFAULTS_PATH = path.join(__dirname, 'test-plan/form-defaults.json');
// run-xss-form.js と同じ form-defaults.json を使う（商品カテゴリ・コンテンツ必須等、画面ごとの検証を通す既定値）
const overridesFor = (url) => (fs.existsSync(DEFAULTS_PATH) ? (JSON.parse(fs.readFileSync(DEFAULTS_PATH, 'utf-8'))[url] || {}) : {});
// 識別しやすく、かつ「未エスケープで出た」ことを機械的に検出できる代表の攻撃文字列（P02相当）を1種類だけ使う。
// S12 は入口ではなく表示先の確認が目的のため、入口側は payload のバリエーションを増やさない
const payloadFor = (marker) => fill("{m}\"><img src=x onerror=alert('{m}')>", marker);
const sigFor = (marker) => `<img src=x onerror=alert('${marker}')>`;
// URL欄は形式チェック(http/https必須)があるため、通常のURLの中にマーカーを仕込む（それでも出力時に属性から脱出しないかを見る）
const urlPayloadFor = (marker) => `https://example.jp/${marker}"><img src=x onerror=alert('${marker}')>`;
const marker = (id) => `XSSTEST_${id.replace(/[^A-Za-z0-9]/g, '')}`;

function loadEntries() {
  const cfg = JSON.parse(fs.readFileSync(ENTRIES_PATH, 'utf-8'));
  // product(商品管理)は CMS と同じログインを使う（run-xss-form.js の --site product と同じ扱い）
  const all = [
    ...(cfg.cms || []).map((e) => ({ ...e, session: 'cms' })),
    ...(cfg.product || []).map((e) => ({ ...e, session: 'cms' })),
    ...(cfg.student || []).map((e) => ({ ...e, session: 'student' })),
  ];
  return all.filter((e) => !only || only.has(e.id));
}

// 1つのエントリを登録する（newdata を開く → 対象項目にマーカーを入れる → 確認 → 登録）。DB へ書き込む
async function registerEntry(sessions, entry, rec) {
  if (entry.blockedBy) {
    rec.add({ id: entry.id, verdict: '対象外', note: `既知の不具合(${entry.blockedBy})により登録できない見込み。修正後に再実行`, url: entry.url });
    console.log(`skip ${entry.id}: 既知の不具合(${entry.blockedBy})のため登録できない見込み`);
    return null;
  }
  const session = sessions[entry.session];
  const m = marker(entry.id);
  const value = /url/i.test(entry.field) ? urlPayloadFor(m) : payloadFor(m);
  const first = await session.goto(entry.url);
  if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) {
    rec.add({ id: entry.id, verdict: '対象外', note: `画面が開けない: HTTP ${first.status}`, url: entry.url });
    return null;
  }
  const found = (await fieldForms(session.page, [entry.field]))[0];
  if (!found || !found.found) {
    rec.add({ id: entry.id, verdict: '対象外', note: '項目が画面にない', url: entry.url });
    return null;
  }
  await fillBaseline(session.page, entry.field, overridesFor(entry.url), true);
  await setFields(session.page, [{ name: entry.field, value }]);
  const confirmRes = await submitGuarded(session);
  if (confirmRes.refused) {
    rec.add({ id: entry.id, verdict: '要確認', note: `確認画面へ進めない: ${confirmRes.refused}`, url: entry.url });
    return null;
  }
  const completeRes = await submitComplete(session);
  if (completeRes.refused) {
    rec.add({ id: entry.id, verdict: '要確認', note: `登録ボタンを押せない: ${completeRes.refused}`, url: entry.url, scope: '確認画面までは到達' });
    return null;
  }
  console.log(`registered ${entry.id} (${entry.desc}) marker=${m} -> ${completeRes.finalUrl}`);
  rec.add({ id: entry.id, verdict: 'OK', note: `登録完了: ${completeRes.finalUrl}`, url: entry.url });
  return { id: entry.id, desc: entry.desc, marker: m, sig: sigFor(m), site: entry.session, registeredAt: new Date().toISOString(), finalUrl: completeRes.finalUrl };
}

// 登録済みマーカーを使い、CMS・受講者サイトを巡回して出現箇所を探す
async function crawlForMarkers(sessions, manifest, rec) {
  const markers = manifest.map((e) => ({ id: e.id, marker: e.marker, sig: e.sig }));
  if (!markers.length) { console.log('登録済みのマーカーがありません（--register-only を先に実行するか、--only を確認してください）'); return; }
  for (const site of ['cms', 'student']) {
    const session = sessions[site];
    console.log(`\n巡回: ${site}（${markers.length} 件のマーカーを探索。最大 ${maxPages} 画面）`);
    const occurrencesByPage = [];
    const { visited, skipped } = await crawl(session, {
      maxPages,
      onPage: async (url, html, status) => {
        const occ = await findMarkerOccurrences(session, html, markers);
        if (occ.length) {
          occurrencesByPage.push({ url, status, occurrences: occ });
          for (const o of occ) {
            const ng = o.findings.some((f) => f.ng);
            console.log(`${ng ? 'NG ' : '?? '} ${o.id} ${url}  ${o.findings.map((f) => f.kind + (f.where ? `[${f.where}]` : '')).join(', ')}`);
          }
        }
      },
    });
    console.log(`巡回 ${visited} 画面 / 出現あり ${occurrencesByPage.length} 画面 / スキップ ${skipped.length} 種類`);
    // ページ単位の記録。1マーカーが複数画面で見つかった場合はそれぞれ記録し、同じIDの最悪判定を report-xss.js 側で集計する
    for (const { url, occurrences } of occurrencesByPage) {
      for (const o of occurrences) {
        const ng = o.findings.some((f) => f.ng);
        rec.add({ id: o.id, verdict: ng ? 'NG' : '要確認', note: `表示先: ${url}`, url, findings: o.findings });
      }
    }
    // 登録はしたが、巡回の範囲内では一度も見つからなかったマーカー（巡回範囲の不足か、本当に表示先がない）
    const seenIds = new Set(occurrencesByPage.flatMap((p) => p.occurrences.map((o) => o.id)));
    for (const m of markers) if (!seenIds.has(m.id) && m.site === site) {
      console.log(`見つからず: ${m.id}（巡回範囲(${maxPages}画面)で未出現。表示先が巡回でリンクを辿れない画面の可能性）`);
    }
  }
}

(async () => {
  const entries = loadEntries();
  if (!entries.length) throw new Error('対象のエントリがありません（test-plan/s12-entries.json・--only を確認してください）');
  const rec = new Recorder('s12');
  const sessions = { cms: await new Session('cms').open(), student: await new Session('student').open() };

  let manifest = [];
  if (!crawlOnly) {
    console.log(`登録: ${entries.length} 件`);
    for (const e of entries) {
      const m = await registerEntry(sessions, e, rec);
      if (m) manifest.push(m);
    }
    fs.mkdirSync(path.dirname(MANIFEST_PATH), { recursive: true });
    fs.writeFileSync(MANIFEST_PATH, JSON.stringify(manifest, null, 1));
    console.log(`登録完了 ${manifest.length} 件。manifest: ${MANIFEST_PATH}`);
  } else {
    if (!fs.existsSync(MANIFEST_PATH)) throw new Error(`${MANIFEST_PATH} がありません。先に --register-only を実行してください`);
    manifest = JSON.parse(fs.readFileSync(MANIFEST_PATH, 'utf-8'));
  }

  if (!registerOnly) await crawlForMarkers(sessions, manifest, rec);

  await sessions.cms.close();
  await sessions.student.close();

  const out = rec.save({ entries: entries.map((e) => e.id), maxPages });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  const c = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
  ids.forEach((id) => c[rec.worst(id)]++);
  console.log(`\n結果 ${ids.length} 件: NG ${c.NG} / 要確認 ${c['要確認']} / OK ${c.OK} / 対象外 ${c['対象外']}`);
  console.log(`登録した値は削除していません。確認後、手動で削除するか、DBダンプから復元してください。`);
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
