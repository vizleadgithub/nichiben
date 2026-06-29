<?php
//ini_set('display_errors', 1);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '購入履歴詳細';

if (!st_login_check()){
	header("Location: /");
	exit;
}

$objDbConnect = new DbConnect();

$order_id = $_GET['oid'];

// 購入履歴詳細の取得＆ログインユーザーの購入履歴かチェック
$arr_buy_detail = array();
$arr_buy = array();
$total_price = 0;

$sql = "
SELECT 
  T1.payment_status, 
  T1.price, 
  T1.product_id, 
  T1.product_name AS product_name_TOD, 
  T1.product_code, 
  date_format(T1.start_date, '%Y%m%d') as start_date, 
  date_format(T1.end_date, '%Y%m%d') as end_date, 
  date_format(T1.payment_date, '%Y-%m-%d') as payment_date, 
  T1.open_period, 
  tbl_product_add.product_type_add,
  tbl_product.product_name AS product_name_TP,
  tbl_product.del_flg
FROM 
  tbl_order_detail AS T1
    LEFT JOIN
  tbl_product
      ON T1.product_id = tbl_product.product_id
    LEFT JOIN 
  tbl_product_add 
      ON T1.product_id = tbl_product_add.product_id
WHERE 
  T1.order_id = '".mysqli_real_escape_string($objDbConnect->connect, $order_id)."' 
  AND T1.member_id = '".$_SESSION['user']['id']."' 
  AND ( T1.payment_status = 1 OR T1.payment_status = 2 OR T1.payment_status = 9 )
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
		if ($val['payment_status'] != 9){
			$total_price += $val['price'];
		}
		//--------------------------------
		$arr_buy_detail[$count]['product_id'] = $val['product_id'];
		$arr_buy_detail[$count]['product_name_TOD'] = $val['product_name_TOD'];
		$arr_buy_detail[$count]['product_code'] = $val['product_code'];
		$arr_buy_detail[$count]['product_type_add'] = $val['product_type_add'];
		$arr_buy_detail[$count]['start_date'] = $val['start_date'];
		$arr_buy_detail[$count]['end_date'] = $val['end_date'];
		$arr_buy_detail[$count]['payment_date'] = $val['payment_date'];
		$arr_buy_detail[$count]['open_period'] = $val['open_period'];
		$arr_buy_detail[$count]['product_name_TP'] = $val['product_name_TP'];
		$arr_buy_detail[$count]['del_flg'] = $val['del_flg'];
		//--------------------------------
		if ($val['product_type_add'] == 1){//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
			$arr_buy_detail[$count]['disp_product_type_add'] = 'e-ラーニング';
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
	  DATE_FORMAT(order_date, '%Y/%m/%d') AS order_date,
	  DATE_FORMAT(payment_date, '%Y/%m/%d') AS payment_date,
	  payment_type,
	  payment_status,
	  receipt_flg
	FROM
	  tbl_order
	WHERE
	  order_id = $order_id 
	 ";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$arr_buy['order_id'] = $res['order_id'];
		$arr_buy['order_date'] = $res['order_date'];
		$arr_buy['payment_date'] = $res['payment_date'];
		$arr_buy['payment_type'] = $res['payment_type'];
		$arr_buy['disp_payment_type'] = get_str_payment_type($res['payment_type']);
		$arr_buy['payment_status'] = $res['payment_status'];
		$arr_buy['disp_payment_status'] = get_str_payment_status($res['payment_status']);
		$arr_buy['receipt_flg'] = $res['receipt_flg'];
		$arr_buy['disp_receipt_flg'] = get_str_receipt_flg($res['receipt_flg']);
	}
	for($i=0;$i<count($arr_buy_detail);$i++){
		$temp_link = true;
		if( $arr_buy_detail[$i]["open_period"]=="0" ){
		} else {
			$temp_ymd = date('Ymd', strtotime($arr_buy_detail[$i]["payment_date"]." +".$arr_buy_detail[$i]["open_period"]." days"));
			if( $temp_ymd<date("Ymd") ){
				$temp_link = false;
			}
		}
		if( $arr_buy_detail[$i]["start_date"]>="18000000" && $arr_buy_detail[$i]["end_date"]>="18000000" ){
			if( $arr_buy_detail[$i]["start_date"]<=date("Ymd") && $arr_buy_detail[$i]["end_date"]>=date("Ymd") ){
			} else {
				$temp_link = false;
			}
		} elseif( $arr_buy_detail[$i]["start_date"]>="18000000" ) {
			if( $arr_buy_detail[$i]["start_date"]<=date("Ymd") ){
			} else {
				$temp_link = false;
			}
		} elseif( $arr_buy_detail[$i]["end_date"]>="18000000" ) {
			if( $arr_buy_detail[$i]["end_date"]>=date("Ymd") ){
			} else {
				$temp_link = false;
			}
		}

		if( $temp_link ){
			$arr_buy_detail[$i]["link"] = "1";
		} else {
			$arr_buy_detail[$i]["link"] = "0";
		}
	}
} else {
	header("Location: /");
	exit;
}

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

//$template->assign('now_date', date("Ymd") );

$template->assign('arr_buy_detail', $arr_buy_detail);
$template->assign('arr_buy', $arr_buy);
$template->assign('total_price', $total_price);

$template->assign('csrf_token', csrf_token_get());
$template->layout_noside('mypage/buy_detail.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>