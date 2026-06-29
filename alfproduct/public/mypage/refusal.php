<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '退会手続き';

$template = new Template();
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 会員データ登録時以外にマスタデータを取得
if($_POST['action'] == 'complete'){
	csrf_token_verify();
	if (!st_login_check()){
		header("Location: /");
		exit;
	} else {
		$user_id="";
		if( is_numeric($_SESSION["user"]["id"]) && $_SESSION["user"]["id"]!="" ){
			$user_id = trim($_SESSION["user"]["id"]);

			$sql = "";
			$sql.= "update student set ";
			$sql.= " status='9' ";
			$sql.= "WHERE ";
			$sql.= " student_id='".$user_id."' ";
			$ret = $objDbConnect->execute($sql);
			$_SESSION = array();
		}
	}
	$template->layout_noside('mypage/refusal_complete.tpl');
} elseif($_POST['action'] == 'confirm'){
	if (!st_login_check()){
		header("Location: /");
		exit;
	} else {
		$user_id="";
		if( is_numeric($_SESSION["user"]["id"]) && $_SESSION["user"]["id"]!="" ){
			$user_id = trim($_SESSION["user"]["id"]);
		}
	}

	$template->assign('csrf_token', csrf_token_get());
	$template->layout_noside('mypage/refusal_confirm.tpl');
} else {
	if (!st_login_check()){
		header("Location: /");
		exit;
	} else {
		$user_id="";
		if( is_numeric($_SESSION["user"]["id"]) && $_SESSION["user"]["id"]!="" ){
			$user_id = trim($_SESSION["user"]["id"]);
		}
	}

	$template->assign('csrf_token', csrf_token_get());
	$template->layout_noside('mypage/refusal.tpl');
}
$objDbConnect->close();
exit();
?>
