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
$search_product_name = "";
$search_product_code = "";
$search_start_date = "";
$search_end_date = "";
if( $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["product.search_product_name"]) && !empty($_SESSION["product.search_product_name"]) ){
		$search_product_name = $_SESSION["product.search_product_name"];
	}
	if( isset($_SESSION["product.search_product_code"]) && !empty($_SESSION["product.search_product_code"]) ){
		$search_product_code = $_SESSION["product.search_product_code"];
	}
	if( isset($_SESSION["product.search_start_date"]) && !empty($_SESSION["product.search_start_date"]) ){
		$search_start_date = $_SESSION["product.search_start_date"];
	}
	if( isset($_SESSION["product.search_end_date"]) && !empty($_SESSION["product.search_end_date"]) ){
		$search_end_date = $_SESSION["product.search_end_date"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_product_name = trim($_POST["search_product_name"]);
	$search_product_code = trim($_POST["search_product_code"]);
	$search_start_date = trim($_POST["search_start_date"]);
	$search_end_date = trim($_POST["search_end_date"]);
	$_SESSION["product.search_product_name"] = $search_product_name;
	$_SESSION["product.search_product_code"] = $search_product_code;
	$_SESSION["product.search_start_date"] = $search_start_date;
	$_SESSION["product.search_end_date"] = $search_end_date;
	$_SESSION["product.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["product.page"]) && !empty($_SESSION["product.page"]) ){
	$page = $_SESSION["product.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["product.page"] = $page;
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
$objAdminPager->setPagerUrl("?page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY product_id DESC ";
$sql = "select * from tbl_product where del_flg=0 ";

//echo $sql.$where.$order.$offset;

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("登録商品一覧");
$template->admin_comment("登録商品を管理します。");
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('page', $page);
$template->assign('search_product_name', $search_product_name);
$template->assign('search_product_code', $search_product_code);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('page_name', 'product');
$template->admin_layout('product/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
