<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
if(!$arr_session){
	header("Location: /?backurl=".$_SERVER['PHP_SELF']);
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_start_date = "";
$search_end_date = "";

if( isset($_SESSION["amount_product.search_start_buy_date"]) && !empty($_SESSION["amount_product.search_start_buy_date"]) ){
	$search_start_buy_date = $_SESSION["amount_product.search_start_buy_date"];
}
if( isset($_SESSION["amount_product.search_end_buy_date"]) && !empty($_SESSION["amount_product.search_end_buy_date"]) ){
	$search_end_buy_date = $_SESSION["amount_product.search_end_buy_date"];
}
if( isset($_SESSION["amount_product.search_monthly"]) && !empty($_SESSION["amount_product.search_monthly"]) ){
	$search_monthly = $_SESSION["amount_product.search_monthly"];
}
if( isset($_SESSION["amount_product.search_product_name"]) && !empty($_SESSION["amount_product.search_product_name"]) ){
	$search_product_name = $_SESSION["amount_product.search_product_name"];
}
if( isset($_SESSION["amount_product.search_product_code"]) && !empty($_SESSION["amount_product.search_product_code"]) ){
	$search_product_code = $_SESSION["amount_product.search_product_code"];
}
if( isset($_SESSION["amount_product.search_product_type_add"]) && !empty($_SESSION["amount_product.search_product_type_add"]) ){
	$search_product_type_add = $_SESSION["amount_product.search_product_type_add"];
}
if( isset($_SESSION["amount_product.search_payment_type"]) && !empty($_SESSION["amount_product.search_payment_type"]) ){
	$search_payment_type = $_SESSION["amount_product.search_payment_type"];
}
if( isset($_SESSION["amount_product.search_claim_flg"]) && !empty($_SESSION["amount_product.search_claim_flg"]) ){
	$search_claim_flg = $_SESSION["amount_product.search_claim_flg"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$all_pay_total = 0;
$all_buy_count = 0;
if( $search_monthly=="" ){
	//----------------------------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " tbl_product.product_id, ";
	$sql.= " tbl_product.product_name, ";
	$sql.= " tbl_product.product_code, ";
	$sql.= " tbl_product_add.product_type_add, ";
	$sql.= " tbl_product_elearning.product_kind_flg, ";
	$sql.= " tbl_product_live_training.training_kind_flg, ";
	$sql.= " tbl_order_detail.pay_total, ";
	$sql.= " tbl_order_detail.payment_status, ";
	$sql.= " COUNT( tbl_order_detail.product_id ) AS buy_count, ";
	$sql.= " SUM( tbl_order_detail.pay_total ) AS all_pay_total ";
	$sql.= "FROM ";
	$sql.= " tbl_product ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
	$sql.= " LEFT JOIN tbl_order_detail ON tbl_product.product_id=tbl_order_detail.product_id ";
	$sql.= " LEFT JOIN student ON student.student_id=tbl_order_detail.member_id ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
	$sql.= " LEFT JOIN tbl_product_elearning ON tbl_product.product_id=tbl_product_elearning.product_id ";
	$sql.= " LEFT JOIN tbl_product_live_training ON tbl_product.product_id=tbl_product_live_training.product_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.payment_status='2' ";
	//-----------------------------------
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
	} else {
		$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
	}
	//----------------------------------------------------------
	if( $search_start_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date>='".$search_start_buy_date."' ";
	}
	//----------------------------------------------------------
	if( $search_end_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date<='".substr($search_end_buy_date,0,14)."59:59' ";
	}
	//----------------------------------------------------------
	if( $search_product_name != "" ){
		$where.= " and tbl_product.product_name LIKE '%".trim($search_product_name)."%' ";
	}
	//----------------------------------------------------------
	if( $search_product_code != "" ){
		$where.= " and tbl_product.product_code LIKE '%".trim($search_product_code)."%' ";
	}
	//----------------------------------------------------------
	$temp_where = "";
	if( !empty($search_product_type_add) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_product_type_add);$i++){
			if($i>0){ $temp_where.= " or "; }
			// 1e-ラーニング
			// 2elライブ
			// 3ライブ実務研修
			// 4倫理研修
			// 5パスポート
			// tbl_product_add.product_type_add 商品種別(1:e-ラーニング 2:会場研修 3:代替倫理研修 4:パスポート)
			// tbl_product_elearning.product_kind_flg 商品種別フラグ（0:その他 1:e-ラーニング 2:e-ライブ）
			// tbl_product_live_training.training_kind_flg 研修種別（0:その他 1:特別研修 2:夏季研修 3:新規登録弁護士研修 4:弁護士会主催研修）
			if($search_product_type_add[$i]=="1"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg<>'2' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="2"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '1' AND tbl_product_elearning.product_kind_flg='2' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="3"){
				//$temp_where.= " ( ";
				//$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg<>'1' ";
				//$temp_where.= " ) ";
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg <> '1' ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="4"){
				//$temp_where.= " ( ";
				//$temp_where.= " (tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg='1') ";
				//$temp_where.= " OR tbl_product_add.product_type_add = '3' ";
				//$temp_where.= " ) ";
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '3' OR ( tbl_product_add.product_type_add = '2' AND tbl_product_live_training.ethic_flg = '1' ) ";
				$temp_where.= " ) ";
			} elseif($search_product_type_add[$i]=="5"){
				$temp_where.= " ( ";
				$temp_where.= " tbl_product_add.product_type_add = '4' ";
				$temp_where.= " ) ";
			}
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( !empty($search_payment_type) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_payment_type);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.payment_type = '".$search_payment_type[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( !empty($search_claim_flg) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_claim_flg);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.claim_flg = '".$search_claim_flg[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$group = "";
	$group.= " GROUP BY ";
	$group.= " tbl_product.product_id, ";
	$group.= " tbl_product.product_name, ";
	$group.= " tbl_product.product_code, ";
	$group.= " tbl_product_add.product_type_add, ";
	$group.= " tbl_order_detail.pay_total, ";
	$group.= " tbl_order_detail.payment_status ";
	//----------------------------------------------------------
	$order = " ORDER BY COUNT( tbl_order_detail.product_id ) DESC,SUM( tbl_order_detail.pay_total ) DESC ";
	//----------------------------------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
	if( !empty($ret) ){
	} else {
		header("Location: /index.php");
		exit();
	}
	for($i=0;$i<count($ret);$i++){
		$all_pay_total += $ret[$i]["all_pay_total"];
		$all_buy_count += $ret[$i]["buy_count"];
	}
} else {
	//----------------------------------------------------------
	$sql = "";
	$sql.= "SELECT ";
	$sql.= " DATE_FORMAT(tbl_order_detail.create_date,'%Y') as buy_y, ";
	$sql.= " DATE_FORMAT(tbl_order_detail.create_date,'%m') as buy_m, ";
	$sql.= " COUNT( tbl_order_detail.product_id ) AS buy_count, ";
	$sql.= " SUM( tbl_order_detail.pay_total ) AS all_pay_total ";
	//$sql.= " tbl_order_detail.product_id, ";
	//$sql.= " tbl_order_detail.pay_total ";
	$sql.= "FROM ";
	$sql.= " tbl_product ";
	$sql.= " LEFT JOIN tbl_product_add ON tbl_product.product_id=tbl_product_add.product_id ";
	$sql.= " LEFT JOIN tbl_order_detail ON tbl_product.product_id=tbl_order_detail.product_id ";
	$sql.= " LEFT JOIN student ON student.student_id=tbl_order_detail.member_id ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";

	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.payment_status='2' ";
	//-----------------------------------
	if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
	} else {
		$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
	}
	//----------------------------------------------------------
	if( $search_start_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date>='".$search_start_buy_date."' ";
	}
	//----------------------------------------------------------
	if( $search_end_buy_date != "" ){
		$where.= " and tbl_order_detail.create_date<='".substr($search_end_buy_date,0,14)."59:59' ";
	}
	//----------------------------------------------------------
	if( $search_product_name != "" ){
		$where.= " and tbl_product.product_name LIKE '%".trim($search_product_name)."%' ";
	}
	//----------------------------------------------------------
	if( $search_product_code != "" ){
		$where.= " and tbl_product.product_code LIKE '%".trim($search_product_code)."%' ";
	}
	//----------------------------------------------------------
	$temp_where = "";
	if( !empty($search_product_type_add) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_product_type_add);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_product_add.product_type_add = '".$search_product_type_add[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( !empty($search_payment_type) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_payment_type);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.payment_type = '".$search_payment_type[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$temp_where = "";
	if( !empty($search_claim_flg) ){
		$temp_where.= " and (";
		for($i=0;$i<count($search_claim_flg);$i++){
			if($i>0){ $temp_where.= " or "; }
			$temp_where.= " ( ";
			$temp_where.= " tbl_order.claim_flg = '".$search_claim_flg[$i]."' ";
			$temp_where.= " ) ";
		}
		$temp_where.= " ) ";
	}
	$where.= $temp_where;
	//----------------------------------------------------------
	$group = "";
	$group.= " GROUP BY ";
	$group.= " DATE_FORMAT(tbl_order_detail.create_date,'%Y'), ";
	$group.= " DATE_FORMAT(tbl_order_detail.create_date,'%m') ";
	//----------------------------------------------------------
	$order = " ORDER BY DATE_FORMAT(tbl_order_detail.create_date,'%Y') DESC,DATE_FORMAT(tbl_order_detail.create_date,'%m') DESC ";
	//----------------------------------------------------------
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
	if( !empty($ret) ){
	} else {
		header("Location: /index.php");
		exit();
	}
	for($i=0;$i<count($ret);$i++){
		$all_pay_total += $ret[$i]["all_pay_total"];
		$all_buy_count += $ret[$i]["buy_count"];
	}
}
//var_dump($ret);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( $search_monthly=="" ){
	//----------------------------------------------------------
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_product_".date("YmdHis").".csv");

	echo mb_convert_encoding("商品ID,商品名,商品コード,販売単価,販売数,合計,\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		echo mb_convert_encoding('"'. $ret[$i]["product_id"]    . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["product_name"]  . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["product_code"]  . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["pay_total"]     . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["buy_count"]     . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding('"'. $ret[$i]["all_pay_total"] . '",',  "SJIS",  "UTF-8");
		echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
	}
	echo mb_convert_encoding(','    ,                              "SJIS", "UTF-8");
	echo mb_convert_encoding(','    ,                              "SJIS", "UTF-8");
	echo mb_convert_encoding(','    ,                              "SJIS", "UTF-8");
	echo mb_convert_encoding('合計,',                              "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $all_buy_count . '",',  "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $all_pay_total . '",',  "SJIS", "UTF-8");
	echo mb_convert_encoding("\r\n",                               "SJIS", "UTF-8");
	//----------------------------------------------------------
} else {
	//----------------------------------------------------------
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_product_monthly_".date("YmdHis").".csv");

	echo mb_convert_encoding("月,販売数,合計,\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		echo mb_convert_encoding('"' . $ret[$i]["buy_y"]."/".$ret[$i]["buy_m"] . '",',  "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["buy_count"] . '",',                    "SJIS", "UTF-8");
		echo mb_convert_encoding('"' . $ret[$i]["all_pay_total"] . '",',                "SJIS", "UTF-8");
		echo mb_convert_encoding("\r\n",                                                "SJIS", "UTF-8");
	}
	echo mb_convert_encoding('合計,',                              "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $all_buy_count . '",',  "SJIS", "UTF-8");
	echo mb_convert_encoding('"' . $all_pay_total . '",',  "SJIS", "UTF-8");
	echo mb_convert_encoding("\r\n",                               "SJIS", "UTF-8");
	//----------------------------------------------------------
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
