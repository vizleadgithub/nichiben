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
$gid = $_GET['gid'];

$search_student_name = "";
$search_lawyer_number = "";
$search_student_email = "";
$search_bar_association_id = "";
if( isset($_SESSION["scontents.search_student_name"]) && !empty($_SESSION["scontents.search_student_name"]) ){
	$search_student_name = $_SESSION["scontents.search_student_name"];
}
if( isset($_SESSION["scontents.search_lawyer_number"]) && !empty($_SESSION["scontents.search_lawyer_number"]) ){
	$search_lawyer_number = $_SESSION["scontents.search_lawyer_number"];
}
if( isset($_SESSION["scontents.search_student_email"]) && !empty($_SESSION["scontents.search_student_email"]) ){
	$search_student_email = $_SESSION["scontents.search_student_email"];
}
if( isset($_SESSION["scontents.search_bar_association_id"]) && !empty($_SESSION["scontents.search_bar_association_id"]) ){
	$search_bar_association_id = $_SESSION["scontents.search_bar_association_id"];
}

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_student_name = trim($_POST["search_student_name"]);
	$search_lawyer_number = trim($_POST["search_lawyer_number"]);
	$search_student_email = trim($_POST["search_student_email"]);
	$search_bar_association_id = trim($_POST["search_bar_association_id"]);

	$_SESSION["scontents.search_student_name"] = $search_student_name;
	$_SESSION["scontents.search_lawyer_number"] = $search_lawyer_number;
	$_SESSION["scontents.search_student_email"] = $search_student_email;
	$_SESSION["scontents.search_bar_association_id"] = $search_bar_association_id;

	$_SESSION["scontents.page"] = 1;
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$page = 1;
if( isset($_SESSION["scontents.page"]) && !empty($_SESSION["scontents.page"]) ){
	$page = $_SESSION["scontents.page"];
}
if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
	$page = $_GET["page"];
	$_SESSION["scontents.page"] = $page;
}
$objAdminPager->setNowPage( $page );
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++



/***********
取得条件は確認する
************/
$sql = "select count(*) as c from student where status=0 ";



$where = "";
if( $search_student_name != "" ){
	$where.= " and ( student_name like '%".$search_student_name."%' ) ";
}
if( $search_lawyer_number != "" ){
	$where.= " and ( lawyer_number = '".$search_lawyer_number."' ) ";
}
if( $search_student_email != "" ){
	$where.= " and ( student_email like '%".$search_student_email."%' ) ";
}
if( $search_bar_association_id != "" ){
	$where.= " and ( bar_association_id = '".$search_bar_association_id."' ) ";
}

$ret = $objDbConnect->query_fetch($sql.$where);
//$objAdminPager->setPageMax(1);
$objAdminPager->setListMax($ret["c"]);
$objAdminPager->setPagerUrl("?gid=".$gid."&page=");
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = " ORDER BY lawyer_number DESC ";

/***********
取得条件は確認する
************/
$sql = "select * from student where status=0 ";
//echo $sql.$where.$order.$offset;
$ret = $objDbConnect->query_fetch_arr($sql.$where.$order.$offset);
for($i=0;$i<count($ret);$i++){
	$ret[$i]["student_name"] = "[".$ret[$i]["lawyer_number"]."]".$ret[$i]["student_name"];
}

$sql = "select * from mtb_bar_association ORDER BY rank ASC ";
$bar_association_list = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("講師受講者検索");
$template->admin_comment("講師受講者を検索します。");

$template->assign('page', $page);
$template->assign('search_student_name', $search_student_name);
$template->assign('search_lawyer_number', $search_lawyer_number);
$template->assign('search_student_email', $search_student_email);
$template->assign('search_bar_association_id', $search_bar_association_id);

$template->assign('pager', $pager);
$template->assign('arr_list', $ret);
$template->assign('bar_association_list', $bar_association_list);
$template->assign('gid', $gid);

if (empty($_POST)){
	if( isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) ){
		$disp_flg = true;
	} else {
		$disp_flg = false;
	}
} else {
	$disp_flg = true;
}
$template->assign('disp_flg', $disp_flg);

$template->assign('page_name', 'product');
$template->admin_layout('product/search_student.tpl', true);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>