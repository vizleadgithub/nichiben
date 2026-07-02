# 補足資料 — cp-content 旧 WordPress ソース セキュリティ修正

**位置づけ**: Ph.1〜Ph.22 の修正対象から漏れていた `cp-content/` ディレクトリへの追加修正  
**修正日**: 2026-06-30  
**修正ファイル数**: 8 ファイル  
**対象ディレクトリ**: `alfproduct/public/custom_pages/cp-content/themes/`

---

## 経緯

`alfproduct/public/custom_pages/cp-content/` は旧 WordPress ソースを流用した PHP ファイル群で、受講者 TOP ページ等のテンプレートとして参照されている。  
Ph.1〜Ph.22 では `alfproduct/public/` 配下の各機能モジュール（product / mypage / search 等）を対象としており、このディレクトリは修正スコープに含まれていなかった。  
Ph.22 完了後の網羅確認調査において同種の脆弱性が残存していることが判明したため、追加修正を実施した。

---

## 適用済み修正の対応関係

以下の表は、Ph.1〜Ph.22 で実施した修正カテゴリと、今回の cp-content への適用状況を示す。

| Ph. | 修正カテゴリ | cp-content への適用 |
|---|---|---|
| Ph.3 | target="_blank" rel="noopener noreferrer" | 前フェーズで適用済み（footer.php / header.php / loop-single.php 等） |
| Ph.9 | デバッグ出力削除 | 確認済み（print/var_dump はすべてコメントアウト済み） |
| Ph.10 | href 属性 XSS（URL エスケープ） | 前フェーズで一部適用済み・今回追加対応（category.php） |
| Ph.13/14 | DB 取得値の htmlspecialchars | 前フェーズで一部適用済み・今回追加対応 |
| Ph.2 | $_REQUEST → $_GET（メソッド限定） | 今回対応（loop-single.php） |
| Ph.14 | セッション値の htmlspecialchars | 今回対応（header.php / sidebar-2.php） |
| — | $_SERVER['SERVER_NAME'] エスケープ | 今回対応（sidebar-2.php ログインフォーム） |

---

## 修正内容詳細

### 1. $_SESSION 値のエスケープ欠落（格納型 XSS）

ユーザー名（`$_SESSION['user']['name']`）および倫理研修義務年（`$_SESSION['user']['bar_association_duty_year']`）を、htmlspecialchars() によるエスケープなしで HTML 出力していた。

```php
// 修正前（脆弱）
echo $_SESSION['user']['name'].'様';
echo $_SESSION['user']['bar_association_duty_year'];

// 修正後（安全）
echo htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8').'様';
echo htmlspecialchars((string)$_SESSION['user']['bar_association_duty_year'], ENT_QUOTES, 'UTF-8');
```

**修正ファイル**

| ファイル | 修正対象 |
|---|---|
| `themes/sp/sidebar-2.php` | `name`、`bar_association_duty_year` |
| `themes/twentyten/sidebar-2.php` | `bar_association_duty_year`（`name` は前フェーズ適用済み） |
| `themes/sp/header.php` | `name`、`bar_association_duty_year` |
| `themes/twentyten/header.php` | `bar_association_duty_year`（`name` は前フェーズ適用済み） |

---

### 2. $_SERVER['SERVER_NAME'] のエスケープ欠落（Host Header Injection / XSS）

ログインフォームの `action` 属性に `$_SERVER['SERVER_NAME']` をエスケープなしで埋め込んでいた。  
ロードバランサー構成等では Host ヘッダの値が `SERVER_NAME` に反映されるケースがあり、フォームの送信先を差し替えられる可能性があった。

```php
// 修正前（脆弱）
<form action="https://<?php echo $_SERVER['SERVER_NAME']; ?>/login/login.php" method="post">

// 修正後（安全）
<form action="https://<?php echo htmlspecialchars($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8'); ?>/login/login.php" method="post">
```

**修正ファイル**: `themes/sp/sidebar-2.php`、`themes/twentyten/sidebar-2.php`

---

### 3. DB取得値（post_title）のエスケープ欠落（格納型 XSS）

ニュース一覧ページで WordPress 投稿タイトル（`$post["post_title"]`）をエスケープなしで出力していた。

```php
// 修正前（脆弱）
print($post["post_title"]);

// 修正後（安全）
print(htmlspecialchars($post["post_title"], ENT_QUOTES, 'UTF-8'));
```

**修正ファイル**: `themes/sp/category.php`（3 箇所）、`themes/twentyten/category.php`（3 箇所）

---

### 4. DB取得値（url）のエスケープ欠落 + rel 属性欠落

ニュース詳細リンク（`$post["url"]`）を href 属性にエスケープなしで出力し、`target="_blank"` に rel 属性も欠落していた。

```php
// 修正前（脆弱）
<a href="<?php print($post["url"]); ?>" rel="bookmark" target="_blank">

// 修正後（安全）
<a href="<?php print(htmlspecialchars($post["url"], ENT_QUOTES, 'UTF-8')); ?>" rel="noopener noreferrer" target="_blank">
```

**修正ファイル**: `themes/sp/category.php`、`themes/twentyten/category.php`

---

### 5. $_REQUEST によるメソッド混在 + 整数型未検証（loop-single.php）

投稿 ID を `$_REQUEST["p"]` で取得しており、GET/POST どちらも受け入れていた。また `(int)` キャストなしで WordPress 関数に渡していた。

```php
// 修正前（脆弱）
if (isset($_REQUEST["p"])){
    $id = $_REQUEST["p"];
}

// 修正後（安全）
if (isset($_GET["p"])){
    $id = (int)$_GET["p"];
}
```

**修正ファイル**: `themes/sp/loop-single.php`、`themes/twentyten/loop-single.php`

---

### 6. DB取得値（product_id）の型保証欠落（URL 出力）

商品詳細へのリンク URL に `$post->product_id` を `(int)` キャストなしで埋め込んでいた。

```php
// 修正前
<?php echo $post->product_id; ?>

// 修正後
<?php echo (int)$post->product_id; ?>
```

**修正ファイル**: `themes/sp/loop-single.php`（全件）、`themes/twentyten/loop-single.php`（全件）

---

## 修正ファイル一覧

| ファイル | 対応項目 |
|---|---|
| `themes/sp/sidebar-2.php` | 1（$_SESSION）、2（$_SERVER） |
| `themes/twentyten/sidebar-2.php` | 1（$_SESSION）、2（$_SERVER） |
| `themes/sp/header.php` | 1（$_SESSION） |
| `themes/twentyten/header.php` | 1（$_SESSION） |
| `themes/sp/category.php` | 3（post_title）、4（url / rel） |
| `themes/twentyten/category.php` | 3（post_title）、4（url / rel） |
| `themes/sp/loop-single.php` | 5（$_REQUEST）、6（product_id） |
| `themes/twentyten/loop-single.php` | 5（$_REQUEST）、6（product_id） |

---

