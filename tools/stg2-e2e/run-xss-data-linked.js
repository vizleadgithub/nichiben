// 重点項目 E-10(講座確認の受講者一覧)・E-12(売上の会員詳細)・E-13(売上の注文詳細)の自動実行。
//
// これらは「実在の受講者・注文データが無いと表示を確認できない」として長らく未自動化だったが、2026-10-07の調査で
// 次の方法により CMS 側の事前セットアップ無しで自動生成できることが分かった。
//   - 受講者: member/regist.php でテスト用の氏名(攻撃文字列入り)を登録するだけ(S12-student-nameと同じ仕組み)
//   - 注文: 無料のeラーニング商品の詳細ページを開くと、0円の注文が自動作成される(アプリの仕様。決済は発生しない。
//     alfproduct/public/product/detail.php の `product_type_add==1 && price<=0` の分岐で確認済み)
//   - E-10の受講者一覧: 既存の任意の講座の edit→confirm(DBへは書き込まない。段階Aと同じ)で、受講者欄に
//     登録したテスト受講者のIDを追加して送信するだけで、確認画面にその受講者名が表示される(commitはしない)
//
// 段階: E-10は段階A相当(confirmまでで登録・更新はしない)。E-12/E-13は、受講者登録と無料商品の0円注文の自動作成を
//   伴うため段階B相当(DBへ書き込む。決済は発生しない)。実行前に stg2 の DB をダンプすること。
//
// 必要な設定: test-plan/data-entries.json の free_product_pid（無料のeラーニング商品のID）。未設定なら
//   E-12/E-13 は対象外として記録される。講座IDは /cms_cource/ の一覧から自動取得する。
//
// 見つからなかったもの(2026-10-07 調査。別途要対応。test-plan/data-entries.json 参照):
//   - E-20(課題確認の提出ファイル名): 受講者側の課題提出画面がソースに存在しない(機能自体の実装状況を要確認)
//   - E-60(領収書・受講証PDFへの氏名反映): receipt_download.phpは入金済み、ticket_download.phpは会場研修商品が
//     必須で、どちらも無料eラーニング商品の自動0円注文では条件を満たせない
//
// 使い方: node run-xss-data-linked.js [--only E-10,E-12,E-13] [--accept-writes]
const fs = require('fs');
const path = require('path');
const { fill } = require('./xss-payloads');
const { Session } = require('./xss-session');
const { Recorder, classify, domInjection } = require('./detect');
const { setFields, fieldForms, fillBaseline, submitGuarded, submitComplete } = require('./xss-form-lib');

const args = process.argv.slice(2);
const opt = (name) => { const i = args.indexOf(name); return i >= 0 ? args[i + 1] : undefined; };
const only = opt('--only') ? new Set(opt('--only').split(',')) : null;
const acceptWrites = args.includes('--accept-writes');
const want = (id) => !only || only.has(id);

const DEFAULTS_PATH = path.join(__dirname, 'test-plan/form-defaults.json');
const overridesFor = (url) => (fs.existsSync(DEFAULTS_PATH) ? (JSON.parse(fs.readFileSync(DEFAULTS_PATH, 'utf-8'))[url] || {}) : {});
const DATA_ENTRIES_PATH = path.join(__dirname, 'test-plan/data-entries.json');
const dataEntries = fs.existsSync(DATA_ENTRIES_PATH) ? JSON.parse(fs.readFileSync(DATA_ENTRIES_PATH, 'utf-8')) : {};

const MARKER = 'XSSTEST_DATALINK';
const SIG = `<img src=x onerror=alert('${MARKER}')>`;
const NAME_PAYLOAD = fill("{m}\"><img src=x onerror=alert('{m}')>", MARKER);

// 受講者サイトでテスト用の受講者を登録する(S12-student-nameと同じ仕組み)。DBへ書き込む
async function registerMarkerStudent(student) {
  const first = await student.goto('/member/regist.php');
  if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) return { error: `画面が開けない: HTTP ${first.status}` };
  const found = (await fieldForms(student.page, ['name1']))[0];
  if (!found || !found.found) return { error: '/member/regist.php に name1 欄がない(画面構成が変わった可能性)' };
  await fillBaseline(student.page, 'name1', overridesFor('/member/regist.php'), true);
  await setFields(student.page, [{ name: 'name1', value: NAME_PAYLOAD }]);
  const confirmRes = await submitGuarded(student);
  if (confirmRes.refused) return { error: `確認画面へ進めない: ${confirmRes.refused}` };
  const completeRes = await submitComplete(student);
  if (completeRes.refused) return { error: `登録を完了できない: ${completeRes.refused}` };
  console.log(`受講者を登録しました: ${NAME_PAYLOAD} -> ${completeRes.finalUrl}`);
  return { ok: true };
}

