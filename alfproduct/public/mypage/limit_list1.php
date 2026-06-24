<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '掲載終了間近の講座(購入済み)';

if (!st_login_check()){
	header("Location: /");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.limit_list1.pagemax"]) && !empty($_SESSION["mypage.limit_list1.pagemax"]) ){
	$pagemax = $_SESSION["mypage.limit_list1.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["limit_list1.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["limit_list1.page"]) && !empty($_SESSION["limit_list1.page"]) ){
	$page = $_SESSION["limit_list1.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["limit_list1.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["mypage.limit_list1.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_buy_date = '';
/*
if( isset($_SESSION["limit_list1.search_buy_date"]) && !empty($_SESSION["limit_list1.search_buy_date"]) ){
	$search_buy_date = $_SESSION["limit_list1.search_buy_date"];
}
if( isset($_GET["search_buy_date"]) && !empty($_GET["search_buy_date"]) ){
	$search_buy_date = $_GET["search_buy_date"];
	$_SESSION["limit_list1.search_buy_date"] = $search_buy_date;
}
if( isset($_GET["search_buy_date"]) && empty($_GET["search_buy_date"]) ){
	$search_buy_date = "";
	$_SESSION["limit_list1.search_buy_date"] = "";
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
T1.product_type_add,
T1.product_name AS product_name_TOD,
T2.product_id,
T2.product_name AS product_name_TP,
DATE_FORMAT(T2.end_date, '%Y/%m/%d') AS end_date,
T2.contents_contents1,
T2.contents_contents2,
T2.contents_contents3,
T2.contents_contents4,
T2.contents_contents5,
T2.contents_contents6,
T2.contents_contents7,
T2.contents_contents8,
T2.contents_contents9,
T2.contents_contents10,
T2.contents_contents11,
T2.contents_contents12,
T2.contents_contents13,
T2.contents_contents14,
T2.contents_contents15,
T2.contents_contents16,
T2.contents_contents17,
T2.contents_contents18,
T2.contents_contents19,
T2.contents_contents20,
T2.contents_contents21,
T2.contents_contents22,
T2.contents_contents23,
T2.contents_contents24,
T2.contents_contents25
";

$order = "T2.end_date ASC";

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

$arr_list = array();
foreach ($ret as $key => $val){
	$arr_list[$key] = $val;
	
	// テストの合否・進捗の初期値
	$arr_list[$key]["test_passing"] = '-';
	$arr_list[$key]["test_progress"] = '-';
	
	// eラーニングの場合
	if ($val['product_type_add'] == 1){
		$disp_complete  = '';
		$diap_reading_date = '';
		$first_flg = true;
		$u_reading_date = '';
		$view_flg = false;
		$$prev_percent = 0;
		
		for($i=1; $i<=MAX_CONTENTS; $i++){
			$sql = "SELECT percent, complete_flag, UNIX_TIMESTAMP(reading_date) AS u_reading_date FROM report_user_video_viewed WHERE student_id = '".$_SESSION['user']['id']."' AND video_id = '".$val["contents_contents$i"]."'";
			$history_view = $objDbConnect->query_fetch($sql);
			if ($history_view){
				$view_flg = true;
				
				// 最大閲覧率
				if ($history_view["complete_flag"]!=1 && $history_view["percent"]>$prev_percent){
					$disp_complete = $history_view["percent"].'％';
					$prev_percent = $history_view["percent"];
				}
				
				// 直近再生日時
				if ($first_flg){
					$u_reading_date = $history_view["u_reading_date"];
					$first_flg = false;
				} else {
					if ($u_reading_date < $history_view["u_reading_date"]){
						$u_reading_date = $history_view["u_reading_date"];
					}
				}
				
			}
		}
		
		if ($disp_complete=='' && $view_flg){
			$disp_complete  = '完了';
		} else if ($disp_complete=='' && !$view_flg){
			$disp_complete  = '未受講';
		}
		
		if ($u_reading_date!=''){
			$diap_reading_date = date('Y/m/d', $u_reading_date);
		} else {
			$diap_reading_date = '-';
		}
		
		$arr_list[$key]['disp_complete'] = $disp_complete;
		$arr_list[$key]['diap_reading_date'] = $diap_reading_date;
		
		// テストの合否・進捗(設問付きeラーニングの場合)
		$sql = "SELECT product_kind_flg FROM tbl_product_elearning WHERE product_id='".$val['product_id']."'";
		$res_tbl_product_elearning = $objDbConnect->query_fetch($sql);
		if ($res_tbl_product_elearning){
			if($res_tbl_product_elearning['product_kind_flg']=='3'){
				$arr_list[$key]["test_progress"] = '0%';
				
				// すべてのテストを受講済みかどうかチェック
				$product_contents_total = 0;
				$sql = "SELECT COUNT(*) AS count FROM rel_product_contents WHERE product_id='".$val['product_id']."' AND exam_id_test>0";
				$res_rel_product_contents = $objDbConnect->query_fetch($sql);
				if($res_rel_product_contents){
					$product_contents_total = $res_rel_product_contents['count'];
				}
				
				$exam_answer_total = 0;
				$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id='".$val['product_id']."' AND student_id='".$_SESSION['user']['id']."' GROUP BY contents_no";
				$res_exam_answer = $objDbConnect->query_fetch_arr($sql);
				if($res_exam_answer){
					$exam_answer_total = count($res_exam_answer);
				}
				
				// すべて受講済みの場合
				if($product_contents_total>0 && $exam_answer_total>0 && ($product_contents_total==$exam_answer_total)){
					// 合格しているか
					$arr_list[$key]["test_passing"] = '合';
					foreach($res_exam_answer as $val_rea){
						if($val_rea['passing_flg']=='0'){
							$arr_list[$key]["test_passing"] = '否';
							break;
						}
					}
					$arr_list[$key]["test_progress"] = '100%';
					
				// 受講済みでなく、テストが設定してある場合は進捗を算出
				} elseif($product_contents_total>0){
					if($exam_answer_total>0){
						$cal_result = $exam_answer_total / $product_contents_total * 100;
						$arr_list[$key]["test_progress"] = round($cal_result).'%';
					}
				}
			}
		}
	}
	
}

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
$template->assign('arr_list', $arr_list);
//$template->assign('arr_select_box_value', $arr_select_box_value);
//$template->assign('arr_select_box_text', $arr_select_box_text);
$template->assign('search_buy_date', $search_buy_date);
$template->assign('all_count', $all_count);

$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('ymd', date("Ymd"));

$template->layout_noside('mypage/limit_list1.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function _get_sql($select_col, $order='', $search_buy_date=''){
	$sql = "SELECT";
	$sql.= "   $select_col";
	$sql.= " FROM";
	$sql.= "   tbl_order_detail AS T1";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product AS T2";
	$sql.= "       ON T1.product_id = T2.product_id";
	$sql.= " WHERE";
	$sql.= "   T1.payment_status = '2'";
	$sql.= "   AND T1.product_type_add IN(1,2)";
	$sql.= "   AND T1.member_id = '".$_SESSION['user']['id']."'";
	$sql.= "   AND T2.del_flg = '0'";
	$sql.= "   AND ( (DATE_SUB(T2.end_date, INTERVAL 1 MONTH) <= '".date("Y-m-d")."') )";
	$sql.= "   AND ( (T2.start_date<='".date("Y-m-d")."' AND T2.end_date>='".date("Y-m-d")."') OR (T2.start_date<='".date("Y-m-d")."' AND T2.end_date IS NULL) OR (T2.start_date IS NULL AND T2.end_date>='".date("Y-m-d")."') OR (T2.start_date IS NULL AND T2.end_date IS NULL) )";
	if ($order!=''){
		$sql.= " ORDER BY $order";
	}
	
	return $sql;
}
?>