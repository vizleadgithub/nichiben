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
$search_start_date = "";
$search_end_date = "";
$search_keyword = "";
$search_status = "";
$search_lawyer_number = "";

if( isset($_GET["search"]) && $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["inquiry.search_start_date"]) && !empty($_SESSION["inquiry.search_start_date"]) ){
		$search_start_date = $_SESSION["inquiry.search_start_date"];
	}
	if( isset($_SESSION["inquiry.search_end_date"]) && !empty($_SESSION["inquiry.search_end_date"]) ){
		$search_end_date = $_SESSION["inquiry.search_end_date"];
	}
	if( isset($_SESSION["inquiry.search_keyword"]) && !empty($_SESSION["inquiry.search_keyword"]) ){
		$search_keyword = $_SESSION["inquiry.search_keyword"];
	}
	if( isset($_SESSION["inquiry.search_status"]) && !empty($_SESSION["inquiry.search_status"]) ){
		$search_status = $_SESSION["inquiry.search_status"];
	}
	if( isset($_SESSION["inquiry.search_lawyer_number"]) && !empty($_SESSION["inquiry.search_lawyer_number"]) ){
		$search_lawyer_number = $_SESSION["inquiry.search_lawyer_number"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_start_date = preg_replace('/[^0-9\/: \-]/', '', trim($_POST["search_start_date"]));
	$search_end_date = preg_replace('/[^0-9\/: \-]/', '', trim($_POST["search_end_date"]));
	$search_keyword = trim($_POST["search_keyword"]);
	$search_status = trim($_POST["search_status"]);
	$search_lawyer_number = trim($_POST["search_lawyer_number"]);
	$_SESSION["inquiry.search_start_date"] = $search_start_date;
	$_SESSION["inquiry.search_end_date"] = $search_end_date;
	$_SESSION["inquiry.search_keyword"] = $search_keyword;
	$_SESSION["inquiry.search_status"] = $search_status;
	$_SESSION["inquiry.search_lawyer_number"] = $search_lawyer_number;
	$_SESSION["inquiry.page"] = 1;
	$_GET["page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["inquiry.page"]) && !empty($_SESSION["inquiry.page"]) ){
	$page = $_SESSION["inquiry.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["inquiry.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select count(*) as c from tbl_inquiry where del_flg=0 ";
$where = "";
if( $search_start_date != "" ){
	$where.= " and regist_date >='".mysqli_real_escape_string($objDbConnect->connect,$search_start_date)."' ";
}
if( $search_end_date != "" ){
	$where.= " and regist_date <='".mysqli_real_escape_string($objDbConnect->connect,substr($search_end_date,0,14))."59:59' ";
}
if( $search_keyword != "" ){
	$where.= " and ( inquiry_name  like '%".mysqli_real_escape_string($objDbConnect->connect,$search_keyword)."%' or inquiry_mail  like '%".mysqli_real_escape_string($objDbConnect->connect,$search_keyword)."%' or inquiry_comment  like '%".mysqli_real_escape_string($objDbConnect->connect,$search_keyword)."%' or return_name  like '%".mysqli_real_escape_string($objDbConnect->connect,$search_keyword)."%' ) ";
}
if( $search_status != "" ){
	$where.= " and inquiry_status ='".mysqli_real_escape_string($objDbConnect->connect,$search_status)."' ";
}
if( $search_lawyer_number != "" ){
	$where.= " and inquiry_lawyer_number ='".mysqli_real_escape_string($objDbConnect->connect,$search_lawyer_number)."' ";
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
$order = " ORDER BY inquiry_id DESC ";
$sql = "select inquiry_id, inquiry_name, inquiry_mail, inquiry_comment, inquiry_status, inquiry_lawyer_number, return_name, return_title, return_comment, DATE_FORMAT(regist_date,'%Y/%m/%d %k:%i')as regist_date, DATE_FORMAT(update_date,'%Y/%m/%d %k:%i')as update_date, del_flg from tbl_inquiry where del_flg=0 ";
//var_dump($sql.$where.$order.$offset);
$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("お問い合わせ");
$template->admin_comment("お問い合わせを管理します。");

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

$template->assign('page', $page);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('search_keyword', $search_keyword);
$template->assign('search_status', $search_status);
$template->assign('search_lawyer_number', $search_lawyer_number);

$template->assign('pager', $pager);
$template->assign('arr_list', $ret);
$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

$template->assign('page_name', 'inquiry');
$template->admin_layout('inquiry/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
