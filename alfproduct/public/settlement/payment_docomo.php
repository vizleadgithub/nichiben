<?php
include(dirname(__FILE__) ."./../../module/module.php");

$err_flg = 0;
$order_id = $_SESSION["order_id"];

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
		$docomo1='株式会社Vizlead';
		$docomo2='商品名';
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

if($err_flg==0 ){
	$objGMOPaymentProtocol = new GMOPaymentProtocol();
	if( $access_id=="" && $access_pass=="" ){
		$ret = $objGMOPaymentProtocol->entry_tran_docomo( $order_id, $price, $tax );
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
			$ret = $objGMOPaymentProtocol->exec_tran_docomo( $access_id, $access_pass, $order_id, $docomo1, $docomo2, $etc1, $etc2, $etc3 );
			if( isset($ret["ErrCode"]) ){
				if( $ret["ErrCode"]!="" ){
					$err_flg = 1;
					print("order err.7");
					//exit();
				}
			} else {
				$sql = "update tbl_order set payment_status='1', exec_return='".serialize($ret)."' where order_id='".$order_id."' ";
				$objDbConnect->execute($sql);
				header("Location: end_docomo.php?".session_name ()."=".session_id() );
				exit;
			}
		}
	}
} else {
?>
	決済エラー<br>
	注文番号：<?php print_r("".$order_id.""); ?><br>
	購入金額：<?php print_r("".$total.""); ?><br>
<?php
}
?>