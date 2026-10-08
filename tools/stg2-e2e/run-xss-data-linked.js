// 重点項目 E-10(講座確認の受講者一覧)・E-12(売上の会員詳細)・E-13(売上の注文詳細)・E-14(受講者検索ポップアップ)・
// E-20(課題確認の提出ファイル名)・E-49(商品検索スマホ版のカテゴリ名。全量一覧No.49の退行確認)・
// E-60(領収書・受講証PDFへの商品名反映)の自動実行。
//
// E-10/E-12/E-13/E-14/E-49 は「実在の受講者・注文データが無いと表示を確認できない」として長らく未自動化だったが、
// 2026-10-07の調査で次の方法により CMS 側の事前セットアップ無しで自動生成できることが分かった。
//   - 受講者: member/regist.php でテスト用の氏名(攻撃文字列入り)を登録するだけ(S12-student-nameと同じ仕組み)
//   - 注文: 無料のeラーニング商品の詳細ページを開くと、0円の注文が自動作成される(アプリの仕様。決済は発生しない。
//     alfproduct/public/product/detail.php の `product_type_add==1 && price<=0` の分岐で確認済み)
//   - E-10の受講者一覧: 既存の任意の講座の edit→confirm(DBへは書き込まない。段階Aと同じ)で、受講者欄に
//     登録したテスト受講者のIDを追加して送信するだけで、確認画面にその受講者名が表示される(commitはしない)
//
// 段階: E-10は段階A相当(confirmまでで登録・更新はしない)。E-12/E-13/E-14/E-49は、受講者登録・商品登録・
//   無料商品の0円注文の自動作成を伴うため段階B相当(DBへ書き込む。決済は発生しない)。実行前に stg2 の DB を
//   ダンプすること。これらは --accept-writes が必須。
//
// E-20・E-60(領収書・受講証)は、2026-10-08 の開発者回答により方針が変わった。
//   - E-20: 受講者側に課題提出画面が無く(ソース上INSERT処理が存在しない)、自動生成できない。CMS側の表示
//     (/cms_issue/detail/{issue_id}。issue_submit_name・issue_submit_logic_name・issue_submit_caption を
//     組み立てる際に無害化)は、データさえあれば確認できるため、人が issue_submit テーブルへ直接SQLで
//     テスト行を1件投入し、自動テストは読み取りのみ行う。
//   - E-60: 従来は商品の自前登録→0円注文の自動作成→amount_order/info.php の mode=pay(入金済みへの変更)
//     までを自動化していたが、mode=pay は受講者へメール送信を伴うことが判明した(order8.mail を mb_send_mail)。
//     同様に無料会場研修の購入(payment_free.php)も受講者へメール送信を伴う(order9.mail)。
//     CLAUDE.md の方針(メール送信を伴う操作は自動化しない)に反するため、**商品登録・購入・入金確認はすべて
//     人がCMS画面・受講者サイトから手動で行う**方式に変更した。自動テストは、人が用意した注文ID
//     (領収書: data-entries.json の receipt_order_id。受講証: ticket_order_detail_id)を使って
//     ダウンロード・確認するだけ(読み取りのみ。書き込み・メール送信は無い)。
//     領収書は成功すると tbl_order.receipt_flg が立ち1注文につき1回しか再現できない(再実行には新しい
//     入金済み注文が必要)。受講証は発行済みフラグの更新処理がコメントアウトされており何度でも再実行できる。
//   具体的な人の作業手順・SQL文は test-plan/data-entries.json のコメントを参照。
//
// B-0015(売上の注文詳細「atena」欄): 2026-10-08、手動実施バケットの監査中に「mode=payとは無関係の別フォーム
//   (downloadForm→pdf.php。領収書の下書きPDFを画面内表示するだけでメール送信は無い)」と判明し自動化対象にした。
//   E-13と同じoid(E-12/E-13の実行経路)に相乗りするため、--only には E-12,E-13 も含めること(どちらも検証結果
//   として記録したくない場合でも、oid特定のために内部的に必要)。
//
// 必要な設定: test-plan/data-entries.json の free_product_pid（無料のeラーニング商品のID。E-12/E-13/E-10/B-0015用）・
//   issue_id（E-20。テスト用の課題のID）・receipt_order_id（E-60領収書。入金済みの注文ID）・
//   ticket_order_detail_id（E-60受講証。会場研修注文の注文明細ID）。未設定の項目はそれぞれ対象外として記録される。
//
// 使い方: node run-xss-data-linked.js [--only E-10,E-12,E-13,E-14,E-20,E-49,E-60,B-0015] [--accept-writes]
//   --accept-writes は E-10/E-12/E-13/E-14/E-49/B-0015(書き込みを伴う)にのみ必要。E-20・E-60(領収書・受講証)は
//   読み取りのみのため --accept-writes が無くても実行される(data-entries.json が未設定なら対象外になるだけ)。
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

