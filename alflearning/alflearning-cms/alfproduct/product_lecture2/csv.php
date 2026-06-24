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
/*
$sql = "SELECT";
$sql.= "  T1.term_id,";
$sql.= "  T1.name,";
$sql.= "  T2.parent";
$sql.= " FROM";
$sql.= "  wp_terms AS T1";
$sql.= "   JOIN";
$sql.= "  wp_term_taxonomy AS T2";
$sql.= "   ON T1.term_id = T2.term_id";
$sql.= "";
$sql.= " ORDER BY T1.slug ASC";
$arr_category = $objDbConnect->query_fetch_arr($sql);
//var_dump($arr_category);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_start_date = "";
$search_end_date = "";

//$arr_postdata = unserialize(str_replace('&amp;','&',str_replace('&quot;','"',str_replace('&#039;',"'",str_replace('&apos;',"'",str_replace('&lt;','<',str_replace('&gt;','>',$_GET["data"])))))));
//var_dump($arr_postdata);
//exit();

if( isset($_SESSION["product_lecture_info.search_start_date"]) && !empty($_SESSION["product_lecture_info.search_start_date"]) ){
	$search_start_date = $_SESSION["product_lecture_info.search_start_date"];
}
if( isset($_SESSION["product_lecture_info.search_end_date"]) && !empty($_SESSION["product_lecture_info.search_end_date"]) ){
	$search_end_date = $_SESSION["product_lecture_info.search_end_date"];
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品ID
$pid = '';
if(isset($_REQUEST["pid"])){
	$pid = $_REQUEST["pid"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 弁護士会支部ID
$aid = '';
//$atype = '';
if(isset($_REQUEST["aid"])){
	$aid = $_REQUEST["aid"];
}
//if(isset($_REQUEST["atype"])){
//	$atype = $_REQUEST["atype"];
//}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// CSV種類
$type = '';
if(isset($_REQUEST["type"])){
	$type = $_REQUEST["type"];
}
if (strlen($type) == 0) {
	header("Location: /index.php");
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
$sql = "";
$sql.= "SELECT ";
$sql.= "tbl_order_detail.product_id, ";
$sql.= "tbl_order_detail.sell_price, ";
$sql.= "SUM(tbl_order_detail.unit) as unit, ";
$sql.= "SUM(tbl_order_detail.pay_total) as pay_total, ";
//$sql.= "DATE_FORMAT(tbl_order_detail.create_date,'%Y/%m/%d') as buy_date, ";
$sql.= "tbl_product.product_code, ";
$sql.= "tbl_product.product_name, ";
$sql.= "tbl_product.term_id, ";
$sql.= "student.school_id ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";
$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";

$where = "";
$where.= "WHERE ";
$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
$where.= " and tbl_order_detail.product_id IS NOT NULL ";
$where.= " and tbl_order.payment_status ='2' ";
if( $search_start_date != "" ){
	$where.= " and tbl_order.payment_date>='".$search_start_date."' ";
}
if( $search_end_date != "" ){
	$where.= " and tbl_order.payment_date<='".substr($search_end_date,0,14)."59:59' ";
}
$group = "";
$group.= " GROUP BY tbl_order_detail.product_id,tbl_order_detail.sell_price,tbl_product.product_code,tbl_product.product_name,student.school_id ";
$offset = "";
$order = " ORDER BY SUM(tbl_order_detail.pay_total) DESC,SUM(tbl_order_detail.unit) DESC ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
if( 0<count($ret) ){
} else {
	// header("Location: /index.php");
	// exit();
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//var_dump($ret);
//exit();


// 商品情報（弁護士会）を取得
$arr_input_2 = array();
//$sql = "select MBA.*, RPBA.bar_association_id, RPBA.atype, RPBA.capacity, RPBA.hall, DATE_FORMAT(RPBA.receptionist_start_date, '%Y/%m/%d') AS receptionist_start_date, DATE_FORMAT(RPBA.receptionist_end_date, '%Y/%m/%d') AS receptionist_end_date, RPBA.contents from rel_product_bar_association RPBA INNER JOIN mtb_bar_association MBA ON (RPBA.bar_association_id = MBA.id) where RPBA.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND RPBA.bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$aid)."' AND RPBA.atype = '".mysqli_real_escape_string($objDbConnect->connect,$atype)."' AND RPBA.atype IN (1,2) ORDER BY RPBA.atype, MBA.id";

// 旧データの確認
if ($pid <= 19233) {
	$arr_association_branch_id = array();
	
	$product_id_old = $pid - 10000;
	
	$sql = "
	SELECT
	  bar_association_branch_id
	FROM
	  import_kenshu_count
	WHERE
	  KENSHU_ID = '$product_id_old'
	";
	$res = $objDbConnect->query_fetch_arr($sql);
	if ($res) {
		foreach ($res as $k1 => $v1) {
			$arr_association_branch_id[] = $v1["bar_association_branch_id"];
		}
	}
	
	$sql = "
	SELECT
	  T1.product_name,
	  T1.price,
	  T2.sponsor,
	  DATE_FORMAT(T3.dates, '%Y/%m/%d') AS dates,
	  T3.capacity,
	  T3.web_flg
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_live_training AS T2
	      ON T1.product_id = T2.product_id
	    LEFT JOIN
	  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND bar_association_branch_id IN ('".implode(",", $arr_association_branch_id)."' )) AS T3
	      ON T2.product_id = T3.product_id
	WHERE
	  T1.del_flg = 0
	  and  T1.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' 
	";
} else {
	$sql = "
	SELECT
	  T1.product_name,
	  T1.price,
	  T2.sponsor,
	  DATE_FORMAT(T3.dates, '%Y/%m/%d') AS dates,
	  T3.capacity,
	  T3.web_flg
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_live_training AS T2
	      ON T1.product_id = T2.product_id
	    LEFT JOIN
	  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ) AS T3
	      ON T2.product_id = T3.product_id
	WHERE
	  T1.del_flg = 0
	  /*AND T3.bar_association_branch_id = '".mysqli_real_escape_string($objDbConnect->connect,$aid)."'*/ 
	  and  T1.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' 
	";
}
$arr_input_2 = $objDbConnect->query_fetch($sql);
if (!$arr_input_2) {
print("<!--[".$sql."]-->");
	echo '情報の取得に失敗しました。';
	exit;
} else {
	$sponsor_flg = false;
	if(  strpos( $arr_input_2['sponsor'], "|".$login_bar_association_id."|") === false  ){
	} else {
		$sponsor_flg = true;
	}
	//$template->assign('sponsor_flg', $sponsor_flg);
}




