<?php
$_SERVER['HTTPS'] = 'on';
//ini_set('display_errors', 1);
//error_reporting(E_ALL & ~E_WARNING);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/bookmark_module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//ini_set('display_errors', 1);
//echo("[".$_GET['student_id']."]");
//echo("[".$_GET['movie_id']."]");
//echo("[".$_GET['time']."]");
//echo("[".$_GET['time2']."]");
//print("[test1]");
//var_dump($_GET);
if (isset($_GET['student_id']) && isset($_GET['movie_id']) && isset($_GET['time'])){
//print("[test2]");
	$student_id = $_GET['student_id'];
	$movie_id = $_GET['movie_id'];
	$time  = floor($_GET['time']);
	$time2 = intval($_GET['time2']);
	$product_id = $_GET['pid'];
//print("[".$student_id."]");
//print("[".$movie_id."]");
//print("[".$time."]");
//print("[".$time2."]");

	if(!cmCheckInput($student_id, 'CK_NUM') && !cmCheckInput($movie_id, 'CK_NUM')){
		// ブックマークの更新
		$sql = "REPLACE INTO";
		$sql.= " tbl_bookmark";
		$sql.= "  (";
		$sql.= "   student_id,";
		$sql.= "   video_id,";
		$sql.= "   bookmark_time";
		$sql.= "  )";
		$sql.= " VALUES";
		$sql.= "  (";
		$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $student_id )."',";
		$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."',";
		$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  gmdate('H:i:s', $time) )."'";
		$sql.= "  )";
		$ret = $objDbConnect->execute($sql);
//echo("[".$sql."]");
//var_dump($ret);
		

		$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".mysqli_escape_string($objDbConnect->connect,  $student_id )."' AND video_id = '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."' ";
//var_dump($ret);
//echo("[".$sql."]");
		$res = $objDbConnect->query_fetch($sql);