// B-0015: 売上の注文詳細(amount_order/info.php)の「atena」欄。確認画面を経由せず、別フォーム(downloadForm)で
// pdf.php(領収書の下書きPDF。Content-Dispositionが無く画面内で表示される。ダウンロードにはならない)へ直接POSTされる
// ため、通常のrun-xss-form.jsのsubmitGuardedでは対象にできない。E-13で見つけたoidを使って個別に確認する
async function checkAtena(rec, cms, oid) {
  const id = 'B-0015';
  const payload = fill("{m}'); alert('{m}');//", MARKER);
  await cms.goto(`/alfproduct/amount_order/info.php?oid=${oid}`);
  const hasForm = await cms.page.evaluate(() => !!document.getElementById('atena'));
  if (!hasForm) { rec.add({ id, verdict: '要確認', note: `atena欄が見つからない(oid=${oid})` }); return; }
  await cms.page.evaluate((v) => { document.getElementById('atena').value = v; }, payload);
  const res = await cms.action(() => cms.page.evaluate(() => document.forms.downloadForm.submit()));
  const findings = [];
  if (res.status >= 400) findings.push({ kind: `HTTP ${res.status}`, text: '' });
  const body = res.html || '';
  if (body.includes(payload)) findings.push({ kind: '未エスケープで出現(PDF内に攻撃文字列がそのまま検出)', text: payload, ng: true });
  else if (body.includes(MARKER)) findings.push({ kind: '文字として検出(エスケープされている可能性)', text: MARKER });
  else findings.push({ kind: 'PDFの生テキストからマーカーを検出できず(圧縮されている可能性。手動でPDFを開いて確認してください)', text: '' });
  rec.add({
    id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('検出できず') ? '要確認' : 'OK', label: `amount_order/pdf.php(oid=${oid})`, status: res.status, findings,
    scope: 'PDF内のテキスト検出はベストエフォート(圧縮されていると検出できない)。領収書の下書き表示のみでDB書き込み・メール送信は無い',
  });
  console.log(`B-0015 amount_order/pdf.php(oid=${oid}) ${findings.map((f) => f.kind).join(',')}`);
}

