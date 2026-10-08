<?php
include(dirname(__FILE__) ."./../../module/module.php");
$id_payment_url = "https://hougakukan.vz-dev.net/alfproducts/payment/id/payment.php";

$err_flg = 0;
$order_id = $_SESSION["order_id"];

$objDbConnect = new DbConnect();
$sql = "select * from tbl_order where order_id='".$order_id."'";
$ret = $objDbConnect->query($sql);
$arrTemp = $objDbConnect->fetch($ret);
if( !$arrTemp ) {
	//print("order err.1");
	exit();
} else {
	$access_id = $arrTemp["access_id"];
	$arr = unserialize($arrTemp["exec_return"], ['allowed_classes' => false]);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<!--<body OnLoad='OnLoadEvent();'>-->
<body>
end<br>
注文番号：<?php print(htmlspecialchars((string)($order_id), ENT_QUOTES, 'UTF-8')); ?><br>
支払い期限日時：<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 0, 4 )), ENT_QUOTES, 'UTF-8')); ?>/<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 4, 2 )), ENT_QUOTES, 'UTF-8')); ?>/<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 6, 2 )), ENT_QUOTES, 'UTF-8')); ?> <?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 8, 2 )), ENT_QUOTES, 'UTF-8')); ?>:<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 10, 2 )), ENT_QUOTES, 'UTF-8')); ?><br>
<!--
<form name="SelectPageCall" action="<?php print(htmlspecialchars((string)($id_payment_url), ENT_QUOTES, 'UTF-8')); ?>" method="POST">
<input type="hidden" name="AccessID" value="<?php print(htmlspecialchars((string)($access_id), ENT_QUOTES, 'UTF-8')); ?>">
iD決済開始画面に遷移します。<br>
<input type="submit" value="続行">
</form>
-->
<a href="<?php print(htmlspecialchars((string)($id_payment_url."?aid=".$access_id), ENT_QUOTES, 'UTF-8')); ?>"><?php print(htmlspecialchars((string)($id_payment_url."?aid=".$access_id), ENT_QUOTES, 'UTF-8')); ?></a><br>
<img src="/alfproducts/qr/qr_img.php?d=<?php print(urlencode($id_payment_url."?aid=".$access_id)); ?>&e=M&s=5&v=6&t=PNG"><br>
iDアプリが利用可能な端末から、QRコードを読み取り、支払いを行ってください。
</body>
</html>
