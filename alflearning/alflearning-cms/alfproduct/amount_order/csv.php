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
$sql = "select * from mtb_bar_association";
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_payment_status = array(
				array("id"=>"1",	"name"=>"未"),
				array("id"=>"2",	"name"=>"済"),
				array("id"=>"3",	"name"=>"一部未入金"),
				array("id"=>"9",	"name"=>"キャンセル"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$oid = "";
$search_name = "";
$search_kana = "";
$search_start_lawyer_number = "";
$search_end_lawyer_number = "";
$search_start_regist_date = "";
$search_end_regist_date = "";
$search_association = "";
$search_start_buy_date = "";
$search_end_buy_date = "";
$search_payment_status = array();
$search_product_code = "";
$search_orderby = "";


//$arr_postdata = unserialize(str_replace('&amp;','&',str_replace('&quot;','"',str_replace('&#039;',"'",str_replace('&apos;',"'",str_replace('&lt;','<',str_replace('&gt;','>',$_GET["data"])))))));
//var_dump($arr_postdata);
//exit();

if( isset($_GET["oid"]) && !empty($_GET["oid"]) ){
	$sid = trim($_GET["oid"]);
}
if( isset($_SESSION["amount_order.search_name"]) && !empty($_SESSION["amount_order.search_name"]) ){
	$search_name = $_SESSION["amount_order.search_name"];
}
if( isset($_SESSION["amount_order.search_kana"]) && !empty($_SESSION["amount_order.search_kana"]) ){
	$search_kana = $_SESSION["amount_order.search_kana"];
}
if( isset($_SESSION["amount_order.search_start_lawyer_number"]) && !empty($_SESSION["amount_order.search_start_lawyer_number"]) ){
	$search_start_lawyer_number = $_SESSION["amount_order.search_start_lawyer_number"];
}
if( isset($_SESSION["amount_order.search_end_lawyer_number"]) && !empty($_SESSION["amount_order.search_end_lawyer_number"]) ){
	$search_end_lawyer_number = $_SESSION["amount_order.search_end_lawyer_number"];
}
if( isset($_SESSION["amount_order.search_start_regist_date"]) && !empty($_SESSION["amount_order.search_start_regist_date"]) ){
	$search_start_regist_date = $_SESSION["amount_order.search_start_regist_date"];
}
if( isset($_SESSION["amount_order.search_end_regist_date"]) && !empty($_SESSION["amount_order.search_end_regist_date"]) ){
	$search_end_regist_date = $_SESSION["amount_order.search_end_regist_date"];
}
if( isset($_SESSION["amount_order.search_association"]) && !empty($_SESSION["amount_order.search_association"]) ){
	$search_association = $_SESSION["amount_order.search_association"];
}
if( isset($_SESSION["amount_order.search_start_buy_date"]) && !empty($_SESSION["amount_order.search_start_buy_date"]) ){
	$search_start_buy_date = $_SESSION["amount_order.search_start_buy_date"];
}
if( isset($_SESSION["amount_order.search_end_buy_date"]) && !empty($_SESSION["amount_order.search_end_buy_date"]) ){
	$search_end_buy_date = $_SESSION["amount_order.search_end_buy_date"];
}
if( isset($_SESSION["amount_order.search_payment_status"]) && !empty($_SESSION["amount_order.search_payment_status"]) ){
	$search_payment_status = $_SESSION["amount_order.search_payment_status"];
}
if( isset($_SESSION["amount_order.search_product_code"]) && !empty($_SESSION["amount_order.search_product_code"]) ){
	$search_product_code = $_SESSION["amount_order.search_product_code"];
}
if( isset($_SESSION["amount_order.search_orderby"]) && !empty($_SESSION["amount_order.search_orderby"]) ){
	$search_orderby = $_SESSION["amount_order.search_orderby"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
if( $oid == "" ){
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order.order_id, ";//注文番号
	$sql.= "tbl_order.order_no, ";//注文番号
	$sql.= "student.student_name, ";//ユーザ名
	$sql.= "student.lawyer_number, ";//登録番号
	$sql.= "mtb_bar_association.name as association_name, ";//弁護士会
	$sql.= "tbl_order_detail.product_id, ";//商品ID
	$sql.= "tbl_order_detail.product_code AS product_code_TOD, ";//商品コード
	$sql.= "tbl_order_detail.product_name AS product_name_TOD, ";//商品名
	$sql.= "tbl_product.product_code AS product_code_TP, ";//商品コード
	$sql.= "tbl_product.product_name AS product_name_TP, ";//商品名
	//$sql.= "tbl_order_detail.payment_status, ";//支払いステータス
	$sql.= "tbl_order.payment_status, ";//支払いステータス
	$sql.= "tbl_order.create_date, ";//購入日
	$sql.= "tbl_order.payment_type, ";//支払い方法（1：カード　12：銀行振込）
	$sql.= "tbl_order.claim_flg, ";//請求書希望
	$sql.= "tbl_order.web_flg ";//Web購入
	$sql.= "FROM ";
	$sql.= "tbl_order ";
	$sql.= "LEFT JOIN tbl_order_detail ON tbl_order.order_id = tbl_order_detail.order_id ";
	$sql.= "LEFT JOIN student ON student.student_id = tbl_order_detail.member_id ";
	$sql.= "LEFT JOIN mtb_bar_association ON mtb_bar_association.id = student.bar_association_id ";
	$sql.= "LEFT JOIN tbl_product ON tbl_order_detail.product_id = tbl_product.product_id ";

	$where = "";
	$where.= "WHERE ";
	$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
	$where.= " and tbl_order_detail.product_id IS NOT NULL ";
	$where.= " and tbl_order.payment_status>='1' ";//支払いステータス
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
	if( $search_start_buy_date != "" ){
		$where.= " and tbl_order.create_date>='".$search_start_buy_date."' ";
	}
	if( $search_end_buy_date != "" ){
		$where.= " and tbl_order.create_date<='".substr($search_end_buy_date,0,14)."59:59' ";
	}
	//-----------------------------------
	$where.= " and tbl_order.payment_status<>'0' ";
	$temp_where = "";
	if( 0<count($search_payment_status) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_payment_status);$i++){
			if($i>0){ $temp_where.= " or "; }
			if($search_payment_status[$i]=="1"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_order.payment_status = '2' ";//完了
				$temp_where.= " ) ";
			}
			if($search_payment_status[$i]=="2"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_order.payment_status = '1' ";//全て未入金
				$temp_where.= " ) ";
			}
			if($search_payment_status[$i]=="3"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_order.payment_status = '3' ";//一部未入金
				$temp_where.= " ) ";
			}
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//-----------------------------------
	if( $search_product_code == "0" || $search_product_code == "" ){
	} else {
		$where.= " AND tbl_product.product_code LIKE '%".$search_product_code."%' ";
	}
	//-----------------------------------
	$group = "";
	/*
	$group.= " GROUP BY student.student_id,student.student_name,student.school_id ";
	*/
	//-----------------------------------
	$order = "";
	if( $search_orderby=="1" ){
		$order = " ORDER BY student.lawyer_number ASC ";
	} elseif( $search_orderby=="2" ){
		$order = " ORDER BY student.lawyer_number DESC ";
	} elseif( $search_orderby=="3" ){
		$order = " ORDER BY tbl_order.payment_status ASC ";
	} elseif( $search_orderby=="4" ){
		$order = " ORDER BY tbl_order.payment_status DESC ";
	} else {
		$order = " ORDER BY tbl_order.order_id DESC ";
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
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($ret);
//exit();
if( $oid == "" ){
	// CSVヘッダ
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_order_".date("YmdHis").".csv");

	echo mb_convert_encoding("決済ID,注文ID,登録番号,氏名,弁護士会,商品コード,商品名,購入日,申込,決済方法,入金,商品ID,請求書\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		//++++++++++++++++++++
		if($ret[$i]["payment_type"]=="1"){
			echo mb_convert_encoding('"' . $ret[$i]["order_id"] . '",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"-",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["order_no"] . '",', "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["lawyer_number"] . '",', "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["student_name"] . '",', "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["association_name"] . '",', "SJIS", "UTF-8");
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
		if ($ret[$i]["product_name_TOD"] != ''){
			$product_name = $ret[$i]["product_name_TOD"];
		} else if ($ret[$i]["product_name_TP"] != ''){
			$product_name = $ret[$i]["product_name_TP"];
		} else {
			$product_name = '';
		}
		echo mb_convert_encoding('"' . $product_name . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["create_date"] . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if($ret[$i]["web_flg"]=="1"){
			echo mb_convert_encoding('"WEB",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"-",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		if($ret[$i]["payment_type"]=="1"){
			echo mb_convert_encoding('"カード",', "SJIS", "UTF-8");
		} elseif($ret[$i]["payment_type"]=="12"){
			echo mb_convert_encoding('"振込",', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"",', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		$str_payment_status = "";
		for($n=0;$n<count($arr_payment_status);$n++){
			if( $arr_payment_status[$n]["id"]==$ret[$i]["payment_status"] ){
				$str_payment_status .= $arr_payment_status[$n]["name"];
			}
		}
		echo mb_convert_encoding('"' . $str_payment_status . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		echo mb_convert_encoding('"' . $ret[$i]["product_id"] . '",', "SJIS", "UTF-8");
		//++++++++++++++++++++
		if($ret[$i]["claim_flg"]=="1"){
			echo mb_convert_encoding('"○"', "SJIS", "UTF-8");
		} else {
			echo mb_convert_encoding('"×"', "SJIS", "UTF-8");
		}
		//++++++++++++++++++++
		echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
		//++++++++++++++++++++
	}
	exit();
} else {
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
