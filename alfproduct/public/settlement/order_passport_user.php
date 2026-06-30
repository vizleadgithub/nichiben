<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();

if (!st_login_check()){
	header("Location: /");
	exit;
}
if (strpos($_SERVER['HTTP_REFERER'], '/settlement') === false){
	header("Location: /");
	exit;
}
csrf_token_verify();

$order_id = 0;
$temp_date = $_POST["temp_date"];
$temp_no   = $_POST["temp_no"];

if (!isset($_SESSION["cart"]) || !isset($_SESSION["cart_total_price"])){
	$objDbConnect->close();
	header("Location: /");
	exit;
}

$cart = $_SESSION["cart"];
$cart_total_price = $_SESSION["cart_total_price"];

$sql = "select * from tbl_order where order_no='".$temp_date.$temp_no."' and buy_comp_flg='1' ";
$ret = $objDbConnect->query_fetch_arr($sql);
if( count($ret)>0 ){
	$objDbConnect->close();
	header("Location: /");
	exit;
	
} else {
	$objDbConnect->tran_begin();
	
	$sql = "insert into tbl_order(order_no,price,tax,payment_type,payment_status,order_date,buy_comp_flg,member_id) values('".$temp_date.$temp_no."','".$cart_total_price."','".tax_cal_yen($cart_total_price)."','99','2','".date("Y-m-d H:i:s")."','1','".$_SESSION['user']['id']."')";
	$objDbConnect->execute($sql);
	$order_id = mysql_insert_id();
	
	foreach ($cart as $key => $val){
		// 会場研修
		if ($val['product_type_add'] == 2){
			$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,bar_association_id,bar_association_branch_id,payment_status) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','0','0','1','0','".$val["product_type_add"]."','".$val["bar_association_id"]."','".$val["bar_association_branch_id"]."','2')";
			//$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,bar_association_id,bar_association_branch_id) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','".$val["price"]."','".$val["price"]."','1','".$val["price"]."','".$val["product_type_add"]."','".$val["bar_association_id"]."','".$val["bar_association_branch_id"]."')";
		} else {
			$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add,payment_status) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','0','0','1','0','".$val["product_type_add"]."','2')";
			//$sql = "insert into tbl_order_detail(order_id,member_id,product_id,price,sell_price,unit,pay_total,product_type_add) values ('".$order_id."','".$_SESSION['user']['id']."','".$key."','".$val["price"]."','".$val["price"]."','1','".$val["price"]."','".$val["product_type_add"]."')";
		}
		$objDbConnect->execute($sql);
	}
	
	$objDbConnect->commit();
}

$objDbConnect->close();

unset($_SESSION["cart"]);
unset($_SESSION["cart_total_price"]);

$_SESSION["payment.order_no"] = $temp_date.$temp_no;
$_SESSION["payment.order_id"] = $order_id;

$template->layout_noside('settlement/order_passport_user.tpl');
exit;
?>