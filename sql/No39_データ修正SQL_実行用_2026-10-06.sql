-- =====================================================================
-- No.39 保存済みデータの修正SQL(実行用) 2026-10-06
-- 対象テーブル: tbl_product  /  対象: 15商品・44件(列×行)
--   contents_contentsN_name 23件 / contents_contentsNso_name 18件 / contents_download_beforeN_M 2件 / teacher 1件
-- 修正内容: 無害化済みの文字(&amp; &nbsp; &#22989;)で保存されたものを、本来の文字(& 空白 姍)に戻す
-- 実行前提: 入力画面の不具合(|escape|urlencode の順序)を修正したソースを、先に本番へ反映していること
--   (未反映のまま実行すると、商品の再保存で「&amp;」に戻ってしまう)
-- 各UPDATEのWHERE句に旧値を含めているため、旧値と異なる行は更新されない(0行)。
-- 検証元: 本番ダンプ(2026-09-29)。実行時点の値が違えば、手順3の合計が44にならないので判別できる。
-- 実行方法: 手順ごとに、phpMyAdmin等で順に実行すること(手順3のSTART TRANSACTIONからSELECT @nまでを実行→件数を確認→COMMITまたはROLLBACKを別に実行)。
--   mysql < ファイル の一括実行は不可。COMMITがコメントのため、接続を閉じたときに更新がすべて取り消される(エラーは出ない)。
-- (C)の空白は、&nbsp;(画面上は空白)に対応する通常の半角スペースに置き換える。
-- =====================================================================
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- 手順1 事前確認: 修正対象が44件ある(旧値のまま残っている)ことを確認する → 結果は44
-- ---------------------------------------------------------------------
SELECT COUNT(*) AS target_rows FROM (
SELECT product_id FROM tbl_product WHERE product_id = 19674 AND contents_contents3_name = '03_9.Q&amp;A【E10109】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20156 AND contents_contents8_name = '08_第2_03 電子書籍に対応した 出版権の整備②（主なQ&amp;A，参考イメージ）【lib273】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20297 AND contents_contents2_name = '02_Ⅰなぜ今、中小企業M&amp;Aなのか？【E10130】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20571 AND contents_contents4_name = '04_M&amp;Aの進め方と留意点1【E10142】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20571 AND contents_contents5_name = '05_M&amp;Aの進め方と留意点2_3【E10142】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24301 AND contents_contents3_name = '03第4_事業承継におけるM&amp;Aの実情と新しい「中小M&amp;Aガイドライン」内容_最後に【E10323】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24576 AND contents_contents1_name = '01_1_総論_2_M&amp;Aの流れ_3_中小M&amp;Aの課題【E10355】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24577 AND contents_contents2_name = '02_2_M&amp;A手続の流れ【E10356】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents1_name = '01_はじめに_1_弁護士の職務の特長-3_M&amp;Aに向かう経営者との出会い【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents2_name = '02_4_M&amp;Aに向かう経営者の心理-6_弁護士がM&amp;Aを担当する意味【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24580 AND contents_contents2_name = '02_第2_M&amp;A仲介業務委託契約書【E10357】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents1_name = '01_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(1)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents2_name = '02_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(2)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents3_name = '03_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(3)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents4_name = '04_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(4)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents5_name = '05_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(5)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents6_name = '06_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(6)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents2_name = '02_第2_M&amp;Aの成立のためのDD(1)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents3_name = '03_第2_M&amp;Aの成立のためのDD(2)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents1_name = '01_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(1)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents2_name = '02_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(2)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents3_name = '03_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(3)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25087 AND contents_contents1_name = '01_1_M&amp;A仲介契約書の解説_2_秘密保持契約書の解説【E10407】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24301 AND contents_contents3so_name = '03第4_事業承継におけるM&amp;Aの実情と新しい「中小M&amp;Aガイドライン」内容_最後に【E10323】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24576 AND contents_contents1so_name = '01_1_総論_2_M&amp;Aの流れ_3_中小M&amp;Aの課題【E10355】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24577 AND contents_contents2so_name = '02_2_M&amp;A手続の流れ【E10356】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents1so_name = '01_はじめに_1_弁護士の職務の特長-3_M&amp;Aに向かう経営者との出会い【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents2so_name = '02_4_M&amp;Aに向かう経営者の心理-6_弁護士がM&amp;Aを担当する意味【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24580 AND contents_contents2so_name = '02_第2_M&amp;A仲介業務委託契約書【E10357】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents1so_name = '01_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(1)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents2so_name = '02_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(2)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents3so_name = '03_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(3)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents4so_name = '04_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(4)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents5so_name = '05_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(5)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents6so_name = '06_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(6)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents2so_name = '02_第2_M&amp;Aの成立のためのDD(1)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents3so_name = '03_第2_M&amp;Aの成立のためのDD(2)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents1so_name = '01_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(1)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents2so_name = '02_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(2)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents3so_name = '03_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(3)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25087 AND contents_contents1so_name = '01_1_M&amp;A仲介契約書の解説_2_秘密保持契約書の解説【E10407】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 13 AND contents_download_before1_1 = '第一部&nbsp;裁判員裁判大づかみ(全編).pdf'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 13 AND contents_download_before1_10 = '第二部&nbsp;裁判員のこころを掴む(全編).pdf'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 14206 AND teacher = '矢吹公敏（東京）、向宣明（第一東京）、宮川裕光（第二東京）、姜&#22989;（中国律師）'
) t;

-- ---------------------------------------------------------------------
-- 手順2 バックアップ: 対象15商品の行を丸ごと別テーブルへ退避する → 結果は15行
-- (これとは別に、運用手順どおりtbl_productのダンプも取得してください)
-- ---------------------------------------------------------------------
CREATE TABLE tbl_product_bk_no39_20261006 AS SELECT * FROM tbl_product WHERE product_id IN (13,14206,19674,20156,20297,20571,24301,24576,24577,24578,24580,24740,25082,25086,25087);
SELECT COUNT(*) AS backup_rows FROM tbl_product_bk_no39_20261006;

-- ---------------------------------------------------------------------
-- 手順3 更新(トランザクション内)。最後の updated_rows が 44 であることを確認してからCOMMIT
-- ---------------------------------------------------------------------
START TRANSACTION;
SET @n = 0;
-- (A)(B) コンテンツ名
UPDATE tbl_product SET contents_contents3_name = '03_9.Q&A【E10109】' WHERE product_id = 19674 AND contents_contents3_name = '03_9.Q&amp;A【E10109】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents8_name = '08_第2_03 電子書籍に対応した 出版権の整備②（主なQ&A，参考イメージ）【lib273】' WHERE product_id = 20156 AND contents_contents8_name = '08_第2_03 電子書籍に対応した 出版権の整備②（主なQ&amp;A，参考イメージ）【lib273】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_Ⅰなぜ今、中小企業M&Aなのか？【E10130】' WHERE product_id = 20297 AND contents_contents2_name = '02_Ⅰなぜ今、中小企業M&amp;Aなのか？【E10130】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents4_name = '04_M&Aの進め方と留意点1【E10142】' WHERE product_id = 20571 AND contents_contents4_name = '04_M&amp;Aの進め方と留意点1【E10142】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents5_name = '05_M&Aの進め方と留意点2_3【E10142】' WHERE product_id = 20571 AND contents_contents5_name = '05_M&amp;Aの進め方と留意点2_3【E10142】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3_name = '03第4_事業承継におけるM&Aの実情と新しい「中小M&Aガイドライン」内容_最後に【E10323】' WHERE product_id = 24301 AND contents_contents3_name = '03第4_事業承継におけるM&amp;Aの実情と新しい「中小M&amp;Aガイドライン」内容_最後に【E10323】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1_name = '01_1_総論_2_M&Aの流れ_3_中小M&Aの課題【E10355】' WHERE product_id = 24576 AND contents_contents1_name = '01_1_総論_2_M&amp;Aの流れ_3_中小M&amp;Aの課題【E10355】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_2_M&A手続の流れ【E10356】' WHERE product_id = 24577 AND contents_contents2_name = '02_2_M&amp;A手続の流れ【E10356】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1_name = '01_はじめに_1_弁護士の職務の特長-3_M&Aに向かう経営者との出会い【E10359】' WHERE product_id = 24578 AND contents_contents1_name = '01_はじめに_1_弁護士の職務の特長-3_M&amp;Aに向かう経営者との出会い【E10359】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_4_M&Aに向かう経営者の心理-6_弁護士がM&Aを担当する意味【E10359】' WHERE product_id = 24578 AND contents_contents2_name = '02_4_M&amp;Aに向かう経営者の心理-6_弁護士がM&amp;Aを担当する意味【E10359】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_第2_M&A仲介業務委託契約書【E10357】' WHERE product_id = 24580 AND contents_contents2_name = '02_第2_M&amp;A仲介業務委託契約書【E10357】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1_name = '01_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(1)【E10217】' WHERE product_id = 24740 AND contents_contents1_name = '01_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(1)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(2)【E10217】' WHERE product_id = 24740 AND contents_contents2_name = '02_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(2)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3_name = '03_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(3)【E10217】' WHERE product_id = 24740 AND contents_contents3_name = '03_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(3)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents4_name = '04_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(4)【E10217】' WHERE product_id = 24740 AND contents_contents4_name = '04_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(4)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents5_name = '05_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(5)【E10217】' WHERE product_id = 24740 AND contents_contents5_name = '05_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(5)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents6_name = '06_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(6)【E10217】' WHERE product_id = 24740 AND contents_contents6_name = '06_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(6)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_第2_M&Aの成立のためのDD(1)【E10406】' WHERE product_id = 25082 AND contents_contents2_name = '02_第2_M&amp;Aの成立のためのDD(1)【E10406】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3_name = '03_第2_M&Aの成立のためのDD(2)【E10406】' WHERE product_id = 25082 AND contents_contents3_name = '03_第2_M&amp;Aの成立のためのDD(2)【E10406】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1_name = '01_第1回_中小M&Aにおける買いサイド支援の実務－総論(1)【E10405】' WHERE product_id = 25086 AND contents_contents1_name = '01_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(1)【E10405】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2_name = '02_第1回_中小M&Aにおける買いサイド支援の実務－総論(2)【E10405】' WHERE product_id = 25086 AND contents_contents2_name = '02_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(2)【E10405】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3_name = '03_第1回_中小M&Aにおける買いサイド支援の実務－総論(3)【E10405】' WHERE product_id = 25086 AND contents_contents3_name = '03_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(3)【E10405】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1_name = '01_1_M&A仲介契約書の解説_2_秘密保持契約書の解説【E10407】' WHERE product_id = 25087 AND contents_contents1_name = '01_1_M&amp;A仲介契約書の解説_2_秘密保持契約書の解説【E10407】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3so_name = '03第4_事業承継におけるM&Aの実情と新しい「中小M&Aガイドライン」内容_最後に【E10323】' WHERE product_id = 24301 AND contents_contents3so_name = '03第4_事業承継におけるM&amp;Aの実情と新しい「中小M&amp;Aガイドライン」内容_最後に【E10323】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1so_name = '01_1_総論_2_M&Aの流れ_3_中小M&Aの課題【E10355】' WHERE product_id = 24576 AND contents_contents1so_name = '01_1_総論_2_M&amp;Aの流れ_3_中小M&amp;Aの課題【E10355】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2so_name = '02_2_M&A手続の流れ【E10356】' WHERE product_id = 24577 AND contents_contents2so_name = '02_2_M&amp;A手続の流れ【E10356】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1so_name = '01_はじめに_1_弁護士の職務の特長-3_M&Aに向かう経営者との出会い【E10359】' WHERE product_id = 24578 AND contents_contents1so_name = '01_はじめに_1_弁護士の職務の特長-3_M&amp;Aに向かう経営者との出会い【E10359】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2so_name = '02_4_M&Aに向かう経営者の心理-6_弁護士がM&Aを担当する意味【E10359】' WHERE product_id = 24578 AND contents_contents2so_name = '02_4_M&amp;Aに向かう経営者の心理-6_弁護士がM&amp;Aを担当する意味【E10359】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2so_name = '02_第2_M&A仲介業務委託契約書【E10357】' WHERE product_id = 24580 AND contents_contents2so_name = '02_第2_M&amp;A仲介業務委託契約書【E10357】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1so_name = '01_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(1)【E10217】' WHERE product_id = 24740 AND contents_contents1so_name = '01_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(1)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2so_name = '02_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(2)【E10217】' WHERE product_id = 24740 AND contents_contents2so_name = '02_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(2)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3so_name = '03_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(3)【E10217】' WHERE product_id = 24740 AND contents_contents3so_name = '03_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(3)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents4so_name = '04_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(4)【E10217】' WHERE product_id = 24740 AND contents_contents4so_name = '04_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(4)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents5so_name = '05_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(5)【E10217】' WHERE product_id = 24740 AND contents_contents5so_name = '05_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(5)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents6so_name = '06_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(6)【E10217】' WHERE product_id = 24740 AND contents_contents6so_name = '06_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(6)【E10217】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2so_name = '02_第2_M&Aの成立のためのDD(1)【E10406】' WHERE product_id = 25082 AND contents_contents2so_name = '02_第2_M&amp;Aの成立のためのDD(1)【E10406】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3so_name = '03_第2_M&Aの成立のためのDD(2)【E10406】' WHERE product_id = 25082 AND contents_contents3so_name = '03_第2_M&amp;Aの成立のためのDD(2)【E10406】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1so_name = '01_第1回_中小M&Aにおける買いサイド支援の実務－総論(1)【E10405】' WHERE product_id = 25086 AND contents_contents1so_name = '01_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(1)【E10405】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents2so_name = '02_第1回_中小M&Aにおける買いサイド支援の実務－総論(2)【E10405】' WHERE product_id = 25086 AND contents_contents2so_name = '02_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(2)【E10405】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents3so_name = '03_第1回_中小M&Aにおける買いサイド支援の実務－総論(3)【E10405】' WHERE product_id = 25086 AND contents_contents3so_name = '03_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(3)【E10405】';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_contents1so_name = '01_1_M&A仲介契約書の解説_2_秘密保持契約書の解説【E10407】' WHERE product_id = 25087 AND contents_contents1so_name = '01_1_M&amp;A仲介契約書の解説_2_秘密保持契約書の解説【E10407】';
SET @n = @n + ROW_COUNT();
-- (C) ダウンロード表示名(&nbsp;混入)
UPDATE tbl_product SET contents_download_before1_1 = '第一部 裁判員裁判大づかみ(全編).pdf' WHERE product_id = 13 AND contents_download_before1_1 = '第一部&nbsp;裁判員裁判大づかみ(全編).pdf';
SET @n = @n + ROW_COUNT();
UPDATE tbl_product SET contents_download_before1_10 = '第二部 裁判員のこころを掴む(全編).pdf' WHERE product_id = 13 AND contents_download_before1_10 = '第二部&nbsp;裁判員のこころを掴む(全編).pdf';
SET @n = @n + ROW_COUNT();
-- (D) 講師名(&#22989;混入)
UPDATE tbl_product SET teacher = '矢吹公敏（東京）、向宣明（第一東京）、宮川裕光（第二東京）、姜姍（中国律師）' WHERE product_id = 14206 AND teacher = '矢吹公敏（東京）、向宣明（第一東京）、宮川裕光（第二東京）、姜&#22989;（中国律師）';
SET @n = @n + ROW_COUNT();
SELECT @n AS updated_rows;   -- 期待値: 44
-- 期待値と一致したら:  COMMIT;
-- 一致しない場合は:    ROLLBACK;  (原因を確認してから再実行)

-- ---------------------------------------------------------------------
-- 手順4 COMMIT後の確認
-- ---------------------------------------------------------------------
-- (a) 旧値が残っていない: 結果は0
SELECT COUNT(*) AS old_value_rows FROM (
SELECT product_id FROM tbl_product WHERE product_id = 19674 AND contents_contents3_name = '03_9.Q&amp;A【E10109】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20156 AND contents_contents8_name = '08_第2_03 電子書籍に対応した 出版権の整備②（主なQ&amp;A，参考イメージ）【lib273】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20297 AND contents_contents2_name = '02_Ⅰなぜ今、中小企業M&amp;Aなのか？【E10130】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20571 AND contents_contents4_name = '04_M&amp;Aの進め方と留意点1【E10142】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20571 AND contents_contents5_name = '05_M&amp;Aの進め方と留意点2_3【E10142】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24301 AND contents_contents3_name = '03第4_事業承継におけるM&amp;Aの実情と新しい「中小M&amp;Aガイドライン」内容_最後に【E10323】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24576 AND contents_contents1_name = '01_1_総論_2_M&amp;Aの流れ_3_中小M&amp;Aの課題【E10355】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24577 AND contents_contents2_name = '02_2_M&amp;A手続の流れ【E10356】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents1_name = '01_はじめに_1_弁護士の職務の特長-3_M&amp;Aに向かう経営者との出会い【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents2_name = '02_4_M&amp;Aに向かう経営者の心理-6_弁護士がM&amp;Aを担当する意味【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24580 AND contents_contents2_name = '02_第2_M&amp;A仲介業務委託契約書【E10357】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents1_name = '01_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(1)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents2_name = '02_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(2)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents3_name = '03_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(3)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents4_name = '04_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(4)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents5_name = '05_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(5)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents6_name = '06_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(6)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents2_name = '02_第2_M&amp;Aの成立のためのDD(1)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents3_name = '03_第2_M&amp;Aの成立のためのDD(2)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents1_name = '01_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(1)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents2_name = '02_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(2)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents3_name = '03_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(3)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25087 AND contents_contents1_name = '01_1_M&amp;A仲介契約書の解説_2_秘密保持契約書の解説【E10407】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24301 AND contents_contents3so_name = '03第4_事業承継におけるM&amp;Aの実情と新しい「中小M&amp;Aガイドライン」内容_最後に【E10323】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24576 AND contents_contents1so_name = '01_1_総論_2_M&amp;Aの流れ_3_中小M&amp;Aの課題【E10355】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24577 AND contents_contents2so_name = '02_2_M&amp;A手続の流れ【E10356】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents1so_name = '01_はじめに_1_弁護士の職務の特長-3_M&amp;Aに向かう経営者との出会い【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents2so_name = '02_4_M&amp;Aに向かう経営者の心理-6_弁護士がM&amp;Aを担当する意味【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24580 AND contents_contents2so_name = '02_第2_M&amp;A仲介業務委託契約書【E10357】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents1so_name = '01_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(1)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents2so_name = '02_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(2)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents3so_name = '03_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(3)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents4so_name = '04_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(4)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents5so_name = '05_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(5)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents6so_name = '06_英文契約書作成の実務_第4回_海外合弁、M&amp;A契約の留意点と実例(6)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents2so_name = '02_第2_M&amp;Aの成立のためのDD(1)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents3so_name = '03_第2_M&amp;Aの成立のためのDD(2)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents1so_name = '01_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(1)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents2so_name = '02_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(2)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents3so_name = '03_第1回_中小M&amp;Aにおける買いサイド支援の実務－総論(3)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25087 AND contents_contents1so_name = '01_1_M&amp;A仲介契約書の解説_2_秘密保持契約書の解説【E10407】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 13 AND contents_download_before1_1 = '第一部&nbsp;裁判員裁判大づかみ(全編).pdf'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 13 AND contents_download_before1_10 = '第二部&nbsp;裁判員のこころを掴む(全編).pdf'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 14206 AND teacher = '矢吹公敏（東京）、向宣明（第一東京）、宮川裕光（第二東京）、姜&#22989;（中国律師）'
) t;
-- (b) 新値になっている: 結果は44
SELECT COUNT(*) AS new_value_rows FROM (
SELECT product_id FROM tbl_product WHERE product_id = 19674 AND contents_contents3_name = '03_9.Q&A【E10109】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20156 AND contents_contents8_name = '08_第2_03 電子書籍に対応した 出版権の整備②（主なQ&A，参考イメージ）【lib273】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20297 AND contents_contents2_name = '02_Ⅰなぜ今、中小企業M&Aなのか？【E10130】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20571 AND contents_contents4_name = '04_M&Aの進め方と留意点1【E10142】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 20571 AND contents_contents5_name = '05_M&Aの進め方と留意点2_3【E10142】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24301 AND contents_contents3_name = '03第4_事業承継におけるM&Aの実情と新しい「中小M&Aガイドライン」内容_最後に【E10323】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24576 AND contents_contents1_name = '01_1_総論_2_M&Aの流れ_3_中小M&Aの課題【E10355】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24577 AND contents_contents2_name = '02_2_M&A手続の流れ【E10356】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents1_name = '01_はじめに_1_弁護士の職務の特長-3_M&Aに向かう経営者との出会い【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents2_name = '02_4_M&Aに向かう経営者の心理-6_弁護士がM&Aを担当する意味【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24580 AND contents_contents2_name = '02_第2_M&A仲介業務委託契約書【E10357】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents1_name = '01_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(1)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents2_name = '02_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(2)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents3_name = '03_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(3)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents4_name = '04_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(4)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents5_name = '05_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(5)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents6_name = '06_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(6)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents2_name = '02_第2_M&Aの成立のためのDD(1)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents3_name = '03_第2_M&Aの成立のためのDD(2)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents1_name = '01_第1回_中小M&Aにおける買いサイド支援の実務－総論(1)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents2_name = '02_第1回_中小M&Aにおける買いサイド支援の実務－総論(2)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents3_name = '03_第1回_中小M&Aにおける買いサイド支援の実務－総論(3)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25087 AND contents_contents1_name = '01_1_M&A仲介契約書の解説_2_秘密保持契約書の解説【E10407】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24301 AND contents_contents3so_name = '03第4_事業承継におけるM&Aの実情と新しい「中小M&Aガイドライン」内容_最後に【E10323】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24576 AND contents_contents1so_name = '01_1_総論_2_M&Aの流れ_3_中小M&Aの課題【E10355】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24577 AND contents_contents2so_name = '02_2_M&A手続の流れ【E10356】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents1so_name = '01_はじめに_1_弁護士の職務の特長-3_M&Aに向かう経営者との出会い【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24578 AND contents_contents2so_name = '02_4_M&Aに向かう経営者の心理-6_弁護士がM&Aを担当する意味【E10359】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24580 AND contents_contents2so_name = '02_第2_M&A仲介業務委託契約書【E10357】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents1so_name = '01_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(1)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents2so_name = '02_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(2)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents3so_name = '03_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(3)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents4so_name = '04_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(4)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents5so_name = '05_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(5)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 24740 AND contents_contents6so_name = '06_英文契約書作成の実務_第4回_海外合弁、M&A契約の留意点と実例(6)【E10217】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents2so_name = '02_第2_M&Aの成立のためのDD(1)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25082 AND contents_contents3so_name = '03_第2_M&Aの成立のためのDD(2)【E10406】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents1so_name = '01_第1回_中小M&Aにおける買いサイド支援の実務－総論(1)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents2so_name = '02_第1回_中小M&Aにおける買いサイド支援の実務－総論(2)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25086 AND contents_contents3so_name = '03_第1回_中小M&Aにおける買いサイド支援の実務－総論(3)【E10405】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 25087 AND contents_contents1so_name = '01_1_M&A仲介契約書の解説_2_秘密保持契約書の解説【E10407】'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 13 AND contents_download_before1_1 = '第一部 裁判員裁判大づかみ(全編).pdf'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 13 AND contents_download_before1_10 = '第二部 裁判員のこころを掴む(全編).pdf'
UNION ALL
SELECT product_id FROM tbl_product WHERE product_id = 14206 AND teacher = '矢吹公敏（東京）、向宣明（第一東京）、宮川裕光（第二東京）、姜姍（中国律師）'
) t;

-- ---------------------------------------------------------------------
-- 戻し方(COMMIT後に元へ戻す場合のみ。バックアップテーブルから該当列を書き戻す)
-- ---------------------------------------------------------------------
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_download_before1_1 = b.contents_download_before1_1, t.contents_download_before1_10 = b.contents_download_before1_10 WHERE t.product_id = 13;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.teacher = b.teacher WHERE t.product_id = 14206;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents3_name = b.contents_contents3_name WHERE t.product_id = 19674;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents8_name = b.contents_contents8_name WHERE t.product_id = 20156;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents2_name = b.contents_contents2_name WHERE t.product_id = 20297;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents4_name = b.contents_contents4_name, t.contents_contents5_name = b.contents_contents5_name WHERE t.product_id = 20571;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents3_name = b.contents_contents3_name, t.contents_contents3so_name = b.contents_contents3so_name WHERE t.product_id = 24301;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents1_name = b.contents_contents1_name, t.contents_contents1so_name = b.contents_contents1so_name WHERE t.product_id = 24576;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents2_name = b.contents_contents2_name, t.contents_contents2so_name = b.contents_contents2so_name WHERE t.product_id = 24577;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents1_name = b.contents_contents1_name, t.contents_contents2_name = b.contents_contents2_name, t.contents_contents1so_name = b.contents_contents1so_name, t.contents_contents2so_name = b.contents_contents2so_name WHERE t.product_id = 24578;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents2_name = b.contents_contents2_name, t.contents_contents2so_name = b.contents_contents2so_name WHERE t.product_id = 24580;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents1_name = b.contents_contents1_name, t.contents_contents2_name = b.contents_contents2_name, t.contents_contents3_name = b.contents_contents3_name, t.contents_contents4_name = b.contents_contents4_name, t.contents_contents5_name = b.contents_contents5_name, t.contents_contents6_name = b.contents_contents6_name, t.contents_contents1so_name = b.contents_contents1so_name, t.contents_contents2so_name = b.contents_contents2so_name, t.contents_contents3so_name = b.contents_contents3so_name, t.contents_contents4so_name = b.contents_contents4so_name, t.contents_contents5so_name = b.contents_contents5so_name, t.contents_contents6so_name = b.contents_contents6so_name WHERE t.product_id = 24740;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents2_name = b.contents_contents2_name, t.contents_contents3_name = b.contents_contents3_name, t.contents_contents2so_name = b.contents_contents2so_name, t.contents_contents3so_name = b.contents_contents3so_name WHERE t.product_id = 25082;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents1_name = b.contents_contents1_name, t.contents_contents2_name = b.contents_contents2_name, t.contents_contents3_name = b.contents_contents3_name, t.contents_contents1so_name = b.contents_contents1so_name, t.contents_contents2so_name = b.contents_contents2so_name, t.contents_contents3so_name = b.contents_contents3so_name WHERE t.product_id = 25086;
-- UPDATE tbl_product t JOIN tbl_product_bk_no39_20261006 b ON b.product_id = t.product_id SET t.contents_contents1_name = b.contents_contents1_name, t.contents_contents1so_name = b.contents_contents1so_name WHERE t.product_id = 25087;

-- 確認が済み、不要になったらバックアップテーブルを削除する:  DROP TABLE tbl_product_bk_no39_20261006;
