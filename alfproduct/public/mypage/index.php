<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = 'マイページ';

if (!st_login_check()){
	header("Location: /login/login.php");
	exit;
}

$objDbConnect = new DbConnect();

// 代替措置研修許可の場合は問題の商品IDを取得
$ethic_product_id = '';
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	$ethic_product_id = get_ethic_product_id();
	
	//$objDbConnect = new DbConnect();
	//$objDbConnect->close();
}

// パスポート価格の取得
$passport_price = get_passport_price($objDbConnect, $_SESSION['user']['year']);

$objDbConnect->close();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('ethic_product_id', $ethic_product_id);
$template->assign('passport_price', $passport_price);

$template->layout_noside('mypage/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>