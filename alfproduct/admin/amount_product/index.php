<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_start_date = date("Y/m/01 00:00")."";
$search_end_date = date("Y/m/t 23:00");
if( $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["amount_product.search_start_date"]) && !empty($_SESSION["amount_product.search_start_date"]) ){
		$search_start_date = $_SESSION["amount_product.search_start_date"];
	}
	if( isset($_SESSION["amount_product.search_end_date"]) && !empty($_SESSION["amount_product.search_end_date"]) ){
		$search_end_date = $_SESSION["amount_product.search_end_date"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_start_date = trim($_POST["search_start_date"]);
	$search_end_date = trim($_POST["search_end_date"]);
	$_SESSION["amount_product.search_start_date"] = $search_start_date;
	$_SESSION["amount_product.search_end_date"] = $search_end_date;
	$_SESSION["amount_product.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$page = 1;
if( isset($_SESSION["amount_product.page"]) && !empty($_SESSION["amount_product.page"]) ){
	$page = $_SESSION["amount_product.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_product.page"] = $page;
}
$objAdminPager->setNowPage( $page );
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.product_id, ";
$sql.= "tbl_order_detail.sell_price, ";
$sql.= "SUM(tbl_order_detail.unit) as unit, ";
$sql.= "SUM(tbl_order_detail.pay_total) as pay_total, ";
//$sql.= "DATE_FORMAT(tbl_order_detail.create_date,'%Y/%m/%d') as buy_date, ";
$sql.= "tbl_product.product_code, ";
$sql.= "tbl_product.product_name, ";
$sql.= "student.school_id ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";

$where = "";
$where.= "WHERE ";
$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
$where.= " and tbl_order_detail.product_id IS NOT NULL ";
if( $search_start_date != "" ){
	$where.= " and tbl_order_detail.create_date>='".$search_start_date."' ";
}
if( $search_end_date != "" ){
	$where.= " and tbl_order_detail.create_date<='".substr($search_end_date,0,14)."59:59' ";
}
$group = "";
$group.= " GROUP BY tbl_order_detail.product_id,tbl_order_detail.sell_price,tbl_product.product_code,tbl_product.product_name,student.school_id ";

$ret = $objDbConnect->query_fetch_arr($sql.$where.$group);
//$objAdminPager->setListMax(count($ret));
//$objAdminPager->setPagerUrl("?page=");
//$objAdminPager->setPageMax(1);
//$pager = $objAdminPager->getPager();
//$offset = $objAdminPager->getOffset();
$order = " ORDER BY SUM(tbl_order_detail.pay_total) DESC,SUM(tbl_order_detail.unit) DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
$pay_total_total = 0;
$unit_total = 0;
for($i=0;$i<count($ret);$i++){
	$pay_total_total += $ret[$i]["pay_total"];
	$unit_total += $ret[$i]["unit"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("売上分析");
$template->admin_comment("売上を集計します。");
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
$template->admin_school($arr_session["cms_master.login.school_name"]);

$sidemenu_html ='<ul>
<li>
<a href="./../amount_product/index.php">売上集計</a>
</li>
<li class="selected">
<a href="./../amount_product/index.php">売上分析</a>
</li>
</ul>';
$template->admin_sidemenu($sidemenu_html);


$template->assign('arr_pref', $arr_pref);

$template->assign('page', $page);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
//$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('pay_total_total', $pay_total_total);
$template->assign('unit_total', $unit_total);

$template->assign('post_data', serialize($_POST));

$template->assign('page_name', 'amount_product');
$template->admin_layout('amount_product/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
