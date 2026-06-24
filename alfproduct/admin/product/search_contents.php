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
$gid = $_GET['gid'];

$search_contents_name = "";
$search_contents_code = "";
if( isset($_SESSION["scontents.search_contents_name"]) && !empty($_SESSION["scontents.search_contents_name"]) ){
	$search_contents_name = $_SESSION["scontents.search_contents_name"];
}
if( isset($_SESSION["scontents.search_contents_code"]) && !empty($_SESSION["scontents.search_contents_code"]) ){
	$search_contents_code = $_SESSION["scontents.search_contents_code"];
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_contents_name = trim($_POST["search_contents_name"]);
	$search_contents_code = trim($_POST["search_contents_code"]);
	$_SESSION["scontents.search_contents_name"] = $search_contents_name;
	$_SESSION["scontents.search_contents_code"] = $search_contents_code;
	$_SESSION["scontents.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["scontents.page"]) && !empty($_SESSION["scontents.page"]) ){
	$page = $_SESSION["scontents.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["scontents.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



/***********
取得条件は確認する
************/
$sql = "select count(*) as c from video where status=0 ";



$where = "";
if( $search_contents_name != "" ){
	$where.= " and ( video_logic_name like '%".$search_contents_name."%' ) ";
}
if( $search_contents_code != "" ){
	$where.= " and ( video_id = '".$search_contents_code."' ) ";
}

$ret = $objDbConnect->query_fetch($sql.$where);
//$objAdminPager->setPageMax(1);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?gid=".$gid."&page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY video_id DESC ";

/***********
取得条件は確認する
************/
$sql = "select * from video where status=0 ";



//echo $sql.$where.$order.$offset;

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("コンテンツ検索");
$template->admin_comment("コンテンツを検索します。");

$template->assign('page', $page);
$template->assign('search_contents_name', $search_contents_name);
$template->assign('search_contents_code', $search_contents_code);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);
$template->assign('gid', $gid);

$template->assign('page_name', 'product');
$template->admin_layout('product/search_contents.tpl', true);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>