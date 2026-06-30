<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '受講履歴(会場研修)';

if (!st_login_check()){
	header("Location: /");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp_mtb_bar_association = get_mtb_bar_association();
foreach ($tmp_mtb_bar_association as $key => $val){
	$mtb_bar_association[$key] = $val;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.lesson_list2.pagemax"]) && !empty($_SESSION["mypage.lesson_list2.pagemax"]) ){
	$pagemax = $_SESSION["mypage.lesson_list2.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["lesson_list2.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["lesson_list2.page"]) && !empty($_SESSION["lesson_list2.page"]) ){
	$page = $_SESSION["lesson_list2.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["lesson_list2.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["mypage.lesson_list2.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sort = 1;
if( isset($_SESSION["lesson_list2.sort"]) && !empty($_SESSION["lesson_list2.sort"]) ){
	$sort = $_SESSION["lesson_list2.sort"];
}
if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
	$sort = $_GET["sort"];
	$_SESSION["lesson_list2.sort"] = $sort;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$search_buy_date = '';
if( isset($_SESSION["lesson_list2.search_buy_date"]) && !empty($_SESSION["lesson_list2.search_buy_date"]) ){
	$search_buy_date = $_SESSION["lesson_list2.search_buy_date"];
}
if( isset($_GET["search_buy_date"]) && !empty($_GET["search_buy_date"]) ){
	$search_buy_date = $_GET["search_buy_date"];
	$_SESSION["lesson_list2.search_buy_date"] = $search_buy_date;
}
if( isset($_GET["search_buy_date"]) && empty($_GET["search_buy_date"]) ){
	$search_buy_date = "";
	$_SESSION["lesson_list2.search_buy_date"] = "";
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期化
$all_count = 0;
$student_id = $_SESSION['user']['id'];
$arr_list = array();

$where = "
WHERE 
  T1.member_id = '$student_id'
  AND ( T1.payment_status = 1 OR T1.payment_status = 2 )
  AND T1.product_type_add = '2'
 ";

$sql = "SELECT COUNT(DISTINCT T1.product_id) AS c FROM tbl_order_detail AS T1 ";
$ret = $objDbConnect->query_fetch($sql.$where);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}

$objPager->setListMax($all_count);
$objPager->setPagerUrl("?sort=".$sort."&page=");
$pager = $objPager->getPager();
$offset = $objPager->getOffset();

$sql = "
SELECT DISTINCT
  T1.participation_flg,
  T1.product_name AS product_name_TOD,
  T2.product_id,
  T2.start_date,
  T2.end_date,
  T2.del_flg,
  T2.product_name AS product_name_TP,
  T3.name AS bar_association_name,
  T4.sponsor,
  DATE_FORMAT(RPBA.dates, '%Y/%m/%d') AS dates
FROM
  tbl_order_detail AS T1
    LEFT JOIN
  tbl_product AS T2
      ON T1.product_id = T2.product_id
    LEFT JOIN
  tbl_product_live_training AS T4
      ON T1.product_id = T4.product_id
    LEFT JOIN
  mtb_bar_association AS T3
      ON T1.bar_association_id = T3.id
    LEFT JOIN
  (SELECT * FROM rel_product_bar_association WHERE atype = 1) AS RPBA
      ON T1.product_id = RPBA.product_id
 ";

$order = " ORDER BY";
if ($sort == 1){
	$order.= " CASE WHEN T2.end_date IS NULL THEN 1 ELSE 0 END, T2.end_date ASC";
	
} else if ($sort == 2){
	$order.= " T2.start_date ASC";
	
} else if ($sort == 3){
	$order.= " T2.start_date DESC";
	
} else {
	$order = "";
	
}

$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
if ($ret){
	foreach ($ret as $key => $val){
		$arr_list[$key] = $val;
		
		$koukai_flg = false;
		
		// 公開期間内商品フラグ
		if ($val["start_date"] == '' && $val["end_date"] == ''){
			$koukai_flg = true;
			
		} else if ($val["start_date"] != '' && $val["end_date"] != ''){
			$u_now_date = time();
			$u_start_date = strtotime($val["start_date"]);
			$u_end_date = strtotime($val["end_date"]);
			if ($u_now_date >= $u_start_date && $u_now_date <= $u_end_date){
				$koukai_flg = true;
			}
		} else if ($val["end_date"] != ''){
			$u_now_date = time();
			$u_end_date = strtotime($val["end_date"]);
			if ($u_now_date <= $u_end_date){
				$koukai_flg = true;
			}
		}
		if ($val["del_flg"] == '1'){
			$koukai_flg = false;
		}
		$arr_list[$key]["koukai_flg"] = $koukai_flg;
		
		// 実施会(主催)
		$disp_sponsor = '';
		if ($val["sponsor"] != ''){
			$arr_sponsor = array();
			$arr_sponsor = explode('|', trim($val["sponsor"], '|'));
			if ($arr_sponsor){
				foreach ($arr_sponsor as $sponsor){
					$disp_sponsor.= htmlspecialchars($mtb_bar_association[$sponsor], ENT_QUOTES, 'UTF-8') . '<br />';
				}
				$disp_sponsor = rtrim($disp_sponsor, '<br />');
			}
			
			// 【】日弁連を含む複数主催の場合、日弁連のみ表示とする ----------------------
			$nichibenren_name = '日弁連';
			if( strstr($disp_sponsor, $nichibenren_name) ){
				$disp_sponsor = $nichibenren_name;
			}
			// 【】日弁連を含む複数主催の場合、日弁連のみ表示とする ----------------------
			
		}
		$arr_list[$key]["disp_sponsor"] = $disp_sponsor;
	}
}

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $arr_list);
$template->assign('all_count', $all_count);

$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('sort_select', _get_sort_selectbox());
$template->assign('sort', $sort);

$template->assign('ymd', date("Ymd"));

$template->layout_noside('mypage/lesson_list2.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function _get_sort_selectbox(){
	return array(
		'1' => '受講率の多い順',
		'2' => '掲載終了間近順',
		'3' => '掲載開始日(古い順)',
		'4' => '掲載開始日(新しい順)',
		'5' => '受講開始日順',
		'6' => '受講終了日順'
	);
}
?>