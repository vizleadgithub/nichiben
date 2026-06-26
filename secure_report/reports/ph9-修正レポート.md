# Ph.9 修正レポート — デバッグ出力一括削除

- 実施日: 2026-06-26
- 対象ID: F-12 / F-14 / F-15b / F-11(admin) / F-8 / F-11(bat/view)
- 修正方針: 全ファイルにおけるデバッグ出力（`var_dump()` / `print_r()` / `echo` デバッグ文 / `exit()` による機能停止）を削除

---

## F-12 — 決済ページ 機密情報漏洩（最優先）

GMO / PayPal API レスポンスの `var_dump()` を削除。

| ファイル | 修正内容 |
|---------|---------|
| `alfproduct/public/settlement/member_card.php` 旧行6 | `var_dump($ret);` 削除 |
| `alfproduct/public/settlement/payment_paypal.php` 旧行77 | `var_dump($ret);` 削除 |
| `alfproduct/public/settlement/member_card_regist.php` 旧行41-42,47-48,56-57 | `echo $arr_id['id']`, `echo '<br />'`, `var_dump($ret1)`, `echo '<br />'`, `var_dump($ret2)`, `echo '<br />'` 削除（計6行） |

---

## F-14 — API Csv_download.php 機能停止修正（最優先）

`exit()` によりCSV出力APIが完全停止していた箇所を修正。

| ファイル | 修正内容 |
|---------|---------|
| `alflearning/alflearning-api/application/controllers/Csv_download.php` 旧行65-69 | `var_dump($post_type)` / `var_dump($post_csv_filename)` / `var_dump($post_csv_where)` / `print("test")` / `//exit()` 削除 |
| `alflearning/alflearning-api/application/controllers/Csv_download.php` 旧行79-80 | `var_dump($table_data); exit();` 削除 → `$csv_header` 代入以降が実行されるように復旧 |

---

## F-15b — API デバッグビュー XSS（最優先）

| ファイル | 修正内容 |
|---------|---------|
| `alflearning/alflearning-api/application/views/debug.php` 行25,29,33 | `$_SERVER['REQUEST_URI']` / `parseArray($_GET)` / `parseArray($_POST)` の出力を `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` でエスケープ |
| `alflearning/alflearning-api/application/controllers/Login.php` 旧行292-296 | POST `debug` パラメータによるデバッグビュー呼び出しブロックを `if(false){}` に変更してルートを閉鎖 |
| `alflearning/alflearning-api/application/controllers/Elm_api_test.php` 行121-133 | `_output_display()` 内の `$request_url` / `$key` / `$value` / `$content` / `print_r()` 結果を `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` でエスケープ（計5箇所） |
| `alflearning/alflearning-api/application/libraries/Curl.php` 行349 | `print_r($this->info)` → `echo htmlspecialchars(print_r($this->info, true), ENT_QUOTES, 'UTF-8')` |

---

## F-11(admin) — 商品追加ページ 機能停止修正（高）

`print("test"); exit();` および `var_dump($product_category_list); exit();` により商品追加ページが完全に動作しなかった箇所を修正。

| ファイル | 修正内容 |
|---------|---------|
| `alfproduct/admin/product/add.php` 旧行2-3 | `print("test"); exit();` 削除 |
| `alfproduct/admin/product/add.php` 旧行33-34 | `var_dump($product_category_list); exit();` 削除 |

---

## F-8 — player デバッグ出力（低）

HTMLコメントへのセッション値出力を削除。

| ファイル | 修正内容 |
|---------|---------|
| `alfproduct/public/player/index.php` 旧行134-142 | `print("<!--[student_id:...")` / `var_dump($user_id)` / `print("]-->")` 等9行削除 |
| `alfproduct/public/player/sample.php` 旧行17 | `print("<!--[".$idkey."]-->");` 削除 |

---

## F-11(bat/view) — Bat_* コントローラー デバッグ出力（低）

`echo var_dump($outputs)` および `print_r($row)` を削除。コメントアウト済みの `//var_dump()` は放置。

| ファイル | 修正箇所 |
|---------|---------|
| `Bat_cron_student_product_history.php` 旧行47 | `echo var_dump($outputs);` 削除 |
| `Bat_cron_student_product_history.php` 旧行547-549 | `print("===")` / `var_dump($sql)` / `print("===")` 3行削除 |
| `Bat_goto_streamserver.php` 旧行31 | `print_r($row);` 削除 |
| `Bat_oneoff_import_student.php` 旧行84 | `echo var_dump($outputs);` 削除 |
| `Bat_oneoff_import_tbl_bookmark.php` 旧行100 | `echo var_dump($outputs);` 削除 |
| `Bat_oneoff_import_tbl_order.php` 旧行105 | `echo var_dump($outputs);` 削除 |
| `Bat_oneoff_import_tbl_order_update.php` 旧行54 | `echo var_dump($outputs);` 削除 |
| `Bat_oneoff_import_teacher.php` 旧行83 | `echo var_dump($outputs);` 削除 |

---

## 修正ファイル総数

| ID | ファイル数 | 修正行数 |
|----|----------|---------|
| F-12 | 3 | 8行削除 |
| F-14 | 1 | 7行削除 |
| F-15b | 4 | 10箇所修正 |
| F-11(admin) | 1 | 4行削除 |
| F-8 | 2 | 10行削除 |
| F-11(bat/view) | 8 | 10行削除 |
| **合計** | **19** | **49行削除・修正** |
