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

// 解答済みかチェックする
$sql = "SELECT COUNT(exam2_answer_id) AS count FROM exam2_answer WHERE status=0 AND student_id='$user_id' AND exam2_id='$e2id' AND product_id='$pid' ";
$res = $objDbConnect->query_fetch($sql);
if($res['count']<=0){
	$objDbConnect->close();
	$template->layout_pop('exam2/err.tpl');
	exit();
}

$arr_list = get_exam2($e2id, $user_id);
$exam2_answer = array();
$total_score = 0;
$exam2_total_score = 0;

// 解答履歴の取得(テスト)
$sql = "SELECT * FROM exam2_answer WHERE status=0 AND student_id='$user_id' AND exam2_id='$e2id' AND product_id='$pid' ";
$res = $objDbConnect->query_fetch_arr($sql);
if($res){
	foreach($res as $val){
		$total_score += $val['exam2_answer_point'];
		$exam2_answer[$val['exam2_problem_id']]['exam2_answer_contents_str'] = $val['exam2_answer_contents'];
		$exam2_answer[$val['exam2_problem_id']]['exam2_answer_contents'] = explode(',', $val['exam2_answer_contents']);
		$exam2_answer[$val['exam2_problem_id']]['exam2_answer_mark'] = $val['exam2_answer_mark'];
		
		// 設問の正解を取得、問題の合計点数を取得
		$exam2_answer[$val['exam2_problem_id']]['correct_answer_str'] = '';
		$sql = "SELECT answer_point, answer_contents FROM exam2_problem WHERE exam2_problem_id='".$val['exam2_problem_id']."'";
		$res = $objDbConnect->query_fetch($sql);
		if($res){
			$exam2_total_score += $res['answer_point'];
			$arr_answer_contents = json_decode($res['answer_contents']);
			if($arr_answer_contents){
				if($arr_answer_contents->answer_kind=='1' || $arr_answer_contents->answer_kind=='2'){
					foreach($arr_answer_contents->answer_contents as $val2){
						if($val2->correct=='1'){
							if($exam2_answer[$val['exam2_problem_id']]['correct_answer_str']==''){
								$exam2_answer[$val['exam2_problem_id']]['correct_answer_str'] .= $val2->no;
							} else {
								$exam2_answer[$val['exam2_problem_id']]['correct_answer_str'] .= ','.$val2->no;
							}
						}
					}
				} elseif($arr_answer_contents->answer_kind=='3'){
					foreach($arr_answer_contents->answer_contents as $val2){
						if($val2->correct=='1'){
							$exam2_answer[$val['exam2_problem_id']]['correct_answer_str'] .= $val2->word;
						}
					}
				}
			}
		}
	}
}

// 判定基準ありかどうか
$hantei_ari = false;
$sql = "SELECT criteria_value FROM exam2 WHERE exam2_id='".mysqli_real_escape_string($objDbConnect->connect,$e2id)."'";
$ret = $objDbConnect->query_fetch($sql);
if($ret){
	if($ret['criteria_value']>0){
		$hantei_ari = true;
	}
}

// 合格しているかどうか
$passing_flg = false;
$sql = "SELECT COUNT(exam2_answer_id) AS count FROM exam2_answer WHERE status=0 AND passing_flg=1 AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ";
$ret = $objDbConnect->query_fetch($sql);
if($ret){
	if($ret['count']>0){
		$passing_flg = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('e2id', $e2id);
$template->assign('arr_list', $arr_list);
$template->assign('exam2_answer', $exam2_answer);
$template->assign('total_score', $total_score);
$template->assign('exam2_total_score', $exam2_total_score);
$template->assign('hantei_ari', $hantei_ari);
$template->assign('passing_flg', $passing_flg);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_pop('exam2/result.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>