// CSVヘッダ
$pay_total_total = 0;
$unit_total = 0;

header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");

if ($type == 'info') {
	header("Content-Disposition: attachment; filename=product_lecture_info_".date("YmdHis").".csv");
	
	$ret = _get_product_lecture_info($objDbConnect, $pid, $login_bar_association_id, $nichibenren_flg);
	
	echo mb_convert_encoding("弁護士会支部ID,申込期限,WEB受付,弁護士会名/支部名,定員,申込数,受講数,受講率\r\n", "SJIS", "UTF-8");
	foreach ($ret as $val){
		// 各項目の整形
		$disp_web = '';
		if ($val['web_flg'] == 1){
			$disp_web = '○';
		} else {
			$disp_web = '-';
		}
		$disp_bar_association = '';
		$disp_bar_association = $val['bar_association_name'].' '.$val['bar_association_branch_name'];
		$disp_entry = '';
		$disp_entry = $val['entry_number'].'('.$val['entry_number_passport'].')';
		$disp_attend = '';
		$disp_attend = $val['attend_number'].'('.$val['attend_number_passport'].')';
		
		// 一行ずつ書き込み
		$write_data = '';
		$write_data = '"'.$val['bar_association_branch_id'].'",'.'"'.$val['limit_date'].'",'.'"'.$disp_web.'",'.'"'.$disp_bar_association.'",'.'"'.$val['capacity'].'",'.'"'.$disp_entry.'",'.'"'.$disp_attend.'",'.'"'.$val['number_percent'].'"';
		$write_data.= "\r\n";
		echo mb_convert_encoding($write_data, "SJIS", "UTF-8");
	}
	
}
else if ($type == 'info_user') {
	header("Content-Disposition: attachment; filename=product_lecture_info_user_".date("YmdHis").".csv");
	
	$ret = _get_product_lecture_info_user($objDbConnect, $pid, $aid, $arr_session, $nichibenren_flg, $sponsor_flg, $login_bar_association_id);
	
	$tmp_mtb_bar_association = get_mtb_bar_association();
	foreach ($tmp_mtb_bar_association as $key => $val){
		$mtb_bar_association[$key] = $val;
	}
	
	echo mb_convert_encoding("受付日,ステイタス,完了,登録番号,氏名,所属弁護士会\r\n", "SJIS", "UTF-8");
	foreach ($ret as $val){
		// 各項目の整形
		$disp_participation = '';
		if ($val['participation_flg'] == 1){
			$disp_participation = '済';
		} else {
			$disp_participation = '未';
		}
		
		// 一行ずつ書き込み
		$write_data = '';
		$write_data = '"'.$val['create_date'].'",'.'"会場",'.'"'.$disp_participation.'",'.'"'.$val['lawyer_number'].'",'.'"'.$val['student_name'].'","'.$mtb_bar_association[$val['bar_association_id']].'"';
		$write_data.= "\r\n";
		echo mb_convert_encoding($write_data, "SJIS", "UTF-8");
	}
	
}
else if ($type == 'info_user_list') {
	header("Content-Disposition: attachment; filename=product_lecture_info_user_list_".date("YmdHis").".csv");
	
	$ret = _get_product_lecture_info_user($objDbConnect, $pid, $aid, $arr_session, $nichibenren_flg, $sponsor_flg, $login_bar_association_id);
	$product_info = _get_product_info($objDbConnect, $pid, $aid);
	if ($product_info){
		$arr_youbi = array('日','月','火','水','木','金','土');
		echo mb_convert_encoding("研修名：".$product_info['product_name']."\r\n", "SJIS", "UTF-8");
		echo mb_convert_encoding("開催日：".$product_info['dates']."\r\n", "SJIS", "UTF-8");
		echo mb_convert_encoding(date('Y年m月d日')."(".$arr_youbi[date('w')].")[".date('H:i')."]作成\r\n", "SJIS", "UTF-8");
		echo "\r\n";
	}
	
	echo mb_convert_encoding("チェック,登録番号,氏名,FP,ステイタス\r\n", "SJIS", "UTF-8");
	foreach ($ret as $val){
		// 各項目の整形
		//if ($val['payment_status_reserv'] != ''){
		//	$disp_payment = $val['disp_payment_reserv'];
		//} else {
		//	$disp_payment = $val['disp_payment'];
		//}
		$disp_payment = '会場';
		
		// 一行ずつ書き込み
		$write_data = '';
		$write_data = '"",'.'"'.$val['lawyer_number'].'","'.$val['student_name'].'","'.$val['disp_fp'].'","'.$disp_payment.'"';
		$write_data.= "\r\n";
		echo mb_convert_encoding($write_data, "SJIS", "UTF-8");
	}
}
else {
	
}

