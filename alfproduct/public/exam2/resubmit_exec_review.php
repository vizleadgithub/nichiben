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

// 管理者側で更新処理がされていないかチェックする
$sql  = "";
$sql .= "SELECT SUM(update_count) AS update_count FROM exam2_answer WHERE status=0 AND student_id='$user_id' AND exam2_id='$e2id' AND product_id='$pid' ";
$res = $objDbConnect->query_fetch($sql);
if($res){
	if($res['update_count'] !== $_SESSION['exam2_answer_update_count']){
		$template->layout_alfstream('exam2/resubmit_exec_update_count_error.tpl');
		$objDbConnect->close();
		exit();
	}
}


$exam2_answer_id_list = array();
$exam2_answer_id_list_q = array();

if( !is_numeric($e2id) ) $e2id = 0;

// 全ての問題の取得
$exam2_list = get_exam2($e2id, $user_id);

$err_flg = false;
$schoolId = 1;
$nowdate = date("Y-m-d H:i:s");
$temp_answer = array(
	"exam2_id" => $e2id,
	"exam2_problem_id" => '0',
	"student_id" => $user_id,
	"exam2_answer_no" => '1',
	"exam2_answer_contents" => '',
	"exam2_answer_date" => $nowdate,
	"exam2_answer_mark" => '0',
	"exam2_answer_point" => '0',
	"marked_teacher_id" => '0',
	"exam2_answer_mark" => '0',
	"status" => '0',
);

// アンケートフラグの取得
$question_flg = 0;
$question_flg = 1;

// 解答回数の取得
$sql  = "";
$sql .= "SELECT MAX(exam2_answer_no) as mno ";
$sql .= "  FROM exam2_answer ";
$sql .= " WHERE status=0 AND exam2_id = '$e2id' AND student_id= '$user_id' AND product_id='$pid' ";
$sql .= "   ORDER BY exam2_answer_no DESC, exam2_answer_id DESC LIMIT 1";
$res = $objDbConnect->query_fetch($sql);
if ($res){
	$temp_answer['exam2_answer_no'] = $res["mno"] + 1;
}

// 解答済み情報の取得
$sql  = "";
$sql .= "SELECT * FROM exam2_answer WHERE status=0 AND student_id='$user_id' AND exam2_id='$e2id' AND product_id='$pid' ";
$ret_exam2_answer = $objDbConnect->query_fetch_arr($sql);

