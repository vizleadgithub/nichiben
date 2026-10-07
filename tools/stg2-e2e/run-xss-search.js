// テスト計画 テストセット S8（検索条件・キーワード）の自動実行。観点1・2。
// 一覧画面の検索フォームの各項目に、項目ごとの識別マーカー入りの攻撃文字列を入れて検索し、結果画面を検査する。
// 読み取り専用: 検索フォームを送信するだけ（登録・更新・削除・CSV出力・メール送信は行わない）。
//
// 使い方: node run-xss-search.js [cms|student] [オプション]   (事前に node login.js でセッションを保存し、
//         python test-plan/export-plan.py で plan.json を作っておく)
//   --full          各攻撃文字列の全バリエーションを使う（既定は代表のみ）
//   --each          1項目ずつ送信する（既定は同じフォームの全項目をまとめて送信。マーカーで項目を特定する）
//   --only A-0010,B-0001   計画のIDを指定して実行
//   --limit N       対象の行数を N 行に制限（動作確認用）
const fs = require('fs');
const path = require('path');
const { expand, fill } = require('./xss-payloads');
const { Session } = require('./xss-session');
const { Recorder } = require('./detect');
const { setFields, fieldForms, inspect, resolveRoundtrip } = require('./xss-form-lib');

const WAIT_MS = 300;   // サーバー負荷を避けるための間隔
const SHEETS = {
  cms: ['管理_CMS', '管理_商品管理'],
  student: ['フロント_受講者サイト'],
};
const SITE_LABEL = { cms: 'CMS', student: '受講者サイト' };

const args = process.argv.slice(2);
const opt = (name) => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] : undefined; };
const site = args.find((a) => SHEETS[a]) || 'cms';
const full = args.includes('--full');
const each = args.includes('--each');
const only = opt('--only') ? new Set(opt('--only').split(',')) : null;
const limit = opt('--limit') ? Number(opt('--limit')) : Infinity;

const marker = (id) => 'X' + id.replace('-', '');

function loadRows() {
  const planPath = path.join(__dirname, 'test-plan/plan.json');
  if (!fs.existsSync(planPath)) throw new Error('先に python test-plan/export-plan.py を実行してください');
  const { rows } = JSON.parse(fs.readFileSync(planPath, 'utf-8'));
  return rows
    .filter((r) => SHEETS[site].includes(r.sheet) && r.set === 'S8' && r.site === SITE_LABEL[site])
    .filter((r) => !only || only.has(r.id))
    .slice(0, limit);
}

// 検索ボタンを押す。ラベルに「検索」等を含むボタンを優先し、なければ唯一のボタン、それも無ければフォームを直接送信
async function submitForm(session, formIndex) {
  const { page } = session;
  const form = page.locator('form[data-xss-form]').first();
  const cand = form.locator('button, input[type=submit], input[type=button], input[type=image], a, img[onclick]');
  const n = await cand.count();
  const labels = await cand.evaluateAll((els) => els.map((e) => `${e.value || ''}${e.textContent || ''}${e.alt || ''}${e.title || ''}`));
  const idx = labels.findIndex((l) => /検索|絞り込|search/i.test(l));
  return session.action(async () => {
    const target = idx >= 0 ? cand.nth(idx) : n === 1 ? cand.first() : null;
    // 検索ボタンが見えない・押せないフォームは、フォームを直接送信する
    if (target) await target.click({ timeout: 3000 }).catch(() => form.evaluate((f) => f.requestSubmit()));
    else await form.evaluate((f) => f.requestSubmit());
  });
}

