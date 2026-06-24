<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = '講座購入';
$objDbConnect = new DbConnect();
$template = new Template();

$back_url = "/";

$err_msg = '';
$mode = $_POST["mode"] ?? '';
$product_type_add = $_POST['hid_product_type_add'] ?? '';

// ログインチェック
$st_login_check = st_login_check();

if (!$st_login_check){
	//header("Location: /login/login.php?back_url=$back_url");
	header("Location: /");
	$objDbConnect->close();
	exit;
}

$pid = "";
if (isset($_POST['pid'])){
	if (strpos($_SERVER['HTTP_REFERER'], '/product/detail.php') !== false
	 && strpos($_SERVER['HTTP_REFERER'], '/settlement/index.php') !== false){
		$template->layout_noside('settlement/err.tpl');
		$objDbConnect->close();
		exit;
	}

	$pid = intval($_POST['pid'] ?? 0);
	if(cmCheckInput($pid, 'CK_NUM')){
		$template->layout_noside('settlement/err.tpl');
		$objDbConnect->close();
		exit;
	}
	$arr_list = get_settlement_product($objDbConnect, $pid);
	if (empty($arr_list)){
		$template->layout_noside('settlement/err.tpl');
		$objDbConnect->close();
		exit;
	}

	// カートの中身削除
	if ($mode == "delete"){
		$back_url = "/product/detail.php?pid=$pid";
		if (isset($_SESSION["cart"][$pid])){
			unset($_SESSION["cart"][$pid]);
			$_SESSION["cart_total_price"] = _get_cart_total_price();
		}
		
	} else {
		$back_url = "/product/detail.php?pid=$pid";
		// -------------
		// 商品チェック
		// -------------
		$user_id = $_SESSION['user']['id'];
		// ユーザー情報の取得
		$user_list = array();
		$sql = "SELECT * FROM student WHERE status='0' AND student_id='$user_id'";
		$user_list = $objDbConnect->query_fetch_arr($sql);
		
		$buy_flg = true;
		if ($product_type_add != 4){
			// 無料確認
			if ($arr_list['price']==0){
				if ($product_type_add != 2){
					$buy_flg = false;
				}
			}
			// 月額課金ユーザ確認
			if ($user_list['member_type']==2){
				if ($product_type_add != 2){
					$buy_flg = false;
				}
			// 購入済み・視聴期間超え確認
			} else {
				if (buy_and_open_period_date_check($objDbConnect, $pid, $user_id)){
					$buy_flg = false;
				}
			}
			if (!$buy_flg){
				header("Location: $back_url");
				$objDbConnect->close();
				exit;
			}
		}

		
		// -----------
		// カート機能
		// -----------
		// カートに同一商品が入っている場合
		if (isset($_SESSION["cart"][$pid])){
			$err_msg = '選択した商品は既にカートに入っています。';
			
		} else {
			$_SESSION["cart"][$pid]["product_name"] = $arr_list["product_name"];
			$_SESSION["cart"][$pid]["price"]        = $arr_list["price"];
			$_SESSION["cart"][$pid]["product_type_add"] = $_POST["hid_product_type_add"];
			if (isset($_POST["hid_bar_association_id"])){
				$_SESSION["cart"][$pid]["bar_association_id"] = $_POST["hid_bar_association_id"];
			}
			if (isset($_POST["hid_bar_association_branch_id"])){
				$_SESSION["cart"][$pid]["bar_association_branch_id"] = $_POST["hid_bar_association_branch_id"];
			}
		}
		$_SESSION["cart_total_price"] = _get_cart_total_price();
	}
}

$temp_no = 10001;
$temp_date = date("Ymd");

