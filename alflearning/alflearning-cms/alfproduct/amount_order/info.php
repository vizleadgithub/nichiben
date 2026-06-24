<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}

// 日弁連フラグ
$login_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
if ($login_bar_association_id == 1){
	$nichibenren_flg = true;  // 日弁連
} else {
	$nichibenren_flg = false; // 日弁連以外の弁護士会
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$mode = "";
$order_id = "";
$order_detail_id = "";
$payment_status = "";
if( isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"]) ){
	$mode = strip_tags($_REQUEST["mode"]);
}
if( isset($_REQUEST["oid"]) && !empty($_REQUEST["oid"]) ){
	$order_id = intval($_REQUEST["oid"]);
}
if( isset($_REQUEST["order_detail_id"]) && !empty($_REQUEST["order_detail_id"]) ){
	$order_detail_id = intval($_REQUEST["order_detail_id"]);
}
if( isset($_REQUEST["payment_status"]) && !empty($_REQUEST["payment_status"]) ){
	$payment_status = strip_tags($_REQUEST["payment_status"]);
}

if($order_id == ""){
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["amount_order.info.page"]) && !empty($_SESSION["amount_order.info.page"]) ){
	$page = $_SESSION["amount_order.info.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_order.info.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "select * from mtb_bar_association";
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_payment_status = array(
				array("id"=>"1",	"name"=>"完了"),
				array("id"=>"2",	"name"=>"全て未入金"),
				array("id"=>"3",	"name"=>"一部未入金"),
				array("id"=>"9",	"name"=>"キャンセル"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql_update_student = '';

if($mode=="pay"){
	$sql = "";
	$sql.= "UPDATE ";
	$sql.= "tbl_order_detail ";
	$sql.= "SET ";
	$sql.= "payment_status='2', ";//商品種別(0:未入金　1:入金待ち　2:入金済み　9:キャンセル)
	$sql.= "payment_date='".date("Y-m-d H:i:s")."', ";//支払日
	$sql.= "receipt_date='".date("Y-m-d H:i:s")."', ";//入金日
	$sql.= "take_date='".date("Y-m-d H:i:s")."', ";//取り込み日（権限付与日）
	$sql.= "update_date='".date("Y-m-d H:i:s")."' ";//更新日
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
	
	$sql2 = "SELECT member_id, product_type_add FROM tbl_order_detail WHERE order_detail_id = '$order_detail_id'";
	$ret_sql2 = $objDbConnect->query_fetch_arr($sql2);
	if ($ret_sql2){
		if ($ret_sql2[0]['product_type_add'] == '4'){
			if ($ret_sql2[0]['member_id'] != ''){
				$now_date = date('Y-m-d');
				//--------------------------------------------
				$sql_passport = "SELECT exp_date_passport, presence_passport FROM student WHERE student_id = '".$ret_sql2[0]['member_id']."' AND exp_date_passport>='".date('Y-m-d')."' ";
				$ret_passport = $objDbConnect->query_fetch_arr($sql_passport);
				$arr_passport = array();
				foreach ($ret_passport as $key => $val){
					$arr_passport = $val;
				}
				// パスポート更新の場合は、有効期限の起算日を＋1日して算出する
				$update_flg = false;
				if( count($arr_passport)>0 ){
					if ($arr_passport["presence_passport"] == '1'){
						if ( strtotime($arr_passport["exp_date_passport"]) >= strtotime($now_date) ){
							$now_date = $arr_passport["exp_date_passport"];
							$update_flg = true;
						}
					}
				}
				//--------------------------------------------
				$arr_now_date = explode('-', $now_date);
				if ($update_flg){
					$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1], $arr_now_date[2] + 1, $arr_now_date[0] + EXP_DATE_PASSPORT_YEAR));
				} else {
					$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1], $arr_now_date[2], $arr_now_date[0] + EXP_DATE_PASSPORT_YEAR));
				}
				$arr_now_date = explode('-', $exp_date_passport);
				$exp_date_passport = date('Y-m-d', mktime(0, 0, 0, $arr_now_date[1] + 1, 0, $arr_now_date[0]));
				$sql_update_student = "UPDATE student SET presence_passport = '1', exp_date_passport = '".$exp_date_passport."' WHERE student_id = '".$ret_sql2[0]['member_id']."'";
			}
		}
	}
	
} elseif($mode=="nopay"){
	$sql = "";
	$sql.= "UPDATE ";
	$sql.= "tbl_order_detail ";
	$sql.= "SET ";
	$sql.= "payment_status='1', ";//商品種別(0:未入金　1:入金待ち　2:入金済み　3:一部未入金　9:キャンセル)
	$sql.= "update_date='".date("Y-m-d H:i:s")."' ";//更新日
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
	
	$sql2 = "SELECT member_id, product_type_add FROM tbl_order_detail WHERE order_detail_id = '$order_detail_id'";
	$ret_sql2 = $objDbConnect->query_fetch_arr($sql2);
	if ($ret_sql2){
		if ($ret_sql2[0]['product_type_add'] == '4'){
			if ($ret_sql2[0]['member_id'] != ''){
				$sql_update_student = "UPDATE student SET presence_passport = '0' WHERE student_id = '".$ret_sql2[0]['member_id']."'";
			}
		}
	}
	
} elseif($mode=="cancel"){
	$sql = "";
	$sql.= "UPDATE ";
	$sql.= "tbl_order_detail ";
	$sql.= "SET ";
	$sql.= "payment_status='9', ";//商品種別(0:未入金　1:入金待ち　2:入金済み　3:一部未入金　9:キャンセル)
	$sql.= "receipt_date='".date("Y-m-d H:i:s")."', ";//入金日
	$sql.= "update_date='".date("Y-m-d H:i:s")."' ";//更新日
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
	
	$sql2 = "SELECT member_id, product_type_add FROM tbl_order_detail WHERE order_detail_id = '$order_detail_id'";
	$ret_sql2 = $objDbConnect->query_fetch_arr($sql2);
	if ($ret_sql2){
		if ($ret_sql2[0]['product_type_add'] == '4'){
			if ($ret_sql2[0]['member_id'] != ''){
				$sql_update_student = "UPDATE student SET presence_passport = '0' WHERE student_id = '".$ret_sql2[0]['member_id']."'";
			}
		}
	}
	
} elseif($mode=="nocancel"){
	$sql = "";
	$sql.= "UPDATE ";
	$sql.= "tbl_order_detail ";
	$sql.= "SET ";
	$sql.= "payment_status='1', ";//商品種別(0:未入金　1:入金待ち　2:入金済み　3:一部未入金　9:キャンセル)
	$sql.= "update_date='".date("Y-m-d H:i:s")."' ";//更新日
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
}
$ret = $objDbConnect->execute($sql.$where);
if(!$ret){
	$arr_err["db"] = "更新に失敗しました。";
} else {
	if ($sql_update_student != ''){
var_dump($sql_update_student);
		$objDbConnect->execute($sql_update_student);
	}
}
if($mode!=""){
	if($mode=="pay"){
		// メールテンプレート取得
		$str_mail = file_get_contents( "/srv/alfproduct/module/order8.mail" );
		$sql = "
		SELECT
		  T2.student_name,
		  T2.student_email,
		  T3.product_name
		FROM
		  tbl_order_detail AS T1
		    INNER JOIN
		  student AS T2
		      ON T1.member_id = T2.student_id
		    INNER JOIN
		  tbl_product AS T3
		      ON T1.product_id = T3.product_id
		WHERE
		  T1.order_detail_id = '$order_detail_id'
		  AND T1.payment_status = 2
		  AND T3.del_flg = 0
		  AND ( (T3.start_date<='".date("Y-m-d")."' and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date<='".date("Y-m-d")."' and T3.end_date IS NULL)  OR  (T3.start_date IS NULL and T3.end_date>='".date("Y-m-d")."')  OR  (T3.start_date IS NULL and T3.end_date IS NULL)  )
		";
		$arr_mail = $objDbConnect->query_fetch_arr($sql);
		if ($arr_mail){
			$name = $arr_mail[0]["student_name"];
			$tomail = $arr_mail[0]["student_email"];
			$product_list = $arr_mail[0]["product_name"]."\n";
			
			$temp_mail = $str_mail;
			$temp_mail = str_replace( '@@name@@', $name, $temp_mail );
			$temp_mail = str_replace( '@@product_list@@', $product_list, $temp_mail );
			
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
		}
	}
	
	//----------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.payment_status ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_id='".$order_id."' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	$all_cancel = true;
	for($i=0;$i<count($arr_temp);$i++){
		if($arr_temp[$i]["payment_status"]!="9"){
			$all_cancel = false;
		}
	}
	if( $all_cancel == true ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='9', ";
		$sql.= "tbl_order.payment_date=null ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.payment_status ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_id='".$order_id."' ";
	$where.= " and tbl_order_detail.payment_status<>'9' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	$all_pay = true;
	if( count($arr_temp)>0 ){
		for($i=0;$i<count($arr_temp);$i++){
			if($arr_temp[$i]["payment_status"]!="2"){
				$all_pay = false;
			}
		}
	} else {
		$all_pay = false;
	}
	if( $all_pay == true ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='2', ";
		$sql.= "tbl_order.payment_date='".date("Y-m-d H:i:s")."' ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.payment_status ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_id='".$order_id."' ";
	$where.= " and tbl_order_detail.payment_status<>'9' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	$all_nopay = true;
	if( count($arr_temp)>0 ){
		for($i=0;$i<count($arr_temp);$i++){
			if($arr_temp[$i]["payment_status"]!="1"){
				$all_nopay = false;
			}
		}
	} else {
		$all_nopay = false;
	}
	if( $all_nopay == true ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='1', ";
		$sql.= "tbl_order.payment_date=null ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
	if( !$all_cancel && !$all_pay && !$all_nopay ){
		$sql = "";
		$sql.= "UPDATE ";
		$sql.= "tbl_order ";
		$sql.= "SET ";
		$sql.= "tbl_order.payment_status='3', ";
		$sql.= "tbl_order.payment_date=null ";
		$where = "";
		$where.= "WHERE ";
		$where.= " tbl_order.order_id='".$order_id."' ";
		$ret = $objDbConnect->execute($sql.$where);
	}
	//----------------------------------------
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order.order_id, ";//注文番号
$sql.= "tbl_order.order_no, ";//注文番号
$sql.= "student.student_name, ";//ユーザ名
$sql.= "student.lawyer_number, ";//登録番号
$sql.= "student.regist_date, ";//登録年月日
$sql.= "mtb_bar_association.name as association_name, ";//弁護士会
//$sql.= "tbl_order_detail.payment_status, ";//支払いステータス
$sql.= "tbl_order.payment_status, ";//支払いステータス
$sql.= "tbl_order.create_date, ";//購入日
$sql.= "tbl_order.payment_type, ";//支払い方法（1：カード　12：銀行振込）
$sql.= "tbl_order.claim_flg, ";//請求書希望
$sql.= "tbl_order.web_flg ";//Web購入
$sql.= "FROM ";
$sql.= "tbl_order ";
$sql.= " LEFT JOIN tbl_order_detail ON tbl_order.order_id=tbl_order_detail.order_id ";
$sql.= " LEFT JOIN student ON student.student_id = tbl_order_detail.member_id ";
$sql.= " LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";

$where = "";
$where.= "WHERE ";
$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
$where.= " and tbl_order.order_id='".$order_id."' ";
$where.= " and tbl_order.payment_status>='1' ";//支払いステータス
//-----------------------------------
if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
} else {
	$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
}
//-----------------------------------
$arr_order = $objDbConnect->query_fetch_arr($sql.$where);
if( 0<count($arr_order) ){
} else {
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "count(order_detail_id) as c ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$where = "";
$where.= "WHERE ";
$where.= " order_id='".$order_id."'";
$group = "";
$ret = $objDbConnect->query_fetch($sql.$where.$group);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?oid=".$order_id."&page=");
//$objAdminPager->setPageMax(1);
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.order_detail_id, ";//ID
$sql.= "tbl_order_detail.product_id, ";//商品ID
$sql.= "tbl_product.product_code, ";//商品コード
$sql.= "tbl_product.product_name AS product_name_TP, ";//商品名
$sql.= "tbl_product_add.product_type_add, ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
$sql.= "tbl_order_detail.pay_total, ";//金額
$sql.= "tbl_order_detail.product_name AS product_name_TOD, ";//商品名
$sql.= "tbl_order.create_date as buy_date, ";//購入日
$sql.= "tbl_order_detail.payment_status, ";//支払い(0:未入金　1:入金待ち　2:入金済み　3:一部未入金　9:キャンセル)
$sql.= "tbl_order_detail.receipt_date, ";//入金日
$sql.= "tbl_order_detail.take_date ";//取り込み日（権限付与日）
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= " LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id ";
$where = "";
$where.= "WHERE ";
$where.= " tbl_order.order_id='".$order_id."' ";
$order = " ORDER BY tbl_order_detail.create_date DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("売上集計");
$template->admin_comment("売上を集計します。");

$temp_bar_association_id = $arr_session["cms_master.login.bar_association_id"];
$temp_bar_association_name = "管理者";
if( $temp_bar_association_id>1 ){
	$sql  = '';
	$sql .= "SELECT  id ";
	$sql .= "       ,(CASE WHEN id = 1 THEN name ELSE concat(name, '弁護士会') END) AS name ";
	$sql .= " FROM mtb_bar_association ";
	$sql .= " WHERE id = ".intval($temp_bar_association_id)." ";
	$headret = $objDbConnect->query_fetch($sql);
	if( isset($headret["name"]) ){
		$temp_bar_association_name = '【'.$headret["name"].'】';
	}
}
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
}

$template->admin_school($arr_session["cms_master.login.school_name"]);

$sidemenu_html = '<ul>';
$sidemenu_html.= '
<li class="selected"><a href="./../amount_order/index.php" style="font-size:13px;">受注履歴</a></li>
<li><a href="./../amount_user/index.php" style="font-size:13px;">会員別売上集計</a></li>
';
//<li><a href="./../amount_passport/index.php" style="font-size:13px;">パスポート注文履歴</a></li>
if ($nichibenren_flg){
	$sidemenu_html.= '
	<li><a href="./../amount_product/index.php" style="font-size:13px;">売り上げ分析*</a></li>
	';
	//<li><a href="./../bank_upload/index.php" style="font-size:13px;">銀行振込取込*</a></li>
}
$sidemenu_html.= '</ul>';
$template->admin_sidemenu($sidemenu_html);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->assign('arr_student', $arr_student[0]);

$template->assign('oid', $order_id);
$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_order', $arr_order);
$template->assign('arr_order_detail', $ret);

$template->assign('page_name', 'amount_order');
$template->admin_layout('amount_order/info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
