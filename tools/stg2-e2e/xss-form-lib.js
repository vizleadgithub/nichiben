// run-xss-search.js / run-xss-form.js 共通: フォーム項目への入力・送信（書き込み防止のガード付き）・結果の検査
const { fill } = require('./xss-payloads');
const { debugFindings, classify, domInjection } = require('./detect');

// フォームの各項目へ値を入れる。select は選択肢を追加、radio/checkbox は value を書き換えてチェックする
// （画面上は入力できない値を直接送る改ざん相当）。送信するフォームに目印の属性を付ける
function setFields(page, fields) {
  return page.evaluate((fs) => fs.map(({ name, value }) => {
    const el = document.querySelector(`[name="${CSS.escape(name)}"]`);
    if (!el) return { name, found: false };
    if (el.form) el.form.setAttribute('data-xss-form', '1');
    const tag = el.tagName.toLowerCase();
    if (value === null) return { name, found: true, type: el.type || tag };   // 値は変えず、フォームの目印だけ付ける
    if (tag === 'select') {
      const o = document.createElement('option');
      o.value = value; o.textContent = value; el.appendChild(o); el.value = value;
    } else if (el.type === 'radio' || el.type === 'checkbox') {
      el.value = value; el.checked = true;
    } else {
      el.removeAttribute('readonly'); el.value = value;
    }
    return { name, found: true, type: el.type || tag };
  }), fields);
}

// 各項目が属するフォームの番号を調べる（フォームの外の項目は -1）
function fieldForms(page, names) {
  return page.evaluate((ns) => ns.map((name) => {
    const el = document.querySelector(`[name="${CSS.escape(name)}"]`);
    return { name, found: !!el, form: el && el.form ? [...document.forms].indexOf(el.form) : -1, type: el ? (el.type || el.tagName.toLowerCase()) : '' };
  }), names);
}

// 対象項目以外の入力欄に、検証を通る「ふつうの値」を入れる（確認画面まで進むため）。
// すでに値がある欄・選択済みの欄はそのまま。overrides: { 項目名: 値 }（画面ごとに必要なら test-plan/form-defaults.json で指定）
function fillBaseline(page, targetName, overrides = {}, includeTarget = false) {
  return page.evaluate(({ targetName, overrides, includeTarget }) => {
    const target = document.querySelector(`[name="${CSS.escape(targetName)}"]`);
    const form = target && target.form;
    if (!form) return { filled: 0 };
    const guess = (el) => {
      const n = (el.name || '').toLowerCase();
      if (el.type === 'email' || /mail/.test(n)) return 'xsstest@example.invalid';
      if (el.type === 'password') return 'Xsstest1234!';
      if (el.type === 'number' || /(^|_)(id|no|num|count|order|sort|price|point|limit|time|minute|hour)(_|$|\d)/.test(n)) return '1';
      if (el.type === 'date' || /date|_at$|day/.test(n)) return '2000-01-01';
      if (el.type === 'url' || /url/.test(n)) return 'https://example.jp/';
      if (el.type === 'tel' || /tel|phone|zip/.test(n)) return '0312345678';
      return '[XSSTEST] テスト';   // 氏名欄が「姓と名の間にスペース」を求めることがあるため、スペース入り
    };
    let filled = 0;
    const radioGroups = new Set();
    const checkboxFirstDone = new Set();
    for (const el of form.elements) {
      if ((el === target && !includeTarget) || !el.name || el.disabled) continue;
      const tag = el.tagName.toLowerCase();
      const ov = Object.prototype.hasOwnProperty.call(overrides, el.name) ? overrides[el.name] : undefined;
      // hidden は、form-defaults.json で指定された項目だけ値を入れる（画面に出ない必須項目が空のレコード用）
      if (el.type === 'hidden' && ov !== undefined) { el.value = ov; filled++; continue; }
      if (['hidden', 'submit', 'button', 'image', 'reset', 'file'].includes(el.type)) continue;
      if (tag === 'select') {
        if (ov !== undefined) { el.value = ov; filled++; continue; }
        if (!el.value) { const o = [...el.options].find((x) => x.value); if (o) { el.value = o.value; filled++; } }
      } else if (el.type === 'radio') {
        if (radioGroups.has(el.name)) continue;
        radioGroups.add(el.name);
        const group = [...form.querySelectorAll(`input[type=radio][name="${CSS.escape(el.name)}"]`)];
        if (!group.some((r) => r.checked)) { group[0].checked = true; filled++; }
      } else if (el.type === 'checkbox') {
        // ov === true: 同じ名前の全チェックボックスをチェックする（複数選択可の項目用）
        // ov === 'first': 同じ名前の最初の1つだけをチェックする（「正解は1つだけ」のような単一選択の項目用）
        if (ov === 'first') { if (!checkboxFirstDone.has(el.name)) { el.checked = true; checkboxFirstDone.add(el.name); filled++; } else el.checked = false; }
        else if (ov !== undefined) { el.checked = !!ov; filled++; }
      } else if (!el.value) {
        el.removeAttribute('readonly');
        el.value = ov !== undefined ? ov : guess(el);
        filled++;
      }
    }
    // 「+項目名」: 画面の読み込み時点では存在しない欄（ポップアップで選ぶ値など、JavaScript が後から追加する hidden）を追加する
    for (const [k, v] of Object.entries(overrides)) {
      if (!k.startsWith('+')) continue;
      for (const val of [].concat(v)) {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = k.slice(1); i.value = val;
        form.appendChild(i); filled++;
      }
    }
    return { filled };
  }, { targetName, overrides, includeTarget });
}

