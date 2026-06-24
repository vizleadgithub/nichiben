<?php
//+++++++++++++++++++++++++++++++++++++++++++
mb_language('Japanese');
date_default_timezone_set('Asia/Tokyo');
/*
ini_set('mbstring.detect_order', 'auto');
ini_set('mbstring.http_input'  , 'pass');
ini_set('mbstring.http_output' , 'pass');
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.script_encoding'  , 'UTF-8');
ini_set('mbstring.substitute_character', 'none');
*/
mb_regex_encoding('UTF-8');
//+++++++++++++++++++++++++++++++++++++++++++
/*
// magic_quotes_gpc = On の場合の対策
if(!function_exists('strip_magic_quotes_slashes')) {
	function strip_magic_quotes_slashes($arr) {
		return is_array($arr) ?
		array_map('strip_magic_quotes_slashes', $arr) :
		stripslashes($arr);
	}
	if (get_magic_quotes_gpc()) {

		$_GET     = strip_magic_quotes_slashes($_GET);
		$_POST    = strip_magic_quotes_slashes($_POST);
		$_REQUEST = strip_magic_quotes_slashes($_REQUEST);
		$_COOKIE  = strip_magic_quotes_slashes($_COOKIE);
	}
}
$TEMP_GET = $_GET;
$TEMP_POST = $_POST;
$TEMP_REQUEST = $_REQUEST;
$TEMP_COOKIE = $_COOKIE;
*/
//+++++++++++++++++++++++++++++++++++++++++++
require_once("DbConnect.php");
require_once("define_list.php");
require_once("functions.php");
require_once("value_check.php");
//+++++++++++++++++++++++++++++++++++++++++++
/*
$_GET = $TEMP_GET;
$_POST = $TEMP_POST;
$_REQUEST = $TEMP_REQUEST;
$_COOKIE = $TEMP_COOKIE;
*/
//+++++++++++++++++++++++++++++++++++++++++++
//date_default_timezone_set('Asia/Tokyo');
//+++++++++++++++++++++++++++++++++++++++++++
?>