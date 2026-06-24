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
$template->assign('nichibenren_flg', $nichibenren_flg);
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
$sql = "SELECT product_name FROM tbl_product WHERE product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."'";
$arr_input = $objDbConnect->query_fetch($sql);

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
*/

// 商品情報（弁護士会支部）を取得
$arr_list = array();
$sql = "
SELECT
  T1.student_id,
  T1.product_id,
  DATE_FORMAT(T1.create_date, '%Y/%m/%d') AS create_date,
  T1.status,
  T1.complete_flg,
  T2.student_id,
  T2.student_name,
  T2.lawyer_number,
  T2.bar_association_id,
  T3.status AS status_reserv
FROM
  tbl_ethic_question_history AS T1
    LEFT JOIN
  student AS T2
      ON T1.student_id = T2.student_id
    LEFT JOIN
  tbl_status_change_ethic AS T3
      ON T1.student_id = T3.student_id
         AND T1.product_id = T3.product_id
WHERE
  T1.product_id = '".mysqli_real_escape_string($objDbConnect->connect,$pid)."'
";

$result = $objDbConnect->query_fetch_arr($sql);
if ($result){
	foreach ($result AS $key => $val){
		if (!$nichibenren_flg){
			if ($login_bar_association_id != $val['bar_association_id']){
				continue;
			}
		}
		
		$arr_list[$key] = $val;
		// ステイタス
		$arr_list[$key]['disp_status'] = get_str_ethic_status_kouza($val['status']);
		// 完了
		if ($val['complete_flg'] == 1){
			$arr_list[$key]['disp_complete'] = '済';
		} else {
			$arr_list[$key]['disp_complete'] = '未';
		}
		// 管理者によってステータスが変更されている場合
		if ($val['status_reserv'] != ''){
			$arr_list[$key]['disp_status_reserv'] = get_str_ethic_status_kouza($val['status_reserv']);
		} else {
			$arr_list[$key]['disp_status_reserv'] = '';
		}
	}
}

//var_dump($arr_list);

/*
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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$tmp_mtb_bar_association = get_mtb_bar_association();
foreach ($tmp_mtb_bar_association as $key => $val){
	$mtb_bar_association[$key] = $val;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 削除ID＆削除結果
$oid = '';
if(isset($_GET["oid"])){
	$oid = $_GET["oid"];
}
$odid = '';
if(isset($_GET["odid"])){
	$odid = $_GET["odid"];
}
$res = '';
if(isset($_GET["res"])){
	$res = $_GET["res"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if(!isset($_POST['mode'])){
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	$template->admin_title("講座管理");
	$template->admin_comment("講座の参加情報を管理します。");

	$sidemenu_html ='<ul>
	<li><a href="./../product_lecture/index.php" style="font-size:13px">会場研修申込状況</a></li>
	<li><a href="/alfproduct/product_lecture2/index.php" style="font-size:13px">会場倫理研修状況</a></li>
	<li class="selected"><a href="./../product_lecture_ethics/index.php" style="font-size:13px">倫理代替措置研修状況</a></li>
	</ul>';
	$template->admin_sidemenu($sidemenu_html);

	//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
	if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
		$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
	} else {
		$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
	}
	$template->admin_school($arr_session["cms_master.login.school_name"]);

	$template->assign('pid', $pid);
	$template->assign('arr_input', $arr_input);
	$template->assign('arr_list', $arr_list);
	$template->assign('res', $res);
	$template->assign('mtb_bar_association', $mtb_bar_association);

	$template->assign('page_name', 'product_lecture');
	$template->admin_layout('product_lecture_ethics/info.tpl');
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

}

// 削除
elseif($_POST['mode'] == 'delete') {
	// 生徒ID
	$sid = '';
	if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
	}
	if (strlen($sid) == 0) {
		header('Location: index.php');
		exit;
	}
	
	if (strlen($sid) > 0 && strlen($pid) > 0) {
		$sql = "delete from tbl_ethic_question_history where student_id = '$sid' and product_id = '$pid'";
		$objDbConnect->execute($sql);
		
		header('Location: info.php?pid='.$pid.'&res=success');
		exit;
	}
	else {
		header('Location: info.php?pid='.$pid.'&res=failed');
		exit;
	}
}

// 完了変更
elseif($_POST['mode'] == 'complete') {
	// 生徒ID
	$sid = '';
	if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
	}
	if (strlen($sid) == 0) {
		header('Location: index.php');
		exit;
	}
	
	if (strlen($sid) > 0 && strlen($pid) > 0) {
		if ($_POST['flg'] == 1){
			$sql = "update tbl_ethic_question_history set complete_flg = '0' where student_id = '$sid' AND product_id = '$pid'";
		} else {
			$sql = "update tbl_ethic_question_history set complete_flg = '1' where student_id = '$sid' AND product_id = '$pid'";
		}
		
		if ($sql != ''){
			$objDbConnect->execute($sql);
		}
		
		header('Location: info.php?pid='.$pid.'&res=success');
		exit;
	}
	else {
		header('Location: info.php?pid='.$pid.'&res=failed');
		exit;
	}
}

// ステイタス変更
elseif($_POST['mode'] == 'status') {
	// 生徒ID
	$sid = '';
	if(isset($_GET["sid"])){
		$sid = $_GET["sid"];
	}
	if (strlen($sid) == 0) {
		header('Location: index.php');
		exit;
	}
	
	if (strlen($sid) > 0 && strlen($pid) > 0) {
		$sql = '';
		if ($_POST['flg'] == 6){
			$sql = "replace into tbl_status_change_ethic (student_id, product_id, status) values ('$sid', '$pid', '7')";
		} else if ($_POST['flg'] == 7){
			$sql = "replace into tbl_status_change_ethic (student_id, product_id, status) values ('$sid', '$pid', '6')";
		}
		
		if ($sql != ''){
			$objDbConnect->execute($sql);
		}
		
		header('Location: info.php?pid='.$pid.'&res=success');
		exit;
	}
	else {
		header('Location: info.php?pid='.$pid.'&res=failed');
		exit;
	}
}
?>