// 送信してよいボタンの判定（書き込み防止のガード）。確認・検索系のボタンだけ押し、登録・更新・削除系は押さない。
// ボタンの「文言・画像名」と「onclick / javascript: の中身」を分けて判定する（onclick の formSubmit(…,'confirm') の "Submit" を誤って送信系と見ないため）
// 「確認」系を優先し、見つからない場合だけ「検索」等も候補にする。編集画面には、本体の確認ボタンより前に
// 受講者・教材などを選ぶポップアップの「検索」ボタンが置かれていることがあり、先に見つかる方を押すと
// ポップアップを開くだけで確認画面へ進めない（cms_cource 等で確認済み）
const ALLOW_LABEL_PRIMARY = /確認|confirm/i;
// btn_revise: 商品管理のinfo.php(詳細表示)の「修正」ボタン(文言は画像のみ。ファイル名btn_revise.pngで
// 判定。onclick="formSubmit('form1','add.php','edit')")。add.php側のact='edit'処理は表示用のテンプレート
// 変数を設定するだけでDB書き込みが無いことをソースで確認済み(2026-10-08発見。info.phpのS11テストに必要)
const ALLOW_LABEL_SECONDARY = /検索|search|preview|プレビュー|次へ|next|btn_revise/i;
const ALLOW_LABEL = new RegExp(`${ALLOW_LABEL_PRIMARY.source}|${ALLOW_LABEL_SECONDARY.source}`, 'i');
const DENY_LABEL = /登録|更新|削除|送信|実行|決定|完了|退会|ログアウト|logout|commit|regist|delete|remove|save|send|update|insert|upload|import|csv|complete/i;
// onclick の引数（'complete'・'regist'・'delete' など）が書き込み系なら、確認系の文言があっても押さない
const DENY_ARG = /^(complete|regist\w*|commit|delete\w*|del|remove|exec\w*|save|update\w*|insert|upload\w*|import\w*|send\w*|cancel|reset|clear|logout|approve\w*)$/i;
const DENY_ACTION = /commit|regist|insert|update|delete|del_|remove|save|exec|complete|send|upload|import|csv|download|approve|cancel|reset|clear|logout|bat_/i;
// conf.php はこのアプリで「確認画面」に使われるファイル名の慣例(mailmagazine/conf.php・inquiry/conf.php等で既出)。
// 受講者サイトの問い合わせフォーム(inquiry/index.php→conf.php)のボタン文言が「送信する」(DENY_LABEL)のため、
// ボタン単位では安全と判定できず、送信先URLの判定でも拾えていなかった(2026-10-08発見)
const CONFIRM_ACTION = /confirm|check|valid|preview|conf\.php$/i;
const BUTTONS = 'button, input[type=submit], input[type=button], input[type=image], a, img[onclick]';

