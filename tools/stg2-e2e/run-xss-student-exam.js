// テスト計画のうち、受講者サイトの試験・アンケート(exam・exam2)の「回答(自由記述)」欄を自動実行する【段階B相当】。
//
// なぜ段階Bか: CMS・商品管理・会員登録の「確認」画面と違い、この系統は確認画面に見える画面(answer_check*.php等)が、
//   表示に進んだ時点で回答を書き込む(DbConnect::execute() をコードで確認。run-xss-form.js の調査で判明)。
//   書き込みなしで確認画面に到達する経路がないため、書き込みを許容してテストする。
//
// 対象(今回実装した3シナリオ。plan.json の実在するURLと対応):
//   exam_plain    /exam/index.php  → /exam/answer_check.php   書き込みなし(確認のみ。--accept-writes 不要)
//   exam_choice   /exam/index1.php → /exam/answer_check1.php  書き込みあり(回答が exam_answer_retry に保存される)
//   survey        /exam2/index.php → /exam2/answer_check.php  書き込みなし(確認のみ。--accept-writes 不要)
// 対象項目: exam_problem_{動的}[]・exam_problem_q_{動的}[](S2 複数行テキスト)、
//           exam_problem_id[]・exam_problem_id_q[]・exam2_problem_id[](S11 hidden改ざん)
//
// 前提: 事前に CMS で、テスト専用の講座・試験・アンケートを作り(STG2_STUDENT_USER だけを割り当てる)、
//   test-plan/student-entry.json に実際の pid・eid・e2id 等を記入しておく。未記入のシナリオはスキップする。
//   exam_choice は DB へ書き込むため、実行前に stg2 の DB をダンプすること(--accept-writes を付けないと実行しない)。
//
// まだ実装していないもの(段階Bの残課題):
//   - exam_freetext(/exam/index2.php。自由記述。質問間の移動そのものが毎回自動保存される。状態遷移が複雑なため別途)
//   - result*.php・resubmit_index*.php(試験が「採点済み」「再提出待ち」等の状態である必要があり、別途CMS側の準備が要る)
//   - ethic_treaning(対象の行はCSRFトークン以外にテスト対象の項目がなく、run-xss-form.js側の対応で十分)
//   - 計画(plan.json)の一部のURLは実際のファイルが存在しない(下記 DATA_ISSUES 参照。xlsx側の要確認事項)
//
// 使い方: node run-xss-student-exam.js [exam_plain|exam_choice|survey|all] [--accept-writes] [--full] [--only A-0001,...]
const fs = require('fs');
const path = require('path');
const { expand, fill } = require('./xss-payloads');
const { Session } = require('./xss-session');
const { Recorder, debugFindings, classify, domInjection } = require('./detect');
const { loadEntry, missing, qs, prefixOf, findDynamicTextareas, fillTextareaByName } = require('./student-exam-lib');

const WAIT_MS = 300;
const args = process.argv.slice(2);
const opt = (name) => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] : undefined; };
const acceptWrites = args.includes('--accept-writes');
const full = args.includes('--full');
const only = opt('--only') ? new Set(opt('--only').split(',')) : null;
const targets = args.filter((a) => !a.startsWith('--') && a !== opt('--only')).length
  ? args.filter((a) => ['exam_plain', 'exam_choice', 'survey', 'all'].includes(a))
  : ['all'];
const wantAll = targets.includes('all');

// 計画(plan.json)のURLに実体がない行(要確認。xlsx側への報告用。ここでは対象外として扱う)
const DATA_ISSUES = [
  { urlPrefix: '/exam2/', re: /^\/exam2\/(answer_check1|confirm1|confirm2|index1|index2|resubmit_exec_result1|resubmit_index1|resubmit_index2|result1|result2)\.php$/,
    note: 'exam2 ディレクトリには番号付きファイル(index1/index2/confirm1/confirm2/answer_check1/resubmit_index1/2・result1/2)が存在しない。exam(非2)の構成を誤って複製したものとみられる。実画面は index.php → answer_check.php の1系統のみ' },
  { urlPrefix: '/exam/', re: /^\/exam\/resubmit_exec_result1\.php$/, note: 'resubmit_exec1.php・resubmit_exec2.php が表示する結果画面のテンプレートで、単独のURLとしては存在しない' },
];

