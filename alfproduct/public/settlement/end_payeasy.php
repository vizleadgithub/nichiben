<?php
include(dirname(__FILE__) ."./../../module/module.php");

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
	$arr = unserialize($arrTemp["exec_return"], ['allowed_classes' => false]);
}
?>

注文番号：<?php print(htmlspecialchars((string)($arr["OrderID"]), ENT_QUOTES, 'UTF-8')); ?><br>
お客様番号：<?php print(htmlspecialchars((string)($arr["CustID"]), ENT_QUOTES, 'UTF-8')); ?><br>
収納機関番号：<?php print(htmlspecialchars((string)($arr["BkCode"]), ENT_QUOTES, 'UTF-8')); ?><br>
確認番号：<?php print(htmlspecialchars((string)($arr["ConfNo"]), ENT_QUOTES, 'UTF-8')); ?><br>
支払い期限日時：<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 0, 4 )), ENT_QUOTES, 'UTF-8')); ?>/<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 4, 2 )), ENT_QUOTES, 'UTF-8')); ?>/<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 6, 2 )), ENT_QUOTES, 'UTF-8')); ?> <?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 8, 2 )), ENT_QUOTES, 'UTF-8')); ?>:<?php print(htmlspecialchars((string)(substr( $arr["PaymentTerm"], 10, 2 )), ENT_QUOTES, 'UTF-8')); ?><br>
受付日時：<?php print(htmlspecialchars((string)(substr( $arr["TranDate"], 0, 4 )), ENT_QUOTES, 'UTF-8')); ?>/<?php print(htmlspecialchars((string)(substr( $arr["TranDate"], 4, 2 )), ENT_QUOTES, 'UTF-8')); ?>/<?php print(htmlspecialchars((string)(substr( $arr["TranDate"], 6, 2 )), ENT_QUOTES, 'UTF-8')); ?> <?php print(htmlspecialchars((string)(substr( $arr["TranDate"], 8, 2 )), ENT_QUOTES, 'UTF-8')); ?>:<?php print(htmlspecialchars((string)(substr( $arr["TranDate"], 10, 2 )), ENT_QUOTES, 'UTF-8')); ?><br>

<br />
<br />
<br />
<br />
<br />

ネットバンキングでお支払いのかたはこちら。<br />
金融機関選択画面に遷移します。<br />
ボタンをクリックしてください。<br />
<form action="#" method="post">
<input type="hidden" name="code" value="<?php echo htmlspecialchars((string)($arr['EncryptReceiptNo']), ENT_QUOTES, 'UTF-8'); ?>" />
<input type="submit" value="金融機関選択画面へ進む" />
</form>
