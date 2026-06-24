<?php
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
$sql = "select * from mtb_mailmagazine_category";
$arr_mailmagazine_category = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_pref";
$arr_pref = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_age";
$arr_age = $objDbConnect->query_fetch_arr($sql);
//・年代
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_job";
$arr_job = $objDbConnect->query_fetch_arr($sql);
//・職業
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_job_type";
$arr_job_type = $objDbConnect->query_fetch_arr($sql);
//・業種
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_school_grade";
$arr_school_grade = $objDbConnect->query_fetch_arr($sql);
//・学年
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$search_start_date = "";
$search_end_date = "";

$search_start_regist_date = "";
$search_end_regist_date = "";

$search_keyword = "";

$search_target_parameter_member_type = "1";
$search_target_parameter_sex = "1";
$search_target_parameter_pref = "";

$search_target_parameter_age = "";
$search_target_parameter_job = "";
$search_target_parameter_job_type = "";
$search_target_parameter_school_grade = "";

$search_mailmagazine_category = array();

if( isset($_GET["search"]) && $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["mailmagazine.search_start_date"]) && !empty($_SESSION["mailmagazine.search_start_date"]) ){
		$search_start_date = $_SESSION["mailmagazine.search_start_date"];
	}
	if( isset($_SESSION["mailmagazine.search_end_date"]) && !empty($_SESSION["mailmagazine.search_end_date"]) ){
		$search_end_date = $_SESSION["mailmagazine.search_end_date"];
	}
	if( isset($_SESSION["mailmagazine.search_keyword"]) && !empty($_SESSION["mailmagazine.search_keyword"]) ){
		$search_keyword = $_SESSION["mailmagazine.search_keyword"];
	}

	if( isset($_SESSION["mailmagazine.search_start_regist_date"]) && !empty($_SESSION["mailmagazine.search_start_regist_date"]) ){
		$search_start_regist_date = $_SESSION["mailmagazine.search_start_regist_date"];
	}
	if( isset($_SESSION["mailmagazine.search_end_regist_date"]) && !empty($_SESSION["mailmagazine.search_end_regist_date"]) ){
		$search_end_regist_date = $_SESSION["mailmagazine.search_end_regist_date"];
	}

	if( isset($_SESSION["mailmagazine.search_target_parameter_member_type"]) && !empty($_SESSION["mailmagazine.search_target_parameter_member_type"]) ){
		$search_target_parameter_member_type = $_SESSION["mailmagazine.search_target_parameter_member_type"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_sex"]) && !empty($_SESSION["mailmagazine.search_target_parameter_sex"]) ){
		$search_target_parameter_sex = $_SESSION["mailmagazine.search_target_parameter_sex"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_pref"]) && !empty($_SESSION["mailmagazine.search_target_parameter_pref"]) ){
		$search_target_parameter_pref = $_SESSION["mailmagazine.search_target_parameter_pref"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_age"]) && !empty($_SESSION["mailmagazine.search_target_parameter_age"]) ){
		$search_target_parameter_age = $_SESSION["mailmagazine.search_target_parameter_age"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_job"]) && !empty($_SESSION["mailmagazine.search_target_parameter_job"]) ){
		$search_target_parameter_job = $_SESSION["mailmagazine.search_target_parameter_job"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_job_type"]) && !empty($_SESSION["mailmagazine.search_target_parameter_job_type"]) ){
		$search_target_parameter_job_type = $_SESSION["mailmagazine.search_target_parameter_job_type"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_school_grade"]) && !empty($_SESSION["mailmagazine.search_target_parameter_school_grade"]) ){
		$search_target_parameter_school_grade = $_SESSION["mailmagazine.search_target_parameter_school_grade"];
	}

	if( isset($_SESSION["mailmagazine.search_mailmagazine_category"]) && !empty($_SESSION["mailmagazine.search_mailmagazine_category"]) ){
		$search_mailmagazine_category = $_SESSION["mailmagazine.search_mailmagazine_category"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_start_date = trim($_POST["search_start_date"]);
	$search_end_date = trim($_POST["search_end_date"]);
	$search_start_regist_date = trim($_POST["search_start_regist_date"]);
	$search_end_regist_date = trim($_POST["search_end_regist_date"]);
	$search_keyword = trim($_POST["search_keyword"]);

	$search_target_parameter_member_type = trim($_POST["search_member_type"]);
	$search_target_parameter_sex = trim($_POST["search_sex"]);
	$search_target_parameter_pref = trim($_POST["search_pref"]);

	$search_target_parameter_age = trim($_POST["search_age"]);
	$search_target_parameter_job = trim($_POST["search_job"]);
	$search_target_parameter_job_type = trim($_POST["search_job_type"]);
	$search_target_parameter_school_grade = trim($_POST["search_school_grade"]);

	$search_mailmagazine_category = $_POST["search_mailmagazine_category"];

	$_SESSION["mailmagazine.search_start_date"] = $search_start_date;
	$_SESSION["mailmagazine.search_end_date"] = $search_end_date;
	$_SESSION["mailmagazine.search_start_regist_date"] = $search_start_regist_date;
	$_SESSION["mailmagazine.search_end_regist_date"] = $search_end_regist_date;
	$_SESSION["mailmagazine.search_keyword"] = $search_keyword;

	$_SESSION["mailmagazine.search_target_parameter_member_type"] = $search_target_parameter_member_type;
	$_SESSION["mailmagazine.search_target_parameter_sex"] = $search_target_parameter_sex;
	$_SESSION["mailmagazine.search_target_parameter_pref"] = $search_target_parameter_pref;

	$_SESSION["mailmagazine.search_target_parameter_age"] = $search_target_parameter_age;
	$_SESSION["mailmagazine.search_target_parameter_job"] = $search_target_parameter_job;
	$_SESSION["mailmagazine.search_target_parameter_job_type"] = $search_target_parameter_job_type;
	$_SESSION["mailmagazine.search_target_parameter_school_grade"] = $search_target_parameter_school_grade;

	$_SESSION["mailmagazine.search_mailmagazine_category"] = $search_mailmagazine_category;


	$_SESSION["mailmagazine.page"] = 1;
	$_GET["page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["mailmagazine.page"]) && !empty($_SESSION["mailmagazine.page"]) ){
	$page = $_SESSION["mailmagazine.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["mailmagazine.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select count(*) as c from tbl_mailmagazine where del_flg=0 ";
$where = "";
if( $search_start_date != "" ){
	$where.= " and submit_datetime>='".$search_start_date."' ";
}
if( $search_end_date != "" ){
	$where.= " and submit_datetime<='".substr($search_end_date,0,14)."59:59' ";
}

if( $search_start_regist_date != "" && $search_end_regist_date != "" ){
	$where.= " and ( ";
	$where.= "  ( ";
	$where.= "   target_parameter_end_regist_date>='".substr($search_start_regist_date,0,10)."' ";
	$where.= "   AND target_parameter_end_regist_date<='".substr($search_end_regist_date,0,10)."' ";
	$where.= "  ) ";
	$where.= "  OR ( ";
	$where.= "   target_parameter_start_regist_date>='".substr($search_start_regist_date,0,10)."' ";
	$where.= "   AND target_parameter_start_regist_date<='".substr($search_end_regist_date,0,10)."' ";
	$where.= "  ) ";
	$where.= " ) ";
} else {
	if( $search_start_regist_date != "" ){
		$where.= " and ( ";
		$where.= "  target_parameter_start_regist_date>='".substr($search_start_regist_date,0,10)."' ";
		$where.= "  OR target_parameter_start_regist_date IS NULL ";
		$where.= " ) ";
	}
	if( $search_end_regist_date != "" ){
		$where.= " and ( ";
		$where.= "  target_parameter_end_regist_date<='".substr($search_end_regist_date,0,10)."' ";
		$where.= "  OR target_parameter_end_regist_date IS NULL ";
		$where.= " ) ";
	}
}

if( $search_keyword != "" ){
	$where.= " and ( mail_title like '%".$search_keyword."%' or mail_body like '%".$search_keyword."%' ) ";
}

if( $search_target_parameter_member_type == "1" ){
} elseif( $search_target_parameter_member_type == "2" ){
	$where.= " and target_parameter_member_type='2' ";
} elseif( $search_target_parameter_member_type == "3" ){
	$where.= " and target_parameter_member_type='3' ";
}
if( $search_target_parameter_sex == "1" ){
} elseif( $search_target_parameter_sex == "2" ){
	$where.= " and target_parameter_sex='2' ";
} elseif( $search_target_parameter_sex == "3" ){
	$where.= " and target_parameter_sex='3' ";
}
if( $search_target_parameter_pref != "" ){
	$where.= " and target_parameter_pref='".$search_target_parameter_pref."' ";
}

if( $search_target_parameter_age != "" ){
	$where.= " and target_parameter_age='".$search_target_parameter_age."' ";
}
if( $search_target_parameter_job != "" ){
	$where.= " and target_parameter_job='".$search_target_parameter_job."' ";
}
if( $search_target_parameter_job_type != "" ){
	$where.= " and target_parameter_job_type='".$search_target_parameter_job_type."' ";
}
if( $search_target_parameter_school_grade != "" ){
	$where.= " and target_parameter_school_grade='".$search_target_parameter_school_grade."' ";
}

if( 0<count($search_mailmagazine_category) ){
	$where.= " and (";
	for($i=0;$i<count($search_mailmagazine_category);$i++){
		if($i>0){ $where.= " or "; }
		$where.= " ( ";
			$where.= " concat(',',target_parameter_mailmagazine_category,',') LIKE '%,".$search_mailmagazine_category[$i].",%' ";
		$where.= " ) ";
	}
	$where.= " ) ";
}

$all_count = 0;
$ret = $objDbConnect->query_fetch($sql.$where);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}
//$objAdminPager->setPageMax(1);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY mailmagazine_id DESC ";
$sql = "select mailmagazine_id, target_parameter, target_number, DATE_FORMAT(submit_datetime,'%Y/%m/%d %k:%i')as submit_datetime, submit_status, mail_title, mail_body, del_flg, create_date, update_date from tbl_mailmagazine where del_flg=0 ";
//var_dump($sql.$where.$order.$offset);
$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("メールマガジン");
$template->admin_comment("メールマガジンを管理します。");

$sidemenu_html = '<ul>';
$sidemenu_html.= '';
$sidemenu_html.= '</ul>';
$template->admin_sidemenu($sidemenu_html);


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

$template->assign('page', $page);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);

$template->assign('search_start_regist_date', $search_start_regist_date);
$template->assign('search_end_regist_date', $search_end_regist_date);

$template->assign('search_keyword', $search_keyword);
$template->assign('search_member_type', $search_target_parameter_member_type);
$template->assign('search_sex', $search_target_parameter_sex);
$template->assign('search_pref', $search_target_parameter_pref);

$template->assign('search_age', $search_target_parameter_age);
$template->assign('search_job', $search_target_parameter_job);
$template->assign('search_job_type', $search_target_parameter_job_type);
$template->assign('search_school_grade', $search_target_parameter_school_grade);

$template->assign('search_mailmagazine_category', $search_mailmagazine_category);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);
$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

$template->assign('page_name', 'mailmagazine');
$template->admin_layout('mailmagazine/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
