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
$return_name = "";
$return_title = "";
$return_comment = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$iid = $_POST["iid"];
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
		$sql.= "return_title, ";
		$sql.= "return_comment, ";
		$sql.= "del_flg ";
		$sql.= "from ";
		$sql.= "tbl_inquiry ";
		$sql.= "where ";
		$sql.= "inquiry_id='".$iid."'";
		$ret = $objDbConnect->query_fetch_arr($sql);
		$arr_data	 = $ret[0];
	}
	$return_name = $_POST["return_name"];
	$return_title = $_POST["return_title"];
	$return_comment = $_POST["return_comment"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_err = array();
if (cmCheckInput($return_name, "CK_KARA")!=0){
	$arr_err["return_name"] = "対応者 は必須です。";
}
if (cmCheckInput($return_title, "CK_KARA")!=0){
	$arr_err["return_title"] = "件名 は必須です。";
}
if (cmCheckInput($return_comment, "CK_KARA")!=0){
	$arr_err["return_comment"] = "本文 は必須です。";
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

$template->assign('return_name', $return_name);
$template->assign('return_title', $return_title);
$template->assign('return_comment', $return_comment);

$template->assign('arr_err', $arr_err);

if(count($arr_err)==0){
	$template->assign('next_url', 'regist.php');
	$template->assign('prev_url', 'edit.php');

	$template->assign('page_name', 'inquiry');
	$template->admin_layout('inquiry/conf.tpl');
} else {
	$template->assign('next_url', 'conf.php');
	$template->assign('prev_url', '');

	$template->assign('page_name', 'inquiry');
	$template->admin_layout('inquiry/form.tpl');
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
