<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = 'パスポートアラート';
$template = new Template();

// ログインチェック
$st_login_check = st_login_check();

if (!$st_login_check){
	header("Location: /");
	exit;
}

if (isset($_POST['act'])){
	$payment_type = $_SESSION['payment_type'];
	unset($_SESSION['payment_type']);
	
	// 今後アラート表示するかのフラグDB登録
	if (isset($_POST['passport_pop_flg'])){
		if ($_POST['passport_pop_flg'] == '1'){
			$sql ="INSERT INTO student_add SET student_id = '".mysql_escape_string($_SESSION['user']['id'])."', passport_pop_flg = '".mysql_escape_string($_POST['passport_pop_flg'])."'";
			$objDbConnect = new DbConnect();
			$res = $objDbConnect->execute($sql);
			if ($res){
				$_SESSION['user']['passport_pop_flg'] = 1;
			}
		}
	}
	
	// パスポート案内
	if ($_POST['act'] == 'passport'){
		$_SESSION['alert_click']['buy'] = 0;
		$_SESSION['alert_click']['passport'] = 1;
		header("Location: /product/list_passport.php");
		exit;
		
	// このまま購入
	} else if ($_POST['act'] == 'buy'){
		$_SESSION['alert_click']['buy'] = 1;
		$_SESSION['alert_click']['passport'] = 0;
		header("Location: /settlement?ptype=".$payment_type);
		exit;
		
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('passport_pop_flg', $_SESSION['user']['passport_pop_flg']);
$template->layout_noside('settlement/alert_passport.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
exit();
?>
