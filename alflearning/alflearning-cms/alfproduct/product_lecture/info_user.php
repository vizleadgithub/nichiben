<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: POST');
    exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
//$objAdminPager = new AdminPager();
$template = new Template();
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
// 商品ID
$pid = '';
if(isset($_POST["pid"])){
	$pid = intval( $_POST["pid"] );
}
if (strlen($pid) == 0) {
	header('Location: index.php');
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$fp_fix_flg = false;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報を取得
/*
$arr_input = array();
$sql = "select TP.*, DATE_FORMAT(TP.start_date,'%Y/%m/%d %H:%i') as start_date,DATE_FORMAT(TP.end_date,'%Y/%m/%d %H:%i') as end_date,DATE_FORMAT(TPLT.live_start_date,'%Y/%m/%d') as live_start_date from ((tbl_product TP LEFT JOIN tbl_product_add TPA ON (TP.product_id = TPA.product_id)) LEFT JOIN tbl_product_live_training TPLT ON (TP.product_id = TPLT.product_id)) LEFT JOIN rel_product_bar_association RPBA ON (TP.product_id = RPBA.product_id) where TP.del_flg=0 ";
$where = ' AND TP.product_id = "'.mysqli_real_escape_string($objDbConnect->connect,$pid).'"';
$group = ' GROUP BY TP.product_id';

// echo "[".$sql.$where.$group."]";

$arr_input = $objDbConnect->query_fetch($sql.$where.$group);

if (!$arr_input) {
	header('Location: index.php');
	exit;
}
*/
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 弁護士会支部ID
$aid = '';
//$atype = '';
if(isset($_POST["aid"])){
	$aid = $_POST["aid"];
}
//if(isset($_POST["atype"])){
//	$atype = $_POST["atype"];
//}
//if (strlen($aid) == 0 || strlen($atype) == 0) {
if (strlen($aid) == 0) {
	header('Location: info.php?pid='.$pid);
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報（弁護士会）を取得
$arr_input_2 = array();
//$sql = "select MBA.*, RPBA.bar_association_id, RPBA.atype, RPBA.capacity, RPBA.hall, DATE_FORMAT(RPBA.receptionist_start_date, '%Y/%m/%d') AS receptionist_start_date, DATE_FORMAT(RPBA.receptionist_end_date, '%Y/%m/%d') AS receptionist_end_date, RPBA.contents from rel_product_bar_association RPBA INNER JOIN mtb_bar_association MBA ON (RPBA.bar_association_id = MBA.id) where RPBA.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND RPBA.bar_association_id = '".mysqli_real_escape_string($objDbConnect->connect,$aid)."' AND RPBA.atype = '".mysqli_real_escape_string($objDbConnect->connect,$atype)."' AND RPBA.atype IN (1,2) ORDER BY RPBA.atype, MBA.id";
$sql = "
SELECT
  T1.product_name,
  T1.price,
  T2.sponsor,
  DATE_FORMAT(T3.dates, '%Y/%m/%d') AS dates,
  T3.dates AS kenshu_dates,
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
$arr_input_2 = $objDbConnect->query_fetch($sql);

if (!$arr_input_2) {
	header('Location: info.php?pid='.$pid);
	exit;
} else {
	$sponsor_flg = false;
	if(  strpos( $arr_input_2['sponsor'], "|".$login_bar_association_id."|") === false  ){
	} else {
		$sponsor_flg = true;
	}
	$template->assign('sponsor_flg', $sponsor_flg);
}


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 旧システムデータの場合
if ($pid <= 19233) {
	if ($aid == '119' || $aid == '120' || $aid == '121' || $aid == '122' || $aid == '123' || $aid == '124' || $aid == '125' || $aid == '126'){
		$old_pid = $pid - 10000;
		$sql = "SELECT bar_association_branch_id FROM import_kenshu_count WHERE KENSHU_ID = '$old_pid'";
		$res_bar_association_branch_id = $objDbConnect->query_fetch_arr($sql);
		if ($res_bar_association_branch_id){
			$aid = $res_bar_association_branch_id[0]['bar_association_branch_id'];
		} else {
			$aid = '1';
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$arr_input_2['entry_number'] = get_entry_number2($aid, $pid);
$bar_association_info = get_bar_association_info($aid);
if ($bar_association_info){
	$arr_input_2['bar_association_id'] = $bar_association_info['id'];
	$arr_input_2['bar_association_name'] = $bar_association_info['name'];
	$arr_input_2['bar_association_branch_id'] = $bar_association_info['bar_association_branch_id'];
	$arr_input_2['bar_association_branch_name'] = $bar_association_info['bar_association_branch_name'];
} else {
	$arr_input_2['bar_association_id'] = '';
	$arr_input_2['bar_association_name'] = '';
	$arr_input_2['bar_association_branch_id'] = '';
	$arr_input_2['bar_association_branch_name'] = '';
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp_mtb_bar_association = get_mtb_bar_association();
foreach ($tmp_mtb_bar_association as $key => $val){
	$mtb_bar_association[$key] = $val;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 削除ID＆削除結果
$oid = '';
if(isset($_POST["oid"])){
	$oid = $_POST["oid"];
}
$odid = '';
if(isset($_POST["odid"])){
	$odid = $_POST["odid"];
}
$res = '';
if(isset($_POST["res"])){
	$res = $_POST["res"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// パスポート更新日時取得
$disp_fp_fix_date = '';
$search_aid = '';
if ($aid == ''){
	$search_aid = 1;
} else {
	$search_aid = $aid;
}
$sql = "SELECT fp_fix_date FROM tbl_fp_fix WHERE product_id = '$pid' AND bar_association_branch_id = '$search_aid'";
$res_tbl_fp_fix = $objDbConnect->query_fetch($sql);
if ($res_tbl_fp_fix){
	$disp_fp_fix_date = date('Y年m月d日 H時i分現在', strtotime($res_tbl_fp_fix['fp_fix_date']));
}
$template->assign('disp_fp_fix_date', $disp_fp_fix_date);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 初期表示
if(!isset($_POST['mode'])){
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
	$sql.= "tbl_order_detail.presence_passport_fix, ";
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
	$sql.= "student.presence_passport, ";
	$sql.= "student.exp_date_passport, ";
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

	//$order = " ORDER BY tbl_order_detail.create_date DESC";
	$order = " ORDER BY student.lawyer_number ASC";

//echo $sql.$where.$group.$order;

	$return = $objDbConnect->query_fetch_arr($sql.$where.$group.$order);
	if ($return){
		//$u_kenshu_dates = strtotime($arr_input_2['kenshu_dates']);
		
		// FPを固定しているかどうかのフラグ取得
		$sql = "SELECT fp_fix_flg FROM rel_product_bar_association_branch WHERE product_id = '$pid' AND bar_association_branch_id = '$aid'";
		$arr_rel_product_bar_association_branch = $objDbConnect->query_fetch($sql);
		if ($arr_rel_product_bar_association_branch){
			if ($arr_rel_product_bar_association_branch['fp_fix_flg'] == '1'){
				$fp_fix_flg = true;
			}
		}
		
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
				$arr_list[$key]['disp_payment_color'] = get_str_payment_status_kouza_color($val['payment_status']);
				$arr_list[$key]['disp_fp'] = '-';
			}
			// 管理者によってステータスが変更されている場合
			if ($val['payment_status_reserv'] != ''){
				$arr_list[$key]['disp_payment_reserv'] = get_str_payment_status_kouza($val['payment_status_reserv']);
				$arr_list[$key]['disp_payment_reserv_color'] = get_str_payment_status_kouza_color($val['payment_status_reserv']);
			} else {
				$arr_list[$key]['disp_payment_reserv'] = '';
			}
			// FPの表示項目
			//$arr_list[$key]['disp_fp'] = '-';
			//if ($val['presence_passport'] == '1'){
			//	$u_exp_date_passport = strtotime($val['exp_date_passport']);
			//	if ($u_exp_date_passport > $u_kenshu_dates){
			//		$arr_list[$key]['disp_fp'] = '○';
			//	}
			//}
			// 当日FP or 現在FP
			if ($fp_fix_flg){
				if ($val['presence_passport_fix'] == '1'){
					$arr_list[$key]['disp_fp2'] = '○';
				} else if ($val['presence_passport_fix'] == '2'){
					$arr_list[$key]['disp_fp2'] = '';
				} else {
					$arr_list[$key]['disp_fp2'] = '-';
				}
			} else {
				if ($val['presence_passport'] == '1'){
					$arr_list[$key]['disp_fp2'] = '○';
				} else {
					$arr_list[$key]['disp_fp2'] = '-';
				}
			}
		}
	}
	
	
/*
	$pay_total_total = 0;
	$unit_total = 0;
	for($i=0;$i<count($ret);$i++){
		$pay_total_total += $ret[$i]["pay_total"];
		$unit_total += $ret[$i]["unit"];
	}
*/
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$template->admin_title("講座管理");
	$template->admin_comment("講座の参加情報を管理します。");
	
	if ($nichibenren_flg){
	$sidemenu_html ='<ul>
	<li class="selected"><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
	<li><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
	<li><a href="/alfproduct/product_lecture_ethics/index.php" style="font-size:13px">倫理代替措置研修状況</a></li>
	</ul>';
	} else {
	$sidemenu_html ='<ul>
	<li class="selected"><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
	<li><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
	</ul>';
	}
	$template->admin_sidemenu($sidemenu_html);

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
	$template->admin_name($arr_session["cms_master.login.teacher_name"]);
	}
	$template->admin_school($arr_session["cms_master.login.school_name"]);

	$template->assign('pid', $pid);
	$template->assign('aid', $aid);
	$template->assign('atype', $atype);
	$template->assign('res', $res);
	//$template->assign('arr_input', $arr_input);
	$template->assign('arr_input_2', $arr_input_2);
	$template->assign('arr_list', $arr_list);
	$template->assign('mtb_bar_association', $mtb_bar_association);
	$template->assign('pay_total_total', $pay_total_total);
	$template->assign('unit_total', $unit_total);
	$template->assign('fp_fix_flg', $fp_fix_flg);
	$template->assign('page_name', 'product_lecture');
	$template->admin_layout('product_lecture/info_user.tpl');
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}

// 削除(キャンセル)
elseif($_POST['mode'] == 'delete') {
	if (strlen($odid) > 0) {
		// 削除ロジックを入れる
		$sql = "update tbl_order_detail set payment_status = '9' where order_detail_id = '$odid'";
		$objDbConnect->execute($sql);
		order_status_update($odid);

		//header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&atype='.$atype.'&res=success');
		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=success');
		exit;
	}
	else {
		//header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&atype='.$atype.'&res=failed');
		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=failed');
		exit;
	}
}

// 受講ステータス変更
elseif($_POST['mode'] == 'participation') {
	if (strlen($odid) > 0) {
		if ($_POST['flg'] == 1){
			$sql = "update tbl_order_detail set participation_flg = '0' where order_detail_id = '$odid'";
		} else {
			$sql = "update tbl_order_detail set participation_flg = '1' where order_detail_id = '$odid'";
		}
		$objDbConnect->execute($sql);
		order_status_update($odid);

		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=success');
		exit;
	}
	else {
		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=failed');
		exit;
	}
}

// 支払ステータス変更
elseif($_POST['mode'] == 'status') {
	if (strlen($odid) > 0) {
		$sql = '';
		if ($_POST['flg'] == 1){
			$sql = "replace into tbl_status_change_payment (order_detail_id, payment_status) values ('$odid', '3')";
		} else if ($_POST['flg'] == 2){
			$sql = "replace into tbl_status_change_payment (order_detail_id, payment_status) values ('$odid', '1')";
		} else if ($_POST['flg'] == 3){
			$sql = "replace into tbl_status_change_payment (order_detail_id, payment_status) values ('$odid', '2')";
		}

		if ($sql != ''){
			$objDbConnect->execute($sql);
			order_status_update($odid);
		}

		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=success');
		exit;
	}
	else {
		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=failed');
		exit;
	}
}

// FP固定
elseif($_POST['mode'] == 'fp_fix') {
	// 講座のFP固定フラグ更新
	$sql = "UPDATE rel_product_bar_association_branch SET fp_fix_flg = 1 WHERE product_id = '$pid' AND bar_association_branch_id = '$aid'";
	$objDbConnect->execute($sql);
	
	// 詳細ベースの固定FP更新
	$sql = "SELECT tbl_order_detail.order_detail_id, student.presence_passport FROM tbl_order_detail LEFT JOIN student ON tbl_order_detail.member_id = student.student_id ";
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
	
	$order = " ORDER BY student.lawyer_number ASC";
	
	$ret = $objDbConnect->query_fetch_arr($sql.$where.$order);
	if ($ret){
		foreach ($ret as $val){
			$sql = "UPDATE tbl_order_detail SET presence_passport_fix = '".$val['presence_passport']."' WHERE order_detail_id = '".$val['order_detail_id']."'";
			$objDbConnect->execute($sql);
		}
		
		// 更新日時の更新
		if ($aid == ''){
			$aid = 1;
		}
		$sql = "REPLACE INTO tbl_fp_fix (product_id, bar_association_branch_id, fp_fix_date) VALUES ('$pid', '$aid', '".date('Y-m-d H:i:s')."')";
		$objDbConnect->execute($sql);
		
		header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=success');
		exit;
	}
	
	header('Location: info_user.php?pid='.$pid.'&aid='.$aid.'&res=failed');
	exit;
}


function order_status_update($order_detail_id){
	$objDbConnect = new DbConnect();
	$order_id = "";
	$sql = "";
	$sql.= "SELECT ";
	$sql.= "tbl_order_detail.order_id ";
	$sql.= "FROM ";
	$sql.= "tbl_order_detail ";
	$where = "";
	$where.= "WHERE ";
	$where.= " tbl_order_detail.order_detail_id='".$order_detail_id."' ";
	$arr_temp = $objDbConnect->query_fetch_arr($sql.$where);
	for($i=0;$i<count($arr_temp);$i++){
		$order_id = $arr_temp[$i]["order_id"];
	}

	if($order_id != ""){
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
}
?>
