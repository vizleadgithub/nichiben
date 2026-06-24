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

$hantei_flg = true;

// 倫理措置フラグチェック
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	// ステータスチェック
	$sql = "SELECT COUNT(*) AS c FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid' AND status IN(3,4)";
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
		
		// 設問の取得
		$sql = "
		SELECT
		  tbl_ethic_question.ethic_question_id,
		  tbl_ethic_question.video_id,
		  tbl_ethic_question.`rank`, 
		  tbl_ethic_question.reference,
		  video_alfstream_status.alfstream_duration 
		FROM
		  tbl_ethic_question 
		  LEFT JOIN video_alfstream_status ON tbl_ethic_question.video_id=video_alfstream_status.video_id 
		WHERE
		  ethic_group_id = '$ethic_group_id'
		  AND failure_flg = '1'
		ORDER BY `rank` ASC
		";
		$res = $objDbConnect->query_fetch_arr($sql);
		if ($res){
			// 履歴の取得
			$sql = "SELECT * FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid'";
			$history_question = $objDbConnect->query_fetch($sql);
			if ($history_question){
				foreach ($res as $val){
					$question_no = $val['rank'] + 10; // 通常問題分プラスする
					
					$arr_list[$val['ethic_question_id']]['reference'] = $val['reference'];
					// 設問番号の設定
					$arr_list[$val['ethic_question_id']]['question_no'] = $question_no;
					// 動画ID
					$arr_list[$val['ethic_question_id']]['video_id'] = $val['video_id'];
					// 解答履歴
					$arr_list[$val['ethic_question_id']]['answer_ethic_branch_id'] = $history_question['answer_ethic_branch_id'.$question_no];
					if (is_null($history_question['answer_ethic_branch_id'.$question_no])){
						$arr_list[$val['ethic_question_id']]['test_flg'] = true;
						$arr_list[$val['ethic_question_id']]['disp_answer'] = '未回答';
						$hantei_flg = false;
					} else {
						$arr_list[$val['ethic_question_id']]['test_flg'] = false;
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
					}
					// 再生時間
					$arr_list[$val['ethic_question_id']]['duration'] = $val['alfstream_duration'];
					// 視聴履歴
					$arr_list[$val['ethic_question_id']]['disp_view'] = '未視聴';
					$sql = "SELECT duration, complete_flag FROM report_user_video_viewed WHERE student_id = '$user_id' AND video_id = '".$val['video_id']."' AND percent >= 1";
					$history_view = $objDbConnect->query_fetch($sql);
					if ($history_view){
						if ($history_view['complete_flag'] == 1){
							$arr_list[$val['ethic_question_id']]['disp_view'] = '視聴完了';
						} else {
							$arr_list[$val['ethic_question_id']]['disp_view'] = '視聴中';
							$hantei_flg = false;
						}
					} else {
						$hantei_flg = false;
					}
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
$template->assign('hantei_flg', $hantei_flg);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_noside('ethic_treaning/retry.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>