// E-12・E-13: 無料のeラーニング商品を開いて0円注文を自動作成させ、売上の会員詳細・注文詳細を確認する
async function checkE1213(rec) {
  // B-0015(atena欄)はE-13で見つけたoidに相乗りするため、E-12/E-13のどちらも対象外でもB-0015だけなら実行する
  const ids = ['E-12', 'E-13'].filter(want);
  const idsAll = [...ids, ...(want('B-0015') ? ['B-0015'] : [])];
  if (!idsAll.length) return;
  const pid = dataEntries.free_product_pid;
  if (!pid) {
    for (const id of idsAll) rec.add({ id, verdict: '対象外', note: 'test-plan/data-entries.json の free_product_pid が未設定(無料のeラーニング商品のIDを記入してください)' });
    console.log('skip E-12/E-13/B-0015: free_product_pid が未設定');
    return;
  }
  if (!acceptWrites) {
    for (const id of idsAll) rec.add({ id, verdict: '対象外', note: '書き込みを伴う(受講者登録・0円注文の自動作成)。--accept-writes を付けて実行してください' });
    console.log('skip E-12/E-13/B-0015: 書き込みを伴う(--accept-writes 未指定)');
    return;
  }
  const cms = await new Session('cms').open();
  const student = await new Session('student').open();
  try {
    const reg = await registerMarkerStudent(student);
    if (reg.error) { for (const id of idsAll) rec.add({ id, verdict: '要確認', note: reg.error }); return; }
    const detail = await student.goto(`/product/detail.php?pid=${encodeURIComponent(pid)}`);
    if (detail.status === 'ERROR' || detail.status === 'SSO' || detail.status >= 400) {
      for (const id of idsAll) rec.add({ id, verdict: '対象外', note: `無料商品の詳細画面が開けない: HTTP ${detail.status}(pidを確認してください)` });
      return;
    }
    const sid = await findStudentId(cms);
    if (!sid) {
      for (const id of idsAll) rec.add({ id, verdict: '要確認', note: '登録した受講者が /cms_student の検索結果に見つからない' });
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
          if (want('B-0015')) await checkAtena(rec, cms, oid);
        }
      }
    } else {
      if (want('E-13')) rec.add({ id: 'E-13', verdict: '要確認', note: 'E-12(sidの特定)と同時実行が必要です。--only に E-12 も含めてください' });
      if (want('B-0015')) rec.add({ id: 'B-0015', verdict: '要確認', note: 'oidの特定にE-12・E-13の経路を使うため、同時実行が必要です。--only に E-12,E-13 も含めてください' });
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

// 受講者サイトのマイページ(buy_list.php)から、対象の注文の領収書ダウンロードフォームを送信する(POST。CSRF同梱)。
// 注文は人が事前にCMS画面・受講者サイトから入金済みにしておく(mode=pay等はメール送信を伴うため自動化しない)
async function downloadReceipt(student, oid) {
  const res = await student.goto('/mypage/buy_list.php');
  if (res.status === 'ERROR' || res.status === 'SSO' || res.status >= 400) return { error: `マイページが開けない: HTTP ${res.status}` };
  const hasForm = await student.page.evaluate((oid) => !!document.getElementById(`receipt_download_form${oid}`), oid);
  if (!hasForm) return { error: `注文(oid=${oid})の領収書ダウンロードフォームが見つからない(入金済みになっていない、またはこの受講者の注文でない可能性)` };
  const after = await student.action(() => student.page.evaluate((oid) => {
    document.getElementById(`receipt_download_form${oid}`).submit();
  }, oid));
  return { ok: true, status: after.status, html: after.html };
}

// E-60(領収書): 人が事前に用意した入金済みの注文(data-entries.json の receipt_order_id)で領収書をダウンロードし、
// 商品名がPDFにエスケープされて出力されるか確認する(読み取りのみ。書き込み・メール送信は無い)
async function checkReceipt(rec) {
  const id = 'E-60';
  if (!want(id)) return;
  const oid = dataEntries.receipt_order_id;
  if (!oid) {
    rec.add({ id, verdict: '対象外', note: 'test-plan/data-entries.json の receipt_order_id が未設定(入金済みの注文のorder_idを、人が用意して記入してください。手順はdata-entries.jsonのコメント参照)' });
    console.log('skip E-60(領収書): receipt_order_id が未設定');
    return;
  }
  const student = await new Session('student').open();
  try {
    const dl = await downloadReceipt(student, oid);
    if (dl.error) { rec.add({ id, verdict: '要確認', note: dl.error, label: `mypage/buy_list.php(oid=${oid})` }); return; }
    const findings = [];
    if (dl.status >= 400) findings.push({ kind: `HTTP ${dl.status}`, text: '' });
    const bodyText = dl.html || '';
    if (bodyText.includes(SIG)) findings.push({ kind: '未エスケープで出現(PDF内に攻撃文字列がそのまま検出)', text: SIG, ng: true });
    else if (bodyText.includes(MARKER)) findings.push({ kind: '文字として検出(エスケープされている可能性)', text: MARKER });
    else findings.push({ kind: 'PDFの生テキストからマーカーを検出できず(PDFが圧縮されている、注文時の商品名にマーカーが入っていない等の可能性。手動でPDFを開いて確認してください)', text: '' });
    rec.add({
      id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('検出できず') ? '要確認' : 'OK', label: `receipt_download.php(oid=${oid})`, status: dl.status, findings,
      scope: 'PDF内のテキスト検出はベストエフォート(圧縮されていると検出できない)。ダウンロード成功後はtbl_order.receipt_flgが立ち、この注文では再実行できない(再実行には新しい入金済み注文が必要)',
    });
    console.log(`E-60(領収書) receipt_download.php(oid=${oid}) ${findings.map((f) => f.kind).join(',')}`);
  } finally {
    await student.close();
  }
}

// 受講者サイトのマイページから、対象の注文明細(odid)の受講証をダウンロードする(GET。CSRF無し)。
// ticket_download.php の発行済みフラグ更新処理はコメントアウトされているため、副作用なく何度でも実行できる
async function downloadTicket(student, odid) {
  const res = await student.goto(`/mypage/ticket_download.php?odid=${odid}`);
  if (res.status === 'ERROR' || res.status === 'SSO') return { error: `受講証画面が開けない: ${res.status}` };
  return { ok: true, status: res.status, html: res.html };
}

// E-60(受講証): 人が事前に登録・購入した会場研修商品の注文明細ID(data-entries.json の ticket_order_detail_id)で
// 受講証をダウンロードし、商品名がPDFにエスケープされて出力されるか確認する(読み取りのみ。何度でも再実行できる)
async function checkTicket(rec) {
  const id = 'E-60';
  if (!want(id)) return;
  const odid = dataEntries.ticket_order_detail_id;
  if (!odid) {
    rec.add({ id, verdict: '対象外', note: 'test-plan/data-entries.json の ticket_order_detail_id が未設定(無料の会場研修商品を登録・購入し、作成された注文明細IDを人が記入してください。手順はdata-entries.jsonのコメント参照)' });
    console.log('skip E-60(受講証): ticket_order_detail_id が未設定');
    return;
  }
  const student = await new Session('student').open();
  try {
    const dl = await downloadTicket(student, odid);
    if (dl.error) { rec.add({ id, verdict: '要確認', note: dl.error, label: `mypage/ticket_download.php(odid=${odid})` }); return; }
    const findings = [];
    if (dl.status >= 400) findings.push({ kind: `HTTP ${dl.status}`, text: '' });
    const bodyText = dl.html || '';
    if (bodyText.includes(SIG)) findings.push({ kind: '未エスケープで出現(PDF内に攻撃文字列がそのまま検出)', text: SIG, ng: true });
    else if (bodyText.includes(MARKER)) findings.push({ kind: '文字として検出(エスケープされている可能性)', text: MARKER });
    else findings.push({ kind: 'PDFの生テキストからマーカーを検出できず(PDFが圧縮されている、注文時の商品名にマーカーが入っていない、odidが誤っている等の可能性。手動でPDFを開いて確認してください)', text: '' });
    rec.add({ id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('検出できず') ? '要確認' : 'OK', label: `ticket_download.php(odid=${odid})`, status: dl.status, findings, scope: 'PDF内のテキスト検出はベストエフォート(圧縮されていると検出できない)' });
    console.log(`E-60(受講証) ticket_download.php(odid=${odid}) ${findings.map((f) => f.kind).join(',')}`);
  } finally {
    await student.close();
  }
}

// E-20: 人が issue_submit テーブルへ直接SQLで投入したテスト行(issue_submit_name・issue_submit_logic_name・
// issue_submit_caption にマーカーを含む)が、CMSの課題確認画面(/cms_issue/detail/{issue_id})にエスケープされて
// 表示されるかを見る(読み取りのみ)。受講者側に提出画面が無いため、データ投入は人が行う(data-entries.json参照)
async function checkE20(rec) {
  const id = 'E-20';
  if (!want(id)) return;
  const issueId = dataEntries.issue_id;
  if (!issueId) {
    rec.add({ id, verdict: '対象外', note: 'test-plan/data-entries.json の issue_id が未設定(テスト用の課題を作成しissue_submitへSQLでテスト行を投入後、issue_idを記入してください。手順はdata-entries.jsonのコメント参照)' });
    console.log('skip E-20: issue_id が未設定');
    return;
  }
  const cms = await new Session('cms').open();
  try {
    const res = await cms.goto(`/cms_issue/detail/${issueId}`);
    if (res.status === 'ERROR' || res.status === 'SSO' || res.status >= 400) { rec.add({ id, verdict: '要確認', note: `画面が開けない: HTTP ${res.status}` }); return; }
    const findings = [];
    const at = res.html.indexOf(SIG);
    if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(res.html, at), text: SIG, ng: true });
    else if (res.html.includes(MARKER)) findings.push({ kind: '文字として表示(エスケープ済み)', text: MARKER });
    else findings.push({ kind: '提出一覧にマーカーが見当たらない(issue_submitへのテスト行投入が済んでいないか、issue_idが違う可能性)', text: '' });
    const dom = await domInjection(cms.page, [MARKER]);
    for (const d of dom) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
    rec.add({ id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('見当たらない') ? '要確認' : 'OK', label: `cms_issue/detail/${issueId}`, status: res.status, findings });
    console.log(`E-20 cms_issue/detail/${issueId} ${findings.map((f) => f.kind).join(',')}`);
  } finally {
    await cms.close();
  }
}

