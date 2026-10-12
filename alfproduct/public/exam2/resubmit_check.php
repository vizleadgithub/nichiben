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
normalize_exam_post();
csrf_token_verify();
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

if( !is_numeric($e2id) ) $e2id = 0;
// 全ての問題の取得
$exam2_list = get_exam2($e2id, $user_id);
$temp_exam2 = array();
$exam2_check = -1;
$return_check = true;
$html_message = "";
$formhtml_message = "";
$prev_no = 0;

$formhtml_message.= '<input type="hidden" name="exam2_id" value="'.$e2id.'">';
$formhtml_message.= '<input type="hidden" name="pid" value="'.$pid.'">'."";
if( $exam2_list['exam2_id'] == $e2id ){
	$exam2_check = 0;
}
// exam2_problem_id は、数字の配列だけを受け付ける（改ざんされた値・配列以外の値は、除く）
$post_problem_ids = array();
if (isset($_POST["exam2_problem_id"]) && is_array($_POST["exam2_problem_id"])) {
	foreach ($_POST["exam2_problem_id"] as $post_problem_id) {
		if (is_string($post_problem_id) && ctype_digit($post_problem_id)) {
			$post_problem_ids[] = $post_problem_id;
		}
	}
}
if( count($post_problem_ids)==0 ){
	$return_check = false;
} else {
	if( $exam2_check>=0 ){
		for ($i = 0; $i < count($post_problem_ids); $i++){
			$html_message .= '<b>設問'.($i+1).'解答</b><br>'."";
			
			//=指定半角文字を全角に変換
			$formhtml_message.= '<input type="hidden" name="exam2_problem_id[]" value="'.convert_symbole( $post_problem_ids[$i] ).'">'."";
			
			$temp_check = array();
			$post_answers = array();
			if (isset($_POST["exam2_problem_".$post_problem_ids[$i]]) && is_array($_POST["exam2_problem_".$post_problem_ids[$i]])) {
				$post_answers = array_values(array_filter($_POST["exam2_problem_".$post_problem_ids[$i]], 'is_string'));
			}
			for ( $j = 0; $j < count($post_answers); $j++){
				if( trim($post_answers[$j])!="" ){
					$temp_check[] = trim($post_answers[$j]);

					for( $k=0;$k<count($exam2_list['problem']);$k++ ){
						if( $exam2_list['problem'][$k]["exam2_problem_id"]==$post_problem_ids[$i] ){
							if( $exam2_list['problem'][$k]["answer_kind"]=="1" || $exam2_list['problem'][$k]["answer_kind"]=="2" ){
								for( $l=0;$l<count($exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"]);$l++ ){
									if( trim($post_answers[$j]) == $exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"] ){
										$html_message.= '・'.htmlspecialchars((string)$exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["no"], ENT_QUOTES, 'UTF-8').'. '.nl2br(htmlspecialchars( (string)$exam2_list['problem'][$k]["answer_contents_select"]["answer_contents"][$l]["word"], ENT_QUOTES, 'UTF-8'))."<br>";
									}
								}
							} else {
								$html_message.= '・'.nl2br(htmlspecialchars( (string)$post_answers[$j], ENT_QUOTES, 'UTF-8'))."<br>";
							}
						}
					}
				}
				//=指定半角文字を全角に変換
				$formhtml_message.= '<input type="hidden" name="'."exam2_problem_".convert_symbole( $post_problem_ids[$i] ).'[]" value="'.convert_symbole( trim($post_answers[$j]) ).'">';
			}
			if( count($temp_check)==0 ){
				$return_check = false;
				$html_message.= '<span style="color: #FF0000;">未解答</span><br>'."";
				if($prev_no==0){
					//=指定半角文字を全角に変換
					$prev_no = convert_symbole( $post_problem_ids[$i] );
				}
			} else {
				
			}
			$html_message.= '<br>'."";
		}
		if( $return_check == false ){
			$html_message = '<span style="color: #FF0000;">未解答の設問があります！</span><br><br>'."".$html_message;
		}
		
		$csrf_next = csrf_token_get();
		$formhtml_message = '<form id="form_regist_answer" name="form_regist_answer" method="post">'."".
			'<input type="hidden" name="csrf_token" value="'.htmlspecialchars($csrf_next, ENT_QUOTES, 'UTF-8').'">'.$formhtml_message."".'</form>';
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
$template->assign('e2id', $e2id);
$template->assign('return', $return);

// Android、iPad、iPhoneのFLG
$template->assign('is_sp', is_sp());
$template->layout_pop('exam2/resubmit_check.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();
?>