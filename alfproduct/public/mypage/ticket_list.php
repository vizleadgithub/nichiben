<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$_SESSION['wp_page_head_title'] = '受講票ダウンロード';

if (!st_login_check()){
	header("Location: /");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.ticket_download.pagemax"]) && !empty($_SESSION["mypage.ticket_download.pagemax"]) ){
	$pagemax = $_SESSION["mypage.ticket_download.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["ticket_download.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["ticket_download.page"]) && !empty($_SESSION["ticket_download.page"]) ){
	$page = $_SESSION["ticket_download.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["ticket_download.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["mypage.ticket_download.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_buy_date = '';
/*
if( isset($_SESSION["ticket_download.search_buy_date"]) && !empty($_SESSION["ticket_download.search_buy_date"]) ){
	$search_buy_date = $_SESSION["ticket_download.search_buy_date"];
}
if( isset($_GET["search_buy_date"]) && !empty($_GET["search_buy_date"]) ){
	$search_buy_date = $_GET["search_buy_date"];
	$_SESSION["ticket_download.search_buy_date"] = $search_buy_date;
}
if( isset($_GET["search_buy_date"]) && empty($_GET["search_buy_date"]) ){
	$search_buy_date = "";
	$_SESSION["ticket_download.search_buy_date"] = "";
}
*/
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
T1.order_detail_id,
T1.product_name AS product_name_TOD,
T1.price,
DATE_FORMAT(T1.payment_date, '%Y/%m/%d') AS payment_date,
T3.product_id,
T3.product_name AS product_name_TP,
T3.del_flg,
T4.hall,
DATE_FORMAT(T4.dates, '%Y/%m/%d') AS dates,
T6.name,
T2.download_flg,
T1.ticket_flg ";

$order = "T4.dates DESC";

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
foreach($ret as $index=>$row){
	$ret[$index]["download_ok"] = 1;

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
	$sql.= " AND tbl_order_detail.order_detail_id='".$row["order_detail_id"]."' ";
	$sql.= " AND ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) ";
	$ret_order = $objDbConnect->query_fetch_arr($sql);
	if (!$ret_order){
		$ret[$index]["download_ok"] = 0;
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
		$ret[$index]["download_ok"] = 0;
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
		$ret[$index]["download_ok"] = 0;
	}
	
}

//var_dump($ret);

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

$template->layout_noside('mypage/ticket_list.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function _get_sql($select_col, $order='', $search_buy_date=''){
	$sql = "SELECT";
	$sql.= "   $select_col";
	$sql.= " FROM";
	$sql.= "   tbl_order_detail AS T1";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product_live_training AS T2";
	$sql.= "       ON T1.product_id = T2.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product AS T3";
	$sql.= "       ON T2.product_id = T3.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   rel_product_bar_association_branch AS T4";
	$sql.= "       ON T1.product_id = T4.product_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   mtb_bar_association_branch AS T5";
	$sql.= "       ON T4.bar_association_branch_id = T5.bar_association_branch_id";
	$sql.= "     LEFT JOIN";
	$sql.= "   mtb_bar_association AS T6";
	$sql.= "       ON T5.bar_association_id = T6.id";
	$sql.= " WHERE";
	//$sql.= "   T1.payment_status IN (1,2)";
	$sql.= "   ( T1.payment_status =1 OR T1.payment_status = 2 )";
	$sql.= "   AND T1.product_type_add = '2'";
	$sql.= "   AND T1.member_id = '".$_SESSION['user']['id']."'";
	$sql.= "   AND T2.download_flg = '1'";
	//$sql.= "   AND T3.del_flg = '0'";
	$sql.= "   AND T1.bar_association_branch_id = T4.bar_association_branch_id";
	if ($order!=''){
		$sql.= " ORDER BY $order";
	}
	
	return $sql;
}
?>