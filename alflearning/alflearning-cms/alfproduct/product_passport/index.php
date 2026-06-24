<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
// 日弁連フラグ
$login_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
if ($login_bar_association_id == 1){
	$nichibenren_flg = true;  // 日弁連
} else {
	$nichibenren_flg = false; // 日弁連以外の弁護士会
}
if (!$nichibenren_flg){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}

if (empty($_POST) && empty($_GET)){
	$disp_flg = false;
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_product_name = "";

if( isset($_GET["search"]) && $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["product_passport.search_product_name"]) && !empty($_SESSION["product_passport.search_product_name"]) ){
		$search_product_name = $_SESSION["product_passport.search_product_name"];
	}
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_product_name = trim($_POST["search_product_name"]);

	$_SESSION["product_passport.search_product_name"] = $search_product_name;

	$_SESSION["product_passport.page"] = 1;
	$_GET["page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["product_passport.page"]) && !empty($_SESSION["product_passport.page"]) ){
	$page = $_SESSION["product_passport.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["product_passport.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$sql = "select count(tbl_product.product_id) as c from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_passport ON tbl_product.product_id = tbl_product_passport.product_id where tbl_product.del_flg=0 ";

$where = "";
if( $search_product_name != "" ){
	$where.= " and ( product_name like '%".mysqli_real_escape_string($objDbConnect->connect,$search_product_name)."%' ) ";
}

$all_count = 0;
$ret = $objDbConnect->query_fetch($sql.$where);
//$objAdminPager->setPageMax(1);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY product_id DESC ";

$sql = "select tbl_product.product_id,tbl_product.product_name,DATE_FORMAT(tbl_product.start_date,'%Y/%m/%d %H:%i') as start_date,DATE_FORMAT(tbl_product.end_date,'%Y/%m/%d %H:%i') as end_date from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_passport ON tbl_product.product_id = tbl_product_passport.product_id where del_flg=0 ";

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("登録商品一覧");
$template->admin_comment("登録商品を管理します。");

$sidemenu_html = '<ul>';
if ($nichibenren_flg){
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">会場研修*</a></li>
<li><a href="./../product/index.php" style="font-size:13px">eラーニング*</a></li>
<li><a href="./../product_ethics/index.php" style="font-size:13px">倫理代替措置研修*</a></li>
';
//<li class="selected"><a href="./../product_passport/index.php" style="font-size:13px">パスポート*</a></li>
} else {
$sidemenu_html.= '
<li><a href="./../product_live/index.php" style="font-size:13px">自会主催研修</a></li>
<li><a href="./../product_live_branch/index.php" style="font-size:10px">日弁連主催研修・他会主催研修</a></li>
';
}
$sidemenu_html.= '</ul>';
$template->admin_sidemenu($sidemenu_html);

//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('page', $page);
$template->assign('search_product_name', $search_product_name);

$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

$template->assign('page_name', 'product');
$template->admin_layout('product_passport/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
