# Ph.7 修正レポート — セキュリティ HTTP ヘッダー追加

**作成日:** 2026-06-25  
**更新日:** 2026-06-30（COEP 削除対応を追記）  
**対応区分:** CMS-M-01〜03 / CMS-L-01 / STU-M-01〜03 / STU-L-01  
**優先度:** 中（M-系：Medium / L-系：Low）  
**作業場所:** サーバー直接修正 — `/etc/httpd/vhost.d/vhost.conf`

---

## サマリー

| 項目 | 値 |
|------|-----|
| 対象ファイル | `/etc/httpd/vhost.d/vhost.conf`（サーバー上） |
| 対象 VirtualHost | product × 2（HTTP/HTTPS）、CMS × 2（HTTP/HTTPS）、API × 2（HTTP/HTTPS） |
| 追加ヘッダー種別 | COOP / CORP / CSP — 3 種（COEP は後述の理由により削除） |
| 指摘 ID | CMS-M-01, 02, 03, L-01 / STU-M-01, 02, 03, L-01（計 8 件） |

> **注意:** vhost.conf はリポジトリ管理外（サーバー上の `/etc/httpd/vhost.d/`）。  
> ローカルソースコードの修正ではないため、ステージングサーバー SSH 接続のうえ直接編集・`httpd -t` で構文確認・`systemctl reload httpd` で反映する。

---

## COEP 削除の経緯と判断根拠

### 問題

`Cross-Origin-Embedder-Policy: require-corp` を適用したところ、受講者サイトの動画プレーヤーが外部動画配信サーバーからメディアファイルを取得できなくなった。

**原因:** COEP `require-corp` は「このページが読み込む全クロスオリジンリソースに `Cross-Origin-Resource-Policy` ヘッダーを要求する」ポリシーである。外部動画配信サーバーが CORP ヘッダーを返していないため、ブラウザがメディアリクエストをブロックした。

### 解決方針の比較検討

| 案 | 内容 | 採否 |
|---|---|---|
| Alfstreamに `CORP: cross-origin` を追加 | Alfstreamの設定変更が必要。動画へのアクセスにセッション認証が必要な場合は別途考慮が必要 | 動画サーバー設定変更可能であれば検討 |
| COEP を `credentialless` に変更 | クロスオリジンリソースをクッキーなし（匿名）で読み込む。動画に認証が不要な公開ファイルなら有効 | 認証付き動画の場合は不可 |
| COEP を削除し COOP + CORP を維持 | COOP・CORP は COEP と独立して機能し、有効な保護を提供する。COEP の主目的（`SharedArrayBuffer` 有効化）はこのアプリでは不要 | **採用** |

### 判断

COEP の主目的は `SharedArrayBuffer` や `performance.measureUserAgentSpecificMemory()` を有効にするための前提条件であり、Spectre 系サイドチャネル攻撃の緩和に寄与する。しかし本システムはこれらの API を使用していないため、COEP を削除しても機能上の影響はない。

COOP と CORP はそれぞれ独立して有効であり、COEP なしでも以下の保護は維持される：

- **COOP `same-origin`**: 他オリジンのポップアップ・タブからの `window.opener` 参照を遮断
- **CORP `same-origin`**: 自サーバーのリソースが他オリジンにサブリソースとして読み込まれることを防止

**結論: COEP を削除し、COOP + CORP のみを適用する。**

---

## 修正前後の差分（全 VirtualHost 共通）

### 当初適用（Ph.7 初版）

```diff
  <IfModule mod_headers.c>
      Header set Referrer-Policy "strict-origin-when-cross-origin"
+     Header set Cross-Origin-Opener-Policy "same-origin"
+     Header set Cross-Origin-Resource-Policy "same-origin"
+     Header set Cross-Origin-Embedder-Policy "require-corp"
+     Header set Content-Security-Policy "..."
  </IfModule>
```

### 現在の確定版（COEP 削除後）

```diff
  <IfModule mod_headers.c>
      Header set Referrer-Policy "strict-origin-when-cross-origin"
      Header set Cross-Origin-Opener-Policy "same-origin"
      Header set Cross-Origin-Resource-Policy "same-origin"
-     Header set Cross-Origin-Embedder-Policy "require-corp"   ← 削除（外部動画サーバーとの互換性のため）
      Header set Content-Security-Policy "..."
  </IfModule>
```