// E-14: 受講者検索ポップアップ(search_student.php)に、登録したテスト受講者の氏名で検索をかけ、
// 検索結果の一覧(student_name)にマーカーがエスケープされて表示されるかを見る
async function checkE14(rec) {
  const id = 'E-14';
  if (!want(id)) return;
  if (!acceptWrites) { rec.add({ id, verdict: '対象外', note: '受講者登録(書き込み)を伴う。--accept-writes を付けて実行してください' }); return; }
  const cms = await new Session('cms').open();
  const student = await new Session('student').open();
  try {
    const reg = await registerMarkerStudent(student);
    if (reg.error) { rec.add({ id, verdict: '要確認', note: reg.error }); return; }
    const url = '/alfproduct/product/search_student.php?gid=xsstest';
    const first = await cms.goto(url);
    if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) { rec.add({ id, verdict: '要確認', note: `画面が開けない: HTTP ${first.status}` }); return; }
    const found = (await fieldForms(cms.page, ['search_student_name']))[0];
    if (!found || !found.found) { rec.add({ id, verdict: '要確認', note: 'search_student_name 欄が見つからない(画面構成が変わった可能性)' }); return; }
    await setFields(cms.page, [{ name: 'search_student_name', value: MARKER }]);
    const result = await submitGuarded(cms);
    if (result.refused) { rec.add({ id, verdict: '要確認', note: `検索を送信できない: ${result.refused}` }); return; }
    const findings = [];
    const at = result.html.indexOf(SIG);
    if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(result.html, at), text: SIG, ng: true });
    else if (result.html.includes(MARKER)) findings.push({ kind: '文字として表示(エスケープ済み)', text: MARKER });
    else findings.push({ kind: '検索結果にマーカーが見当たらない(検索条件・項目名が変わった可能性)', text: '' });
    const dom = await domInjection(cms.page, [MARKER]);
    for (const d of dom) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
    rec.add({ id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('見当たらない') ? '要確認' : 'OK', label: 'product/search_student.php', status: result.status, findings });
    console.log(`E-14 search_student.php ${findings.map((f) => f.kind).join(',')}`);
  } finally {
    await cms.close();
    await student.close();
  }
}

