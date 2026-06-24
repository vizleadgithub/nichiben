<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '受講履歴(eラーニング)';

if (!st_login_check()){
	header("Location: /");
	exit;
}

$objDbConnect = new DbConnect();
$objPager = new Pager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$pagemax = 5;
if( isset($_SESSION["mypage.lesson_list1.pagemax"]) && !empty($_SESSION["mypage.lesson_list1.pagemax"]) ){
	$pagemax = $_SESSION["mypage.lesson_list1.pagemax"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$_SESSION["lesson_list1.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["lesson_list1.page"]) && !empty($_SESSION["lesson_list1.page"]) ){
	$page = $_SESSION["lesson_list1.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["lesson_list1.page"] = $page;
}
if( isset($_GET["pagemax"]) && is_numeric($_GET["pagemax"]) ){
	$pagemax = $_GET["pagemax"];
	$_SESSION["mypage.lesson_list1.pagemax"] = $pagemax;
}
$objPager->setNowPage( $page );
$objPager->setPageMax( $pagemax );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sort = 1;
if( isset($_SESSION["lesson_list1.sort"]) && !empty($_SESSION["lesson_list1.sort"]) ){
	$sort = $_SESSION["lesson_list1.sort"];
}
if( isset($_GET["sort"]) && !empty($_GET["sort"]) && is_numeric($_GET["sort"]) ){
	$sort = $_GET["sort"];
	$_SESSION["lesson_list1.sort"] = $sort;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$search_buy_date = '';
if( isset($_SESSION["lesson_list1.search_buy_date"]) && !empty($_SESSION["lesson_list1.search_buy_date"]) ){
	$search_buy_date = $_SESSION["lesson_list1.search_buy_date"];
}
if( isset($_GET["search_buy_date"]) && !empty($_GET["search_buy_date"]) ){
	$search_buy_date = $_GET["search_buy_date"];
	$_SESSION["lesson_list1.search_buy_date"] = $search_buy_date;
}
if( isset($_GET["search_buy_date"]) && empty($_GET["search_buy_date"]) ){
	$search_buy_date = "";
	$_SESSION["lesson_list1.search_buy_date"] = "";
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "
SELECT 
 * 
FROM 
 student 
WHERE student_id = '".mysqli_real_escape_string($objDbConnect->connect,$_SESSION['user']['id'])."'";
$student_info = $objDbConnect->query_fetch($sql.$where);
//var_dump($student_info);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期化
$all_count = 0;
$get_flg = true;
$arr_viewd_video = array(); // ビデオ情報
$arr_list = array(); // ビデオに紐付く商品情報
$student_id = intval($_SESSION['user']['id']);
$pid = $_GET["pid"];

// 受講履歴より動画のvideo_idを取得
$sql = "
SELECT
  video_id
FROM
  report_user_video_viewed
WHERE
  student_id = '".mysqli_real_escape_string($objDbConnect->connect,$student_id)."'
  AND percent >= 1
 ";
$res = $objDbConnect->query_fetch_arr($sql);
if ($res){
	$in_video_id = '';
	foreach ($res as $val){
		// where文用文字列の作成
		$in_video_id.= $val['video_id'].',';
	}
	$in_video_id = rtrim($in_video_id, ',');
	
	// ビデオが設定されている商品をベースに全件取得
	$where = "
	WHERE
	  (
	    TP.contents_contents1 IN($in_video_id)
	    OR TP.contents_contents2 IN($in_video_id)
	    OR TP.contents_contents3 IN($in_video_id)
	    OR TP.contents_contents4 IN($in_video_id)
	    OR TP.contents_contents5 IN($in_video_id)
	    OR TP.contents_contents6 IN($in_video_id)
	    OR TP.contents_contents7 IN($in_video_id)
	    OR TP.contents_contents8 IN($in_video_id)
	    OR TP.contents_contents9 IN($in_video_id)
	    OR TP.contents_contents10 IN($in_video_id)
	    OR TP.contents_contents11 IN($in_video_id)
	    OR TP.contents_contents12 IN($in_video_id)
	    OR TP.contents_contents13 IN($in_video_id)
	    OR TP.contents_contents14 IN($in_video_id)
	    OR TP.contents_contents15 IN($in_video_id)
	    OR TP.contents_contents16 IN($in_video_id)
	    OR TP.contents_contents17 IN($in_video_id)
	    OR TP.contents_contents18 IN($in_video_id)
	    OR TP.contents_contents19 IN($in_video_id)
	    OR TP.contents_contents20 IN($in_video_id)
	    OR TP.contents_contents21 IN($in_video_id)
	    OR TP.contents_contents22 IN($in_video_id)
	    OR TP.contents_contents23 IN($in_video_id)
	    OR TP.contents_contents24 IN($in_video_id)
	    OR TP.contents_contents25 IN($in_video_id)
	  )
	  AND TPA.product_type_add = '1'
	  AND TP.product_id NOT IN (19234, 19235, 19236, 19237, 19238)
	  AND TP.product_id=".mysqli_real_escape_string($objDbConnect->connect, $pid)." 
	 ";
	
	$sql = "SELECT COUNT(*) AS c FROM tbl_product AS TP INNER JOIN tbl_product_add AS TPA ON TP.product_id = TPA.product_id";
	$ret = $objDbConnect->query_fetch($sql.$where);
	if ($ret){
		$all_count = $ret["c"];
	} else {
		$all_count = 0;
	}

	$sql = "SELECT";
	for($i=1; $i<=MAX_CONTENTS; $i++){
		$sql.= "   TP.contents_contents$i,";
	}
	$sql.= "   TP.product_id,";
	$sql.= "   TP.product_name AS product_name_TP,";
	$sql.= "   TP.del_flg,";
	$sql.= "   TP.start_date,";
	$sql.= "   TP.end_date,";
	$sql.= "   ( SELECT DATE_FORMAT(MIN(regist_at), '%Y/%m/%d') FROM report_user_video_viewed WHERE student_id = '".mysqli_real_escape_string($objDbConnect->connect,$_SESSION['user']['id'])."' AND video_id IN( 
			TP.contents_contents1,
			TP.contents_contents2,
			TP.contents_contents3,
			TP.contents_contents4,
			TP.contents_contents5,
			TP.contents_contents6,
			TP.contents_contents7,
			TP.contents_contents8,
			TP.contents_contents9,
			TP.contents_contents10,
			TP.contents_contents11,
			TP.contents_contents12,
			TP.contents_contents13,
			TP.contents_contents14,
			TP.contents_contents15,
			TP.contents_contents16,
			TP.contents_contents17,
			TP.contents_contents18,
			TP.contents_contents19,
			TP.contents_contents20,
			TP.contents_contents21,
			TP.contents_contents22,
			TP.contents_contents23,
			TP.contents_contents24,
			TP.contents_contents25 
					 ) AND percent >= 1 ) AS TSUB_regist_at, ";
	$sql.= "   ( SELECT DATE_FORMAT(MAX(complete_date), '%Y/%m/%d') FROM report_user_video_viewed WHERE student_id = '".mysqli_real_escape_string($objDbConnect->connect,$_SESSION['user']['id'])."' AND video_id IN( 
			TP.contents_contents1,
			TP.contents_contents2,
			TP.contents_contents3,
			TP.contents_contents4,
			TP.contents_contents5,
			TP.contents_contents6,
			TP.contents_contents7,
			TP.contents_contents8,
			TP.contents_contents9,
			TP.contents_contents10,
			TP.contents_contents11,
			TP.contents_contents12,
			TP.contents_contents13,
			TP.contents_contents14,
			TP.contents_contents15,
			TP.contents_contents16,
			TP.contents_contents17,
			TP.contents_contents18,
			TP.contents_contents19,
			TP.contents_contents20,
			TP.contents_contents21,
			TP.contents_contents22,
			TP.contents_contents23,
			TP.contents_contents24,
			TP.contents_contents25 
					 ) AND percent >= 1 ) AS TSUB_complete_date, ";
	$sql.= " (SELECT TOD.product_name FROM tbl_order_detail AS TOD WHERE TOD.member_id = '".mysqli_real_escape_string($objDbConnect->connect,$_SESSION['user']['id'])."' AND TOD.product_id = TP.product_id ORDER BY TOD.order_detail_id DESC LIMIT 1) AS product_name_TOD";
	$sql.= " FROM";
	$sql.= "   tbl_product AS TP";
	$sql.= "     LEFT JOIN";
	$sql.= "   tbl_product_add AS TPA";
	$sql.= "       ON TP.product_id = TPA.product_id";
	$ret = $objDbConnect->query_fetch_arr($sql.$where);
	if ($ret){
		$arr_sort = array();
		$arr_sort1 = array();
		
		foreach ($ret as $key => $val){
			$arr_list[$key] = $val;
			
			$res_duration_reading   = false; // 視聴済み時間取得用
			$all_alfstream_duration = '00:00:00'; // 総再生時間計算用
			$all_duration_reading   = '00:00:00'; // 総視聴済み時間計算用
			$prev_percent = 0; // 最大閲覧率の最大値判断用
			$first_flg = true;
			$all_complete_flg = true;
			$koukai_flg = false;
			
			for($i=1; $i<=MAX_CONTENTS; $i++){
				if ($val["contents_contents$i"] != ''){
					// 総再生時間
					$sql = "SELECT alfstream_duration FROM video_alfstream_status WHERE video_id='".$val["contents_contents$i"]."'";
					$res_alfstream_duration = $objDbConnect->query_fetch($sql);
					if ($res_alfstream_duration){
						$all_alfstream_duration = getTimeAddition($all_alfstream_duration, $res_alfstream_duration["alfstream_duration"]);
					}
					$arr_list[$key]["total_duration"] = $all_alfstream_duration;
					
					// 視聴済時間の総計
					$sql = "SELECT duration_reading, percent, UNIX_TIMESTAMP(reading_date) AS u_reading_date, UNIX_TIMESTAMP(regist_at) AS u_regist_at, UNIX_TIMESTAMP(complete_date) AS u_complete_date, complete_flag FROM report_user_video_viewed WHERE student_id = '$student_id' AND video_id = '".$val["contents_contents$i"]."'";
					$res_duration_reading = $objDbConnect->query_fetch($sql);
					if ($res_duration_reading){
						// 視聴時間
						$all_duration_reading = getTimeAddition($all_duration_reading, $res_duration_reading["duration_reading"]);
						// 各日時の取得
						if ($first_flg){
							$u_reading_date = $res_duration_reading["u_reading_date"];
							$u_regist_at = $res_duration_reading["u_regist_at"];
							if ($res_duration_reading["complete_flag"]==1){
								$u_complete_date = $res_duration_reading["u_complete_date"];
							}
							$first_flg = false;
						} else {
							// 最終受講日
							if ($u_reading_date < $res_duration_reading["u_reading_date"]){
								$u_reading_date = $res_duration_reading["u_reading_date"];
							}
							// 受講開始日
							if ($u_regist_at > $res_duration_reading["u_regist_at"]){
								$u_regist_at = $res_duration_reading["u_regist_at"];
							}
							// 受講終了日
							if ($res_duration_reading["complete_flag"]==1){
								if ($u_complete_date < $res_duration_reading["u_complete_date"]){
									$u_complete_date = $res_duration_reading["u_complete_date"];
								}
							}
						}
						$arr_list[$key]["reading_date"] = date('Y/m/d', $u_reading_date);
						$arr_list[$key]["regist_at"] = date('Y/m/d', $u_regist_at);
						if ($res_duration_reading["complete_flag"]==1){
							$arr_list[$key]["complete_date"] = date('Y/m/d', $u_complete_date);
						}
					}
					
					// 全ての講座を見たか
					if ($all_complete_flg){
						$sql = "SELECT COUNT(*) AS c FROM report_user_video_viewed WHERE student_id = '".$student_id."' AND video_id = '".$val["contents_contents$i"]."' AND complete_flag = '1'";
						$res_count = $objDbConnect->query_fetch($sql);
						if ($res_count['c']==0){
							$all_complete_flg = false;
							$arr_list[$key]["TSUB_complete_date_SUB"] = "0000/00/00";
						} else {
							$arr_list[$key]["TSUB_complete_date_SUB"] = $arr_list[$key]["complete_date"];
						}
					}
					
				} else {
					break;
				}
			}
			$arr_list[$key]["all_complete_flg"] = $all_complete_flg;
			$arr_list[$key]["all_remaining"] = getTimeSubtraction($all_alfstream_duration, $all_duration_reading);
			
			// 受講率計算
			if ($all_complete_flg){
				$arr_list[$key]["max_percent"] = 100;
			} else {
				$arr_list[$key]["max_percent"] = 0;
				
				$in_video_id = '';
				$ret_tbl_product = array();
				$sql = "SELECT ";
				for($k=1; $k<=MAX_CONTENTS; $k++){
					$sql.= " contents_contents$k,";
				}
				$sql = rtrim($sql, ',');
				$sql.= " FROM";
				$sql.= "   tbl_product";
				$sql.= " WHERE";
				$sql.= "   product_id = '".$val['product_id']."'";
				$ret_tbl_product = $objDbConnect->query_fetch_arr($sql);
				if ($ret_tbl_product){
					for($l=1; $l<=MAX_CONTENTS; $l++){
						if ($ret_tbl_product[0]["contents_contents$l"] != ''){
							$in_video_id.= $ret_tbl_product[0]["contents_contents$l"] . ',';
						}
					}
					$in_video_id = rtrim($in_video_id, ',');
					
					$ret_report_user_video_viewed = array();
					$sql = "
					SELECT
					  TIME_TO_SEC(duration) AS duration_sec,
					  TIME_TO_SEC(duration_reading) AS duration_reading_sec,
					  complete_flag
					FROM
					  report_user_video_viewed
					WHERE
					  student_id = '$student_id'
					  AND video_id IN($in_video_id)
					";
					$ret_report_user_video_viewed = $objDbConnect->query_fetch_arr($sql);
					if ($ret_report_user_video_viewed){
						// 動画を見終わっているかどうかで、視聴済み時間を変更
						// complete_flag=0：視聴済み時間を使用(duration_reading)
						// complete_flag=1：動画再生時間を使用(duration)(視聴済み時間が最新の時間で更新されてしまうため)
						$video_duration_reading = 0;
						foreach ($ret_report_user_video_viewed as $aruvv_val){
							if ($aruvv_val['complete_flag'] == '1'){
								$video_duration_reading += $aruvv_val['duration_sec'];
							} else {
								$video_duration_reading += $aruvv_val['duration_reading_sec'];
							}
						}
						
						$arr_all_alfstream_duration = array();
						$sql = "SELECT TIME_TO_SEC('$all_alfstream_duration') AS all_alfstream_duration_sec";
						$arr_all_alfstream_duration = $objDbConnect->query_fetch_arr($sql);
						if ($arr_all_alfstream_duration){
							$percent = $video_duration_reading / $arr_all_alfstream_duration[0]['all_alfstream_duration_sec'] * 100;
							if (!is_int($percent)){
								$arr_list[$key]["max_percent"] = (int)round($percent);
							}
						}
					}
				}
			}
			
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
			
			// 並び替え用配列の準備
			if ($sort == 1){ // 受講率の多い順
				$arr_sort[] = $arr_list[$key]["max_percent"];
				$arr_sort1[] = $arr_list[$key]["all_complete_flg"];
				
			} else if ($sort == 2){ // 掲載終了間近順
				$arr_sort[] = $arr_list[$key]["end_date"];
				
			} else if ($sort == 3){ // 掲載開始日(古い順)
				$arr_sort[] = $arr_list[$key]["start_date"];
				
			} else if ($sort == 4){ // 掲載開始日(新しい順)
				$arr_sort[] = $arr_list[$key]["start_date"];
				
			} else if ($sort == 5){ // 受講開始日順
				$arr_sort[] = $arr_list[$key]["TSUB_regist_at"];
				
			} else if ($sort == 6){ // 受講終了日順
				$arr_sort[] = $arr_list[$key]["TSUB_complete_date_SUB"];
				$arr_sort1[] = $arr_list[$key]["TSUB_regist_at"];
			}
		}

		// 並び替え
		$sort_result = false;
		if (!empty($arr_sort)){
			if ($sort == 1){ // 受講率の多い順
				$sort_result = array_multisort($arr_sort1, SORT_DESC, SORT_REGULAR, $arr_sort, SORT_DESC, SORT_NUMERIC, $arr_list);
				
			} else if ($sort == 2){ // 掲載終了間近順
				$sort_result = array_multisort($arr_sort, SORT_ASC, SORT_REGULAR, $arr_list);
				
			} else if ($sort == 3){ // 掲載開始日(古い順)
				$sort_result = array_multisort($arr_sort, SORT_ASC, SORT_REGULAR, $arr_list);
				
			} else if ($sort == 4){ // 掲載開始日(新しい順)
				$sort_result = array_multisort($arr_sort, SORT_DESC, SORT_REGULAR, $arr_list);
				
			} else if ($sort == 5){ // 受講開始日順
				$sort_result = array_multisort($arr_sort, SORT_DESC, SORT_REGULAR, $arr_list);
				
			} else if ($sort == 6){ // 受講終了日順
				$sort_result = array_multisort($arr_sort, SORT_DESC, SORT_REGULAR, $arr_sort1, SORT_DESC,  $arr_list);
				
			}
		}
		
		$arr_disp_list = $arr_list;
	}
}

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('student_info', $student_info);

$template->assign('page', $page);
$template->assign('arr_list', $arr_disp_list);
$template->assign('all_count', $all_count);

$template->assign('sort_select', _get_sort_selectbox());
$template->assign('sort', $sort);

$template->assign('ymd', date("Ymd"));

$template->layout_noside('mypage/lesson_list1_print.tpl');
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