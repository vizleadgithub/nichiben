<?php
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();

if (!st_login_check()){
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}
if (strpos($_SERVER['HTTP_REFERER'], '/settlement') === false){
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}

$order_id = 0;
//$pid = $_POST["pid"];
//$payment_type = $_POST["payment_type"];
//$pid       = $_REQUEST["pid"];
$temp_date = $_REQUEST["temp_date"];
$temp_no   = $_REQUEST["temp_no"];
$payment_type = $_REQUEST["payment_type"];



//----------------------------------------------
$passport_elearning_mix = false;
$buy_passport = 0;
foreach ($_SESSION["cart"] as $cart){
	if( $cart["product_type_add"]=="4" ){
		foreach ($_SESSION["cart"] as $cart){
			if( $cart["product_type_add"]=="1" ){
				$passport_elearning_mix = true;
				break;
			}
		}
		$buy_passport = 1;
	}
}
$template->assign('buy_passport', $buy_passport);
//----------------------------------------------


// パスポート購入の場合はpayment_typeを99にする
if ($_SESSION['user']['presence_passport']=='1' && $buy_passport==0){
	$payment_type = '99';
}

// パスポート未所持かつカート合計金額0円の場合は、パスポートアラートチェックをせず、銀行振込扱いにする
if ( $_SESSION['user']['presence_passport']=='0' && $_SESSION['cart_total_price'] <= 0 ){
	$payment_type = '12';
} else {
	// パスポート未購入の場合、カート内金額が対象パスポート金額を超えているかチェック
	if (!isset($_SESSION['alert_click'])){
//		if ($_SESSION['user']['presence_passport']=='0' && $_SESSION['user']['passport_pop_flg']!='1'){
//			// カートの中身がパスポートかチェック
//			$passport_flg = false;
//			foreach ($_SESSION['cart'] as $key => $val){
//				if ($val['product_type_add']==4){
//					$passport_flg = true;
//				}
//			}
//			if (!$passport_flg){
//				$user_year = $_SESSION['user']['year']; // 何年目の弁護士か
//				$check_price = 0;
//				if ($user_year >= 1 && $user_year <= 2){
//					$check_price = PASSPORT_PRICE1;
//				} else if ($user_year >= 3 && $user_year <= 5){
//					$check_price = PASSPORT_PRICE2;
//				} else {
//					$check_price = PASSPORT_PRICE3;
//				}
//				
//				if ($_SESSION['cart_total_price'] >= $check_price){
//					$_SESSION['payment_type'] = $payment_type;
//					header("Location: /settlement/alert_passport.php");
//					exit;
//				}
//			}
//		}
		
		// パスポートとeラーニングが一緒にカートに入っている場合、カート画面に戻る
		if ($passport_elearning_mix){
			header("Location: /settlement/");
			exit;
		}
		
	} else {
		// パスポート購入を選択した場合
		if ($_SESSION['alert_click']['passport']==1){
			// パスポートがカートに入っているかチェックする
			$passport_flg = false;
			foreach ($_SESSION['cart'] as $key => $val){
				if ($val['product_type_add']==4){
					$passport_flg = true;
				}
			}
			// パスポートが入っていた場合は、カートをパスポートのみにし、入っていた商品をお気に入り登録する
			if ($passport_flg){
				foreach ($_SESSION['cart'] as $key => $val){
					if ($val['product_type_add']!=4){
						// カート総金額計算
						$_SESSION['cart_total_price'] -= $val['price'];
						// お気に入り登録
						$sql = "SELECT COUNT(*) AS c FROM tbl_favorite WHERE member_id='".$_SESSION['user']['id']."' AND product_id='".$key."' AND del_flg='0'";
						$ret = $objDbConnect->query_fetch($sql);
						if ($ret['c']==0){
							$sql = "update tbl_favorite set rank=rank+1 where member_id='".$_SESSION['user']['id']."'";
							$objDbConnect->execute($sql);
							$sql = "INSERT INTO tbl_favorite (member_id,product_id,regist_date) VALUES ('".$_SESSION['user']['id']."','".$key."','".date('Y-m-d H:i:s')."')";
							$objDbConnect->execute($sql);
						}
						unset($_SESSION['cart'][$key]);
					}
				}
			}
		}
		unset($_SESSION['alert_click']);
	}
}

