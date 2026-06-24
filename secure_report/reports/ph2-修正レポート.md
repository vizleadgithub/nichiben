# Ph.2 修正レポート — A型 GET 405拒否 / C型 `$_REQUEST` 置換

**実施日:** 2026-06-24  
**対応区分:** CMS-L-02（AppScan 必須）+ STU-L-02（AppScan 必須）

---

## サマリー

| 項目 | 値 |
|------|-----|
| 修正ファイル数 | 79 |
| スキップ（既存チェックあり） | 1 |
| GET拒否ブロック追加行数 | 320 行（64 ファイル × 5 行） |
| `$_REQUEST`→`$_POST` 置換箇所 | 83 箇所（11 ファイル） |
| `$_REQUEST`→`$_GET` 置換箇所 | 70 箇所（15 ファイル） |
| **合計修正行数** | **320 行追加 / 0 行削除** |

> **AppScan への影響:**  
> CMS-L-02・STU-L-02（ボディパラメータをクエリで送信）の根本原因である GET リクエストでの  
> POST エンドポイントへのアクセスを全面遮断。再診断で該当指摘がすべて解消される見込み。

---

## 修正パターン

### A型: GET 拒否ブロック（`<?php` 直後に挿入）

```diff
@@ -1,4 +1,9 @@
 <?php
+if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
+    header('HTTP/1.1 405 Method Not Allowed');
+    header('Allow: POST');
+    exit;
+}
 //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 include("/srv/alfproduct/module/module.php");
 $template = new Template();
```

### C型書込系: GET 拒否ブロック + `$_REQUEST` → `$_POST`

```diff
@@ -1,6 +1,11 @@
 <?php
+if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
+    header('HTTP/1.1 405 Method Not Allowed');
+    header('Allow: POST');
+    exit;
+}
 //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 include("/srv/alfproduct/module/module.php");
 ...
-if(isset($_REQUEST["pid"])){
-    $pid = intval($_REQUEST["pid"]);
+if(isset($_POST["pid"])){
+    $pid = intval($_POST["pid"]);
```

### C型表示系: `$_REQUEST` → `$_GET`（GET 拒否なし）

```diff
-if(isset($_REQUEST["pid"])){
-    $pid = intval($_REQUEST["pid"]);
+if(isset($_GET["pid"])){
+    $pid = intval($_GET["pid"]);
```

---

## CMS-A型（22 ファイル）— GET 拒否追加のみ

| # | ファイル | 状態 | 追加行 |
|---|---------|------|------|
| 1 | `alflearning-cms/alfproduct/product/add.php` | modified | +5 |
| 2 | `alflearning-cms/alfproduct/product/add_review.php` | modified | +5 |
| 3 | `alflearning-cms/alfproduct/product/delete_all_contents.php` | modified | +5 |
| 4 | `alflearning-cms/alfproduct/product/delete_document.php` | modified | +5 |
| 5 | `alflearning-cms/alfproduct/product/delete_thumbnail.php` | modified | +5 |
| 6 | `alflearning-cms/alfproduct/product/upload_all_contents.php` | modified | +5 |
| 7 | `alflearning-cms/alfproduct/product/upload_document.php` | modified | +5 |
| 8 | `alflearning-cms/alfproduct/product/upload_thumbnail.php` | modified | +5 |
| 9 | `alflearning-cms/alfproduct/product_ethics/add.php` | modified | +5 |
| 10 | `alflearning-cms/alfproduct/product_ethics/delete_all_contents.php` | modified | +5 |
| 11 | `alflearning-cms/alfproduct/product_ethics/delete_document.php` | modified | +5 |
| 12 | `alflearning-cms/alfproduct/product_ethics/delete_thumbnail.php` | modified | +5 |
| 13 | `alflearning-cms/alfproduct/product_ethics/upload_all_contents.php` | modified | +5 |
| 14 | `alflearning-cms/alfproduct/product_ethics/upload_document.php` | modified | +5 |
| 15 | `alflearning-cms/alfproduct/product_ethics/upload_thumbnail.php` | modified | +5 |
| 16 | `alflearning-cms/alfproduct/product_live/add.php` | modified | +5 |
| 17 | `alflearning-cms/alfproduct/product_live/delete_thumbnail.php` | modified | +5 |
| 18 | `alflearning-cms/alfproduct/product_live/upload_thumbnail.php` | modified | +5 |
| 19 | `alflearning-cms/alfproduct/product_live_branch/add.php` | modified | +5 |
| 20 | `alflearning-cms/alfproduct/product_passport/add.php` | modified | +5 |
| 21 | `alflearning-cms/alfproduct/product_passport/delete_thumbnail.php` | modified | +5 |
| 22 | `alflearning-cms/alfproduct/product_passport/upload_thumbnail.php` | modified | +5 |

