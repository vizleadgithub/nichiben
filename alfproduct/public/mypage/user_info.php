<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = 'マイページTOP';

if (!st_login_check()){
	header("Location: /login/login.php");
	exit;
}

$objDbConnect = new DbConnect();

$mtb_bar_association = get_mtb_bar_association();

// ユーザー情報の取得
$user_info = array();
$sql = "
SELECT
  student_name,
  lawyer_number,
  DATE_FORMAT(regist_date, '%Y/%m/%d') AS regist_date,
  bar_association_id,
  target_passport,
  presence_passport,
  student_email,
  mailmagazine_flg
FROM
  student
WHERE
  status = '0'
  AND student_id = '".$_SESSION['user']['id']."'
";
$res = $objDbConnect->query_fetch($sql);

// パスポート価格の取得
$passport_price = get_passport_price($objDbConnect, $_SESSION['user']['year']);

$objDbConnect->close();

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$template = new Template();

$template->assign('user_info', $res);
$template->assign('mtb_bar_association', $mtb_bar_association);
$template->assign('passport_price', $passport_price);

$template->layout_noside('mypage/user_info.tpl');
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>