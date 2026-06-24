#!/usr/bin/php
<?php
/**
 * 講座管理・会場研修申込状況確認で変更したステータスをtbl_order_detailに反映させる。
 * ※1日1回、任意のタイミングで動作するようcron設定してください。
 */
date_default_timezone_set('Asia/Tokyo');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新開始'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_product_detail.log');
mb_language("japanese");
mb_internal_encoding("UTF-8");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 変更対象データの取得
$sql = "SELECT * FROM tbl_status_change_payment";
$res = $objDbConnect->query_fetch_arr($sql);
if ($res){
	// 支払ステータスの更新
	foreach ($res as $val){
		if( trim($val['payment_status'])=="2" ){
			$sql = "UPDATE tbl_order_detail SET payment_status = '".mysqli_real_escape_string($objDbConnect->connect, $val['payment_status'])."', payment_date='".date("Y-m-d H:i:s")."' WHERE order_detail_id = '".mysqli_real_escape_string($objDbConnect->connect, $val['order_detail_id'])."'";
		} else {
			$sql = "UPDATE tbl_order_detail SET payment_status = '".mysqli_real_escape_string($objDbConnect->connect, $val['payment_status'])."' WHERE order_detail_id = '".mysqli_real_escape_string($objDbConnect->connect, $val['order_detail_id'])."'";
		}
		$objDbConnect->execute($sql);
		$order_detail_id = $val['order_detail_id'];
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// パスポート商品の場合は、studentテーブルのデータを更新する
		$sql2 = "SELECT member_id, product_type_add FROM tbl_order_detail WHERE order_detail_id = '$order_detail_id'";
		$ret_sql2 = $objDbConnect->query_fetch_arr($sql2);
		if ($ret_sql2){
			if ($ret_sql2[0]['product_type_add'] == '4'){
				if ($ret_sql2[0]['member_id'] != ''){
					$now_date = date('Y-m-d');
					//--------------------------------------------
					$sql_passport = "SELECT exp_date_passport FROM student WHERE student_id = '".$ret_sql2[0]['member_id']."' AND exp_date_passport>='".date('Y-m-d')."' ";
					$ret_passport = $objDbConnect->query_fetch_arr($sql_passport);
					$arr_passport = array();
					foreach ($ret_passport as $key => $val){
						$arr_passport = $val;
					}
					if( count($arr_passport)>0 ){
						$now_date = $arr_passport["exp_date_passport"];
					} else {
						$now_date = date('Y-m-d');
					}
					//--------------------------------------------
					$arr_now_date = explode('-', $now_date);
					$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1], $arr_now_date[2] + 1, $arr_now_date[0] + 1));
					$arr_now_date = explode('-', $exp_date_passport);
					$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1] + 1, 0, $arr_now_date[0]));
					$sql_update_student = "UPDATE student SET presence_passport = '1', exp_date_passport = '".$exp_date_passport."' WHERE student_id = '".$ret_sql2[0]['member_id']."'";
					$objDbConnect->execute($sql_update_student);
				}
			}
		}
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		order_status_update($val['order_detail_id']);
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	}

	$sql = "TRUNCATE TABLE tbl_status_change_payment";
	$objDbConnect->execute($sql);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
@error_log(date("Y-m-d H:i:s").':'.'更新終了'."\n", 3, '/alflearning-data/alfproduct/logs/status_change_product_detail.log');
exit();

function order_status_update($order_detail_id){
	$objDbConnect = new DbConnect();
	$order_id = "";
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.order_id ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	for($i=0;$i<count($arr_temp);$i++){
		$order_id = $arr_temp[$i]["order_id"];
	}

	if($order_id != ""){
		//----------------------------------------
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
		//----------------------------------------
	}
}
?>
