<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if (!file_exists('/srv/alfproduct/module/vendor/setasign/fpdf/font/ipaexg.php')) {
    echo 'Font definition file does not exist.';
}
if (!file_exists('/srv/alfproduct/module/vendor/setasign/fpdf/font/ipaexg.z')) {
    echo 'Font compressed file does not exist.';
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
require '/srv/alfproduct/module/vendor/autoload.php'; // mPDFのautoloadを読み込む

//use setasign\Fpdi\Fpdi;
use tecnickcom\Tcpdf;
use setasign\Fpdi\TcpdfFpdi;
use setasign\Fpdi\PdfParser\PdfParser; // PdfParserをインポート

require_once("/srv/alfproduct/module/session_start.php");
require_once("/srv/alfproduct/module/DbConnect.php");
require_once("/srv/alfproduct/module/define_list.php");
require_once("/srv/alfproduct/module/functions.php");
require_once("/srv/alfproduct/module/ci/libraries/Encrypt.php");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
mb_language("Japanese");
mb_internal_encoding("UTF-8");

$template_file_path = '/srv/alfproduct/module/template/';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$_SESSION['wp_page_head_title'] = '受講票ダウンロードエラー';
if (!st_login_check()){
	header("Location: /#1");
	exit;
}
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$odid = "";
if( isset($_GET["odid"]) && !empty($_GET["odid"]) && is_numeric($_GET["odid"]) ){
	$odid = $_GET["odid"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order.order_id, ";
$sql.= "tbl_order.order_no, ";
$sql.= "DATE_FORMAT(tbl_order.payment_date, '%m') AS payment_date_m, ";//支払日時(月)
$sql.= "DATE_FORMAT(tbl_order.payment_date, '%d') AS payment_date_d, ";//支払日時(日)
$sql.= "tbl_order.payment_type, ";//支払方法(1:カード 12:銀行振込 99:パスポート購入)
$sql.= "tbl_order_detail.order_detail_id, ";
$sql.= "tbl_order_detail.member_id, ";
$sql.= "tbl_order_detail.product_id, ";
$sql.= "tbl_order_detail.product_name, ";
$sql.= "tbl_order_detail.product_type_add, ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
$sql.= "tbl_order_detail.bar_association_id, ";//弁護士会ID(会場研修商品の時に値が入る)
$sql.= "tbl_order_detail.bar_association_branch_id, ";//弁護士会支部ID(会場研修商品の時に値が入る) 
$sql.= "tbl_order_detail.payment_status, ";//商品種別(0:未入金 1:入金待ち 2:入金済み 9:キャンセル)
$sql.= "tbl_order_detail.pay_total ";//支払総計
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= "LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
$sql.= "LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= "WHERE ";
$sql.= " tbl_order_detail.ticket_flg=0 ";
$sql.= " AND tbl_order_detail.product_type_add=2 ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
$sql.= " AND tbl_order_detail.member_id='".$_SESSION["user"]["id"]."' ";
$sql.= " AND tbl_order_detail.order_detail_id='".$odid."' ";
$sql.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) ";
$ret_order = $objDbConnect->query_fetch_arr($sql);
if (!$ret_order){
	header("Location: /#2");
	exit;
}

$sql = "";
$sql.= "SELECT ";
$sql.= "*, ";
$sql.= "mtb_bar_association.name as bar_association_name ";
$sql.= "FROM ";
$sql.= "student ";
$sql.= "LEFT JOIN mtb_bar_association ON student.bar_association_id=mtb_bar_association.id ";
$sql.= "WHERE ";
$sql.= " student.student_id='".$_SESSION["user"]["id"]."' ";
$ret_member = $objDbConnect->query_fetch_arr($sql);
if (!$ret_member){
	header("Location: /#3");
	exit;
}

$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_product.product_id, ";//商品ID
$sql.= "tbl_product.product_type, ";//商品種別　1:会員専用　2:一般公開
$sql.= "tbl_product.product_name, ";//商品名
$sql.= "tbl_product.product_code, ";//商品コード
$sql.= "tbl_product.price, ";//商品価格(円、税抜き)
$sql.= "tbl_product.term_id, ";//商品カテゴリID
$sql.= "tbl_product.discount_code, ";//割引コード
$sql.= "tbl_product.start_date, ";//公開日（開始）
$sql.= "tbl_product.end_date, ";//公開日（終了）
$sql.= "tbl_product_live_training.training_kind_flg, ";//研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
$sql.= "tbl_product_live_training.ethic_flg, ";//倫理フラグ（0:倫理研修対象としない 1:倫理研修対象とする）
$sql.= "tbl_product_live_training.app_flg, ";//許可フラグ（0:日弁連未許可 1:日弁連許可）
$sql.= "tbl_product_live_training.limit_date, ";//受講料振り込み期限
$sql.= "DATE_FORMAT(tbl_product_live_training.limit_date, '%m') AS limit_date_m, ";//受講料振り込み期限(月)
$sql.= "DATE_FORMAT(tbl_product_live_training.limit_date, '%d') AS limit_date_d, ";//受講料振り込み期限(日)
$sql.= "tbl_product_live_training.memo1, ";//研修の内容
$sql.= "tbl_product_live_training.memo2, ";//講義タイトル、講師名
$sql.= "tbl_product_live_training.memo3, ";//日時詳細
$sql.= "tbl_product_live_training.memo4, ";//問い合わせ先
$sql.= "tbl_product_live_training.memo5, ";//受講資格/他会員の受講等
$sql.= "rel_product_bar_association_branch.capacity, ";//定員
$sql.= "rel_product_bar_association_branch.hall, ";//会場
$sql.= "rel_product_bar_association_branch.receptionist_start_date, ";//受付日（開始）
$sql.= "rel_product_bar_association_branch.receptionist_end_date, ";//受付日（終了）
$sql.= "DATE_FORMAT(rel_product_bar_association_branch.dates, '%Y/%m/%d %H:%i') AS dates, ";//実施日
$sql.= "rel_product_bar_association_branch.contents ";//備考
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= "LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= "LEFT JOIN tbl_product_live_training ON tbl_order_detail.product_id=tbl_product_live_training.product_id ";
$sql.= "LEFT JOIN rel_product_bar_association ON tbl_order_detail.product_id=rel_product_bar_association.product_id ";
$sql.= "LEFT JOIN rel_product_bar_association_branch ON tbl_order_detail.product_id=rel_product_bar_association_branch.product_id ";
$sql.= "WHERE ";
//$sql.= " tbl_product.del_flg = '0' ";
$sql.= " tbl_product_live_training.download_flg=1 ";
$sql.= " AND tbl_order_detail.product_id='".$ret_order[0]["product_id"]."' ";//商品ID
$sql.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) ";
$sql.= " AND rel_product_bar_association.bar_association_id='".$ret_order[0]["bar_association_id"]."' ";//弁護士会ID(会場研修商品の時に値が入る)
$sql.= " AND rel_product_bar_association_branch.bar_association_branch_id='".$ret_order[0]["bar_association_branch_id"]."' ";//弁護士会支部ID(会場研修商品の時に値が入る)
$ret_product = $objDbConnect->query_fetch_arr($sql);
if (!$ret_product){
	header("Location: /#4");
	exit;
}

// 使用テンプレートのタイプ設定
$template_type = '';
if ( $ret_order[0]['payment_type'] == '99' || $ret_order[0]['pay_total'] == '0'){
	$template_type = 1; // パスポート購入
} else if ($ret_order[0]['payment_status'] == '2'){
	$template_type = 2; // 入金済
} else if ($ret_order[0]['payment_status'] == '1'){
	$template_type = 3; // 入金未
}

// 使用テンプレートのファイル名指定
$template = '';
if ($template_type == 1){
	$template = 'wa';
} else if ($template_type == 2){
	$template = 'wb';
} else if ($template_type == 3){
	$template = 'wc';
}

// テンプレートファイルのチェック
if ($template_type != '' && $template != ''){

	$template = $template . '.pdf';
	if (file_exists($template_file_path . $template)){


		// FPDIインスタンス作成
		//$pdf = new Fpdi();
		$pdf = new \setasign\Fpdi\Tcpdf\Fpdi("P", "mm", "A4", true, "UTF-8");
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		//$pdf->AddFont('kozgopromedium', '', 'ipaexg.php'); // フォントを追加

		// PDFファイルを読み込む
		$pdf->setSourceFile($template_file_path . $template);

		// 新規ページを追加し、テンプレートの1ページ目を適用
		$pdf->AddPage();
		// 使用するフォント名

		$tplId = $pdf->importPage(1);
		$pdf->useTemplate($tplId);
	
		// ---------------------------
		// wa,wb,wcそれぞれの書き込み
		// ---------------------------
		if ($template_type == 1){ // wa
			// 講座名
			$product_name = "";
			if ($ret_order[0]["product_name"]!=""){
				$product_name = $ret_order[0]["product_name"];
			} else if ($ret_product[0]["product_name"]!=""){
				$product_name = $ret_product[0]["product_name"];
			}
			$pdf->SetFont('kozgopromedium', '', 22);
			$max_gyo      = 2;
			$max_gyo_moji = 20;
			$count_product_name = mb_strlen($product_name, 'UTF-8');
			if ($count_product_name >= ( $max_gyo * $max_gyo_moji )){
				$pdf->SetXY(28, 51);
				$pdf->Write(0, mb_substr($product_name, 0, $max_gyo_moji, 'UTF-8'));
				$pdf->SetXY(28, 61);
				$pdf->Write(0, mb_substr($product_name, $max_gyo_moji, $max_gyo_moji, 'UTF-8'));
			} else if ($count_product_name >= $max_gyo_moji){
				$pdf->SetXY(28, 51);
				$pdf->Write(0, mb_substr($product_name, 0, $max_gyo_moji, 'UTF-8'));
				if ($count_product_name != $max_gyo_moji){
					$pdf->SetXY(28, 61);
					$pdf->Write(0, mb_substr($product_name, $max_gyo_moji, null, 'UTF-8'));
				}
			} else {
				$pdf->SetXY(28, 51);
				$pdf->Write(0, mb_convert_encoding($product_name, 'UTF-8', 'auto'));
			}

			// 場所
			if ($ret_product[0]["hall"] != ''){
				$pdf->SetFont('kozgopromedium', '', 14);
				$pdf->SetXY(50, 74);
				$pdf->Write(0, $ret_product[0]["hall"]);
			}
			
			// 日時
			if ($ret_product[0]["dates"] != ''){
				$arr_youbi = array('日','月','火','水','木','金','土');
				$u_dates = strtotime($ret_product[0]["dates"]);
				$pdf->SetFont('kozgopromedium', '', 14);
				//$pdf->SetXY(50, 81);
				//$pdf->Write(0, date('Y年m月d日', $u_dates).'('.$arr_youbi[date('w', $u_dates)].') '.date('H時i分', $u_dates));

				$pdf->SetXY(60, 81);
				$pdf->Write(0, date('Y', $u_dates));
				$pdf->SetXY(79, 81);
				$pdf->Write(0, date('m', $u_dates));
				$pdf->SetXY(94, 81);
				$pdf->Write(0, date('d', $u_dates));
				$pdf->SetXY(107.5, 81);
				$pdf->Write(0, $arr_youbi[date('w', $u_dates)]);
				$pdf->SetXY(120, 81);
				$pdf->Write(0, date('H', $u_dates));
				$pdf->SetXY(135, 81);
				$pdf->Write(0, date('i', $u_dates));

			}
			
			// 主催
			//$pdf->SetFont('kozgopromedium', '', 14);
			//$pdf->SetXY(50, 93);
			//$pdf->Write(0, get_bar_association_name($ret_order[0]["bar_association_id"]));
			
			// 登録番号
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(35, 114);
			$pdf->Write(0, $_SESSION['user']['lawyer_number']);
			
			// 氏名
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(115, 114);
			$pdf->Write(0, $_SESSION['user']['name']);
			
		} else if ($template_type == 2){ // wb
			// 講座名
			$product_name = "";
			if ($ret_order[0]["product_name"]!=""){
				$product_name = $ret_order[0]["product_name"];
			} else if ($ret_product[0]["product_name"]!=""){
				$product_name = $ret_product[0]["product_name"];
			}
			$pdf->SetFont('kozgopromedium', '', 22);
			$max_gyo      = 2;
			$max_gyo_moji = 20;
			$count_product_name = mb_strlen($product_name, 'UTF-8'); // UTF-8を指定
			if ($count_product_name >= ($max_gyo * $max_gyo_moji)) {
			    $pdf->SetXY(28, 51);
			    $pdf->Write(0, mb_substr($product_name, 0, $max_gyo_moji, 'UTF-8')); // UTF-8を指定
			    $pdf->SetXY(28, 61);
			    $pdf->Write(0, mb_substr($product_name, $max_gyo_moji, $max_gyo_moji, 'UTF-8')); // UTF-8を指定
			} else if ($count_product_name >= $max_gyo_moji) {
			    $pdf->SetXY(28, 51);
			    $pdf->Write(0, mb_substr($product_name, 0, $max_gyo_moji, 'UTF-8')); // UTF-8を指定
			    if ($count_product_name != $max_gyo_moji) {
			        $pdf->SetXY(28, 61);
			        $pdf->Write(0, mb_substr($product_name, $max_gyo_moji, null, 'UTF-8')); // UTF-8を指定
			    }
			} else {
			    $pdf->SetXY(28, 51);
			    $pdf->Write(0, $product_name); // $product_nameがUTF-8であることを確認
			}
			
			// 場所
			if ($ret_product[0]["hall"] != ''){
				$pdf->SetFont('kozgopromedium', '', 14);
				$pdf->SetXY(50, 72);
				$pdf->Write(0, $ret_product[0]["hall"]);
			}
			
			// 日時
			if ($ret_product[0]["dates"] != ''){
				$arr_youbi = array('日','月','火','水','木','金','土');
				$u_dates = strtotime($ret_product[0]["dates"]);
				$pdf->SetFont('kozgopromedium', '', 14);
				$pdf->SetXY(50, 79);
				$pdf->Write(0, date('Y年m月d日', $u_dates).'('.$arr_youbi[date('w', $u_dates)].') '.date('H時i分', $u_dates));
			}
			
			// 主催
			$pdf->SetFont('kozgopromedium', '', 14);
			$pdf->SetXY(50, 93);
			$pdf->Write(0, get_bar_association_name($ret_order[0]["bar_association_id"]));
			
			// 登録番号
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(35, 109);
			$pdf->Write(0, $_SESSION['user']['lawyer_number']);
			
			// 氏名
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(115, 109);
			$pdf->Write(0, $_SESSION['user']['name']);
			
			// 入金年月
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(40, 159);
			$pdf->Write(0, $ret_order[0]["payment_date_m"]);
			$pdf->SetXY(60, 159);
			$pdf->Write(0, $ret_order[0]["payment_date_d"]);
			
		} else if ($template_type == 3){ // wc
			// 講座名
			$product_name = "";
			if ($ret_order[0]["product_name"]!=""){
				$product_name = $ret_order[0]["product_name"];
			} else if ($ret_product[0]["product_name"]!=""){
				$product_name = $ret_product[0]["product_name"];
			}
			$pdf->SetFont('kozgopromedium', '', 22);
			$max_gyo      = 2;
			$max_gyo_moji = 20;
			$count_product_name = mb_strlen($product_name);
			if ($count_product_name >= ( $max_gyo * $max_gyo_moji )){
				$pdf->SetXY(28, 46);
				$pdf->Write(0, mb_substr($product_name, 0, $max_gyo_moji, 'UTF-8'));
				$pdf->SetXY(28, 56);
				$pdf->Write(0, mb_substr($product_name, $max_gyo_moji, $max_gyo_moji, 'UTF-8'));
			} else if ($count_product_name >= $max_gyo_moji){
				$pdf->SetXY(28, 46);
				$pdf->Write(0, mb_substr($product_name, 0, $max_gyo_moji, 'UTF-8'));
				if ($count_product_name != $max_gyo_moji){
					$pdf->SetXY(28, 56);
					$pdf->Write(0, mb_substr($product_name, $max_gyo_moji, null, 'UTF-8'));
				}
			} else {
				$pdf->SetXY(28, 46);
				$pdf->Write(0, $product_name);
			}
			
			// 場所
			if ($ret_product[0]["hall"] != ''){
				$pdf->SetFont('kozgopromedium', '', 14);
				$pdf->SetXY(50, 67);
				$pdf->Write(0, $ret_product[0]["hall"]);
			}
			
			// 日時
			if ($ret_product[0]["dates"] != ''){
				$arr_youbi = array('日','月','火','水','木','金','土');
				$u_dates = strtotime($ret_product[0]["dates"]);
				$pdf->SetFont('kozgopromedium', '', 14);
				$pdf->SetXY(50, 74);
				$pdf->Write(0, date('Y年m月d日', $u_dates).'('.$arr_youbi[date('w', $u_dates)].') '.date('H時i分', $u_dates));
			}
			
			// 主催
			$pdf->SetFont('kozgopromedium', '', 14);
			$pdf->SetXY(50, 88);
			$pdf->Write(0, get_bar_association_name($ret_order[0]["bar_association_id"]));
			
			// 登録番号
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(35, 103);
			$pdf->Write(0, $_SESSION['user']['lawyer_number']);
			
			// 氏名
			$pdf->SetFont('kozgopromedium', '', 20);
			$pdf->SetXY(115, 103);
			$pdf->Write(0, $_SESSION['user']['name']);
			
			// 受講料
			$pdf->SetFont('kozgopromedium', '', 14);
			$pdf->SetXY(46, 136);
			$pdf->Write(0, number_format($ret_order[0]["pay_total"]));
			
			// 振り込み期限
			$pdf->SetFont('kozgopromedium', '', 14);
			$pdf->SetXY(80, 136);
			$pdf->Write(0, $ret_product[0]["limit_date_m"]);
			$pdf->SetXY(93, 136);
			$pdf->Write(0, $ret_product[0]["limit_date_d"]);
			
		}
		
		// 出力する
		$pdf->Output("ticket_".date("YmdHis").".pdf", 'D');
		//$pdf->Output("I", "ticket_".date("YmdHis").".pdf");
		
		// 受講票発行済みフラグの更新
/*
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= " tbl_order_detail ";
		$sql.= "SET ";
		$sql.= " tbl_order_detail.ticket_flg=1 ";
		$sql.= "WHERE ";
		$sql.= " tbl_order_detail.ticket_flg=0 ";
		$sql.= " AND tbl_order_detail.product_type_add=2 ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
		$sql.= " AND tbl_order_detail.member_id='".$_SESSION["user"]["id"]."' ";
		$sql.= " AND tbl_order_detail.order_detail_id='".$odid."' ";
		$ret = $objDbConnect->execute($sql);
*/
	}
}

$objDbConnect->close();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>