for ($i = 0; $i < count(@$_POST["exam2_problem_id"]); $i++){
	$answer = $temp_answer;
	$answer["exam2_problem_id"] = $_POST["exam2_problem_id"][$i];
	$temp_check = array();
	for ( $j = 0; $j < count(@$_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]] ); $j++){
		$exam2_problem = trim($_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j])."";
		$exam2_problem = convert_symbole( $exam2_problem );
		$temp_check[] = $exam2_problem;
	}

	$arr_correct_no = array();
	for( $k=0;$k<count($exam2_list['problem']);$k++ ){
		if( $exam2_list['problem'][$k]["exam2_problem_id"]==$_POST["exam2_problem_id"][$i] ){
			if( $exam2_list['problem'][$k]["answer_kind"]=="1" || $exam2_list['problem'][$k]["answer_kind"]=="2" ){
				for( $l=0;$l<count($exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"]);$l++ ){
					if( $exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["correct"] == "1" ){
						$arr_correct_no[] = $exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"];
					}
				}
				sort($temp_check);
				sort($arr_correct_no);
				if( array_equal_set($temp_check,$arr_correct_no) ){
					$answer["exam2_answer_mark"] = '1';
					$answer["exam2_answer_point"] = $exam2_list['problem'][$k]["answer_point"];
				}
			}
		}
	}

	$answer["exam2_answer_contents"] = implode ( ",", $temp_check);

	// 更新
	foreach($ret_exam2_answer as $val){
		if($val['exam2_id']==$answer['exam2_id'] 
		&& $val['exam2_problem_id']==$answer['exam2_problem_id'] 
		&& $val['student_id']==$answer['student_id'] 
		&& $val['product_id']==$pid 
		){
			$exam2_answer_id = $val['exam2_answer_id'];
			$sql  = "";
			$sql .= "UPDATE exam2_answer SET ";
			$sql .= "  exam2_answer_no='".$answer['exam2_answer_no']."', ";
			$sql .= "  exam2_answer_contents='".$answer['exam2_answer_contents']."', ";
			$sql .= "  exam2_answer_date='".$answer['exam2_answer_date']."', ";
			$sql .= "  exam2_answer_mark='".$answer['exam2_answer_mark']."', ";
			$sql .= "  exam2_answer_point='".$answer['exam2_answer_point']."', ";
			$sql .= "  update_at='".$answer['exam2_answer_date']."', ";
			$sql .= "  update_count = (ifnull(update_count,0)+1) ";
			$sql .= " WHERE";
			$sql .= "  exam2_answer_id='".$exam2_answer_id."'";
			$ret = $objDbConnect->execute($sql);
			if (!$ret){
				$err_flg = true;
			} else {
				$exam2_answer_id_list[] = $exam2_answer_id;
			}
			break;
		}
	}
	// 管理者側で更新処理がされていないかチェックする
	$sql  = "";
	$sql .= "SELECT SUM(update_count) AS update_count FROM exam2_answer WHERE status=0 AND student_id='$user_id' AND exam2_id='$e2id' AND product_id='$pid' ";
	$res = $objDbConnect->query_fetch($sql);
	if($res){
		$_SESSION['exam2_answer_update_count'] = $res['update_count'];
	}
}

// 合格フラグの登録
$passing_flg = 0;
if(!empty($exam2_answer_id_list)){
	$exam2_id = $answer['exam2_id'];
	/*
	$sql = "SELECT criteria_type, criteria_value FROM exam2 WHERE exam2_id='".$exam2_id."'";
	$ret_exam2 = $objDbConnect->query_fetch($sql);
	if($ret_exam2){
		// 点数
		if($ret_exam2['criteria_type']=='1'){
			$my_total_answer_point = 0;
			foreach($exam2_answer_id_list as $exam2_answer_id){
				$sql = "SELECT exam2_answer_point FROM exam2_answer WHERE exam2_answer_id='".$exam2_answer_id."'";
				$ret = $objDbConnect->query_fetch($sql);
				if($ret){
					$my_total_answer_point += $ret['exam2_answer_point'];
				}
			}
			
			if($ret_exam2['criteria_value']<=$my_total_answer_point){
				$passing_flg = 1;
			}
			
		// 割合
		} elseif($ret_exam2['criteria_type']=='2'){
			$my_total_answer_point = 0;
			foreach($exam2_answer_id_list as $exam2_answer_id){
				$sql = "SELECT exam2_answer_point FROM exam2_answer WHERE exam2_answer_id='".$exam2_answer_id."'";
				$ret = $objDbConnect->query_fetch($sql);
				if($ret){
					$my_total_answer_point += $ret['exam2_answer_point'];
				}
			}
			
			$sql = "SELECT SUM(answer_point) AS total_answer_point FROM rel_exam2_problem LEFT JOIN exam2_problem ON rel_exam2_problem.exam2_problem_id=exam2_problem.exam2_problem_id WHERE rel_exam2_problem.exam2_id='".$exam2_id."'";
			$ret = $objDbConnect->query_fetch($sql);
			if($ret){
				$check_value = floor($ret_exam2['criteria_value'] / 100 * $ret['total_answer_point']);
				if($check_value<=$my_total_answer_point){
					$passing_flg = 1;
				}
			}
			
		// 正答数
		} elseif($ret_exam2['criteria_type']=='3'){
			$my_total_answer_point = 0;
			foreach($exam2_answer_id_list as $exam2_answer_id){
				$sql = "SELECT exam2_answer_mark FROM exam2_answer WHERE exam2_answer_id='".$exam2_answer_id."'";
				$ret = $objDbConnect->query_fetch($sql);
				if($ret){
					if($ret['exam2_answer_mark']=='1'){
						$my_total_answer_point += 1;
					}
				}
			}
			
			if($ret_exam2['criteria_value']<=$my_total_answer_point){
				$passing_flg = 1;
			}
			
		}
		
		foreach($exam2_answer_id_list as $exam2_answer_id){
			$sql = "UPDATE exam2_answer SET passing_flg='".$passing_flg."', criteria_type='".$ret_exam2['criteria_type']."', criteria_value='".$ret_exam2['criteria_value']."' WHERE exam2_answer_id='".$exam2_answer_id."'";
			$ret = $objDbConnect->execute($sql);
			if (!$ret){
				$err_flg = true;
			}
		}
	}
	*/
	$sql = "UPDATE exam2_answer SET passing_flg='1', criteria_type='1', criteria_value='0' WHERE exam2_answer_id='".$exam2_answer_id."' ";
	$ret = $objDbConnect->execute($sql);
	if (!$ret){
		$err_flg = true;
	}
}

