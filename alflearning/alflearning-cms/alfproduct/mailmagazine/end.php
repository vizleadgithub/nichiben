<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
//$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("メールマガジン");
$template->admin_comment("メールマガジンを管理します。");
//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('page_name', 'mailmagazine');
$template->admin_layout('mailmagazine/end.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
