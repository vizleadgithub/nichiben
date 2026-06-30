<?php
include(dirname(__FILE__) ."./../../module/module.php");

$err_flg = 0;
$order_id = $_SESSION["order_id"];

$item_name = '';
$mail_address = '';
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
		//++++++++++++++++++++++++
		//注文詳細から取得
		$shop_name = '株式会社ビズリード';
		$item_name = '商品名';
		$user_name = $_POST["user_name"];
		$mail_address = $_POST["mail_address"];
		//++++++++++++++++++++++++
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
	csrf_token_verify();
	$objGMOPaymentProtocol = new GMOPaymentProtocol();
	if( $access_id=="" && $access_pass=="" ){
		$ret = $objGMOPaymentProtocol->entry_tran_webmoney( $order_id, $price, $tax );
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
					$sql = "update tbl_order set payment_status='0', access_id='".$ret["AccessID"]."', access_pass='".$ret["AccessPass"]."' where order_id='".$order_id."' ";
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
			$ret = $objGMOPaymentProtocol->exec_tran_webmoney( $access_id, $access_pass, $order_id, $item_name, $shop_name, $mail_address, $etc1, $etc2, $etc3 );
			if( isset($ret["ErrCode"]) ){
				if( $ret["ErrCode"]!="" ){
					$err_flg = 1;
					print("order err.7");
					//exit();
				}
			} else {
				$sql = "update tbl_order set payment_status='1', exec_return='".serialize($ret)."' where order_id='".$order_id."' ";
				$objDbConnect->execute($sql);
				
				header("Location: end_webmoney.php?".session_name ()."=".session_id() );
				exit;
			}
		}
	}
?>
	決済エラー<br>
	<form name="form_payment" method="post" action="#">
	注文番号：<?php print_r("".$order_id.""); ?><br>
	購入金額：<?php print_r("".$total.""); ?><br>
	<!--氏名:<input type="text" name="user_name" value="<?php print($user_name); ?>"><br>-->
	<!--E-Mail:<input type="text" name="mail_address" value="<?php print($mail_address); ?>"><br>-->
	<input type="submit" name="btn_submit" value="Submit">
	</form>
<?php
} else {
?>
	<form name="form_payment" method="post" action="#">
	注文番号：<?php print_r("".$order_id.""); ?><br>
	購入金額：<?php print_r("".$total.""); ?><br>
	<!--氏名:<input type="text" name="user_name" value=""><br>-->
	<!--E-Mail:<input type="text" name="mail_address" value=""><br>-->
	<input type="submit" name="btn_submit" value="Submit">
	</form>
<?php
}
?>