//var_dump($res);
		if ($res['c'] == 0){
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
			$sql.= "   regist_at,";
			$sql.= "   update_at";
			$sql.= "  )";
			$sql.= " VALUES";
			$sql.= "  (";
			$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $student_id )."',";
			$sql.= "   '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."',";
			$sql.= "   (SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".mysqli_escape_string($objDbConnect->connect,  $movie_id )."'),";
			$sql.= "   '00:00:00',";
			$sql.= "   '".date("Y-m-d H:i:s")."',";
			$sql.= "   '0',";
			$sql.= "   '0',";
			$sql.= "   '".date("Y-m-d H:i:s")."',";
			$sql.= "   '0000-00-00 00:00:00'";
			$sql.= "  )";
			$objDbConnect->execute($sql);
		}

		// 再生完了時間になった場合は、視聴履歴の完了フラグ更新
		if ($time >= ($time2 - 10) ){
			$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id='".mysqli_escape_string($objDbConnect->connect,  $student_id )."' AND video_id='".mysqli_escape_string($objDbConnect->connect,  $movie_id )."' AND complete_flag='1' ";
			$res = $objDbConnect->query_fetch($sql);
			if ($res['c'] <= 0){
				$sql = "UPDATE ";
				$sql.= "   report_user_video_viewed ";
				$sql.= " SET";
				$sql.= "   duration=(SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".mysqli_escape_string($objDbConnect->connect,  $movie_id )."'), ";
				$sql.= "   duration_reading=(SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".mysqli_escape_string($objDbConnect->connect,  $movie_id )."'), ";
				$sql.= "   percent = '100', ";
				$sql.= "   complete_flag = '1', ";
				$sql.= "   complete_date = '".date('Y-m-d H:i:s')."' ";
				$sql.= " WHERE ";
				$sql.= "   student_id = '".mysqli_escape_string($objDbConnect->connect,  $student_id )."' ";
				$sql.= "   AND video_id = '".mysqli_escape_string($objDbConnect->connect,  $movie_id )."' ";
				$objDbConnect->execute($sql);
			}
			//-----------------------------------------------------------
			if( !cmCheckInput($product_id, 'CK_NUM') ){
				$sql = "";
				$sql.= "SELECT ";
				$sql.= " contents_contents1, ";
				$sql.= " contents_contents2, ";
				$sql.= " contents_contents3, ";
				$sql.= " contents_contents4, ";
				$sql.= " contents_contents5, ";
				$sql.= " contents_contents6, ";
				$sql.= " contents_contents7, ";
				$sql.= " contents_contents8, ";
				$sql.= " contents_contents9, ";
				$sql.= " contents_contents10, ";
				$sql.= " contents_contents11, ";
				$sql.= " contents_contents12, ";
				$sql.= " contents_contents13, ";
				$sql.= " contents_contents14, ";
				$sql.= " contents_contents15, ";
				$sql.= " contents_contents16, ";
				$sql.= " contents_contents17, ";
				$sql.= " contents_contents18, ";
				$sql.= " contents_contents19, ";
				$sql.= " contents_contents20, ";
				$sql.= " contents_contents21, ";
				$sql.= " contents_contents22, ";
				$sql.= " contents_contents23, ";
				$sql.= " contents_contents24, ";
				$sql.= " contents_contents25 ";
				$sql.= "FROM ";
				$sql.= " tbl_product ";
				$sql.= "WHERE ";
				$sql.= " product_id='".mysqli_escape_string($objDbConnect->connect,  $product_id )."' ";

				$res = $objDbConnect->query_fetch_arr($sql);
				$video_count = 0;
				$arr_video_id = array();
				if( trim($res[0]["contents_contents1"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents1"]); }
				if( trim($res[0]["contents_contents2"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents2"]); }
				if( trim($res[0]["contents_contents3"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents3"]); }
				if( trim($res[0]["contents_contents4"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents4"]); }
				if( trim($res[0]["contents_contents5"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents5"]); }
				if( trim($res[0]["contents_contents6"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents6"]); }
				if( trim($res[0]["contents_contents7"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents7"]); }
				if( trim($res[0]["contents_contents8"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents8"]); }
				if( trim($res[0]["contents_contents9"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents9"]); }
				if( trim($res[0]["contents_contents10"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents10"]); }
				if( trim($res[0]["contents_contents11"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents11"]); }
				if( trim($res[0]["contents_contents12"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents12"]); }
				if( trim($res[0]["contents_contents13"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents13"]); }
				if( trim($res[0]["contents_contents14"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents14"]); }
				if( trim($res[0]["contents_contents15"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents15"]); }
				if( trim($res[0]["contents_contents16"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents16"]); }
				if( trim($res[0]["contents_contents17"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents17"]); }
				if( trim($res[0]["contents_contents18"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents18"]); }
				if( trim($res[0]["contents_contents19"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents19"]); }
				if( trim($res[0]["contents_contents20"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents20"]); }
				if( trim($res[0]["contents_contents21"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents21"]); }
				if( trim($res[0]["contents_contents22"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents22"]); }
				if( trim($res[0]["contents_contents23"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents23"]); }
				if( trim($res[0]["contents_contents24"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents24"]); }
				if( trim($res[0]["contents_contents25"])!="" ){ $video_count += 1; $arr_video_id[]=trim($res[0]["contents_contents25"]); }
				$sql = "";
				$sql.= "SELECT ";
				$sql.= " * ";
				$sql.= "FROM ";
				$sql.= " report_user_video_viewed ";
				$sql.= "WHERE ";
				$sql.= " complete_flag='1' ";
				$sql.= " and student_id='".mysqli_escape_string($objDbConnect->connect,  $student_id )."' ";
				$sql.= " and video_id IN ( ".trim(implode(",",$arr_video_id),",")." ) ";
				$res = $objDbConnect->query_fetch_arr($sql);
				if( count($res)==$video_count ){
					$sql = "";
					$sql.= "UPDATE ";
					$sql.= " tbl_order_detail ";
					$sql.= "SET ";
					$sql.= " video_complete_flg='1' ";
					$sql.= "WHERE ";
					$sql.= " member_id='".mysqli_escape_string($objDbConnect->connect,  $student_id )."' ";
					$sql.= " AND product_id='".mysqli_escape_string($objDbConnect->connect,  $product_id )."' ";
					$objDbConnect->execute($sql);
				}
			}
			//-----------------------------------------------------------
		}
	}
}
$objDbConnect->close();
print("0");
exit;
?>