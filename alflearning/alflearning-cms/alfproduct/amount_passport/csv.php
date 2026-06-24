<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
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
$sql = "SELECT * FROM mtb_bar_association";
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_passport_target = array();
$sql = "SELECT passport_target_id, passport_target_name FROM mtb_passport_target ORDER BY rank ASC";
$temp_arr_passport_target = $objDbConnect->query_fetch_arr($sql);
if ($temp_arr_passport_target){
	foreach ($temp_arr_passport_target as $val){
		$arr_passport_target[$val['passport_target_id']] = $val['passport_target_name'];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_payment_status = array(
				array("id"=>"1",	"name"=>"入金済"),
				array("id"=>"2",	"name"=>"未入金"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_name = "";
$search_start_lawyer_number = "";
$search_end_lawyer_number = "";
$search_association = "";
$search_start_buy_date = "";
$search_end_buy_date = "";
$search_passport_target = array();
$search_payment_status = array();
$search_orderby = "";
if( isset($_SESSION["amount_passport.search_name"]) && !empty($_SESSION["amount_passport.search_name"]) ){
	$search_name = $_SESSION["amount_passport.search_name"];
}
if( isset($_SESSION["amount_passport.search_start_lawyer_number"]) && !empty($_SESSION["amount_passport.search_start_lawyer_number"]) ){
	$search_start_lawyer_number = $_SESSION["amount_passport.search_start_lawyer_number"];
}
if( isset($_SESSION["amount_passport.search_end_lawyer_number"]) && !empty($_SESSION["amount_passport.search_end_lawyer_number"]) ){
	$search_end_lawyer_number = $_SESSION["amount_passport.search_end_lawyer_number"];
}
if( isset($_SESSION["amount_passport.search_association"]) && !empty($_SESSION["amount_passport.search_association"]) ){
	$search_association = $_SESSION["amount_passport.search_association"];
}
if( isset($_SESSION["amount_passport.search_start_buy_date"]) && !empty($_SESSION["amount_passport.search_start_buy_date"]) ){
	$search_start_buy_date = $_SESSION["amount_passport.search_start_buy_date"];
}
if( isset($_SESSION["amount_passport.search_end_buy_date"]) && !empty($_SESSION["amount_passport.search_end_buy_date"]) ){
	$search_end_buy_date = $_SESSION["amount_passport.search_end_buy_date"];
}
if( isset($_SESSION["amount_passport.search_passport_target"]) && !empty($_SESSION["amount_passport.search_passport_target"]) ){
	$search_passport_target = $_SESSION["amount_passport.search_passport_target"];
}
if( isset($_SESSION["amount_passport.search_payment_status"]) && !empty($_SESSION["amount_passport.search_payment_status"]) ){
	$search_payment_status = $_SESSION["amount_passport.search_payment_status"];
}
if( isset($_SESSION["amount_passport.search_orderby"]) && !empty($_SESSION["amount_passport.search_orderby"]) ){
	$search_orderby = $_SESSION["amount_passport.search_orderby"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= " SELECT";
$sql.= "   tbl_order_detail.product_name AS product_name_TOD,";
$sql.= "   tbl_order_detail.payment_status,";
$sql.= "   tbl_order_detail.pay_total,";
$sql.= "   tbl_order.order_date,";
$sql.= "   tbl_product.product_name AS product_name_TP,";
$sql.= "   tbl_product_passport.passport_target,";
$sql.= "   student.lawyer_number,";
$sql.= "   student.student_name,";
$sql.= "   mtb_bar_association.name AS association_name";
$sql.= " FROM";
$sql.= "   tbl_order_detail";
$sql.= "     LEFT JOIN";
$sql.= "   tbl_order";
$sql.= "       ON tbl_order_detail.order_id = tbl_order.order_id";
$sql.= "     LEFT JOIN";
$sql.= "   tbl_product";
$sql.= "       ON tbl_order_detail.product_id = tbl_product.product_id";
$sql.= "     LEFT JOIN";
$sql.= "   tbl_product_passport";
$sql.= "       ON tbl_order_detail.product_id = tbl_product_passport.product_id";
$sql.= "     LEFT JOIN";
$sql.= "   student";
$sql.= "       ON tbl_order_detail.member_id = student.student_id";
$sql.= "     LEFT JOIN";
$sql.= "   mtb_bar_association";
$sql.= "       ON student.bar_association_id = mtb_bar_association.id";

$where = "";
$where.= " WHERE";
$where.= "   tbl_order_detail.product_type_add = 4";
$where.= "   AND tbl_order_detail.payment_status IN(1,2)";
$where.= "   AND tbl_order_detail.product_id IS NOT NULL";
$where.= "   AND tbl_order.payment_status <> 9";
//-----------------------------------
if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
} else {
	$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
}
//-----------------------------------
if( $search_name == "0" || $search_name == "" ){
} else {
	$where.= " AND student.student_name LIKE '%".$search_name."%' ";
}
//-----------------------------------
if( $search_start_lawyer_number == "0" || $search_start_lawyer_number == "" ){
} else {
	$where.= " AND student.lawyer_number >= CAST(".$search_start_lawyer_number." AS SIGNED) ";
}
if( $search_end_lawyer_number == "0" || $search_end_lawyer_number == "" ){
} else {
	$where.= " AND student.lawyer_number <= CAST(".$search_end_lawyer_number." AS SIGNED) ";
}
//-----------------------------------
if( $search_association == "0" || $search_association == "" ){
} else {
	$where.= " AND student.bar_association_id='".$search_association."' ";
}
//-----------------------------------
if( $search_start_buy_date != "" ){
	$where.= " AND tbl_order.order_date >= '".$search_start_buy_date."' ";
}
if( $search_end_buy_date != "" ){
	$where.= " AND tbl_order.order_date <= '".substr($search_end_buy_date,0,14)."59:59' ";
}
//-----------------------------------
$temp_where = "";
if( 0<count($search_passport_target) ){
	$temp_where.= " AND (";
	for($i=0;$i<count($search_passport_target);$i++){
		if($i>0){ $temp_where.= " OR "; }
		$temp_where.= " ( ";
		$temp_where.= " tbl_product_passport.passport_target LIKE '%|" . $search_passport_target[$i] . "|%' ";
		$temp_where.= " ) ";
	}
	$temp_where.= " ) ";
}
$where.= $temp_where;
//-----------------------------------
$temp_where = "";
if( 0<count($search_payment_status) ){
	$temp_where.= " AND (";
	for($i=0;$i<count($search_payment_status);$i++){
		if($i>0){ $temp_where.= " OR "; }
		if($search_payment_status[$i]=="1"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_order_detail.payment_status = '2' ";
			$temp_where.= " ) ";
		}
		if($search_payment_status[$i]=="2"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_order_detail.payment_status = '1' ";
			$temp_where.= " ) ";
		}
	}
	$temp_where.= " ) ";
}
$where.= $temp_where;
//-----------------------------------
$group = "";
//-----------------------------------
$order = "";
if( $search_orderby=="1" ){
	$order = " ORDER BY student.lawyer_number ASC ";
} elseif( $search_orderby=="2" ){
	$order = " ORDER BY student.lawyer_number DESC ";
} elseif( $search_orderby=="3" ){
	$order = " ORDER BY tbl_order_detail.payment_status ASC ";
} elseif( $search_orderby=="4" ){
	$order = " ORDER BY tbl_order_detail.payment_status DESC ";
} else {
	$order = " ORDER BY tbl_order_detail.order_detail_id DESC ";
}
//-----------------------------------
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
$ret_count = count($ret);
if( $ret_count <= 0 ){
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// CSVヘッダ
header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");
header("Content-Disposition: attachment; filename=amount_passport_".date("YmdHis").".csv");

echo mb_convert_encoding("登録番号,氏名,弁護士会,購入日,商品名,パスポート種別,入金,金額\r\n", "SJIS", "UTF-8");
for($i=0; $i<$ret_count; $i++){
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . $ret[$i]["lawyer_number"] . '",', "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $ret[$i]["student_name"] . '",', "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $ret[$i]["association_name"] . '",', "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $ret[$i]["order_date"] . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	if ($ret[$i]["product_name_TOD"] != ''){
		$product_name = $ret[$i]["product_name_TOD"];
	} else {
		$product_name = $ret[$i]["product_name_TP"];
	}
	echo mb_convert_encoding('"' . $product_name . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	$disp_passport_target = '';
	$arr_pt = array();
	if ($ret[$i]['passport_target'] != ''){
		$arr_pt = explode('|', trim($ret[$i]['passport_target'], '|'));
		foreach ($arr_pt as $val1){
			$disp_passport_target.= $arr_passport_target[$val1] . '|';
		}
		$disp_passport_target = rtrim($disp_passport_target, '|');
	}
	echo mb_convert_encoding('"' . $disp_passport_target . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	$str_payment_status = "";
	if ($ret[$i]["payment_status"] == '0'){
		$str_payment_status = $arr_payment_status[1]['name'];
	} elseif ($ret[$i]["payment_status"] == '1'){
		$str_payment_status = $arr_payment_status[1]['name'];
	} elseif ($ret[$i]["payment_status"] == '2'){
		$str_payment_status = $arr_payment_status[0]['name'];
	}
	echo mb_convert_encoding('"' . $str_payment_status . '",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding('"' . number_format($ret[$i]["pay_total"]) . '円",', "SJIS", "UTF-8");
	//++++++++++++++++++++
	echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
	//++++++++++++++++++++
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
