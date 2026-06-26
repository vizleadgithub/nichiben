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
$template->admin_title("商品登録");
$template->admin_comment("商品を登録します。");
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
$template->assign('page_name', 'product');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// 初期表示
if(!isset($_POST['action'])){
	$product_category_list = get_product_category();
	$template->assign('productcategory_list', $product_category_list);
	$template->admin_layout('product/add.tpl');
	
// 初期表示以外
} else {
	//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
	$arr_input = array(
		'product_type' => $_POST["product_type"],
		'product_name' => $_POST["product_name"],
		'product_code' => $_POST["product_code"],
		'price' => $_POST["price"],
		'discount_code' => $_POST["discount_code"],
		'start_date' => $_POST["start_date"],
		'end_date' => $_POST["end_date"],
		'open_period' => $_POST["open_period"],
		'memo' => $_POST["memo"],
		'term_id' => $_POST["term_id"],
	);
	
	if($_POST["thumbnail"] != ''){
		$arr_input["thumbnail"] = $_POST["thumbnail"];
	} elseif(isset($_POST["hid_thumbnail"])){
		$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
	} else {
		$arr_input["thumbnail"] = '';
	}
	
	for($i=1; $i<=MAX_CONTENTS; $i++){
		if($_POST["contents_thumbnail$i"] != ''){
			$arr_input["contents_thumbnail$i"] = $_POST["contents_thumbnail$i"];
		} elseif(isset($_POST["hid_contents_thumbnail$i"])){
			$arr_input["contents_thumbnail$i"] = $_POST["hid_contents_thumbnail$i"];
		} else {
			$arr_input["contents_thumbnail$i"] = '';
		}
		$arr_input["contents_contents$i"] = $_POST["contents_contents$i"];
		$arr_input["contents_contents$i".'_name'] = $_POST["contents_contents$i".'_name'];
		$arr_input["contents_free_time$i"] = $_POST["contents_free_time$i"];
		$arr_input["contents_start_date$i"] = $_POST["contents_start_date$i"];
		$arr_input["contents_end_date$i"] = $_POST["contents_end_date$i"];
		$arr_input["contents_memo$i"] = $_POST["contents_memo$i"];
		$arr_input["contents_teacher$i"] = $_POST["contents_teacher$i"];
		
		for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
			if($_POST['contents_download'.$i.'_'.$j] != ''){
				$arr_input['contents_download'.$i.'_'.$j] = $_POST['contents_download'.$i.'_'.$j];
				$arr_input['contents_download_before'.$i.'_'.$j] = $_POST['contents_download_before'.$i.'_'.$j];
			} elseif(isset($_POST['hid_contents_download'.$i.'_'.$j])){
				$arr_input['contents_download'.$i.'_'.$j] = $_POST['hid_contents_download'.$i.'_'.$j];
				$arr_input['contents_download_before'.$i.'_'.$j] = $_POST['contents_download_before'.$i.'_'.$j];
			} else {
				$arr_input['contents_download'.$i.'_'.$j] = '';
			}
		}
	}
	for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
		$arr_input["related_products$i"] = $_POST["related_products$i"];
		$arr_input["related_products$i".'_name'] = $_POST["related_products$i".'_name'];
	}
	for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
		$arr_input["free_html_area$i"] = stripslashes($_POST["free_html_area$i"]);
	}
	// カテゴリ
	$term_id = '';
	$arr_input["term_id"] = $_POST["term_id"];
	$arr_input["arr_term_id"] = $_POST["arr_term_id"];
	if(!is_null($arr_input["arr_term_id"]) && is_array($arr_input["arr_term_id"])){
		$cnt = 1;
		foreach($arr_input["arr_term_id"] as $val){
			$str_term_id .= $cnt.','.get_product_category_name_html($val).'<br />';
			$term_id .= $val.',';
			$cnt++;
		}
		$arr_input['term_id'] = rtrim($term_id, ',');
		$arr_input['str_term_id'] = rtrim($str_term_id, ',');
	}
	// 修正時の対象ID
	if (isset($_POST['mid'])){
		$arr_input['mid'] = $_POST['mid'];
	}
	
	$template->assign('arr_input', $arr_input);
	$template->assign('arr_term_id', $arr_input["arr_term_id"]);
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	// 処理分岐
	switch($_POST['action']){
		// 確認
		case 'confirm':
			$err_msg = err_check($template, $arr_input);
			
			// 入力エラーなし
			if(empty($err_msg)){
				$template->admin_layout('product/add_confirm.tpl');
				
			// 入力エラーあり
			} else {
				$template->assign('err_style', 'style="background-color:red;"');
				$template->assign('productcategory_list', get_product_category());
				$template->admin_layout('product/add.tpl');
			}
			break;
			
		// 完了
		case 'complete':
			$err_flag = 0;
			$err_msg = err_check($template, $arr_input);
			// 改竄なし
			if(empty($err_msg)){
				// 商品修正
				if(isset($arr_input["mid"])){
					$sql = "UPDATE";
					$sql.= "  tbl_product";
					$sql.= " SET";
					$sql.= "  product_type = '".$arr_input["product_type"]."',";
					$sql.= "  product_name = '".$arr_input["product_name"]."',";
					$sql.= "  product_code = '".$arr_input["product_code"]."',";
					$sql.= "  price = '".$arr_input["price"]."',";
					$sql.= "  discount_code = '".$arr_input["discount_code"]."',";
					$sql.= "  start_date = '".$arr_input["start_date"]."',";
					$sql.= "  end_date = '".$arr_input["end_date"]."',";
					$sql.= "  open_period = '".$arr_input["open_period"]."',";
					$sql.= "  thumbnail = '".$arr_input["thumbnail"]."',";
					$sql.= "  memo = '".$arr_input["memo"]."',";
					for($i=1; $i<=MAX_CONTENTS; $i++){
						if($arr_input["contents_thumbnail$i"] != ''
						&& $arr_input["contents_contents$i"] != ''
						&& $arr_input["contents_free_time$i"] != ''){
							$sql.= "  contents_thumbnail$i = '".$arr_input["contents_thumbnail$i"]."',";
							$sql.= "  contents_contents$i = '".$arr_input["contents_contents$i"]."',";
							$sql.= "  contents_free_time$i = '".$arr_input["contents_free_time$i"]."',";
							$sql.= "  contents_start_date$i = '".$arr_input["contents_start_date$i"]."',";
							$sql.= "  contents_end_date$i = '".$arr_input["contents_end_date$i"]."',";
							$sql.= "  contents_memo$i = '".$arr_input["contents_memo$i"]."',";
							$sql.= "  contents_teacher$i = '".$arr_input["contents_teacher$i"]."',";
							for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
								$sql.= "  contents_download".$i."_".$j." = '".$arr_input["contents_download".$i."_".$j]."',";
								$sql.= "  contents_download_before".$i."_".$j." = '".$arr_input["contents_download_before".$i."_".$j]."',";
							}
						}
					}
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= "  related_products$i = '".$arr_input["related_products$i"]."',";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= "  free_html_area$i = '".$arr_input["free_html_area$i"]."',";
					}
					$sql.= "  term_id = '".$arr_input["term_id"]."'";
					$sql.= " WHERE";
					$sql.= "  product_id = '".$arr_input["mid"]."'";
					
				// 商品新規登録
				} else {
					$sql = "INSERT INTO tbl_product";
					$sql.= " (";
					$sql.= "  product_type,";
					$sql.= "  product_name,";
					$sql.= "  product_code,";
					$sql.= "  price,";
					$sql.= "  discount_code,";
					$sql.= "  start_date,";
					$sql.= "  end_date,";
					$sql.= "  open_period,";
					$sql.= "  thumbnail,";
					$sql.= "  memo,";
					for($i=1; $i<=MAX_CONTENTS; $i++){
						if($arr_input["contents_thumbnail$i"] != ''
						&& $arr_input["contents_contents$i"] != ''
						&& $arr_input["contents_free_time$i"] != ''){
							$sql.= "  contents_thumbnail$i,";
							$sql.= "  contents_contents$i,";
							$sql.= "  contents_free_time$i,";
							$sql.= "  contents_start_date$i,";
							$sql.= "  contents_end_date$i,";
							$sql.= "  contents_memo$i,";
							$sql.= "  contents_teacher$i,";
							for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
								$sql.= " contents_download".$i."_".$j.",";
								$sql.= " contents_download_before".$i."_".$j.",";
							}
						}
					}
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= " related_products$i,";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= " free_html_area$i,";
					}
					$sql.= "  term_id";
					$sql.= " )";
					$sql.= " VALUES";
					$sql.= " (";
					$sql.= "  '".$arr_input["product_type"]."',";
					$sql.= "  '".$arr_input["product_name"]."',";
					$sql.= "  '".$arr_input["product_code"]."',";
					$sql.= "  '".$arr_input["price"]."',";
					$sql.= "  '".$arr_input["discount_code"]."',";
					$sql.= "  '".$arr_input["start_date"]."',";
					$sql.= "  '".$arr_input["end_date"]."',";
					$sql.= "  '".$arr_input["open_period"]."',";
					$sql.= "  '".$arr_input["thumbnail"]."',";
					$sql.= "  '".$arr_input["memo"]."',";
					for($i=1; $i<=MAX_CONTENTS; $i++){
						if($arr_input["contents_thumbnail$i"] != ''
						&& $arr_input["contents_contents$i"] != ''
						&& $arr_input["contents_free_time$i"] != ''){
							$sql.= "  '".$arr_input["contents_thumbnail$i"]."',";
							$sql.= "  '".$arr_input["contents_contents$i"]."',";
							$sql.= "  '".$arr_input["contents_free_time$i"]."',";
							$sql.= "  '".$arr_input["contents_start_date$i"]."',";
							$sql.= "  '".$arr_input["contents_end_date$i"]."',";
							$sql.= "  '".$arr_input["contents_memo$i"]."',";
							$sql.= "  '".$arr_input["contents_teacher$i"]."',";
							for($j=1; $j<=MAX_CONTENTS_DOWNLOAD; $j++){
								$sql.= "  '".$arr_input['contents_download'.$i.'_'.$j]."',";
								$sql.= "  '".$arr_input['contents_download_before'.$i.'_'.$j]."',";
							}
						}
					}
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= "  '".$arr_input["related_products$i"]."',";
					}
					for($i=1; $i<=MAX_FREE_HTML_AREA; $i++){
						$sql.= "  '".$arr_input["free_html_area$i"]."',";
					}
					$sql.= "  '".$arr_input["term_id"]."'";
					$sql.= " )";
				}
				
				$ret = $objDbConnect->execute($sql);
				if(!$ret){
					$err_flag = 1;
				}
				
			// 改竄あり
			} else {
				$err_flag = 1;
			}
			
			$template->assign('err_flag', $err_flag);
			$template->admin_layout('product/add_complete.tpl');
			break;
			
		// 修正初期表示
		case 'edit':
			$template->assign('productcategory_list', get_product_category());
			$template->admin_layout('product/add.tpl');
			break;
			
		// 戻る
		case 'back':
			$template->assign('productcategory_list', get_product_category());
			$template->admin_layout('product/add.tpl');
			break;
			
		// サムネイル画像アップロード
		case 'upload':
			$template->assign('productcategory_list', get_product_category());
			$err_msg = err_check($template, $arr_input);
			$template->admin_layout('product/add.tpl');
			break;
			
		default:
	}
	
}

