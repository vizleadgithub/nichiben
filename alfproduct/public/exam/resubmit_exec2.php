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
			foreach($ret_exam_answer as $val){
				if($val['exam_id']==$answer['exam_id'] 
				&& $val['exam_problem_id']==$answer['exam_problem_id'] 
				&& $val['student_id']==$answer['student_id'] 
				&& $val['product_id']==$pid 
				&& $val['contents_no']==$ccno 
				){
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
				foreach($ret_exam_answer as $val){
					if($val['exam_id']==$answer['exam_id'] 
					&& $val['exam_problem_id']==$answer['exam_problem_id'] 
					&& $val['student_id']==$answer['student_id'] 
					&& $val['product_id']==$pid 
					&& $val['contents_no']==$ccno 
					){
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
							//$exam_answer_id_list_q[] = $exam_answer_id;
						}
						break;
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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
if($qid!=''){
	header("Location: /exam/result2.php?pid=".$pid."&ccno=".$ccno."&eid=".$eid."&qid=".$qid);
} else {
	header("Location: /exam/result2.php?pid=".$pid."&ccno=".$ccno."&eid=".$eid);
}
exit();
?>