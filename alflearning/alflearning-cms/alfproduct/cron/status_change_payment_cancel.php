#!/usr/bin/php
<?php
/**
 * 購入データを入金待ち→キャンセルに変更する(指定日数経過データ対象)
 * ※任意のタイミングで動作するようcron設定してください。
 */
define('SCPC_CANCEL_DAY', 15); // 指定日数
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新開始'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_payment_cancel.log');
mb_language("japanese");
mb_internal_encoding("UTF-8");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 変更対象データの取得
$sql = "
SELECT
  T1.order_detail_id,
  T2.order_id
FROM
  tbl_order_detail AS T1
    LEFT JOIN
  tbl_order AS T2
      ON T1.order_id = T2.order_id
    LEFT JOIN
  tbl_product_live_training AS T3
      ON T1.product_id = T3.product_id
WHERE
  ( T1.payment_status = 0 OR T1.payment_status = 1 )
  AND ( T3.training_kind_flg <> 1 OR T3.training_kind_flg IS NULL )
  AND ( T3.ethic_flg = 0 OR T3.ethic_flg IS NULL )
  AND ( T3.sponsor <> '|1|' OR T3.sponsor IS NULL )
  AND ADDDATE( T2.order_date, INTERVAL " . SCPC_CANCEL_DAY . " DAY) <= '" . date('Y-m-d H:i:s') . "'
  AND T2.order_date >= '2013-12-01 00:00:00'
";
$arr_order_info = $objDbConnect->query_fetch_arr($sql);
if ($arr_order_info){
	// 支払ステータスの更新
	foreach ($arr_order_info as $val){
		$objDbConnect->tran_begin();
		
		// 注文履歴詳細の更新
		$sql = "UPDATE tbl_order_detail SET payment_status = 9, update_date = '" . date('Y-m-d H:i:s') . "' WHERE order_detail_id = '" . $val['order_detail_id'] . "'";
		$flg = $objDbConnect->execute($sql);
		if ($flg){
			// 注文履歴の更新
			$flg = _order_status_update($objDbConnect, $val['order_id']);
		}
		
		if ($flg){
			$objDbConnect->commit();
			@error_log('status change success. order_detail_id = ' . $val['order_detail_id'] . ', order_id = ' . $val['order_id'] . "\n", 3, '/alflearning-data/alfproduct/logs/status_change_payment_cancel.log');
		} else {
			$objDbConnect->rollback();
			@error_log('status change error. order_detail_id = '.$val['order_detail_id'] . ', order_id = ' . $val['order_id'] . "\n", 3, '/alflearning-data/alfproduct/logs/status_change_payment_cancel.log');
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新終了'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_payment_cancel.log');
exit();

/**
 * 注文履歴の支払ステータス更新
 */
function _order_status_update($objDbConnect, $order_id){
	$ret = false;
	
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.payment_status ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_id='".$order_id."' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	$all_cancel = true;
	for($i=0;$i<count($arr_temp);$i++){
		if($arr_temp[$i]["payment_status"]!="9"){
			$all_cancel = false;
		}
	}
	if( $all_cancel == true ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='9', ";
		$sql.= "tbl_order.payment_date=null ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.payment_status ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_id='".$order_id."' ";
	$where.= " and tbl_order_detail.payment_status<>'9' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	$all_pay = true;
	if( count($arr_temp)>0 ){
		for($i=0;$i<count($arr_temp);$i++){
			if($arr_temp[$i]["payment_status"]!="2"){
				$all_pay = false;
			}
		}
	} else {
		$all_pay = false;
	}
	if( $all_pay == true ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='2', ";
		$sql.= "tbl_order.payment_date='".date("Y-m-d H:i:s")."' ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.payment_status ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_id='".$order_id."' ";
	$where.= " and tbl_order_detail.payment_status<>'9' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	$all_nopay = true;
	if( count($arr_temp)>0 ){
		for($i=0;$i<count($arr_temp);$i++){
			if($arr_temp[$i]["payment_status"]!="1"){
				$all_nopay = false;
			}
		}
	} else {
		$all_nopay = false;
	}
	if( $all_nopay == true ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='1', ";
		$sql.= "tbl_order.payment_date=null ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
	if( !$all_cancel && !$all_pay && !$all_nopay ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='3', ";
		$sql.= "tbl_order.payment_date=null ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	
	return $ret;
}
?>
