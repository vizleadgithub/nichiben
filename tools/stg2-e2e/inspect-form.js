// 入力画面の入力欄を一覧にして、test-plan/form-defaults.json に何を指定するかを調べる補助。読み取り専用（確認画面までは進めない）。
// 実行中の別の自動テストとセッションが衝突しないよう、調査用に別のログイン状態(.auth/cms-inspect.json)を使う。
// 使い方: node inspect-form.js cms_class/edit,cms_exam/edit ...
const path = require('path');
const { chromium, env, assertStg2, AUTH_DIR, newContext } = require('./lib');
const { Session } = require('./xss-session');

const STATE = path.join(AUTH_DIR, 'cms-inspect.json');

async function login() {
  const base = env('STG2_CMS_URL');
  assertStg2(base);
  const browser = await chromium.launch();
  const ctx = await newContext(browser);
  const page = await ctx.newPage();
  await page.goto(base);
  await page.fill('input[name="login_id"]', env('STG2_CMS_USER'));
  await page.fill('input[name="password"]', env('STG2_CMS_PASS'));
  await Promise.all([page.waitForNavigation(), page.click('form input[type="submit"]')]);
  if (page.url().includes('/school_select')) {
    await page.check(`input[name="school_id"][value="${process.env.STG2_CMS_SCHOOL_ID || '1'}"]`);
    await Promise.all([page.waitForNavigation(), page.click('form input[type="image"]')]);
  }
  await ctx.storageState({ path: STATE });
  await browser.close();
}

module.exports = { login, STATE };

if (require.main === module) (async () => {
  const urls = (process.argv[2] || '').split(',').filter(Boolean).map((u) => '/' + u.replace(/^\/+/, ''));
  if (!urls.length) throw new Error('使い方: node inspect-form.js cms_class/edit,...');
  await login();
  const s = await new Session('cms', { statePath: STATE }).open();
  for (const url of urls) {
    let r = await s.goto(url.replace(/edit$/, 'newdata'));
    let entry = url.replace(/edit$/, 'newdata');
    if (r.status >= 400 || !(await s.page.evaluate(() => document.forms.length))) { r = await s.goto(url); entry = url; }
    const info = await s.page.evaluate(() => [...document.forms].map((f) => ({
      action: f.getAttribute('action'),
      controls: [...f.elements].filter((e) => e.name && !['submit', 'button', 'image', 'reset'].includes(e.type)).map((e) => {
        const o = { n: e.name, t: e.type || e.tagName.toLowerCase() };
        if (e.tagName === 'SELECT') o.opts = [...e.options].slice(0, 4).map((x) => `${x.value}:${x.textContent.trim().slice(0, 14)}`).concat(e.options.length > 4 ? [`…計${e.options.length}`] : []);
        if (e.type === 'hidden') o.v = (e.value || '').slice(0, 20);
        if (e.type === 'radio' || e.type === 'checkbox') o.v = e.value;
        return o;
      }),
    })).filter((f) => f.controls.length));
    console.log(`\n== ${url} (入口: ${entry})`);
    for (const f of info) {
      console.log(`  form action=${f.action}`);
      const seen = new Set();
      for (const c of f.controls) {
        const k = c.n + c.t;
        if (seen.has(k)) continue;
        seen.add(k);
        console.log(`    ${c.n} [${c.t}]${c.opts ? ' ' + c.opts.join(' | ') : ''}${c.v !== undefined && c.t !== 'hidden' ? ' value=' + c.v : ''}${c.t === 'hidden' ? ' v=' + c.v : ''}`);
      }
    }
  }
  await s.close();
})().catch((e) => { console.error(e.message); process.exit(1); });
