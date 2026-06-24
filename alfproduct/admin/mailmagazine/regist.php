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
$member_type = "";
$sex = "";
$pref = "";
$mailmagazine_category = array();
$submit_datetime = "";
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
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_mailmagazine_category";
$arr_mailmagazine_category = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_pref";
$arr_pref = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_err = array();
//member_type
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
	$sql = "select count(student.student_id) as c from student where student.status=0 ";
	if( $arr_session["cms_master.login.school_id"]>0 && trim($arr_session["cms_master.login.school_id"])!="" ){
		$sql.= " and school_id=".$arr_session["cms_master.login.school_id"]." ";
	}
	$where = "";
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

	$ret = $objDbConnect->query_fetch($sql.$where);
	$member_count = $ret["c"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if(count($arr_err)==0){
	if( $mid=="" ){
		//Insert
		$arr_target_parameter = array();
		$arr_target_parameter["member_type"] = $member_type;
		$arr_target_parameter["sex"] = $sex;
		$arr_target_parameter["pref"] = $pref;
		$arr_target_parameter["mailmagazine_category"] = $mailmagazine_category;
		$arr_target_parameter["school_id"] = $arr_session["cms_master.login.school_id"];
		$sql = "";
		$sql.= "insert into tbl_mailmagazine(";
		$sql.= "target_parameter ";

		$sql.= ",target_parameter_member_type ";
		$sql.= ",target_parameter_sex ";
		$sql.= ",target_parameter_pref ";
		$sql.= ",target_parameter_mailmagazine_category ";
		$sql.= ",school_id ";

		$sql.= ",target_number ";
		$sql.= ",submit_datetime ";
		$sql.= ",submit_status ";
		$sql.= ",mail_title ";
		$sql.= ",mail_body ";
		$sql.= ",del_flg ";
		$sql.= ") ";
		$sql.= " values(";
		$sql.= "'".mysql_escape_string(serialize($arr_target_parameter))."'";

		$sql.= ",'".mysql_escape_string($member_type)."'";
		$sql.= ",'".mysql_escape_string($sex)."'";
		$sql.= ",'".mysql_escape_string($pref)."'";
		$sql.= ",'".mysql_escape_string(implode(",",$mailmagazine_category))."'";
		$sql.= ",'".mysql_escape_string($arr_session["cms_master.login.school_id"])."'";

		$sql.= ",'".mysql_escape_string($member_count)."'";
		$sql.= ",'".mysql_escape_string($submit_datetime)."'";
		$sql.= ",'0'";
		$sql.= ",'".mysql_escape_string($mail_title)."'";
		$sql.= ",'".mysql_escape_string($mail_body)."'";
		$sql.= ",'0'";
		$sql.= ") ";
		$ret = $objDbConnect->execute($sql);
		if(!$ret){
			$arr_err["db"] = "登録に失敗しました。";
		} else {
			$ret = $objDbConnect->get_thread_id("tbl_mailmagazine");
			$mid = $ret[0];
		}
	} else {
		//Update
		$arr_target_parameter = array();
		$arr_target_parameter["member_type"] = $member_type;
		$arr_target_parameter["sex"] = $sex;
		$arr_target_parameter["pref"] = $pref;
		$arr_target_parameter["mailmagazine_category"] = $mailmagazine_category;
		$arr_target_parameter["school_id"] = $arr_session["cms_master.login.school_id"];
		$sql = "";
		$sql.= "update tbl_mailmagazine set ";
		$sql.= "target_parameter='".mysql_escape_string(serialize($arr_target_parameter))."' ";

		$sql.= ",target_parameter_member_type='".mysql_escape_string($member_type)."'";
		$sql.= ",target_parameter_sex='".mysql_escape_string($sex)."'";
		$sql.= ",target_parameter_pref='".mysql_escape_string($pref)."'";
		$sql.= ",target_parameter_mailmagazine_category='".mysql_escape_string(implode(",",$mailmagazine_category))."'";
		$sql.= ",school_id='".mysql_escape_string($arr_session["cms_master.login.school_id"])."'";

		$sql.= ",target_number='".mysql_escape_string($member_count)."' ";
		$sql.= ",submit_datetime='".mysql_escape_string($submit_datetime)."' ";
		$sql.= ",mail_title='".mysql_escape_string($mail_title)."' ";
		$sql.= ",mail_body='".mysql_escape_string($mail_body)."' ";
		$sql.= ",update_date='".date("Y-m-d H:i:00")."' ";
		$sql.= " where mailmagazine_id='".mysql_escape_string($mid)."' ";
		$ret = $objDbConnect->execute($sql);
		if(!$ret){
			$arr_err["db"] = "更新に失敗しました。";
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if(count($arr_err)==0){
	if (strtotime(date('Y/m/d H:i')) >= strtotime($submit_datetime)) {
		//var_dump($mid);
		//var_dump( dirname(__FILE__) );
		//var_dump( dirname(__FILE__)."/../../cron/mailmagazine_submit.php" );
		//var_dump('php '.realpath( dirname(__FILE__)."/../../cron/mailmagazine_submit.php" )." ".$mid );
		//exit();

		exec('php '.realpath( dirname(__FILE__)."/../../cron/mailmagazine_submit.php" )." ".$mid );
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
if(count($arr_err)==0){
	header("Location: end.php");
	exit();
} else {
	$template->admin_title("メールマガジン");
	$template->admin_comment("メールマガジンを管理します。");
	$template->admin_name($arr_session["cms_master.login.teacher_name"]);
	$template->admin_school($arr_session["cms_master.login.school_name"]);

	$template->assign('arr_pref', $arr_pref);
	$template->assign('arr_mailmagazine_category', $arr_mailmagazine_category);
	$template->assign('member_count', $member_count);

	$template->assign('mid', $mid);
	$template->assign('member_type', $member_type);
	$template->assign('sex', $sex);
	$template->assign('pref', $pref);
	$template->assign('mailmagazine_category', $mailmagazine_category);
	$template->assign('submit_datetime', $submit_datetime);
	$template->assign('mail_title', $mail_title);
	$template->assign('mail_body', $mail_body);
	$template->assign('arr_list', $ret);

	$template->assign('arr_err', $arr_err);


	$template->assign('next_url', 'conf.php');
	$template->assign('prev_url', '');

	$template->assign('page_name', 'mailmagazine');
	$template->admin_layout('mailmagazine/form.tpl');
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
