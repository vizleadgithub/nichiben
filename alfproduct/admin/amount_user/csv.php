<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
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
$search_member_type = "1";
$search_sex = "1";
$search_age = array();
$search_pref = "";
$search_start_date = "";
$search_end_date = "";

//$arr_postdata = unserialize(str_replace('&amp;','&',str_replace('&quot;','"',str_replace('&#039;',"'",str_replace('&apos;',"'",str_replace('&lt;','<',str_replace('&gt;','>',$_GET["data"])))))));
//var_dump($arr_postdata);
//exit();

if( isset($_GET["sid"]) && !empty($_GET["sid"]) ){
	$sid = trim($_GET["sid"]);
}
if( isset($_SESSION["amount_user.search_member_type"]) && !empty($_SESSION["amount_user.search_member_type"]) ){
	$search_member_type = $_SESSION["amount_user.search_member_type"];
}
if( isset($_SESSION["amount_user.search_sex"]) && !empty($_SESSION["amount_user.search_sex"]) ){
	$search_sex = $_SESSION["amount_user.search_sex"];
}
if( isset($_SESSION["amount_user.search_age"]) && !empty($_SESSION["amount_user.search_age"]) ){
	$search_age = $_SESSION["amount_user.search_age"];
}
if( isset($_SESSION["amount_user.search_pref"]) && !empty($_SESSION["amount_user.search_pref"]) ){
	$search_pref = $_SESSION["amount_user.search_pref"];
}
if( isset($_SESSION["amount_user.search_start_date"]) && !empty($_SESSION["amount_user.search_start_date"]) ){
	$search_start_date = $_SESSION["amount_user.search_start_date"];
}
if( isset($_SESSION["amount_user.search_end_date"]) && !empty($_SESSION["amount_user.search_end_date"]) ){
	$search_end_date = $_SESSION["amount_user.search_end_date"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
if( $sid == "" ){
	$sql.= "SELECT ";
	$sql.= "student.student_id, ";
	$sql.= "student.student_name, ";
	$sql.= "student.school_id, ";
	$sql.= "(YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5)) AS age, ";
	//$sql.= "max(tbl_order_detail.create_date) as buy_date, ";
	$sql.= "tbl_order_detail.create_date as buy_date, ";
	$sql.= "tbl_order_detail.product_id, ";
	$sql.= "tbl_product.product_name ";
	$sql.= "FROM ";
	$sql.= "student ";
	$sql.= " LEFT JOIN tbl_order_detail ON student.student_id=tbl_order_detail.member_id ";
	$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";

	$where = "";
	$where.= "WHERE ";
	$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
	$where.= " and tbl_order_detail.product_id IS NOT NULL ";
	if( $search_start_date != "" ){
		$where.= " and tbl_order_detail.create_date>='".$search_start_date."' ";
	}
	if( $search_end_date != "" ){
		//$where.= " and tbl_order_detail.create_date<='".$search_end_date."' ";
		$where.= " and tbl_order_detail.create_date<='".substr($search_end_date,0,14)."59:59' ";
	}
	if( $search_member_type == "1" || $search_member_type == "" ){
	} elseif( $search_member_type == "2" ){
		$where.= " and student.member_type='1' ";
	} elseif( $search_member_type == "3" ){
		$where.= " and student.member_type='2' ";
	}
	if( $search_sex == "1" || $search_sex == "" ){
	} elseif( $search_sex == "2" ){
		$where.= " and student.sex='1' ";
	} elseif( $search_sex == "3" ){
		$where.= " and student.sex='2' ";
	}
	if( $search_pref == "0" || $search_pref == "" ){
	} else {
		$where.= " and student.pref='".$search_pref."' ";
	}
	if( 0<count($search_age) ){
		$where.= " and (";
		for($i=0;$i<count($search_age);$i++){
			if($i>0){ $where.= " or "; }
			$where.= " ( ";
			if($search_age[$i]=="10"){
				$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=10 ";
				$where.= " and (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))<=19 ";
			} elseif($search_age[$i]=="20"){
				$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=20 ";
				$where.= " and (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))<=29 ";
			} elseif($search_age[$i]=="30"){
				$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=30 ";
				$where.= " and (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))<=39 ";
			} elseif($search_age[$i]=="40"){
				$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=40 ";
				$where.= " and (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))<=49 ";
			} elseif($search_age[$i]=="50"){
				$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=50 ";
				$where.= " and (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))<=59 ";
			//} elseif($search_age[$i]=="60"){
			//	$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=60 ";
			//	$where.= " and (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))<=69 ";
			} elseif($search_age[$i]=="etc"){
				$where.= " (YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5))>=60 ";
			}
			$where.= " ) ";
		}
		$where.= " ) ";
	}
	$group = "";
	//$group.= " GROUP BY student.student_id,student.student_name,student.school_id ";
	$order = "";
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$group);
	if( 0<count($ret) ){
	} else {
		header("Location: /index.php");
		exit();
	}
} else {
	$sql = "";
	$sql.= "select ";
	$sql.= "* ";
	$sql.= ",(YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5)) AS age ";
	$sql.= "from ";
	$sql.= "student ";
	$sql.= "where ";
	$sql.= "student_id='".$sid."'";
	$arr_student = $objDbConnect->query_fetch_arr($sql);
	if( 0<count($arr_student) ){
	} else {
		header("Location: /index.php");
		exit();
	}

	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.*, ";
	$sql.= "tbl_product.product_code, ";
	$sql.= "tbl_product.product_name ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.product_id IS NOT NULL ";
	$where.= " and member_id='".$sid."'";
	$group = "";
	$order = " ORDER BY tbl_order_detail.create_date DESC ";
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
	if( 0<count($ret) ){
	} else {
		header("Location: /index.php");
		exit();
	}

	$sql = "";
	$sql.= "select * from mtb_pref";
	$arr_pref = $objDbConnect->query_fetch_arr($sql);
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

	echo mb_convert_encoding("ID,生徒名,購入商品,購入日,\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		echo mb_convert_encoding('"' . $ret[$i]["student_id"] . '","' . $ret[$i]["student_name"] . '","' . $ret[$i]["product_name"] . '","' . $ret[$i]["buy_date"] . '",' ."\r\n", "SJIS", "UTF-8");
	}
	exit();
} else {
	// CSVヘッダ
	header("Cache-Control: public");
	header("Pragma: public");
	header("Content-Type: text/octet-stream");
	header("Content-Disposition: attachment; filename=amount_user_info_".date("YmdHis").".csv");

	if($arr_student[0]["member_type"]=="1"){
		echo mb_convert_encoding("会員種別,一般会員,\r\n", "SJIS", "UTF-8");
	}elseif($arr_student[0]["member_type"]=="2"){
		echo mb_convert_encoding("会員種別,月額会員,\r\n", "SJIS", "UTF-8");
	}
	echo mb_convert_encoding("生徒名,".$arr_student[0]["student_name"].",\r\n", "SJIS", "UTF-8");
	echo mb_convert_encoding("E-Mail,".$arr_student[0]["student_email"].",\r\n", "SJIS", "UTF-8");
	if($arr_student[0]["sex"]=="1"){
		echo mb_convert_encoding("性別,男性,\r\n", "SJIS", "UTF-8");
	}elseif($arr_student[0]["sex"]=="2"){
		echo mb_convert_encoding("性別,女性,\r\n", "SJIS", "UTF-8");
	}
	echo mb_convert_encoding("年齢,".$arr_student[0]["age"].",\r\n", "SJIS", "UTF-8");
	for ($i=0;$i<count($arr_pref);$i++){
		if( $arr_pref[$i]["id"]==$arr_student[0]["pref"] ){
			echo mb_convert_encoding("都道府県,".$arr_pref[$i]["name"].",\r\n", "SJIS", "UTF-8");
		}
	}

	echo mb_convert_encoding("\r\n", "SJIS", "UTF-8");
	echo mb_convert_encoding("商品コード,商品名,購入価格,購入日,\r\n", "SJIS", "UTF-8");
	for($i=0;$i<count($ret);$i++){
		echo mb_convert_encoding('"' . $ret[$i]["product_code"] . '","' . $ret[$i]["product_name"] . '","' . $ret[$i]["pay_total"] . '","' . $ret[$i]["create_date"] . '",' ."\r\n", "SJIS", "UTF-8");
	}
	exit();
}
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