if(!$err_flg){
	$temp_exam2 = array();
	$return_check = true;
	$html_message = "";
	$formhtml_message = "";
	$prev_no = 0;

	if( count(@$_POST["exam2_problem_id"])==0 ){
		$return_check = false;
	} else {
		for ($i = 0; $i < count(@$_POST["exam2_problem_id"]); $i++){
			$html_message .= '<b>設問'.($i+1).'解答</b><br>'."";
			
			//=指定半角文字を全角に変換
			//$formhtml_message.= '<input type="hidden" name="exam2_problem_id[]" value="'.convert_symbole( $_POST["exam2_problem_id"][$i] ).'">'."";
			//$formhtml_message.= '<input type="hidden" name="pid" value="'.$pid.'">'."";
			//$formhtml_message.= '<input type="hidden" name="ccno" value="'.$ccno.'">'."";
			
			$temp_check = array();
			for ( $j = 0; $j < count(@$_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]] ); $j++){
				if( trim($_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j])!="" ){
					$temp_check[] = trim($_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j]);

					for( $k=0;$k<count($exam2_list['problem']);$k++ ){
						if( $exam2_list['problem'][$k]["exam2_problem_id"]==$_POST["exam2_problem_id"][$i] ){
							if( $exam2_list['problem'][$k]["answer_kind"]=="1" || $exam2_list['problem'][$k]["answer_kind"]=="2" ){
								for( $l=0;$l<count($exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"]);$l++ ){
									if( trim($_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j]) == $exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"] ){
										$html_message.= '・'.$exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"].'. '.nl2br(htmlspecialchars( $exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["word"] ))."<br>";
									}
								}
							} else {
								$html_message.= '・'.nl2br(htmlspecialchars( $_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j] ))."<br>";
							}
						}
					}
				}
				//=指定半角文字を全角に変換
				//$formhtml_message.= '<input type="hidden" name="'."exam2_problem_".convert_symbole( $_POST["exam2_problem_id"][$i] ).'[]" value="'.convert_symbole( trim($_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j]) ).'">';
			}
			if( count($temp_check)==0 ){
				$return_check = false;
				$html_message.= '<span style="color: #FF0000;">未解答</span><br>'."";
				if($prev_no==0){
					//=指定半角文字を全角に変換
					$prev_no = convert_symbole( $_POST["exam2_problem_id"][$i] );
				}
			} else {
				
			}
			$html_message.= '<br>'."";
		}
		if( $return_check == false ){
			$html_message = '<span style="color: #FF0000;">未解答の設問があります！</span><br><br>'."".$html_message;
		}
		
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
$template->assign('e2id', $e2id);
$template->assign('return', $return);
$template->assign('err_flg', $err_flg);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_alfstream('exam2/resubmit_exec.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>