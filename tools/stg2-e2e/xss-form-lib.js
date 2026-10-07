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
        if (ov !== undefined) { el.checked = !!ov; filled++; }
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
const ALLOW_LABEL = /確認|confirm|検索|search|preview|プレビュー|次へ|next/i;
const DENY_LABEL = /登録|更新|削除|送信|実行|決定|完了|commit|regist|delete|remove|save|send|update|insert|upload|import|csv|complete/i;
// onclick の引数（'complete'・'regist'・'delete' など）が書き込み系なら、確認系の文言があっても押さない
const DENY_ARG = /^(complete|regist\w*|commit|delete\w*|del|remove|exec\w*|save|update\w*|insert|upload\w*|import\w*|send\w*|cancel|reset|clear|logout|approve\w*)$/i;
const DENY_ACTION = /commit|regist|insert|update|delete|del_|remove|save|exec|complete|send|upload|import|csv|download|approve|cancel|reset|clear|logout|bat_/i;
const CONFIRM_ACTION = /confirm|check|valid|preview/i;
const BUTTONS = 'button, input[type=submit], input[type=button], input[type=image], a, img[onclick]';

const denyByArgs = (o) => [...String(o).matchAll(/['"]([^'"]*)['"]/g)].some((m) => DENY_ARG.test(m[1].replace(/\.php$/i, '')));

// 範囲(フォームまたはページ)内のボタンを調べ、押してよいものの番号を返す
async function pickButton(scope) {
  const cand = scope.locator(BUTTONS);
  const info = await cand.evaluateAll((els) => els.map((e) => ({
    t: `${e.value || ''}${e.textContent || ''}${e.alt || ''}${e.title || ''}${(e.getAttribute('src') || '').split('/').pop()}${(e.querySelector && e.querySelector('img') ? (e.querySelector('img').getAttribute('src') || '').split('/').pop() : '')}`,
    o: `${e.getAttribute('onclick') || ''} ${/^javascript:/i.test(e.getAttribute('href') || '') ? e.getAttribute('href') : ''}`,
  })));
  const idx = info.findIndex((c) => (ALLOW_LABEL.test(c.t) || ALLOW_LABEL.test(c.o)) && !DENY_LABEL.test(c.t) && !denyByArgs(c.o));
  return { cand, info, idx };
}

// 目印のあるフォームを、確認・検索ボタンで送信する。送信できない（書き込みの可能性がある）場合は { refused } を返す
async function submitGuarded(session, { allowFormSubmit = true } = {}) {
  const { page } = session;
  const form = page.locator('form[data-xss-form]').first();
  const action = (await form.getAttribute('action').catch(() => '')) || '';
  const actionPath = new URL(action || page.url(), page.url()).pathname + new URL(action || page.url(), page.url()).search;
  if (DENY_ACTION.test(actionPath) && !CONFIRM_ACTION.test(actionPath)) {
    return { refused: `フォームの送信先が書き込み系の可能性があるため送信しない: ${actionPath.slice(0, 80)}` };
  }
  // まずフォームの中、なければページ全体（商品管理は確認ボタンがフォームの外の <a onclick="formSubmit(…,'confirm')"> のことがある）
  let { cand, info, idx } = await pickButton(form);
  if (idx < 0) {
    // フォームの外のボタンは、onclick がこのフォームの名前・id を指しているものだけ採用する（別のフォームの検索ボタンなどを押さない）
    const formId = (await form.getAttribute('name').catch(() => '')) || (await form.getAttribute('id').catch(() => '')) || '';
    ({ cand, info, idx } = await pickButton(page.locator('body')));
    if (idx >= 0 && !(formId && info[idx].o.includes(formId))) idx = -1;
  }
  const byAction = CONFIRM_ACTION.test(actionPath);
  if (idx < 0 && !(byAction && allowFormSubmit)) {
    return { refused: `確認・検索ボタンを特定できない（書き込みの可能性があるため送信しない）。ボタン: ${info.map((c) => c.t.slice(0, 20)).join(' | ').slice(0, 100)}` };
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

module.exports = { setFields, fieldForms, fillBaseline, submitGuarded, inspect, resolveRoundtrip };
