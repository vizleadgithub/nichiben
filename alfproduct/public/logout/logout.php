<?php
include(dirname(__FILE__) ."./../../module/define_list.php");

/* SSO old
$iPlanetDirectoryPro = $_COOKIE['iPlanetDirectoryPro'];
//var_dump($iPlanetDirectoryPro);

$api_url = SSO_LOGOUT_API . "?subjectid=".$iPlanetDirectoryPro;
exec('curl -d client_ip='.urlencode ($_SERVER["REMOTE_ADDR"]).' -d client_ua='.urlencode ($_SERVER["HTTP_USER_AGENT"]).' '.$api_url, $output);

unset($_SESSION['user']);
unset($_COOKIE["iPlanetDirectoryPro"]);
*/

$_SESSION = array();
if(isset($_COOKIE["PHPSESSID"])){
	setcookie("PHPSESSID", '', time() - 1800, '/');
}

@session_destroy();

/* SSO old
header("Location: " . SSO_LOGOUT_BACK_URL);
exit();
*/

//$_SESSION['sso_transaction_id'] = sha1(uniqid(mt_rand(), true));
$_SESSION['sso_transaction_id'] = date("YmdHis").mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);


// SSO処理サーバ側のログアウト
$url = NEW_SSO_LOGOUT;
$url.= "?secret=".NEW_SSO_SECRET;
$url.= "&transactionId=".$_SESSION['sso_transaction_id'];
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header("Content-type: text/html; charset=UTF-8");
header('Location: '.$url);
exit;
?>
