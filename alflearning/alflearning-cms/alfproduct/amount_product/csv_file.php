<?php
set_time_limit(180);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
date_default_timezone_set('Asia/Tokyo');
include( "/srv/alfproduct/module/DbConnect.php" );
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$all_pay_total = 0;
$all_buy_count = 0;
$sql = "";
$sql.= "SELECT ";
$sql.= " tbl_product.product_id, ";
$sql.= " tbl_product.product_name, ";
$sql.= " tbl_product.product_code, ";
$sql.= " tbl_product_add.product_type_add, ";
$sql.= " tbl_product_elearning.product_kind_flg, ";
$sql.= " tbl_product_live_training.training_kind_flg, ";
$sql.= " tbl_order_detail.pay_total, ";
$sql.= " tbl_order_detail.payment_status, ";
$sql.= " COUNT( tbl_order_detail.product_id ) AS buy_count, ";
$sql.= " SUM( tbl_order_detail.pay_total ) AS all_pay_total ";
$sql.= "FROM ";
$sql.= " tbl_product ";
$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
$sql.= " LEFT JOIN tbl_order_detail ON tbl_product.product_id=tbl_order_detail.product_id ";
$sql.= " LEFT JOIN student ON student.student_id=tbl_order_detail.member_id ";
$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
$sql.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
$sql.= " LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id ";
$where = "";
$where.= "WHERE ";
$where.= " tbl_order_detail.payment_status='2' ";
//----------------------------------------------------------
$group = "";
$group.= " GROUP BY ";
$group.= " tbl_product.product_id, ";
$group.= " tbl_product.product_name, ";
$group.= " tbl_product.product_code, ";
$group.= " tbl_product_add.product_type_add, ";
$group.= " tbl_order_detail.pay_total, ";
$group.= " tbl_order_detail.payment_status ";
//----------------------------------------------------------
$order = " ORDER BY COUNT( tbl_order_detail.product_id ) DESC,SUM( tbl_order_detail.pay_total ) DESC ";
//----------------------------------------------------------
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
if( 0<count($ret) ){
} else {
	print("end");
	exit();
}
for($i=0;$i<count($ret);$i++){
	$all_pay_total += $ret[$i]["all_pay_total"];
	$all_buy_count += $ret[$i]["buy_count"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



//ファイル名
$sFileName =         date('YmdHis').'.txt';
//ファイルパス
$sPath =             '/tmp/'.$sFileName;

//ファイルの存在確認
if(file_exists($sPath)){
    echo '・指定ファイルが既に存在しております'."\n";
    exit;
}else{
    echo '・ファイルの存在確認完了'."\n";
}

//ファイルを作成
if(touch($sPath)){
    echo '・ファイル作成完了'."\n";
}else{
    echo '・ファイル作成失敗'."\n";
    exit;
}

//ファイルのパーティションの変更
if(chmod($sPath,0644)){
    echo '・ファイルパーミッション変更完了'."\n";
}else{
    echo '・ファイルパーミッション変更失敗'."\n";
    exit;
}

//ファイルをオープン
//if($filepoint = fopen($sPath,"w")){
if($filepoint = fopen($sPath,"ab")){
    echo '・ファイルオープン完了'."\n";
}else{
    echo '・ファイルオープン失敗'."\n";
    exit;
}

//ファイルのロック
if(flock($filepoint, LOCK_EX)){
    echo '・ファイルロック完了'."\n";
}else{
    echo '・ファイルロック失敗'."\n";
    exit;
}

//ファイルへ書き込み
fwrite($filepoint,  "商品ID,商品名,商品コード,販売単価,販売数,合計,\r\n");
for($i=0;$i<count($ret);$i++){
	$temp = mb_convert_encoding('"'. $ret[$i]["product_id"]    . '",'.'"'. $ret[$i]["product_name"]  . '",'.'"'. $ret[$i]["product_code"]  . '",'.'"'. $ret[$i]["pay_total"]     . '",'.'"'. $ret[$i]["buy_count"]     . '",'.'"'. $ret[$i]["all_pay_total"] . '",'."\r\n", "SJIS", "UTF-8");
	fwrite($filepoint,  $temp);
}
$temp = mb_convert_encoding(',,,合計,"' . $all_buy_count . '","' . $all_pay_total . '",'."\r\n",                               "SJIS", "UTF-8");
fwrite($filepoint,  $temp);

//ファイルのアンロック
if(flock($filepoint, LOCK_UN)){
    echo '・ファイルアンロック完了'."\n";
}else{
    echo '・ファイルアンロック失敗'."\n";
    exit;
}

//ファイルを閉じる
if(fclose($filepoint)){
    echo '・ファイルクローズ完了'."\n";
}else{
    echo '・ファイルクローズ失敗'."\n";
    exit;
}

/*
$flag = fclose($filepoint);
	//----------------------------------------------------------
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_product_".date("YmdHis").".csv");

	echo mb_convert_encoding("商品ID,商品名,商品コード,販売単価,販売数,合計,\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		echo mb_convert_encoding('"'. $ret[$i]["product_id"]    . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["product_name"]  . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["product_code"]  . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["pay_total"]     . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["buy_count"]     . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["all_pay_total"] . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
	}
	echo mb_convert_encoding(','    ,                              "SJIS", "UTF-8");
	echo mb_convert_encoding(','    ,                              "SJIS", "UTF-8");
	echo mb_convert_encoding(','    ,                              "SJIS", "UTF-8");
	echo mb_convert_encoding('合計,',                              "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $all_buy_count . '",',  "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $all_pay_total . '",',  "SJIS", "UTF-8");
	echo mb_convert_encoding("\r\n",                               "SJIS", "UTF-8");
	//----------------------------------------------------------
*/

exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