// CMSの受講者検索(s_name)で、登録したテスト受講者の student_id を探す
async function findStudentId(cms) {
  const res = await cms.goto('/cms_student');
  if (res.status === 'ERROR' || res.status === 'SSO' || res.status >= 400) return null;
  const found = (await fieldForms(cms.page, ['s_name']))[0];
  if (!found || !found.found) return null;
  await setFields(cms.page, [{ name: 's_name', value: MARKER }]);
  const result = await submitGuarded(cms, { allowFormSubmit: true }).catch(() => ({ refused: true }));
  const html = result && result.html ? result.html : await cms.page.content();
  const m = html.match(/\/cms_student\/detail\/(\d+)/);
  return m ? m[1] : null;
}

// E-12・E-13: 無料のeラーニング商品を開いて0円注文を自動作成させ、売上の会員詳細・注文詳細を確認する
async function checkE1213(rec) {
  const ids = ['E-12', 'E-13'].filter(want);
  if (!ids.length) return;
  const pid = dataEntries.free_product_pid;
  if (!pid) {
    for (const id of ids) rec.add({ id, verdict: '対象外', note: 'test-plan/data-entries.json の free_product_pid が未設定(無料のeラーニング商品のIDを記入してください)' });
    console.log('skip E-12/E-13: free_product_pid が未設定');
    return;
  }
  if (!acceptWrites) {
    for (const id of ids) rec.add({ id, verdict: '対象外', note: '書き込みを伴う(受講者登録・0円注文の自動作成)。--accept-writes を付けて実行してください' });
    console.log('skip E-12/E-13: 書き込みを伴う(--accept-writes 未指定)');
    return;
  }
  const cms = await new Session('cms').open();
  const student = await new Session('student').open();
  try {
    const reg = await registerMarkerStudent(student);
    if (reg.error) { for (const id of ids) rec.add({ id, verdict: '要確認', note: reg.error }); return; }
    const detail = await student.goto(`/product/detail.php?pid=${encodeURIComponent(pid)}`);
    if (detail.status === 'ERROR' || detail.status === 'SSO' || detail.status >= 400) {
      for (const id of ids) rec.add({ id, verdict: '対象外', note: `無料商品の詳細画面が開けない: HTTP ${detail.status}(pidを確認してください)` });
      return;
    }
    const sid = await findStudentId(cms);
    if (!sid) {
      for (const id of ids) rec.add({ id, verdict: '要確認', note: '登録した受講者が /cms_student の検索結果に見つからない' });
      return;
    }
    console.log(`受講者ID(sid)=${sid} が見つかりました`);

    if (want('E-12')) {
      const res = await cms.goto(`/alfproduct/amount_user/info.php?sid=${sid}`);
      const findings = [];
      const at = res.html.indexOf(SIG);
      if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(res.html, at), text: SIG, ng: true });
      else if (res.html.includes(MARKER)) findings.push({ kind: '文字として表示(エスケープ済み)', text: MARKER });
      const dom = await domInjection(cms.page, [MARKER]);
      for (const d of dom) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
      rec.add({ id: 'E-12', verdict: findings.some((f) => f.ng) ? 'NG' : findings.length ? 'OK' : '要確認', label: `amount_user/info.php?sid=${sid}`, status: res.status, findings });
      console.log(`E-12 amount_user/info.php?sid=${sid} ${findings.map((f) => f.kind).join(',') || '(マーカー未検出)'}`);

      if (want('E-13')) {
        const oidMatch = res.html.match(/amount_order\/info\.php\?oid=(\d+)/);
        if (!oidMatch) {
          rec.add({ id: 'E-13', verdict: '要確認', note: '売上の会員詳細に注文詳細へのリンクが見つからない(0円注文が作成されていない可能性)' });
        } else {
          const oid = oidMatch[1];
          const res2 = await cms.goto(`/alfproduct/amount_order/info.php?oid=${oid}`);
          const findings2 = [];
          const at2 = res2.html.indexOf(SIG);
          if (at2 >= 0) findings2.push({ kind: '未エスケープで出現(HTML)', where: classify(res2.html, at2), text: SIG, ng: true });
          else if (res2.html.includes(MARKER)) findings2.push({ kind: '文字として表示(エスケープ済み)', text: MARKER });
          const dom2 = await domInjection(cms.page, [MARKER]);
          for (const d of dom2) findings2.push({ kind: 'DOM上のイベント属性/JSリンク(確認ダイアログ等)', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
          rec.add({ id: 'E-13', verdict: findings2.some((f) => f.ng) ? 'NG' : findings2.length ? 'OK' : '要確認', label: `amount_order/info.php?oid=${oid}`, status: res2.status, findings: findings2 });
          console.log(`E-13 amount_order/info.php?oid=${oid} ${findings2.map((f) => f.kind).join(',') || '(マーカー未検出)'}`);
        }
      }
    } else if (want('E-13')) {
      rec.add({ id: 'E-13', verdict: '要確認', note: 'E-12(sidの特定)と同時実行が必要です。--only に E-12 も含めてください' });
    }
  } finally {
    await cms.close();
    await student.close();
  }
}

