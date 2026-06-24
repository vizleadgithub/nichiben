<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
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
$search_start_date = "";
$search_end_date = "";

//$arr_postdata = unserialize(str_replace('&amp;','&',str_replace('&quot;','"',str_replace('&#039;',"'",str_replace('&apos;',"'",str_replace('&lt;','<',str_replace('&gt;','>',$_GET["data"])))))));
//var_dump($arr_postdata);
//exit();

if( isset($_SESSION["amount_product.search_start_date"]) && !empty($_SESSION["amount_product.search_start_date"]) ){
	$search_start_date = $_SESSION["amount_product.search_start_date"];
}
if( isset($_SESSION["amount_product.search_end_date"]) && !empty($_SESSION["amount_product.search_end_date"]) ){
	$search_end_date = $_SESSION["amount_product.search_end_date"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.product_id, ";
$sql.= "tbl_order_detail.sell_price, ";
$sql.= "SUM(tbl_order_detail.unit) as unit, ";
$sql.= "SUM(tbl_order_detail.pay_total) as pay_total, ";
//$sql.= "DATE_FORMAT(tbl_order_detail.create_date,'%Y/%m/%d') as buy_date, ";
$sql.= "tbl_product.product_code, ";
$sql.= "tbl_product.product_name, ";
$sql.= "student.school_id ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";

$where = "";
$where.= "WHERE ";
$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
$where.= " and tbl_order_detail.product_id IS NOT NULL ";
if( $search_start_date != "" ){
	$where.= " and tbl_order_detail.create_date>='".$search_start_date."' ";
}
if( $search_end_date != "" ){
	$where.= " and tbl_order_detail.create_date<='".substr($search_end_date,0,14)."59:59' ";
}
$group = "";
$group.= " GROUP BY tbl_order_detail.product_id,tbl_order_detail.sell_price,tbl_product.product_code,tbl_product.product_name,student.school_id ";
$offset = "";
$order = " ORDER BY SUM(tbl_order_detail.pay_total) DESC,SUM(tbl_order_detail.unit) DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
if( 0<count($ret) ){
} else {
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($ret);
//exit();
// CSVヘッダ
$pay_total_total = 0;
$unit_total = 0;
header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");
header("Content-Disposition: attachment; filename=amount_product_".date("YmdHis").".csv");

 	 	 	 	
echo mb_convert_encoding("商品CD,商品名,販売単価,販売数,合計\r\n", "SJIS", "UTF-8");
for($i=0;$i<count($ret);$i++){
	echo mb_convert_encoding('"' . $ret[$i]["product_code"] . '","' . $ret[$i]["product_name"] . '","' . $ret[$i]["sell_price"] . '","' . $ret[$i]["unit"] . '","' . $ret[$i]["pay_total"] . '",' ."\r\n", "SJIS", "UTF-8");
	$pay_total_total += $ret[$i]["pay_total"];
	$unit_total += $ret[$i]["unit"];
}
echo mb_convert_encoding("合計,,,".$unit_total.",".$pay_total_total."\r\n", "SJIS", "UTF-8");
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
