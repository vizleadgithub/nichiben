# Ph.7 修正レポート — セキュリティ HTTP ヘッダー追加

**作成日:** 2026-06-25  
**対応区分:** CMS-M-01〜03 / CMS-L-01 / STU-M-01〜03 / STU-L-01  
**優先度:** 中（M-系：Medium / L-系：Low）  
**作業場所:** サーバー直接修正 — `/etc/httpd/vhost.d/vhost.conf`

---

## サマリー

| 項目 | 値 |
|------|-----|
| 対象ファイル | `/etc/httpd/vhost.d/vhost.conf`（サーバー上） |
| 対象 VirtualHost | product × 2（HTTP/HTTPS）、CMS × 2（HTTP/HTTPS）、API × 2（HTTP/HTTPS） |
| 追加ヘッダー種別 | COOP / CORP / COEP / CSP — 4 種 |
| 指摘 ID | CMS-M-01, 02, 03, L-01 / STU-M-01, 02, 03, L-01（計 8 件） |

> **注意:** vhost.conf はリポジトリ管理外（サーバー上の `/etc/httpd/vhost.d/`）。  
> ローカルソースコードの修正ではないため、ステージングサーバー SSH 接続のうえ直接編集・`httpd -t` で構文確認・`systemctl reload httpd` で反映する。

---

## 修正前後の差分（全 VirtualHost 共通）

```diff
  <IfModule mod_headers.c>
      Header set Referrer-Policy "strict-origin-when-cross-origin"
+     Header set Cross-Origin-Opener-Policy "same-origin"
+     Header set Cross-Origin-Resource-Policy "same-origin"
+     Header set Cross-Origin-Embedder-Policy "require-corp"
+     Header set Content-Security-Policy "..."
  </IfModule>
```

---

## ヘッダー別説明

### 1. COOP（Cross-Origin-Opener-Policy）— CMS-M-01 / STU-M-01

```
Header set Cross-Origin-Opener-Policy "same-origin"
```

**効果:** 別オリジンのページとブラウザ閲覧コンテキスト（window.opener）を切り離す。タブナビゲーションアタック防止。  
**破壊的影響:** なし。ポップアップウィンドウ経由の親子 window 参照が切れるが、現システムに該当フローがあれば確認が必要。

---

### 2. CORP（Cross-Origin-Resource-Policy）— CMS-M-02 / STU-M-02

```
Header set Cross-Origin-Resource-Policy "same-origin"
```

**効果:** このサーバーのリソースを同一オリジンのみが `fetch/XHR` で読み込める。Spectre 等のサイドチャネル攻撃緩和。  
**破壊的影響:** 外部サイトからこのサーバーのリソース（画像・JS 等）を直接参照している場合はブロックされる。  
**確認事項:** `/upload/video_thumbnail/`（NFS alias）は同一ホストからのアクセスのため問題なし。

---

### 3. COEP（Cross-Origin-Embedder-Policy）— CMS-M-03 / STU-M-03

```
Header set Cross-Origin-Embedder-Policy "require-corp"
```

**効果:** ページが読み込むすべてのサブリソース（画像・JS・iframe 等）に CORP ヘッダーが必要となる。SharedArrayBuffer 有効化の前提条件でもある。  

**⚠️ 破壊的リスクが高い — 事前確認必須**

| 確認項目 | 影響可能性 |
|---------|-----------|
| GMO 決済ページの外部 iframe | **高** — GMO 側に CORP ヘッダーがなければブロック |
| 外部 CDN（jQuery 等）を直接読み込む場合 | **高** — CDN からのレスポンスに CORP ヘッダーがなければブロック |
| `/alflearning-data/` の動画サムネイル（Apache Alias） | **低** — 同一オリジン配信のため問題なし |
| 動画プレーヤーが外部ストリームを使う場合 | **要確認** |

**推奨:** まず CMS VirtualHost のみに適用して動作確認後、product VirtualHost に展開する。  
product VirtualHost で外部リソースが確認された場合は `unsafe-none` → `require-corp` の段階適用を検討。

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
        Header set Cross-Origin-Embedder-Policy "require-corp"       ← 追加（要確認後）
        Header set Content-Security-Policy "..."                     ← 追加（Step 1はReport-Onlyで）
    </IfModule>
</VirtualHost>
```

同様に HTTP (port 80) の各 VirtualHost にも追加する。

---

#### 修正対象ブロック一覧

| VirtualHost | Port | ServerName | 追加ヘッダー数 |
|-------------|------|-----------|-------------|
| product | 443 | `nichibenren-stg2.alfcloud.com` | 4 |
| product | 80 | `nichibenren-stg2.alfcloud.com` | 4 |
| cms | 443 | `cms.nichibenren-stg2.alfcloud.com` | 4 |
| cms | 80 | `cms.nichibenren-stg2.alfcloud.com` | 4 |
| api | 443 | `api.nichibenren-stg2.alfcloud.com` | 3（COEPは不要） |
| api | 80 | `api.nichibenren-stg2.alfcloud.com` | 3（COEPは不要） |

> **API VirtualHost について:** API は JSON を返す REST エンドポイントのため COEP は不要。COOP・CORP・CSP のみ追加する。  
> CSP は `default-src 'none'; frame-ancestors 'none'` のみで十分（API レスポンスはブラウザが直接レンダリングしない）。

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
| CMS-M-01 | Medium | COOP | cms | `same-origin` |
| CMS-M-02 | Medium | CORP | cms | `same-origin` |
| CMS-M-03 | Medium | COEP | cms | `require-corp`（動作確認要） |
| CMS-L-01 | Low | CSP | cms | Report-Only → 段階適用 |
| STU-M-01 | Medium | COOP | product | `same-origin` |
| STU-M-02 | Medium | CORP | product | `same-origin` |
| STU-M-03 | Medium | COEP | product | `require-corp`（外部リソース確認要） |
| STU-L-01 | Low | CSP | product | Report-Only から開始 |

---

## 次フェーズ（Ph.8）

| 指摘 ID | 内容 | 備考 |
|---------|------|------|
| CMS-M-04 | CSRF（商品一覧） | CI3 CSRF トークン実装 + Smarty フォーム修正 |
| CMS-M-05 | CSRF（商品登録） | 同上 |
| STU-M-04 | CSRF（検索） | alfproduct 独自 CSRF 実装 + Smarty フォーム修正 |
| STU-M-05 | CSRF（問い合わせ確認） | 同上 |