/*
echo mb_convert_encoding("商品CD,商品名,商品カテゴリ,販売単価,販売数,合計\r\n", "SJIS", "UTF-8");
for($i=0;$i<count($ret);$i++){
	echo mb_convert_encoding('"' . $ret[$i]["product_code"] . '","' . $ret[$i]["product_name"] . '",', "SJIS", "UTF-8");

	$arr_term_id = explode(",",$ret[$i]["term_id"]);
	$str_category = "";
	for($m=0;$m<count($arr_term_id);$m++){
		for($n=0;$n<count($arr_category);$n++){
			if( $arr_category[$n]["term_id"]==$arr_term_id[$m] ){
				$str_category .= $arr_category[$n]["name"].",";
			}
		}
	}
	echo mb_convert_encoding('"' . $str_category . '",', "SJIS", "UTF-8");

	echo mb_convert_encoding('"' . $ret[$i]["sell_price"] . '","' . $ret[$i]["unit"] . '","' . $ret[$i]["pay_total"] . '",' ."\r\n", "SJIS", "UTF-8");
	$pay_total_total += $ret[$i]["pay_total"];
	$unit_total += $ret[$i]["unit"];
}
echo mb_convert_encoding("合計,,,,".$unit_total.",".$pay_total_total."\r\n", "SJIS", "UTF-8");
*/
exit();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/**
 * 主催の弁護士会支部情報を取得
 * ※現状info.phpのsqlと同じ
 */
