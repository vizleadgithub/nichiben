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

$return = array();
$exam_answer_id_list = array();
$exam_answer_id_list_q = array();

if( !is_numeric($eid) ) $eid = 0;

// 全ての問題の取得
$exam_list = get_exam($eid, $user_id);

$err_flg = false;
$schoolId = 1;
$nowdate = date("Y-m-d H:i:s");
$temp_answer = array(
	"exam_id" => $eid,
	"exam_problem_id" => '0',
	"student_id" => $user_id,
	"exam_answer_no" => '1',
	"exam_answer_contents" => '',
	"exam_answer_date" => $nowdate,
	"exam_answer_mark" => '0',
	"exam_answer_point" => '0',
	"marked_teacher_id" => '0',
	"exam_answer_mark" => '0',
	"status" => '0',
);

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
$sql  = "";
$sql .= "SELECT MAX(exam_answer_no) as mno ";
$sql .= "  FROM exam_answer ";
$sql .= " WHERE status=0 AND exam_id = '$eid' AND student_id= '$user_id' AND product_id='$pid' AND contents_no='$ccno' ";
$sql .= "   ORDER BY exam_answer_no DESC, exam_answer_id DESC LIMIT 1";
$res = $objDbConnect->query_fetch($sql);
if ($res){
	$temp_answer['exam_answer_no'] = $res["mno"] + 1;
}

// 解答済み情報の取得
$sql  = "";
$sql .= "SELECT * FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$eid' AND product_id='$pid' AND contents_no='$ccno'";
$ret_exam_answer = $objDbConnect->query_fetch_arr($sql);

for ($i = 0; $i < count($_POST["exam_problem_id"] ?? []); $i++){
	$answer = $temp_answer;
	$answer["exam_problem_id"] = $_POST["exam_problem_id"][$i];
	$temp_check = array();
	for ( $j = 0; $j < count($_POST["exam_problem_".$_POST["exam_problem_id"][$i]] ?? []); $j++){
		$exam_problem = trim($_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j])."";
		$exam_problem = convert_symbole( $exam_problem );
		$temp_check[] = $exam_problem;
	}

	$arr_correct_no = array();
	for( $k=0;$k<count($exam_list['problem']);$k++ ){
		if( $exam_list['problem'][$k]["exam_problem_id"]==$_POST["exam_problem_id"][$i] ){
			if( $exam_list['problem'][$k]["answer_kind"]=="1" || $exam_list['problem'][$k]["answer_kind"]=="2" ){
				for( $l=0;$l<count($exam_list['problem'][$k]["answer_contents_select"]["answer_contents"]);$l++ ){
					if( $exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["correct"] == "1" ){
						$arr_correct_no[] = $exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"];
					}
				}
				sort($temp_check);
				sort($arr_correct_no);
				if( array_equal_set($temp_check,$arr_correct_no) ){
					$answer["exam_answer_mark"] = '1';
					$answer["exam_answer_point"] = $exam_list['problem'][$k]["answer_point"];
				}
			}
		}
	}

	$answer["exam_answer_contents"] = implode ( ",", $temp_check);

	// 登録
	if(count($ret_exam_answer)<=0){
		$sql  = "";
		$sql .= "INSERT INTO exam_answer ( ";
		$sql .= "       exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg ";
		$sql .= ") VALUES ('".$answer['exam_id']."', '".$answer['exam_problem_id']."', '".$answer['student_id']."', '".$answer['exam_answer_no']."', '".$answer['exam_answer_contents']."', '".$answer['exam_answer_date']."', '".$answer['exam_answer_mark']."', '".$answer['exam_answer_point']."', '".$answer['marked_teacher_id']."', '".$answer['status']."', '".$pid."', '".$ccno."', '".$question_flg."') ";
		$ret = $objDbConnect->execute($sql);
		if (!$ret){
			$err_flg = true;
		} else {
			//$exam_answer_id_list[] = mysql_insert_id();
			$exam_answer_id_list[] = $objDbConnect->get_thread_id("exam_answer");
		}

	// 更新
	} else {
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
				$sql .= "  exam_answer_no='".$answer['exam_answer_no']."', ";
				$sql .= "  exam_answer_contents='".$answer['exam_answer_contents']."', ";
				$sql .= "  exam_answer_date='".$answer['exam_answer_date']."', ";
				$sql .= "  exam_answer_mark='".$answer['exam_answer_mark']."', ";
				$sql .= "  exam_answer_point='".$answer['exam_answer_point']."', ";
				$sql .= "  update_at='".$answer['exam_answer_date']."' ";
				$sql .= " WHERE";
				$sql .= "  exam_answer_id='".$exam_answer_id."'";
				$ret = $objDbConnect->execute($sql);
				if (!$ret){
					$err_flg = true;
				} else {
					$exam_answer_id_list[] = $exam_answer_id;
				}
				break;
			}
		}
		// 既存のexam_answerにこの問題のレコードが無ければINSERT（フォールバック）
		// 再受験時に前回欠落した問題を復活させるための修正（NICHIBEN_ET-376）
		if(!$matched){
			$sql  = "";
			$sql .= "INSERT INTO exam_answer ( ";
			$sql .= "       exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg ";
			$sql .= ") VALUES ('".$answer['exam_id']."', '".$answer['exam_problem_id']."', '".$answer['student_id']."', '".$answer['exam_answer_no']."', '".$answer['exam_answer_contents']."', '".$answer['exam_answer_date']."', '".$answer['exam_answer_mark']."', '".$answer['exam_answer_point']."', '".$answer['marked_teacher_id']."', '".$answer['status']."', '".$pid."', '".$ccno."', '".$question_flg."') ";
			$ret = $objDbConnect->execute($sql);
			if (!$ret){
				$err_flg = true;
			} else {
				$exam_answer_id_list[] = $objDbConnect->get_thread_id("exam_answer");
			}
		}
	}
}

