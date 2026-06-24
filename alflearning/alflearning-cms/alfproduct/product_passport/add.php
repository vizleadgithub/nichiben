<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
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
$template->admin_title("商品登録");
$template->admin_comment("商品を登録します。");

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

$template->admin_sidemenu($sidemenu_html);
//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
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
$template->assign('page_name', 'product');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// パスポート対象配列
$arr_passport_target = get_mtb_passport_target_checkbox();
$template->assign('arr_passport_target', $arr_passport_target);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

// 初期表示
if(!isset($_POST['act'])){
	$arr_input = array(
		'product_name' => "",
		'price' => "",
		'memo' => "",
		'passport_target' => ""
	);
	$template->assign('arr_input', $arr_input);

	
	$template->admin_layout('product_passport/add.tpl');
	
// 初期表示以外
} else {
	//++++++++++入力値取得++++++++++++++++++++++++++++++++++++++++++++++++++
	$arr_input = array(
		'product_name' => $_POST["product_name"],
		'price' => $_POST["price"],
		'memo' => $_POST["memo"],
		'passport_target' => $_POST["passport_target"]
	);
	
	if( isset($_POST["thumbnail"]) && $_POST["thumbnail"] != '' ){
		$arr_input["thumbnail"] = $_POST["thumbnail"];
	} elseif(isset($_POST["hid_thumbnail"]) && $_POST["hid_thumbnail"] != '' ){
		$arr_input["thumbnail"] = $_POST["hid_thumbnail"];
	} else {
		$arr_input["thumbnail"] = '';
	}
	
	for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
		$arr_input["related_products$i"] = $_POST["related_products$i"];
		$arr_input["related_products$i".'_name'] = $_POST["related_products$i".'_name'];
	}
	
	// 修正時の対象ID
	if (isset($_POST['mid'])){
		$arr_input['mid'] = $_POST['mid'];
	}
	
	$template->assign('arr_input', $arr_input);
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	// 処理分岐
	switch($_POST['act']){
		// 確認
		case 'confirm':
			$err_msg = err_check($template, $arr_input);
			
			// 入力エラーなし
			if(empty($err_msg)){
				$template->admin_layout('product_passport/add_confirm.tpl');
				
			// 入力エラーあり
			} else {
				$template->assign('err_style', 'style="background-color:red;"');
				$template->admin_layout('product_passport/add.tpl');
			}
			break;
			
		// 完了
		case 'complete':
			$err_flag = 0;
			$err_msg = err_check($template, $arr_input);
			// 改竄なし
			if(empty($err_msg)){
				// トランザクション開始
				$objDbConnect->tran_begin();
				
				// 商品修正
				if(isset($arr_input["mid"])){
					$sql = "UPDATE";
					$sql.= "  tbl_product";
					$sql.= " SET";
					$sql.= "  product_type = '2',";
					$sql.= "  product_name = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_name"])."',";
					$sql.= "  price = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["price"])."',";
					$sql.= "  open_period = '0',";
					$sql.= "  thumbnail = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail"])."',";
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= "  related_products$i = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["related_products$i"])."',";
					}
					$sql.= "  memo = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo"])."',";
					$sql.= "  update_date = '".date('Y-m-d H:i:s')."'";
					$sql.= " WHERE";
					$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
					$ret = $objDbConnect->execute($sql);
					if(!$ret){
						$err_flag = 1;
					} else {
						// tbl_product_addのデータ更新
						//$sql = "UPDATE";
						//$sql.= "  tbl_product_add";
						//$sql.= " SET";
						//$sql.= "  all_contents_download = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["all_contents_download"])."',";
						//$sql.= "  all_contents_download_before = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["all_contents_download_before"])."'";
						//$sql.= " WHERE";
						//$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
						//$ret = $objDbConnect->execute($sql);
						//if(!$ret){
						//	$err_flag = 1;
						//} else {
							// tbl_product_passportにデータ登録
							$str_passport_target = '';
							if ($arr_input["passport_target"] != ''){
								$str_passport_target = '|';
								foreach ($arr_input["passport_target"] as $val){
									$str_passport_target.= $val.'|';
								}
							}
							
							$sql = "UPDATE";
							$sql.= "  tbl_product_passport";
							$sql.= " SET";
							$sql.= "  passport_target = '".mysqli_real_escape_string($objDbConnect->connect,$str_passport_target)."'";
							$sql.= " WHERE";
							$sql.= "  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["mid"])."'";
							$ret = $objDbConnect->execute($sql);
							if(!$ret){
								$err_flag = 1;
							}
						//}
						
					}
					
				// 商品新規登録
				} else {
					$sql = "INSERT INTO tbl_product";
					$sql.= " (";
					$sql.= "  product_type,";
					$sql.= "  product_name,";
					$sql.= "  price,";
					$sql.= "  open_period,";
					$sql.= "  thumbnail,";
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= " related_products$i,";
					}
					$sql.= "  memo,";
					$sql.= "  regist_date";
					$sql.= " )";
					$sql.= " VALUES";
					$sql.= " (";
					$sql.= "  '2',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["product_name"])."',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["price"])."',";
					$sql.= "  '0',";
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["thumbnail"])."',";
					for($i=1; $i<=MAX_RELATED_PRODUCTS; $i++){
						$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["related_products$i"])."',";
					}
					$sql.= "  '".mysqli_real_escape_string($objDbConnect->connect,$arr_input["memo"])."',";
					$sql.= "  '".date('Y-m-d H:i:s')."'";
					$sql.= " )";
					$ret = $objDbConnect->execute($sql);
					if(!$ret){
						$err_flag = 1;
					} else {
						$product_id = mysqli_insert_id($objDbConnect->connect);
						
						// tbl_product_addにデータ登録
						$sql = "INSERT INTO tbl_product_add";
						$sql.= "  (";
						$sql.= "   product_id,";
						$sql.= "   product_type_add";
						$sql.= "  )";
						$sql.= " VALUES";
						$sql.= "  (";
						$sql.= "   '".$product_id."',";
						$sql.= "   '4'";
						$sql.= "  )";
						$ret = $objDbConnect->execute($sql);
						if(!$ret){
							$err_flag = 1;
						} else {
							// tbl_product_passportにデータ登録
							$str_passport_target = '';
							if ($arr_input["passport_target"] != ''){
								$str_passport_target = '|';
								foreach ($arr_input["passport_target"] as $val){
									$str_passport_target.= $val.'|';
								}
							}
							
							$sql = "INSERT INTO tbl_product_passport";
							$sql.= "  (";
							$sql.= "   product_id,";
							$sql.= "   passport_target";
							$sql.= "  )";
							$sql.= " VALUES";
							$sql.= "  (";
							$sql.= "   '".$product_id."',";
							$sql.= "   '".mysqli_real_escape_string($objDbConnect->connect,$str_passport_target)."'";
							$sql.= "  )";
							$ret = $objDbConnect->execute($sql);
							if(!$ret){
								$err_flag = 1;
							}
						}
					}
				}
				
			// 改竄あり
			} else {
				$err_flag = 1;
			}
			
			if ($err_flag){
				$objDbConnect->rollback();
			} else {
				$objDbConnect->commit();
			}
			
			$template->assign('err_flag', $err_flag);
			$template->admin_layout('product_passport/add_complete.tpl');
			break;
			
		// 修正初期表示
		case 'edit':
			$template->admin_layout('product_passport/add.tpl');
			break;
		// 戻る
		case 'back':
			
			$template->admin_layout('product_passport/add.tpl');
			break;
		// サムネイル画像アップロード
		case 'upload':
			$err_msg = err_check($template, $arr_input);
			$template->admin_layout('product_passport/add.tpl');
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
		if(!isset($_POST['fileupload'])){
			if(cmCheckInput($arr_input['product_name'], 'CK_KARA')){
				$err_msg['product_name'] = '商品名は必須です。';
			}
			if(cmCheckInput($arr_input['price'], 'CK_KARA')){
				$err_msg['price'] = '商品価格は必須です。';
			} else {
				if(cmCheckInput($arr_input['price'], 'CK_NUM')){
					$err_msg['price'] = '商品価格は半角数字で入力してください。';
				}
			}
			if(cmCheckInput($arr_input['passport_target'], 'CK_KARA')){
				$err_msg['passport_target'] = '対象者は必須です。';
			}
		}
	}
	$template->assign('err_msg', $err_msg);
	
	return $err_msg;
}
?>
