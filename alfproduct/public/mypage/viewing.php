<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '受講中の講座';

if (!st_login_check()){
	header("Location: /");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.viewing.pagemax"]) && !empty($_SESSION["mypage.viewing.pagemax"]) ){
	$pagemax = $_SESSION["mypage.viewing.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["viewing.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["viewing.page"]) && !empty($_SESSION["viewing.page"]) ){
	$page = $_SESSION["viewing.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["viewing.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["mypage.viewing.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sort = 1;
if( isset($_SESSION["viewing.sort"]) && !empty($_SESSION["viewing.sort"]) ){
	$sort = $_SESSION["viewing.sort"];
}
if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
	$sort = $_GET["sort"];
	$_SESSION["viewing.sort"] = $sort;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$search_buy_date = '';
if( isset($_SESSION["viewing.search_buy_date"]) && !empty($_SESSION["viewing.search_buy_date"]) ){
	$search_buy_date = $_SESSION["viewing.search_buy_date"];
}
if( isset($_GET["search_buy_date"]) && !empty($_GET["search_buy_date"]) ){
	$search_buy_date = $_GET["search_buy_date"];
	$_SESSION["viewing.search_buy_date"] = $search_buy_date;
}
if( isset($_GET["search_buy_date"]) && empty($_GET["search_buy_date"]) ){
	$search_buy_date = "";
	$_SESSION["viewing.search_buy_date"] = "";
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期化
$all_count = 0;
$get_flg = true;
$arr_viewd_video = array(); // ビデオ情報
$arr_list = array(); // ビデオに紐付く商品情報
$student_id = $_SESSION['user']['id'];

$sql = "
SELECT 
  count(student_product_history.product_id) AS c 
FROM 
 student_product_history 
 LEFT JOIN tbl_product ON tbl_product.product_id=student_product_history.product_id 
WHERE 
 1=1 
 AND student_product_history.student_id='$student_id' 
 AND student_product_history.total_time<>student_product_history.viewed_time 
 AND ROUND( (TIME_TO_SEC(student_product_history.viewed_time) / TIME_TO_SEC(student_product_history.total_time))*100 )<100
 AND (tbl_product.start_date<='".date("Y-m-d H:i:s")."' OR tbl_product.start_date IS null) 
 AND (tbl_product.end_date >='".date("Y-m-d H:i:s")."' OR tbl_product.end_date IS null ) 
";
$ret = $objDbConnect->query_fetch($sql);
if ($ret){
	$all_count = $ret["c"];
} else {
	$all_count = 0;
}

$ret = array();
$sql = "
SELECT 
  student_product_history.product_id 
 ,tbl_product.product_name 
 ,tbl_product.start_date 
 ,tbl_product.end_date 
 ,student_product_history.viewed_time
 ,student_product_history.total_time
 ,ROUND( (TIME_TO_SEC(student_product_history.viewed_time) / TIME_TO_SEC(student_product_history.total_time))*100 ) AS max_percent 
 ,TIME_FORMAT( TIMEDIFF(student_product_history.total_time,student_product_history.viewed_time),'%H:%i:%s') AS all_remaining 
 ,student_product_history.last_activity AS reading_date 
FROM 
 student_product_history 
 LEFT JOIN tbl_product ON tbl_product.product_id=student_product_history.product_id 
WHERE 
 1=1 
 AND student_product_history.student_id='$student_id' 
 AND student_product_history.total_time<>student_product_history.viewed_time 
 AND ROUND( (TIME_TO_SEC(student_product_history.viewed_time) / TIME_TO_SEC(student_product_history.total_time))*100 )<100
 AND (tbl_product.start_date<='".date("Y-m-d H:i:s")."' OR tbl_product.start_date IS null) 
 AND (tbl_product.end_date >='".date("Y-m-d H:i:s")."' OR tbl_product.end_date IS null ) 
";
if ($sort == 1){ // 最終受講日順
	$sql.= " ORDER BY reading_date DESC ";
} else if ($sort == 2){ // 受講率の多い順
	$sql.= " ORDER BY max_percent DESC ";
} else if ($sort == 3){ // 掲載終了間近順
	$sql.= " ORDER BY tbl_product.end_date DESC ";
} else if ($sort == 4){ // 掲載開始日(古い順)
	$sql.= " ORDER BY tbl_product.start_date DESC ";
} else if ($sort == 5){ // 掲載開始日(新しい順)
	$sql.= " ORDER BY tbl_product.start_date ASC ";
}
if($pagemax>0){
	$sql.= " LIMIT ".$pagemax." OFFSET ".(($page * $pagemax)-$pagemax)."";
}
$ret = $objDbConnect->query_fetch_arr($sql);
if ($ret){
	$arr_sort = array();
	foreach ($ret as $key => $val){
		$arr_list[$key] = $val;
		
		$res_duration_reading   = false; // 視聴済み時間取得用
		$all_alfstream_duration = '00:00:00'; // 総再生時間計算用
		$all_duration_reading   = '00:00:00'; // 総視聴済み時間計算用
		$prev_percent = 0; // 最大閲覧率の最大値判断用
		$first_flg = true;
		$all_complete_flg = true;
		$koukai_flg = false;
		

		$arr_list[$key]["product_name_TP"] = $val["product_name"];
		$all_alfstream_duration = $val["total_time"];
		$arr_list[$key]["total_duration"] = $all_alfstream_duration;
		$all_duration_reading = $val["viewed_time"];
		$arr_list[$key]["reading_date"] = date('Y/m/d', strtotime($val["reading_date"]));
		$arr_list[$key]["all_remaining"] = $val["all_remaining"];
		$arr_list[$key]["max_percent"] = $val["max_percent"];
		
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
		$arr_list[$key]["koukai_flg"] = $koukai_flg;
		
		// テストの合否・進捗(設問付きeラーニングの場合)
		$arr_list[$key]["test_passing"] = '-';
		$arr_list[$key]["test_progress"] = '-';
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
				$sql = "SELECT passing_flg FROM exam_answer WHERE status=0 AND question_flg=0 AND product_id='".$val['product_id']."' AND student_id='".$student_id."' GROUP BY contents_no";
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
//var_dump($arr_list);

// ページャー
$objPager->setListMax($all_count);
$objPager->setPagerUrl("?sort=".$sort."&page=");
$pager = $objPager->getPager();

$arr_disp_list = $arr_list;

$objDbConnect->close();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $arr_disp_list);
$template->assign('all_count', $all_count);

$template->assign('list_start', $objPager->getOffsetStart());
$template->assign('list_end', $objPager->getOffsetEnd());
$template->assign('page_max', $pagemax);

$template->assign('sort_select', _get_sort_selectbox());
$template->assign('sort', $sort);

$template->assign('ymd', date("Ymd"));

$template->layout_noside('mypage/viewing.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function _get_sort_selectbox(){
	return array(
		'1' => '最終受講日順',
		'2' => '受講率の多い順',
		'3' => '掲載終了間近順',
		'4' => '掲載開始日(古い順)',
		'5' => '掲載開始日(新しい順)'
	);
}
?>
