<?php
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
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

//$arr_postdata = unserialize(str_replace('&amp;','&',str_replace('&quot;','"',str_replace('&#039;',"'",str_replace('&apos;',"'",str_replace('&lt;','<',str_replace('&gt;','>',$_GET["data"])))))));
//var_dump($arr_postdata);
//exit();

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
$sql = "select * from student where student.status=0 and mailmagazine_flg=1 ";
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
	if( $pref=="" || $pref=="0"){
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

$ret = $objDbConnect->query_fetch_arr($sql.$where);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($sql.$where);
//var_dump($ret);
//exit();
// CSVヘッダ
header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");
header("Content-Disposition: attachment; filename=send_user_".date("YmdHis").".csv");

echo mb_convert_encoding("生徒ID,生徒名,メールアドレス\r\n", "SJIS", "UTF-8");
for($i=0;$i<count($ret);$i++){
	echo mb_convert_encoding('"' . $ret[$i]["student_id"] . '","' . $ret[$i]["student_name"] . '","' . $ret[$i]["student_email"] . '",' ."\r\n", "SJIS", "UTF-8");
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
