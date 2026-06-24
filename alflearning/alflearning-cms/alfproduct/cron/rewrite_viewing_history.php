#!/usr/bin/php
<?php
/**
 * 視聴完了情報の書き換えをする（本バッチによりデータの整合性を取る）
 * ※alfstreamからの視聴履歴取得バッチが完了した後に動作するようcron設定してください。
 * （bat_get_alfstream_reading_historyの動作時刻から完了時刻を予測し、余裕を持って設定すること）
 */
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新開始'."\n", 3, '/alflearning-data/alfproduct/logs/rewrite_viewing_history.log');
mb_language("japanese");
mb_internal_encoding("UTF-8");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$exe_date = date('Y-m-d H:i:s');

// 視聴済み時間の書き換え
$arr = array();
$sql = "
SELECT
  student_id,
  video_id,
  duration
FROM
  report_user_video_viewed
WHERE
  percent = 100
  AND complete_flag = 1
  AND (duration_reading = '00:00:00' OR duration_reading IS NULL)
";
$arr = $objDbConnect->query_fetch_arr($sql);
if ($arr){
	foreach ($arr as $val){
		$sql = "UPDATE report_user_video_viewed SET duration_reading = '".$val['duration']."', update_at = '".$exe_date."' WHERE student_id = '".$val['student_id']."' AND video_id = '".$val['video_id']."'";
		$objDbConnect->execute($sql);
	}
}

// 完了日時の書き換え
$arr = array();
$sql = "
SELECT
  student_id,
  video_id
FROM
  report_user_video_viewed
WHERE
  complete_flag = 1
  AND (complete_date = '0000-00-00 00:00:00' OR complete_date IS NULL)
";
$arr = $objDbConnect->query_fetch_arr($sql);
if ($arr){
	foreach ($arr as $val){
		$sql = "UPDATE report_user_video_viewed SET complete_date = '".$exe_date."', update_at = '".$exe_date."' WHERE student_id = '".$val['student_id']."' AND video_id = '".$val['video_id']."'";
		$objDbConnect->execute($sql);
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新終了'."\n", 3, '/alflearning-data/alfproduct/logs/rewrite_viewing_history.log');
exit();
?>
