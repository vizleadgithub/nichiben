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

$arr_list = get_exam($eid, $user_id);

$arr_list_q = array();
if($qid!=''){
	$arr_list_q = get_exam($qid, $user_id);
}

// 解答済データの取得
$answered_list = array();
$exam_answered = get_exam_answerd($eid, $user_id, $pid, $ccno);
if(!empty($exam_answered)){
	foreach($exam_answered as $val){
		// 設問番号をkeyにする
		$exam_no = 0;
		if(isset($arr_list['problem'])){
			foreach($arr_list['problem'] as $problem){
				if($val['exam_problem_id']==$problem['exam_problem_id']){
					$exam_no = $problem['exam_no'];
					break;
				}
			}
			
			if($exam_no>0){
				$answered_list[$exam_no]['answer1'] = ''; // 単一形式の解答が入る
				$answered_list[$exam_no]['answer2'] = []; // 複数形式の解答が入る
				$answered_list[$exam_no]['answer3'] = ''; // フリー形式の解答が入る
				
				// 解答種類の取得
				$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".$val['exam_problem_id']."'";
				$res = $objDbConnect->query_fetch($sql);
				if($res){
					if($res['answer_kind']=='1'){
						if($val['exam_answer_contents']!=''){
							$answered_list[$exam_no]['answer1'] = $val['exam_answer_contents'];
						}
					} elseif($res['answer_kind']=='2'){
						if($val['exam_answer_contents']!=''){
							$answered_list[$exam_no]['answer2'] = explode(',', $val['exam_answer_contents']);
						} else {
							$answered_list[$exam_no]['answer2'] = [];
						}
					} elseif($res['answer_kind']=='3'){
						if($val['exam_answer_contents']!=''){
							$answered_list[$exam_no]['answer3'] = $val['exam_answer_contents'];
						}
					}
				}
			}
		}
	}
}

$answered_list_q = array();
if($qid!=''){
	$exam_answered = get_exam_answerd($qid, $user_id, $pid, $ccno);
	if(!empty($exam_answered)){
		foreach($exam_answered as $val){
			// 設問番号をkeyにする
			$exam_no = 0;
			if(isset($arr_list['problem'])){
				foreach($arr_list['problem'] as $problem){
					if($val['exam_problem_id']==$problem['exam_problem_id']){
						$exam_no = $problem['exam_no'];
						break;
					}
				}
				
				if($exam_no>0){
					$answered_list_q[$exam_no]['answer1'] = ''; // 単一形式の解答が入る
					$answered_list_q[$exam_no]['answer2'] = []; // 複数形式の解答が入る
					$answered_list_q[$exam_no]['answer3'] = ''; // フリー形式の解答が入る
					
					// 解答種類の取得
					$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".$val['exam_problem_id']."'";
					$res = $objDbConnect->query_fetch($sql);
					if($res){
						if($res['answer_kind']=='1'){
							if($val['exam_answer_contents']!=''){
								$answered_list_q[$exam_no]['answer1'] = $val['exam_answer_contents'];
							}
						} elseif($res['answer_kind']=='2'){
							if($val['exam_answer_contents']!=''){
								$answered_list_q[$exam_no]['answer2'] = explode(',', $val['exam_answer_contents']);
							} else {
								$answered_list_q[$exam_no]['answer2'] = [];
							}
						} elseif($res['answer_kind']=='3'){
							if($val['exam_answer_contents']!=''){
								$answered_list_q[$exam_no]['answer3'] = $val['exam_answer_contents'];
							}
						}
					}
				}
			}
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($arr_list);
//var_dump($arr_list_q);
//var_dump($answered_list);
//var_dump($answered_list_q);
//exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('eid', $eid);
$template->assign('qid', $qid);
$template->assign('arr_list', $arr_list);
$template->assign('arr_list_q', $arr_list_q);
$template->assign('answered_list', $answered_list);
$template->assign('answered_list_q', $answered_list_q);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());
$template->assign('csrf_token', csrf_token_get());

$template->layout_noside('exam/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>