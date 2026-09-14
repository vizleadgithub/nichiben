-- =====================================================================
-- F-024 / NICHIBEN_ET-392  対応案 A・B  DDL
-- 作成日: 2026-09-08
-- 対象  : alflearning（本番／stg 共通）
-- 前提  : Aurora MySQL 8.0 系（生成列を使用）
-- 注意  : 実行前に必ず対象テーブルの行数と既存索引を確認すること。
--         tbl_order / tbl_order_detail は大きいテーブルのため、
--         ALTER は ALGORITHM=INPLACE, LOCK=NONE が選ばれることを EXPLAIN 相当で確認してから流す。
-- =====================================================================


-- ---------------------------------------------------------------------
-- A-2 : 「申込有無」判定（EXISTS）用の複合索引
--       条件: product_id = ? / product_type_add = 2 / bar_association_branch_id = ?
--             / payment_status IN (1,2,3)
--       既存の KEY `product_id` (product_id, member_id, payment_status,
--       product_type_add, bar_association_id) には
--       bar_association_branch_id が含まれておらず、2 列目が member_id のため
--       この判定には効かない。
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_order_detail`
  ADD KEY `idx_od_product_type_branch_status`
    (`product_id`, `product_type_add`, `bar_association_branch_id`, `payment_status`);


-- ---------------------------------------------------------------------
-- A-3 : 旧研修集計との結合を索引可能にする
--       現行: LEFT JOIN import_kenshu_count
--               ON tbl_product.product_id = import_kenshu_count.KENSHU_ID + 10000
--       KENSHU_ID は varchar(256) のため、計算式を外して
--       「product_id - 10000 = KENSHU_ID」と書き換えても
--       文字列と数値の比較になり索引は使えない。
--       そこで結合値そのものを生成列として持たせ、そこに索引を張る。
--       import_kenshu_count は移行用の参照専用テーブルで、
--       アプリからの INSERT / UPDATE は存在しない（全 9,232 行・KENSHU_ID は全て数値・重複なし）。
-- ---------------------------------------------------------------------
ALTER TABLE `import_kenshu_count`
  ADD COLUMN `product_id_join` BIGINT
    GENERATED ALWAYS AS (CAST(`KENSHU_ID` AS UNSIGNED) + 10000) STORED
    COMMENT '結合用（tbl_product.product_id に対応）F-024 A-3',
  ADD KEY `idx_product_id_join` (`product_id_join`);


-- ---------------------------------------------------------------------
-- B-2 : お気に入りテーブルの索引（現状は索引ゼロ）
--       判定は WHERE member_id = ? AND product_id = ? AND del_flg = 0
--       (member_id, product_id) に重複が 74 組あるため UNIQUE にはしない。
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_favorite`
  ADD KEY `idx_favorite_member_product` (`member_id`, `product_id`);


-- ---------------------------------------------------------------------
-- B-5 : 入金キャンセル定期処理の日付条件用の索引
--       PHP 側で ADDDATE(order_date, INTERVAL 15 DAY) <= NOW() を
--       order_date <= (実行日時 - 15 日) に書き換え済み。
--       order_date は上限・下限の両方で絞るため単独索引で範囲が効く。
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_order`
  ADD KEY `idx_order_order_date` (`order_date`);


-- =====================================================================
-- 切り戻し
-- =====================================================================
-- ALTER TABLE `tbl_order` DROP KEY `idx_order_order_date`;
-- ALTER TABLE `tbl_favorite` DROP KEY `idx_favorite_member_product`;
-- ALTER TABLE `import_kenshu_count` DROP KEY `idx_product_id_join`, DROP COLUMN `product_id_join`;
-- ALTER TABLE `tbl_order_detail` DROP KEY `idx_od_product_type_branch_status`;
