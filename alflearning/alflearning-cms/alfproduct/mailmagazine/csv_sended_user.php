<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mid = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$mid = $_POST["mid"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_mailmagazine_category";
$arr_mailmagazine_category = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_pref";
$arr_pref = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$member_count = 0;
if(count($arr_err)==0){
	$sql = "select tbl_mailmagazine_sended.*,student.* from tbl_mailmagazine_sended LEFT JOIN student ON tbl_mailmagazine_sended.member_id=student.student_id where mailmagazine_id='".$mid."' ";
	$ret = $objDbConnect->query_fetch_arr($sql.$where);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($ret);
//exit();
// CSVヘッダ
header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");
header("Content-Disposition: attachment; filename=send_user_".date("YmdHis").".csv");

echo mb_convert_encoding("生徒ID,生徒名,メールアドレス\r\n", "SJIS", "UTF-8");
for($i=0;$i<count($ret);$i++){
	echo mb_convert_encoding('"' . $ret[$i]["member_id"] . '","' . $ret[$i]["member_name"] . '","' . $ret[$i]["member_mail"] . '",' ."\r\n", "SJIS", "UTF-8");
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
