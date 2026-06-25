# Ph.6 修正レポート — SameSite=Strict 変更

**実施日:** 2026-06-25  
**対応区分:** セッションCookieの SameSite 属性強化

---

## サマリー

| 項目 | 値 |
|------|-----|
| 修正ファイル数 | 2 |
| 修正箇所数 | 4 |
| 変更内容 | `SameSite=Lax` → `SameSite=Strict` |

---

## 修正内容

### 変更前 → 変更後

```diff
- $config['sess_samesite'] = 'Lax';
+ $config['sess_samesite'] = 'Strict';

- $config['cookie_samesite'] = 'Lax';
+ $config['cookie_samesite'] = 'Strict';
```

---

## 修正ファイル

### 1. `alflearning/alflearning-cms/application/config/config.php`

| 行 | 項目 | 変更前 | 変更後 |
|----|------|--------|--------|
| 395 | `sess_samesite` | `'Lax'` | `'Strict'` |
| 425 | `cookie_samesite` | `'Lax'` | `'Strict'` |

### 2. `alflearning/alflearning-api/application/config/config.php`

| 行 | 項目 | 変更前 | 変更後 |
|----|------|--------|--------|
| 394 | `sess_samesite` | `'Lax'` | `'Strict'` |
| 424 | `cookie_samesite` | `'Lax'` | `'Strict'` |

---

## 効果・影響

### 効果
`SameSite=Strict` にすることで、**クロスサイトリクエスト時にCookieが一切送信されなくなる**。  
CSRF 攻撃の根本的な緩和策となる（CSRF トークン実装の補完）。

### 影響範囲
- **CMS セッション（ci_session）:** 外部サイトからのリンク経由でのアクセス時に再ログインが必要になる
- **API セッション:** alfproduct（同一eTLD+1: alfcloud.com）からのリクエストは同一サイト扱いのため影響なし
- 本番ドメイン（nichibenren.or.jp 等）が異なる場合は別途検討が必要

> **注:** `alfcloud.com` 配下のサブドメイン間（`nichibenren-stg2.alfcloud.com` ↔  
> `cms.nichibenren-stg2.alfcloud.com` ↔ `api.nichibenren-stg2.alfcloud.com`）は  
> 同一サイト（eTLD+1 = `alfcloud.com`）として扱われるため、SameSite=Strict でも Cookie は送信される。

---

## 補足: AlfSession.php との関係

`alfproduct/module/AlfSession.php` はセッションCookieの **読み取り** のみ行い、  
Cookie の **発行** は CI3 の Session ライブラリ（CMS 側）が担う。  
したがって SameSite 属性の変更は `config.php` への修正で有効になる。

---

## 次フェーズ（サーバー側・別途対応）

| 対応 | 内容 | 対応方法 |
|------|------|---------|
| COOP/CORP/COEP ヘッダー | Cross-Origin 分離ヘッダー | vhost.conf に `Header set` 追加 |
| CSP ヘッダー | Content-Security-Policy | vhost.conf または PHP で設定 |
| CSRF トークン | CMS-M-04, M-05, STU-M-04, M-05 | 設計フェーズ（別途） |
