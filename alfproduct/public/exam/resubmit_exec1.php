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

$qid = '';
if(isset($_GET['qid'])){
	$qid = $_GET['qid'];
	if(cmCheckInput($qid, 'CK_NUM')){
		$objDbConnect->close();
		header("Location: /");
		exit();
	}
}

$eflg = '0';
if(isset($_GET['eflg'])){
	$eflg = $_GET['eflg'];
	if($eflg!='1'){
		$eflg = '1';
	}
}
if(isset($_POST['eflg'])){
	$eflg = $_POST['eflg'];
	if($eflg!='1'){
		$eflg = '1';
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$user_id = $_SESSION['user']['id'];

$back_url = get_back_url();

$exam_answer_id_list = array();
$exam_answer_id_list_q = array();

$exam_list = get_exam($eid, $user_id);

$err_flg = false;
$nowdate = date("Y-m-d H:i:s");

if(!empty($_POST)){
	// テスト
	if(isset($_POST['exam_problem_id'])){
		$post_exam_problem_id = $_POST['exam_problem_id'][0];
		
		if(cmCheckInput($post_exam_problem_id, 'CK_NUM')){
			$err_flg = true;
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
			$err_flg = true;
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
		$err_flg = true;
	}
	
}

if(!$err_flg){
	$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$eid)."' AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."'";
	$res_exam_answer_retry = $objDbConnect->query_fetch_arr($sql);
	if ($res_exam_answer_retry){
		// アンケートフラグの取得
		$question_flg = 0;
		$sql  = "";
		$sql .= "SELECT exam_id_question FROM rel_product_contents WHERE product_id='$pid' AND contents_no='$ccno'";
		$res = $objDbConnect->query_fetch($sql);
		if($res){
			if($eid==$res['exam_id_question']){
				$question_flg = 1;
			}
		}
		
		// 解答回数の取得
		$exam_answer_no = 1;
		$sql  = "";
		$sql .= "SELECT MAX(exam_answer_no) as mno ";
		$sql .= "  FROM exam_answer ";
		$sql .= " WHERE status=0 AND exam_id = '$eid' AND student_id= '$user_id' AND product_id='$pid' AND contents_no='$ccno' ";
		$sql .= "   ORDER BY exam_answer_no DESC, exam_answer_id DESC LIMIT 1";
		$res = $objDbConnect->query_fetch($sql);
		if ($res){
			$exam_answer_no += $res["mno"];
		}
		
		// 解答済み情報の取得
		$sql  = "";
		$sql .= "SELECT * FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$eid' AND product_id='$pid' AND contents_no='$ccno'";
		$ret_exam_answer = $objDbConnect->query_fetch_arr($sql);
		
		//$objDbConnect->tran_begin();
		
		foreach($res_exam_answer_retry as $answer){
			$temp_check = array();
			
			$sql = "SELECT answer_kind FROM exam_problem WHERE exam_problem_id='".$answer['exam_problem_id']."'";
			$exam_problem = $objDbConnect->query_fetch($sql);
			if($exam_problem){
				if($exam_problem['answer_kind']=='1'){
					$temp_check[] = $answer['exam_answer_contents'];
				} elseif($exam_problem['answer_kind']=='2'){
					$arr_exam_answer_contents = array();
					$arr_exam_answer_contents = explode(',', $answer['exam_answer_contents']);
					foreach($arr_exam_answer_contents as $val){
						$temp_check[] = $val;
					}
				} elseif($exam_problem['answer_kind']=='3'){
					$temp_check[] = convert_symbole( $answer['exam_answer_contents'] );
				}
			}
			
			$arr_correct_no = array();
			$exam_answer_mark = 0;
			$exam_answer_point = 0;
			for($k=0; $k<count($exam_list['problem']); $k++){
				if( $exam_list['problem'][$k]["exam_problem_id"]==$answer['exam_problem_id'] ){
					if( $exam_list['problem'][$k]["answer_kind"]=="1" || $exam_list['problem'][$k]["answer_kind"]=="2" ){
						for($l=0; $l<count($exam_list['problem'][$k]["answer_contents_select"]["answer_contents"]); $l++){
							if( $exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["correct"] == "1" ){
								$arr_correct_no[] = $exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"];
							}
						}
						sort($temp_check);
						sort($arr_correct_no);
						if( array_equal_set($temp_check,$arr_correct_no) ){
							$exam_answer_mark = '1';
							$exam_answer_point = $exam_list['problem'][$k]["answer_point"];
						}
					}
				}
			}
			
			// 更新
			$matched = false;
			foreach($ret_exam_answer as $val){
				if($val['exam_id']==$answer['exam_id']
				&& $val['exam_problem_id']==$answer['exam_problem_id']
				&& $val['student_id']==$answer['student_id']
				&& $val['product_id']==$pid
				&& $val['contents_no']==$ccno
				){
					$matched = true;
					$exam_answer_id = $val['exam_answer_id'];
					$sql  = "";
					$sql .= "UPDATE exam_answer SET ";
					$sql .= "  exam_answer_no='".$exam_answer_no."', ";
					$sql .= "  exam_answer_contents='".$answer['exam_answer_contents']."', ";
					$sql .= "  exam_answer_date='".$nowdate."', ";
					$sql .= "  exam_answer_mark='".$exam_answer_mark."', ";
					$sql .= "  exam_answer_point='".$exam_answer_point."', ";
					$sql .= "  update_at='".$nowdate."' ";
					$sql .= " WHERE";
					$sql .= "  exam_answer_id='".$exam_answer_id."'";
					$ret = $objDbConnect->execute($sql);
					if (!$ret){
						$err_flg = true;
					} else {
						//$exam_answer_id_list[] = $exam_answer_id;
					}
					break;
				}
			}
			// 既存のexam_answerにこの問題のレコードが無ければINSERT（フォールバック・NICHIBEN_ET-376）
			if(!$matched){
				$sql  = "";
				$sql .= "INSERT INTO exam_answer ( ";
				$sql .= "       exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg ";
				$sql .= ") VALUES ('".$answer['exam_id']."', '".$answer['exam_problem_id']."', '".$answer['student_id']."', '".$exam_answer_no."', '".$answer['exam_answer_contents']."', '".$nowdate."', '".$exam_answer_mark."', '".$exam_answer_point."', '0', '0', '".$pid."', '".$ccno."', '".$question_flg."') ";
				$ret = $objDbConnect->execute($sql);
				if (!$ret){
					$err_flg = true;
				}
			}
		}

		// 解答の修正が一問ずつとなるため、exam_answer_idの総取得
		if(!$err_flg){
			$sql = "SELECT exam_answer_id FROM exam_answer WHERE student_id='".$user_id."' AND product_id='".$pid."' AND contents_no='".$ccno."' AND exam_id='".$eid."'";
			$ret = $objDbConnect->query_fetch_arr($sql);
			if($ret){
				foreach($ret as $val){
					$exam_answer_id_list[] = $val['exam_answer_id'];
				}
			} else {
				$err_flg = true;
			}
		}
		
		$passing_flg = 0; // 合格フラグ
		if(!$err_flg){
			// 合格フラグの登録
			if(!empty($exam_answer_id_list)){
				$exam_id = $answer['exam_id'];
				
				$sql = "SELECT criteria_type, criteria_value FROM exam WHERE exam_id='".$exam_id."'";
				$ret_exam = $objDbConnect->query_fetch($sql);
				if($ret_exam){
					// 点数
					if($ret_exam['criteria_type']=='1'){
						$my_total_answer_point = 0;
						foreach($exam_answer_id_list as $exam_answer_id){
							$sql = "SELECT exam_answer_point FROM exam_answer WHERE exam_answer_id='".$exam_answer_id."'";
							$ret = $objDbConnect->query_fetch($sql);
							if($ret){
								$my_total_answer_point += $ret['exam_answer_point'];
							}
						}
						
						if($ret_exam['criteria_value']<=$my_total_answer_point){
							$passing_flg = 1;
						}
						
					// 割合
					} elseif($ret_exam['criteria_type']=='2'){
						$my_total_answer_point = 0;
						foreach($exam_answer_id_list as $exam_answer_id){
							$sql = "SELECT exam_answer_point FROM exam_answer WHERE exam_answer_id='".$exam_answer_id."'";
							$ret = $objDbConnect->query_fetch($sql);
							if($ret){
								$my_total_answer_point += $ret['exam_answer_point'];
							}
						}
						
						$sql = "SELECT SUM(answer_point) AS total_answer_point FROM rel_exam_problem LEFT JOIN exam_problem ON rel_exam_problem.exam_problem_id=exam_problem.exam_problem_id WHERE rel_exam_problem.exam_id='".$exam_id."'";
						$ret = $objDbConnect->query_fetch($sql);
						if($ret){
							$check_value = floor($ret_exam['criteria_value'] / 100 * $ret['total_answer_point']);
							if($check_value<=$my_total_answer_point){
								$passing_flg = 1;
							}
						}
						
					// 正答数
					} elseif($ret_exam['criteria_type']=='3'){
						$my_total_answer_point = 0;
						foreach($exam_answer_id_list as $exam_answer_id){
							$sql = "SELECT exam_answer_mark FROM exam_answer WHERE exam_answer_id='".$exam_answer_id."'";
							$ret = $objDbConnect->query_fetch($sql);
							if($ret){
								if($ret['exam_answer_mark']=='1'){
									$my_total_answer_point += 1;
								}
							}
						}
						
						if($ret_exam['criteria_value']<=$my_total_answer_point){
							$passing_flg = 1;
						}
						
					}
					
					foreach($exam_answer_id_list as $exam_answer_id){
						$sql = "UPDATE exam_answer SET passing_flg='".$passing_flg."', criteria_type='".$ret_exam['criteria_type']."', criteria_value='".$ret_exam['criteria_value']."' WHERE exam_answer_id='".$exam_answer_id."'";
						$ret = $objDbConnect->execute($sql);
						if (!$ret){
							$err_flg = true;
						}
					}
				}
			}
		}
		
		if(!$err_flg){
			$sql  = "";
			$sql .= "DELETE FROM exam_answer_retry WHERE exam_id='$eid' AND student_id='$user_id' AND product_id='$pid' AND contents_no='$ccno' ";
			$ret = $objDbConnect->execute($sql);
			if (!$ret){
				$err_flg = true;
			}
		}
		
		//if(!$err_flg){
		//	$objDbConnect->commit();
		//} else {
		//	$objDbConnect->rollback();
		//}
	}
	
	// アンケート
	if($qid!=''){
		$sql = "SELECT * FROM exam_answer_retry WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$qid)."' AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."'";
		$res_exam_answer_retry = $objDbConnect->query_fetch_arr($sql);
		if ($res_exam_answer_retry){
			// 解答回数の取得
			$exam_answer_no = 1;
			$sql  = "";
			$sql .= "SELECT MAX(exam_answer_no) as mno ";
			$sql .= "  FROM exam_answer ";
			$sql .= " WHERE status=0 AND exam_id = '$qid' AND student_id= '$user_id' AND product_id='$pid' AND contents_no='$ccno' ";
			$sql .= "   ORDER BY exam_answer_no DESC, exam_answer_id DESC LIMIT 1";
			$res = $objDbConnect->query_fetch($sql);
			if ($res){
				$exam_answer_no += $res["mno"];
			}
			
			// 解答済み情報の取得
			$sql  = "";
			$sql .= "SELECT * FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$qid' AND product_id='$pid' AND contents_no='$ccno'";
			$ret_exam_answer = $objDbConnect->query_fetch_arr($sql);
			
			//$objDbConnect->tran_begin();
			
			foreach($res_exam_answer_retry as $answer){
				// 更新
				$matched_q = false;
				foreach($ret_exam_answer as $val){
					if($val['exam_id']==$answer['exam_id']
					&& $val['exam_problem_id']==$answer['exam_problem_id']
					&& $val['student_id']==$answer['student_id']
					&& $val['product_id']==$pid
					&& $val['contents_no']==$ccno
					){
						$matched_q = true;
						$exam_answer_id = $val['exam_answer_id'];
						$sql  = "";
						$sql .= "UPDATE exam_answer SET ";
						$sql .= "  exam_answer_no='".$exam_answer_no."', ";
						$sql .= "  exam_answer_contents='".$answer['exam_answer_contents']."', ";
						$sql .= "  exam_answer_date='".$nowdate."', ";
						$sql .= "  exam_answer_mark='0', ";
						$sql .= "  exam_answer_point='0', ";
						$sql .= "  update_at='".$nowdate."' ";
						$sql .= " WHERE";
						$sql .= "  exam_answer_id='".$exam_answer_id."'";
						$ret = $objDbConnect->execute($sql);
						if (!$ret){
							$err_flg = true;
						} else {
							//$exam_answer_id_list_q[] = $exam_answer_id;
						}
						break;
					}
				}
				// アンケート再提出のフォールバックINSERT（NICHIBEN_ET-376）
				if(!$matched_q){
					$sql  = "";
					$sql .= "INSERT INTO exam_answer ( ";
					$sql .= "       exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg ";
					$sql .= ") VALUES ('".$answer['exam_id']."', '".$answer['exam_problem_id']."', '".$answer['student_id']."', '".$exam_answer_no."', '".$answer['exam_answer_contents']."', '".$nowdate."', '0', '0', '0', '0', '".$pid."', '".$ccno."', '1') ";
					$ret = $objDbConnect->execute($sql);
					if (!$ret){
						$err_flg = true;
					}
				}
			}

			// 解答の修正が一問ずつとなるため、exam_answer_idの総取得
			if(!$err_flg){
				$sql = "SELECT exam_answer_id FROM exam_answer WHERE student_id='".$user_id."' AND product_id='".$pid."' AND contents_no='".$ccno."' AND exam_id='".$qid."'";
				$ret = $objDbConnect->query_fetch_arr($sql);
				if($ret){
					foreach($ret as $val){
						$exam_answer_id_list_q[] = $val['exam_answer_id'];
					}
				} else {
					$err_flg = true;
				}
			}
			
			if(!$err_flg){
				// 合格フラグの登録
				if(!empty($exam_answer_id_list_q)){
					$exam_id = $answer['exam_id'];
					
					$sql = "SELECT criteria_type, criteria_value FROM exam WHERE exam_id='".$exam_id."'";
					$ret_exam = $objDbConnect->query_fetch($sql);
					if($ret_exam){
						foreach($exam_answer_id_list_q as $exam_answer_id){
							$sql = "UPDATE exam_answer SET passing_flg='".$passing_flg."', criteria_type='".$ret_exam['criteria_type']."', criteria_value='".$ret_exam['criteria_value']."' WHERE exam_answer_id='".$exam_answer_id."'";
							$ret = $objDbConnect->execute($sql);
							if (!$ret){
								$err_flg = true;
							}
						}
					}
				}
			}
			
			if(!$err_flg){
				$sql  = "";
				$sql .= "DELETE FROM exam_answer_retry WHERE exam_id='$qid' AND student_id='$user_id' AND product_id='$pid' AND contents_no='$ccno' ";
				$ret = $objDbConnect->execute($sql);
				if (!$ret){
					$err_flg = true;
				}
			}
			
			//if(!$err_flg){
			//	$objDbConnect->commit();
			//} else {
			//	$objDbConnect->rollback();
			//}
		}
	}
}

// 表示用データの取得
$arr_list = array();
$arr_list_q = array();
$answered_info = array();
$eno_max = 0;
$eno_max_test = 0;
$eno_max_question = 0;
if(!$err_flg){
	$arr_list = get_exam($eid, $user_id);
	
	if(!empty($arr_list)){
		$eno_max_test = count($arr_list['problem']);
		$eno_max = $eno_max_test;
	}

	// sql条件文用
	$eid_sql = $eid;
	$eno_sql = $eno;

	// アンケートが設定されている場合
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

	$sql = "SELECT exam_problem_id FROM rel_exam_problem WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$eid_sql)."' AND exam_no='".mysqli_real_escape_string($objDbConnect->connect,$eno_sql)."'";
	$res_rel_exam_problem = $objDbConnect->query_fetch($sql);
	if ($res_rel_exam_problem){
		$sql = "SELECT * FROM exam_answer WHERE exam_id='".mysqli_real_escape_string($objDbConnect->connect,$eid_sql)."' AND exam_problem_id='".mysqli_real_escape_string($objDbConnect->connect,$res_rel_exam_problem['exam_problem_id'])."' AND student_id='".mysqli_real_escape_string($objDbConnect->connect,$user_id)."' AND product_id='".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND contents_no='".mysqli_real_escape_string($objDbConnect->connect,$ccno)."'";
		$res_exam_answer = $objDbConnect->query_fetch($sql);
		
		$answered_info[$res_rel_exam_problem['exam_problem_id']]['exam_answer_contents_str'] = $res_exam_answer['exam_answer_contents'];
		$answered_info[$res_rel_exam_problem['exam_problem_id']]['exam_answer_contents'] = explode(',', $res_exam_answer['exam_answer_contents']);
		$answered_info[$res_rel_exam_problem['exam_problem_id']]['exam_answer_mark'] = 0;
		
		if($eno > $eno_max_test){
			// アンケートの場合は正解取得が不要
		} else {
			// 設問の正解を取得
			$answered_info[$res_rel_exam_problem['exam_problem_id']]['correct_answer_str'] = '';
			$sql = "SELECT answer_point, answer_contents FROM exam_problem WHERE exam_problem_id='".$res_rel_exam_problem['exam_problem_id']."'";
			$res_exam_problem = $objDbConnect->query_fetch($sql);
			if($res_exam_problem){
				$arr_answer_contents = json_decode($res_exam_problem['answer_contents']);
				if($arr_answer_contents){
					$temp_check = array();
					$arr_correct_no = array();
					
					if($arr_answer_contents->answer_kind=='1' || $arr_answer_contents->answer_kind=='2'){
						foreach($arr_answer_contents->answer_contents as $val){
							if($val->correct=='1'){
								$arr_correct_no[] = $val->no;
								if($answered_info[$res_rel_exam_problem['exam_problem_id']]['correct_answer_str']==''){
									$answered_info[$res_rel_exam_problem['exam_problem_id']]['correct_answer_str'] .= $val->no;
								} else {
									$answered_info[$res_rel_exam_problem['exam_problem_id']]['correct_answer_str'] .= ','.$val->no;
								}
							}
						}
						
						if($arr_answer_contents->answer_kind=='2'){
							$arr_exam_answer_contents = explode(',', $res_exam_answer['exam_answer_contents']);
							foreach($arr_exam_answer_contents as $val){
								$temp_check[] = $val;
							}
						} else {
							$temp_check[] = $res_exam_answer['exam_answer_contents'];
						}
						
						sort($temp_check);
						sort($arr_correct_no);
						
						if( array_equal_set($temp_check,$arr_correct_no) ){
							$answered_info[$res_rel_exam_problem['exam_problem_id']]['exam_answer_mark'] = 1;
						}
						
					} elseif($arr_answer_contents->answer_kind=='3'){
						foreach($arr_answer_contents->answer_contents as $val){
							if($val->correct=='1'){
								$answered_info[$res_rel_exam_problem['exam_problem_id']]['correct_answer_str'] .= $val->word;
							}
						}
					}
				}
			}
		}
	}
}

// エラーがあった場合は解答結果画面に戻す
if($err_flg){
	$objDbConnect->close();
	header("Location: /exam/result1.php?pid=".$pid."&ccno=".$ccno."&eid=".$eid."&_=".date("YmdHis"));
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if($eflg=='1'){
	$objDbConnect->close();
	header("Location: /exam/result1.php?pid=".$pid."&ccno=".$ccno."&eid=".$eid."&_=".date("YmdHis"));
	exit();
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

$template->assign('csrf_token', csrf_token_get());
$template->layout_noside('exam/resubmit_exec_result1.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>