function _get_product_lecture_info($objDbConnect, $pid, $login_bar_association_id, $nichibenren_flg){
	//$all_entry_number = 0;
	//$all_entry_number_passport = 0;
	//$all_attend_number = 0;
	//$all_attend_number_passport = 0;
	$arr_list = array();
	$sql = "
	SELECT
	  target
	FROM
	  tbl_product_live_training
	WHERE
	  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."'
	 ";
	$res = $objDbConnect->query_fetch($sql);
	if ($res){
		if ($res['target'] != '' && $res['target'] != '||'){
			$str_target = str_replace('|', ',', trim($res['target'], '|'));
		} else {
			$str_target = 0;
		}
		
		// 商品のbar_association_branch_idを取得
		$arr_association_branch_id = array();
		// 旧データの確認
		if ($pid <= 19233) {
			$product_id_old = $pid - 10000;
			
			$sql = "
			SELECT
			  bar_association_branch_id
			FROM
			  import_kenshu_count
			WHERE
			  KENSHU_ID = '$product_id_old'
			";
			$res = $objDbConnect->query_fetch_arr($sql);
			if ($res) {
				foreach ($res as $k1 => $v1) {
					$arr_association_branch_id[] = $v1["bar_association_branch_id"];
				}
			}
			
			$sql = "";
			$sql.= "SELECT";
			$sql.= "  T1.bar_association_branch_id,";
			$sql.= "  T1.bar_association_branch_name,";
			$sql.= "  T2.name AS bar_association_name,";
			$sql.= "  DATE_FORMAT(T3.receptionist_end_date, '%Y/%m/%d') AS limit_date,";
			$sql.= "  T3.web_flg,";
			$sql.= "  T3.capacity";
			$sql.= " FROM";
			$sql.= "  mtb_bar_association_branch AS T1";
			$sql.= "    INNER JOIN";
			$sql.= "  mtb_bar_association AS T2";
			$sql.= "      ON T1.bar_association_id = T2.id";
			$sql.= "    LEFT JOIN";
			$sql.= "  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND bar_association_branch_id IN ('".implode(",", $arr_association_branch_id)."' )) AS T3";
			$sql.= "      ON T1.bar_association_branch_id = T3.bar_association_branch_id";
			$sql.= " ORDER BY";
			$sql.= "  T1.bar_association_branch_id ASC, T1.rank ASC";
			
		} else {
			$sql = "
			SELECT
			  bar_association_branch_id
			FROM
			  rel_product_bar_association_branch
			WHERE
			  product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."'
			";
			$res = $objDbConnect->query_fetch_arr($sql);
			if ($res) {
				foreach ($res as $k1 => $v1) {
					$arr_association_branch_id[] = $v1["bar_association_branch_id"];
				}
			}
			
			$sql = "";
			$sql.= "SELECT";
			$sql.= "  T1.bar_association_branch_id,";
			$sql.= "  T1.bar_association_branch_name,";
			$sql.= "  T2.name AS bar_association_name,";
			$sql.= "  DATE_FORMAT(T3.receptionist_end_date, '%Y/%m/%d') AS limit_date,";
			$sql.= "  T3.web_flg,";
			$sql.= "  T3.capacity";
			$sql.= " FROM";
			$sql.= "  mtb_bar_association_branch AS T1";
			$sql.= "    INNER JOIN";
			$sql.= "  mtb_bar_association AS T2";
			$sql.= "      ON T1.bar_association_id = T2.id";
			$sql.= "    LEFT JOIN";
			$sql.= "  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ) AS T3";
			$sql.= "      ON T1.bar_association_branch_id = T3.bar_association_branch_id";
			$sql.= " WHERE";
			if ($nichibenren_flg){
			$sql.= "  T1.bar_association_id IN ($str_target)";
			} else {
			$sql.= "  T1.bar_association_id = '$login_bar_association_id'";
			}
			$sql.= " ORDER BY";
			$sql.= "  T1.bar_association_branch_id ASC, T1.rank ASC";
			
		}
		$res = $objDbConnect->query_fetch_arr($sql);
		if ($res){
			foreach ($res as $key => $val){
				if (!in_array($val['bar_association_branch_id'] , $arr_association_branch_id)) {
					continue;
				}
				
				$bar_association_branch_id = $val['bar_association_branch_id'];
				
				// 旧システムデータの場合
				if ($pid <= 19233) {
					if ($bar_association_branch_id == '119' || $bar_association_branch_id == '120' || $bar_association_branch_id == '121' || $bar_association_branch_id == '122' || $bar_association_branch_id == '123' || $bar_association_branch_id == '124' || $bar_association_branch_id == '125' || $bar_association_branch_id == '126'){
						$old_pid = $pid - 10000;
						$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
						$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
						if ($res_bar_association_branch_id){
							$bar_association_branch_id = $res_bar_association_branch_id[0]['bar_association_branch_id'];
						} else {
							$bar_association_branch_id = '1';
						}
					}
				}
				
				$arr_list[$bar_association_branch_id]['bar_association_branch_id'] = $val['bar_association_branch_id'];
				$arr_list[$bar_association_branch_id]['bar_association_branch_name'] = $val['bar_association_branch_name'];
				$arr_list[$bar_association_branch_id]['bar_association_name'] = $val['bar_association_name'];
				$arr_list[$bar_association_branch_id]['limit_date'] = $val['limit_date'];
				$arr_list[$bar_association_branch_id]['web_flg'] = $val['web_flg'];
				$arr_list[$bar_association_branch_id]['capacity'] = $val['capacity'];
				$arr_list[$bar_association_branch_id]['entry_number'] = get_entry_number($bar_association_branch_id, $pid);
				$arr_list[$bar_association_branch_id]['entry_number_passport'] = get_entry_number_passport($bar_association_branch_id, $pid);
				$arr_list[$bar_association_branch_id]['attend_number'] = get_attend_number($bar_association_branch_id, $pid);
				$arr_list[$bar_association_branch_id]['attend_number_passport'] = get_attend_number_passport($bar_association_branch_id, $pid);
				if ($arr_list[$bar_association_branch_id]['entry_number'] != 0){
					$arr_list[$bar_association_branch_id]['number_percent'] = $arr_list[$bar_association_branch_id]['attend_number'] / $arr_list[$bar_association_branch_id]['entry_number'] * 100;
				} else {
					$arr_list[$bar_association_branch_id]['number_percent'] = 0;
				}
				// 旧データの確認
				if ($pid <= 19233) {
					$arr_list[$bar_association_branch_id]['kanri_flg'] = true;
				} else {
					if ($arr_list[$bar_association_branch_id]['limit_date']!='' && $arr_list[$bar_association_branch_id]['capacity']!=''){
						$arr_list[$bar_association_branch_id]['kanri_flg'] = true;
					} else {
						$arr_list[$bar_association_branch_id]['kanri_flg'] = false;
					}
				}
				//$all_entry_number += $arr_list[$bar_association_branch_id]['entry_number'];
				//$all_entry_number_passport += $arr_list[$bar_association_branch_id]['entry_number_passport'];
				//$all_attend_number += $arr_list[$bar_association_branch_id]['attend_number'];
				//$all_attend_number_passport += $arr_list[$bar_association_branch_id]['attend_number_passport'];
			}
		}
	}
	
	return $arr_list;
}

