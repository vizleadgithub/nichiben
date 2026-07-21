<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$template = new Template();
$objDbConnect = new DbConnect();
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
$template->admin_title("商品詳細");
$template->admin_comment("商品を管理します。");

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
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
}

$template->admin_school($arr_session["cms_master.login.school_name"]);

$template->assign('section_open_period', MAX_OPEN_PERIOD + 1);
$template->assign('section_contents', MAX_CONTENTS + 1);
$template->assign('section_contents_download', MAX_CONTENTS_DOWNLOAD + 1);
$template->assign('section_related_products', MAX_RELATED_PRODUCTS + 1);
$template->assign('section_free_html_area', MAX_FREE_HTML_AREA + 1);
$template->assign('section_contents_free_time', MAX_CONTENTS_FREE_TIME + 1);
$template->assign('thumbnail_path', THUMBNAIL_PATH);
$template->assign('contents_thumbnail_path', CONTENTS_THUMBNAIL_PATH);
$template->assign('document_path', DOCUMENT_PATH);
$template->assign('page_name', 'product');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// パスポート対象配列
$arr_passport_target = get_mtb_passport_target_checkbox();
$template->assign('arr_passport_target', $arr_passport_target);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mid = '';
if(isset($_GET["mid"])){
	$mid = intval($_GET["mid"]);
} elseif(isset($_POST["mid"])){
	$mid = intval($_POST["mid"]);
}
$template->assign('mid', $mid);

// 初期表示
if(!isset($_POST['act'])){
	$arr_input = array();
	$sql = "select * from tbl_product INNER JOIN tbl_product_add ON tbl_product.product_id = tbl_product_add.product_id INNER JOIN tbl_product_passport ON tbl_product.product_id = tbl_product_passport.product_id where tbl_product.product_id='".$mid."'";
	$arr_input = $objDbConnect->query_fetch($sql);
	if ($arr_input){
		// 関連商品名の取得
		for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
			if ($arr_input["related_products$i"] != ''){
				$sql = "select product_name from tbl_product where product_id='".$arr_input["related_products$i"]."'";
				$ret2 = $objDbConnect->query_fetch($sql);
				if ($ret2){
					$arr_input["related_products".$i."_name"] = $ret2["product_name"];
				}
			}
		}
		// パスポート対象
		if ($arr_input["passport_target"] != ''){
			$arr_input["passport_target"] = explode('|', trim($arr_input["passport_target"], '|'));
		}
	}
	
	$template->assign('arr_input', $arr_input);
	$template->assign('csrf_token', csrf_token_get());
	$template->admin_layout('product_passport/info.tpl');

// 削除
} elseif($_POST['act'] == 'delete') {
	csrf_token_verify();
	$sql = "update tbl_product set del_flg = '1' where product_id = '".mysqli_real_escape_string($objDbConnect->connect,$mid)."'";
	$objDbConnect->execute($sql);
	
	header('Location: index.php');
	exit;
}
?>
