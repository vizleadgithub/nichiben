<?php
include(dirname(__FILE__) ."./../../module/module.php");

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
<form action="https://pt01.mul-pay.jp/payment/PaypalStart.idPass" method="POST">
<input type="hidden" name="ShopID" value="<?php echo GMOPaymentProtocol::SHOP_ID; ?>" />
<input type="hidden" name="AccessID" value="<?php echo $access_id; ?>" />
PayPalの決済画面へ遷移します。<br>
<input type="submit" value="続行">
</form>
</body>
</html>
