<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
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
$search_start_date = "";
$search_end_date = "";
$search_keyword = "";

$search_target_parameter_member_type = "1";
$search_target_parameter_sex = "1";
$search_target_parameter_pref = "";

$search_mailmagazine_category = array();

if( $_GET["search"]=="new" ){
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

	if( isset($_SESSION["mailmagazine.search_target_parameter_member_type"]) && !empty($_SESSION["mailmagazine.search_target_parameter_member_type"]) ){
		$search_target_parameter_member_type = $_SESSION["mailmagazine.search_target_parameter_member_type"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_sex"]) && !empty($_SESSION["mailmagazine.search_target_parameter_sex"]) ){
		$search_target_parameter_sex = $_SESSION["mailmagazine.search_target_parameter_sex"];
	}
	if( isset($_SESSION["mailmagazine.search_target_parameter_pref"]) && !empty($_SESSION["mailmagazine.search_target_parameter_pref"]) ){
		$search_target_parameter_pref = $_SESSION["mailmagazine.search_target_parameter_pref"];
	}

	if( isset($_SESSION["mailmagazine.search_mailmagazine_category"]) && !empty($_SESSION["mailmagazine.search_mailmagazine_category"]) ){
		$search_mailmagazine_category = $_SESSION["mailmagazine.search_mailmagazine_category"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_start_date = trim($_POST["search_start_date"]);
	$search_end_date = trim($_POST["search_end_date"]);
	$search_keyword = trim($_POST["search_keyword"]);

	$search_target_parameter_member_type = trim($_POST["search_member_type"]);
	$search_target_parameter_sex = trim($_POST["search_sex"]);
	$search_target_parameter_pref = trim($_POST["search_pref"]);

	$search_mailmagazine_category = $_POST["search_mailmagazine_category"];

	$_SESSION["mailmagazine.search_start_date"] = $search_start_date;
	$_SESSION["mailmagazine.search_end_date"] = $search_end_date;
	$_SESSION["mailmagazine.search_keyword"] = $search_keyword;

	$_SESSION["mailmagazine.search_target_parameter_member_type"] = $search_target_parameter_member_type;
	$_SESSION["mailmagazine.search_target_parameter_sex"] = $search_target_parameter_sex;
	$_SESSION["mailmagazine.search_target_parameter_pref"] = $search_target_parameter_pref;

	$_SESSION["mailmagazine.search_mailmagazine_category"] = $search_mailmagazine_category;
	$_SESSION["mailmagazine.page"] = 1;
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

$ret = $objDbConnect->query_fetch($sql.$where);
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
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('arr_pref', $arr_pref);
$template->assign('arr_mailmagazine_category', $arr_mailmagazine_category);

$template->assign('page', $page);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('search_keyword', $search_keyword);
$template->assign('search_member_type', $search_target_parameter_member_type);
$template->assign('search_sex', $search_target_parameter_sex);
$template->assign('search_pref', $search_target_parameter_pref);
$template->assign('search_mailmagazine_category', $search_mailmagazine_category);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('page_name', 'mailmagazine');
$template->admin_layout('mailmagazine/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
