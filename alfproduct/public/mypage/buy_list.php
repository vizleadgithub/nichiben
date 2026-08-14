<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '購入履歴';

if (!st_login_check()){
	header("Location: /login/login.php");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.buylist.pagemax"]) && !empty($_SESSION["mypage.buylist.pagemax"]) ){
	$pagemax = $_SESSION["mypage.buylist.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["buylist.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["buylist.page"]) && !empty($_SESSION["buylist.page"]) ){
	$page = $_SESSION["buylist.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["buylist.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["mypage.buylist.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_buy_date = '';
if( isset($_SESSION["buylist.search_buy_date"]) && !empty($_SESSION["buylist.search_buy_date"]) ){
	$search_buy_date = $_SESSION["buylist.search_buy_date"];
}
if( isset($_GET["search_buy_date"]) && !empty($_GET["search_buy_date"]) ){
	$search_buy_date = $_GET["search_buy_date"];
	$_SESSION["buylist.search_buy_date"] = $search_buy_date;
}
if( isset($_GET["search_buy_date"]) && empty($_GET["search_buy_date"]) ){
	$search_buy_date = "";
	$_SESSION["buylist.search_buy_date"] = "";
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = _get_sql("COUNT(*) AS c", '', $search_buy_date);
$ret = $objDbConnect->query_fetch($sql);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}

$objPager->setListMax($all_count);
$objPager->setPagerUrl("?page=");
$pager = $objPager->getPager();
$offset = $objPager->getOffset();

$select_col = "
T1.order_id, 
T1.price, 
T1.payment_status, 
T1.receipt_flg, 
DATE_FORMAT(T1.payment_date, '%Y年%m月%d日') AS payment_date, 
T1.payment_type,
T2.product_name AS product_name_TOD,
T3.product_name AS product_name_TP
 ";

$order = "T1.order_date DESC";
$sql = _get_sql($select_col, $order, $search_buy_date);

/*
$sql = _get_sql("DATE_FORMAT(T3.payment_date, '%Y年%m月%d日') AS create_date, 
			T3.payment_date,
			DATE_FORMAT(  DATE_ADD( T3.payment_date, INTERVAL T2.open_period DAY ),  '%Y年%m月%d日') AS view_end_date, 
			DATE_FORMAT(  DATE_ADD( T3.payment_date, INTERVAL T2.open_period DAY ),  '%Y%m%d') AS view_end_ymd, 
			T1.product_id, 
			T2.term_id, 
			T2.product_name, 
			T2.open_period, 
			T2.play_time, 
			T2.price 
			", "ORDER BY T3.payment_date DESC", $search_buy_date);
*/

$ret = $objDbConnect->query_fetch_arr($sql.$offset);


// セレクトボックス作成
/*
$arr_select_box = array();

$start_year  = 2013;
$start_month = 4;
$end_year    = date('Y');
$end_month   = date('n')+1;
$loop_year    = $start_year;
$loop_month   = $start_month;
$arr_select_box_value[] = '';
$arr_select_box_text[] = '全て';
while ($loop_year!=$end_year || $loop_month!=$end_month){
	$arr_select_box_value[] = $loop_year.sprintf("%02d", $loop_month);
	$arr_select_box_text[] = $loop_year."/".$loop_month;
	$loop_month += 1;
	if ($loop_month == 13){
		$loop_month = 1;
		$loop_year += 1;
	}
}
*/

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);
//$template->assign('arr_select_box_value', $arr_select_box_value);
//$template->assign('arr_select_box_text', $arr_select_box_text);
$template->assign('search_buy_date', $search_buy_date);
$template->assign('all_count', $all_count);

$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('ymd', date("Ymd"));
$template->assign('csrf_token', csrf_token_get());

$template->layout_noside('mypage/buy_list.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function _get_sql($select_col, $order='', $search_buy_date=''){
	$sql = "SELECT ";
	$sql.= "   $select_col ";
	$sql.= " FROM ";
	$sql.= "   tbl_order AS T1 ";
	$sql.= "     INNER JOIN ";
	$sql.= "  (SELECT order_id, product_id, product_name FROM tbl_order_detail WHERE member_id = '".$_SESSION['user']['id']."' AND ( payment_status = 1 OR payment_status = 2 ) GROUP BY order_id ORDER BY order_detail_id ASC) AS T2 ";
	$sql.= "       ON T1.order_id = T2.order_id ";
	$sql.= "     INNER JOIN ";
	$sql.= "   tbl_product AS T3 ";
	$sql.= "       ON T2.product_id = T3.product_id ";
	$sql.= " WHERE ";
	$sql.= "   T1.del_flg = '0' ";
	//$sql.= "   AND T1.payment_status = '2' ";
	//$sql.= "   AND T1.price > 0 ";
	if ($order!=''){
		$sql.= " ORDER BY $order";
	}
	
	return $sql;
}
?>