(async () => {
  const rows = loadRows();
  if (!rows.length) throw new Error('対象の行がありません（plan.json・--only を確認してください）');
  const payloads = expand('S8', { full });
  const byUrl = new Map();
  for (const r of rows) {
    if (/[{}]/.test(r.url)) continue;   // {ID} 等を含むURLは対象外（個別に実画面で確認）
    if (!byUrl.has(r.url)) byUrl.set(r.url, []);
    byUrl.get(r.url).push(r);
  }
  const rec = new Recorder(`search-${site}`);
  const session = await new Session(site).open();
  console.log(`${site}: ${rows.length} 行 / ${byUrl.size} 画面 / 攻撃文字列 ${payloads.length} 種 / ${each ? '1項目ずつ' : 'フォーム単位でまとめて'}送信`);

  for (const [url, urlRows] of byUrl) {
    // 1) 画面を開き、項目の有無とフォームを調べる
    const first = await session.goto(url);
    if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) {
      const why = `HTTP ${first.status} ${first.error || ''}`.trim();
      for (const r of urlRows) rec.add({ id: r.id, verdict: '対象外', note: `画面が開けない: ${why}`, url });
      console.log(`skip ${why} ${url}`);
      continue;
    }
    const baseDialogs = new Set(session.dialogs);   // 攻撃文字列を入れる前に画面自身が出すダイアログ（攻撃由来と区別する基準）
    const names = [...new Set(urlRows.map((r) => r.field).filter(Boolean))];
    const forms = new Map((await fieldForms(session.page, names)).map((f) => [f.name, f]));
    const testable = [];
    for (const r of urlRows) {
      const f = forms.get(r.field);
      if (!r.field) rec.add({ id: r.id, verdict: '対象外', note: '項目名(name)が計画にない', url });
      else if (!f || !f.found) rec.add({ id: r.id, verdict: '対象外', note: '画面に項目がない（コントローラで受け取る値など。入力欄の有無を要確認）', url });
      else if (f.form < 0) rec.add({ id: r.id, verdict: '対象外', note: 'フォームの外の項目（JavaScriptで送信される値の可能性。手動で確認）', url });
      else testable.push({ ...r, form: f.form });
    }
    // 2) フォーム単位（--each のときは1項目ずつ）で、攻撃文字列ごとに送信
    const groups = new Map();
    for (const r of testable) {
      const k = each ? r.id : String(r.form);
      if (!groups.has(k)) groups.set(k, []);
      groups.get(k).push(r);
    }
    for (const group of groups.values()) {
      const formIndex = group[0].form;
      for (const p of payloads) {
        const opened = await session.goto(url);
        if (opened.status === 'ERROR' || opened.status === 'SSO') break;
        const fields = group.map((r) => ({ name: r.field, value: fill(p.v, marker(r.id)) }));
        const set = await setFields(session.page, fields);
        const injected = group.map((r, i) => ({
          id: r.id, marker: marker(r.id), field: r.field, payload: p, type: set[i].type,
        }));
        let res;
        try {
          res = await submitForm(session, formIndex);
        } catch (e) {
          for (const i of injected) rec.add({ id: i.id, pid: p.pid, idx: p.idx, verdict: '要確認', note: `送信できない: ${e.message.split('\n')[0]}`, url });
          continue;
        }
        const perRow = await inspect(session, res, injected, baseDialogs);
        for (let k = 0; k < injected.length; k++) {
          const i = injected[k];
          const findings = await resolveRoundtrip(session, res, perRow[k]);
          const ng = findings.some((f) => f.ng);
          rec.add({
            id: i.id, pid: p.pid, idx: p.idx, marker: i.marker, status: res.status, url, finalUrl: res.finalUrl,
            verdict: ng ? 'NG' : findings.length ? '要確認' : 'OK',
            findings, jsErrors: session.jsErrors.slice(0, 3),
          });
          if (findings.length) console.log(`${ng ? 'NG ' : '?? '} ${i.id} ${p.pid}.${p.idx} ${findings.map((f) => f.kind + (f.where ? `[${f.where}]` : '')).join(', ')}`);
        }
        await session.page.waitForTimeout(WAIT_MS);
      }
    }
    console.log(`done ${url}  (${testable.length} 項目)`);
  }
  await session.close();
  const out = rec.save({ site, full, each });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  const c = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
  ids.forEach((id) => c[rec.worst(id)]++);
  console.log(`\n計画の行 ${ids.length}: NG ${c.NG} / 要確認 ${c['要確認']} / OK ${c.OK} / 対象外 ${c['対象外']}`);
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
