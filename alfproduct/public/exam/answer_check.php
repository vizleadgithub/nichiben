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

if( !is_numeric($eid) ) $eid = 0;

// 全ての問題の取得
$exam_list = get_exam($eid, $user_id);
$temp_exam = array();
$exam_check = -1;
$return_check = true;
$html_message = "";
$formhtml_message = "";
$prev_no = 0;

if( $exam_list['exam_id'] == $eid ){
	$exam_check = 0;
}
if( count($_POST["exam_problem_id"] ?? [])==0 ){
	$return_check = false;
} else {
	if( $exam_check>=0 ){
		for ($i = 0; $i < count($_POST["exam_problem_id"] ?? []); $i++){
			$html_message .= '設問'.($i+1).'解答<br>'."";
			
			//=指定半角文字を全角に変換
			$formhtml_message.= '<input type="hidden" name="exam_problem_id[]" value="'.convert_symbole( $_POST["exam_problem_id"][$i] ).'">'."";
			$formhtml_message.= '<input type="hidden" name="pid" value="'.$pid.'">'."";
			$formhtml_message.= '<input type="hidden" name="ccno" value="'.$ccno.'">'."";
			
			$temp_check = array();
			if( isset($_POST["exam_problem_".$_POST["exam_problem_id"][$i]]) ){
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
					$formhtml_message.= '<input type="hidden" name="'."exam_problem_".convert_symbole( $_POST["exam_problem_id"][$i] ).'[]" value="'.convert_symbole( trim($_POST["exam_problem_".$_POST["exam_problem_id"][$i]][$j]) ).'">';
				}
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
			$exam_list_q = get_exam($qid, $user_id);
			$no = $i;
			for ($i = 0; $i < count($_POST["exam_problem_id_q"] ?? []); $i++){
				$html_message .= '設問'.($i+1+$no).'解答<br>'."";
				
				//=指定半角文字を全角に変換
				$formhtml_message.= '<input type="hidden" name="exam_problem_id_q[]" value="'.convert_symbole( $_POST["exam_problem_id_q"][$i] ).'">'."";

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
					$formhtml_message.= '<input type="hidden" name="'."exam_problem_q_".convert_symbole( $_POST["exam_problem_id_q"][$i] ).'[]" value="'.convert_symbole( trim($_POST["exam_problem_q_".$_POST["exam_problem_id_q"][$i]][$j]) ).'">';
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
			
			$csrf_next = csrf_token_get();
			$formhtml_message = '<form id="form_regist_answer" name="form_regist_answer" method="post" action="/exam/answer_save.php?eid='.$eid.'&pid='.$pid.'&ccno='.$ccno.'&qid='.$qid.'">'."".'<input type="hidden" name="csrf_token" value="'.htmlspecialchars($csrf_next, ENT_QUOTES, 'UTF-8').'">'.$formhtml_message."".'</form>';
		} else {
			$csrf_next = csrf_token_get();
			$formhtml_message = '<form id="form_regist_answer" name="form_regist_answer" method="post" action="/exam/answer_save.php?eid='.$eid.'&pid='.$pid.'&ccno='.$ccno.'">'."".'<input type="hidden" name="csrf_token" value="'.htmlspecialchars($csrf_next, ENT_QUOTES, 'UTF-8').'">'.$formhtml_message."".'</form>';
		}
		
		$formhtml_message.= '<input type="hidden" name="exam_id" value="'.$eid.'">';
		
	} else {
		$return_check = false;
	}
}

$return = array(
	"return_check"=>$return_check,
	"html"=>$html_message,
	"formhtml"=>$formhtml_message,
	"no_answer"=>$prev_no,
);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('pid', $pid);
$template->assign('ccno', $ccno);
$template->assign('eid', $eid);
$template->assign('qid', $qid);
$template->assign('return', $return);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());

$template->layout_alfstream('exam/answer_check.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>