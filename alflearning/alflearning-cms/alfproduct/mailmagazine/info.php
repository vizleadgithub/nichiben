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

$submit_datetime = date('Y/m/d H:00');
$mail_title = "";
$mail_body = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$mid = intval($_POST["mid"]);
	$post_all = strip_tags($_POST["post_all"]);
	if( $post_all == "" ){
		$member_type = strip_tags($_POST["member_type"]);
		$sex = strip_tags($_POST["sex"]);
		$pref = strip_tags($_POST["pref"]);
		$age = strip_tags($_POST["age"]);
		$job = strip_tags($_POST["job"]);
		$job_type = strip_tags($_POST["job_type"]);
		$school_grade = strip_tags($_POST["school_grade"]);
		$mailmagazine_category = strip_tags($_POST["mailmagazine_category"]);

		$bar_association_id = strip_tags($_POST["bar_association_id"]);
		$start_regist_date = strip_tags($_POST["start_regist_date"]);
		$end_regist_date = strip_tags($_POST["end_regist_date"]);
		$start_lawyer_number = strip_tags($_POST["start_lawyer_number"]);
		$end_lawyer_number = strip_tags($_POST["end_lawyer_number"]);
	}
	$submit_datetime = strip_tags($_POST["submit_datetime"]);
	$mail_title = strip_tags($_POST["mail_title"]);
	$mail_body = strip_tags($_POST["mail_body"]);
} else {
	$mid = $_GET["mid"];

	//$sql = "select mailmagazine_id,target_parameter,DATE_FORMAT(submit_datetime,'%Y/%m/%d %k:%i')as submit_datetime, submit_status, mail_title,mail_body from tbl_mailmagazine where mailmagazine_id='".$mid."'";
	$sql = "";
	$sql.= "select ";
	$sql.= "mailmagazine_id,";
	$sql.= "target_parameter,";
	$sql.= "target_parameter_member_type,";
	$sql.= "target_parameter_sex,";
	$sql.= "target_parameter_pref,";
	$sql.= "target_parameter_age,";
	$sql.= "target_parameter_job,";
	$sql.= "target_parameter_job_type,";
	$sql.= "target_parameter_school_grade,";

	$sql.= "target_parameter_start_regist_date,";
	$sql.= "target_parameter_end_regist_date,";
	$sql.= "target_parameter_bar_association_id,";
	$sql.= "target_parameter_start_lawyer_number,";
	$sql.= "target_parameter_end_lawyer_number,";
	$sql.= "target_parameter_post_all,";

	$sql.= "target_parameter_mailmagazine_category,";
	$sql.= "DATE_FORMAT(submit_datetime,'%Y/%m/%d %k:%i')as submit_datetime,";
	$sql.= "submit_status,";
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
	$mailmagazine_category = explode(",",$ret[0]["target_parameter_mailmagazine_category"]);

	$bar_association_id	 = $ret[0]["target_parameter_bar_association_id"];
	if($ret[0]["target_parameter_start_regist_date"] != "0000-00-00"){
		$start_regist_date	 = $ret[0]["target_parameter_start_regist_date"];
	}
	if($ret[0]["target_parameter_end_regist_date"] != "0000-00-00"){
		$end_regist_date	 = $ret[0]["target_parameter_end_regist_date"];
	}
	$start_lawyer_number	 = $ret[0]["target_parameter_start_lawyer_number"];
	$end_lawyer_number	 = $ret[0]["target_parameter_end_lawyer_number"];
	$post_all		 = $ret[0]["target_parameter_post_all"];

	$submit_datetime = $ret[0]["submit_datetime"];
	$submit_status	 = $ret[0]["submit_status"];
	$mail_title	 = $ret[0]["mail_title"];
	$mail_body	 = $ret[0]["mail_body"];
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
$member_count = 0;
if(count($arr_err)==0){
	$sql = "select count(student.student_id) as c from student where student.status=0 ";
	if( $arr_session["cms_master.login.school_id"]>0 && trim($arr_session["cms_master.login.school_id"])!="" ){
		$sql.= " and school_id=".$arr_session["cms_master.login.school_id"]." ";
	}

	$where = "";
	if( $post_all == "" || $post_all == "0" ){
/*
		//会員種別の条件
		if( $member_type=="2" ){
			$where.= " and student.member_type='1' ";
		} elseif( $member_type=="3" ){
			$where.= " and student.member_type='2' ";
		} else {
		}
		//性別の条件
		if( $sex=="2" ){
			$where.= " and student.sex='1' ";
		} elseif( $sex=="3" ){
			$where.= " and student.sex='2' ";
		} else {
		}
		//都道府県の条件
		if( $pref=="" || $pref=="0" ){
		} else {
			$where.= " and student.pref='".$pref."' ";
		}
		if( $age == "" || $age=="0" ){
		} else {
			$where.= " and student.age='".$age."' ";
		}
		if( $job == "" || $job=="0" ){
		} else {
			$where.= " and student.job='".$job."' ";
		}
		if( $job_type == "" || $job_type=="0" ){
		} else {
			$where.= " and student.job_type='".$job_type."' ";
		}
		if( $school_grade == "" || $school_grade=="0" ){
		} else {
			$where.= " and student.school_grade='".$school_grade."' ";
		}

		//カテゴリの条件
		if( 0<count($mailmagazine_category) ){
			$where.= " and (";
			for($i=0;$i<count($mailmagazine_category);$i++){
				if($i>0){ $where.= " or "; }
				$where.= " ( ";
					$where.= " concat(',',student.mailmagazine_ids,',') LIKE '%,".$mailmagazine_category[$i].",%' ";
				$where.= " ) ";
			}
			$where.= " ) ";
		}
*/
		if( $bar_association_id == "" ){
		} else {
			$where.= " and student.bar_association_id='".$bar_association_id."' ";
		}
		if( $start_regist_date != "" ){
			$where.= " and student.regist_date>='".substr($start_regist_date,0,10)."' ";
		}
		if( $end_regist_date != "" ){
			$where.= " and student.regist_date<='".substr($end_regist_date,0,10)."' ";
		}

		if( $start_lawyer_number != "" ){
			$where.= " and student.lawyer_number>='".$start_lawyer_number."' ";
		}
		if( $end_lawyer_number != "" ){
			$where.= " and student.lawyer_number<='".$end_lawyer_number."' ";
		}
	}
	$ret = $objDbConnect->query_fetch($sql.$where);
	$member_count = $ret["c"];
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

$template->assign('arr_pref', $arr_pref);
$template->assign('arr_age', $arr_age);
$template->assign('arr_job', $arr_job);
$template->assign('arr_job_type', $arr_job_type);
$template->assign('arr_school_grade', $arr_school_grade);
$template->assign('arr_mailmagazine_category', $arr_mailmagazine_category);
$template->assign('arr_bar_association', $arr_bar_association);

$template->assign('member_count', $member_count);
$template->assign('page', $_SESSION["mailmagazine.page"]);

$template->assign('mid', $mid);
$template->assign('post_all', $post_all);

$template->assign('member_type', $member_type);
$template->assign('sex', $sex);
$template->assign('pref', $pref);
$template->assign('age', $age);
$template->assign('job', $job);
$template->assign('job_type', $job_type);
$template->assign('school_grade', $school_grade);

$template->assign('mailmagazine_category', $mailmagazine_category);

$template->assign('bar_association_id', $bar_association_id);
$template->assign('start_regist_date', $start_regist_date);
$template->assign('end_regist_date', $end_regist_date);
$template->assign('start_lawyer_number', $start_lawyer_number);
$template->assign('end_lawyer_number', $end_lawyer_number);

$template->assign('submit_datetime', $submit_datetime);
$template->assign('submit_status', $submit_status);
$template->assign('mail_title', $mail_title);
$template->assign('mail_body', $mail_body);
$template->assign('arr_list', $ret);

$template->assign('next_url', 'edit.php');

$template->assign('page_name', 'mailmagazine');
$template->admin_layout('mailmagazine/info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
