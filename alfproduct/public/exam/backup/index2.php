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
if (!isset($_GET['eno'])){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$eno = $_GET['eno'];
if(cmCheckInput($eno, 'CK_NUM')){
	$objDbConnect->close();
	header("Location: /");
	exit();
}
$eflg = '0';
if(isset($_GET['eflg'])){
	$eflg = $_GET['eflg'];
	if(cmCheckInput($eflg, 'CK_NUM')){
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
	if($eflg!='1'){
		$eflg = '1';
	}
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

// sql条件文用
$eid_sql = $eid;
$eno_sql = $eno;

// アンケートが設定されている場合
$arr_list_q = array();
if($qid!=''){
	$arr_list_q = get_exam($qid, $user_id);
	
	if(!empty($arr_list_q)){
		$eno_max_question = count($arr_list_q['problem']);
		$eno_max += $eno_max_question;
	}
	
	if($eno > $eno_max_test){
		$eid_sql = $qid;
		$eno_sql = $eno - $eno_max_test;
	}
}

$sql = "SELECT exam_problem_id FROM rel_exam_problem WHERE exam_id='".mysql_real_escape_string($eid_sql)."' AND exam_no='".mysql_real_escape_string($eno_sql)."'";
$res = $objDbConnect->query_fetch($sql);
if ($res){
	$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysql_real_escape_string($eid_sql)."' AND exam_problem_id='".mysql_real_escape_string($res['exam_problem_id'])."' AND student_id='".mysql_real_escape_string($user_id)."' AND product_id='".mysql_real_escape_string($pid)."' AND contents_no='".mysql_real_escape_string($ccno)."'";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$answered_info = $res;
		$answered_info['arr_exam_answer_contents'] = explode(',', $res['exam_answer_contents']);
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
			$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".mysql_real_escape_string($post_exam_problem_id)."'";
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
				    '".mysql_real_escape_string($eid)."', 
				    '".mysql_real_escape_string($post_exam_problem_id)."', 
				    '".mysql_real_escape_string($user_id)."', 
				    '".mysql_real_escape_string($exam_answer_contents)."', 
				    '0', 
				    '".$now_date."', 
				    '".mysql_real_escape_string($pid)."', 
				    '".mysql_real_escape_string($ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='".mysql_real_escape_string($exam_answer_contents)."', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysql_real_escape_string($pid)."', 
				    contents_no='".mysql_real_escape_string($ccno)."' 
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
				    '".mysql_real_escape_string($eid)."', 
				    '".mysql_real_escape_string($post_exam_problem_id)."', 
				    '".mysql_real_escape_string($user_id)."', 
				    '', 
				    '0', 
				    '".$now_date."', 
				    '".mysql_real_escape_string($pid)."', 
				    '".mysql_real_escape_string($ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysql_real_escape_string($pid)."', 
				    contents_no='".mysql_real_escape_string($ccno)."' 
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
			$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".mysql_real_escape_string($post_exam_problem_id)."'";
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
				    '".mysql_real_escape_string($qid)."', 
				    '".mysql_real_escape_string($post_exam_problem_id)."', 
				    '".mysql_real_escape_string($user_id)."', 
				    '".mysql_real_escape_string($exam_answer_contents)."', 
				    '0', 
				    '".$now_date."', 
				    '".mysql_real_escape_string($pid)."', 
				    '".mysql_real_escape_string($ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='".mysql_real_escape_string($exam_answer_contents)."', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysql_real_escape_string($pid)."', 
				    contents_no='".mysql_real_escape_string($ccno)."' 
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
				    '".mysql_real_escape_string($qid)."', 
				    '".mysql_real_escape_string($post_exam_problem_id)."', 
				    '".mysql_real_escape_string($user_id)."', 
				    '', 
				    '0', 
				    '".$now_date."', 
				    '".mysql_real_escape_string($pid)."', 
				    '".mysql_real_escape_string($ccno)."' 
				  ) 
				  on duplicate key update 
				    exam_answer_contents='', 
				    exam_answer_status='0', 
				    update_at='".$now_date."', 
				    product_id='".mysql_real_escape_string($pid)."', 
				    contents_no='".mysql_real_escape_string($ccno)."' 
			 ";
			 $objDbConnect->execute($sql);
		}
		
	} else {
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
	
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('eid', $eid);
$template->assign('qid', $qid);
$template->assign('eflg', $eflg);
$template->assign('eno', $eno);
$template->assign('eno_max', $eno_max);
$template->assign('eno_max_test', $eno_max_test);
$template->assign('eno_max_question', $eno_max_question);
$template->assign('arr_list', $arr_list);
$template->assign('arr_list_q', $arr_list_q);
$template->assign('answered_info', $answered_info);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_noside('exam/index2.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>