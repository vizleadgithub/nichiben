<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mid = "";
$mid = $_GET["mid"];
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Update
$sql = "";
$sql.= "update tbl_mailmagazine set ";
$sql.= "del_flg='".mysql_escape_string($mid)."' ";
$sql.= " where mailmagazine_id='".mysql_escape_string($mid)."' ";
$ret = $objDbConnect->execute($sql);
if(!$ret){
	$arr_err["db"] = "更新に失敗しました。";
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
header("Location: index.php?page=".$_SESSION["mailmagazine.page"]."");
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
