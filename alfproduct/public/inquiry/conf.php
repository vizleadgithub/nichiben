<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
$_SESSION['wp_page_head_title'] = 'お問い合わせ';
$objDbConnect = new DbConnect();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$input_lawyer_number = "";
$input_name = "";
//$input_company = "";
//$input_post = "";
//$input_tel = "";
$input_mail = "";
$input_comment = "";

if( $_SERVER["REQUEST_METHOD"] == "POST" ){
	$input_lawyer_number	 = trim($_POST["input_lawyer_number"]);
	$input_name	 = trim($_POST["input_name"]);
	//$input_company	 = trim($_POST["input_company"]);
	//$input_post	 = trim($_POST["input_post"]);
	//$input_tel	 = trim($_POST["input_tel"]);
	$input_mail	 = trim($_POST["input_mail"]);
	$input_comment	 = trim($_POST["input_comment"]);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ユーザー情報の取得
$user_info = array();
$sql = "
SELECT
  lawyer_number
FROM
  student
WHERE
  status = '0'
  AND student_id = '".$_SESSION['user']['id']."'
";
$user_info = $objDbConnect->query_fetch($sql);
$input_lawyer_number = $user_info["lawyer_number"] ?? '';
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$arr_err = array();
//------------------------------------------------------------
//登録番号
//if (cmCheckInput($input_lawyer_number, "CK_KARA")!=0){
//	$arr_err["input_lawyer_number"] = "登録番号 は必須です。";
//} else {
//}
//氏名
if (cmCheckInput($input_name, "CK_KARA")!=0){
	$arr_err["input_name"] = "氏名 は必須です。";
} else {
}
//------------------------------------------------------------
//会社名
//if (cmCheckInput($input_company, "CK_KARA")!=0){
//	$arr_err["input_company"] = "会社名 は必須です。";
//} else {
//}
//------------------------------------------------------------
//部署名
//if (cmCheckInput($input_post, "CK_KARA")!=0){
//} else {
//}
//------------------------------------------------------------
//お電話番号（半角）
//if (cmCheckInput($input_tel, "CK_KARA")!=0){
//	$arr_err["input_tel"] = "お電話番号（半角） は必須です。";
//} else {
//}
//------------------------------------------------------------
//メールアドレス
if (cmCheckInput($input_mail, "CK_KARA")!=0){
	$arr_err["input_mail"] = "メールアドレス は必須です。";
} else {
}
//------------------------------------------------------------
//お問い合わせ内容
//if (cmCheckInput($input_comment, "CK_KARA")!=0){
//	$arr_err["input_comment"] = "お問い合わせ内容 は必須です。";
//} else {
//}
//------------------------------------------------------------
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
$template = new Template();

$template->assign('arr_err',		 $arr_err);

$template->assign('input_lawyer_number',		 $input_lawyer_number);
$template->assign('input_name',		 $input_name);
//$template->assign('input_company',	 $input_company);
//$template->assign('input_post',		 $input_post);
//$template->assign('input_tel',		 $input_tel);
$template->assign('input_mail',		 $input_mail);
$template->assign('input_comment',	 $input_comment);

if(0<count($arr_err)){
	$template->layout('inquiry/form.tpl');
} else {
	$template->layout('inquiry/conf.tpl');
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
