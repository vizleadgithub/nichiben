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
if($login_bar_association_id > 1){
	$sql = "SELECT student_id from student where bar_association_id = " . $login_bar_association_id ;
}else{
	$sql = "SELECT student_id from student";
}

$ret = $objDbConnect->query_fetch_arr($sql);

$all_student = count($ret);

$template->assign('all_student_count', $all_student);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if($login_bar_association_id > 1){
	$sql2 = "SELECT student_id from student where status = 9 and bar_association_id = " . $login_bar_association_id ;
}else{
	$sql2 = "SELECT student_id from student where status = 9";
}
$ret2 = $objDbConnect->query_fetch_arr($sql2);

$del_student = count($ret2);

$template->assign('del_student_count', $del_student);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$before_month = date("Y-m-d H:i:s",strtotime("-1 month"));

if($login_bar_association_id > 1){
	$sql3 = "SELECT student_id from student where update_at >= '" . $before_month . "' and bar_association_id = " . $login_bar_association_id ;
}else{
	$sql3 = "SELECT student_id from student where update_at >= '" . $before_month . "'" ;
}
$ret3 = $objDbConnect->query_fetch_arr($sql3);

$login_student = count($ret3);

$template->assign('month_login_student_count', $login_student);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


$template->admin_title("レポート");
$template->admin_comment("月毎の集計レポートを表示します");
$template->admin_school($arr_session["cms_master.login.school_name"]);

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
	$template->admin_name($arr_session["cms_master.login.teacher_name"].$temp_bar_association_name);
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if ($nichibenren_flg){
$sidemenu_html ='<ul>
<li><a href="/cms_report/cms_video/">コンテンツ*</a></li>
<li><a href="/alfproduct/report_product/index.php">商品</a></li>
<li><a href="/cms_report/cms_user/">ユーザ</a></li>
<li class="selected"><a href="/alfproduct/report_all/index.php">全体確認</a></li>
</ul>';
} else {
$sidemenu_html ='<ul>
<li><a href="/alfproduct/report_product/index.php">商品</a></li>
<li><a href="/cms_report/cms_user/">ユーザ</a></li>
<li class="selected"><a href="/alfproduct/report_all/index.php">全体確認</a></li>
</ul>';
}
$template->admin_sidemenu($sidemenu_html);
$template->admin_school($arr_session["cms_master.login.school_name"]);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//------------------------------------------------------------
$template->assign('post_data', serialize($_POST));
//------------------------------------------------------------

$template->assign('page_name', 'report_all');
$template->admin_layout('report_all/index.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
