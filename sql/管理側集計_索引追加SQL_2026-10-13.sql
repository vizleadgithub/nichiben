-- =====================================================================
-- 管理側の売上(注文)の検索を速くするための索引追加 2026-10-13
-- 対象: alflearning(stg2 で作成・確認済み。本番は、ソースの反映の前に、実行する)
--
-- 経緯: 売上(注文)(alfproduct/amount_order/index.php)の検索が遅い(stg2: 登録番号・氏名で 17〜35 秒)。
--       実行計画(EXPLAIN ANALYZE)で、次の2点が、原因と分かった。
--       1) tbl_order に、購入日(create_date)の索引がなく、購入日で絞っても、全件(約 203万行)を走査する
--       2) tbl_order_detail に、会員ID(member_id)から探せる索引がない
--          (既存は product_id, member_id, ... の複合索引で、member_id が先頭でない)。
--          そのため、登録番号・氏名で会員を絞っても、会員から注文明細を引けず、注文の全件を走査していた
--
-- 効果(stg2): 購入日 1日の検索 18秒 → 0.14秒 / 登録番号の範囲検索 35秒 → 0.17秒 / 氏名検索 35秒 → 0.47秒
--             登録番号の範囲検索の件数用SQLの実行計画: 18秒 → 0.24ミリ秒
--
-- 注意(必ず守る):
--   * ソースの反映(amount_order/index.php の修正)と、この索引の追加は、両方が必要。
--     索引なしでソースだけ反映すると、登録番号・氏名の検索が、修正前より遅くなる(登録番号 1件で 0.8秒 → 2.2秒)
--   * 実行前に、SHOW INDEX で、既存の索引を確認する(同じ名前があると #1061 で止まる)
--   * ALGORITHM=INPLACE, LOCK=NONE を指定し、実行中も、読み書きができるようにする(stg2 では、1本が数秒)
--   * 本番では、利用の少ない時間帯に実行する(ddl/F-024_ddl.sql と同じ扱い)
--   * phpMyAdmin の構文チェックが、ALGORITHM=INPLACE, LOCK=NONE を、「認識できない alter 操作」と表示することがあるが、
--     実際のエラーではない(実行すれば、通る)
-- =====================================================================
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- 手順1: 事前確認(既存の索引。idx_order_create_date・idx_od_member_order が、ないこと)
-- ---------------------------------------------------------------------
SHOW INDEX FROM tbl_order;
SHOW INDEX FROM tbl_order_detail;

-- ---------------------------------------------------------------------
-- 手順2: tbl_order に、購入日の索引を追加する(stg2 では、作成済み)
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_order`
  ADD KEY `idx_order_create_date` (`create_date`),
  ALGORITHM=INPLACE, LOCK=NONE;

-- ---------------------------------------------------------------------
-- 手順3: tbl_order_detail に、会員ID から探す索引を追加する(stg2 では、作成済み。4.7秒)
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_order_detail`
  ADD KEY `idx_od_member_order` (`member_id`, `order_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

-- ---------------------------------------------------------------------
-- 手順4: 確認
-- ---------------------------------------------------------------------
SHOW INDEX FROM tbl_order;          -- idx_order_create_date が、あること
SHOW INDEX FROM tbl_order_detail;   -- idx_od_member_order が、あること

-- 登録番号の範囲検索で、会員の索引(idx_lawyer_number_division)から始まり、
-- tbl_order_detail の idx_od_member_order が使われること(「Table scan on tbl_order」から始まらないこと)
EXPLAIN ANALYZE
SELECT COUNT(tbl_order.order_id) AS c
FROM tbl_order
LEFT JOIN tbl_order_detail ON tbl_order.order_id = tbl_order_detail.order_id
LEFT JOIN student ON student.student_id = tbl_order_detail.member_id
WHERE student.school_id = (SELECT school_id FROM student WHERE student_id = 36366)
  AND tbl_order_detail.product_id IS NOT NULL
  AND student.lawyer_number >= '10000' AND student.lawyer_number <= '10010'
  AND (tbl_order.claim_flg = '0' OR tbl_order.claim_flg = '1');

-- 購入日の条件で、idx_order_create_date が使われること(key に idx_order_create_date が出る)
EXPLAIN
SELECT COUNT(tbl_order.order_id) AS c
FROM tbl_order
WHERE tbl_order.create_date >= '2026-10-12 00:00' AND tbl_order.create_date <= '2026-10-12 23:59:59';

-- ---------------------------------------------------------------------
-- 戻す場合(索引だけを削除する。データには影響しない)
-- ---------------------------------------------------------------------
-- ALTER TABLE `tbl_order` DROP KEY `idx_order_create_date`;
-- ALTER TABLE `tbl_order_detail` DROP KEY `idx_od_member_order`;
