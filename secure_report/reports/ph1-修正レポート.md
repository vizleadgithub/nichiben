# Ph.1 修正レポート — デバッグ echo / print 削除

**実施日:** 2026-06-24  
**対応区分:** CMS-H-01〜16（必須）＋ F-8b（同時対応）

---

## サマリー

| 項目 | 値 |
|------|-----|
| 修正ファイル数 | 5 |
| 削除行数（合計） | 9 行 |
| 追加行 | 0（削除のみ） |
| AppScan 解消件数 | CMS-H-01〜16（高リスク × 16 件） |

> **AppScan への影響:**  
> CMS-H-01〜06（反射型 XSS × 6件）と CMS-H-07〜16（格納型 XSS × 10件）の根本原因である  
> `echo "<!--[..SQL..]-->"` を削除。再診断で高リスク 16 件がすべて解消される見込み。

---

## 修正ファイル一覧

| # | ファイル | 区分 | 削除行番号 | 削除行数 |
|---|---------|------|-----------|---------|
| 1 | `alflearning-cms/alfproduct/product/index.php` | CMS-H-01〜16 必須 | 356〜357 | 2 |
| 2 | `alflearning-cms/alfproduct/product_lecture_ethics/index.php` | F-8b 同時対応 | 429〜430 | 2 |
| 3 | `alflearning-cms/alfproduct/product_ethics/index.php` | F-8b 同時対応 | 361〜362 | 2 |
| 4 | `alflearning-cms/alfproduct/product_lecture2/csv.php` | F-8b 同時対応 | 194 | 1 |
| 5 | `alflearning-cms/alfproduct/product_live/index20260324.php` | F-8b 同時対応 | 400〜401 | 2 |

---

## 修正差分

### 1. `alflearning-cms/alfproduct/product/index.php`

**区分:** CMS-H-01〜16 必須 / **削除行:** 356〜357 (-2行)

```diff
@@ -353,8 +353,6 @@
 //$sql = "select product_id,...";
 $sql = "select tbl_product.product_id,tbl_product.product_name, ...";
 
-echo "<!--[".$sql.$where.$order.$offset."]-->";
-
 $ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
```

> SQL クエリ全文（WHERE 句 = ユーザー入力含む）が HTML コメントに出力されていた。CMS-H 系 XSS の直接的な発生源。

---

### 2. `alflearning-cms/alfproduct/product_lecture_ethics/index.php`

**区分:** F-8b 同時対応 / **削除行:** 429〜430 (-2行)

```diff
@@ -426,7 +426,5 @@
 //$sql = "select ..."; // コメントアウト済み旧SQL
 
-	echo "<!--[".$sql.$where.$group.$order.$offset."]-->";
-
 	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
```

> product/index.php と同一パターン。GROUP BY 句も含むため変数が追加されている。AppScan 未到達ページだが同時削除。

---

### 3. `alflearning-cms/alfproduct/product_ethics/index.php`

**区分:** F-8b 同時対応 / **削除行:** 361〜362 (-2行)

```diff
@@ -358,7 +358,5 @@
 $sql = "select tbl_product.product_id, ...";
 
 
-echo "<!--[".$sql.$where.$order.$offset."]-->";
-
 $ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
```

> 倫理研修商品一覧。product/index.php と完全に同一のパターン。

---

### 4. `alflearning-cms/alfproduct/product_lecture2/csv.php`

**区分:** F-8b 同時対応 / **削除行:** 194 (-1行)

```diff
@@ -191,6 +191,5 @@
 $arr_input_2 = $objDbConnect->query_fetch($sql);
 if (!$arr_input_2) {
-print("<!--[".$sql."]-->");
 	echo '情報の取得に失敗しました。';
 	exit;
```

> 他と異なりエラー条件内に記述。クエリ失敗時に SQL 文字列をそのまま出力していた。

---

### 5. `alflearning-cms/alfproduct/product_live/index20260324.php`

**区分:** F-8b 同時対応 / **削除行:** 400〜401 (-2行)

```diff
@@ -396,8 +396,6 @@
  ";
 //echo $sql.$where.$order;  // 既にコメントアウト済みの旧残存
 
-echo "<!--[".$sql.$where.$order.$offset."]-->";
-
 $ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
```

> ファイル名に日付 (20260324) が付く旧版ファイル。コメントアウト済みの echo が別途残存しており、有効な echo も共存していた。

---

## スコープ補足

- **F-* 除外方針:** F-8b 以外の F-* 系列（F-1〜F-16）はすべて「PDF スキャン対象外」として除外済み。
- **セッション格納前エスケープ（行 138〜146）は非対応:** echo 削除で XSS ベクターを完全に除去しているため不要。SQL WHERE 句に入る値に HTML エスケープを掛けることは不正確になるため見送り。テンプレート表示エスケープは Ph.4（#A Smarty `|escape`）でカバー。

---

## 次フェーズ

| フェーズ | 内容 | 対象件数 |
|---------|------|---------|
| **Ph.2** | A型 405 GET 排除 + C型 `$_REQUEST`→`$_POST` | 65 ファイル |
| **Ph.3** | `rel="noopener noreferrer"` 付与 | 22 箇所 |
| **Ph.4** | Smarty `\|escape` 追加（#A） | 77 ファイル |
| **Ph.5** | CI3 ビュー `htmlspecialchars()` 追加（#D） | 84 ファイル |
| **Ph.6** | `AlfSession.php` SameSite=Strict 変更 | 1 ファイル |