//----------------------------------------------
$buy_passport = 0;
foreach ($_SESSION["cart"] as $cart){
	if( $cart["product_type_add"]=="4" ){
		foreach ($_SESSION["cart"] as $cart){
			if( $cart["product_type_add"]=="1" ){
				if (isset($_SESSION['alert_click']['passport'])){
					if ($_SESSION['alert_click']['passport']!=1){
						$err_msg = 'パスポートとeラーニングの同時購入は、できません。';
						break;
					}
				} else {
					$err_msg = 'パスポートとeラーニングの同時購入は、できません。';
					break;
				}
			}
		}
		$buy_passport = 1;
	}
}
$template->assign('buy_passport', $buy_passport);
$_SESSION["cart_total_price"] = _get_cart_total_price($buy_passport);
//----------------------------------------------


if(  isset($_SESSION["payment.temp_no"]) && !empty($_SESSION["payment.temp_no"]) && isset($_SESSION["payment.temp_date"]) && !empty($_SESSION["payment.temp_date"])  ){
	$temp_no = $_SESSION["payment.temp_no"];
	$temp_date = $_SESSION["payment.temp_date"];
	$sql = "select order_id from tbl_order_temp where order_no='".$temp_date.$temp_no."' and payment_status<>0 LIMIT 1";
	$ret = $objDbConnect->query_fetch_arr($sql);
	if( count($ret)>0 ){
		$objDbConnect->tran_begin();
		$sql = "select create_date, no from tbl_order_no where create_date='".date("Y-m-d")."' ORDER BY no DESC LIMIT 1";
		$ret = $objDbConnect->query_fetch_arr($sql);
		if( count($ret)>0 ){
			$temp_no = $ret[0]["no"] + 1;
			$temp_date = date("Ymd");
		} else {
			$temp_no = 10001;
			$temp_date = date("Ymd");
		}
		$_SESSION["payment.temp_no"]   = $temp_no;
		$_SESSION["payment.temp_date"] = $temp_date;

		$sql = "INSERT INTO tbl_order_no( create_date, no ) values('".$temp_date."','".$temp_no."')";
		$ret = $objDbConnect->execute($sql);
		if($ret){
			$objDbConnect->commit();
		} else {
			$objDbConnect->rollback();
			$template->layout_noside('settlement/err.tpl');
			$objDbConnect->close();
			exit;
		}
	} else {
		$_SESSION["payment.temp_no"]   = $temp_no;
		$_SESSION["payment.temp_date"] = $temp_date;
	}
} else {
	$objDbConnect->tran_begin();
	$sql = "select create_date, no from tbl_order_no where create_date='".date("Y-m-d")."' ORDER BY no DESC LIMIT 1";
	$ret = $objDbConnect->query_fetch_arr($sql);
	if( count($ret)>0 ){
		$temp_no = $ret[0]["no"] + 1;
		$temp_date = date("Ymd");
	} else {
		$temp_no = 10001;
		$temp_date = date("Ymd");
	}
	$_SESSION["payment.temp_no"]   = $temp_no;
	$_SESSION["payment.temp_date"] = $temp_date;

	$sql = "INSERT INTO tbl_order_no( create_date, no ) values('".$temp_date."','".$temp_no."')";
	$ret = $objDbConnect->execute($sql);
	if($ret){
		$objDbConnect->commit();
	} else {
		$objDbConnect->rollback();
		$template->layout_noside('settlement/err.tpl');
		$objDbConnect->close();
		exit;
	}
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('arr_list', $arr_list);
$template->assign('pid', $pid);

$template->assign('temp_date', $temp_date);
$template->assign('temp_no', $temp_no);

$template->assign('back_url', $back_url);

$template->assign('err_msg', $err_msg);
$template->assign('cart', $_SESSION["cart"]);
$template->assign('cart_total_price', $_SESSION["cart_total_price"]);



if (isset($_GET['ptype'])){
	$ptype = $_GET['ptype'];
} else {
	$ptype = '1';
}
$template->assign('ptype', $ptype);

$template->layout_noside('settlement/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit();

function _get_cart_total_price($buy_passport=0){
	$cart_total_price = 0;
	if ($_SESSION['user']['presence_passport'] != 1 || $buy_passport==1){
		if (isset($_SESSION["cart"])){
			foreach ($_SESSION["cart"] as $cart){
				$cart_total_price += $cart["price"];
			}
		}
	}
	
	return $cart_total_price;
}
?>
