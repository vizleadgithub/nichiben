<?php
$_SERVER['HTTPS'] = 'on';
//ini_set('display_errors', 1);
//error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_WARNING);
// セッション Cookie のセキュリティ強化
ini_set("session.cookie_httponly", 1);  // JavaScript からのアクセスを防止
ini_set("session.cookie_secure", 1);    // HTTPS 接続時のみ Cookie を送信
ini_set("session.cookie_samesite", "Lax");  // SameSite=Lax に設定
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

/*
$ret = false;
$arr_accept = array();
$arr_accept[] = "121.119.246.193";
$arr_accept[] = "202.19.227.118";
$arr_accept[] = "202.19.227.119";
$arr_accept[] = "123.226.229.81";
$arr_accept[] = "123.226.229.253";
$arr_accept[] = "123.226.231.47";
$arr_accept[] = "180.22.112.19";
for( $i=0;$i<count($arr_accept);$i++ ){
	$accept = $arr_accept[$i];
	$remote_ip = $_SERVER['REMOTE_ADDR'];
	if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ){
		$remote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	if(strpos($accept,'/') !== false){
		$accept_ip = "";
		$mask = "";
		list($accept_ip, $mask) = explode('/', $accept);
		$accept_long = ip2long($accept_ip) >> (32 - $mask);
		$remote_long = ip2long($remote_ip) >> (32 - $mask);
		if ($accept_long == $remote_long) {
			$ret = true;
		}
	} else {
		if ($accept == $remote_ip) {
			$ret = true;
		}
	}
}
*/

?>
<?php
//if( $ret ){
	define('WP_USE_THEMES', true);
	/** Loads the WordPress Environment and Template */
	require('./custom_pages/cp-blog-header.php');
//} else {
/*  ?>
<!DOCTYPE html>
<html dir="ltr" lang="ja">
<head>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<meta http-equiv="Pragma" content="no-cache">
<meta name="description" content="日本弁護士連合会　総合研修サイト" />
<meta name="keywords" content="日本弁護士連合会　総合研修サイト" />
<meta charset="UTF-8" />
<title>日本弁護士連合 | 総合研修サイト</title>
<!--<link rel="profile" href="http://gmpg.org/xfn/11" />-->
<link rel="stylesheet" type="text/css" media="all" href="https://kenshu.nichibenren.or.jp/wordpress/wp-content/themes/twentyten/style.css" />
<link rel="pingback" href="/wordpress/xmlrpc.php" />
<meta name='robots' content='noindex,nofollow' />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://kenshu.nichibenren.or.jp/wordpress/wp-includes/wlwmanifest.xml" /> 
<link rel='index' title='日本弁護士連合' href='https://kenshu.nichibenren.or.jp' />
<meta name="generator" content="WordPress 3.1.4" />
<!-- Start WOWSlider.com HEAD section -->
<link rel="stylesheet" type="text/css" href="/engine1/style.css" />
<script type="text/javascript" src="/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/jquery/jquery.smoothScroll.js"></script>
<script type="text/javascript" src="/js/smartRollover.js"></script>

<link href="/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker-ja.js"></script>
<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/js/jquery.ui.resizable.js"></script>
<!-- End WOWSlider.com HEAD section -->

        <link href="/js/src/perfect-scrollbar.css " rel="stylesheet">
        <script src="/js/src/jquery.mousewheel.js"></script>
        <script src="/js/src/perfect-scrollbar.js"></script>

	<link href="/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="/facebox/facebox.js" type="text/javascript"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox() 
	})
	</script>

	<link rel="stylesheet" href="/popupwindow/css/popupwindow.css" type="text/css" media="all" />
	<script type="text/javascript" src="/popupwindow/popupwindow-1.8.1.js"></script>

	<style type="text/css">
	div#header div#head_top_area{

	}
	div#header ul#head_menu{
	list-style:none;
	}
	div#header ul#head_menu li{
	float:left;
	text-align:center;
	}

	</style>

	<!--[if IE]>
	<style type="text/css">
	div#header ul#head_menu{
	list-style:none;
	}
	div#header ul#head_menu li{
	float:left;
	text-align:center;
	}

	.lc_st ol{
		margin:0px;
		margin-top:-18px;
		padding:0px;
	}
	</style>
	<![endif]-->


</head>

<body class="home blog" style="height:100%;">


	<a id="top" name="top"></a>
	<div id="wrapper" class="hfeed">
		<div id="headbd">
			<div id="header">
				<div id="head_top_area">
					<div style="float:left;padding:16px 0px 20px 11px ;"><a href="/"><img src="/img/logo.png" alt="On-Tap" /></a></div>
				</div>
				<div class="subnavi">
				</div>
			</div><!-- #header -->
		<br style="clear:both;"/>
	</div>


	<div id="main" style="height:200px;text-align: center;">

		<div>
		メンテナンス中のためアクセスすることができません。<br>
		御迷惑をお掛けいたしますが，メンテナンス終了までお待ちいただきますようお願いいたします。<br>
		<br>
		<停止期間><br>
		2024年06月18日（火）11:00～2024年06月18日（火）18:00頃<br>
		</div>

	</div><!-- #main -->


<style type="text/css">
div#footer ul#foot_menu{
list-style:none;
margin-top:20px;
}
div#footer ul#foot_menu li{
float:left;
width:240px;
	line-height:180%;
}
div#footer ul#foot_menu li a{
margin-left:10px;
}
div#footer div#copyright{
clear:both;
text-align:center;
padding-top:10px;
color:#ffffff;
}
</style>

	<div id="footer" role="contentinfo" style="height:auto;">
		<div id="colophon">
			<div id="copyright" style="color:#aaaaaa;">
				Copyright (C) Japan Federation of Bar Associations all rights reserved.
			</div>
		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->


</body>
</html>
<?php
}
*/  ?>