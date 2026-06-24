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
$mid = "";
$member_type = "1";
$sex = "1";
$pref = "";
$age = "";
$job = "";
$job_type = "";
$school_grade = "";
$mailmagazine_category = array();

$bar_association_id = "";
$start_regist_date = "";
$start_regist_date = "";
$end_regist_date = "";
$start_lawyer_number = "";
$end_lawyer_number = "";
$post_all = "";

$submit_datetime = date("Y/m/d H:00");
//var_dump($submit_datetime);
$mail_title = "";
$mail_body = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$mid = $_POST["mid"];

	$post_all = $_POST["post_all"];
	if( $post_all == "" ){
		$member_type = $_POST["member_type"];
		$sex = $_POST["sex"];
		$pref = $_POST["pref"];
		$age = $_POST["age"];
		$job = $_POST["job"];
		$job_type = $_POST["job_type"];
		$school_grade = $_POST["school_grade"];
		$mailmagazine_category = $_POST["mailmagazine_category"];

		$bar_association_id = $_POST["bar_association_id"];
		$start_regist_date = $_POST["start_regist_date"];
		$end_regist_date = $_POST["end_regist_date"];
		$start_lawyer_number = $_POST["start_lawyer_number"];
		$end_lawyer_number = $_POST["end_lawyer_number"];
	}

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
		$sql.= "target_parameter_age,";
		$sql.= "target_parameter_job,";
		$sql.= "target_parameter_job_type,";
		$sql.= "target_parameter_school_grade,";

		$sql.= "target_parameter_bar_association_id,";
		$sql.= "target_parameter_start_regist_date,";
		$sql.= "target_parameter_end_regist_date,";
		$sql.= "target_parameter_start_lawyer_number,";
		$sql.= "target_parameter_end_lawyer_number,";
		$sql.= "target_parameter_post_all,";

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
		$age		 = $ret[0]["target_parameter_age"];
		$job		 = $ret[0]["target_parameter_job"];
		$job_type	 = $ret[0]["target_parameter_job_type"];
		$school_grade	 = $ret[0]["target_parameter_school_grade"];

		$bar_association_id	 = $ret[0]["target_parameter_bar_association_id"];
		$start_regist_date	 = $ret[0]["target_parameter_start_regist_date"];
		$end_regist_date	 = $ret[0]["target_parameter_end_regist_date"];
		$start_lawyer_number	 = $ret[0]["target_parameter_start_lawyer_number"];
		$end_lawyer_number	 = $ret[0]["target_parameter_end_lawyer_number"];
		$post_all		 = $ret[0]["target_parameter_post_all"];

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
$sql = "select * from mtb_age";
$arr_age = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_job";
$arr_job = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_job_type";
$arr_job_type = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_school_grade";
$arr_school_grade = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_bar_association";
$arr_bar_association = $objDbConnect->query_fetch_arr($sql);
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

$template->assign('arr_pref', $arr_pref);
$template->assign('arr_age', $arr_age);
$template->assign('arr_job', $arr_job);
$template->assign('arr_job_type', $arr_job_type);
$template->assign('arr_school_grade', $arr_school_grade);
$template->assign('arr_mailmagazine_category', $arr_mailmagazine_category);
$template->assign('arr_bar_association', $arr_bar_association);

$template->assign('mid', $mid);
$template->assign('member_type', $member_type);
$template->assign('sex', $sex);
$template->assign('pref', $pref);
$template->assign('age', $age);
$template->assign('job', $job);
$template->assign('job_type', $job_type);
$template->assign('school_grade', $school_grade);
$template->assign('mailmagazine_category', $mailmagazine_category);

$template->assign('post_all', $post_all);
$template->assign('bar_association_id', $bar_association_id);
$template->assign('start_regist_date', $start_regist_date);
$template->assign('end_regist_date', $end_regist_date);
$template->assign('start_lawyer_number', $start_lawyer_number);
$template->assign('end_lawyer_number', $end_lawyer_number);

$template->assign('submit_datetime', $submit_datetime);

$template->assign('mail_title', $mail_title);
$template->assign('mail_body', $mail_body);
$template->assign('arr_list', $ret);

$template->assign('next_url', 'conf.php');

$template->assign('page_name', 'mailmagazine');
$template->admin_layout('mailmagazine/form.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
