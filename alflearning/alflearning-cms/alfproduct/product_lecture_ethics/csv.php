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
/*
$sql = "SELECT";
$sql.= "  T1.term_id,";
$sql.= "  T1.name,";
$sql.= "  T2.parent";
$sql.= " FROM";
$sql.= "  wp_terms AS T1";
$sql.= "   JOIN";
$sql.= "  wp_term_taxonomy AS T2";
$sql.= "   ON T1.term_id = T2.term_id";
$sql.= "";
$sql.= " ORDER BY T1.slug ASC";
$arr_category = $objDbConnect->query_fetch_arr($sql);
//var_dump($arr_category);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_start_date = "";
$search_end_date = "";

//$arr_postdata = unserialize(str_replace('&amp;','&',str_replace('&quot;','"',str_replace('&#039;',"'",str_replace('&apos;',"'",str_replace('&lt;','<',str_replace('&gt;','>',$_GET["data"])))))));
//var_dump($arr_postdata);
//exit();

if( isset($_SESSION["product_lecture_info.search_start_date"]) && !empty($_SESSION["product_lecture_info.search_start_date"]) ){
	$search_start_date = $_SESSION["product_lecture_info.search_start_date"];
}
if( isset($_SESSION["product_lecture_info.search_end_date"]) && !empty($_SESSION["product_lecture_info.search_end_date"]) ){
	$search_end_date = $_SESSION["product_lecture_info.search_end_date"];
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品ID
$pid = '';
if(isset($_REQUEST["pid"])){
	$pid = $_REQUEST["pid"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 弁護士会支部ID
//$aid = '';
//$atype = '';
//if(isset($_REQUEST["aid"])){
//	$aid = $_REQUEST["aid"];
//}
//if(isset($_REQUEST["atype"])){
//	$atype = $_REQUEST["atype"];
//}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// CSV種類
$type = '';
if(isset($_REQUEST["type"])){
	$type = $_REQUEST["type"];
}
if (strlen($type) == 0) {
	header("Location: /index.php");
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.product_id, ";
$sql.= "tbl_order_detail.sell_price, ";
$sql.= "SUM(tbl_order_detail.unit) as unit, ";
$sql.= "SUM(tbl_order_detail.pay_total) as pay_total, ";
//$sql.= "DATE_FORMAT(tbl_order_detail.create_date,'%Y/%m/%d') as buy_date, ";
$sql.= "tbl_product.product_code, ";
$sql.= "tbl_product.product_name, ";
$sql.= "tbl_product.term_id, ";
$sql.= "student.school_id ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";
$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";

$where = "";
$where.= "WHERE ";
$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
$where.= " and tbl_order_detail.product_id IS NOT NULL ";
$where.= " and tbl_order.payment_status ='2' ";
if( $search_start_date != "" ){
	$where.= " and tbl_order.payment_date>='".$search_start_date."' ";
}
if( $search_end_date != "" ){
	$where.= " and tbl_order.payment_date<='".substr($search_end_date,0,14)."59:59' ";
}
$group = "";
$group.= " GROUP BY tbl_order_detail.product_id,tbl_order_detail.sell_price,tbl_product.product_code,tbl_product.product_name,student.school_id ";
$offset = "";
$order = " ORDER BY SUM(tbl_order_detail.pay_total) DESC,SUM(tbl_order_detail.unit) DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
if( 0<count($ret) ){
} else {
	// header("Location: /index.php");
	// exit();
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($ret);
//exit();
// CSVヘッダ
$pay_total_total = 0;
$unit_total = 0;

header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");

if ($type == 'info') {
	header("Content-Disposition: attachment; filename=product_lecture_ethics_info_".date("YmdHis").".csv");
	
	$ret = _get_product_lecture_ethics_info($objDbConnect, $pid);
	
	$tmp_mtb_bar_association = get_mtb_bar_association();
	foreach ($tmp_mtb_bar_association as $key => $val){
		$mtb_bar_association[$key] = $val;
	}
	
	echo mb_convert_encoding("受付日,ステイタス,完了,登録番号,氏名,所属弁護士会\r\n", "SJIS", "UTF-8");
	foreach ($ret as $val){
		// 各項目の整形
		$disp_status = '';
		if ($val['disp_status_reserv'] != ''){
			$disp_status = $val['disp_status_reserv'];
		} else {
			$disp_status = $val['disp_status'];
		}
		
		
		// 一行ずつ書き込み
		$write_data = '';
		$write_data = '"'.$val['create_date'].'",'.'"'.$disp_status.'",'.'"'.$val['disp_complete'].'",'.'"'.$val['lawyer_number'].'",'.'"'.$val['student_name'].'","'.$mtb_bar_association[$val['bar_association_id']].'"';
		$write_data.= "\r\n";
		echo mb_convert_encoding($write_data, "SJIS", "UTF-8");
	}
	
}
else if ($type == 'info_list') {
	header("Content-Disposition: attachment; filename=product_lecture_ethics_info_list_".date("YmdHis").".csv");
	
	$ret = _get_product_lecture_ethics_info($objDbConnect, $pid);
	
	echo mb_convert_encoding("登録番号,氏名\r\n", "SJIS", "UTF-8");
	foreach ($ret as $val){
		// 一行ずつ書き込み
		$write_data = '';
		$write_data = '"'.$val['lawyer_number'].'",'.'"'.$val['student_name'].'"';
		$write_data.= "\r\n";
		echo mb_convert_encoding($write_data, "SJIS", "UTF-8");
	}
}
else {
	
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/**
 * 
 * ※現状info.phpのsqlと同じ
 */
function _get_product_lecture_ethics_info($objDbConnect, $pid){
	$arr_list = array();
	$sql = "
	SELECT
	  T1.student_id,
	  T1.product_id,
	  DATE_FORMAT(T1.create_date, '%Y/%m/%d') AS create_date,
	  T1.status,
	  T1.complete_flg,
	  T2.student_id,
	  T2.student_name,
	  T2.lawyer_number,
	  T2.bar_association_id,
	  T3.status AS status_reserv
	FROM
	  tbl_ethic_question_history AS T1
	    LEFT JOIN
	  student AS T2
	      ON T1.student_id = T2.student_id
	    LEFT JOIN
	  tbl_status_change_ethic AS T3
	      ON T1.student_id = T3.student_id
	         AND T1.product_id = T3.product_id
	WHERE
	  T1.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."'
	";

	$result = $objDbConnect->query_fetch_arr($sql);
	if ($result){
		foreach ($result AS $key => $val){
			$arr_list[$key] = $val;
			// ステイタス
			$arr_list[$key]['disp_status'] = get_str_ethic_status_kouza($val['status']);
			// 完了
			if ($val['complete_flg'] == 1){
				$arr_list[$key]['disp_complete'] = '済';
			} else {
				$arr_list[$key]['disp_complete'] = '未';
			}
			// 管理者によってステータスが変更されている場合
			if ($val['status_reserv'] != ''){
				$arr_list[$key]['disp_status_reserv'] = get_str_ethic_status_kouza($val['status_reserv']);
			} else {
				$arr_list[$key]['disp_status_reserv'] = '';
			}
		}
	}
	
	return $arr_list;
}
?>
