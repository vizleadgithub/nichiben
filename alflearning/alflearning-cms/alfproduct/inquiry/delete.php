<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$iid = "";
$iid = $_GET["iid"];
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Update
$sql = "";
$sql.= "update tbl_inquiry set ";
$sql.= "del_flg='1' ";
$sql.= " where inquiry_id='".mysql_escape_string($iid)."' ";
$ret = $objDbConnect->execute($sql);
if(!$ret){
	$arr_err["db"] = "更新に失敗しました。";
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
header("Location: index.php?page=".$_SESSION["inquiry.page"]."");
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
