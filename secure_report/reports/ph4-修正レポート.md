# Ph.4 修正レポート — Smarty テンプレート `|escape` 追加（#A）

**実施日:** 2026-06-25  
**対応区分:** 管理画面 XSS（優先度：低）— Smarty 変数の出力エスケープ漏れ

---

## サマリー

| 項目 | 値 |
|------|-----|
| 対象ファイル総数 | 66 |
| 修正（`|escape` 追加） | 58 ファイル |
| 対応済み（既に `|escape` 有） | 3 ファイル |
| スキップ（`pager` のみ or 変数なし） | 5 ファイル |
| **追加修正箇所合計** | **143 箇所** |

> **スキップ方針:**  
> `pager`（CI3 Pagination が生成する HTML フラグメント）と `admin_main_side_menu`（同様 HTML フラグメント）は  
> `|escape` 追加の対象外。その他の変数はすべて追加済み。

---

## 修正パターン

```diff
- <!--{$variable}-->
+ <!--{$variable|escape}-->

- <!--{$variable|urlencode}-->
+ <!--{$variable|urlencode|escape}-->

- <!--{$variable|number_format}-->
+ <!--{$variable|number_format|escape}-->
```

---

## フレーム / 共通（3 ファイル・10 箇所）

| ファイル | 状態 | 箇所数 | 対象変数 |
|---------|------|------|---------|
| `main_frame.tpl` | modified | 6 | admin_main_title(×3), admin_main_name, admin_main_school, admin_main_comment |
| `main_frame_non.tpl` | modified | 1 | admin_main_title |
| `pop_frame.tpl` | modified | 3 | admin_main_title(×2), admin_main_comment |

---

## 金額集計系（7 ファイル・20 箇所）

| ファイル | 状態 | 箇所数 | 対象変数 |
|---------|------|------|---------|
| `amount_order/index.tpl` | modified | 3 | row.name(×3) |
| `amount_order/info.tpl` | modified | 4 | arr_order[0].lawyer_number, arr_order[0].student_name(×2), arr_order[0].association_name |
| `amount_passport/index.tpl` | modified | 4 | row.name(×2), passport_target_name, row.disp_passport_target |
| `amount_product/index.tpl` | modified | 2 | search_product_name, search_product_code |
| `amount_user/index.tpl` | modified | 1 | row.name |
| `amount_user/info.tpl` | modified | 3 | arr_student.lawyer_number, arr_student.student_name, arr_student.association_name |
| `bank_upload/index.tpl` | modified | 3 | err_msg, ok_msg(×2) |

---

## 問合・メルマガ系（9 ファイル・23 箇所）

| ファイル | 状態 | 箇所数 | 対象変数 |
|---------|------|------|---------|
| `inquiry/conf.tpl` | modified | 2 | prev_url, next_url |
| `inquiry/form.tpl` | already_done | 0 | — |
| `inquiry/index.tpl` | modified | 2 | search_keyword, search_lawyer_number |
| `inquiry/info.tpl` | modified | 8 | iid(×5), mid, page(×2)（JS URL 内） |
| `inquiry/status.tpl` | modified | 2 | next_url, err |
| `inquiry/status_conf.tpl` | modified | 2 | prev_url, next_url |
| `mailmagazine/conf.tpl` | modified | 2 | prev_url, next_url |
| `mailmagazine/form.tpl` | modified | 3 | next_url, err, row.name |
| `mailmagazine/index.tpl` | modified | 2 | search_keyword, row.mail_title |

---

## 商品系（11 ファイル・15 箇所）

| ファイル | 状態 | 箇所数 | 対象変数 |
|---------|------|------|---------|
| `product/info.tpl` | modified | 1 | arr_input.exam2_id |
| `product/search_contents_so.tpl` | modified | 1 | row.video_logic_name |
| `product/search_elive.tpl` | modified | 2 | row.product_name(×2) |
| `product/search_product.tpl` | modified | 2 | row.product_name(×2) |
| `product/search_product_ranking.tpl` | modified | 2 | row.product_name(×2) |
| `product/search_student.tpl` | modified | 3 | row.name, row.student_name(×2) |
| `product_ethics/add_confirm.tpl` | modified | 1 | ethic_group[$arr_input.ethic_group_id] |
| `product_ethics/index.tpl` | skipped | 0 | pager のみ |
| `product_ethics/info.tpl` | already_done | 0 | — |
| `product_ethics/search_contents.tpl` | modified | 1 | row.video_logic_name |
| `product_ethics/search_product.tpl` | modified | 2 | row.product_name(×2) |

---

## 講義・講座系（19 ファイル・47 箇所）

