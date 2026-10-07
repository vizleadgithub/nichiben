// CMS 管理者・受講者(SSO経由)でログインし、セッションを .auth/ に保存する。
// 受講者 SSO は本番と共用のため、ログインはこのスクリプトで1回だけ行い、以降は保存したセッションを使い回す。
// 使い方: node login.js [cms|student|student-ethic]   (省略時は cms・student の両方。student-ethic は明示指定時のみ)
const fs = require('fs');
const path = require('path');
const { chromium, env, assertStg2, SITES, AUTH_DIR, newContext } = require('./lib');

async function loginCms(browser) {
  const base = env('STG2_CMS_URL');
  assertStg2(base);
  const context = await newContext(browser);
  const page = await context.newPage();
  await page.goto(base);
  await page.fill('input[name="login_id"]', env('STG2_CMS_USER'));
  await page.fill('input[name="password"]', env('STG2_CMS_PASS'));
  await Promise.all([page.waitForNavigation(), page.click('form input[type="submit"]')]);
  if (page.url().includes('/login_page')) throw new Error('CMS ログインに失敗しました（ログイン画面のまま）');
  // ログイン直後は学校選択画面。日弁連(school_id=1, alflearning-global-config.php の nichibenren_school_id)を選ぶ
  if (page.url().includes('/school_select')) {
    const schoolId = process.env.STG2_CMS_SCHOOL_ID || '1';
    await page.check(`input[name="school_id"][value="${schoolId}"]`);
    await Promise.all([page.waitForNavigation(), page.click('form input[type="image"]')]);
    if (page.url().includes('/school_select')) throw new Error('学校選択に失敗しました');
  }
  await context.storageState({ path: SITES.cms.state });
  console.log('cms: ログイン成功 ->', new URL(page.url()).pathname);
  await context.close();
}

async function loginStudentAs(browser, { user, pass, statePath }) {
  const base = env('STG2_STUDENT_URL');
  assertStg2(base);
  const context = await newContext(browser);
  const page = await context.newPage();
  await page.goto(base);
  const ssoHost = new URL(page.url()).hostname;
  if (ssoHost !== 'member.nichibenren.or.jp') throw new Error(`想定外のSSOログイン画面です: ${ssoHost}`);
  await page.fill('input[name="id"]', user);
  await page.fill('input[name="pw"]', pass);
  await Promise.all([
    page.waitForURL((u) => u.hostname.endsWith('nichibenren-stg2.alfcloud.com'), { timeout: 60000 }),
    page.click('#btn-login'),
  ]);
  await page.waitForLoadState('networkidle');
  await context.storageState({ path: statePath });
  console.log(`student(${path.basename(statePath)}): ログイン成功 ->`, new URL(page.url()).pathname);
  await context.close();
}

async function loginStudent(browser) {
  return loginStudentAs(browser, { user: env('STG2_STUDENT_USER'), pass: env('STG2_STUDENT_PASS'), statePath: SITES.student.state });
}

// 代替倫理研修の権限を持つ別アカウント(STG2_STUDENT_ETHIC_USER)。通常の受講者アカウントとは別人・別セッションのため、
// 保存先を分ける(.auth/student-ethic.json)。値が未記入のアカウントを使うテストはスキップする方針のため、未設定なら何もしない
const STUDENT_ETHIC_STATE = path.join(AUTH_DIR, 'student-ethic.json');
async function loginStudentEthic(browser) {
  if (!process.env.STG2_STUDENT_ETHIC_USER || !process.env.STG2_STUDENT_ETHIC_PASS) {
    console.log('student-ethic: STG2_STUDENT_ETHIC_USER/STG2_STUDENT_ETHIC_PASS が未設定のためスキップします');
    return;
  }
  return loginStudentAs(browser, { user: process.env.STG2_STUDENT_ETHIC_USER, pass: process.env.STG2_STUDENT_ETHIC_PASS, statePath: STUDENT_ETHIC_STATE });
}

module.exports = { loginCms, loginStudent, loginStudentEthic, STUDENT_ETHIC_STATE };

if (require.main === module) {
  (async () => {
    fs.mkdirSync(AUTH_DIR, { recursive: true });
    const target = process.argv[2];
    const browser = await chromium.launch();
    try {
      if (!target || target === 'cms') await loginCms(browser);
      if (!target || target === 'student') await loginStudent(browser);
      if (target === 'student-ethic') await loginStudentEthic(browser);
    } finally {
      await browser.close();
    }
  })().catch((e) => { console.error(e.message); process.exit(1); });
}