**小計: +110 行**

---

## STU-A型（32 ファイル）— GET 拒否追加のみ

| # | ファイル | 状態 | 追加行 | 備考 |
|---|---------|------|------|------|
| 1 | `alfproduct/public/exam/answer_check.php` | modified | +5 | |
| 2 | `alfproduct/public/exam/answer_check1.php` | modified | +5 | |
| 3 | `alfproduct/public/exam/answer_save.php` | modified | +5 | |
| 4 | `alfproduct/public/exam/confirm2.php` | modified | +5 | |
| 5 | `alfproduct/public/exam/index2.php` | modified | +5 | |
| 6 | `alfproduct/public/exam/resubmit_exec.php` | modified | +5 | |
| 7 | `alfproduct/public/exam/resubmit_exec1.php` | modified | +5 | |
| 8 | `alfproduct/public/exam/resubmit_exec2.php` | modified | +5 | |
| 9 | `alfproduct/public/exam/resubmit_index1.php` | modified | +5 | |
| 10 | `alfproduct/public/exam2/answer_check.php` | modified | +5 | |
| 11 | `alfproduct/public/exam2/answer_save.php` | modified | +5 | |
| 12 | `alfproduct/public/exam2/resubmit_check.php` | modified | +5 | |
| 13 | `alfproduct/public/exam2/resubmit_check_mst.php` | modified | +5 | |
| 14 | `alfproduct/public/exam2/resubmit_check_review.php` | modified | +5 | |
| 15 | `alfproduct/public/exam2/resubmit_exec.php` | modified | +5 | |
| 16 | `alfproduct/public/exam2/resubmit_exec_mst.php` | modified | +5 | |
| 17 | `alfproduct/public/exam2/resubmit_exec_review.php` | modified | +5 | |
| 18 | `alfproduct/public/ethic_treaning/question_answer.php` | modified | +5 | |
| 19 | `alfproduct/public/ethic_treaning/question_answer_retry.php` | modified | +5 | |
| 20 | `alfproduct/public/inquiry/conf.php` | **skipped** | — | 既存 REQUEST_METHOD チェックあり |
| 21 | `alfproduct/public/member/regist.php` | modified | +5 | |
| 22 | `alfproduct/public/mypage/edit.php` | modified | +5 | |
| 23 | `alfproduct/public/mypage/favorite.php` | modified | +5 | |
| 24 | `alfproduct/public/mypage/receipt_download.php` | modified | +5 | |
| 25 | `alfproduct/public/mypage/refusal.php` | modified | +5 | |
| 26 | `alfproduct/public/product/detail.php` | modified | +5 | |
| 27 | `alfproduct/public/product/detail_review.php` | modified | +5 | |
| 28 | `alfproduct/public/settlement/alert_passport.php` | modified | +5 | |
| 29 | `alfproduct/public/settlement/araigae_upload.php` | modified | +5 | |
| 30 | `alfproduct/public/settlement/member_card_regist.php` | modified | +5 | |
| 31 | `alfproduct/public/settlement/payment_bank.php` | modified | +5 | |
| 32 | `alfproduct/public/settlement/payment_passport_user.php` | modified | +5 | |

**小計: +155 行（1 ファイルスキップ）**

---

## C型書込系（11 ファイル）— GET 拒否 + `$_REQUEST`→`$_POST`

