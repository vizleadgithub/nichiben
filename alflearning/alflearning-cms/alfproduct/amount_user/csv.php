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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sid = "";
$search_name = "";
$search_kana = "";
$search_start_lawyer_number = "";
$search_end_lawyer_number = "";
$search_start_regist_date = "";
$search_end_regist_date = "";
$search_association = "";

if( isset($_GET["sid"]) && !empty($_GET["sid"]) ){
	$sid = trim($_GET["sid"]);
}

if( isset($_SESSION["amount_user.search_name"]) && !empty($_SESSION["amount_user.search_name"]) ){
	$search_name = $_SESSION["amount_user.search_name"];
}
if( isset($_SESSION["amount_user.search_kana"]) && !empty($_SESSION["amount_user.search_kana"]) ){
	$search_kana = $_SESSION["amount_user.search_kana"];
}
if( isset($_SESSION["amount_user.search_start_lawyer_number"]) && !empty($_SESSION["amount_user.search_start_lawyer_number"]) ){
	$search_start_lawyer_number = $_SESSION["amount_user.search_start_lawyer_number"];
}
if( isset($_SESSION["amount_user.search_end_lawyer_number"]) && !empty($_SESSION["amount_user.search_end_lawyer_number"]) ){
	$search_end_lawyer_number = $_SESSION["amount_user.search_end_lawyer_number"];
}
if( isset($_SESSION["amount_user.search_start_regist_date"]) && !empty($_SESSION["amount_user.search_start_regist_date"]) ){
	$search_start_regist_date = $_SESSION["amount_user.search_start_regist_date"];
}
if( isset($_SESSION["amount_user.search_end_regist_date"]) && !empty($_SESSION["amount_user.search_end_regist_date"]) ){
	$search_end_regist_date = $_SESSION["amount_user.search_end_regist_date"];
}
if( isset($_SESSION["amount_user.search_association"]) && !empty($_SESSION["amount_user.search_association"]) ){
	$search_association = $_SESSION["amount_user.search_association"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
if( $sid == "" ){
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "student.student_id, ";
	$sql.= "student.student_name, ";
	$sql.= "student.student_email, ";
	$sql.= "student.lawyer_number, ";
	$sql.= "student.presence_passport, ";
	$sql.= "student.sub_auth_ethic_training, ";
	$sql.= "mtb_bar_association.name as association_name ";//弁護士会
	$sql.= "FROM ";
	$sql.= "student ";
	$sql.= "LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " student.status='0' ";
	//-----------------------------------
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
	} else {
		$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
	}
	//-----------------------------------
	if( $search_name == "0" || $search_name == "" ){
	} else {
		$where.= " AND student.student_name LIKE '%".$search_name."%' ";
	}
	//-----------------------------------
	if( $search_kana == "0" || $search_kana == "" ){
	} else {
		$where.= " AND student.student_name_kana LIKE '%".$search_kana."%' ";
	}
	//-----------------------------------
	if( $search_start_lawyer_number == "0" || $search_start_lawyer_number == "" ){
	} else {
		$where.= " AND student.lawyer_number>='".$search_start_lawyer_number."' ";
	}
	if( $search_end_lawyer_number == "0" || $search_end_lawyer_number == "" ){
	} else {
		$where.= " AND student.lawyer_number<='".$search_end_lawyer_number."' ";
	}
	//-----------------------------------
	if( $search_start_regist_date != "" ){
		$where.= " and student.regist_date>='".$search_start_regist_date."' ";
	}
	if( $search_end_regist_date != "" ){
		$where.= " and student.regist_date<='".substr($search_end_regist_date,0,14)."59:59' ";
	}
	//-----------------------------------
	if( $search_association == "0" || $search_association == "" ){
	} else {
		$where.= " AND student.bar_association_id='".$search_association."' ";
	}
	//-----------------------------------
	$group = "";
	//$group.= " GROUP BY student.student_id,student.student_name,student.school_id ";
	//-----------------------------------
	$order = "";
	if( $search_orderby=="1" ){
		$order = " ORDER BY student.lawyer_number ASC ";
	} elseif( $search_orderby=="2" ){
		$order = " ORDER BY student.lawyer_number DESC ";
	} else {
		$order = " ORDER BY student.lawyer_number ASC ";
	}
	//-----------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$group);
	//var_dump($sql.$where.$order.$group);
	//exit();
	if( 0<count($ret) ){
	} else {
		header("Location: /index.php");
		exit();
	}
} else {
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "student.student_id, ";
	$sql.= "student.student_name, ";
	$sql.= "student.student_email, ";
	$sql.= "student.lawyer_number, ";
	$sql.= "student.regist_date, ";
	$sql.= "student.presence_passport, ";
	$sql.= "student.sub_auth_ethic_training, ";
	$sql.= "mtb_bar_association.name as association_name ";//弁護士会
	$sql.= "FROM ";
	$sql.= "student ";
	$sql.= "LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " student.status='0' ";
	$where.= " AND student.student_id='".$sid."'";
	//-----------------------------------
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
	} else {
		$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
	}
	//-----------------------------------
	$arr_student = $objDbConnect->query_fetch_arr($sql.$where);

	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.order_id, ";//ID
	$sql.= "tbl_order.order_no, ";//ID
	$sql.= "tbl_order_detail.order_detail_id, ";//ID
	$sql.= "tbl_order_detail.product_id, ";//商品ID
	$sql.= "tbl_product.product_code AS product_code_TP, ";//商品コード
	$sql.= "tbl_product.product_name AS product_name_TP, ";//商品名
	$sql.= "tbl_product_add.product_type_add, ";//商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
	$sql.= "tbl_order_detail.pay_total, ";//金額
	$sql.= "tbl_order_detail.product_code AS product_code_TOD, ";//商品コード
	$sql.= "tbl_order_detail.product_name AS product_name_TOD, ";//商品名
	$sql.= "tbl_order.create_date, ";//購入日
	$sql.= "tbl_order.payment_type, ";//支払い方法（1：カード　12：銀行振込）
	$sql.= "tbl_order_detail.payment_status, ";//支払い(0:未入金　1:入金待ち　2:入金済み　9:キャンセル)
	$sql.= "tbl_order_detail.receipt_date, ";//入金日
	$sql.= "tbl_order_detail.take_date, ";//取り込み日（権限付与日）
	$sql.= "tbl_order.claim_flg, ";//請求書希望
	$sql.= "tbl_order.web_flg ";//Web購入
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order.order_id=tbl_order_detail.order_id ";
	$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_order_detail.product_id=tbl_product_add.product_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.product_id IS NOT NULL ";
	$where.= " and ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) ";
	$where.= " AND tbl_order.order_no IS NOT NULL AND tbl_order.order_no<>'' ";
	$where.= " and tbl_order_detail.member_id='".$sid."' ";
	if( $search_orderby=="1" ){
		$order = " ORDER BY tbl_order_detail.payment_status ASC ";
	} elseif( $search_orderby=="2" ){
		$order = " ORDER BY tbl_order_detail.payment_status DESC ";
	} else {
		$order = " ORDER BY tbl_order_detail.order_id DESC ";
	}
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
	$all_total = 0;
	for($i=0;$i<count($ret);$i++){
		$all_total += $ret[$i]["pay_total"];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($ret);
//exit();
if( $sid == "" ){
	// CSVヘッダ
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_user_".date("YmdHis").".csv");

	echo mb_convert_encoding("登録番号,氏名,メールアドレス,FP,代替権限,所属弁護士会,\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["lawyer_number"] . '",', "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["student_name"] . '",', "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["student_email"] . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if($ret[$i]["presence_passport"]=="1"){
			echo mb_convert_encoding('"○",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"-",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		if($ret[$i]["sub_auth_ethic_training"]=="1"){
			echo mb_convert_encoding('"○",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"-",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["association_name"] . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		//++++++++++++++++++++
		echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
		//++++++++++++++++++++
	}
	exit();
} else {
	// CSVヘッダ
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_user_".date("YmdHis").".csv");

	echo mb_convert_encoding("登録番号,".$arr_student[0]["lawyer_number"].",\r\n", "SJIS", "UTF-8");
	echo mb_convert_encoding("氏名,".$arr_student[0]["student_name"].",\r\n", "SJIS", "UTF-8");
	echo mb_convert_encoding("登録年月日,".$arr_student[0]["regist_date"].",\r\n", "SJIS", "UTF-8");
	echo mb_convert_encoding("所属弁護士会,".$arr_student[0]["association_name"].",\r\n", "SJIS", "UTF-8");

	echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
	echo mb_convert_encoding("注文ID,商品名,商品コード,入金,購入日,購入価格,申込,決済方法,商品ID\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["order_no"] . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if ($ret[$i]["product_name_TOD"] != ''){
			$product_name = $ret[$i]["product_name_TOD"];
		} else if ($ret[$i]["product_name_TP"] != ''){
			$product_name = $ret[$i]["product_name_TP"];
		} else {
			$product_name = '';
		}
		echo mb_convert_encoding('"' . $product_name . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if ($ret[$i]["product_code_TOD"] != ''){
			$product_code = $ret[$i]["product_code_TOD"];
		} else if ($ret[$i]["product_code_TP"] != ''){
			$product_code = $ret[$i]["product_code_TP"];
		} else {
			$product_code = '';
		}
		echo mb_convert_encoding('"' . $product_code . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if($ret[$i]["payment_status"]=="0"){
			echo mb_convert_encoding('"未",', "SJIS", "UTF-8");
		}elseif($ret[$i]["payment_status"]=="1"){
			echo mb_convert_encoding('"入金待ち",', "SJIS", "UTF-8");
		}elseif($ret[$i]["payment_status"]=="2"){
			echo mb_convert_encoding('"済",', "SJIS", "UTF-8");
		}elseif($ret[$i]["payment_status"]=="3"){
			echo mb_convert_encoding('"一部未入金",', "SJIS", "UTF-8");
		}elseif($ret[$i]["payment_status"]=="9"){
			echo mb_convert_encoding('"キャンセル",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"-",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["create_date"] . '",', "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["pay_total"] . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if($ret[$i]["web_flg"]=="1"){
			echo mb_convert_encoding('"WEB",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"-",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		if($ret[$i]["payment_type"]=="1"){
			echo mb_convert_encoding('"カード",', "SJIS", "UTF-8");
		}elseif($ret[$i]["payment_type"]=="12"){
			echo mb_convert_encoding('"振込",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["product_id"] . '"', "SJIS", "UTF-8");
		//++++++++++++++++++++
		echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
		//++++++++++++++++++++
	}
	exit();
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
