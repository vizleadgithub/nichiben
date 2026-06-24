<?php
include(dirname(__FILE__) ."./../../module/module.php");
$objDbConnect = new DbConnect();
$template = new Template();

if (!st_login_check()){
	//var_dump("Test4");
	$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	exit;
}
if (strpos($_SERVER['HTTP_REFERER'], '/settlement') !== false){
} else {
	//var_dump("Test5");
	//$template->layout_noside('settlement/err.tpl');
	$objDbConnect->close();
	header("Location: /" );
	exit;
}

$err_flg = 0;
$order_id = "";
$order_id = $_SESSION["order_id"];
$order_no = "";

$card_no = $_POST["card_no"];
$expire_y = $_POST["expire_y"];
$expire_m = $_POST["expire_m"];
$expire = $expire_y.$expire_m;
$etc1 = "";
$etc2 = "";
$etc3 = "";

// カートにパスポートが入っているかチェック
$passport_flg = false;
foreach ($_SESSION["cart"] as $val){
	if ($val["product_type_add"] == 4){
		$passport_flg = true;
		break;
	}
}

$sql = "select * from tbl_order_temp where order_id='".$order_id."'";
$ret = $objDbConnect->query($sql);
$arrTemp = $objDbConnect->fetch($ret);
if( !$arrTemp ) {
	$err_flg = 1;
	//print("order err.1");
	//exit();
} else {
	$sql = "select * from tbl_order where order_no='".$arrTemp["order_no"]."' and payment_status=2";
	$ret_sub = $objDbConnect->query_fetch_arr($sql);
	if( count($ret_sub)>0 ){
		$template->layout_noside('settlement/err.tpl');
		$objDbConnect->close();
		exit;
	}

	if ( is_numeric($arrTemp["order_no"]) ) {
		$order_no = $arrTemp["order_no"];
	} else {
		$err_flg = 1;
		//print("order err.2");
		//exit();
	}

	if( $arrTemp["payment_status"]!="0" ){
		$template->assign('order_id', $order_id);
		$template->assign('order_no', $order_no);
		$template->layout_noside('settlement/end.tpl');
		$objDbConnect->close();
		exit;
	}

	if ( is_numeric($arrTemp["price"]) && is_numeric($arrTemp["tax"]) ) {
		$access_id = $arrTemp["access_id"];
		$access_pass = $arrTemp["access_pass"];
		$price = $arrTemp["price"];
		$tax = $arrTemp["tax"];
		$total = $arrTemp["price"] + $arrTemp["tax"];
	} else {
		$err_flg = 1;
		//print("order err.3");
		//exit();
	}
	if( $total>0 ){
	} else {
		$err_flg = 1;
		//print("order err.4");
		//exit();
	}
}

