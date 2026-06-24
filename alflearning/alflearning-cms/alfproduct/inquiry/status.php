<?php
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$iid = "";
$inquiry_status = "3";
$return_name = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$iid = $_POST["iid"];
	$inquiry_status = $_POST["inquiry_status"];
	$return_name = $_POST["return_name"];
} else {
	$iid = trim($_GET["iid"]);
}
if($iid!=""){
	$sql = "";
	$sql.= "select ";
	$sql.= "inquiry_id,";
	$sql.= "inquiry_name,";
	$sql.= "inquiry_mail,";
	$sql.= "inquiry_comment,";
	$sql.= "inquiry_status,";
	$sql.= "DATE_FORMAT(regist_date,'%Y/%m/%d %k:%i')as regist_date, ";
	$sql.= "DATE_FORMAT(update_date,'%Y/%m/%d %k:%i')as update_date, ";
	$sql.= "return_name, ";
	$sql.= "return_title, ";
	$sql.= "return_comment, ";
	$sql.= "del_flg ";
	$sql.= "from ";
	$sql.= "tbl_inquiry ";
	$sql.= "where ";
	$sql.= "inquiry_id='".$iid."'";
	$ret = $objDbConnect->query_fetch_arr($sql);
	$arr_data	 = $ret[0];
	if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	} else {
		if($arr_data["inquiry_status"]!="0"){
			$inquiry_status = $arr_data["inquiry_status"];
		}
		$return_name = $arr_data["return_name"];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("お問い合わせ");
$template->admin_comment("お問い合わせを管理します。");
//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('iid', $iid);
$template->assign('arr_data', $arr_data);

$template->assign('inquiry_status', $inquiry_status);
$template->assign('return_name', $return_name);

$template->assign('next_url', 'status_conf.php');

$template->assign('page_name', 'inquiry');
$template->admin_layout('inquiry/status.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
