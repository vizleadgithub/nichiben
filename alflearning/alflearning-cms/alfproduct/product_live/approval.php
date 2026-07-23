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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["product_live_approval.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["product_live_approval.page"]) && !empty($_SESSION["product_live_approval.page"]) ){
	$page = $_SESSION["product_live_approval.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["product_live_approval.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "
SELECT
  COUNT(tbl_product.product_id) AS c
FROM
  tbl_product
    INNER JOIN
  tbl_product_add
      ON tbl_product.product_id = tbl_product_add.product_id
    INNER JOIN
  tbl_product_live_training
      ON tbl_product.product_id = tbl_product_live_training.product_id
WHERE
  tbl_product.del_flg = 0
  AND tbl_product_live_training.ethic_flg = 1
  AND tbl_product_live_training.app_flg = 0
";

$all_count = 0;
$ret = $objDbConnect->query_fetch($sql);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY tbl_product.product_id ASC ";

$sql = "
SELECT
  tbl_product.product_id,
  tbl_product.product_name,
  DATE_FORMAT(tbl_product.start_date, '%Y/%m/%d %H:%i') AS start_date,
  DATE_FORMAT(tbl_product.end_date, '%Y/%m/%d %H:%i') AS end_date
FROM
  tbl_product
    INNER JOIN
  tbl_product_add
      ON tbl_product.product_id = tbl_product_add.product_id
    INNER JOIN
  tbl_product_live_training
      ON tbl_product.product_id = tbl_product_live_training.product_id
WHERE
  tbl_product.del_flg = 0
  AND tbl_product_live_training.ethic_flg = 1
  AND tbl_product_live_training.app_flg = 0
";

$ret = $objDbConnect->query_fetch_arr($sql.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("承認待ち一覧");
$template->admin_comment("承認待ち一覧を管理します。");

$sidemenu_html = '<ul>';
if ($nichibenren_flg){
$sidemenu_html.= '
<li class="selected"><a href="./../product_live/index.php" style="font-size:13px">会場研修*</a></li>
<li><a href="./../product/index.php" style="font-size:13px">eラーニング*</a></li>
<li><a href="./../product_ethics/index.php" style="font-size:13px">倫理代替措置研修*</a></li>
';
//<li><a href="./../product_passport/index.php" style="font-size:13px">パスポート*</a></li>
} else {
$sidemenu_html.= '
<li class="selected"><a href="./../product_live/index.php" style="font-size:13px">自会主催研修</a></li>
<li><a href="./../product_live_branch/index.php" style="font-size:10px">日弁連主催研修・他会主催研修</a></li>
';
}
$sidemenu_html.= '</ul>';
$template->admin_sidemenu($sidemenu_html);

$temp_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
$temp_bar_association_name = "管理者";
if( $temp_bar_association_id>1 ){
	$sql  = '';
	$sql .= "SELECT  id ";
	$sql .= "       ,(CASE WHEN id = 1 THEN name ELSE concat(name, '弁護士会') END) AS name ";
	$sql .= " FROM mtb_bar_association ";
	$sql .= " WHERE id = ".intval($temp_bar_association_id)." ";
	$headret = $objDbConnect->query_fetch($sql);
	if( isset($headret["name"]) ){
		$temp_bar_association_name = '【'.$headret["name"].'】';
	}
}
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]);
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('page', $page);

$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('all_count', $all_count);
$template->assign('list_start', $objAdminPager->getOffsetStart());
$template->assign('list_end', $objAdminPager->getOffsetEnd());

$template->assign('page_name', 'product');
$template->admin_layout('product_live/approval.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
