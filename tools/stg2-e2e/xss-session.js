// run-xss-*.js 共通: ブラウザセッション（保存済みログインの利用・ダイアログ/JSエラーの収集・CMS の自動再ログイン）
const fs = require('fs');
const { chromium, assertStg2, SITES, newContext } = require('./lib');
const { loginCms } = require('./login');

const SESSION_LOST = /\/login_page|member\.nichibenren\.or\.jp/;
// 受講者 SSO（OpenAM）は本番と共用。XSS 用の値を送らないよう、SSO ドメインへの通信はブラウザ側で遮断する
// 未ログイン・セッション切れの受講者サイトは、どのURLでも SSO へ JS リダイレクトするページ(200)を返す
const SSO_BOUNCE = /location\.href\s*=\s*["']https?:\/\/(member\.nichibenren\.or\.jp|www\.nichibenren-member-sso\.jp)/;
const SSO_HOSTS = /(^|\.)nichibenren(-member-sso)?\.(or\.jp|jp)$/;

class Session {
  constructor(site, { anonymous = false } = {}) {
    if (!SITES[site]) throw new Error('site は cms か student を指定してください');
    this.site = site;
    this.anonymous = anonymous;
    this.baseUrl = SITES[site].baseUrl();
    assertStg2(this.baseUrl);
    this.origin = new URL(this.baseUrl).origin;
    this.relogins = 0;
    this.dialogs = [];
    this.jsErrors = [];
  }

  async open() {
    if (!this.anonymous && !fs.existsSync(SITES[this.site].state)) {
      throw new Error(`先に node login.js ${this.site} を実行してください`);
    }
    this.browser = this.browser || await chromium.launch();
    this.context = await newContext(this.browser, this.anonymous ? null : this.site);
    await this.context.route('**/*', (route) => {
      const u = route.request().url();
      if (SSO_HOSTS.test(new URL(u).hostname)) { this.blocked = u; return route.abort(); }
      return route.continue();
    });
    this.page = await this.context.newPage();
    this.page.on('dialog', (d) => { this.dialogs.push(d.message()); d.dismiss().catch(() => {}); });
    this.page.on('pageerror', (e) => this.jsErrors.push(String(e.message).slice(0, 200)));
    return this;
  }

  reset() { this.dialogs = []; this.jsErrors = []; this.blocked = null; }

  abs(p) {
    const u = new URL(p, this.origin);
    assertStg2(u.toString());   // stg2 以外へは行かない
    return u.toString();
  }

  // セッション切れなら CMS は再ログインして true を返す。受講者は本番共用 SSO のため再ログインしない
  async recover(finalUrl) {
    if (this.anonymous || !SESSION_LOST.test(finalUrl)) return false;
    if (this.site === 'cms' && this.relogins < 5) {
      this.relogins++;
      console.error(`セッション切れのため CMS に再ログインします（${this.relogins}回目）`);
      await this.context.close();
      await loginCms(this.browser);
      await this.open();
      return true;
    }
    throw new Error(`セッションが切れています（${finalUrl}）。node login.js ${this.site} を再実行してください`);
  }

  // ページを開き、レスポンス本文(ソース)を返す
  async goto(url) {
    for (let attempt = 0; attempt < 2; attempt++) {
      this.reset();
      let res;
      try {
        res = await this.page.goto(this.abs(url), { waitUntil: 'domcontentloaded', timeout: 90000 });
      } catch (e) {
        // SSO へのリダイレクトを遮断した場合: 保存済みセッションなら期限切れ。未ログインなら「SSO へ遷移した」ことだけ返す
        if (this.blocked) {
          if (!this.anonymous && this.site === 'student') {
            throw new Error(`セッションが切れています（${this.blocked}）。node login.js student を再実行してください`);
          }
          return { status: 'SSO', html: '', finalUrl: this.blocked, error: 'SSO へ遷移（遮断）' };
        }
        return { status: 'ERROR', error: e.message.split('\n')[0], html: '', finalUrl: url };
      }
      const finalUrl = this.page.url();
      if (await this.recover(finalUrl)) continue;
      const html = finalUrl.startsWith('chrome-error:') ? '' : await this.text(res, finalUrl);
      if (finalUrl.startsWith('chrome-error:') || SSO_BOUNCE.test(html)) {
        if (!this.anonymous && this.site === 'student') {
          throw new Error(`セッションが切れています（SSOへ遷移）。node login.js student を再実行してください`);
        }
        return { status: 'SSO', html: '', finalUrl: url, error: 'SSO へ遷移（未ログイン。確認不可）' };
      }
      return { status: res ? res.status() : 0, html, finalUrl };
    }
    return { status: 'ERROR', error: '再ログイン後も開けません', html: '', finalUrl: url };
  }

  async text(res, url) {
    try { return res ? await res.text() : ''; } catch { return (await this.context.request.get(url)).text(); }
  }

  // 操作(クリック等)で遷移が起きたら、遷移先のソースを返す。遷移しなければ現在のDOMを返す
  async action(fn) {
    this.reset();
    const [res] = await Promise.all([
      this.page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 15000 }).catch(() => null),
      fn(),
    ]);
    await this.page.waitForTimeout(400);   // img onerror / svg onload 等の非同期の実行を待つ
    const finalUrl = this.page.url();
    if (res) return { status: res.status(), html: await this.text(res, finalUrl), finalUrl, navigated: true };
    return { status: 200, html: await this.page.content(), finalUrl, navigated: false };
  }

  async close() {
    if (!this.anonymous && this.context) await this.context.storageState({ path: SITES[this.site].state }).catch(() => {});
    if (this.browser) await this.browser.close();
  }
}

module.exports = { Session, SESSION_LOST, SSO_BOUNCE };
