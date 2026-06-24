<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include("/srv/alfproduct/module/module.php");
include("/srv/alfproduct/module/fpdf/japanese.php");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template_file_path = '/srv/alfproduct/module/fpdf/template/';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mode = "";
$order_id = "";
$order_detail_id = "";
$payment_status = "";
if( isset($_GET["mode"]) && !empty($_GET["mode"]) ){
	$mode = $_GET["mode"];
}
if( isset($_GET["oid"]) && !empty($_GET["oid"]) ){
	$order_id = $_GET["oid"];
}
if( isset($_GET["order_detail_id"]) && !empty($_GET["order_detail_id"]) ){
	$order_detail_id = $_GET["order_detail_id"];
}
if( isset($_GET["payment_status"]) && !empty($_GET["payment_status"]) ){
	$payment_status = $_GET["payment_status"];
}

if($order_id == ""){
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["amount_order.info.page"]) && !empty($_SESSION["amount_order.info.page"]) ){
	$page = $_SESSION["amount_order.info.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_order.info.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_bar_association";
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_payment_status = array(
				array("id"=>"1",	"name"=>"完了"),
				array("id"=>"2",	"name"=>"全て未入金"),
				array("id"=>"3",	"name"=>"一部未入金"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order.order_id, ";//注文番号
$sql.= "tbl_order.order_no, ";//注文番号
$sql.= "DATE_FORMAT(tbl_order.payment_date, '%Y/%m/%d') AS payment_date, ";//支払日
$sql.= "student.student_name, ";//ユーザ名
$sql.= "student.lawyer_number, ";//登録番号
$sql.= "student.regist_date, ";//登録年月日
$sql.= "mtb_bar_association.name as association_name, ";//弁護士会
//$sql.= "tbl_order_detail.payment_status, ";//支払いステータス
$sql.= "tbl_order.payment_status, ";//支払いステータス
$sql.= "tbl_order.create_date, ";//購入日
$sql.= "tbl_order.payment_type, ";//支払い方法（1：カード　12：銀行振込）
$sql.= "tbl_order.claim_flg, ";//請求書希望
$sql.= "tbl_order.web_flg ";//Web購入
$sql.= "FROM ";
$sql.= "tbl_order ";
$sql.= " LEFT JOIN tbl_order_detail ON tbl_order.order_id=tbl_order_detail.order_id ";
$sql.= " LEFT JOIN student ON student.student_id = tbl_order_detail.member_id ";
$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";

$where = "";
$where.= "WHERE ";
$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
$where.= " and tbl_order.order_id='".$order_id."' ";
$where.= " and tbl_order.payment_status>='1' ";//支払いステータス
//-----------------------------------
if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
} else {
	$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
}
//-----------------------------------
$arr_order = $objDbConnect->query_fetch($sql.$where);
if( 0<count($arr_order) ){
} else {
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "count(order_detail_id) as c ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$where = "";
$where.= "WHERE ";
$where.= " order_id='".$order_id."'";
$group = "";
$ret = $objDbConnect->query_fetch($sql.$where.$group);
//$objAdminPager->setPageMax(1);
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.order_detail_id, ";//ID
$sql.= "tbl_order_detail.product_id, ";//商品ID
$sql.= "tbl_order_detail.price, ";//商品価格
$sql.= "tbl_order_detail.unit, ";//商品数量
$sql.= "tbl_product.product_code, ";//商品コード
$sql.= "tbl_product.product_name AS product_name_TP, ";//商品名
$sql.= "tbl_product_add.product_type_add, ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
$sql.= "tbl_order_detail.pay_total, ";//金額
$sql.= "tbl_order_detail.product_name AS product_name_TOD, ";//商品名
$sql.= "tbl_order.create_date as buy_date, ";//購入日
$sql.= "tbl_order_detail.payment_status, ";//支払い(0:未入金　1:入金待ち　2:入金済み　9:キャンセル)
$sql.= "tbl_order_detail.receipt_date, ";//入金日
$sql.= "tbl_order_detail.take_date ";//取り込み日（権限付与日）
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= " LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id ";
$where = "";
$where.= "WHERE ";
$where.= " tbl_order.order_id='".$order_id."' ";
$order = " ORDER BY tbl_order_detail.create_date DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);

$arr_order_detail = $ret;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$template = 'receipt.pdf';
if (file_exists($template_file_path . $template)){
	$pdf= new PDF_Japanese('P', 'mm', 'A4');
	$pdf->AddSJISFont();
	
	$page_no = 1; // ページNo. 1ページ10商品
	
	// PDFファイル読み込み
	$pdf->AddPage();
	$pdf->setSourceFile($template_file_path . $template);
	$page = $pdf->importPage(1);
	$pdf->useTemplate($page);
	
	// -----------------
	// 固定部分書き込み
	// -----------------
	// 発行年月日
	$publish_date = date('Y/m/d');
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(149, 44);
	$pdf->Write(0, mb_convert_encoding($publish_date, "SJIS-win", "UTF-8"));
	
	// 宛名(フリーテキスト)
	$atena_free = '';
	if (isset($_POST['atena'])){
		if ($_POST['atena'] != ''){
			$atena_free = $_POST['atena'];
			$pdf->SetFont('SJIS', '', 16);
			$pdf->SetXY(30, 42);
			$pdf->Write(0, mb_convert_encoding($atena_free, "SJIS-win", "UTF-8"));
		}
	}
	
	// 宛名(氏名)
	$atena_name = $arr_order['student_name'];
	$pdf->SetFont('SJIS', '', 16);
	$pdf->SetXY(30, 50);
	$pdf->Write(0, mb_convert_encoding($atena_name, "SJIS-win", "UTF-8"));

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
	$pdf->SetFont('SJIS', '', 1);
	$pdf->SetXY(10, 25);
	$pdf->Write(0, mb_convert_encoding($atena, "SJIS-win", "UTF-8"));
*/

	// 領収書番号
	$ryousyu_no = $arr_order['order_id'] . '-' . $page_no;
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(48, 193);
	$pdf->Write(0, mb_convert_encoding($ryousyu_no, "SJIS-win", "UTF-8"));
	
	// 注文番号
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(44, 198);
	$pdf->Write(0, mb_convert_encoding($arr_order['order_no'], "SJIS-win", "UTF-8"));
	
	// 登録番号
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(44, 203);
	$pdf->Write(0, mb_convert_encoding($arr_order['lawyer_number'], "SJIS-win", "UTF-8"));
	
	// 所属弁護士会
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(53, 207);
	$pdf->Write(0, mb_convert_encoding($arr_order['association_name'] . '弁護士会', "SJIS-win", "UTF-8"));
	
	// 購入手続き完了日
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(57, 212);
	$pdf->Write(0, mb_convert_encoding($arr_order['payment_date'], "SJIS-win", "UTF-8"));
	
	// 決済方法
	$pdf->SetFont('SJIS', '', 10);
	$pdf->SetXY(45, 217);
	$pdf->Write(0, mb_convert_encoding(get_str_payment_type($arr_order['payment_type']), "SJIS-win", "UTF-8"));
	
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
	$count_arr_order_detail = count($arr_order_detail);
	for ($i=0; $i<$count_arr_order_detail; $i++){
		$pd_y = $default_y + $count_gyo * 5;
		
		if ($next_page_flg){
			$next_page_flg = false;
			
			$page_no++;
			
			$pdf->AddPage();
			$pdf->setSourceFile($template_file_path . $template);
			$page = $pdf->importPage(1);
			$pdf->useTemplate($page);
			
			// 発行年月日
			$publish_date = date('Y/m/d');
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(149, 44);
			$pdf->Write(0, mb_convert_encoding($publish_date, "SJIS-win", "UTF-8"));
			
			// 宛名(フリーテキスト)
			$atena_free = '';
			if (isset($_POST['atena'])){
				if ($_POST['atena'] != ''){
					$atena_free = $_POST['atena'];
					$pdf->SetFont('SJIS', '', 16);
					$pdf->SetXY(30, 42);
					$pdf->Write(0, mb_convert_encoding($atena_free, "SJIS-win", "UTF-8"));
				}
			}
			
			// 宛名(氏名)
			$atena_name = $arr_order['student_name'];
			$pdf->SetFont('SJIS', '', 16);
			$pdf->SetXY(30, 50);
			$pdf->Write(0, mb_convert_encoding($atena_name, "SJIS-win", "UTF-8"));
			
			// 領収書番号
			$ryousyu_no = $arr_order['order_id'] . '-' . $page_no;
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(48, 193);
			$pdf->Write(0, mb_convert_encoding($ryousyu_no, "SJIS-win", "UTF-8"));
			
			// 注文番号
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(44, 198);
			$pdf->Write(0, mb_convert_encoding($arr_order['order_no'], "SJIS-win", "UTF-8"));
			
			// 登録番号
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(44, 203);
			$pdf->Write(0, mb_convert_encoding($arr_order['lawyer_number'], "SJIS-win", "UTF-8"));
			
			// 所属弁護士会
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(53, 207);
			$pdf->Write(0, mb_convert_encoding($arr_order['association_name'] . '弁護士会', "SJIS-win", "UTF-8"));
			
			// 購入手続き完了日
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(57, 212);
			$pdf->Write(0, mb_convert_encoding($arr_order['payment_date'], "SJIS-win", "UTF-8"));
			
			// 決済方法
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(45, 217);
			$pdf->Write(0, mb_convert_encoding(get_str_payment_type($arr_order['payment_type']), "SJIS-win", "UTF-8"));
		}
		
		// 商品名
		$product_name = '';
		if ($arr_order_detail[$i]['product_name_TOD'] != ''){
			$product_name = $arr_order_detail[$i]['product_name_TOD'];
		} else if ($arr_order_detail[$i]['product_name_TP'] != ''){
			$product_name = $arr_order_detail[$i]['product_name_TP'];
		}
		$pdf->SetFont('SJIS', '', 10);
		$pdf->SetXY($product_name_x, $pd_y);
		$pdf->Write(0, mb_convert_encoding(mb_strimwidth($product_name, 0, 57, '...'), "SJIS-win", "UTF-8"));
		
		// 単価
		$pdf->SetFont('SJIS', '', 10);
		$pdf->SetXY($product_price_x, $pd_y);
		$pdf->Write(0, mb_convert_encoding("\\" . number_format($arr_order_detail[$i]['price']), "SJIS-win", "UTF-8"));
		
		// 数量
		//$pdf->SetFont('SJIS', '', 10);
		//$pdf->SetXY($product_unit_x, $pd_y);
		//$pdf->Write(0, mb_convert_encoding($arr_order_detail[$i]['unit'], "SJIS-win", "UTF-8"));
		
		// 小計
		$subtotal = $arr_order_detail[$i]['price'] * $arr_order_detail[$i]['unit'];
		$pdf->SetFont('SJIS', '', 10);
		$pdf->SetXY($product_subtotal_x, $pd_y);
		$pdf->Write(0, mb_convert_encoding("\\" . number_format($subtotal), "SJIS-win", "UTF-8"));
		
		// 総計集計
		$total += $subtotal;
		
		$count_gyo++;
		
		if ( ($page_no * $max_detail_gyo) == ($i + 1) ){
			$next_page_flg = true;
			
			// 総計(表下部)
			$pdf->SetFont('SJIS', '', 10);
			$pdf->SetXY(170, 179);
			$pdf->Write(0, mb_convert_encoding("\\" . number_format($total), "SJIS-win", "UTF-8"));
			// 総計(上部)
			$pdf->SetFont('SJIS', '', 20);
			$pdf->SetXY(55, 76);
			$pdf->Write(0, mb_convert_encoding("\\" . number_format($total) . '-', "SJIS-win", "UTF-8"));
			
			$total = 0;
			$count_gyo = 0;
		}
	}
	if ($total != 0){
		// 総計(表下部)
		$pdf->SetFont('SJIS', '', 10);
		$pdf->SetXY(170, 179);
		$pdf->Write(0, mb_convert_encoding("\\" . number_format($total), "SJIS-win", "UTF-8"));
		// 総計(上部)
		$pdf->SetFont('SJIS', '', 20);
		$pdf->SetXY(55, 76);
		$pdf->Write(0, mb_convert_encoding("\\" . number_format($total) . '-', "SJIS-win", "UTF-8"));
	}
	
	// 出力する
	$pdf->Output("receipt_".date("YmdHis").".pdf", 'D');
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