/**
 * 主催の弁護士会支部申込ユーザー情報を取得
 * ※現状info_user.phpのsqlと同じ
 */
function _get_product_lecture_info_user($objDbConnect, $pid, $aid, $arr_session, $nichibenren_flg, $sponsor_flg, $login_bar_association_id){
	$arr_list = array();
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "DATE_FORMAT(tbl_order_detail.create_date, '%Y/%m/%d') AS create_date, ";
	$sql.= "tbl_order.order_id, ";
	$sql.= "tbl_order.order_no, ";
	$sql.= "student.student_name, ";
	$sql.= "tbl_order_detail.order_detail_id, ";
	$sql.= "tbl_order_detail.product_id, ";
	$sql.= "tbl_order_detail.pay_total, ";
	$sql.= "tbl_order_detail.payment_status, ";
	$sql.= "tbl_order_detail.participation_flg, ";
	$sql.= "tbl_order.payment_type, ";
	$sql.= "tbl_order.web_flg, ";
	//$sql.= "tbl_order_detail.sell_price, ";
	//$sql.= "SUM(tbl_order_detail.unit) as unit, ";
	//$sql.= "SUM(tbl_order_detail.pay_total) as pay_total, ";
	//$sql.= "DATE_FORMAT(tbl_order_detail.create_date,'%Y/%m/%d') as buy_date, ";
	$sql.= "tbl_product.product_code, ";
	$sql.= "tbl_product.product_name, ";
	$sql.= "student.school_id, ";
	$sql.= "student.lawyer_number, ";
	$sql.= "student.bar_association_id, ";
	$sql.= "tbl_status_change_payment.payment_status AS payment_status_reserv ";
	//$sql.= "RPBAB.web_flg ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$sql.= " LEFT JOIN tbl_product ON tbl_order_detail.product_id=tbl_product.product_id ";
	$sql.= " LEFT JOIN student ON tbl_order_detail.member_id=student.student_id ";
	$sql.= " LEFT JOIN tbl_order ON tbl_order_detail.order_id=tbl_order.order_id ";
	$sql.= " LEFT JOIN tbl_status_change_payment ON tbl_order_detail.order_detail_id=tbl_status_change_payment.order_detail_id ";
	//$sql.= " LEFT JOIN ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '$pid' AND bar_association_branch_id = '$aid' ) AS RPBAB ON tbl_order_detail.bar_association_branch_id=RPBAB.bar_association_branch_id ";

	$where = "";
	$where.= "WHERE ";
	$where.= " student.school_id='".$arr_session["cms_master.login.school_id"]."' ";
	if (!$nichibenren_flg){
		if( !$sponsor_flg ){
			$where.= " and student.bar_association_id='".$login_bar_association_id."' ";
		}
	}
	$where.= " and tbl_order_detail.product_id IS NOT NULL ";
	$where.= " and tbl_order_detail.product_id = '".$pid."'";
	$where.= " and tbl_order_detail.bar_association_branch_id = '".$aid."'";
	$where.= " and tbl_order_detail.product_type_add ='2' ";
	$where.= " and ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 OR tbl_order_detail.payment_status = 3 ) ";
	//$where.= " and tbl_order.payment_status ='2' ";
	$group = "";
	//$group.= " GROUP BY tbl_order_detail.product_id,tbl_order_detail.sell_price,tbl_product.product_code,tbl_product.product_name,student.school_id ";

	$order = " ORDER BY tbl_order_detail.create_date DESC";

//echo $sql.$where.$group.$order;

	$return = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
	if ($return){
		foreach ($return as $key => $val){
			$arr_list[$key] = $val;
			
			//if ($val['participation_flg'] == '1'){
			//	$arr_list[$key]['disp_participation'] = '済';
			//} else {
			//	$arr_list[$key]['disp_participation'] = '未';
			//}
			if ($val['web_flg'] == '1'){
				$arr_list[$key]['disp_web'] = '○';
			} else {
				$arr_list[$key]['disp_web'] = '-';
			}
			// パスポートの場合
			if ($val['payment_type'] == '99'){
				$arr_list[$key]['disp_payment'] = '--';
				$arr_list[$key]['pay_total'] = 0;
				$arr_list[$key]['disp_fp'] = '○';
				
			} else {
				$arr_list[$key]['disp_payment'] = get_str_payment_status_kouza($val['payment_status']);
				$arr_list[$key]['disp_fp'] = '-';
			}
			// 管理者によってステータスが変更されている場合
			if ($val['payment_status_reserv'] != ''){
				$arr_list[$key]['disp_payment_reserv'] = get_str_payment_status_kouza($val['payment_status_reserv']);
			} else {
				$arr_list[$key]['disp_payment_reserv'] = '';
			}
		}
	}
	
	return $arr_list;
}

/**
 * 商品情報の取得
 */
function _get_product_info($objDbConnect, $pid, $aid){
	$sql = "
	SELECT
	  T1.product_name,
	  T1.price,
	  DATE_FORMAT(T3.dates, '%Y/%m/%d') AS dates,
	  T3.capacity,
	  T3.web_flg
	FROM
	  tbl_product AS T1
	    INNER JOIN
	  tbl_product_live_training AS T2
	      ON T1.product_id = T2.product_id
	    LEFT JOIN
	  ( SELECT * FROM rel_product_bar_association_branch WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' ) AS T3
	      ON T2.product_id = T3.product_id
	WHERE
	  T1.del_flg = 0
	  AND T3.bar_association_branch_id = '".mysqli_real_escape_string($objDbConnect->connect,$aid)."'
	 ";
	$arr_input = $objDbConnect->query_fetch($sql);
	if ($arr_input){
		return $arr_input;
	}
	
	return false;
}
?>
