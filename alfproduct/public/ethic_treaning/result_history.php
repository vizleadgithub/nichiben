<?php
header('Etag: ' . date("YmdHis"));
header('Expires: Sun, 26 Nov 2000 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$agent = $_SERVER['HTTP_USER_AGENT']; 

$isPad = false;
if(preg_match("/iPad/", $agent)){//iPad
	$isPad = true;
}
$isApple = false;
if(preg_match("/iPhone/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPad/", $agent)){//iPhone
	$isApple = true;
} elseif(preg_match("/iPod/", $agent)){//iPhone
	$isApple = true;
}
$isAndroid = false;
if(preg_match("/Android/", $agent)){//Android
	$isAndroid = true;
}
$isAndroidTablet = false;
if(preg_match("/Android/", $agent)){
	if(preg_match("/Mobile/", $agent) && preg_match("/SC-01C/", $agent)){
		$isAndroidTablet = true;
	}elseif(preg_match("/mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Mobile/", $agent)){
		$isAndroidTablet = false;
	} elseif(preg_match("/Tablet/", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '倫理研修代替措置研修';
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (!isset($_GET['pid'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$pid = $_GET['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}

// 正しい倫理研修問題の商品IDかチェック
if ($pid != get_ethic_product_id()){
	header("Location: /");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];

$back_url = get_back_url();

$arr_list = array();
$arr_list_add = array();

$hantei_flg = true;

$product_name = '';

// 倫理措置フラグチェック
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	// ステータスチェック
	$sql = "SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid' AND status IN(2,3,4,5,6,7,8)";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		if ($res['c']<=0){
			header("Location: /");
			exit();
		}
	}
	
	// 倫理問題グループIDの取得
	$sql = "
	SELECT
	  ethic_group_id
	FROM
	  tbl_product_ethic_training
	WHERE
	  product_id = '$pid'
	  AND publish_flg = '1';
	";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$ethic_group_id = $res['ethic_group_id'];
/*
		// 問題タイトルの取得
		$sql = "SELECT question_group FROM tbl_ethic_group WHERE ethic_group_id = '$ethic_group_id'";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			$question_title = $res['question_group'];
		} else {
			$question_title = '';
		}
*/
		// 設問の取得
		$sql = "
		SELECT
		  ethic_question_id,
		  failure_flg,
		  video_id,
		  `rank`, 
		  reference 
		FROM
		  tbl_ethic_question
		WHERE
		  ethic_group_id = '$ethic_group_id'
		ORDER BY failure_flg ASC, `rank` ASC
		";
		$res = $objDbConnect->query_fetch_arr($sql);
		if ($res){
			// 履歴の取得
			$sql = "SELECT * FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid'";
			$history_question = $objDbConnect->query_fetch($sql);
			if ($history_question){
				// 合否情報
				if ($history_question['status']==2){
					$str_result = '合格';
					$judge_date = date('Y年m月d日 H時i分', strtotime($history_question['judge_date1']));
					$judge_flg = 1;
				} else if ($history_question['status']==3 || $history_question['status']==4){
					$str_result = '不合格（テストの正答数が６問以下）';
					$judge_date = date('Y年m月d日 H時i分', strtotime($history_question['judge_date1']));
					$judge_flg = 2;
				} else if ($history_question['status'] == 5){
					$str_result = '合格';
					$judge_date = date('Y年m月d日 H時i分', strtotime($history_question['judge_date2']));
					$judge_flg = 3;
				} else if ($history_question['status'] == 6){
					$str_result = '不合格（テストの正答数が３問以下）';
					$judge_date = date('Y年m月d日 H時i分', strtotime($history_question['judge_date2']));
					$judge_flg = 4;
				} else if ($history_question['status'] == 7){
					$str_result = '合格';
					$judge_date = '';
					// 取り込みレポート
					if (is_null($history_question['judge_date2'])){
						$judge_flg = 5;
					// 二次×→レポートの場合
					} else {
						$judge_flg = 6;
					}
				} else {
					$str_result = '';
					$judge_date = '';
					$judge_flg = 0;
				}
				
				foreach ($res as $val){
					// 追試を受けているかどうか
					if ($val['failure_flg']==1 && $history_question['status']!=5 && $history_question['status']!=6 && $history_question['status']!=7){
						break;
					}
					
					if ($val['failure_flg'] == 1){
						$question_no = $val['rank'] + 10;
						
						$arr_list_add[$val['ethic_question_id']]['reference'] = $val['reference'];
						// 設問番号
						$arr_list_add[$val['ethic_question_id']]['question_no'] = $val['rank'];
						// 動画ID
						$arr_list_add[$val['ethic_question_id']]['video_id'] = $val['video_id'];
						// 解答履歴
						$arr_list_add[$val['ethic_question_id']]['answer_ethic_branch_id'] = $history_question['answer_ethic_branch_id'.$question_no];
						$sql = "SELECT answer_flg FROM tbl_ethic_branch WHERE ethic_branch_id = '".$history_question["answer_ethic_branch_id$question_no"]."'";
						$history_answer = $objDbConnect->query_fetch($sql);
						if ($history_answer){
							$arr_list_add[$val['ethic_question_id']]['answer_flg'] = $history_answer['answer_flg'];
							if ($history_answer['answer_flg'] == 1){
								$arr_list_add[$val['ethic_question_id']]['disp_answer'] = '正答';
							} else {
								$arr_list_add[$val['ethic_question_id']]['disp_answer'] = '誤答';
							}
						}
						
						// 視聴履歴
						$sql = "SELECT duration, complete_flag FROM report_user_video_viewed WHERE student_id = '$user_id' AND video_id = '".$val['video_id']."'";
						$history_view = $objDbConnect->query_fetch($sql);
						if ($history_view){
							$arr_list_add[$val['ethic_question_id']]['duration'] = $history_view['duration'];
							$arr_list_add[$val['ethic_question_id']]['complete_flag'] = $history_view['complete_flag'];
						}
						
					} else {
						$question_no = $val['rank'];
						
						$arr_list[$val['ethic_question_id']]['reference'] = $val['reference'];
						// 設問番号
						$arr_list[$val['ethic_question_id']]['question_no'] = $val['rank'];
						// 動画ID
						$arr_list[$val['ethic_question_id']]['video_id'] = $val['video_id'];
						// 解答履歴
						$arr_list[$val['ethic_question_id']]['answer_ethic_branch_id'] = $history_question['answer_ethic_branch_id'.$question_no];
						$sql = "SELECT answer_flg FROM tbl_ethic_branch WHERE ethic_branch_id = '".$history_question["answer_ethic_branch_id$question_no"]."'";
						$history_answer = $objDbConnect->query_fetch($sql);
						if ($history_answer){
							$arr_list[$val['ethic_question_id']]['answer_flg'] = $history_answer['answer_flg'];
							if ($history_answer['answer_flg'] == 1){
								$arr_list[$val['ethic_question_id']]['disp_answer'] = '正答';
							} else {
								$arr_list[$val['ethic_question_id']]['disp_answer'] = '誤答';
							}
						}
						
						// 視聴履歴
						$sql = "SELECT duration, complete_flag FROM report_user_video_viewed WHERE student_id = '$user_id' AND video_id = '".$val['video_id']."'";
						$history_view = $objDbConnect->query_fetch($sql);
						if ($history_view){
							$arr_list[$val['ethic_question_id']]['duration'] = $history_view['duration'];
							$arr_list[$val['ethic_question_id']]['complete_flag'] = $history_view['complete_flag'];
						}
					}
				}
				
				// 商品名の取得
				$sql = "SELECT product_name FROM tbl_product WHERE product_id = '$pid'";
				$res = $objDbConnect->query_fetch($sql);
				if ($res){
					$product_name = $res['product_name'];
				}
			}
		}
	}
	
	
//var_dump($arr_list);
	
} else {
	header("Location: /");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('arr_list', $arr_list);
$template->assign('arr_list_add', $arr_list_add);
$template->assign('str_result', $str_result);
$template->assign('judge_date', $judge_date);
//$template->assign('question_title', $question_title);
$template->assign('product_name', $product_name);
$template->assign('judge_flg', $judge_flg);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->assign('csrf_token', csrf_token_get());
$template->layout_noside('ethic_treaning/result_history.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>