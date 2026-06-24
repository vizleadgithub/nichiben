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
if( count(@$_POST["exam2_problem_id"])==0 ){
	$return_check = false;
} else {
	if( $exam2_check>=0 ){
		for ($i = 0; $i < count(@$_POST["exam2_problem_id"]); $i++){
			$html_message .= '<b>設問'.($i+1).'解答</b><br>'."";
			
			//=指定半角文字を全角に変換
			$formhtml_message.= '<input type="hidden" name="exam2_problem_id[]" value="'.convert_symbole( $_POST["exam2_problem_id"][$i] ).'">'."";
			
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
				$formhtml_message.= '<input type="hidden" name="'."exam2_problem_".convert_symbole( $_POST["exam2_problem_id"][$i] ).'[]" value="'.convert_symbole( trim($_POST["exam2_problem_".$_POST["exam2_problem_id"][$i]][$j]) ).'">';
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
		
		$formhtml_message = '<form id="form_regist_answer" name="form_regist_answer" method="post">'."".$formhtml_message."".'</form>';
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