#!/usr/bin/php
<?php
/**
 * 講座管理・倫理代替措置研修で変更したステータスをtbl_ethic_question_historyに反映させる。
 * ※1日1回、任意のタイミングで動作するようcron設定してください。
 */
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新開始'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_ethic_question_history.log');
mb_language("japanese");
mb_internal_encoding("UTF-8");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// -----------------
// ステータスの更新
// -----------------
// 変更対象データの取得
$sql = "SELECT * FROM  tbl_status_change_ethic";
$res = $objDbConnect->query_fetch_arr($sql);
if ($res){
	// 倫理研修問題の進捗ステータス更新
	foreach ($res as $val){
		$sql = "UPDATE tbl_ethic_question_history SET status = '".mysql_escape_string($val['status'])."' WHERE student_id = '".mysql_escape_string($val['student_id'])."' AND product_id = '".mysql_escape_string($val['product_id'])."'";
		$objDbConnect->execute($sql);
	}
	
	$sql = "TRUNCATE TABLE tbl_status_change_ethic";
	$objDbConnect->execute($sql);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// -----------------------------------------------------------------------
// 倫理研修受講許可のユーザーで、受講ログが存在しない場合、初回登録をする
// -----------------------------------------------------------------------
// 現在の倫理研修問題の商品IDを取得
$sql = "SELECT product_id FROM tbl_product_ethic_training WHERE publish_flg = 1 ORDER BY product_id DESC LIMIT 1";
$arr_tbl_product_ethic_training = $objDbConnect->query_fetch_arr($sql);
if ($arr_tbl_product_ethic_training){
	$product_id = $arr_tbl_product_ethic_training[0]['product_id'];
	
	// 倫理研修受講対象者のID取得
	$sql = "SELECT student_id FROM student WHERE sub_auth_ethic_training = 1";
	$arr_auth_ethic_student_id = $objDbConnect->query_fetch_arr($sql);
	if ($arr_auth_ethic_student_id){
		if (!empty($arr_tbl_product_ethic_training) && !empty($arr_auth_ethic_student_id)){
			foreach ($arr_auth_ethic_student_id as $auth_ethic_student_id){
				$auth_ethic_student_id = $auth_ethic_student_id['student_id'];
				
				// 倫理研修問題受講ログが登録されているかチェック
				$sql = "SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE student_id = '$auth_ethic_student_id' AND product_id = '$product_id'";
				$res = $objDbConnect->query_fetch_arr($sql);
				if ($res){
					// ログ未登録なら新規登録する
					if ($res[0]['c']==0){
						$sql = "INSERT INTO tbl_ethic_question_history (student_id, product_id, create_date) VALUES ('$auth_ethic_student_id', '$product_id', '".date('Y-m-d H:i:s')."')";
						$objDbConnect->execute($sql);
					}
				}
			}
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新終了'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_ethic_question_history.log');
exit();
?>
