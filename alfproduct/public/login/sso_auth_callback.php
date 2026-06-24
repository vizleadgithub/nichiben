<?php
require_once '/srv/alfproduct/module/session_start.php';
require_once '/srv/alfproduct/module/define_list.php';
require_once '/srv/alfproduct/module/sso_logger.php';
require_once '/srv/alfproduct/public/custom_pages/cp-includes/class-json.php';

error_reporting(E_ALL);
ini_set("display_errors", 0);

$burl = "/";
if( isset($_SESSION["burl"]) && $_SESSION["burl"]!="" ){
	$burl = $_SESSION["burl"];
	$_SESSION['burl'] = "";
}

sso_log('callback_in', array(
	'has_session_txn' => isset($_SESSION['sso_transaction_id']) ? 1 : 0,
	'has_request_txn' => isset($_GET['transactionId']) ? 1 : 0,
	'has_id_token'    => isset($_GET['IDtoken']) ? 1 : 0,
));

// 必要な情報が揃っているかチェック
if (isset($_SESSION['sso_transaction_id']) && isset($_GET["transactionId"]) && isset($_GET["IDtoken"])){

	$token_id = $_GET["IDtoken"];
	// 認証時のtransactionIdかどうかチェック
	if($_SESSION['sso_transaction_id'] == $_GET["transactionId"]){
		// callback→login_sso_newを同一トレースIDで追えるよう、unset前に退避する
		$_SESSION['sso_log_trace_id'] = $_SESSION['sso_transaction_id'];
		// リロード等で再実行できないようsso_transaction_idは消しておく
		unset($_SESSION['sso_transaction_id']);

		$postdata = array(
			'token_id' => $token_id,
			'user_agent' => $_SERVER['HTTP_USER_AGENT']
		);

		$content = http_build_query($postdata);

		sso_log('callback_api_request', array(
			'endpoint'  => NEW_SSO_API_UPDATE_PROFILE,
			'token_len' => strlen($token_id),
		));

		$options = array('http' => array(
			'method' => 'POST',
			'header' =>
				"Content-Type: application/x-www-form-urlencoded\r\n".
				"Content-Length: ".strlen($content)."\r\n",
				'content' => $content,
		));
		$res = @file_get_contents(NEW_SSO_API_UPDATE_PROFILE, false, stream_context_create($options));
		$res_decode = json_decode($res, false);

		$api_status  = (isset($res_decode->result->status)) ? $res_decode->result->status : null;
		$result_keys = (isset($res_decode->result) && is_object($res_decode->result))
			? array_keys(get_object_vars($res_decode->result))
			: array();
		$lawyer_number_raw = (isset($res_decode->result->lawyer_number)) ? $res_decode->result->lawyer_number : '';
		sso_log('callback_api_response', array(
			'api_reachable'   => ($res !== false) ? 1 : 0,
			'response_len'    => is_string($res) ? strlen($res) : 0,
			'json_decodable'  => ($res_decode !== null) ? 1 : 0,
			'has_result'      => (isset($res_decode->result)) ? 1 : 0,
			'result_keys'     => implode(',', $result_keys),
			'status'          => ($api_status === null) ? 'null' : $api_status,
			'has_lawyer_no'   => ($lawyer_number_raw !== '' && $lawyer_number_raw !== null) ? 1 : 0,
			'lawyer_no'       => ($lawyer_number_raw === null) ? '' : $lawyer_number_raw,
		));

		if ($api_status === 'OK'){
			// 結果をセッションにセット
			$_SESSION['user']['login_result'] = $res_decode->result;
			// token_idをセッションにセット
			$_SESSION['user']['token_id'] = $token_id;
			sso_log('callback_session_set', array(
				'has_lawyer_no' => ($lawyer_number_raw !== '' && $lawyer_number_raw !== null) ? 1 : 0,
			));
			// 研修サイトへのログイン処理を実行
			header("Location: /login/login_sso_new.php?burl=".urlencode($burl) );
			exit;
		} else {
			sso_log('callback_unauthorized', array(
				'status' => ($api_status === null) ? 'null' : $api_status,
			));
			//http_response_code(403);
			header('HTTP/1.1 403 Unauthorized');
			print("権限のないユーザです。");
			print('<br><br><a href="https://member.nichibenren.or.jp">会員サイトのTOPページに戻る</a>');
			exit();
		}
	} else {
		sso_log('callback_txn_mismatch');
	}
}

//echo 'Bad Request';
//header('HTTP', true, 400);

// transactionIdをセットしておいてコールバック時のチェックに使用
//$_SESSION['sso_transaction_id'] = sha1(uniqid(mt_rand(), true));
$_SESSION['sso_transaction_id'] = date("YmdHis").mt_rand(0, 9).mt_rand(0, 9).mt_rand(0, 9);
//header("Location: /logout/logout.php");
//exit;

sso_log('callback_auth_redirect');

// 認証
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header("Content-type: text/html; charset=UTF-8");
$url = NEW_SSO_AUTH;
$url.= "?secret=".NEW_SSO_SECRET;
$url.= "&transactionId=".$_SESSION['sso_transaction_id'];
$url.= "&callback=".urlencode(NEW_SSO_AUTH_CALLBACK_URL);
$url.= "&sessionTime=".NEW_SSO_IDTOKEN_SESSION_TIME;
header("Location: ".$url);
//print('<a href="'.$url.'">'.$url.'</a>');
exit;
?>
