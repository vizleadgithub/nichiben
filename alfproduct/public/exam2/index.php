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
	$template->layout_pop('exam2/err.tpl');
	exit();
}
$pid = $_GET['pid'];
if(cmCheckInput($pid, 'CK_NUM')){
	$objDbConnect->close();
	$template->layout_pop('exam2/err.tpl');
	exit();
}
if (!isset($_GET['e2id'])){
	$objDbConnect->close();
	$template->layout_pop('exam2/err.tpl');
	exit();
}
$e2id = $_GET['e2id'];
if(cmCheckInput($e2id, 'CK_NUM')){
	$objDbConnect->close();
	$template->layout_pop('exam2/err.tpl');
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];
$back_url = get_back_url();
$arr_list = get_exam2($e2id, $user_id);
//var_dump($arr_list);

// 解答済データの取得
$answered_list = array();
$exam2_answered = get_exam2_answerd($e2id, $user_id, $pid);
if(!empty($exam2_answered)){
	foreach($exam2_answered as $val){
		// 設問番号をkeyにする
		$exam2_no = 0;
		if(isset($arr_list['problem'])){
			foreach($arr_list['problem'] as $problem){
				if($val['exam2_problem_id']==$problem['exam2_problem_id']){
					$exam2_no = $problem['exam2_no'];
					break;
				}
			}
			
			if($exam2_no>0){
				$answered_list[$exam2_no]['answer1'] = ''; // 単一形式の解答が入る
				$answered_list[$exam2_no]['answer2'] = ''; // 複数形式の解答が入る
				$answered_list[$exam2_no]['answer3'] = ''; // フリー形式の解答が入る
				
				// 解答種類の取得
				$sql = "SELECT answer_kind FROM exam2_problem WHERE exam2_problem_id='".$val['exam2_problem_id']."'";
				$res = $objDbConnect->query_fetch($sql);
				if($res){
					if($res['answer_kind']=='1'){
						if($val['exam2_answer_contents']!=''){
							$answered_list[$exam2_no]['answer1'] = $val['exam2_answer_contents'];
						}
					} elseif($res['answer_kind']=='2'){
						if($val['exam2_answer_contents']!=''){
							$answered_list[$exam2_no]['answer2'] = explode(',', $val['exam2_answer_contents']);
						}
					} elseif($res['answer_kind']=='3'){
						if($val['exam2_answer_contents']!=''){
							$answered_list[$exam2_no]['answer3'] = $val['exam2_answer_contents'];
						}
					}
				}
			}
		}
	}
}

$answered_list_q = array();
if($qid!=''){
	$exam2_answered = get_exam2_answerd($qid, $user_id, $pid);
	if(!empty($exam2_answered)){
		foreach($exam2_answered as $val){
			// 設問番号をkeyにする
			$exam2_no = 0;
			if(isset($arr_list['problem'])){
				foreach($arr_list['problem'] as $problem){
					if($val['exam2_problem_id']==$problem['exam2_problem_id']){
						$exam2_no = $problem['exam2_no'];
						break;
					}
				}
				
				if($exam2_no>0){
					$answered_list_q[$exam2_no]['answer1'] = ''; // 単一形式の解答が入る
					$answered_list_q[$exam2_no]['answer2'] = ''; // 複数形式の解答が入る
					$answered_list_q[$exam2_no]['answer3'] = ''; // フリー形式の解答が入る
					
					// 解答種類の取得
					$sql = "SELECT answer_kind FROM exam2_problem WHERE exam2_problem_id='".$val['exam2_problem_id']."'";
					$res = $objDbConnect->query_fetch($sql);
					if($res){
						if($res['answer_kind']=='1'){
							if($val['exam2_answer_contents']!=''){
								$answered_list_q[$exam2_no]['answer1'] = $val['exam2_answer_contents'];
							}
						} elseif($res['answer_kind']=='2'){
							if($val['exam2_answer_contents']!=''){
								$answered_list_q[$exam2_no]['answer2'] = explode(',', $val['exam2_answer_contents']);
							}
						} elseif($res['answer_kind']=='3'){
							if($val['exam2_answer_contents']!=''){
								$answered_list_q[$exam2_no]['answer3'] = $val['exam2_answer_contents'];
							}
						}
					}
				}
			}
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('e2id', $e2id);
$template->assign('qid', $qid);
$template->assign('arr_list', $arr_list);
$template->assign('arr_list_q', $arr_list_q);
$template->assign('answered_list', $answered_list);
$template->assign('answered_list_q', $answered_list_q);
$template->assign('csrf_token', csrf_token_get());

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());
$template->layout_pop('exam2/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>