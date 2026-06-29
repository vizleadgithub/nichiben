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
if(ereg("iPad", $agent)){//iPad
	$isPad = true;
}
$isApple = false;
if(ereg("iPhone", $agent)){//iPhone
	$isApple = true;
} elseif(ereg("iPad", $agent)){//iPhone
	$isApple = true;
} elseif(ereg("iPod", $agent)){//iPhone
	$isApple = true;
}
$isAndroid = false;
if(ereg("Android", $agent)){//Android
	$isAndroid = true;
}
$isAndroidTablet = false;
if(ereg("Android", $agent)){
	if(ereg("Mobile", $agent) && ereg("SC-01C", $agent)){
		$isAndroidTablet = true;
	}elseif(ereg("mobile", $agent)){
		$isAndroidTablet = false;
	} elseif(ereg("Mobile", $agent)){
		$isAndroidTablet = false;
	} elseif(ereg("Tablet", $agent)){
		$isAndroidTablet = true;
	} else {
		$isAndroidTablet = true;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
csrf_token_verify();
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
$answered_info = array();
$eno_max = 0;
$eno_max_test = 0;
$eno_max_question = 0;

if(!empty($arr_list)){
	$eno_max_test = count($arr_list['problem']);
	$eno_max = $eno_max_test;
}

// アンケートが設定されている場合
$arr_list_q = array();
if($qid!=''){
	$arr_list_q = get_exam($qid, $user_id);
	
	if(!empty($arr_list_q)){
		$eno_max_question = count($arr_list_q['problem']);
		$eno_max += $eno_max_question;
	}
}

if(!empty($_POST)){
	// テスト
	if(isset($_POST['exam_problem_id'])){
		$post_exam_problem_id = $_POST['exam_problem_id'][0];
		
		if(cmCheckInput($post_exam_problem_id, 'CK_NUM')){
			$objDbConnect->close();
			header("Location: /");
			exit();
		}
		
		if(isset($_POST['exam_problem_'.$post_exam_problem_id])){
			$post_exam_answer_contents = $_POST['exam_problem_'.$post_exam_problem_id];
			
			$answer_kind = '';
			$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".mysqli_real_escape_string($objDbConnect->connect,$post_exam_problem_id)."'";
			$res = $objDbConnect->query_fetch($sql);
			if($res){
				$answer_kind = $res['answer_kind'];
			}
			
			$exam_answer_contents = '';
			foreach($post_exam_answer_contents as $val){
				if($answer_kind==='1'){
					$exam_answer_contents = $val;
				} elseif($answer_kind==='2'){
					if($exam_answer_contents==''){
						$exam_answer_contents .= $val;
					} else {
						$exam_answer_contents .= ','.$val;
					}
				} elseif($answer_kind==='3'){
					$exam_answer_contents = $val;
				}
				
			}
			
			$now_date = date('Y-m-d H:i:s');
			$sql = "
				insert into 
				  exam_answer_retry (
				    exam_id, 
				    exam_problem_id, 
				    student_id, 
				    exam_answer_contents, 
				    exam_answer_status, 
				    update_at, 
				    product_id, 
				    contents_no 
				  ) 
				  values (
				    '".mysqli_real_escape_string($objDbConnect->connect,$eid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$post_exam_problem_id)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$exam_answer_contents)."', 
				    '0', 
				    '".$now_date."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='".mysqli_real_escape_string($objDbConnect->connect,$exam_answer_contents)."', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
			 ";
			 $objDbConnect->execute($sql);
			 
		} else {
			$now_date = date('Y-m-d H:i:s');
			$sql = "
				insert into 
				  exam_answer_retry (
				    exam_id, 
				    exam_problem_id, 
				    student_id, 
				    exam_answer_contents, 
				    exam_answer_status, 
				    update_at, 
				    product_id, 
				    contents_no 
				  ) 
				  values (
				    '".mysqli_real_escape_string($objDbConnect->connect,$eid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$post_exam_problem_id)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', 
				    '', 
				    '0', 
				    '".$now_date."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
			 ";
			 $objDbConnect->execute($sql);
		}
		
	// アンケート
	} elseif(isset($_POST['exam_problem_id_q'])){
		$post_exam_problem_id = $_POST['exam_problem_id_q'][0];
		
		if(cmCheckInput($post_exam_problem_id, 'CK_NUM')){
			$objDbConnect->close();
			header("Location: /");
			exit();
		}
		
		if(isset($_POST['exam_problem_q_'.$post_exam_problem_id])){
			$post_exam_answer_contents = $_POST['exam_problem_q_'.$post_exam_problem_id];
			
			$answer_kind = '';
			$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".mysqli_real_escape_string($objDbConnect->connect,$post_exam_problem_id)."'";
			$res = $objDbConnect->query_fetch($sql);
			if($res){
				$answer_kind = $res['answer_kind'];
			}
			
			$exam_answer_contents = '';
			foreach($post_exam_answer_contents as $val){
				if($answer_kind==='1'){
					$exam_answer_contents = $val;
				} elseif($answer_kind==='2'){
					if($exam_answer_contents==''){
						$exam_answer_contents .= $val;
					} else {
						$exam_answer_contents .= ','.$val;
					}
				} elseif($answer_kind==='3'){
					$exam_answer_contents = $val;
				}
				
			}
			
			$now_date = date('Y-m-d H:i:s');
			$sql = "
				insert into 
				  exam_answer_retry (
				    exam_id, 
				    exam_problem_id, 
				    student_id, 
				    exam_answer_contents, 
				    exam_answer_status, 
				    update_at, 
				    product_id, 
				    contents_no 
				  ) 
				  values (
				    '".mysqli_real_escape_string($objDbConnect->connect,$qid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$post_exam_problem_id)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$exam_answer_contents)."', 
				    '0', 
				    '".$now_date."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='".mysqli_real_escape_string($objDbConnect->connect,$exam_answer_contents)."', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
			 ";
			 $objDbConnect->execute($sql);
			 
		} else {
			$now_date = date('Y-m-d H:i:s');
			$sql = "
				insert into 
				  exam_answer_retry (
				    exam_id, 
				    exam_problem_id, 
				    student_id, 
				    exam_answer_contents, 
				    exam_answer_status, 
				    update_at, 
				    product_id, 
				    contents_no 
				  ) 
				  values (
				    '".mysqli_real_escape_string($objDbConnect->connect,$qid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$post_exam_problem_id)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$user_id)."', 
				    '', 
				    '0', 
				    '".$now_date."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    '".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."', 
				    contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' 
			 ";
			 $objDbConnect->execute($sql);
		}
		
	} else {
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
	
}

$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$eid)."' AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."'";
$res = $objDbConnect->query_fetch_arr($sql);
if ($res){
	foreach($res as $val){
		$answered_info[$val['exam_problem_id']]['arr_exam_answer_contents'] = explode(',', $val['exam_answer_contents']);
	}
}

if($qid!=''){
	$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$qid)."' AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."'";
	$res = $objDbConnect->query_fetch_arr($sql);
	if ($res){
		foreach($res as $val){
			$answered_info[$val['exam_problem_id']]['arr_exam_answer_contents'] = explode(',', $val['exam_answer_contents']);
		}
	}
}

// ボタンタイプの取得
$btn_type = '0';
$sql = "SELECT btn_type FROM rel_product_contents WHERE product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' AND exam_id_test='".mysqli_real_escape_string($objDbConnect->connect,$eid)."'";
$res = $objDbConnect->query_fetch($sql);
if($res){
	$btn_type = $res['btn_type'];
}
$btn_type_q = '0';
if($qid!=''){
	$sql = "SELECT btn_type FROM rel_product_contents WHERE product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."' AND exam_id_question='".mysqli_real_escape_string($objDbConnect->connect,$qid)."'";
	$res = $objDbConnect->query_fetch($sql);
	if($res){
		$btn_type_q = $res['btn_type'];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('eid', $eid);
$template->assign('qid', $qid);
$template->assign('eno_max', $eno_max);
$template->assign('eno_max_test', $eno_max_test);
$template->assign('eno_max_question', $eno_max_question);
$template->assign('arr_list', $arr_list);
$template->assign('arr_list_q', $arr_list_q);
$template->assign('answered_info', $answered_info);
$template->assign('btn_type', $btn_type);
$template->assign('btn_type_q', $btn_type_q);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->assign('csrf_token', csrf_token_get());
$template->layout_noside('exam/confirm2.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>