//if(cmCheckInput($pid, 'CK_NUM') || cmCheckInput($payment_type, 'CK_NUM')){
if(cmCheckInput($payment_type, 'CK_NUM')){
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}

if (!isset($_SESSION["cart"]) || !isset($_SESSION["cart_total_price"])){
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}

//if ($_SESSION['user']['presence_passport']=='1'){
//	if (empty($_SESSION["cart"])){
//		$template->layout_noside('settlement/err.tpl');
//		$objDbConnect->close();
//		exit;
//	}
//} else {
//	if (empty($_SESSION["cart"]) || $_SESSION["cart_total_price"]==0){
//		$template->layout_noside('settlement/err.tpl');
//		$objDbConnect->close();
//		exit;
//	}
//}

if (empty($_SESSION["cart"])){
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}

$cart = $_SESSION["cart"];
$cart_total_price = $_SESSION["cart_total_price"];

/*
$arr_list = get_settlement_product($objDbConnect, $pid);
if (empty($arr_list)){
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}

$price = $arr_list['price'];
$tax = $arr_list['tax'];
$subtotal = $arr_list['subtotal'];
*/

$sql = "select * from tbl_order_temp where order_no='".$temp_date.$temp_no."' and payment_status='0' and entry_return IS NULL ";
$ret = $objDbConnect->query_fetch_arr($sql);

if( !empty($ret) ){
	$order_id=$ret[0]["order_id"];
	$change_flg = false;
	
	// カートの中身に変更があった場合、注文情報を更新する
	if ($ret[0]["price"]!=$_SESSION["cart_total_price"]){
		$change_flg = true;
		
		$objDbConnect->tran_begin();
		
		// 注文情報
		$sql = "UPDATE tbl_order_temp SET price = '".$_SESSION["cart_total_price"]."' WHERE order_id = '$order_id'";
		$objDbConnect->execute($sql);
		
		// 注文情報詳細
		$sql = "DELETE FROM tbl_order_detail WHERE order_id = '$order_id'";
		$objDbConnect->execute($sql);
		
		foreach ($cart as $key => $val){
			// 会場研修
			if ($val['product_type_add'] == 2){
				$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,bar_association_id,bar_association_branch_id,product_name,product_code,open_period,start_date,end_date) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','".$val["price"]."','".$val["price"]."','1','".$val["price"]."','".$val["product_type_add"]."','".$val["bar_association_id"]."','".$val["bar_association_branch_id"]."',(select tbl_product.product_name from tbl_product where tbl_product.product_id=$key),(select tbl_product.product_code from tbl_product where tbl_product.product_id=$key),(select tbl_product.open_period from tbl_product where tbl_product.product_id=$key),(select tbl_product.start_date from tbl_product where tbl_product.product_id=$key),(select tbl_product.end_date from tbl_product where tbl_product.product_id=$key))";
			} else {
				$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,product_name,product_code,open_period,start_date,end_date) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','".$val["price"]."','".$val["price"]."','1','".$val["price"]."','".$val["product_type_add"]."',(select tbl_product.product_name from tbl_product where tbl_product.product_id=$key),(select tbl_product.product_code from tbl_product where tbl_product.product_id=$key),(select tbl_product.open_period from tbl_product where tbl_product.product_id=$key),(select tbl_product.start_date from tbl_product where tbl_product.product_id=$key),(select tbl_product.end_date from tbl_product where tbl_product.product_id=$key))";
			}
			$objDbConnect->execute($sql);
		}
	}
	
	// 支払方法に変更があった場合、注文情報を更新する
	if ($ret[0]["payment_type"]!=$payment_type){
		$change_flg = true;
		
		$objDbConnect->tran_begin();
		
		// 支払方法
		$sql = "UPDATE tbl_order_temp SET payment_type = '".$payment_type."' WHERE order_id = '$order_id'";
		$objDbConnect->execute($sql);
	}
	
	if ($change_flg){
		$objDbConnect->commit();
		$objDbConnect->close();
	}
	
} else {
	$objDbConnect->tran_begin();
	
	$sql = "insert into tbl_order_temp(order_no,price,tax,payment_type,payment_status,order_date,member_id) values('".$temp_date.$temp_no."','".$cart_total_price."','".tax_cal_yen($cart_total_price)."','".$payment_type."','0','".date("Y-m-d H:i:s")."','".$_SESSION['user']['id']."')";
	$objDbConnect->execute($sql);
	//$order_id = mysql_insert_id();
	$ret = $objDbConnect->query_fetch("SELECT LAST_INSERT_ID() as order_id");
	$order_id = $ret["order_id"];

	foreach ($cart as $key => $val){
		// 会場研修
		if ($val['product_type_add'] == 2){
			$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,bar_association_id,bar_association_branch_id,product_name,product_code,open_period,start_date,end_date) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','".$val["price"]."','".$val["price"]."','1','".$val["price"]."','".$val["product_type_add"]."','".$val["bar_association_id"]."','".$val["bar_association_branch_id"]."',(select tbl_product.product_name from tbl_product where tbl_product.product_id=$key),(select tbl_product.product_code from tbl_product where tbl_product.product_id=$key),(select tbl_product.open_period from tbl_product where tbl_product.product_id=$key),(select tbl_product.start_date from tbl_product where tbl_product.product_id=$key),(select tbl_product.end_date from tbl_product where tbl_product.product_id=$key))";
		} else {
			$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,product_name,product_code,open_period,start_date,end_date) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','".$val["price"]."','".$val["price"]."','1','".$val["price"]."','".$val["product_type_add"]."',(select tbl_product.product_name from tbl_product where tbl_product.product_id=$key),(select tbl_product.product_code from tbl_product where tbl_product.product_id=$key),(select tbl_product.open_period from tbl_product where tbl_product.product_id=$key),(select tbl_product.start_date from tbl_product where tbl_product.product_id=$key),(select tbl_product.end_date from tbl_product where tbl_product.product_id=$key))";
		}
		$objDbConnect->execute($sql);
	}

