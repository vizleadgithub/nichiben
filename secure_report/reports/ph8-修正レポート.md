# Ph.8 修正レポート — CSRF トークン実装

**作成日:** 2026-06-25  
**対応区分:** CMS-M-04 / CMS-M-05 / STU-M-04 / STU-M-05  
**優先度:** 高（Medium）  
**作業場所:** ローカルソースコード修正

---

## サマリー

| 項目 | 値 |
|------|-----|
| 修正ファイル数 | 15 |
| 追加関数数 | 2（`csrf_token_get()` / `csrf_token_verify()`） |
| 対象指摘 | CMS-M-04, CMS-M-05, STU-M-04, STU-M-05 |

CI3 の `csrf_protection` はフラット PHP ファイル（`alfproduct/` 系・`alflearning-cms/alfproduct/` 系）には適用されないため、独自の CSRF トークンヘルパーを実装した。

---

## 実装方針

### トークンの仕様

| 項目 | 内容 |
|------|------|
| 生成 | `bin2hex(random_bytes(32))` — 256 bit の暗号論的乱数 |
| 保存 | `$_SESSION['csrf_token']` |
| 検証 | `hash_equals()` によるタイミングセーフ比較 |
| 再発行 | 検証成功時に毎回再発行（トークンのリプレイ防止） |

### ヘルパー関数（`alfproduct/module/functions.php` に追加）

