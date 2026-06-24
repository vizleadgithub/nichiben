<?php
include(dirname(__FILE__) ."./../../module/module.php");

$err_flg = 0;
$order_id = $_SESSION["order_id"];
$name = $_POST["name"];
$kana = $_POST["kana"];
$tel1 = $_POST["tel1"];
$tel2 = $_POST["tel2"];
$tel3 = $_POST["tel3"];
$tel  = $tel1.'-'.$tel2.'-'.$tel3;
$mail_address = $_POST["mail_address"];
$etc1 = "";
$etc2 = "";
$etc3 = "";

$objDbConnect = new DbConnect();

$sql = "select * from tbl_order where order_id='".$order_id."'";
$ret = $objDbConnect->query($sql);
$arrTemp = $objDbConnect->fetch($ret);
if( !$arrTemp ) {
	print("order err.1");
	exit();
} else {
	if ( is_numeric($arrTemp["price"]) && is_numeric($arrTemp["tax"]) ) {
		$access_id = $arrTemp["access_id"];
		$access_pass = $arrTemp["access_pass"];
		$price = $arrTemp["price"];
		$tax = $arrTemp["tax"];
		$total = $arrTemp["price"] + $arrTemp["tax"];
	} else {
		$err_flg = 1;
		print("order err.2");
		//exit();
	}
	if( $total>0 ){
	} else {
		$err_flg = 1;
		print("order err.3");
		//exit();
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && $err_flg==0 ){
	$objGMOPaymentProtocol = new GMOPaymentProtocol();
	if( $access_id=="" && $access_pass=="" ){
		$ret = $objGMOPaymentProtocol->entry_tran_payeasy( $order_id, $price, $tax );
		$sql = "update tbl_order set entry_return='".serialize($ret)."' where order_id='".$order_id."' ";
		$objDbConnect->execute($sql);

		if( isset($ret["ErrCode"]) ){
			if( $ret["ErrCode"]!="" ){
				$err_flg = 1;
				print("order err.4");
				//exit();
			}
		}
		if($err_flg==0 ){
			if( isset($ret["AccessID"]) && isset($ret["AccessPass"]) ){
				$access_id = $ret["AccessID"];
				$access_pass = $ret["AccessPass"];
				if( $ret["AccessID"]!="" && $ret["AccessPass"]!="" ){
					$sql = "update tbl_order set payment_status='1', access_id='".$ret["AccessID"]."', access_pass='".$ret["AccessPass"]."' where order_id='".$order_id."' ";
					$objDbConnect->execute($sql);
				} else {
					$err_flg = 1;
					print("order err.5");
					//exit();
				}
			} else {
				$err_flg = 1;
				print("order err.6");
				//exit();
			}
		}
	} 
	if($err_flg==0 ){
		if( $access_id!="" && $access_pass!="" ){
			$ret = $objGMOPaymentProtocol->exec_tran_payeasy( $access_id, $access_pass, $order_id, $name, $kana, $tel, $mail_address, $etc1, $etc2, $etc3 );
			if( isset($ret["ErrCode"]) ){
				if( $ret["ErrCode"]!="" ){
					$err_flg = 1;
					print("order err.7");
					//exit();
				}
			} else {
				$sql = "update tbl_order set exec_return='".serialize($ret)."', payment_date='".date("Y-m-d H:i:s")."' where order_id='".$order_id."' ";
				$objDbConnect->execute($sql);
				
				header("Location: end_payeasy.php?".session_name ()."=".session_id() );
				exit;
			}
		}
	}
?>
	決済エラー<br>
	<form name="form_payment" method="post" action="#">
	注文番号：<?php print_r("".$order_id.""); ?><br>
	購入金額：<?php print_r("".$total.""); ?><br>
	氏名：<input type="text" name="name" value="<?php echo $name; ?>" /><br>
	フリガナ：<input type="text" name="kana" value="<?php echo $kana; ?>" /><br>
	電話番号：<input type="text" name="tel" value="<?php echo $tel1; ?>" />&nbsp;-&nbsp;<input type="text" name="tel" value="<?php echo $tel2; ?>" />&nbsp;-&nbsp;<input type="text" name="tel" value="<?php echo $tel3; ?>" /><br>
	E-Mail:<input type="text" name="mail_address" value="<?php echo $mail_address; ?>"><br>
	<input type="submit" name="btn_submit" value="Submit">
	</form>
<?php
} else {
?>
	<form name="form_payment" method="post" action="#">
	注文番号：<?php print_r("".$order_id.""); ?><br>
	購入金額：<?php print_r("".$total.""); ?><br>
	氏名：<input type="text" name="name" /><br>
	フリガナ：<input type="text" name="kana" /><br>
	電話番号：<input type="text" name="tel1" />&nbsp;-&nbsp;<input type="text" name="tel2" />&nbsp;-&nbsp;<input type="text" name="tel3" /><br>
	E-Mail:<input type="text" name="mail_address" value=""><br>
	<input type="submit" name="btn_submit" value="Submit">
	</form>
<?php
}
?>