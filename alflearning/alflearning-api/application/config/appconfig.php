<?php
//==============================================
// require global config
//==============================================
require(dirname(__FILE__).'/../../../alflearning-global-config.php');

//==============================================
// config
//==============================================
/*
| -------------------------------------------------------------------
|  File Upload Config item
| -------------------------------------------------------------------
*/
//----------------------------------------------
//admin設定
//----------------------------------------------
//$config['admin_email']    = 'admin@alflearning.com';
//$config['admin_password'] = 'Zg91bkEy';		//現状平文で登録
if( getenv('URL_SERVICE')== 'alfsales' ){
	$config['admin_email']    = 'admin@alfsales.net';
	$config['admin_password'] = 'Xm4Y5UMW';		//現状平文で登録
}else{
	$config['admin_email']    = 'admin@alflearning.com';
	$config['admin_password'] = 'Zg91bkEy';		//現状平文で登録
}

//----------------------------------------------
//イメージ設定
//----------------------------------------------
$config['images_dir'] = 'static/image/';
$config['logo_small'] = 'logo_On-Tap.png';

$config['images_dir_conference'] = 'static_conference/image/';
$config['logo_small_alfsales'] = 'logo_alfsales.png';

//----------------------------------------------
//ページネーション設定
//----------------------------------------------
$config['pagination_per_page'] = 20;	//1ページの件数

/* End of file appcongif.php */
