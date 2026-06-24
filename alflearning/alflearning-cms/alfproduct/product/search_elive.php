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
$gid = $_GET['gid'];

$search_product_name = "";
$search_product_code = "";
$search_start_date = "";
$search_end_date = "";
if( isset($_SESSION["selive.search_product_name"]) && !empty($_SESSION["selive.search_product_name"]) ){
	$search_product_name = $_SESSION["selive.search_product_name"];
}
if( isset($_SESSION["selive.search_product_code"]) && !empty($_SESSION["selive.search_product_code"]) ){
	$search_product_code = $_SESSION["selive.search_product_code"];
}
if( isset($_SESSION["selive.search_start_date"]) && !empty($_SESSION["selive.search_start_date"]) ){
	$search_start_date = $_SESSION["selive.search_start_date"];
}
if( isset($_SESSION["selive.search_end_date"]) && !empty($_SESSION["selive.search_end_date"]) ){
	$search_end_date = $_SESSION["selive.search_end_date"];
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_product_name = trim($_POST["search_product_name"]);
	$search_product_code = trim($_POST["search_product_code"]);
	$search_start_date = trim($_POST["search_start_date"]);
	$search_end_date = trim($_POST["search_end_date"]);
	$_SESSION["selive.search_product_name"] = $search_product_name;
	$_SESSION["selive.search_product_code"] = $search_product_code;
	$_SESSION["selive.search_start_date"] = $search_start_date;
	$_SESSION["selive.search_end_date"] = $search_end_date;
	$_SESSION["selive.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["selive.page"]) && !empty($_SESSION["selive.page"]) ){
	$page = $_SESSION["selive.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["selive.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//$sql = "select count(*) as c from tbl_product where del_flg=0 ";

$sql = "select count(tbl_product.product_id) as c from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id where tbl_product.del_flg=0 ";

$where = "";
if( $search_product_name != "" ){
	$where.= " and ( tbl_product.product_name like '%".$search_product_name."%' ) ";
}
if( $search_product_code != "" ){
	$where.= " and ( tbl_product.product_code like '%".$search_product_code."%' ) ";
}
if( $search_start_date != "" && $search_end_date != "" ){
	$where.= " and tbl_product.start_date<='".$search_start_date."' and tbl_product.end_date>='".$search_end_date."' ";
} elseif( $search_start_date != "" ){
	$where.= " and tbl_product.start_date<='".$search_start_date."' ";
} elseif( $search_end_date != "" ){
	$where.= " and tbl_product.end_date>='".$search_end_date."' ";
}

$ret = $objDbConnect->query_fetch($sql.$where);
//$objAdminPager->setPageMax(1);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?gid=".$gid."&page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY tbl_product.product_id DESC ";
$sql = "select * from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_live_training ON tbl_product.product_id = tbl_product_live_training.product_id where tbl_product.del_flg=0 ";


//echo $sql.$where.$order.$offset;

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("e-ライブ関連商品検索");
$template->admin_comment("e-ライブ関連商品を検索します。会場研修商品より選択します。");

$template->assign('page', $page);
$template->assign('search_product_name', $search_product_name);
$template->assign('search_product_code', $search_product_code);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);
$template->assign('gid', $gid);

if (empty($_POST)){
	if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
		$disp_flg = true;
	} else {
		$disp_flg = false;
	}
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);

$template->assign('page_name', 'product');
$template->admin_layout('product/search_elive.tpl', true);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>