if ($_POST["mode"] == "settlement_exe"){
	$claim_flg = 0;
	if (isset($_POST["claim_flg"])){
		$claim_flg = $_POST["claim_flg"];
	}
	
	if( $err_flg==0 ){
		// tbl_order_tempテーブルからtbl_orderに購入データをコピー
		$sql = "INSERT INTO tbl_order SELECT * FROM tbl_order_temp WHERE order_id = '$order_id'";
		$exe_result = $objDbConnect->execute($sql);
		// tempデータの削除
		//$sql = "DELETE FROM tbl_order_temp WHERE order_id = '$order_id'";
		//$objDbConnect->execute($sql);
		
		// tbl_order_detail更新
		if ($exe_result){
			// 購入商品の中に、無料のものが含まれているかチェック
			$free_flg = false;
			$sql = "SELECT order_detail_id, price FROM tbl_order_detail WHERE order_id = '".$order_id."'";
			$ret_od = $objDbConnect->query_fetch_arr($sql);
			if ($ret_od){
				foreach ($ret_od as $od_row){
					// 注文詳細の支払フラグ更新
					$payment_status = 0;
					if ($od_row['price'] <= 0){
						$free_flg = true;
						$payment_status = 2; // 無料の時は、支払済み
					} else {
						$payment_status = 1; // 無料以外の時は、入金待ち
					}
					$sql = "update tbl_order_detail set payment_status='".$payment_status."' where order_detail_id='".$od_row['order_detail_id']."' ";
					$exe_result = $objDbConnect->execute($sql);
					if ($exe_result){
					} else {
						$err_flg = 1;
						break;
					}
				}
				
				// tbl_order更新
				if ($exe_result){
					// 注文データの更新
					$payment_status = 0;
					if ($free_flg){
						$payment_status = 3; // 無料商品が含まれていた場合は、一部入金
					} else {
						$payment_status = 1; // 無料商品が含まれていなかった場合は、入金待ち
					}
					$sql = "update tbl_order set payment_status='".$payment_status."', claim_flg='$claim_flg', web_flg='1', order_date='".date('Y-m-d H:i:s')."' where order_id='".$order_id."' ";
					$exe_result = $objDbConnect->execute($sql);
					if ($exe_result){
					} else {
						$err_flg = 1;
					}
					
				} else {
					$err_flg = 1;
				}
				
			} else {
				$err_flg = 1;
			}
			
		} else {
			$err_flg = 1;
		}
		
		// カート情報削除
		unset($_SESSION["cart"]);
		unset($_SESSION["cart_total_price"]);
		
		unset($_SESSION["order_id"]);
		unset($_SESSION["payment.temp_no"]);
		unset($_SESSION["payment.temp_date"]);
		unset($_SESSION["payment.order_no"]);
		unset($_SESSION["payment.order_id"]);
		
		$template->assign('order_id', $order_id);
		$template->assign('order_no', $order_no);
		$template->assign('total', $total);
		//+++++++++++++++++++++++++++++++++++++++++++++++++++
		$_SESSION["payment.order_no"] = $order_no;
		$_SESSION["payment.order_id"] = $order_id;
		//+++++++++++++++++++++++++++++++++++++++++++++++++++
		// 銀行振込＋パスポート＋請求書なし
		if ($passport_flg && $claim_flg == 0){
			$str_mail = file_get_contents( dirname(__FILE__) ."./../../module/order6.mail" );
			
		// 銀行振込＋パスポート＋請求書あり
		} else if ($passport_flg && $claim_flg == 1){
			$str_mail = file_get_contents( dirname(__FILE__) ."./../../module/order7.mail" );
			
		// 銀行振込＋パスポートなし＋請求書なし
		} else if (!$passport_flg && $claim_flg == 0){
			$str_mail = file_get_contents( dirname(__FILE__) ."./../../module/order2.mail" );
			
		// 銀行振込＋パスポートなし＋請求書あり
		} else if (!$passport_flg && $claim_flg == 1){
			$str_mail = file_get_contents( dirname(__FILE__) ."./../../module/order3.mail" );
		}
		$sql = "select * from tbl_order where order_id='".$order_id."'";
		$arr_order = $objDbConnect->query_fetch_arr($sql);
		if( !$arr_order ){
	//var_dump($sql);
		}else{
			$sql = "
			select
			  tbl_order_detail.member_id,
			  tbl_order_detail.pay_total,
			  tbl_order_detail.product_type_add,
			  tbl_order_detail.product_id,
			  tbl_order_detail.bar_association_branch_id,
			  tbl_product.product_name,
			  tbl_product.product_code,
			  tbl_product.teacher,
			  DATE_FORMAT(tbl_product.start_date,'%Y/%m/%d') as start_date,
			  DATE_FORMAT(DATE_ADD(tbl_product.start_date, INTERVAL tbl_product.open_period DAY),'%Y/%m/%d') as end_date,
			  tbl_product.open_period
			from
			  tbl_order_detail
			    LEFT JOIN
			  tbl_product
			      ON tbl_order_detail.product_id=tbl_product.product_id
			where
			  order_id='".$order_id."'
			";
			$arr_order_detail = $objDbConnect->query_fetch_arr($sql);
			if( !$arr_order_detail ){
	//var_dump($sql);
			}else{
				$sql = "select student_name,student_email from student where student_id='".$arr_order_detail[0]["member_id"]."'";
				$arr_student = $objDbConnect->query_fetch_arr($sql);
				if( !$arr_student ){
	//var_dump($arr_order_detail);
	//var_dump($sql);
				}else{
					$name = $arr_student[0]["student_name"];
					$tomail = $arr_student[0]["student_email"];
					//$order_no = $arr_order[0]["order_no"];
					$total = $arr_order[0]["price"] + $arr_order[0]["tax"];
					$total = number_format($total);
					//$tax = $arr_order[0]["tax"];;
					//$payment_type = "カード決済";
					//$subtotal = 0;
					$str_product_list = "";
					for($x=0;$x<count($arr_order_detail);$x++){
						// パスポート
						if ($passport_flg){
							$str_product_list.= '○'.$arr_order_detail[$x]["product_name"]."\n";
							
						// eラーニング・会場研修
						} else {
							// 会場研修
							if ($arr_order_detail[$x]["product_type_add"] == 2){
								// 開催日時の取得
								$arr_rel_product_bar_association_branch = array();
								$sql = "SELECT DATE_FORMAT(dates, '%Y年%m月%d日') AS dates FROM rel_product_bar_association_branch WHERE product_id = '".$arr_order_detail[$x]["product_id"]."' AND bar_association_branch_id = '".$arr_order_detail[$x]["bar_association_branch_id"]."'";
								$arr_rel_product_bar_association_branch = $objDbConnect->query_fetch_arr($sql);
								if ($arr_rel_product_bar_association_branch){
									$dates = $arr_rel_product_bar_association_branch = '['.$arr_rel_product_bar_association_branch[0][dates].'開催]';
								} else {
									$dates = '';
								}
								
								$str_product_list.= '○'.$dates.$arr_order_detail[$x]["product_name"]."\n";
								
							// eラーニング・その他
							} else {
								$str_product_list.= '○'.$arr_order_detail[$x]["product_name"]."\n";
								
							}
						}
						$str_product_list.= ' →料金(税込)：￥'.number_format($arr_order_detail[$x]["pay_total"])."\n";
						$str_product_list.= "\n";
						
						//$str_product_list.= '講座名 : '.$arr_order_detail[$x]["product_name"]."\n";
						//$str_product_list.= '商品コード : '.$arr_order_detail[$x]["product_code"]."\n";
						//$str_product_list.= '価格 : '.$arr_order_detail[$x]["pay_total"]."\n";
						//$str_product_list.= '講師名 : '.$arr_order_detail[$x]["teacher"]."\n";
						//$str_product_list.= '公開日 : '.$arr_order_detail[$x]["start_date"]."\n";
						//if( $arr_order_detail[$x]["open_period"]=="0" ){
						//	$str_product_list.= '視聴期間 : 無制限'."\n";
						//} else {
						//	$str_product_list.= '視聴期間 : 購入から'.$arr_order_detail[$x]["open_period"].'日間'."\n";
						//}
						//$str_product_list.= "\n";
						//$subtotal += $arr_order_detail[$x]["pay_total"];
					}
					$temp_mail = $str_mail;
					$temp_mail = str_replace( '@@name@@', $name, $temp_mail );
					$temp_mail = str_replace( '@@total@@', $total, $temp_mail );
					//$temp_mail = str_replace( '@@order_no@@', $order_no, $temp_mail );
					//$temp_mail = str_replace( '@@total@@', $total, $temp_mail );
					//$temp_mail = str_replace( '@@tax@@', $tax, $temp_mail );
					//$temp_mail = str_replace( '@@payment_type@@', $payment_type, $temp_mail );
					//$temp_mail = str_replace( '@@subtotal@@', $subtotal, $temp_mail );
					$temp_mail = str_replace( '@@product_list@@', $str_product_list, $temp_mail );
					// 請求書なしの場合、振込期限の取得
					if ($claim_flg == 0){
						$sql = "SELECT DATE_FORMAT(ADDDATE(NOW(), INTERVAL 15 DAY), '%Y年%m月%d日') AS limit_date";
						$arr_limit_date = $objDbConnect->query_fetch_arr($sql);
						if ($arr_limit_date){
							$limit_date = $arr_limit_date[0]['limit_date'];
						} else {
							$limit_date = '';
						}
						$temp_mail = str_replace( '@@limit1@@', $limit_date, $temp_mail );
						$temp_mail = str_replace( '@@limit2@@', $limit_date, $temp_mail );
					}

					mb_language("japanese");
					mb_internal_encoding("UTF-8");
					define('FROMADDRESS', 'KENSHUmaster@nichibenren.or.jp');
					define('FROMNAME', '日本弁護士連合会');
					$to      = $tomail;
					$subject = str_replace('@@name@@',$name,'【日本弁護士連合会】ご購入ありがとうございます。');
					$body    = $temp_mail;
					$from    = mb_encode_mimeheader(mb_convert_encoding(FROMNAME,"JIS","UTF-8"))."<".FROMADDRESS.">";
					//@mb_send_mail($to,$subject,$body,"From:".$from);
					//mb_send_mail($to,$subject,$body,"From:".$from,"-fKENSHUmaster@nichibenren.or.jp");
					$add_header = "\n";
					//$add_header.= "Bcc: KENSHUmaster@nichibenren.or.jp\n";
					$add_header.= "Reply-to: KENSHUmaster@nichibenren.or.jp\n";
					$add_header.= "X-Mailer: PHP/". phpversion();
					$opt = '-f'.'KENSHUmaster@nichibenren.or.jp';
					$ret_mail = mb_send_mail($to,$subject,$body,"From:".$from.$add_header,$opt);
					//$ret_mail = mb_send_mail($to,$subject,$body,"From:".$from);
	//var_dump($to);
	//var_dump($subject);
	//var_dump($body);
	//var_dump($from);
	//exit();
				}
			}
		}
	//exit();
		//+++++++++++++++++++++++++++++++++++++++++++++++++++
		//$template->layout_noside('settlement/end_card.tpl');
		header("Location: end_bank.php?".session_name ()."=".session_id() );
		$objDbConnect->close();
		exit;
		//+++++++++++++++++++++++++++++++++++++++++++++++++++
	}
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('err_flg', $err_flg);
$template->assign('order_id', $order_id);
$template->assign('order_no', $order_no);
$template->assign('total', $total);
$template->assign('card_no', $card_no);
$template->assign('expire_y', $expire_y);
$template->assign('expire_m', $expire_m);

$template->assign('cart', $_SESSION["cart"]);
$template->assign('cart_total_price', $_SESSION["cart_total_price"]);

$template->layout_noside('settlement/payment_bank.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
exit;
?>