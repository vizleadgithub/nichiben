<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$template = new Template();
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("商品詳細");
$template->admin_comment("商品を管理します。");
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
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
$mid = '';
if(isset($_REQUEST["mid"])){
	$mid = $_REQUEST["mid"];
}
$template->assign('mid', $mid);

// 初期表示
if(!isset($_POST['action'])){
	$arr_input = array();
	$sql = "select * from tbl_product where product_id='".$mid."'";
	$arr_input = $objDbConnect->query_fetch($sql);
	if ($arr_input){
		// コンテンツ名の取得
		for ($i=1; $i<MAX_CONTENTS; $i++){
			if ($arr_input["contents_contents$i"] != ''){
				$sql = "select video_logic_name from video where video_id='".$arr_input["contents_contents$i"]."'";
				$ret1 = $objDbConnect->query_fetch($sql);
				if ($ret1){
					$arr_input["contents_contents$i".'_name'] = $ret1["video_logic_name"];
				}
			}
		}
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
		// カテゴリ名の取得
		$term_name = '';
		if (strpos($arr_input['term_id'], ',') !== false){
			$arr_term_id = explode(',', $arr_input['term_id']);
			$cnt = 1;
			foreach($arr_term_id as $val){
				$term_name.= $cnt.','.get_product_category_name_html($val).'<br />';
				$cnt++;
			}
			
		} else {
			$arr_term_id[] = $arr_input['term_id'];
			$term_name = get_product_category_name_html($arr_input['term_id']);
		}
	}
	
	$template->assign('arr_input', $arr_input);
	$template->assign('arr_term_id', $arr_term_id);
	$template->assign('term_name', $term_name);
	$template->admin_layout('product/info.tpl');
	
// 削除
} elseif($_POST['action'] == 'delete') {
	$sql = "update tbl_product set del_flg = '1' where product_id = '$mid'";
	$objDbConnect->execute($sql);
	
	header('Location: index.php');
	exit;
}
?>