// 対象シナリオの定義。submitSel は、押す実際のリンク(onclick で action を動的に設定してから submit するため、
// xss-form-lib.js の汎用ガードでは正しい送信先を再現できない。既知の構造として個別に指定する)
const SCENARIOS = {
  exam_plain: {
    entry: '/exam/index.php', entryParams: ['pid', 'ccno', 'eid', 'qid'],
    fieldPrefixes: ['exam_problem_', 'exam_problem_q_'], hiddenFields: ['exam_problem_id[]', 'exam_problem_id_q[]'],
    submitSel: 'a[onclick*="examFormSubmit"]', confirmUrlRe: /\/exam\/answer_check\.php/, writes: false,
    planRows: { s2: ['C-0035', 'C-0036'], s11: ['C-0037'] },
  },
  exam_choice: {
    entry: '/exam/index1.php', entryParams: ['pid', 'ccno', 'eid', 'qid', 'eno'],
    fieldPrefixes: ['exam_problem_', 'exam_problem_q_'], hiddenFields: ['exam_problem_id[]', 'exam_problem_id_q[]'],
    submitSel: 'a[onclick*="examFormSubmit(\'exec\'"]', confirmUrlRe: /\/exam\/answer_check1\.php/, writes: true,
    writeNote: '回答が exam_answer_retry テーブルに保存される(下書き)',
    planRows: { s2: ['C-0039', 'C-0040'], s11: ['C-0041'] },
  },
  survey: {
    entry: '/exam2/index.php', entryParams: ['pid', 'e2id'],
    fieldPrefixes: ['exam2_problem_'], hiddenFields: ['exam2_problem_id[]'],
    submitSel: 'a[onclick*="examFormSubmit"], a[onclick*="pop_get_html_sub"]', confirmUrlRe: /\/exam2\/answer_check\.php/, writes: false,
    planRows: { s2: ['C-0154'], s11: ['C-0155'] },
  },
};

function loadRows(ids) {
  const { rows } = JSON.parse(fs.readFileSync(path.join(__dirname, 'test-plan/plan.json'), 'utf-8'));
  return ids.map((id) => rows.find((r) => r.id === id)).filter(Boolean);
}

async function clickSubmit(session, selector) {
  const link = session.page.locator(selector).first();
  if (await link.count() === 0) return { refused: `送信リンクが見つからない(${selector})` };
  return session.action(() => link.click({ timeout: 5000 }));
}

async function reachedConfirm(res, re) {
  return re.test(res.finalUrl) || re.test(res.html.slice(0, 200));
}

function marker(id) { return 'X' + id.replace('-', ''); }

// 1回の送信結果を検査する(xss-form-lib.js の inspect と同様の考え方。試験画面は項目名が動的なため専用に書く)
async function inspectResult(session, res, markers, dialogsBefore) {
  const findings = [];
  const dom = await domInjection(session.page, markers);
  for (const d of session.dialogs) {
    if (dialogsBefore.has(d)) continue;
    if (markers.some((m) => d.includes(m)) || d === '1') findings.push({ kind: 'ダイアログ実行', text: d, ng: true });
  }
  for (const d of dom) if (markers.some((m) => d.value.includes(m)) || d.ng) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
  for (const m of markers) {
    // onerror= を含む攻撃文字列が、エスケープされずそのまま出た場合を検出(simple signature match)
    const sig = `onerror=alert('${m}')`;
    const at = res.html.indexOf(sig);
    if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(res.html, at), text: res.html.slice(Math.max(0, at - 30), at + 60) });
  }
  if (res.status >= 500) for (const f of debugFindings(res.html)) findings.push({ kind: `確認用出力(${f.check})`, text: f.text });
  return findings;
}

async function runTextareaScenario(session, rec, key, def) {
  const entry = loadEntry(key);
  const why = missing(entry, def.entryParams);
  const rows = loadRows(def.planRows.s2).filter((r) => !only || only.has(r.id));
  if (why) {
    for (const r of rows) rec.add({ id: r.id, verdict: '対象外', note: why, scenario: key });
    console.log(`skip ${key}: ${why}`);
    return;
  }
  if (def.writes && !acceptWrites) {
    for (const r of rows) rec.add({ id: r.id, verdict: '対象外', note: `書き込みを伴う(${def.writeNote})。--accept-writes を付けて実行してください`, scenario: key });
    console.log(`skip ${key}: 書き込みを伴う(--accept-writes 未指定)`);
    return;
  }
  if (!rows.length) { console.log(`skip ${key}: 対象の行がない(--only を確認)`); return; }

  const url = `${def.entry}?${qs(entry, def.entryParams)}`;
  const payloads = expand('S2', { full });
  console.log(`${key}: ${def.entry} / 攻撃文字列 ${payloads.length} 種${def.writes ? '（書き込みあり）' : '（書き込みなし）'}`);

  for (const r of rows) {
    const prefix = prefixOf(r.field);
    if (!prefix) { rec.add({ id: r.id, verdict: '対象外', note: `項目名のパターンを解釈できない: ${r.field}`, scenario: key }); continue; }
    for (const p of payloads) {
      const dialogsBefore = new Set(session.dialogs);
      const opened = await session.goto(url);
      if (opened.status === 'ERROR' || opened.status === 'SSO' || opened.status >= 400) {
        rec.add({ id: r.id, pid: p.pid, idx: p.idx, verdict: '対象外', note: `画面が開けない: HTTP ${opened.status} ${opened.error || ''}`.trim(), scenario: key, url });
        break;
      }
      const names = await findDynamicTextareas(session.page, [prefix]);
      if (!names.length) {
        rec.add({ id: r.id, pid: p.pid, idx: p.idx, verdict: '対象外', note: `対象の textarea が画面にない(接頭辞: ${prefix})。試験に設問があるか確認してください`, scenario: key, url });
        break;
      }
      const m = marker(r.id);
      const value = fill(p.v, m);
      for (const n of names) await fillTextareaByName(session.page, n, value);
      const res = await clickSubmit(session, def.submitSel);
      if (res.refused) { rec.add({ id: r.id, pid: p.pid, idx: p.idx, verdict: '要確認', note: res.refused, scenario: key, url }); break; }
      const reached = await reachedConfirm(res, def.confirmUrlRe);
      const findings = await inspectResult(session, res, [m], dialogsBefore);
      const ng = findings.some((f) => f.ng);
      rec.add({
        id: r.id, pid: p.pid, idx: p.idx, marker: m, status: res.status, url, finalUrl: res.finalUrl, scenario: key,
        verdict: ng ? 'NG' : findings.length ? '要確認' : reached ? 'OK' : '要確認',
        note: reached ? undefined : '確認画面に到達したか判定できなかった(confirmUrlRe 不一致。手動で確認)',
        findings, writes: def.writes,
      });
      if (findings.length || !reached) console.log(`${ng ? 'NG ' : '?? '} ${r.id} ${p.pid}.${p.idx} ${findings.map((f) => f.kind).join(',') || '(確認画面に未到達)'}`);
      await session.page.waitForTimeout(WAIT_MS);
    }
  }
}

