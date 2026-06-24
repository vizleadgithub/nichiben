<?php
function sitepageLoad(){
	print("test");

	$meta_keywords = '弁護士や日本弁護士連合会についての解説。その活動についての紹介。法律相談窓口 について等.';
	$meta_description = '';

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


}
