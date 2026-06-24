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
$_SESSION['wp_page_head_title'] = 'テスト';
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
if (!isset($_GET['ccno'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$ccno = $_GET['ccno'];
if(cmCheckInput($ccno, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
if (!isset($_GET['eid'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$eid = $_GET['eid'];
if(cmCheckInput($eid, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}

$qid = '';
if(isset($_GET['qid'])){
	$qid = $_GET['qid'];
	if(cmCheckInput($qid, 'CK_NUM')){
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];

$back_url = get_back_url();

// 解答済みかチェックする
$sql = "SELECT COUNT(exam_answer_id) AS count FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$eid' AND product_id='$pid' AND contents_no='$ccno'";
$res = $objDbConnect->query_fetch($sql);
if($res['count']<=0){
	$objDbConnect->close();
	header("Location: /");
	exit();
}

$arr_list = get_exam($eid, $user_id);
$exam_answer = array();
$total_score = 0;
$exam_total_score = 0;

// 解答履歴の取得(テスト)
$sql = "SELECT * FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$eid' AND product_id='$pid' AND contents_no='$ccno'";
$res = $objDbConnect->query_fetch_arr($sql);
if($res){
	foreach($res as $val){
		$total_score += $val['exam_answer_point'];
		$exam_answer[$val['exam_problem_id']]['exam_answer_contents_str'] = $val['exam_answer_contents'];
		$exam_answer[$val['exam_problem_id']]['exam_answer_contents'] = explode(',', $val['exam_answer_contents']);
		$exam_answer[$val['exam_problem_id']]['exam_answer_mark'] = $val['exam_answer_mark'];
		
		// 設問の正解を取得、問題の合計点数を取得
		$exam_answer[$val['exam_problem_id']]['correct_answer_str'] = '';
		$sql = "SELECT answer_point, answer_contents FROM exam_problem WHERE exam_problem_id='".$val['exam_problem_id']."'";
		$res = $objDbConnect->query_fetch($sql);
		if($res){
			$exam_total_score += $res['answer_point'];
			$arr_answer_contents = json_decode($res['answer_contents']);
			if($arr_answer_contents){
				if($arr_answer_contents->answer_kind=='1' || $arr_answer_contents->answer_kind=='2'){
					foreach($arr_answer_contents->answer_contents as $val2){
						if($val2->correct=='1'){
							if($exam_answer[$val['exam_problem_id']]['correct_answer_str']==''){
								$exam_answer[$val['exam_problem_id']]['correct_answer_str'] .= $val2->no;
							} else {
								$exam_answer[$val['exam_problem_id']]['correct_answer_str'] .= ','.$val2->no;
							}
						}
					}
				} elseif($arr_answer_contents->answer_kind=='3'){
					foreach($arr_answer_contents->answer_contents as $val2){
						if($val2->correct=='1'){
							$exam_answer[$val['exam_problem_id']]['correct_answer_str'] .= $val2->word;
						}
					}
				}
			}
		}
	}
}

// 解答履歴の取得(アンケート)
$arr_list_q = array();
$exam_answer_q = array();
if($qid!=''){
	$arr_list_q = get_exam($qid, $user_id);
	
	$sql = "SELECT * FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$qid' AND product_id='$pid' AND contents_no='$ccno'";
	$res = $objDbConnect->query_fetch_arr($sql);
	if($res){
		foreach($res as $val){
			$exam_answer_q[$val['exam_problem_id']]['exam_answer_contents_str'] = $val['exam_answer_contents'];
			$exam_answer_q[$val['exam_problem_id']]['exam_answer_contents'] = explode(',', $val['exam_answer_contents']);
			$exam_answer_q[$val['exam_problem_id']]['exam_answer_mark'] = $val['exam_answer_mark'];
		}
	}
}

// アンケートのみ設定かのフラグ
$question_flg = false;
$sql = "SELECT * FROM rel_product_contents WHERE product_id='$pid' AND contents_no='$ccno'";
$res = $objDbConnect->query_fetch($sql);
if($res){
	if($res['exam_id_test']<=0 && $res['exam_id_question']>0){
		$question_flg = true;
	}
}

// 判定基準ありかどうか
$hantei_ari = false;
$sql = "SELECT criteria_value FROM exam WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$eid)."'";
$ret = $objDbConnect->query_fetch($sql);
if($ret){
	if($ret['criteria_value']>0){
		$hantei_ari = true;
	}
}

// 合格しているかどうか
$passing_flg = false;
$sql = "SELECT COUNT(exam_answer_id) AS count FROM exam_answer WHERE status=0 AND passing_flg=1 AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."'";
$ret = $objDbConnect->query_fetch($sql);
if($ret){
	if($ret['count']>0){
		$passing_flg = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('eid', $eid);
$template->assign('qid', $qid);
$template->assign('arr_list', $arr_list);
$template->assign('exam_answer', $exam_answer);
$template->assign('arr_list_q', $arr_list_q);
$template->assign('exam_answer_q', $exam_answer_q);
$template->assign('total_score', $total_score);
$template->assign('exam_total_score', $exam_total_score);
$template->assign('question_flg', $question_flg);
$template->assign('hantei_ari', $hantei_ari);
$template->assign('passing_flg', $passing_flg);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_noside('exam/result.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>