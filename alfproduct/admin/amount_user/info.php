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
$member_id = "";
if( isset($_REQUEST["sid"]) && !empty($_REQUEST["sid"]) ){
	$member_id = $_REQUEST["sid"];
}
if($member_id == ""){
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["amount_user.info.page"]) && !empty($_SESSION["amount_user.info.page"]) ){
	$page = $_SESSION["amount_user.info.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_user.info.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "select * from mtb_pref";
$arr_pref = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "select ";
$sql.= "* ";
$sql.= ",(YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5)) AS age ";
$sql.= "from ";
$sql.= "student ";
$sql.= "where ";
$sql.= "student_id='".$member_id."'";
$arr_student = $objDbConnect->query_fetch_arr($sql);
if( 0<count($arr_student) ){
} else {
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "count(order_detail_id) as c ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$where = "";
$where.= "WHERE ";
$where.= " tbl_order_detail.product_id IS NOT NULL ";
$where.= " and member_id='".$member_id."'";
$group = "";
$ret = $objDbConnect->query_fetch($sql.$where.$group);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?sid=".$member_id."&page=");
//$objAdminPager->setPageMax(1);
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.*, ";
$sql.= "tbl_product.product_code, ";
$sql.= "tbl_product.product_name ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$order = " ORDER BY tbl_order_detail.create_date DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("売上集計");
$template->admin_comment("売上を集計します。");
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
$template->admin_school($arr_session["cms_master.login.school_name"]);

$sidemenu_html ='<ul>
<li class="selected">
<a href="./../amount_user/index.php">売上集計</a>
</li>
<li>
<a href="./../amount_product/index.php">売上分析</a>
</li>
</ul>';
$template->admin_sidemenu($sidemenu_html);


$template->assign('arr_pref', $arr_pref);
$template->assign('arr_student', $arr_student[0]);

$template->assign('sid', $member_id);
$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('page_name', 'amount_user');
$template->admin_layout('amount_user/info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
