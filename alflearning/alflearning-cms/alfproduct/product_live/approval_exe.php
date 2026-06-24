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


$login_bar_association_id = 1;  // テスト用


if ($login_bar_association_id != 1){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}

if (isset($_GET["mid"])){
	$sql = "UPDATE tbl_product_live_training SET app_flg = 1 WHERE product_id = '".$_GET["mid"]."'";
	$objDbConnect->execute($sql);
}

header('Location: approval.php');
exit;
?>
