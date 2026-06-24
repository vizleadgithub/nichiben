<?php
include(dirname(__FILE__) ."./../../module/module.php");
$au_payment_url = "https://hougakukan.vz-dev.net/alfproducts/payment/au/payment.php";

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
支払い期限日時：<?php print(substr( $arr["StartLimitDate"], 0, 4 )); ?>/<?php print(substr( $arr["StartLimitDate"], 4, 2 )); ?>/<?php print(substr( $arr["StartLimitDate"], 6, 2 )); ?> <?php print(substr( $arr["StartLimitDate"], 8, 2 )); ?>:<?php print(substr( $arr["StartLimitDate"], 10, 2 )); ?><br>
<!--
<form name="AuStartCall" action="<?php print( $arr["StartURL"] ); ?>" method="POST">
<input type="hidden" name="AccessID" value="<?php print( $arr["AccessID"] ); ?>">
<input type="hidden" name="Token" value="<?php print( str_replace(' ','+',$arr["Token"]) ); ?>">
auかんたん決済の決済画面へ遷移します。<br>
<input type="submit" value="続行">
-->
<a href="<?php print($au_payment_url."?aid=".$access_id); ?>"><?php print($au_payment_url."?aid=".$access_id); ?></a><br>
<img src="/alfproducts/qr/qr_img.php?d=<?php print(urlencode($au_payment_url."?aid=".$access_id)); ?>&e=M&s=5&v=6&t=PNG"><br>
au端末から、QRコードを読み取り、支払いを行ってください。
</form>
</body>
</html>