| ファイル | 状態 | 箇所数 | 対象変数 |
|---------|------|------|---------|
| `product_lecture/index.tpl` | modified | 3 | row.name(×2), row2.name |
| `product_lecture/info.tpl` | modified | 2 | arr_input.product_name, arr_input.dates |
| `product_lecture/info_user.tpl` | modified | 3 | arr_input_2.bar_association_name, arr_input_2.bar_association_branch_name, arr_input_2.product_name |
| `product_lecture/info_user_import.tpl` | modified | 11 | arr_input_2.bar_association_name, arr_input_2.bar_association_branch_name, arr_input_2.product_name, arr_input_2.dates, arr_input_2.entry_number, arr_input_2.capacity, err_msg, row.lawyer_number, row.student_name, row.bar_association_name, res_msg |
| `product_lecture/info_user_regist.tpl` | modified | 7 | arr_input_2.bar_association_name, arr_input_2.bar_association_branch_name, arr_input_2.product_name, arr_input_2.dates, arr_input_2.entry_number, arr_input_2.capacity, res_msg |
| `product_lecture2/index.tpl` | modified | 3 | row.name(×2), row2.name |
| `product_lecture2/info.tpl` | modified | 1 | arr_input.product_name |
| `product_lecture2/info_user.tpl` | skipped | 0 | arr_input.* 出現なし（arr_input_2 は別変数） |
| `product_lecture2/info_user_import.tpl` | modified | 1 | err_msg |
| `product_lecture2/info_user_regist.tpl` | modified | 2 | arr_input_2.product_name, res_msg |
| `product_lecture2/product_lecture2/index.tpl` | modified | 2 | row.name, row2.name |
| `product_lecture2/product_lecture2/info.tpl` | modified | 2 | arr_input.product_name, arr_input.dates |
| `product_lecture2/product_lecture2/info_user.tpl` | modified | 1 | arr_input_2.product_name |
| `product_lecture2/product_lecture2/info_user_import.tpl` | modified | 1 | err_msg |
| `product_lecture2/product_lecture2/info_user_regist.tpl` | modified | 2 | arr_input_2.product_name, res_msg |
| `product_lecture_ethics/index.tpl` | already_done | 0 | — |
| `product_lecture_ethics/info.tpl` | modified | 1 | arr_input.product_name |
| `product_lecture_ethics/info_user_import.tpl` | modified | 5 | arr_input_2.product_name, err_msg, row.lawyer_number, row.student_name, res_msg |
| `product_lecture_ethics/info_user_regist.tpl` | modified | 2 | arr_input_2.product_name, res_msg |

---

## ライブ・パスポート・レポート系（17 ファイル・28 箇所）

| ファイル | 状態 | 箇所数 | 対象変数 |
|---------|------|------|---------|
| `product_live/add.tpl` | modified | 2 | val.name, branch.name |
| `product_live/add_confirm.tpl` | modified | 5 | mtb_live_training_type[...], mtb_live_target_flg[...], val.name, branch.name, arr_input.$dates |
| `product_live/approval.tpl` | skipped | 0 | pager のみ |
| `product_live/index.tpl` | modified | 1 | mtb_bar_association[$row.bar_association_id] |
| `product_live/info.tpl` | modified | 3 | val.name, branch.name, arr_input.$dates |
| `product_live/search_product.tpl` | modified | 2 | row.product_name(×2) |
| `product_live_branch/add.tpl` | modified | 2 | msg, arr_input.$dates |
| `product_live_branch/add_confirm.tpl` | modified | 3 | val.name, branch.name, arr_input.$dates |
| `product_live_branch/index.tpl` | modified | 1 | mtb_bar_association[$row.bar_association_id] |
| `product_live_branch/info.tpl` | modified | 2 | branch.bar_association_branch_name, branch.dates |
| `product_passport/add.tpl` | modified | 1 | msg |
| `product_passport/add_confirm.tpl` | modified | 1 | arr_passport_target.$val |
| `product_passport/index.tpl` | skipped | 0 | pager のみ |
| `product_passport/info.tpl` | modified | 1 | arr_passport_target.$val |
| `product_passport/search_product.tpl` | modified | 2 | row.product_name(×2) |
| `report_product/index.tpl` | modified | 2 | row.name(×2) |
| `report_product/info.tpl` | skipped | 0 | pager のみ |

---

## 修正合計

| 区分 | ファイル数 | 修正箇所数 |
|------|----------|---------|
| フレーム / 共通 | 3 | 10 |
| 金額集計系 | 7 | 20 |
| 問合・メルマガ系 | 9（1 already_done） | 23 |
| 商品系 | 11（1 already_done・1 skipped） | 15 |
| 講義・講座系 | 19（1 already_done・1 skipped） | 47 |
| ライブ・パスポート・レポート系 | 17（3 skipped） | 28 |
| **合計** | **66（3 already_done・5 skipped・58 modified）** | **143** |

---

## スコープ外・特記事項

| 事項 | 理由 |
|------|------|
| `pager` 変数 | CI3 Pagination クラスが生成する HTML フラグメント — `|escape` 追加不可 |
| `admin_main_side_menu` | HTML フラグメント — `|escape` 追加不可 |
| `alfproduct/admin/` 管理画面 PHP | DocumentRoot 外（Web 非到達）— スコープ外 |

---

## 次フェーズ

| フェーズ | 内容 | 対象件数 |
|---------|------|---------|
| **Ph.5** | CI3 ビュー `htmlspecialchars()` 追加（#D） | 84 ファイル |
| **Ph.6** | `AlfSession.php` SameSite=Strict 変更 | 1 ファイル |
