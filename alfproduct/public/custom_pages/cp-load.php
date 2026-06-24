<?php
$meta_keywords = '弁護士や日本弁護士連合会についての解説。その活動についての紹介。法律相談窓口 について等.';
$meta_description = '';

require_once '/srv/alfproduct/module/module.php';
require_once '/srv/alfproduct/public/custom_pages/cp-includes/class-json.php';

/**
 * 2030年の記事の公開
 */
$objDbConnect = new DbConnect();
$sql = "UPDATE wp_posts SET post_status='publish' WHERE post_status='future' AND DATE_FORMAT(post_date, '%Y')=2030 ";
$ret = $objDbConnect->execute($sql);
$sql = "UPDATE wp_posts SET post_status='future' WHERE post_status='publish' AND DATE_FORMAT(post_date, '%Y')<>2030 AND post_date>'".date("Y-m-d H:i:s")."' ";
$ret = $objDbConnect->execute($sql);

/**
 * シングルサインオン
 */
if( strpos($_SERVER['HTTP_HOST'],'kenshu-cms.nichibenren.or.jp')===false && strpos($_SERVER['HTTP_HOST'],'cms.nichibenren-stg.alfcloud.com')===false && strpos($_SERVER['HTTP_HOST'],'cms.nichibenren-stg2.alfcloud.com')===false ){
	if (!st_login_check()){
		// SSO処理サーバで未認証の場合
		if (!isset($_SESSION["sso_logged_in"])){

			$_SESSION['burl'] = $_SERVER['REQUEST_URI'];

			// transactionIdをセットしておいてコールバック時のチェックに使用
			$_SESSION['sso_transaction_id'] = create_sso_transaction_id();

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
			//header("Location: ".$url);
$html = <<< "SSO_HTML"
<html>
<body>
<script>
location.href = "$url";
</script>
</body>
</html>
SSO_HTML;
print($html);
exit();
		}
	}
}


// SSO old
/*
if (!st_login_check()){
	if (isset($_COOKIE["iPlanetDirectoryPro"])){
		$postdata = array(
			'token_id' => $_COOKIE["iPlanetDirectoryPro"]
		);
		
		$content= http_build_query($postdata);
		
		$options = array('http' => array(
			'method' => 'POST',
			'header' =>
				"Content-Type: application/x-www-form-urlencoded\r\n".
				"Content-Length: ".strlen($content)."\r\n",
				'content' => $content,
		));
		
		$res = file_get_contents(SSO_API, false, stream_context_create($options));
		
		$objJSON = new Services_JSON();
		$res_decode = $objJSON->decode($res);
		
		if ($res_decode->result->status === 'OK'){
			// 結果をセッションにセット
			$_SESSION['user']['login_result'] = $res_decode->result;
			// token_idをセッションにセット
			$_SESSION['user']['token_id'] = $_COOKIE["iPlanetDirectoryPro"];
			// token_idのクッキーを削除
			//unset($_COOKIE["iPlanetDirectoryPro"]);
                        setcookie('iPlanetDirectoryPro', "", time() - 3600);
			
			header("Location: /login/login_sso.php?burl=".$_SERVER['REQUEST_URI']);
			exit;
			
		} else {
			// token_idのクッキーを削除
			//unset($_COOKIE["iPlanetDirectoryPro"]);
                        setcookie('iPlanetDirectoryPro', "", time() - 3600);
			//echo 'Login Error.';
			header("Location: https://www.nichibenren-member-sso.jp/openam");
			exit;
		}
		
	} else {
		echo 'Bad Request';
		header('HTTP', true, 400);
		exit;
	}
}
*/

/** Define ABSPATH as this files directory */
define( 'ABSPATH', dirname(__FILE__) . '/' );
//print("<!--[".ABSPATH."]-->");
if ( defined('E_RECOVERABLE_ERROR') )
	//error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
else
	//error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
if ( file_exists( ABSPATH . 'cp-config.php') ) {
	require_once( ABSPATH . 'cp-config.php' );
} elseif ( file_exists( dirname(ABSPATH) . '/cp-config.php' ) && ! file_exists( dirname(ABSPATH) . '/cp-settings.php' ) ) {

	require_once( dirname(ABSPATH) . '/cp-config.php' );

} else {
	require_once( ABSPATH . '/cp-includes/class-wp-error.php' );
	require_once( ABSPATH . '/cp-includes/functions.php' );
	require_once( ABSPATH . '/cp-includes/plugin.php' );
	$text_direction = 'ltr';
}
?>