```php
function csrf_token_get(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_token_verify(): void {
    $stored = $_SESSION['csrf_token'] ?? '';
    $posted = $_POST['csrf_token'] ?? '';
    if (empty($stored) || !hash_equals($stored, $posted)) {
        http_response_code(403);
        exit;
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

`functions.php` は `module/module.php` 経由で全対象ページに自動 include されるため、追加設定不要。

---

## CMS-M-04 — 商品一覧・検索フォーム CSRF

**対象ページ:** CMS 商品一覧 (`/alfproduct/product/index.php`)

### 修正ファイル

#### 1. `alflearning/alflearning-cms/alfproduct/product/index.php`

```diff
 if( $_SERVER["REQUEST_METHOD"] == "POST" ){
+    csrf_token_verify();
     $search_product_name = isset($_POST["search_product_name"]) ...
```

```diff
 $template->assign('page_name', 'product');
+$template->assign('csrf_token', csrf_token_get());
 $template->admin_layout('product/index.tpl');
```

#### 2. `alfproduct/smarty/templates/admin/product/index.tpl`

```diff
 <form action="#" accept-charset="utf-8" method="post" name="search_form">
+    <input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
     <table class="form">
```

---

## CMS-M-05 — 商品登録・編集 CSRF

**対象ページ:** CMS 商品詳細 (`/alfproduct/product/info.php`) → 商品登録 (`add.php`)

### フォームフロー

```
info.php (GET) → info.tpl [token T]
  → add.php (act=edit) [verify T, assign T2] → add.tpl [token T2]
    → add.php (act=confirm) [assign T3] → add_confirm.tpl [token T3]
      → add.php (act=complete) [verify T3] → 完了
```

### 修正ファイル

#### 1. `alflearning/alflearning-cms/alfproduct/product/info.php`

```diff
 $template->assign('arr_term_id', $arr_term_id);
 $template->assign('term_name', $term_name);
+$template->assign('csrf_token', csrf_token_get());
 $template->admin_layout('product/info.tpl');
```

#### 2. `alfproduct/smarty/templates/admin/product/info.tpl`

```diff
 <form name="form1" action="#" method="post">
+    <input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
     <input type="hidden" name="mid" id="mid" value="<!--{$mid}-->" />
```

#### 3. `alflearning/alflearning-cms/alfproduct/product/add.php`

**case 'complete' — 検証追加:**
```diff
 case 'complete':
+    csrf_token_verify();
     $err_flag = 0;
```

**case 'confirm' 成功 — トークン引き渡し:**
```diff
 if(empty($err_msg)){
+    $template->assign('csrf_token', csrf_token_get());
     $template->admin_layout('product/add_confirm.tpl');
```

**case 'confirm' 失敗 / 'edit' / 'back' / 'upload' — トークン引き渡し:**
```diff
     $template->assign('productcategory_list', get_product_category());
+    $template->assign('csrf_token', csrf_token_get());
     $template->admin_layout('product/add.tpl');
```

#### 4. `alfproduct/smarty/templates/admin/product/add.tpl`

```diff
 <form name="form1" action="add.php" method="post" enctype="multipart/form-data">
 <input type="hidden" name="act" id="act" value="confirm" />
+<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
```

#### 5. `alfproduct/smarty/templates/admin/product/add_confirm.tpl`

```diff
 <input type="hidden" name="act" id="act" value="" />
+<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
```

---

## STU-M-04 — 受講者向け講座検索 CSRF

**対象ページ:** 受講者サイト 講座検索 (`/search/index.php`)

### 修正ファイル

#### 1. `alfproduct/public/search/index.php`

```diff
 if( $_SERVER["REQUEST_METHOD"] == "POST" ){
+    csrf_token_verify();
     $disp_flg = true;
```

```diff
 $template->assign('disp_flg', $disp_flg);
+$template->assign('csrf_token', csrf_token_get());
 $template->layout('search/index.tpl');
```

#### 2. `alfproduct/smarty/templates/default/search/index.tpl`

```diff
 <form name="search_form" method="post" action="index.php?search=new#main">
+    <input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
```

---

## STU-M-05 — お問い合わせ確認・送信 CSRF

**対象ページ:** お問い合わせ確認 (`/inquiry/conf.php`) → 送信 (`send.php`)

### フォームフロー

```
conf.php [assign token] → conf.tpl [token T in form_inquiry_submit]
  → send.php [verify T] → 送信完了
```

### 修正ファイル

#### 1. `alfproduct/public/inquiry/conf.php`

```diff
 } else {
+    $template->assign('csrf_token', csrf_token_get());
     $template->layout('inquiry/conf.tpl');
 }
```

#### 2. `alfproduct/smarty/templates/default/inquiry/conf.tpl`

form_inquiry_back（戻るボタン）:
```diff
 <form name="form_inquiry_back" method="post" action="index.php">
+    <input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
```

form_inquiry_submit（送信ボタン）:
```diff
 <form name="form_inquiry_submit" method="post" action="send.php">
+    <input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
```

#### 3. `alfproduct/public/inquiry/send.php`

```diff
 if( $_SERVER["REQUEST_METHOD"] == "POST" ){
+    csrf_token_verify();
     $input_lawyer_number = trim($_POST["input_lawyer_number"]);
```

---

## 修正ファイル一覧

| ファイル | 修正種別 |
|---------|---------|
| `alfproduct/module/functions.php` | ヘルパー関数 2 件追加 |
| `alflearning/alflearning-cms/alfproduct/product/index.php` | verify + assign |
| `alfproduct/smarty/templates/admin/product/index.tpl` | hidden フィールド追加 |
| `alflearning/alflearning-cms/alfproduct/product/info.php` | assign |
| `alfproduct/smarty/templates/admin/product/info.tpl` | hidden フィールド追加 |
| `alflearning/alflearning-cms/alfproduct/product/add.php` | verify (complete) + assign (confirm/edit/back/upload) |
| `alfproduct/smarty/templates/admin/product/add.tpl` | hidden フィールド追加 |
| `alfproduct/smarty/templates/admin/product/add_confirm.tpl` | hidden フィールド追加 |
| `alfproduct/public/search/index.php` | verify + assign |
| `alfproduct/smarty/templates/default/search/index.tpl` | hidden フィールド追加 |
| `alfproduct/public/inquiry/conf.php` | assign |
| `alfproduct/smarty/templates/default/inquiry/conf.tpl` | hidden フィールド追加（2 フォーム） |
| `alfproduct/public/inquiry/send.php` | verify |
| `alfproduct/smarty/templates/smartphone/search/index.tpl` | hidden フィールド追加（SP 版） |
| `alfproduct/smarty/templates/smartphone/inquiry/conf.tpl` | hidden フィールド追加（SP 版・2 フォーム） |

---

## 動作確認方法

### CMS-M-04 確認

1. CMS にログインし `/alfproduct/product/index.php` を開く
2. 商品名で検索 → 正常に結果が返ること
3. 別タブで CSRF フォーム（csrf_token を空にした POST）を送信 → 403 が返ること

### CMS-M-05 確認

1. CMS で商品詳細 (`info.php?mid=xxx`) を開く
2. 「修正」ボタン → add.tpl が開くこと
3. 内容確認 → add_confirm.tpl → 「完了」ボタン → 正常に更新されること
4. csrf_token を改ざんした POST を add.php に直接送信 → 403 が返ること

### STU-M-04 確認

1. 受講者サイトで講座検索を実行 → 正常に結果が返ること
2. csrf_token なし POST → 403 が返ること

### STU-M-05 確認

1. お問い合わせフォーム入力 → 確認ページ表示
2. 「送信する」ボタン → 送信完了ページへ遷移すること
3. csrf_token なし POST を send.php に送信 → 403 が返ること

---

## 注意事項

- `csrf_token_verify()` は検証成功後にトークンを再発行するため、ブラウザ戻るボタンで同じフォームを再送信すると 403 になる。これは意図した動作。
- `AlfSession::session_check()` は CI3 セッションデータを `$_SESSION` にマージするが、`$_SESSION['csrf_token']` は `prev_session` に含まれるため正しく引き継がれる。
- Smarty の `|escape` modifier は `htmlspecialchars()` を通すため、トークン値（hex文字列）は安全に出力される。

---

## 指摘 ID 対応表

| 指摘 ID | 区分 | 内容 | 対応 |
|---------|------|------|------|
| CMS-M-04 | Medium | CSRF（商品一覧・検索） | `csrf_token_verify()` + hidden フィールド |
| CMS-M-05 | Medium | CSRF（商品登録・編集） | `csrf_token_verify()` (complete) + トークン引き渡し |
| STU-M-04 | Medium | CSRF（講座検索） | `csrf_token_verify()` + hidden フィールド |
| STU-M-05 | Medium | CSRF（問い合わせ送信） | `csrf_token_verify()` + hidden フィールド |
