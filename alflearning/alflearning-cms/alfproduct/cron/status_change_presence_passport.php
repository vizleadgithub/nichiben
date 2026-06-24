#!/usr/bin/php
<?php
/**
 * 講座管理・倫理代替措置研修で変更したステータスをtbl_ethic_question_historyに反映させる。
 * ※1日1回、任意のタイミングで動作するようcron設定してください。
 */
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'status_change_presence_passport Start'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_presence_passport.log');
mb_language("japanese");
mb_internal_encoding("UTF-8");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql=" 
SELECT 
 student_id, 
 lawyer_number, 
 exp_date_passport 
FROM 
 student 
WHERE 
 exp_date_passport < '".date("Y-m-d 00:00:00")."' 
 AND presence_passport='1' 
 ";
$arr_student = $objDbConnect->query_fetch_arr($sql);
if(!$arr_student){
	@error_log(date("Y-m-d H:i:s").':'.'パスポート期限切れユーザの取得に失敗しました。['.$sql.']'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_presence_passport.log');
} else {
	@error_log(date("Y-m-d H:i:s").':'.'パスポート期限切れユーザ[student_id:student_id:exp_date_passport]'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_presence_passport.log');
	for($i=0;$i<count($arr_student);$i++){
		@error_log(date("Y-m-d H:i:s").':'.'['.$arr_student[$i]["student_id"].':'.$arr_student[$i]["lawyer_number"].':'.$arr_student[$i]["exp_date_passport"].']'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_presence_passport.log');
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql=" 
UPDATE 
 student 
SET 
 presence_passport='0' 
WHERE 
 exp_date_passport < '".date("Y-m-d 00:00:00")."' 
 ";
$res = $objDbConnect->execute($sql);
if (!$res){
	@error_log(date("Y-m-d H:i:s").':'.'パスポート期限切れユーザの更新に失敗しました。['.$sql.']'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_presence_passport.log');
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'status_change_presence_passport End'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_presence_passport.log');
exit();
