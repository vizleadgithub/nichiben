<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include("/srv/alfproduct/module/module.php");
csrf_token_verify();
ini_set('display_errors', 1);
require '/srv/alfproduct/module/vendor/autoload.php'; // mPDFのautoloadを読み込む
use Mpdf\Mpdf;
use Mpdf\Import\PdfReader;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template_file_path = '/srv/alfproduct/module/template/';
$template = 'receipt.pdf';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$_SESSION['wp_page_head_title'] = '領収書ダウンロードエラー';
if (!st_login_check()){
	header("Location: /");
	exit;
}
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$oid = "";
if( isset($_GET["oid"]) && !empty($_GET["oid"]) && is_numeric($_GET["oid"]) ){
	$oid = $_GET["oid"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 購入履歴詳細の取得＆ログインユーザーの購入履歴かチェック
$arr_buy_detail = array();
$arr_buy = array();
$total_price = 0;

$sql = "";
$sql.= "SELECT ";
$sql.= "*, ";
$sql.= "mtb_bar_association.name as bar_association_name ";
$sql.= "FROM ";
$sql.= "student ";
$sql.= "LEFT JOIN mtb_bar_association ON student.bar_association_id=mtb_bar_association.id ";
$sql.= "WHERE ";
$sql.= " student.student_id='".$_SESSION["user"]["id"]."' ";
$ret_member = $objDbConnect->query_fetch($sql);
if (!$ret_member){
	header("Location: /#1");
	exit;
}

$sql = "
SELECT 
  T1.payment_status,
  T1.price,
  T1.unit,
  T1.product_name AS product_name_TOD,
  T2.product_name AS product_name_TP,
  T2.product_code,
  tbl_product_add.product_type_add
FROM 
  tbl_order_detail AS T1 
    LEFT JOIN 
  tbl_product AS T2 
      ON T1.product_id = T2.product_id 
    LEFT JOIN 
  tbl_product_add 
      ON T2.product_id = tbl_product_add.product_id 
WHERE 
  T1.order_id = '".mysqli_real_escape_string($objDbConnect->connect, $oid)."' 
  AND T1.member_id = '".$_SESSION['user']['id']."' 
  AND T1.payment_status = 2
 ";
$res = $objDbConnect->query_fetch_arr($sql);
if ($res){
	$count = 0;
	foreach ($res as $val){
		//--------------------------------
		$arr_buy_detail[$count]['payment_status'] = $val['payment_status'];
		//--------------------------------
		if ($val['payment_status'] == 2){
			$arr_buy_detail[$count]['disp_payment_status'] = '済';
		} elseif ($val['payment_status'] == 9){
			$arr_buy_detail[$count]['disp_payment_status'] = 'キャンセル';
		} else {
			$arr_buy_detail[$count]['disp_payment_status'] = '未';
		}
		//--------------------------------
		$arr_buy_detail[$count]['price'] = $val['price'];
		//--------------------------------
		$arr_buy_detail[$count]['unit'] = $val['unit'];
		//--------------------------------
		if ($val['payment_status'] != 9){
			$total_price += $val['price'];
		}
		//--------------------------------
		if ($val['product_name_TOD'] != ''){
			$arr_buy_detail[$count]['product_name'] = $val['product_name_TOD'];
		} else if ($val['product_name_TP'] != ''){
			$arr_buy_detail[$count]['product_name'] = $val['product_name_TP'];
		} else {
			$arr_buy_detail[$count]['product_name'] = '';
		}
		//--------------------------------
		$arr_buy_detail[$count]['product_code'] = $val['product_code'];
		//--------------------------------
		$arr_buy_detail[$count]['product_type_add'] = $val['product_type_add'];
		//--------------------------------
		if ($val['product_type_add'] == 1){//商品種別(1:eラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
			$arr_buy_detail[$count]['disp_product_type_add'] = 'eラーニング';
		} elseif ($val['product_type_add'] == 2){
			$arr_buy_detail[$count]['disp_product_type_add'] = '会場研修';
		} elseif ($val['product_type_add'] == 3){
			$arr_buy_detail[$count]['disp_product_type_add'] = '代替倫理研修';
		} elseif ($val['product_type_add'] == 4){
			$arr_buy_detail[$count]['disp_product_type_add'] = 'パスポート';
		} else {
			$arr_buy_detail[$count]['disp_product_type_add'] = '';
		}
		//--------------------------------
		$count++;
	}
	
	// 購入履歴の取得
	$sql = "
	SELECT 
	  order_id, 
	  order_no, 
	  DATE_FORMAT(payment_date, '%Y/%m/%d') AS payment_date, 
	  payment_type, 
	  payment_status, 
	  receipt_flg 
	FROM 
	  tbl_order 
	WHERE 
	  order_id = '".mysqli_real_escape_string($objDbConnect->connect, $oid)."' 
	  AND receipt_flg=0 
	  AND payment_status=2
	 ";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$arr_buy['order_id'] = $res['order_id'];
		$arr_buy['order_no'] = $res['order_no'];
		$arr_buy['payment_date'] = $res['payment_date'];
		$arr_buy['payment_type'] = $res['payment_type'];
		$arr_buy['disp_payment_type'] = get_str_payment_type($res['payment_type']);
		$arr_buy['payment_status'] = $res['payment_status'];
		$arr_buy['disp_payment_status'] = get_str_payment_status($res['payment_status']);
		$arr_buy['receipt_flg'] = $res['receipt_flg'];
		$arr_buy['disp_receipt_flg'] = get_str_receipt_flg($res['receipt_flg']);
	}
	
} else {
	header("Location: /#2");
	exit;
}

//var_dump($arr_buy_detail);
//exit;

if (file_exists($template_file_path . $template)){
	// mPDFインスタンス作成
	$mpdf = new Mpdf([
		'mode' => 'ja', // 日本語対応
		'format' => 'A4',
		'tempDir' => __DIR__ . '/tmp', // 一時フォルダを指定（Amazon Linuxなどで必要）
	]);

	// PDFテンプレートを読み込む
	$reader = new PdfReader($template_path . $template);
	$mpdf->SetSourceFile($reader);
	
	$page_no = 1; // ページNo. 1ページ10商品

	// 新規ページを追加し、テンプレートの1ページ目を適用
	$mpdf->AddPage();
	$tplId = $mpdf->ImportPage(1);
	$mpdf->UseTemplate($tplId);

	// -----------------
	// 固定部分書き込み
	// -----------------
	// 発行年月日
	$publish_date = date('Y/m/d');
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(149, 44);
	//$mpdf->Write(0, $publish_date);
	$mpdf->Write(0, $publish_date);
	
	// 宛名(フリーテキスト)
	$atena_free = '';
	if (isset($_POST['atena'])){
		if ($_POST['atena'] != ''){
			$atena_free = $_POST['atena'];
			$mpdf->SetFont('ipaexg', '', 16);
			$mpdf->SetXY(30, 42);
			$mpdf->Write(0, $atena_free);
		}
	}
	
	// 宛名(氏名)
	$atena_name = $_SESSION['user']['name'];
	$mpdf->SetFont('ipaexg', '', 16);
	$mpdf->SetXY(30, 50);
	$mpdf->Write(0, $atena_name);

/*
	// 宛名
	$atena = $_SESSION['user']['name'];
	if (isset($_POST['atena']) && isset($_POST['atena_ichi'])){
		if ($_POST['atena'] != '' && $_POST['atena_ichi'] != ''){
			if ($_POST['atena_ichi'] == '1'){
				$atena = $_POST['atena'] . ' ' . $atena;
			} else if ($_POST['atena_ichi'] == '2'){
				$atena = $atena . ' ' . $_POST['atena'];
			}
		}
	}
	$mpdf->SetFont('ipaexg', '', 1);
	$mpdf->SetXY(10, 25);
	$mpdf->Write(0, $atena);
*/

	// 領収書番号
	$ryousyu_no = $arr_buy['order_id'] . '-' . $page_no;
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(48, 193);
	$mpdf->Write(0, $ryousyu_no);
	
	// 注文番号
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(44, 198);
	$mpdf->Write(0, $arr_buy['order_no']);
	
	// 登録番号
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(44, 203);
	$mpdf->Write(0, $ret_member['lawyer_number']);
	
	// 所属弁護士会
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(53, 207);
	$mpdf->Write(0, $ret_member['bar_association_name'] . '弁護士会');
	
	// 購入手続き完了日
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(57, 212);
	$mpdf->Write(0, $arr_buy['payment_date']);
	
	// 決済方法
	$mpdf->SetFont('ipaexg', '', 10);
	$mpdf->SetXY(45, 217);
	$mpdf->Write(0, $arr_buy['disp_payment_type']);
	
	// -----------------
	// 動的部分書き込み
	// -----------------
	// 商品詳細
	$product_name_x     = 38;  // 商品名x軸
	$product_price_x    = 145;  // 単価x軸
	//$product_unit_x     = 100; // 数量x軸
	$product_subtotal_x = 170; // 小計x軸
	$default_y = 131; // y軸初期値
	$total = 0; // 総計金額
	$next_page_flg = false; // 次のページがあるか判断
	$max_detail_gyo = 10; // 商品詳細の最大行数
	$count_gyo = 0;
	$count_arr_buy_detail = count($arr_buy_detail);
	for ($i=0; $i<$count_arr_buy_detail; $i++){
		$pd_y = $default_y + $count_gyo * 5;
		
		if ($next_page_flg){
			$next_page_flg = false;
			
			$page_no++;
			
			$mpdf->AddPage();
			$reader = new PdfReader($template_path . $template);
			$mpdf->SetSourceFile($reader);

			$page = $mpdf->ImportPage(1);
			$mpdf->UseTemplate($page);
			
			// 発行年月日
			$publish_date = date('Y/m/d');
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(149, 44);
			$mpdf->Write(0, $publish_date);
			
			// 宛名(フリーテキスト)
			$atena_free = '';
			if (isset($_POST['atena'])){
				if ($_POST['atena'] != ''){
					$atena_free = $_POST['atena'];
					$mpdf->SetFont('ipaexg', '', 16);
					$mpdf->SetXY(30, 42);
					$mpdf->Write(0, $atena_free);
				}
			}
			
			// 宛名(氏名)
			$atena_name = $_SESSION['user']['name'];
			$mpdf->SetFont('ipaexg', '', 16);
			$mpdf->SetXY(30, 50);
			$mpdf->Write(0, $atena_name);
			
			// 領収書番号
			$ryousyu_no = $arr_buy['order_id'] . '-' . $page_no;
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(48, 193);
			$mpdf->Write(0, $ryousyu_no);
			
			// 注文番号
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(44, 198);
			$mpdf->Write(0, $arr_buy['order_no']);
			
			// 登録番号
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(44, 203);
			$mpdf->Write(0, $ret_member['lawyer_number']);
			
			// 所属弁護士会
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(53, 207);
			$mpdf->Write(0, $ret_member['bar_association_name'] . '弁護士会');
			
			// 購入手続き完了日
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(57, 212);
			$mpdf->Write(0, $arr_buy['payment_date']);
			
			// 決済方法
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(45, 217);
			$mpdf->Write(0, $arr_buy['disp_payment_type']);
		}
		
		// 商品名
		$mpdf->SetFont('ipaexg', '', 10);
		$mpdf->SetXY($product_name_x, $pd_y);
		$mpdf->Write(0, mb_strimwidth($arr_buy_detail[$i]['product_name'], 0, 57, '...'));
		
		// 単価
		$mpdf->SetFont('ipaexg', '', 10);
		$mpdf->SetXY($product_price_x, $pd_y);
		$mpdf->Write(0, "\\" . number_format($arr_buy_detail[$i]['price']));
		
		// 数量
		//$mpdf->SetFont('ipaexg', '', 10);
		//$mpdf->SetXY($product_unit_x, $pd_y);
		//$mpdf->Write(0, $arr_buy_detail[$i]['unit']);
		
		// 小計
		$subtotal = $arr_buy_detail[$i]['price'] * $arr_buy_detail[$i]['unit'];
		$mpdf->SetFont('ipaexg', '', 10);
		$mpdf->SetXY($product_subtotal_x, $pd_y);
		$mpdf->Write(0, "\\" . number_format($subtotal));
		
		// 総計集計
		$total += $subtotal;
		
		$count_gyo++;
		
		if ( ($page_no * $max_detail_gyo) == ($i + 1) ){
			$next_page_flg = true;
			
			// 総計(表下部)
			$mpdf->SetFont('ipaexg', '', 10);
			$mpdf->SetXY(170, 179);
			$mpdf->Write(0, "\\" . number_format($total));
			// 総計(上部)
			$mpdf->SetFont('ipaexg', '', 20);
			$mpdf->SetXY(55, 76);
			$mpdf->Write(0, "\\" . number_format($total) . '-');
			
			$total = 0;
			$count_gyo = 0;
		}
	}
	if ($total != 0){
		// 総計(表下部)
		$mpdf->SetFont('ipaexg', '', 10);
		$mpdf->SetXY(170, 179);
		$mpdf->Write(0, "\\" . number_format($total));
		// 総計(上部)
		$mpdf->SetFont('ipaexg', '', 20);
		$mpdf->SetXY(55, 76);
		$mpdf->Write(0, "\\" . number_format($total) . '-');
	}
	
	// 出力する
	$mpdf->Output("receipt_".date("YmdHis").".pdf", 'D');
	
	$sql = "";
	$sql.= "UPDATE ";
	$sql.= " tbl_order ";
	$sql.= "SET ";
	$sql.= " tbl_order.receipt_flg=1 ";
	$sql.= "WHERE ";
	$sql.= " tbl_order.receipt_flg=0 ";
	$sql.= " AND tbl_order.member_id='".$_SESSION["user"]["id"]."' ";
	$sql.= " AND tbl_order.order_id='".mysqli_real_escape_string($objDbConnect->connect, $oid)."' ";
	$ret = $objDbConnect->execute($sql);
}

$objDbConnect->close();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

?>