---

## ヘッダー別説明

### 1. COOP（Cross-Origin-Opener-Policy）— CMS-M-01 / STU-M-01　✅ 適用

```
Header set Cross-Origin-Opener-Policy "same-origin"
```

**効果:** 別オリジンのページとブラウザ閲覧コンテキスト（window.opener）を切り離す。タブナビゲーションアタック防止。  
**破壊的影響:** なし。ポップアップウィンドウ経由の親子 window 参照が切れるが、現システムに該当フローがあれば確認が必要。

---

### 2. CORP（Cross-Origin-Resource-Policy）— CMS-M-02 / STU-M-02　✅ 適用

```
Header set Cross-Origin-Resource-Policy "same-origin"
```

**効果:** このサーバーのリソースを同一オリジンのみが `fetch/XHR` で読み込める。Spectre 等のサイドチャネル攻撃緩和。  
**破壊的影響:** 外部サイトからこのサーバーのリソース（画像・JS 等）を直接参照している場合はブロックされる。  
**確認事項:** `/upload/video_thumbnail/`（NFS alias）は同一ホストからのアクセスのため問題なし。

---

### 3. COEP（Cross-Origin-Embedder-Policy）— CMS-M-03 / STU-M-03　❌ 削除

```
# Header set Cross-Origin-Embedder-Policy "require-corp"  ← Alfstreamとの互換性のため削除
```

**削除理由:** 上記「COEP 削除の経緯と判断根拠」参照。  
**代替策（将来対応）:** Alfstreamに `Cross-Origin-Resource-Policy: cross-origin` を追加できれば、COEP の再適用が可能。

---

### 4. CSP（Content-Security-Policy）— CMS-L-01 / STU-L-01

CSP は "どのオリジンのリソースを何の目的で読み込んでいいか" をブラウザに伝えるポリシー。  
アプリが実際に使用しているリソースをすべて把握してから設定しないと、ページが壊れる。

#### 推奨アプローチ: Report-Only → 段階的強化

**Step 1 — まず `Report-Only` で違反を収集する**

```apache
Header set Content-Security-Policy-Report-Only "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-src 'self'; object-src 'none'; base-uri 'self'; report-uri /csp-report"
```

- `Content-Security-Policy-Report-Only` はポリシー違反をレポートするが、**ブロックはしない**
- 違反は `report-uri` で受け取るか、ブラウザの DevTools Console で確認できる
- 数日〜数週間の動作ログから `script-src` などに追加すべきオリジンを特定する

**Step 2 — 違反確認後、`Content-Security-Policy` に切り替える**

違反ログをもとに、外部オリジンを明示的に許可リストに追加してから本適用する。

#### CMS VirtualHost の初期ポリシー案

CMS は管理者専用・限定ユーザーのため、まず以下で試す：

```apache
Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: blob:; font-src 'self'; connect-src 'self'; frame-ancestors 'none'; object-src 'none'; base-uri 'self'"
```

| ディレクティブ | 値 | 理由 |
|---|---|---|
| `default-src` | `'self'` | 未指定カテゴリはすべて自分自身のみ |
| `script-src` | `'self' 'unsafe-inline' 'unsafe-eval'` | CodeIgniter のインライン JS が存在するため一時許可（将来的に削除を目指す） |
| `style-src` | `'self' 'unsafe-inline'` | インライン style が存在するため |
| `img-src` | `'self' data: blob:` | data URI のアイコン等に対応 |
| `frame-ancestors` | `'none'` | CMS 画面を iframe 埋め込みさせない（クリックジャッキング防止） |
| `object-src` | `'none'` | Flash/ActiveX 完全禁止 |

#### product VirtualHost の初期ポリシー案

外部リソース（GMO 決済、動画ストリーム等）の把握が先決。**Report-Only から開始する**こと。

```apache
Header set Content-Security-Policy-Report-Only "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: blob: https:; font-src 'self' data:; connect-src 'self'; frame-src 'self'; object-src 'none'; base-uri 'self'"
```