// S11: exam_problem_id[] 等の改ざん。存在しない/他人のID・配列崩し等を送り、エラー画面にスクリプト・SQL等が出ないかを見る
async function runHiddenTamper(session, rec, key, def) {
  const entry = loadEntry(key);
  const why = missing(entry, def.entryParams);
  const rows = loadRows(def.planRows.s11).filter((r) => !only || only.has(r.id));
  if (why || (def.writes && !acceptWrites) || !rows.length) {
    for (const r of rows) rec.add({ id: r.id, verdict: '対象外', note: why || (def.writes ? '書き込みを伴う(--accept-writes 未指定)' : '対象の行がない'), scenario: key });
    return;
  }
  const url = `${def.entry}?${qs(entry, def.entryParams)}`;
  const TAMPER = ['-1', '0', '99999999999999999999', '1 OR 1=1'];
  for (const r of rows) {
    const m = marker(r.id);
    for (const t of [...TAMPER, `${m}"><svg/onload=alert(1)>`]) {
      const dialogsBefore = new Set(session.dialogs);
      const opened = await session.goto(url);
      if (opened.status === 'ERROR' || opened.status === 'SSO' || opened.status >= 400) break;
      for (const f of def.hiddenFields) {
        await session.page.evaluate(({ f, t }) => {
          const els = document.getElementsByName(f);
          els.forEach((el) => { el.value = t; });
        }, { f, t });
      }
      const res = await clickSubmit(session, def.submitSel);
      if (res.refused) { rec.add({ id: r.id, verdict: '要確認', note: res.refused, scenario: key, url }); break; }
      const findings = await inspectResult(session, res, [m], dialogsBefore);
      const ng = findings.some((f) => f.ng);
      rec.add({ id: r.id, value: t, status: res.status, verdict: ng ? 'NG' : findings.length ? '要確認' : 'OK', findings, scenario: key, url, writes: def.writes });
      if (findings.length) console.log(`${ng ? 'NG ' : '?? '} ${r.id} tamper=${t.slice(0, 30)} ${findings.map((f) => f.kind).join(',')}`);
      await session.page.waitForTimeout(WAIT_MS);
    }
  }
}

(async () => {
  const rec = new Recorder('student-exam');
  const session = await new Session('student').open();
  console.log(`受講者サイトの試験・アンケート / --accept-writes=${acceptWrites} / --full=${full}`);
  for (const [key, def] of Object.entries(SCENARIOS)) {
    if (!wantAll && !targets.includes(key)) continue;
    await runTextareaScenario(session, rec, key, def);
    await runHiddenTamper(session, rec, key, def);
  }
  await session.close();

  const out = rec.save({ acceptWrites, full, targets, dataIssues: DATA_ISSUES.map((d) => d.note) });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  const c = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
  ids.forEach((id) => c[rec.worst(id)]++);
  console.log(`\n計画の行 ${ids.length}: NG ${c.NG} / 要確認 ${c['要確認']} / OK ${c.OK} / 対象外 ${c['対象外']}`);
  if (rec.entries.some((e) => e.writes)) console.log('※書き込みを行いました。確認後、stg2 の DB をダンプから復元してください（または人が内容を確認してから復元）。');
  console.log('※未実装・未対応の範囲は、このファイル冒頭のコメントと DATA_ISSUES を参照してください。');
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
