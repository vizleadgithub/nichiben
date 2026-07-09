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
$member_id = "";
if( isset($_GET["sid"]) && !empty($_GET["sid"]) ){
	$member_id = intval($_GET["sid"]);
}
if($member_id == ""){
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$search_orderby = "";
$page = 1;
if( $_GET["search"]=="new" ){
} else {
	if( isset($_SESSION["amount_user.info.search_orderby"]) && !empty($_SESSION["amount_user.info.search_orderby"]) ){
		$search_orderby = $_SESSION["amount_user.info.search_orderby"];
	}
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_orderby = trim($_POST["search_orderby"]);
	$_SESSION["amount_user.info.search_orderby"] = $search_orderby;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if( isset($_SESSION["amount_user.info.page"]) && !empty($_SESSION["amount_user.info.page"]) ){
	$page = $_SESSION["amount_user.info.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["amount_user.info.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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
$where.= " AND student.student_id='".$member_id."'";
//-----------------------------------
if( $arr_session["cms_master.login.bar_association_id"]=="1" ){
} else {
	$where.= " AND student.bar_association_id = '".$arr_session["cms_master.login.bar_association_id"]."' ";
}
//-----------------------------------
$arr_student = $objDbConnect->query_fetch_arr($sql.$where);
if( 0<count($arr_student) ){
} else {
	header("Location: /index.php");
	exit();
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "count(order_detail_id) as c ";
$sql.= "FROM ";
$sql.= "tbl_order_detail ";
$where = "";
$where.= "WHERE ";
$where.= " tbl_order_detail.product_id IS NOT NULL ";
$where.= " and ( tbl_order_detail.payment_status = 1 OR tbl_order_detail.payment_status = 2 ) ";
$where.= " and tbl_order_detail.member_id='".$member_id."' ";
//-----------------------------------
$group = "";
//-----------------------------------
$order = "";
if( $search_orderby=="1" ){
	$order = " ORDER BY tbl_order_detail.payment_status ASC ";
} elseif( $search_orderby=="2" ){
	$order = " ORDER BY tbl_order_detail.payment_status DESC ";
} else {
	$order = " ORDER BY tbl_order_detail.order_id DESC ";
}
//-----------------------------------
$ret = $objDbConnect->query_fetch($sql.$where.$group);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?sid=".$member_id."&page=");
//$objAdminPager->setPageMax(1);
//$pager = $objAdminPager->getPager();
//$offset = $objAdminPager->getOffset();
$offset = "";
//-----------------------------------
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
$where.= " and tbl_order_detail.member_id='".$member_id."' ";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
$all_total = 0;
for($i=0;$i<count($ret);$i++){
	$all_total += $ret[$i]["pay_total"];
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
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
$template->assign('arr_student', $arr_student[0]);

$template->assign('sid', $member_id);
$template->assign('all_total', $all_total);

$template->assign('page', $page);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('search_orderby', $search_orderby);

$template->assign('page_name', 'amount_user');
$template->admin_layout('amount_user/info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