/*
	$sql = "insert into tbl_order(order_no,price,tax,payment_type,payment_status,order_date) values('".$temp_date.$temp_no."','".$cart_total_price."','".tax_cal_yen($)."','".$payment_type."','0','".date("Y-m-d H:i:s")."')";
	$objDbConnect->execute($sql);
	$order_id = mysql_insert_id();

	$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total) values ('".$order_id."','".$_SESSION['user']['id']."','".$pid."','".$price."','".$subtotal."','1','".$subtotal."')";
	$objDbConnect->execute($sql);
*/

	$objDbConnect->commit();
	$objDbConnect->close();
}
$_SESSION["order_id"] = $order_id;

// パスポート未所持かつカート合計金額0円の場合は、無料決済フローへ
//if ( $_SESSION['user']['presence_passport']=='0' && $cart_total_price <= 0 ){
if ( $cart_total_price <= 0 ){
	header("Location: payment_free.php?".session_name ()."=".session_id() );
	$objDbConnect->close();
	exit;
}

if( $payment_type=="1" ){
	header("Location: payment_card.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="2" ){
	header("Location: payment_suica.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="3" ){
	header("Location: payment_edy.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="4" ){
	header("Location: payment_cvs.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="5" ){
	header("Location: payment_payeasy.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="6" ){
	header("Location: payment_paypal.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="7" ){
	header("Location: payment_id.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="8" ){
	header("Location: payment_webmoney.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="9" ){
	header("Location: payment_au.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="10" ){
	header("Location: payment_docomo.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="11" ){
	header("Location: payment_sb.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="12" ){
	header("Location: payment_bank.php?".session_name ()."=".session_id() );
} elseif( $payment_type=="99" ){
	header("Location: payment_passport_user.php?".session_name ()."=".session_id() );
} else {
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}
$objDbConnect->close();
exit;
?>