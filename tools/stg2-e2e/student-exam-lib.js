// run-xss-student-exam.js 専用の補助。試験・アンケート画面は、項目名が実行時に決まる(例: exam_problem_12345[])ため、
// 計画(plan.json)の「{動的}」という項目名を、実際の画面から見つけて使う。
const fs = require('fs');
const path = require('path');

const ENTRY_PATH = path.join(__dirname, 'test-plan/student-entry.json');

function loadEntry(key) {
  if (!fs.existsSync(ENTRY_PATH)) throw new Error(`${ENTRY_PATH} がありません`);
  const all = JSON.parse(fs.readFileSync(ENTRY_PATH, 'utf-8'));
  const e = all[key];
  if (!e) throw new Error(`test-plan/student-entry.json に "${key}" がありません`);
  return e;
}

// 必須の値が空でないか確認する。空があれば理由を返す（null なら揃っている）
function missing(entry, required) {
  const empty = required.filter((k) => !entry[k]);
  return empty.length ? `test-plan/student-entry.json の "${empty.join(', ')}" が未記入です（CMSでテスト専用の講座・試験を作成し、実際のIDを記入してください）` : null;
}

const qs = (obj, keys) => keys.filter((k) => obj[k]).map((k) => `${k}=${encodeURIComponent(obj[k])}`).join('&');

// 計画の項目名(例: exam_problem_{動的}[]・exam2_problem_{動的}[])から、接頭辞(exam_problem_)を取り出す
function prefixOf(templatedName) {
  const m = templatedName.match(/^([a-z0-9_]*?)\{動的\}\[\]$/i);
  return m ? m[1] : null;
}

// ページ上に実在する textarea(接頭辞に一致するもの)の、実際の name を列挙する
function findDynamicTextareas(page, prefixes) {
  return page.evaluate((pfxs) => [...document.querySelectorAll('textarea')]
    .map((el) => el.name)
    .filter((n) => n && pfxs.some((p) => n.startsWith(p) && n.endsWith('[]'))), prefixes);
}

function fillTextareaByName(page, name, value) {
  return page.evaluate(({ name, value }) => {
    const el = document.querySelector(`textarea[name="${CSS.escape(name)}"]`);
    if (!el) return false;
    el.value = value;
    return true;
  }, { name, value });
}

module.exports = { loadEntry, missing, qs, prefixOf, findDynamicTextareas, fillTextareaByName };
