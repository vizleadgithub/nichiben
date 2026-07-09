<?php
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
if(isset($_GET["pid"])){
	$pid = intval($_GET["pid"]);
}
if (strlen($pid) == 0) {
	header('Location: index.php');
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 商品情報を取得
$arr_input = array();
$sql = "select TP.*, DATE_FORMAT(TP.start_date,'%Y/%m/%d %H:%i') as start_date,DATE_FORMAT(TP.end_date,'%Y/%m/%d %H:%i') as end_date,DATE_FORMAT(TPLT.live_start_date,'%Y/%m/%d') as live_start_date, DATE_FORMAT(RPBA.dates,'%Y/%m/%d') as dates from ((tbl_product TP LEFT JOIN tbl_product_add TPA ON (TP.product_id = TPA.product_id)) LEFT JOIN tbl_product_live_training TPLT ON (TP.product_id = TPLT.product_id)) LEFT JOIN rel_product_bar_association RPBA ON (TP.product_id = RPBA.product_id) where TP.del_flg=0 ";
$where = ' AND TP.product_id = "'.mysqli_real_escape_string($objDbConnect->connect,$pid).'"';
$group = ' GROUP BY TP.product_id';

// echo "[".$sql.$where.$group."]";

$arr_input = $objDbConnect->query_fetch($sql.$where.$group);

if (!$arr_input) {
	header('Location: index.php');
	exit;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 主催する弁護士会の情報から弁護士会IDを全部を取得する
/*
$arr_bar_association = array();
$sql = "select bar_association_id from rel_product_bar_association where product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND atype IN (1,2) ORDER BY bar_association_id";
$ret = $objDbConnect->query_fetch_arr($sql);
foreach ($ret as $ke => $va) {
	array_push($arr_bar_association, $va['bar_association_id']);
}

// 主催する弁護士会の情報から弁護士会支部IDを全部を取得する
$arr_bar_association_branch = array();
if ($arr_bar_association) {
	$sql = "select bar_association_branch_id from mtb_bar_association_branch where bar_association_id IN (".implode(",", $arr_bar_association).") ORDER BY bar_association_id";
	$ret = $objDbConnect->query_fetch_arr($sql);
	foreach ($ret as $ke => $va) {
		array_push($arr_bar_association_branch, $va['bar_association_branch_id']);
	}
}

// 商品情報（弁護士会支部）を取得
$arr_list = array();
$sql = "select MBAB.*, MBA.*, RPBA.bar_association_id, RPBA.atype, RPBAB.capacity, RPBAB.hall, DATE_FORMAT(RPBAB.receptionist_start_date, '%Y/%m/%d') AS receptionist_start_date, DATE_FORMAT(RPBAB.receptionist_end_date, '%Y/%m/%d') AS receptionist_end_date, RPBAB.contents from ((mtb_bar_association_branch MBAB INNER JOIN mtb_bar_association MBA ON (MBAB.bar_association_id = MBA.id)) LEFT JOIN rel_product_bar_association RPBA ON (MBA.id = RPBA.bar_association_id AND RPBA.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."' AND RPBA.atype IN (1,2))) LEFT JOIN rel_product_bar_association_branch RPBAB ON (MBAB.bar_association_branch_id = RPBAB.bar_association_branch_id AND RPBAB.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."') where MBAB.bar_association_branch_id != 0";
if (count($arr_bar_association) > 0) {
	$sql .= " and MBA.id IN (".implode(",", $arr_bar_association).")";
}
if (count($arr_bar_association_branch) > 0) {
	$sql .= " and MBAB.bar_association_id IN (".implode(",", $arr_bar_association_branch).")";
}
$sql .= " ORDER BY MBA.id, MBAB.bar_association_branch_id";
$arr_list = $objDbConnect->query_fetch_arr($sql);
foreach ($arr_list as $ke => $va) {
	$arr_list[$ke]['aid'] = str_pad($ke + 1, 2, "0", STR_PAD_LEF);

	// 有効の判断
	if (strlen($va['receptionist_end_date']) > 0 && $va['capacity'] > 0) {
		$arr_list[$ke]['yuuko_flg'] = true;
	}
	else {
		$arr_list[$ke]['yuuko_flg'] = false;
	}
}
*/

// 受講対象の弁護士会支部情報を取得
$all_entry_number = 0;
$all_entry_number_passport = 0;
$all_attend_number = 0;
$all_attend_number_passport = 0;
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
			$all_entry_number += $arr_list[$bar_association_branch_id]['entry_number'];
			$all_entry_number_passport += $arr_list[$bar_association_branch_id]['entry_number_passport'];
			$all_attend_number += $arr_list[$bar_association_branch_id]['attend_number'];
			$all_attend_number_passport += $arr_list[$bar_association_branch_id]['attend_number_passport'];
		}
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("講座管理");
$template->admin_comment("講座の参加情報を管理します。");

if ($nichibenren_flg){
$sidemenu_html ='<ul>
<li><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
<li class="selected"><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
<li><a href="/alfproduct/product_lecture_ethics/index.php" style="font-size:13px">倫理代替措置研修状況</a></li>
</ul>';
} else {
$sidemenu_html ='<ul>
<li><a href="/alfproduct/product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
<li class="selected"><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
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
$template->assign('arr_input', $arr_input);
$template->assign('arr_list', $arr_list);

$template->assign('all_entry_number', $all_entry_number);
$template->assign('all_entry_number_passport', $all_entry_number_passport);
$template->assign('all_attend_number', $all_attend_number);
$template->assign('all_attend_number_passport', $all_attend_number_passport);

$template->assign('page_name', 'product_lecture2');
$template->admin_layout('product_lecture2/info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
