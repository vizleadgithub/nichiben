<?php
include(dirname(__FILE__) ."./../../module/module.php");
$webmoney_payment_url = "https://pt01.mul-pay.jp/payment/WebmoneyStart.idPass";

$err_flg = 0;
$order_id = $_SESSION["order_id"];

$objDbConnect = new DbConnect();
$sql = "select * from tbl_order where order_id='".$order_id."'";
$ret = $objDbConnect->query($sql);
$arrTemp = $objDbConnect->fetch($ret);
if( !$arrTemp ) {
	print("order err.1");
	exit();
} else {
	$access_id = $arrTemp["access_id"];
	$arr = unserialize($arrTemp["exec_return"]);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<!--<body OnLoad='OnLoadEvent();'>-->
<body>
end<br>
注文番号：<?php print($order_id); ?><br>
支払い期限日時：<?php print(substr( $arr["PaymentTerm"], 0, 4 )); ?>/<?php print(substr( $arr["PaymentTerm"], 4, 2 )); ?>/<?php print(substr( $arr["PaymentTerm"], 6, 2 )); ?> <?php print(substr( $arr["PaymentTerm"], 8, 2 )); ?>:<?php print(substr( $arr["PaymentTerm"], 10, 2 )); ?><br>
<form name="WebmoneyStartCall" action="<?php print( $webmoney_payment_url ); ?>" method="POST">
<input type="hidden" name="AccessID" value="<?php print( $access_id ); ?>">
WebMoneyの決済画面へ遷移します。<br>
<input type="submit" value="続行">
<!--
<img src="/qr/qr_img.php?d=<?php print(urlencode($webmoney_payment_url."?aid=".$access_id)); ?>&e=M&s=5&v=6&t=PNG"><br>
docomo端末から、QRコードを読み取り、支払いを行ってください。
-->
</form>
</body>
</html>
