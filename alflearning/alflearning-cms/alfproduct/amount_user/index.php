<?php
//ini_set( 'display_errors', 1 ); 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//include(dirname(__FILE__) ."./../../module/module.php");
include("/srv/alfproduct/module/module.php");
$objDbConnect = new DbConnect();
$objAdminPager = new AdminPager();
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
$search_name = "";
$search_kana = "";
$search_start_lawyer_number = "";
$search_end_lawyer_number = "";
$search_start_regist_date = "";
$search_end_regist_date = "";
$search_association = "";
$search_orderby = "";
if( trim($_GET["search"])=="new" ){
} else {
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
	if( isset($_SESSION["amount_user.search_orderby"]) && !empty($_SESSION["amount_user.search_orderby"]) ){
		$search_orderby = $_SESSION["amount_user.search_orderby"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_name = trim($_POST["search_name"]);
	$search_kana = trim($_POST["search_kana"]);
	$search_start_lawyer_number = trim($_POST["search_start_lawyer_number"]);
	$search_end_lawyer_number = trim($_POST["search_end_lawyer_number"]);
	$search_start_regist_date = trim($_POST["search_start_regist_date"]);
	$search_end_regist_date = trim($_POST["search_end_regist_date"]);
	$search_association = trim($_POST["search_association"]);
	$search_orderby = trim($_POST["search_orderby"]);

	$_SESSION["amount_user.search_name"] = $search_name;
	$_SESSION["amount_user.search_kana"] = $search_kana;
	$_SESSION["amount_user.search_start_lawyer_number"] = $search_start_lawyer_number;
	$_SESSION["amount_user.search_end_lawyer_number"] = $search_end_lawyer_number;
	$_SESSION["amount_user.search_start_regist_date"] = $search_start_regist_date;
	$_SESSION["amount_user.search_end_regist_date"] = $search_end_regist_date;
	$_SESSION["amount_user.search_association"] = $search_association;
	$_SESSION["amount_user.page"] = 1;
	$_GET["page"] = 1;
	$_SESSION["amount_user.search_orderby"] = $search_orderby;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (empty($_POST) && empty($_GET)){
	$disp_flg = false;
	$search_name = "";
	$search_kana = "";
	$search_start_lawyer_number = "";
	$search_end_lawyer_number = "";
	$search_start_regist_date = "";
	$search_end_regist_date = "";
	$search_association = "";
	$search_orderby = "";

	$_SESSION["amount_user.search_name"] = $search_name;
	$_SESSION["amount_user.search_kana"] = $search_kana;
	$_SESSION["amount_user.search_start_lawyer_number"] = $search_start_lawyer_number;
	$_SESSION["amount_user.search_end_lawyer_number"] = $search_end_lawyer_number;
	$_SESSION["amount_user.search_start_regist_date"] = $search_start_regist_date;
	$_SESSION["amount_user.search_end_regist_date"] = $search_end_regist_date;
	$_SESSION["amount_user.search_association"] = $search_association;
	$_SESSION["amount_user.page"] = 1;
	$_SESSION["amount_user.search_orderby"] = $search_orderby;
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["amount_user.page"]) && !empty($_SESSION["amount_user.page"]) ){
	$page = $_SESSION["amount_user.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_user.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($nichibenren_flg){
	$sql = "select * from mtb_bar_association";
} else {
	$sql = "select * from mtb_bar_association where id = $login_bar_association_id";
}
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group);
$objAdminPager->setListMax(count($ret));
$objAdminPager->setPagerUrl("?page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
	$template->admin_name($arr_session["cms_master.login.teacher_name"]);
}

$template->admin_school($arr_session["cms_master.login.school_name"]);

$sidemenu_html = '<ul>';
$sidemenu_html.= '
<li><a href="./../amount_order/index.php" style="font-size:13px;">受注履歴</a></li>
<li class="selected"><a href="./../amount_user/index.php" style="font-size:13px;">会員別売上集計</a></li>
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
$template->assign('arr_association', $arr_association);
//------------------------------------------------------------
$template->assign('arr_list', $ret);
$template->assign('page', $page);
$template->assign('list_max', $objAdminPager->list_max);
$template->assign('list_start', ($objAdminPager->now_page - 1) * $objAdminPager->page_max + 1  );
if(  (($objAdminPager->now_page) * $objAdminPager->page_max)<=($objAdminPager->list_max)  ){
	$template->assign('list_end', (($objAdminPager->now_page) * $objAdminPager->page_max)  );
} else {
	$template->assign('list_end', ($objAdminPager->list_max)  );
}
$template->assign('pager', $pager);
//------------------------------------------------------------
$template->assign('search_name', $search_name);
$template->assign('search_kana', $search_kana);
$template->assign('search_start_lawyer_number', $search_start_lawyer_number);
$template->assign('search_end_lawyer_number', $search_end_lawyer_number);
$template->assign('search_start_regist_date', $search_start_regist_date);
$template->assign('search_end_regist_date', $search_end_regist_date);
$template->assign('search_association', $search_association);

$template->assign('search_orderby', $search_orderby);
//------------------------------------------------------------
$template->assign('post_data', serialize($_POST));
//------------------------------------------------------------
$template->assign('page_name', 'amount_user');
$template->admin_layout('amount_user/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>