// E-10: 講座のedit→confirm(DBへは書き込まない)で、受講者欄にテスト受講者のIDを追加して送信し、
// 確認画面の受講者一覧(lecture_students_name)にその氏名が表示されるかを見る
async function checkE10(rec) {
  const id = 'E-10';
  if (!want(id)) return;
  const pid = dataEntries.free_product_pid;
  if (!acceptWrites) { rec.add({ id, verdict: '対象外', note: '受講者登録(書き込み)を伴う。--accept-writes を付けて実行してください' }); return; }
  const cms = await new Session('cms').open();
  const student = await new Session('student').open();
  try {
    const reg = await registerMarkerStudent(student);
    if (reg.error) { rec.add({ id, verdict: '要確認', note: reg.error }); return; }
    const sid = await findStudentId(cms);
    if (!sid) { rec.add({ id, verdict: '要確認', note: '登録した受講者が /cms_student の検索結果に見つからない' }); return; }

    const list = await cms.goto('/cms_cource/');
    const href = await cms.page.evaluate(() => {
      const a = [...document.querySelectorAll('a[href]')].find((x) => /\/cms_cource\/detail\/\d+/.test(x.getAttribute('href')));
      return a && a.getAttribute('href');
    }).catch(() => null);
    if (!href) { rec.add({ id, verdict: '対象外', note: '/cms_cource/ に既存の講座が1件もない' }); return; }
    const courceId = href.match(/detail\/(\d+)/)[1];
    const detail = await cms.goto(`/cms_cource/detail/${courceId}`);
    if (detail.status === 'ERROR' || detail.status === 'SSO' || detail.status >= 400) { rec.add({ id, verdict: '要確認', note: `講座の詳細が開けない: HTTP ${detail.status}` }); return; }
    const edit = await cms.goto('/cms_cource/edit');
    const found = (await fieldForms(cms.page, ['cource_name']))[0];
    if (!found || !found.found) { rec.add({ id, verdict: '要確認', note: '/cms_cource/edit に cource_name 欄がない(セッション連携の仕組みが変わった可能性)' }); return; }
    await fillBaseline(cms.page, 'cource_name', { ...overridesFor('/cms_cource/edit'), '+lecture_students_array[]': sid }, true);
    const confirmRes = await submitGuarded(cms);
    if (confirmRes.refused) { rec.add({ id, verdict: '要確認', note: `確認画面へ進めない: ${confirmRes.refused}`, scope: `講座ID ${courceId}` }); return; }
    const findings = [];
    const at = confirmRes.html.indexOf(SIG);
    if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(confirmRes.html, at), text: SIG, ng: true });
    else if (confirmRes.html.includes(MARKER)) findings.push({ kind: '文字として表示(エスケープ済み)', text: MARKER });
    else findings.push({ kind: '受講者一覧にマーカーが見当たらない(受講者欄の項目名が変わった可能性)', text: '' });
    rec.add({ id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('見当たらない') ? '要確認' : 'OK', label: `cms_cource/confirm(講座ID ${courceId}。DBへは書き込んでいない)`, status: confirmRes.status, findings });
    console.log(`E-10 cms_cource/confirm(講座ID ${courceId}) ${findings.map((f) => f.kind).join(',')}`);
  } finally {
    await cms.close();
    await student.close();
  }
}

(async () => {
  const rec = new Recorder('data-linked');
  console.log(`E-10・E-12・E-13 / --accept-writes=${acceptWrites}`);
  await checkE1213(rec);
  await checkE10(rec);
  const out = rec.save({ acceptWrites });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  const c = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
  ids.forEach((id) => c[rec.worst(id)]++);
  console.log(`\n結果 ${ids.length} 件: NG ${c.NG} / 要確認 ${c['要確認']} / OK ${c.OK} / 対象外 ${c['対象外']}`);
  if (rec.entries.length) console.log('※受講者登録・0円注文の作成を行いました。確認後、stg2 の DB をダンプから復元してください。');
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