const denyByArgs = (o) => [...String(o).matchAll(/['"]([^'"]*)['"]/g)].some((m) => DENY_ARG.test(m[1].replace(/\.php$/i, '')));
const ok = (c, re) => (re.test(c.t) || re.test(c.o)) && !DENY_LABEL.test(c.t) && !denyByArgs(c.o);
const evalInfo = (loc) => loc.evaluateAll((els) => els.map((e) => ({
  t: `${e.value || ''}${e.textContent || ''}${e.alt || ''}${e.title || ''}${(e.getAttribute('src') || '').split('/').pop()}${(e.querySelector && e.querySelector('img') ? (e.querySelector('img').getAttribute('src') || '').split('/').pop() : '')}`,
  o: `${e.getAttribute('onclick') || ''} ${/^javascript:/i.test(e.getAttribute('href') || '') ? e.getAttribute('href') : ''}`,
  // e.form: HTML の form IDL 属性。壊れた(閉じタグが無い等の)HTMLでは、見た目上は<form>の外にある要素でも
  // ブラウザの実際のDOM構築ではその<form>に関連付けられることがある(CSSの子孫セレクタでは見つからない。
  // cms_ranking等で発見。2026-10-08)。この場合でも送信時にはその関連付けに従って送信される
  formId: e.form ? (e.form.id || e.form.name || '') : '',
})));
// 商品登録(product/add.php 等)のような、ファイル欄が数百個ありHTMLが数MBになる画面では、BUTTONS(全候補)を
// evaluateAll するだけで非常に時間がかかり(実測90秒超)、確認ボタンを見失うことがあった(2026-10-08 発見)。
// 実際の確認ボタンは onclick="formSubmit(...,'confirm')" のような形が大半のため、まずそれだけに絞った
// 狭いセレクタで探し、見つかればそれを使う（従来の判定(ok/ALLOW_LABEL)はそのまま使うため、見つけた場合の
// 結果は全候補を評価したときと同じになる）。見つからない場合だけ、従来どおり全候補を評価する
const FAST_CONFIRM = 'a[onclick*="confirm" i], a[href*="confirm" i], button[onclick*="confirm" i], input[onclick*="confirm" i], button:has-text("確認"), input[type=submit][value*="確認"], input[type=image][alt*="確認"]';

// 範囲(フォームまたはページ)内のボタンを調べ、押してよいものの番号を返す
async function pickButton(scope) {
  const fast = scope.locator(FAST_CONFIRM);
  if (await fast.count().catch(() => 0) > 0) {
    const info = await evalInfo(fast);
    const idx = info.findIndex((c) => ok(c, ALLOW_LABEL_PRIMARY));
    if (idx >= 0) return { cand: fast, info, idx };
  }
  const cand = scope.locator(BUTTONS);
  const info = await evalInfo(cand);
  let idx = info.findIndex((c) => ok(c, ALLOW_LABEL_PRIMARY));
  if (idx < 0) idx = info.findIndex((c) => ok(c, ALLOW_LABEL_SECONDARY));
  return { cand, info, idx };
}

