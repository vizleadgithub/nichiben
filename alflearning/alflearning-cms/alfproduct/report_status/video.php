<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	echo 'error.1';
	exit();
}

if (!isset($_GET['pid'])){
	$objDbConnect->close();
	echo 'error.2';
	exit();
}
$pid = $_GET['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	echo 'error.3';
	exit();
}

if (!isset($_GET['sid'])){
	$objDbConnect->close();
	echo 'error.4';
	exit();
}
$sid = $_GET['sid'];
if(cmCheckInput($sid, 'CK_NUM')){
	$objDbConnect->close();
	echo 'error.5';
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_list = array();

$sql = "SELECT ";
for($i=1; $i<=MAX_CONTENTS; $i++){
	$sql.= " contents_contents$i, ";
}
$sql.= "  product_name ";
$sql.= " FROM ";
$sql.= "  tbl_product ";
$sql.= " WHERE ";
$sql.= "  product_id='$pid' ";
$ret_tbl_product = $objDbConnect->query_fetch($sql);
if($ret_tbl_product){
	for($i=1; $i<=MAX_CONTENTS; $i++){
		// 動画ID
		$arr_list[$i]['video_id'] = $ret_tbl_product["contents_contents$i"];
		// 動画名
		$arr_list[$i]['video_name'] = '';
		// 視聴状況
		$arr_list[$i]['video_percent'] = 0;
		
		if($arr_list[$i]['video_id']!=''){
			$sql = "SELECT video_logic_name FROM video WHERE video_id='".$arr_list[$i]['video_id']."'";
			$ret_video = $objDbConnect->query_fetch($sql);
			if($ret_video){
				$arr_list[$i]['video_name'] = $ret_video['video_logic_name'];
			}
			
			$sql = "SELECT percent, complete_flag FROM report_user_video_viewed WHERE student_id='".$sid."' AND video_id='".$arr_list[$i]['video_id']."'";
			$ret_report_user_video_viewed = $objDbConnect->query_fetch($sql);
			if($ret_report_user_video_viewed){
				if($ret_report_user_video_viewed['complete_flag']=='1'){
					$arr_list[$i]['video_percent'] = 100;
				} else {
					$arr_list[$i]['video_percent'] = $ret_report_user_video_viewed['percent'];
				}
			}
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('arr_list', $arr_list);

$template->admin_layout_non('report_status/video.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