`img-src` に `https:` を入れているのは、外部画像の存在が不明なため暫定許可。違反ログで特定後に絞る。

---

## 修正手順

### 適用ファイル: `/etc/httpd/vhost.d/vhost.conf`

#### 対象 VirtualHost ブロックと追加位置

各 VirtualHost の `<IfModule mod_headers.c>` ブロック末尾に追加する。

```
#------------------------------------------------------------------------#
# cms (HTTPS)
#------------------------------------------------------------------------#
<VirtualHost *:443>
    ...
    <IfModule mod_headers.c>
        Header set Referrer-Policy "strict-origin-when-cross-origin"
        Header set Cross-Origin-Opener-Policy "same-origin"          ← 追加
        Header set Cross-Origin-Resource-Policy "same-origin"        ← 追加
        # Header set Cross-Origin-Embedder-Policy "require-corp"     ← 外部動画サーバーとの互換性のため削除
        Header set Content-Security-Policy "..."                     ← 追加（Step 1はReport-Onlyで）
    </IfModule>
</VirtualHost>
```

同様に HTTP (port 80) の各 VirtualHost にも追加する。

---

#### 修正対象ブロック一覧

| VirtualHost | Port | ServerName | 追加ヘッダー |
|-------------|------|-----------|------------|
| product | 443 | `nichibenren-stg2.alfcloud.com` | COOP / CORP / CSP |
| product | 80 | `nichibenren-stg2.alfcloud.com` | COOP / CORP / CSP |
| cms | 443 | `cms.nichibenren-stg2.alfcloud.com` | COOP / CORP / CSP |
| cms | 80 | `cms.nichibenren-stg2.alfcloud.com` | COOP / CORP / CSP |
| api | 443 | `api.nichibenren-stg2.alfcloud.com` | COOP / CORP / CSP |
| api | 80 | `api.nichibenren-stg2.alfcloud.com` | COOP / CORP / CSP |

> **API VirtualHost について:** API は JSON を返す REST エンドポイントのため COEP は不要。CSP は `default-src 'none'; frame-ancestors 'none'` のみで十分（API レスポンスはブラウザが直接レンダリングしない）。

---

## 反映・確認コマンド

```bash
# 構文チェック
httpd -t

# 設定反映
systemctl reload httpd

# ヘッダー確認（CMS）
curl -sI https://cms.nichibenren-stg2.alfcloud.com/ | grep -i "cross-origin\|content-security"

# ヘッダー確認（product）
curl -sI https://nichibenren-stg2.alfcloud.com/ | grep -i "cross-origin\|content-security"
```

---

## 指摘 ID 対応表

| 指摘 ID | 区分 | ヘッダー | VirtualHost | 対応 |
|---------|------|---------|------------|------|
| CMS-M-01 | Medium | COOP | cms | ✅ `same-origin` 適用済み |
| CMS-M-02 | Medium | CORP | cms | ✅ `same-origin` 適用済み |
| CMS-M-03 | Medium | COEP | cms | ❌ 削除（Alfstreamとの互換性問題のため。COOP+CORP で代替保護） |
| CMS-L-01 | Low | CSP | cms | Report-Only → 段階適用 |
| STU-M-01 | Medium | COOP | product | ✅ `same-origin` 適用済み |
| STU-M-02 | Medium | CORP | product | ✅ `same-origin` 適用済み |
| STU-M-03 | Medium | COEP | product | ❌ 削除（Alfstreamとの互換性問題のため。COOP+CORP で代替保護） |
| STU-L-01 | Low | CSP | product | Report-Only から開始 |

---

## 次フェーズ（Ph.8）

| 指摘 ID | 内容 | 備考 |
|---------|------|------|
| CMS-M-04 | CSRF（商品一覧） | CI3 CSRF トークン実装 + Smarty フォーム修正 |
| CMS-M-05 | CSRF（商品登録） | 同上 |
| STU-M-04 | CSRF（検索） | alfproduct 独自 CSRF 実装 + Smarty フォーム修正 |
| STU-M-05 | CSRF（問い合わせ確認） | 同上 |
