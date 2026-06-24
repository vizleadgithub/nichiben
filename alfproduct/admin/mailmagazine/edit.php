<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mid = "";
$member_type = "1";
$sex = "1";
$pref = "";
$mailmagazine_category = array();
$submit_datetime = date('Y/m/d H:00');
$mail_title = "";
$mail_body = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$mid = $_POST["mid"];
	$member_type = $_POST["member_type"];
	$sex = $_POST["sex"];
	$pref = $_POST["pref"];
	$mailmagazine_category = $_POST["mailmagazine_category"];
	$submit_datetime = $_POST["submit_datetime"];
	$mail_title = $_POST["mail_title"];
	$mail_body = $_POST["mail_body"];
} else {
	$mid = trim($_GET["mid"]);
	if($mid!=""){
		$sql = "";
		$sql.= "select ";
		$sql.= "mailmagazine_id,";
		$sql.= "target_parameter,";
		$sql.= "target_parameter_member_type,";
		$sql.= "target_parameter_sex,";
		$sql.= "target_parameter_pref,";
		$sql.= "target_parameter_mailmagazine_category,";
		$sql.= "DATE_FORMAT(submit_datetime,'%Y/%m/%d %k:%i')as submit_datetime,";
		$sql.= "mail_title,";
		$sql.= "mail_body ";
		$sql.= "from ";
		$sql.= "tbl_mailmagazine ";
		$sql.= "where ";
		$sql.= "mailmagazine_id='".$mid."'";
		$ret = $objDbConnect->query_fetch_arr($sql);
		//$temp_arr = unserialize( $ret[0]["target_parameter"] );
		$member_type	 = $ret[0]["target_parameter_member_type"];
		$sex		 = $ret[0]["target_parameter_sex"];
		$pref		 = $ret[0]["target_parameter_pref"];
		$mailmagazine_category = explode(",",$ret[0]["target_parameter_mailmagazine_category"]);
		$submit_datetime = $ret[0]["submit_datetime"];
		$mail_title	 = $ret[0]["mail_title"];
		$mail_body	 = $ret[0]["mail_body"];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_mailmagazine_category";
$arr_mailmagazine_category = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_pref";
$arr_pref = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("メールマガジン");
$template->admin_comment("メールマガジンを管理します。");
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('arr_pref', $arr_pref);
$template->assign('arr_mailmagazine_category', $arr_mailmagazine_category);

$template->assign('mid', $mid);
$template->assign('member_type', $member_type);
$template->assign('sex', $sex);
$template->assign('pref', $pref);
$template->assign('mailmagazine_category', $mailmagazine_category);

$template->assign('submit_datetime', $submit_datetime);

$template->assign('mail_title', $mail_title);
$template->assign('mail_body', $mail_body);
$template->assign('arr_list', $ret);

$template->assign('next_url', 'conf.php');

$template->assign('page_name', 'mailmagazine');
$template->admin_layout('mailmagazine/form.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
