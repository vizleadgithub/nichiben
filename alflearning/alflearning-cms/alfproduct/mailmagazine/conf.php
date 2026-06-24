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
$member_type = "";
$sex = "";
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

$submit_datetime = "";
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
$arr_err = array();
//member_type
/*
if (cmCheckInput($member_type, "CK_KARA")!=0){
	$arr_err["member_type"] = "配信対象 は必須です。";
} else {
	if (cmCheckInput($member_type, "CK_NUM")!=0){
		$arr_err["member_type"] = "配信対象 が不正です。";
	}
}
//sex
if (cmCheckInput($sex, "CK_KARA")!=0){
	$arr_err["sex"] = "性別 は必須です。";
} else {
	if (cmCheckInput($sex, "CK_NUM")!=0){
		$arr_err["sex"] = "性別 が不正です。";
	}
}
//pref
if (cmCheckInput($pref, "CK_KARA")!=0){
} else {
	if (cmCheckInput($pref, "CK_NUM")!=0){
		$arr_err["pref"] = "都道府県 が不正です。";
	}
}
*/
//submit_datetime
if (cmCheckInput($submit_datetime, "CK_KARA")!=0){
	$arr_err["submit_datetime"] = "配信日時 は必須です。";
} else {
	if ( !strptime( $submit_datetime, '%Y/%m/%d %H:%M' )){
		$arr_err["submit_datetime"] = "配信日時 が不正です。";
	}
}
//mail_title
if (cmCheckInput($mail_title, "CK_KARA")!=0){
	$arr_err["mail_title"] = "件名 は必須です。";
}
//mail_title
if (cmCheckInput($mail_body, "CK_KARA")!=0){
	$arr_err["mail_body"] = "本文 は必須です。";
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$member_count = 0;
if(count($arr_err)==0){
	$sql = "select count(student.student_id) as c from student where student.status=0 and mailmagazine_flg=1 ";
	if( $arr_session["cms_master.login.school_id"]>0 && trim($arr_session["cms_master.login.school_id"])!="" ){
		$sql.= " and school_id=".$arr_session["cms_master.login.school_id"]." ";
	}
	$where = "";

	if( $post_all == "" ){
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
		if( $pref=="" ){
		} else {
			$where.= " and student.pref='".$pref."' ";
		}

		if( $age == "" ){
		} else {
			$where.= " and student.age='".$age."' ";
		}
		if( $job == "" ){
		} else {
			$where.= " and student.job='".$job."' ";
		}
		if( $job_type == "" ){
		} else {
			$where.= " and student.job_type='".$job_type."' ";
		}
		if( $school_grade == "" ){
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

$template->assign('mid', $mid);

$template->assign('post_all', $post_all);

$template->assign('member_type', $member_type);
$template->assign('sex', $sex);
$template->assign('pref', $pref);

$template->assign('age', $age);
$template->assign('job', $job);
$template->assign('job_type', $job_type);
$template->assign('school_grade', $school_grade);

$template->assign('bar_association_id', $bar_association_id);
$template->assign('start_regist_date', $start_regist_date);
$template->assign('end_regist_date', $end_regist_date);
$template->assign('start_lawyer_number', $start_lawyer_number);
$template->assign('end_lawyer_number', $end_lawyer_number);

$template->assign('mailmagazine_category', $mailmagazine_category);

$template->assign('submit_datetime', $submit_datetime);
$template->assign('mail_title', $mail_title);
$template->assign('mail_body', $mail_body);
$template->assign('arr_list', $ret);

$template->assign('arr_err', $arr_err);

if(count($arr_err)==0){
	$template->assign('next_url', 'regist.php');
	$template->assign('prev_url', 'edit.php');

	$template->assign('page_name', 'mailmagazine');
	$template->admin_layout('mailmagazine/conf.tpl');
} else {
	$template->assign('next_url', 'conf.php');
	$template->assign('prev_url', '');

	$template->assign('page_name', 'mailmagazine');
	$template->admin_layout('mailmagazine/form.tpl');
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