// 目印のあるフォームを、確認・検索ボタンで送信する。送信できない（書き込みの可能性がある）場合は { refused } を返す。
// acceptWrites: true の場合、安全な確認・検索ボタンが見つからなかったときに限り、登録・更新系のボタン
// (COMPLETE_LABEL。cms_issue/newdata等、確認画面を経由せず直接登録される画面向け)を探して押す(実際に書き込む。
// 段階B。決済・メール送信を伴う画面は呼び出し側で対象から除外すること)
async function submitGuarded(session, { allowFormSubmit = true, acceptWrites = false } = {}) {
  const { page } = session;
  let form = page.locator('form[data-xss-form]').first();
  const action = (await form.getAttribute('action').catch(() => '')) || '';
  const actionPath = new URL(action || page.url(), page.url()).pathname + new URL(action || page.url(), page.url()).search;
  // まずボタン単位の判定(文言・onclick引数を見る pickButton/ok)を行い、安全なボタンが見つからない場合に限って
  // フォームの送信先URLを見る。自己postingフォーム(member/regist.php・product/add.php等。隠しact欄で
  // 確認/登録を切り替える設計)は、送信先URLが"regist"等の書き込み系の語を含むことがあり、URLだけで先に
  // 判定すると、実際には安全な「確認」ボタンがあってもその中身を見る前に拒否してしまっていた(2026-10-08発見。
  // member/regist.phpのregisterMarkerStudent()が常に失敗していた原因)。
  // まずフォームの中、なければページ全体（商品管理は確認ボタンがフォームの外の <a onclick="formSubmit(…,'confirm')"> のことがある）
  let { cand, info, idx } = await pickButton(form);
  if (idx < 0) {
    // フォームの外のボタンは、onclick がこのフォームの名前・id を指しているものだけ採用する（別のフォームの検索ボタンなどを押さない）
    const formId = (await form.getAttribute('name').catch(() => '')) || (await form.getAttribute('id').catch(() => '')) || '';
    ({ cand, info, idx } = await pickButton(page.locator('body')));
    if (idx >= 0 && !(formId && info[idx].o.includes(formId))) idx = -1;
  }
  if (idx < 0) {
    // 対象項目がページ内の小さな付随フォーム(ファイルアップロード用等)に属しており、本来の送信先は
    // 別の<form>(action が confirm 等)であることがある(cms_ranking等。項目ごとに別々の<form>で囲まれて
    // いるが、実際の送信はそれらとは独立した1つのメインフォームで行う作り)。さらにそのメインフォームの
    // 実際のボタン群が、壊れたHTML(閉じタグの不足等)によりDOM上は<form>の子孫にならず、CSSの子孫
    // セレクタ(scope.locator(...))では見つからないことがある。ブラウザのform IDL属性(evalInfoのformId。
    // 子孫関係ではなく実際の関連付け)で照合することで見つける(cms_ranking等。2026-10-08発見)
    const otherForms = page.locator('form:not([data-xss-form])');
    const otherCount = await otherForms.count().catch(() => 0);
    for (let i = 0; i < otherCount && idx < 0; i++) {
      const otherForm = otherForms.nth(i);
      const otherAction = (await otherForm.getAttribute('action').catch(() => '')) || '';
      if (!otherAction || !CONFIRM_ACTION.test(new URL(otherAction, page.url()).pathname)) continue;
      const otherFormId = (await otherForm.getAttribute('id').catch(() => '')) || (await otherForm.getAttribute('name').catch(() => '')) || '';
      if (!otherFormId) continue;
      const byAssoc = info.reduce((a, c, j) => (c.formId === otherFormId ? [...a, j] : a), []);
      const foundIdx = byAssoc.find((j) => ok(info[j], ALLOW_LABEL_PRIMARY)) ?? byAssoc.find((j) => ok(info[j], ALLOW_LABEL_SECONDARY));
      if (foundIdx !== undefined) { idx = foundIdx; form = otherForm; break; }
    }
  }
  if (idx < 0) {
    // 一覧画面の並び替え・ページング等、ボタンではなく<select>のonchange(外部JS)で自動送信される
    // GET送信のフォームは、HTTPの意味上書き込みを伴わないため、ボタンが見つからなくてもそのまま送信してよい
    // (2026-10-08発見。product/list_limit.php等、多くの一覧画面がこのパターンで停止していた)
    const method = await form.evaluate((f) => f.method).catch(() => '');
    if (method === 'get') {
      return session.action(() => form.evaluate((f) => f.requestSubmit()));
    }
    // 書き込みを許容する場合(段階B)は、登録・更新系のボタン(COMPLETE_LABEL)を対象フォーム内から探して押す。
    // newdata→confirmの中間段階が無く、入力からそのまま登録される画面(cms_issue/newdata等)向け
    if (acceptWrites) {
      const completeInfo = await evalInfo(form);
      let completeIdx = completeInfo.findIndex((c) => COMPLETE_LABEL.test(c.t) || COMPLETE_LABEL.test(c.o));
      let completeCand = form.locator(BUTTONS);
      if (completeIdx < 0) {
        // 文言が無い画像ボタン(<input type=image src="btn_ok.png"> 等。alt/value/onclickが無く、
        // ファイル名からも意味を判定できない)だけの画面向け。ここまでで確認・検索ボタン(pickButton)も
        // COMPLETE_LABELも見つからなかった場合、フォーム内の「本物の送信コントロール」(submit/image。
        // <a>やonclick付きimg等、押しても送信されない物は対象外)のうち、明らかな削除・キャンセル等
        // (DENY_LABEL)ではないものを最後の手段として対象にする(cms_issue/newdata等。2026-10-08発見)
        const generic = form.locator('input[type=submit], input[type=image], button[type=submit], button:not([type])');
        const genericInfo = await evalInfo(generic);
        const genericIdx = genericInfo.findIndex((c) => !DENY_LABEL.test(c.t) && !DENY_LABEL.test(c.o));
        if (genericIdx >= 0) { completeCand = generic; completeIdx = genericIdx; }
      }
      if (completeIdx >= 0) {
        return session.action(() => completeCand.nth(completeIdx).click({ timeout: 3000 }).catch(() => form.evaluate((f) => f.requestSubmit())));
      }
      // フォーム内にボタンが1つも無い(他の要素のonclickからJSで直接submit()される作り。
      // 受講者サイトのお気に入り登録フォーム(favoriteForm)等。2026-10-08発見)場合、
      // クリックする対象が無いため直接送信する
      const anyButton = await form.locator(BUTTONS).count().catch(() => 0);
      if (anyButton === 0) {
        return session.action(() => form.evaluate((f) => f.requestSubmit()));
      }
    }
    // 安全と判定できるボタンが見つからない場合のみ、フォームの送信先URLを見る。書き込み系の語を含み、
    // かつ確認系の語(confirm等)を含まないなら、そのまま送信するのは危険なので拒否する
    if (DENY_ACTION.test(actionPath) && !CONFIRM_ACTION.test(actionPath)) {
      return { refused: `フォームの送信先が書き込み系の可能性があるため送信しない: ${actionPath.slice(0, 80)}` };
    }
    const byAction = CONFIRM_ACTION.test(actionPath);
    if (!(byAction && allowFormSubmit)) {
      return { refused: `確認・検索ボタンを特定できない（書き込みの可能性があるため送信しない）。ボタン: ${info.map((c) => c.t.slice(0, 20)).join(' | ').slice(0, 100)}` };
    }
  }
  return session.action(async () => {
    if (idx >= 0) await cand.nth(idx).click({ timeout: 3000 }).catch(() => form.evaluate((f) => f.requestSubmit()));
    else await form.evaluate((f) => f.requestSubmit());
  });
}

