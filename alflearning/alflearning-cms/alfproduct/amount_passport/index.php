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
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (empty($_POST) && empty($_GET)){
	$disp_flg = false;
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_name = "";
$search_start_lawyer_number = "";
$search_end_lawyer_number = "";
$search_association = "";
$search_start_buy_date = "";
$search_end_buy_date = "";
$search_passport_target = array();
$search_payment_status = array();
$search_orderby = "";
if( isset($_GET["search"]) && trim($_GET["search"])=="new" ){
} else {
	if( isset($_SESSION["amount_passport.search_name"]) && !empty($_SESSION["amount_passport.search_name"]) ){
		$search_name = $_SESSION["amount_passport.search_name"];
	}
	if( isset($_SESSION["amount_passport.search_start_lawyer_number"]) && !empty($_SESSION["amount_passport.search_start_lawyer_number"]) ){
		$search_start_lawyer_number = $_SESSION["amount_passport.search_start_lawyer_number"];
	}
	if( isset($_SESSION["amount_passport.search_end_lawyer_number"]) && !empty($_SESSION["amount_passport.search_end_lawyer_number"]) ){
		$search_end_lawyer_number = $_SESSION["amount_passport.search_end_lawyer_number"];
	}
	if( isset($_SESSION["amount_passport.search_association"]) && !empty($_SESSION["amount_passport.search_association"]) ){
		$search_association = $_SESSION["amount_passport.search_association"];
	}
	if( isset($_SESSION["amount_passport.search_start_buy_date"]) && !empty($_SESSION["amount_passport.search_start_buy_date"]) ){
		$search_start_buy_date = $_SESSION["amount_passport.search_start_buy_date"];
	}
	if( isset($_SESSION["amount_passport.search_end_buy_date"]) && !empty($_SESSION["amount_passport.search_end_buy_date"]) ){
		$search_end_buy_date = $_SESSION["amount_passport.search_end_buy_date"];
	}
	if( isset($_SESSION["amount_passport.search_passport_target"]) && !empty($_SESSION["amount_passport.search_passport_target"]) ){
		$search_passport_target = $_SESSION["amount_passport.search_passport_target"];
	}
	if( isset($_SESSION["amount_passport.search_payment_status"]) && !empty($_SESSION["amount_passport.search_payment_status"]) ){
		$search_payment_status = $_SESSION["amount_passport.search_payment_status"];
	}
	if( isset($_SESSION["amount_passport.search_orderby"]) && !empty($_SESSION["amount_passport.search_orderby"]) ){
		$search_orderby = $_SESSION["amount_passport.search_orderby"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_name = trim($_POST["search_name"]);
	$search_start_lawyer_number = trim($_POST["search_start_lawyer_number"]);
	$search_end_lawyer_number = trim($_POST["search_end_lawyer_number"]);
	$search_association = trim($_POST["search_association"]);
	$search_passport_target = $_POST["search_passport_target"];
	$search_payment_status = $_POST["search_payment_status"];
	$search_start_buy_date = trim($_POST["search_start_buy_date"]);
	$search_end_buy_date = trim($_POST["search_end_buy_date"]);
	$search_orderby = trim($_POST["search_orderby"]);
	if( $search_passport_target==null ){
		$search_passport_target = array();
	}
	if( $search_payment_status==null ){
		$search_payment_status = array();
	}
	$_SESSION["amount_passport.search_name"] = $search_name;
	$_SESSION["amount_passport.search_start_lawyer_number"] = $search_start_lawyer_number;
	$_SESSION["amount_passport.search_end_lawyer_number"] = $search_end_lawyer_number;
	$_SESSION["amount_passport.search_association"] = $search_association;
	$_SESSION["amount_passport.search_passport_target"] = $search_passport_target;
	$_SESSION["amount_passport.search_payment_status"] = $search_payment_status;
	$_SESSION["amount_passport.search_start_buy_date"] = $search_start_buy_date;
	$_SESSION["amount_passport.search_end_buy_date"] = $search_end_buy_date;
	$_SESSION["amount_passport.page"] = 1;
	$_GET["page"] = 1;
	$_SESSION["amount_passport.search_orderby"] = $search_orderby;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if (empty($_POST) && empty($_GET)){
	$disp_flg = false;
	$search_name = "";
	$search_start_lawyer_number = "";
	$search_end_lawyer_number = "";
	$search_association = "";
	$search_passport_target = "";
	$search_payment_status = "";
	$search_start_buy_date = "";
	$search_end_buy_date = "";
	$search_orderby = "";
	$search_passport_target = array();
	$search_payment_status = array();
	$_SESSION["amount_passport.search_name"] = $search_name;
	$_SESSION["amount_passport.search_start_lawyer_number"] = $search_start_lawyer_number;
	$_SESSION["amount_passport.search_end_lawyer_number"] = $search_end_lawyer_number;
	$_SESSION["amount_passport.search_association"] = $search_association;
	$_SESSION["amount_passport.search_passport_target"] = $search_passport_target;
	$_SESSION["amount_passport.search_payment_status"] = $search_payment_status;
	$_SESSION["amount_passport.search_start_buy_date"] = $search_start_buy_date;
	$_SESSION["amount_passport.search_end_buy_date"] = $search_end_buy_date;
	$_SESSION["amount_passport.page"] = 1;
	$_SESSION["amount_passport.search_orderby"] = $search_orderby;
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["amount_passport.page"]) && !empty($_SESSION["amount_passport.page"]) ){
	$page = $_SESSION["amount_passport.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_passport.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "SELECT * FROM mtb_bar_association";
$arr_association = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_passport_target = array();
$sql = "SELECT passport_target_id, passport_target_name FROM mtb_passport_target ORDER BY `rank` ASC";
$temp_arr_passport_target = $objDbConnect->query_fetch_arr($sql);
if ($temp_arr_passport_target){
	foreach ($temp_arr_passport_target as $val){
		$arr_passport_target[$val['passport_target_id']] = $val['passport_target_name'];
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_payment_status = array(
				array("id"=>"1",	"name"=>"入金済"),
				array("id"=>"2",	"name"=>"未入金"),
			);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql_c = "";
$sql_c.= " SELECT";
$sql_c.= "   COUNT(tbl_order_detail.order_detail_id) AS c";
$sql_c.= " FROM";
$sql_c.= "   tbl_order_detail";
$sql_c.= "     LEFT JOIN";
$sql_c.= "   tbl_order";
$sql_c.= "       ON tbl_order_detail.order_id = tbl_order.order_id";
$sql_c.= "     LEFT JOIN";
$sql_c.= "   tbl_product";
$sql_c.= "       ON tbl_order_detail.product_id = tbl_product.product_id";
$sql_c.= "     LEFT JOIN";
$sql_c.= "   tbl_product_passport";
$sql_c.= "       ON tbl_order_detail.product_id = tbl_product_passport.product_id";
$sql_c.= "     LEFT JOIN";
$sql_c.= "   student";
$sql_c.= "       ON tbl_order_detail.member_id = student.student_id";
$sql_c.= "     LEFT JOIN";
$sql_c.= "   mtb_bar_association";
$sql_c.= "       ON student.bar_association_id = mtb_bar_association.id";

$sql = "";
$sql.= " SELECT";
$sql.= "   tbl_order_detail.product_name AS product_name_TOD,";
$sql.= "   tbl_order_detail.payment_status,";
$sql.= "   tbl_order_detail.pay_total,";
$sql.= "   tbl_order.order_date,";
$sql.= "   tbl_product.product_name AS product_name_TP,";
$sql.= "   tbl_product_passport.passport_target,";
$sql.= "   student.lawyer_number,";
$sql.= "   student.student_name,";
$sql.= "   mtb_bar_association.name AS association_name";
$sql.= " FROM";
$sql.= "   tbl_order_detail";
$sql.= "     LEFT JOIN";
$sql.= "   tbl_order";
$sql.= "       ON tbl_order_detail.order_id = tbl_order.order_id";
$sql.= "     LEFT JOIN";
$sql.= "   tbl_product";
$sql.= "       ON tbl_order_detail.product_id = tbl_product.product_id";
$sql.= "     LEFT JOIN";
$sql.= "   tbl_product_passport";
$sql.= "       ON tbl_order_detail.product_id = tbl_product_passport.product_id";
$sql.= "     LEFT JOIN";
$sql.= "   student";
$sql.= "       ON tbl_order_detail.member_id = student.student_id";
$sql.= "     LEFT JOIN";
$sql.= "   mtb_bar_association";
$sql.= "       ON student.bar_association_id = mtb_bar_association.id";

$where = "";
$where.= " WHERE";
$where.= "   tbl_order_detail.product_type_add = 4";
$where.= "   AND tbl_order_detail.payment_status IN(1,2)";
$where.= "   AND tbl_order_detail.product_id IS NOT NULL";
$where.= "   AND tbl_order.payment_status <> 9";
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
	$where.= " AND student.lawyer_number >= CAST(".$search_start_lawyer_number." AS SIGNED) ";
}
if( $search_end_lawyer_number == "0" || $search_end_lawyer_number == "" ){
} else {
	$where.= " AND student.lawyer_number <= CAST(".$search_end_lawyer_number." AS SIGNED) ";
}
//-----------------------------------
if( $search_association == "0" || $search_association == "" ){
} else {
	$where.= " AND student.bar_association_id='".$search_association."' ";
}
//-----------------------------------
if( $search_start_buy_date != "" ){
	$where.= " AND tbl_order.order_date >= '".$search_start_buy_date."' ";
}
if( $search_end_buy_date != "" ){
	$where.= " AND tbl_order.order_date <= '".substr($search_end_buy_date,0,14)."59:59' ";
}
//-----------------------------------
$temp_where = "";
if( 0<count($search_passport_target) ){
	$temp_where.= " AND (";
	for($i=0;$i<count($search_passport_target);$i++){
		if($i>0){ $temp_where.= " OR "; }
		$temp_where.= " ( ";
		$temp_where.= " tbl_product_passport.passport_target LIKE '%|" . $search_passport_target[$i] . "|%' ";
		$temp_where.= " ) ";
	}
	$temp_where.= " ) ";
}
$where.= $temp_where;
//-----------------------------------
$temp_where = "";
if( 0<count($search_payment_status) ){
	$temp_where.= " AND (";
	for($i=0;$i<count($search_payment_status);$i++){
		if($i>0){ $temp_where.= " OR "; }
		if($search_payment_status[$i]=="1"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_order_detail.payment_status = '2' ";
			$temp_where.= " ) ";
		}
		if($search_payment_status[$i]=="2"){
			$temp_where.= " ( ";
			$temp_where.= " tbl_order_detail.payment_status = '1' ";
			$temp_where.= " ) ";
		}
	}
	$temp_where.= " ) ";
}
$where.= $temp_where;
//-----------------------------------
$group = "";
//-----------------------------------
$order = "";
if( $search_orderby=="1" ){
	$order = " ORDER BY student.lawyer_number ASC ";
} elseif( $search_orderby=="2" ){
	$order = " ORDER BY student.lawyer_number DESC ";
} elseif( $search_orderby=="3" ){
	$order = " ORDER BY tbl_order_detail.payment_status ASC ";
} elseif( $search_orderby=="4" ){
	$order = " ORDER BY tbl_order_detail.payment_status DESC ";
} else {
	$order = " ORDER BY tbl_order_detail.order_detail_id DESC ";
}
//-----------------------------------
$ret = $objDbConnect->query_fetch_arr($sql_c.$where.$group);
//var_dump($ret);
$objAdminPager->setListMax($ret[0]["c"]);
$objAdminPager->setPagerUrl("?page=");
//$objAdminPager->setPageMax(1);
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
//$order = "";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);

$arr_list = array();
if ($ret){
	foreach ($ret as $key => $val){
		$arr_list[$key] = $val;
		
		$disp_passport_target = '';
		$arr_pt = array();
		if ($arr_list[$key]['passport_target'] != ''){
			$arr_pt = explode('|', trim($arr_list[$key]['passport_target'], '|'));
			foreach ($arr_pt as $val1){
				$disp_passport_target.= $arr_passport_target[$val1] . '<br />';
			}
			$disp_passport_target = rtrim($disp_passport_target, '<br />');
		}
		$arr_list[$key]['disp_passport_target'] = $disp_passport_target;
	}
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template->admin_title("売上集計");
$template->admin_comment("売上を集計します。");
//$template->admin_name($arr_session["cms_master.login.teacher_name"]);
if($arr_session["cms_master.login.teacher_auth"]["school_admin"]==1){
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 管理者");
} else {
	$template->admin_name($arr_session["cms_master.login.teacher_name"]." 講師");
}
$template->admin_school($arr_session["cms_master.login.school_name"]);

$sidemenu_html = '<ul>';
$sidemenu_html.= '
<li><a href="./../amount_order/index.php" style="font-size:13px;">受注履歴</a></li>
<li><a href="./../amount_user/index.php" style="font-size:13px;">会員別売上集計</a></li>
';
//<li class="selected"><a href="./../amount_passport/index.php" style="font-size:13px;">パスポート注文履歴</a></li>
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
$template->assign('arr_passport_target', $arr_passport_target);
$template->assign('arr_payment_status', $arr_payment_status);
//------------------------------------------------------------
$template->assign('arr_list', $arr_list);
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
$template->assign('search_start_lawyer_number', $search_start_lawyer_number);
$template->assign('search_end_lawyer_number', $search_end_lawyer_number);
$template->assign('search_association', $search_association);
$template->assign('search_passport_target', $search_passport_target);
$template->assign('search_payment_status', $search_payment_status);
$template->assign('search_start_buy_date', $search_start_buy_date);
$template->assign('search_end_buy_date', $search_end_buy_date);

$template->assign('search_orderby', $search_orderby);
$template->assign('bar_association_id', $arr_session["cms_master.login.bar_association_id"]);
//------------------------------------------------------------
$template->assign('post_data', serialize($_POST));
//------------------------------------------------------------
$template->assign('page_name', 'amount_passport');
$template->admin_layout('amount_passport/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
