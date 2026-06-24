<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/bookmark_module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$student_id = "0";
$movie_id = "0";
$product_id = "0";
if (isset($_GET['student_id']) && isset($_GET['movie_id'])){
	$student_id = $_GET['student_id'];
	$movie_id = $_GET['movie_id'];
	
	if(!cmCheckInput($student_id, 'CK_NUM') && !cmCheckInput($movie_id, 'CK_NUM')){
		$sql = "DELETE FROM tbl_bookmark WHERE student_id='".mysqli_escape_string($objDbConnect->connect,  $student_id )."' AND video_id='".mysqli_escape_string($objDbConnect->connect,  $movie_id )."'";
		$objDbConnect->execute($sql);
	}
}
if ( isset($_GET['pid']) ){
	$product_id = $_GET['pid'];

	$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id='".mysqli_escape_string($objDbConnect->connect,  $student_id )."' AND video_id='".mysqli_escape_string($objDbConnect->connect,  $movie_id )."' AND complete_flag='1' ";
	$res = $objDbConnect->query_fetch_arr($sql);
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
}
$objDbConnect->close();
exit;
?>