// 合格フラグの登録
$passing_flg = 0;
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

// アンケートが登録されていた場合
if($qid!=''){
	if( !is_numeric($qid) ) $qid = 0;

	$temp_answer = array(
		"exam_id" => $qid,
		"exam_problem_id" => '0',
		"student_id" => $user_id,
		"exam_answer_no" => '1',
		"exam_answer_contents" => '',
		"exam_answer_date" => $nowdate,
		"exam_answer_mark" => '0',
		"exam_answer_point" => '0',
		"marked_teacher_id" => '0',
		"exam_answer_mark" => '0',
		"status" => '0',
	);

	// 解答回数の取得
	$sql  = "";
	$sql .= "SELECT MAX(exam_answer_no) as mno ";
	$sql .= "  FROM exam_answer ";
	$sql .= " WHERE status=0 AND exam_id = '$qid' AND student_id= '$user_id' AND product_id='$pid' AND contents_no='$ccno' ";
	$sql .= "   ORDER BY exam_answer_no DESC, exam_answer_id DESC LIMIT 1";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		$temp_answer['exam_answer_no'] = $res["mno"] + 1;
	}

	// 解答済み情報の取得
	$sql  = "";
	$sql .= "SELECT * FROM exam_answer WHERE status=0 AND student_id='$user_id' AND exam_id='$qid' AND product_id='$pid' AND contents_no='$ccno'";
	$ret_exam_answer = $objDbConnect->query_fetch_arr($sql);

	for ($i = 0; $i < count($_POST["exam_problem_id_q"] ?? []); $i++){
		$answer = $temp_answer;
		$answer["exam_problem_id"] = $_POST["exam_problem_id_q"][$i];
		$temp_check = array();
		for ( $j = 0; $j < count($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]] ?? []); $j++){
			$exam_problem = trim($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j])."";
			$exam_problem = convert_symbole( $exam_problem );
			$temp_check[] = $exam_problem;
		}

		sort($temp_check);

		$answer["exam_answer_contents"] = implode ( ",", $temp_check);

		// 登録
		if(count($ret_exam_answer)<=0){
			$sql  = "";
			$sql .= "INSERT INTO exam_answer ( ";
			$sql .= "       exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg ";
			$sql .= ") VALUES ('".$answer['exam_id']."', '".$answer['exam_problem_id']."', '".$answer['student_id']."', '".$answer['exam_answer_no']."', '".$answer['exam_answer_contents']."', '".$answer['exam_answer_date']."', '".$answer['exam_answer_mark']."', '".$answer['exam_answer_point']."', '".$answer['marked_teacher_id']."', '".$answer['status']."', '".$pid."', '".$ccno."', '1') ";
			$ret = $objDbConnect->execute($sql);
			if (!$ret){
				$err_flg = true;
			} else {
				//$exam_answer_id_list_q[] = mysql_insert_id();
				$exam_answer_id_list_q[] = $objDbConnect->get_thread_id("exam_answer");
			}

		// 更新
		} else {
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
					$sql .= "  exam_answer_no='".$answer['exam_answer_no']."', ";
					$sql .= "  exam_answer_contents='".$answer['exam_answer_contents']."', ";
					$sql .= "  exam_answer_date='".$answer['exam_answer_date']."', ";
					$sql .= "  exam_answer_mark='".$answer['exam_answer_mark']."', ";
					$sql .= "  exam_answer_point='".$answer['exam_answer_point']."', ";
					$sql .= "  update_at='".$answer['exam_answer_date']."' ";
					$sql .= " WHERE";
					$sql .= "  exam_answer_id='".$exam_answer_id."'";
					$ret = $objDbConnect->execute($sql);
					if (!$ret){
						$err_flg = true;
					} else {
						$exam_answer_id_list_q[] = $exam_answer_id;
					}
					break;
				}
			}
			// 既存のexam_answerにこの問題のレコードが無ければINSERT（フォールバック・NICHIBEN_ET-376）
			if(!$matched_q){
				$sql  = "";
				$sql .= "INSERT INTO exam_answer ( ";
				$sql .= "       exam_id, exam_problem_id, student_id, exam_answer_no, exam_answer_contents, exam_answer_date, exam_answer_mark, exam_answer_point, marked_teacher_id, status, product_id, contents_no, question_flg ";
				$sql .= ") VALUES ('".$answer['exam_id']."', '".$answer['exam_problem_id']."', '".$answer['student_id']."', '".$answer['exam_answer_no']."', '".$answer['exam_answer_contents']."', '".$answer['exam_answer_date']."', '".$answer['exam_answer_mark']."', '".$answer['exam_answer_point']."', '".$answer['marked_teacher_id']."', '".$answer['status']."', '".$pid."', '".$ccno."', '1') ";
				$ret = $objDbConnect->execute($sql);
				if (!$ret){
					$err_flg = true;
				} else {
					$exam_answer_id_list_q[] = $objDbConnect->get_thread_id("exam_answer");
				}
			}
		}
	}
	
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
	$temp_exam = array();
	$return_check = true;
	$html_message = "";
	$formhtml_message = "";
	$prev_no = 0;

	if( count($_POST["exam_problem_id"] ?? [])==0 ){
		$return_check = false;
	} else {
		for ($i = 0; $i < count($_POST["exam_problem_id"] ?? []); $i++){
			$html_message .= '設問'.($i+1).'解答<br>'."";
			
			//=指定半角文字を全角に変換
			//$formhtml_message.= '<input type="hidden" name="exam_problem_id[]" value="'.convert_symbole( $_POST["exam_problem_id"][$i] ).'">'."";
			//$formhtml_message.= '<input type="hidden" name="pid" value="'.$pid.'">'."";
			//$formhtml_message.= '<input type="hidden" name="ccno" value="'.$ccno.'">'."";
			
			$temp_check = array();
			for ( $j = 0; $j < count($_POST["exam_problem_".$_POST["exam_problem_id"][$i]] ?? []); $j++){
				if( trim($_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j])!="" ){
					$temp_check[] = trim($_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j]);

					for( $k=0;$k<count($exam_list['problem']);$k++ ){
						if( $exam_list['problem'][$k]["exam_problem_id"]==$_POST["exam_problem_id"][$i] ){
							if( $exam_list['problem'][$k]["answer_kind"]=="1" || $exam_list['problem'][$k]["answer_kind"]=="2" ){
								for( $l=0;$l<count($exam_list['problem'][$k]["answer_contents_select"]["answer_contents"]);$l++ ){
									if( trim($_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j]) == $exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"] ){
										$html_message.= '・'.$exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"].'. '.nl2br(htmlspecialchars( $exam_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["word"] ))."<br>";
									}
								}
							} else {
								$html_message.= '・'.nl2br(htmlspecialchars( $_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j] ))."<br>";
							}
						}
					}
				}
				//=指定半角文字を全角に変換
				//$formhtml_message.= '<input type="hidden" name="'."exam_problem_".convert_symbole( $_POST["exam_problem_id"][$i] ).'[]" value="'.convert_symbole( trim($_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j]) ).'">';
			}
			if( count($temp_check)==0 ){
				$return_check = false;
				$html_message.= '<span style="color: #FF0000;">未解答</span><br>'."";
				if($prev_no==0){
					//=指定半角文字を全角に変換
					$prev_no = convert_symbole( $_POST["exam_problem_id"][$i] );
				}
			} else {
				
			}
			$html_message.= '<br>'."";
		}
		if( $return_check == false ){
			$html_message = '<span style="color: #FF0000;">未解答の設問があります！</span><br><br>'."".$html_message;
		}
		
		// アンケートが設定されていた場合
		if($qid!=''){
			// 全てのアンケートの取得
			$exam_list_q = get_exam($qid, $user_id);
			
			$no = $i;
			for ($i = 0; $i < count($_POST["exam_problem_id_q"] ?? []); $i++){
				$html_message .= '設問'.($i+1+$no).'解答<br>'."";
				
				//=指定半角文字を全角に変換
				//$formhtml_message.= '<input type="hidden" name="exam_problem_id_q[]" value="'.convert_symbole( $_POST["exam_problem_id_q"][$i] ).'">'."";

				$temp_check = array();
				for ( $j = 0; $j < count($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]] ?? []); $j++){
					if( trim($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j])!="" ){
						$temp_check[] = trim($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j]);

						for( $k=0;$k<count($exam_list_q['problem']);$k++ ){
							if( $exam_list_q['problem'][$k]["exam_problem_id"]==$_POST["exam_problem_id_q"][$i] ){
								if( $exam_list_q['problem'][$k]["answer_kind"]=="1" || $exam_list_q['problem'][$k]["answer_kind"]=="2" ){
									for( $l=0;$l<count($exam_list_q['problem'][$k]["answer_contents_select"]["answer_contents"]);$l++ ){
										if( trim($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j]) == $exam_list_q['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"] ){
											$html_message.= '・'.$exam_list_q['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"].'. '.nl2br(htmlspecialchars( $exam_list_q['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["word"] ))."<br>";
										}
									}
								} else {
									$html_message.= '・'.nl2br(htmlspecialchars( $_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j] ))."<br>";
								}
							}
						}
					}
					//=指定半角文字を全角に変換
					//$formhtml_message.= '<input type="hidden" name="'."exam_problem_q_".convert_symbole( $_POST["exam_problem_id_q"][$i] ).'[]" value="'.convert_symbole( trim($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j]) ).'">';
				}
				if( count($temp_check)==0 ){
					$return_check = false;
					$html_message.= '<span style="color: #FF0000;">未解答</span><br>'."";
					if($prev_no==0){
						//=指定半角文字を全角に変換
						$prev_no = convert_symbole( $_POST["exam_problem_id_q"][$i] );
					}
				} else {
					
				}
				$html_message.= '<br>'."";
			}
			
			//$formhtml_message = '<form id="form_regist_answer" name="form_regist_answer" method="post" action="/exam/answer_save.php?eid='.$eid.'&pid='.$pid.'&ccno='.$ccno.'&qid='.$qid.'">'."".$formhtml_message."".'</form>';
		} else {
			//$formhtml_message = '<form id="form_regist_answer" name="form_regist_answer" method="post" action="/exam/answer_save.php?eid='.$eid.'&pid='.$pid.'&ccno='.$ccno.'">'."".$formhtml_message."".'</form>';
		}
		
		//$formhtml_message.= '<input type="hidden" name="exam_id" value="'.$eid.'">';
	}

	$return = array(
		"return_check"=>$return_check,
		"html"=>$html_message,
		"formhtml"=>$formhtml_message,
		"no_answer"=>$prev_no,
	);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('eid', $eid);
$template->assign('qid', $qid);
$template->assign('return', $return);
$template->assign('err_flg', $err_flg);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_alfstream('exam/answer_save.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>