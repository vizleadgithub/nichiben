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
if (!isset($_GET['pid']) || !isset($_GET['qid'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$pid = $_GET['pid'];
$qid = $_GET['qid'];
if(cmCheckInput($pid, 'CK_NUM') || cmCheckInput($qid, 'CK_NUM')){
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
$question = '';
$reference = '';
$answer_btn_disp_flg = false;

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
	
	// 設問の取得
	$sql = "SELECT question, reference FROM tbl_ethic_question WHERE ethic_question_id = '$qid'";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$question = $res['question'];
		$reference = $res['reference'];
	}
	
	// 問題の取得
	$sql = "SELECT ethic_branch_id, question_branch FROM tbl_ethic_branch WHERE ethic_question_id = '$qid' ORDER BY `rank` ASC";
	$res = $objDbConnect->query_fetch_arr($sql);
	if ($res){
		foreach ($res as $val){
			$arr_list[$val['ethic_branch_id']] = $val['question_branch'];
		}
	}
	
	// 回答ボタン表示フラグ
	$sql = "SELECT `rank` FROM tbl_ethic_question WHERE ethic_question_id = '$qid'";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$question_no = $res['rank'] + 10; // 通常問題分プラスする
		$sql = "SELECT answer_ethic_branch_id$question_no FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid'";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			if (is_null($res["answer_ethic_branch_id$question_no"])){
				$answer_btn_disp_flg = true;
			}
		}
	}
	
} else {
	header("Location: /");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('qid', $qid);
$template->assign('arr_list', $arr_list);
$template->assign('question', $question);
$template->assign('reference', $reference);
$template->assign('answer_btn_disp_flg', $answer_btn_disp_flg);

$template->layout_noside('ethic_treaning/question_retry.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>