// 1回の送信結果を検査して、行ごとの検出内容を返す。injected: [{ id, marker, field, payload, type }]
async function inspect(session, res, injected, baseDialogs) {
  const markers = injected.map((i) => i.marker);
  const dom = await domInjection(session.page, markers);
  const dbg = res.html ? debugFindings(res.html) : [];
  return injected.map((i) => {
    const findings = [];
    const mine = (s) => s.includes(i.marker);
    for (const d of session.dialogs) {
      if (baseDialogs.has(d)) continue;
      if (mine(d) || d === '1') findings.push({ kind: 'ダイアログ実行', text: d, ng: true });
    }
    for (const d of dom) {
      if (d.value.includes(i.marker) || d.ng) findings.push({ kind: 'DOM上のイベント属性/JSリンク', text: `<${d.tag} ${d.attr}="${d.value}">`, ng: true });
    }
    if (i.payload.sig) {
      const sig = fill(i.payload.sig, i.marker);
      const at = res.html.indexOf(sig);
      if (at >= 0) findings.push({ kind: '未エスケープで出現', where: classify(res.html, at), text: sig.slice(0, 120) });
    }
    if (i.payload.roundtrip && i.type && /text|search|textarea/.test(i.type) && !session.dialogs.length) {
      findings.push({ kind: '__roundtrip__', value: fill(i.payload.v, i.marker), name: i.field });
    }
    if (i.payload.errCheck) for (const f of dbg) findings.push({ kind: `確認用出力(${f.check})`, text: f.text });
    if (res.status >= 500) findings.push({ kind: `HTTP ${res.status}`, text: '' });
    return findings;
  });
}

