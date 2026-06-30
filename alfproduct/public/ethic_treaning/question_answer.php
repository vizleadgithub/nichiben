<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
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
csrf_token_verify();
$_SESSION['wp_page_head_title'] = '倫理研修代替措置研修';
$objDbConnect = new DbConnect();
$template = new Template();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$back_url = get_back_url();

if (!isset($_POST['ethic_branch_id'])){
	$objDbConnect->close();
	header("Location: $back_url");
	exit();
}
$ethic_branch_id = $_POST['ethic_branch_id'];

if (!isset($_GET['pid']) || !isset($_GET['qid'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$pid = $_GET['pid'];
$qid = $_GET['qid'];

if(cmCheckInput($pid, 'CK_NUM') || cmCheckInput($qid, 'CK_NUM') || cmCheckInput($ethic_branch_id, 'CK_NUM')){
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

// 倫理措置フラグチェック
if ($_SESSION['user']['sub_auth_ethic_training'] == 1){
	// ステータスチェック
	$sql = "SELECT status FROM tbl_ethic_question_history WHERE student_id = '$user_id' AND product_id = '$pid' AND status IN(0,1)";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$status = $res['status'];
		
	} else {
		header("Location: /");
		exit();
	}
	
	// 何問目の問題か
	$sql = "SELECT `rank` FROM tbl_ethic_question WHERE ethic_question_id = '$qid'";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$question_no = $res['rank'];
		
		// 初回回答の場合はステータス＆開始日時更新
		if ($status == 0){
			$sql = "
			UPDATE
			  tbl_ethic_question_history
			SET
			  status = '1',
			  start_date1 = '".date('Y-m-d H:i:s')."'
			WHERE
			  student_id = '$user_id'
			  AND product_id = '$pid'
			";
			$objDbConnect->execute($sql);
		}
		
		// 回答履歴の更新
		$sql = "
		UPDATE
		  tbl_ethic_question_history
		SET
		  answer_ethic_branch_id".$question_no." = '".$ethic_branch_id."',
		  answer_date".$question_no." = '".date('Y-m-d H:i:s')."'
		WHERE
		  student_id = '$user_id'
		  AND product_id = '$pid'
		";
		$objDbConnect->execute($sql);
		
		// 正答か誤答の取得
		$sql = "SELECT answer_flg FROM tbl_ethic_branch WHERE ethic_branch_id = '$ethic_branch_id'";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			if ($res['answer_flg'] == 1){
				$str_answer = '正答';
			} else {
				$str_answer = '誤答';
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
$template->assign('question_no', $question_no);
$template->assign('str_answer', $str_answer);
$template->assign('csrf_token', csrf_token_get());

$template->layout_noside('ethic_treaning/question_answer.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>