function err_check($template, $arr_input){
	$err_msg = array();
	// ファイルアップロード時のエラー
	if(isset($_POST['err_msg'])){
		$err_msg['file_err_msg'] = $_POST['err_msg'];
		
	// 確認時のエラー
	} else {
		if(cmCheckInput($arr_input['product_type'], 'CK_NUM')){
			$err_msg['product_type'] = '商品種別は必須です。';
		}
		if(cmCheckInput($arr_input['product_name'], 'CK_KARA')){
			$err_msg['product_name'] = '商品名は必須です。';
		}
		if(cmCheckInput($arr_input['product_code'], 'CK_KARA')){
			$err_msg['product_code'] = '商品コードは必須です。';
		}
		if(cmCheckInput($arr_input['price'], 'CK_KARA')){
			$err_msg['product_code'] = '商品価格は必須です。';
		} else {
			if(cmCheckInput($arr_input['price'], 'CK_NUM')){
				$err_msg['price'] = '商品価格は半角数字で入力してください。';
			}
		}
		if(cmCheckInput($arr_input['term_id'], 'CK_KARA')){
			$err_msg['term_id'] = '商品カテゴリは必須です。';
		}
		if(cmCheckInput($arr_input['open_period'], 'CK_KARA')){
			$err_msg['open_period'] = '購入後公開期間日数は必須です。';
		}
		// コンテンツは以下の3種類が入力された場合に登録
		// ・コンテンツ○サムネイル画像
		// ・コンテンツ○コンテンツ
		// ・コンテンツ○無料公開範囲(秒)
		// ※コンテンツ1のみ登録必須項目
		if(cmCheckInput($arr_input['contents_thumbnail1'], 'CK_KARA')){
			$err_msg['contents_thumbnail1'] = 'コンテンツ1サムネイルは必須です。';
		}
		if(cmCheckInput($arr_input['contents_contents1'], 'CK_KARA')){
			$err_msg['contents_contents1'] = 'コンテンツ1コンテンツは必須です。';
		}
		if(cmCheckInput($arr_input['contents_free_time1'], 'CK_NUM')){
			$err_msg['contents_free_time1'] = 'コンテンツ1無料公開範囲(秒)は必須です。';
		}
		
		if(isset($arr_input['mid'])){
			// 商品修正時の商品IDのエラーチェック実装する。
		}
	}
	$template->assign('err_msg', $err_msg);
	
	return $err_msg;
}
?>