// 入力欄に入れた値が、送信後の画面に同じ値で再表示されること（二重エスケープ・欠落・書き換えの検出）。__roundtrip__ を置き換える
async function resolveRoundtrip(session, res, findings) {
  const rt = findings.find((f) => f.kind === '__roundtrip__');
  const rest = findings.filter((f) => f.kind !== '__roundtrip__');
  if (rt && res.navigated) {
    const shown = await session.page.evaluate((n) => {
      const el = document.querySelector(`[name="${CSS.escape(n)}"]`);
      return el ? el.value : null;
    }, rt.name);
    if (shown !== null && shown !== rt.value) rest.push({ kind: '再表示された値が入力と異なる', text: `入力: ${rt.value.slice(0, 60)} / 再表示: ${String(shown).slice(0, 60)}` });
  }
  return rest;
}

// 「確認」から先へ進み、実際に登録(commit)するボタンを押す。submitGuarded とは逆に、登録・更新系のボタンを探す。
// S12（登録→全画面で表示を確認）のように、書き込みを前提にした段階B専用。普段の確認段階テスト(run-xss-form.js)では使わない。
// 確認画面は edit 画面とは別の新しいページ（data-xss-form の目印は付いていない）なので、画面内の最初のフォームを対象にする
const COMPLETE_LABEL = /登録|更新|完了|決定|commit|regist\w*|complete|update\w*/i;
async function submitComplete(session) {
  const { page } = session;
  // 確認画面にフォームが複数ある場合（ヘッダーの検索欄等）もあるため、ページ全体からボタンを探す
  const cand = page.locator(BUTTONS);
  const info = await cand.evaluateAll((els) => els.map((e) => ({
    t: `${e.value || ''}${e.textContent || ''}${e.alt || ''}${e.title || ''}${(e.getAttribute('src') || '').split('/').pop()}`,
    o: `${e.getAttribute('onclick') || ''} ${/^javascript:/i.test(e.getAttribute('href') || '') ? e.getAttribute('href') : ''}`,
  })));
  const idxs = info.reduce((a, c, i) => ((COMPLETE_LABEL.test(c.t) || COMPLETE_LABEL.test(c.o)) ? [...a, i] : a), []);
  if (!idxs.length) return { refused: `登録ボタンを特定できない。ボタン: ${info.map((c) => c.t.slice(0, 20)).join(' | ').slice(0, 100)}` };
  // 文言が一致する候補が複数ある場合(ページ内のナビゲーション等が偶然一致することがある。2026-10-08発見)、
  // 最初の候補が実際にはクリックできない(非表示等)ことがあるため、クリックできる候補が見つかるまで順に試す
  for (let i = 0; i < idxs.length; i++) {
    try {
      return await session.action(() => cand.nth(idxs[i]).click({ timeout: 3000 }));
    } catch (e) {
      if (i === idxs.length - 1) throw e;
    }
  }
}

module.exports = { setFields, fieldForms, fillBaseline, submitGuarded, submitComplete, inspect, resolveRoundtrip };
