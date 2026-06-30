<?php
include(dirname(__FILE__) ."./../../module/module.php");
$template = new Template();
$objDbConnect = new DbConnect();

$err_msg = array();
$zip = '';
$zip1 = '';
$zip2 = '';

$zip = mb_convert_kana($_GET["zip1"].$_GET["zip2"] ,"n");
$zip1 = $_GET["zip1"];
$zip2 = $_GET["zip2"];
$input1 = $_GET["input1"];
$input2 = $_GET["input2"];
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
if(cmCheckInput($zip, 'CK_KARA')!=0){
	$err_msg['zip'] = '郵便番号が不正です。';
} else {
	if(cmCheckInput($zip, 'CK_NUM')!=0){
		$err_msg[] = '郵便番号が不正です。';
	} elseif(strlen($zip)!=7){
		$err_msg[] = '郵便番号が不正です。';
	}
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
$id = "";
$state = "";
$city = "";
$town = "";

$sql = "";
$sql.= "SELECT ";
$sql.= "mtb_zip.state, ";
$sql.= "mtb_zip.city, ";
$sql.= "mtb_zip.town, ";
$sql.= "mtb_pref.id ";
$sql.= "FROM ";
$sql.= "mtb_zip ";
$sql.= "LEFT JOIN mtb_pref ON mtb_zip.state=mtb_pref.name ";
$sql.= "WHERE mtb_zip.zipcode='".$zip."' LIMIT 1 ";
$ret = $objDbConnect->query_fetch_arr($sql);
if( count($ret)>0 ){
	$id    = $ret[0]["id"];
	$state = $ret[0]["state"];
	$city  = $ret[0]["city"];
	$town  = $ret[0]["town"];
	//$town = ereg_replace("（.*）$","",$town);
	//$town = ereg_replace("以下に掲載がない場合","",$town);
	$town = preg_replace("/（.*）$/u", "", $town);
	$town = preg_replace("/以下に掲載がない場合/u", "", $town);
} else {
	$err_msg[] = '郵便番号に該当する住所がありません。';
}

$objDbConnect->close();

//+++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('err_msg', $err_msg);
$template->assign('id', $id);
$template->assign('state', $state);
$template->assign('city', $city);
$template->assign('town', $town);
$template->assign('input1', $input1);
$template->assign('input2', $input2);
$template->assign('csrf_token', csrf_token_get());
if( count($err_msg)>0 ){
	$template->display('member/zip_err.tpl');
} else {
	$template->display('member/zip.tpl');
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++
exit();
?>