// 商品管理でカテゴリ(cms_category)を新規登録する(wp_terms/wp_term_taxonomyへ直接書き込まれる。S12-catと同じ仕組み)
async function registerMarkerCategory(cms) {
  const url = '/cms_category/newdata';
  const first = await cms.goto(url);
  if (first.status === 'ERROR' || first.status === 'SSO' || first.status >= 400) return { error: `画面が開けない: HTTP ${first.status}` };
  const found = (await fieldForms(cms.page, ['name']))[0];
  if (!found || !found.found) return { error: `${url} に name 欄がない(画面構成が変わった可能性)` };
  await fillBaseline(cms.page, 'name', overridesFor(url), true);
  await setFields(cms.page, [{ name: 'name', value: NAME_PAYLOAD }]);
  const confirmRes = await submitGuarded(cms);
  if (confirmRes.refused) return { error: `確認画面へ進めない: ${confirmRes.refused}` };
  const completeRes = await submitComplete(cms);
  if (completeRes.refused) return { error: `登録を完了できない: ${completeRes.refused}` };
  console.log(`カテゴリを登録しました: ${NAME_PAYLOAD} -> ${completeRes.finalUrl}`);
  return { ok: true };
}

// E-49: 登録したカテゴリ名が、受講者サイトの商品検索(スマホ版)の画面内スクリプト(arr_cat_name)に
// エスケープされて出るかを見る(全量一覧No.49/コミットae445150の退行確認)。カテゴリは/search/index.phpの
// SQLに絞り込み条件が無く全件が無条件で一覧に出る仕組みのため、検索操作は不要
const MOBILE_UA = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';
async function checkE49(rec) {
  const id = 'E-49';
  if (!want(id)) return;
  if (!acceptWrites) { rec.add({ id, verdict: '対象外', note: 'カテゴリ登録(書き込み)を伴う。--accept-writes を付けて実行してください' }); return; }
  const cms = await new Session('cms').open();
  const sp = await new Session('student', { contextOptions: { userAgent: MOBILE_UA } }).open();
  try {
    const reg = await registerMarkerCategory(cms);
    if (reg.error) { rec.add({ id, verdict: '要確認', note: reg.error }); return; }
    const res = await sp.goto('/search/index.php');
    if (res.status === 'ERROR' || res.status === 'SSO' || res.status >= 400) { rec.add({ id, verdict: '要確認', note: `画面が開けない(スマホ版UA): HTTP ${res.status}` }); return; }
    const findings = [];
    const at = res.html.indexOf(SIG);
    if (at >= 0) findings.push({ kind: '未エスケープで出現(画面内スクリプト)', where: classify(res.html, at), text: SIG, ng: true });
    else if (res.html.includes(MARKER)) findings.push({ kind: '文字として表示(エスケープ済み)', text: MARKER });
    else findings.push({ kind: 'カテゴリ一覧にマーカーが見当たらない(一覧の生成条件が変わった可能性)', text: '' });
    const dom = await domInjection(sp.page, [MARKER]);
    for (const d of dom) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
    rec.add({ id, verdict: findings.some((f) => f.ng) ? 'NG' : findings[0].kind.includes('見当たらない') ? '要確認' : 'OK', label: '/search/index.php(スマホ版UA)', status: res.status, findings });
    console.log(`E-49 search/index.php(スマホ版) ${findings.map((f) => f.kind).join(',')}`);
  } finally {
    await cms.close();
    await sp.close();
  }
}

(async () => {
  const rec = new Recorder('data-linked');
  console.log(`E-10・E-12・E-13・E-14・E-20・E-49・E-60 / --accept-writes=${acceptWrites}`);
  await checkE1213(rec);
  await checkE10(rec);
  await checkE14(rec);
  await checkE49(rec);
  await checkE20(rec);
  await checkReceipt(rec);
  await checkTicket(rec);
  const out = rec.save({ acceptWrites });
  const ids = [...new Set(rec.entries.map((e) => e.id))];
  const c = { NG: 0, '要確認': 0, OK: 0, '対象外': 0 };
  ids.forEach((id) => c[rec.worst(id)]++);
  console.log(`\n結果 ${ids.length} 件: NG ${c.NG} / 要確認 ${c['要確認']} / OK ${c.OK} / 対象外 ${c['対象外']}`);
  if (acceptWrites) console.log('※書き込みを伴うチェック(E-10/E-12/E-13/E-14/E-49)を実行した場合、確認後 stg2 の DB をダンプから復元してください。E-20・E-60(領収書・受講証)は読み取りのみです。');
  console.log(`結果: ${out}`);
})().catch((e) => { console.error(e.message); process.exit(1); });
