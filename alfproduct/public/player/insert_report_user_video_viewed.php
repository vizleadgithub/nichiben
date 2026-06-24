<?php
//ini_set('display_errors', 1);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/bookmark_module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (isset($_GET['student_id']) && isset($_GET['movie_id'])){
	$student_id = $_GET['student_id'];
	$movie_id = $_GET['movie_id'];
	
	if(!cmCheckInput($student_id, 'CK_NUM') && !cmCheckInput($movie_id, 'CK_NUM')){
		// 履歴登録チェック
		$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".mysqli_escape_string($objDbConnect->connect,  $student_id )."' AND video_id = '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."'";
		$res = $objDbConnect->query_fetch($sql);
		if ($res['c'] <= 0){
			// 視聴履歴の初回登録
			$sql = "SELECT alfstream_duration FROM video_alfstream_status WHERE video_id = '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."'";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				$sql = "INSERT INTO";
				$sql.= " report_user_video_viewed";
				$sql.= "  (";
				$sql.= "   student_id,";
				$sql.= "   video_id,";
				$sql.= "   duration,";
				$sql.= "   duration_reading,";
				$sql.= "   reading_date,";
				$sql.= "   percent,";
				$sql.= "   complete_flag,";
				$sql.= "   regist_at";
				$sql.= "  )";
				$sql.= " VALUES";
				$sql.= "  (";
				$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $student_id )."',";
				$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."',";
				$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $res["alfstream_duration"] )."',";
				$sql.= "   '00:00:00',";
				$sql.= "   '".date('Y-m-d H:i:s')."',";
				$sql.= "   '1',";
				$sql.= "   '0',";
				$sql.= "   '".date('Y-m-d H:i:s')."'";
				$sql.= "  )";
				$objDbConnect->execute($sql);
			}
		}
	}
}
$objDbConnect->close();
print("0");
exit;
?>