<?php
header('Etag: ' . date("YmdHis"));
header('Expires: Sun, 26 Nov 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT']; 

$isPad = false;
if(preg_match("/iPad/", $agent)){//iPad
	$isPad = true;
}
$isApple = false;
if(preg_match("/iPhone/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPad/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPod/", $agent)){//iPhone
	$isApple = true;
}
$isAndroid = false;
if(preg_match("/Android/", $agent)){//Android
	$isAndroid = true;
}
$isAndroidTablet = false;
if(preg_match("/Android/", $agent)){
	if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
		$isAndroidTablet = true;
	}elseif(preg_match("/mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Tablet/", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
csrf_token_verify();
$_SESSION['wp_page_head_title'] = '講座詳細';
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (!isset($_POST['pid'])){
	$objDbConnect->close();
	echo '不正なアクセス';
	exit();
}
$pid = $_POST['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	echo '不正なアクセス';
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];

$back_url = get_back_url();

// ログインチェック
$st_login_check = st_login_check();

// 商品詳細の取得
$product_list = array();
$where = " WHERE tbl_product.del_flg = '0'";
$where.= " AND tbl_product.product_id = '$pid'";
$where.= " AND ( (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date<='".date("Y-m-d")."' and tbl_product.end_date IS NULL)  OR  (tbl_product.start_date IS NULL and tbl_product.end_date>='".date("Y-m-d")."')  OR  (tbl_product.start_date IS NULL and tbl_product.end_date IS NULL)  ) ";

$sql = "SELECT";
$sql.= "  tbl_product.*,";
$sql.= "  tbl_product_add.all_contents_download,";
$sql.= "  tbl_product_add.all_contents_download_before";
$sql.= " FROM";
$sql.= "  tbl_product";
$sql.= "    INNER JOIN";
$sql.= "  tbl_product_add";
$sql.= "      ON tbl_product.product_id = tbl_product_add.product_id";

$product_list = $objDbConnect->query_fetch($sql.$where);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('product_list', $product_list);
$template->assign('pid', $pid);
$template->assign('user_id', $user_id);
$template->assign('THUMBNAIL_PATH', THUMBNAIL_PATH);
$template->assign('CONTENTS_THUMBNAIL_PATH', CONTENTS_THUMBNAIL_PATH);
$template->assign('section_max_related_products', MAX_RELATED_PRODUCTS + 1);
$template->assign('section_max_contents', MAX_CONTENTS + 1);
$template->assign('section_max_contents_download', MAX_CONTENTS_DOWNLOAD + 1);
$template->assign('section_max_product_sub_image', MAX_PRODUCT_SUB_IMAGE + 1);
$template->assign('back_url', $back_url);
$template->assign('download_flg', $download_flg);
$template->assign('st_login_check', $st_login_check);
$template->assign('isApple', $isApple);
$template->assign('isAndroid', $isAndroid);
$template->assign('sid', session_id());
$template->assign('csrf_token', csrf_token_get());

$GLOBALS['meta_description'] = '';
$GLOBALS['meta_keywords'] = get_product_gategory_meta_keywords($objDbConnect, $pid);

$template->layout_non('product/document.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>