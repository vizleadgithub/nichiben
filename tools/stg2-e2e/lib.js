// stg2 ブラウザ自動テストの共通処理（環境変数の読み込み・ブラウザコンテキストの生成）
const path = require('path');
const fs = require('fs');
require('dotenv').config({ path: path.join(__dirname, '../../.env.stg2'), quiet: true });
const { chromium } = require('playwright');

const AUTH_DIR = path.join(__dirname, '.auth');
const RESULT_DIR = path.join(__dirname, 'test-results');

const env = (key) => {
  const v = process.env[key];
  if (!v) throw new Error(`${key} が .env.stg2 に設定されていません`);
  return v;
};

// 誤って stg2 以外へアクセスしないための確認
function assertStg2(url) {
  const host = new URL(url).hostname;
  if (!/(^|\.)nichibenren-stg2\.alfcloud\.com$/.test(host)) {
    throw new Error(`stg2 以外のURLは対象外です: ${host}`);
  }
}

const SITES = {
  cms: { baseUrl: () => env('STG2_CMS_URL'), state: path.join(AUTH_DIR, 'cms.json') },
  student: { baseUrl: () => env('STG2_STUDENT_URL'), state: path.join(AUTH_DIR, 'student.json') },
};

async function newContext(browser, site, statePath, extra = {}) {
  const opts = {
    httpCredentials: { username: env('STG2_BASIC_USER'), password: env('STG2_BASIC_PASS') },
    ignoreHTTPSErrors: false,
    ...extra,   // userAgent・viewport 等(E-09のスマホ版確認など)を個別に上書きする場合に使う
  };
  const state = statePath || (site && SITES[site].state);
  if (state && fs.existsSync(state)) opts.storageState = state;
  return browser.newContext(opts);
}

module.exports = { chromium, env, assertStg2, SITES, AUTH_DIR, RESULT_DIR, newContext };
