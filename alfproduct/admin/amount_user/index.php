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
$search_member_type = "1";
$search_sex = "1";
$search_age = array();
$search_pref = "";
$search_start_date = "";
$search_end_date = "";
if( $_GET["search"]=="new" ){
} else {
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
}
if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$search_member_type = trim($_POST["search_member_type"]);
	$search_sex = trim($_POST["search_sex"]);
	$search_age = $_POST["search_age"];
	$search_pref = trim($_POST["search_pref"]);
	$search_start_date = trim($_POST["search_start_date"]);
	$search_end_date = trim($_POST["search_end_date"]);
	$_SESSION["amount_user.search_member_type"] = $search_member_type;
	$_SESSION["amount_user.search_sex"] = $search_sex;
	$_SESSION["amount_user.search_age"] = $search_age;
	$_SESSION["amount_user.search_pref"] = $search_pref;
	$_SESSION["amount_user.search_start_date"] = $search_start_date;
	$_SESSION["amount_user.search_end_date"] = $search_end_date;
	$_SESSION["amount_user.page"] = 1;
}
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
$sql = "select * from mtb_pref";
$arr_pref = $objDbConnect->query_fetch_arr($sql);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$sql = "";
$sql.= "SELECT ";
$sql.= "student.student_id, ";
$sql.= "student.student_name, ";
$sql.= "student.school_id, ";
$sql.= "(YEAR(CURDATE()) - YEAR(student.student_birthday)) - (RIGHT(CURDATE(), 5) < RIGHT(student.student_birthday, 5)) AS age, ";
$sql.= "max(tbl_order_detail.create_date) as buy_date, ";
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
$group.= " GROUP BY student.student_id,student.student_name,student.school_id ";

$ret = $objDbConnect->query_fetch_arr($sql.$where.$group);
$objAdminPager->setListMax(count($ret));
$objAdminPager->setPagerUrl("?page=");
//$objAdminPager->setPageMax(1);
$pager = $objAdminPager->getPager();
$offset = $objAdminPager->getOffset();
$order = "";
$ret = $objDbConnect->query_fetch_arr($sql.$where.$group.$order.$offset);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();
$template->admin_title("売上集計");
$template->admin_comment("売上を集計します。");
$template->admin_name($arr_session["cms_master.login.teacher_name"]);
$template->admin_school($arr_session["cms_master.login.school_name"]);

$sidemenu_html ='<ul>
<li class="selected">
<a href="./../amount_user/index.php">売上集計</a>
</li>
<li>
<a href="./../amount_product/index.php">売上分析</a>
</li>
</ul>';
$template->admin_sidemenu($sidemenu_html);


$template->assign('arr_pref', $arr_pref);

$template->assign('page', $page);
$template->assign('search_member_type', $search_member_type);
$template->assign('search_sex', $search_sex);
$template->assign('search_age', $search_age);
$template->assign('search_pref', $search_pref);
$template->assign('search_start_date', $search_start_date);
$template->assign('search_end_date', $search_end_date);
$template->assign('pager', $pager);
$template->assign('arr_list', $ret);

$template->assign('post_data', serialize($_POST));

$template->assign('page_name', 'amount_user');
$template->admin_layout('amount_user/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
