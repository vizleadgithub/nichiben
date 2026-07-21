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
if( isset($_SESSION["sproduct.search_product_name"]) && !empty($_SESSION["sproduct.search_product_name"]) ){
	$search_product_name = $_SESSION["sproduct.search_product_name"];
}
if( isset($_SESSION["sproduct.search_product_code"]) && !empty($_SESSION["sproduct.search_product_code"]) ){
	$search_product_code = $_SESSION["sproduct.search_product_code"];
}
if( isset($_SESSION["sproduct.search_start_date"]) && !empty($_SESSION["sproduct.search_start_date"]) ){
	$search_start_date = $_SESSION["sproduct.search_start_date"];
}
if( isset($_SESSION["sproduct.search_end_date"]) && !empty($_SESSION["sproduct.search_end_date"]) ){
	$search_end_date = $_SESSION["sproduct.search_end_date"];
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_product_name = trim($_POST["search_product_name"]);
	$search_product_code = trim($_POST["search_product_code"]);
	$search_start_date = preg_replace('/[^0-9\/: \-]/', '', trim($_POST["search_start_date"]));
	$search_end_date = preg_replace('/[^0-9\/: \-]/', '', trim($_POST["search_end_date"]));
	$_SESSION["sproduct.search_product_name"] = $search_product_name;
	$_SESSION["sproduct.search_product_code"] = $search_product_code;
	$_SESSION["sproduct.search_start_date"] = $search_start_date;
	$_SESSION["sproduct.search_end_date"] = $search_end_date;
	$_SESSION["sproduct.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["sproduct.page"]) && !empty($_SESSION["sproduct.page"]) ){
	$page = $_SESSION["sproduct.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["sproduct.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select count(*) as c from tbl_product where del_flg=0 ";
$where = "";
if( $search_product_name != "" ){
	$where.= " and ( product_name like '%".$search_product_name."%' ) ";
}
if( $search_product_code != "" ){
	$where.= " and ( product_code like '%".$search_product_code."%' ) ";
}
if( $search_start_date != "" && $search_end_date != "" ){
	$where.= " and start_date<='".$search_start_date."' and end_date>='".$search_end_date."' ";
} elseif( $search_start_date != "" ){
	$where.= " and start_date<='".$search_start_date."' ";
} elseif( $search_end_date != "" ){
	$where.= " and end_date>='".$search_end_date."' ";
}

$ret = $objDbConnect->query_fetch($sql.$where);
//$objAdminPager->setPageMax(1);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?gid=".$gid."&page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY product_id DESC ";
$sql = "select * from tbl_product where del_flg=0 ";

//echo $sql.$where.$order.$offset;

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("関連商品検索");
$template->admin_comment("関連商品を検索します。");

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
$template->admin_layout('product/search_product.tpl', true);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>