| # | ファイル | 区分 | 追加行 | 置換箇所 |
|---|---------|------|------|---------|
| 1 | `alflearning-cms/alfproduct/product_lecture2/info_user.php` | CMS 必須 | +5 | 12 |
| 2 | `alflearning-cms/alfproduct/product_lecture/info_user.php` | CMS 必須 | +5 | 12 |
| 3 | `alflearning-cms/alfproduct/product_lecture2/info_user_regist.php` | CMS 必須 | +5 | 10 |
| 4 | `alflearning-cms/alfproduct/product_lecture2/info_user_import.php` | CMS 必須 | +5 | 10 |
| 5 | `alflearning-cms/alfproduct/product_lecture/info_user_regist.php` | CMS 必須 | +5 | 10 |
| 6 | `alflearning-cms/alfproduct/product_lecture/info_user_import.php` | CMS 必須 | +5 | 10 |
| 7 | `alflearning-cms/alfproduct/product_lecture_ethics/info_user_import.php` | CMS 必須 | +5 | 6 |
| 8 | `alflearning-cms/alfproduct/product_lecture_ethics/info_user_regist.php` | CMS 必須 | +5 | 4 |
| 9 | `alfproduct/public/settlement/index.php` | STU 必須 | +5 | 3 |
| 10 | `alfproduct/public/settlement/order_regist.php` | STU 必須 | +5 | 4 |
| 11 | `alfproduct/public/settlement/order_passport_user.php` | STU 必須 | +5 | 2 |

**小計: +55 行追加 / 83 箇所置換**

---

## C型表示系（15 ファイル）— `$_REQUEST`→`$_GET`のみ

| # | ファイル | 区分 | 置換箇所 | 置換対象パラメーター |
|---|---------|------|---------|---------|
| 1 | `alflearning-cms/alfproduct/product_lecture_ethics/info.php` | CMS 任意 | 14 | pid, oid, odid, res, sid 等 |
| 2 | `alflearning-cms/alfproduct/product_lecture2/info.php` | CMS 任意 | 2 | pid |
| 3 | `alflearning-cms/alfproduct/product_lecture/info.php` | CMS 任意 | 2 | pid |
| 4 | `alflearning-cms/alfproduct/product_ethics/info.php` | CMS 任意 | 2 | mid |
| 5 | `alflearning-cms/alfproduct/product/info.php` | CMS 任意 | 2 | mid |
| 6 | `alflearning-cms/alfproduct/product_live/info.php` | CMS 任意 | 2 | mid |
| 7 | `alflearning-cms/alfproduct/product_live_branch/info.php` | CMS 任意 | 2 | mid |
| 8 | `alflearning-cms/alfproduct/product_passport/info.php` | CMS 任意 | 2 | mid |
| 9 | `alflearning-cms/alfproduct/amount_user/info.php` | CMS 任意 | 2 | sid |
| 10 | `alflearning-cms/alfproduct/amount_order/info.php` | CMS 任意 | 8 | mode, oid, order_detail_id, payment_status |
| 11 | `alflearning-cms/alfproduct/product_lecture_ethics/csv.php` | CMS 任意 | 8 | pid, type, aid, atype |
| 12 | `alflearning-cms/alfproduct/product_lecture2/csv.php` | CMS 任意 | 6 | pid, aid, type |
| 13 | `alflearning-cms/alfproduct/product_lecture/csv.php` | CMS 任意 | 6 | pid, aid, type |
| 14 | `alflearning-cms/alfproduct/amount_order/pdf.php` | CMS 任意 | 8 | mode, oid, order_detail_id, payment_status |
| 15 | `alfproduct/public/product/download_android.php` | STU 任意 | 4 | pid, cdname, uid |

**小計: 0 行追加 / 70 箇所置換**

---

## スキップ

| ファイル | 理由 |
|---------|------|
| `alfproduct/public/inquiry/conf.php` | 既存の REQUEST_METHOD チェックが存在（Ph.1 以前に対応済み） |

---

## 修正合計

| 区分 | ファイル数 | 追加行 | 置換箇所 |
|------|----------|------|---------|
| CMS-A型（GET 拒否のみ） | 22 | +110 | — |
| STU-A型（GET 拒否のみ） | 31 | +155 | — |
| C型書込系（GET 拒否 + POST 置換） | 11 | +55 | 83 |
| C型表示系（GET 置換のみ） | 15 | 0 | 70 |
| **合計** | **79** | **+320** | **153** |

---

## 次フェーズ

| フェーズ | 内容 | 対象件数 |
|---------|------|---------|
| **Ph.3** | `rel="noopener noreferrer"` 付与 | 22 箇所 |
| **Ph.4** | Smarty `\|escape` 追加（#A） | 77 ファイル |
| **Ph.5** | CI3 ビュー `htmlspecialchars()` 追加（#D） | 84 ファイル |
| **Ph.6** | `AlfSession.php` SameSite=Strict 変更 | 1 ファイル |
