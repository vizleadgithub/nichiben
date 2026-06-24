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
	$arr = unserialize($arrTemp["exec_return"]);
}
?>

注文番号：<?php print($arr["OrderID"]); ?><br>
お客様番号：<?php print($arr["CustID"]); ?><br>
収納機関番号：<?php print($arr["BkCode"]); ?><br>
確認番号：<?php print($arr["ConfNo"]); ?><br>
支払い期限日時：<?php print(substr( $arr["PaymentTerm"], 0, 4 )); ?>/<?php print(substr( $arr["PaymentTerm"], 4, 2 )); ?>/<?php print(substr( $arr["PaymentTerm"], 6, 2 )); ?> <?php print(substr( $arr["PaymentTerm"], 8, 2 )); ?>:<?php print(substr( $arr["PaymentTerm"], 10, 2 )); ?><br>
受付日時：<?php print(substr( $arr["TranDate"], 0, 4 )); ?>/<?php print(substr( $arr["TranDate"], 4, 2 )); ?>/<?php print(substr( $arr["TranDate"], 6, 2 )); ?> <?php print(substr( $arr["TranDate"], 8, 2 )); ?>:<?php print(substr( $arr["TranDate"], 10, 2 )); ?><br>

<br />
<br />
<br />
<br />
<br />

ネットバンキングでお支払いのかたはこちら。<br />
金融機関選択画面に遷移します。<br />
ボタンをクリックしてください。<br />
<form action="#" method="post">
<input type="hidden" name="code" value="<?php echo $arr['EncryptReceiptNo']; ?>" />
<input type="submit" value="金融機関選択画面へ進む" />
</form>
