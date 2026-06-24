<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
include(dirname(__FILE__) ."./../../module/module.php");
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
if (cmCheckInput($input_comment, "CK_KARA")!=0){
	$arr_err["input_comment"] = "お問い合わせ内容 は必須です。";
} else {
}
//------------------------------------------------------------
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if(count($arr_err)==0){
	$sql = "";
	$sql.= "INSERT INTO ";
	$sql.= "tbl_inquiry ( ";
	$sql.= " inquiry_lawyer_number, ";
	$sql.= " inquiry_name, ";
	$sql.= " inquiry_mail, ";
	$sql.= " inquiry_comment, ";
	$sql.= " regist_date ";
	$sql.= ") VALUES ( ";
	$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$input_lawyer_number)."', ";
	$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$input_name)."', ";
	$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$input_mail)."', ";
	$sql.= "'".mysqli_real_escape_string($objDbConnect->connect,$input_comment)."', ";
	$sql.= "'".date("Y-m-d H:i:s")."' ";
	$sql.= ") ";
	$ret = $objDbConnect->execute($sql);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
if(count($arr_err)==0){
$to = "KENSHUmaster@nichibenren.or.jp";
$to2 = "KENSHUmaster@nichibenren.or.jp";

$to = "CS@edutec.co.jp";
$to2 = "CS@edutec.co.jp";

// 管理者宛のメールで差出人を送信者のメールアドレスにする(する=1, しない=0)
// する場合は、メール入力欄のname属性の値を「Email」にしてください。例 <input size="30" type="text" name="Email" />
//メーラーなどで返信する場合に便利なので「する」がおすすめです。
$fromAdd = 1;

// 管理者宛に送信されるメールのタイトル（件名）
$sbj = "【日本弁護士連合会】お問い合わせがありました";

// 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
// 送る場合は、メール入力欄のname属性の値を「Email」にしてください。例 <input size="30" type="text" name="Email" />
// また差出人に送るメール本文の文頭に「○○様」と表示さたい場合は名前入力欄のname属性を name="名前"としてください
$remail = 1;

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$resbj = "【日本弁護士連合会】送信ありがとうございました";

//自動返信メールに署名を表示(する=1, しない=0)※管理者宛にも表示されます。
$mailFooterDsp = 0;

//上記で「1」を選択時に表示する署名（FOOTER～FOOTER;の間に記述してください）
$mailSignature = <<< FOOTER

──────────────────────
日本弁護士連合会　広報課
TEL：03-3580-9841（代）
──────────────────────

FOOTER;

/* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
値はシングルクォーテーションで囲んで下さい。複数指定する場合は「,」で区切ってください)*/
$eles = array('ご用件','氏名','会社名','電話番号','メールアドレス');

//--------------------- 任意設定ここまで -----------------------------------

// 文字の置き換え
$string_from = "＼";
$string_to = "ー";

// 管理者宛に届くメールのレイアウトの編集
$body="お問い合わせからメールが届きました\n\n";
$body.="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
$body.="【 登録番号 】 "		.$input_lawyer_number."\n";
$body.="【 氏名 】 "		.$input_name."\n";
//$body.="【 会社名 】 "		.$input_company."\n";
//$body.="【 部署名 】 "		.$input_post."\n";
//$body.="【 お電話番号 】 "	.$input_tel."\n";
$body.="【 メールアドレス 】 "		.$input_mail."\n";
$body.="【 お問い合わせ内容 】 \n".$input_comment."\n";
$body.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
$body.="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
$body.="送信者のIPアドレス：".$_SERVER["REMOTE_ADDR"]."\n";
$body.="送信者のホスト名：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
//$body.="問い合わせのページURL：".$_POST['httpReferer']."\n";
if($mailFooterDsp == 1) $body.= $mailSignature;
//--- レイアウトの編集終了 --->
if($remail == 1) {
//--- 差出人への送信確認メールのレイアウト
if(isset($_POST['名前'])){ $rebody = "{$_POST['名前']} 様\n\n";}

$rebody.="お問い合わせを受け付けました。\n";
$rebody.="お問い合わせに関しては順次回答をさせていただきます。\n";
$rebody.="内容によってはお時間をいただく場合がございますのでご了承ください。\n";
$rebody.="また、講座・講義内容に関してはお答えできませんので、\n";
$rebody.="あらかじめご了承ください。\n\n";
$rebody.="送信内容は以下になります。\n\n";
$rebody.="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
$rebody.="【 登録番号 】 "		.$input_lawyer_number."\n";
$rebody.="【 氏名 】 "		.$input_name."\n";
//$rebody.="【 会社名 】 "		.$input_company."\n";
//$rebody.="【 部署名 】 "		.$input_post."\n";
//$rebody.="【 お電話番号 】 "	.$input_tel."\n";
$rebody.="【 メールアドレス 】 "		.$input_mail."\n";
$rebody.="【 お問い合わせ内容 】 \n".$input_comment."\n";
$rebody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
$rebody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
$rebody.=""."\n";
$rebody.="KENSHUmaster@nichibenren.or.jp からお送りしていますが、このメールアドレスにご返信いただくことはできません。"."\n";
$rebody.="お問い合わせはサイト内『お問い合わせ』迄お願いいたします。"."\n";

if($mailFooterDsp == 1) $rebody.= $mailSignature;
$reto = $input_mail;
$rebody=mb_convert_encoding($rebody,"JIS","utf-8");
$resbj="=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($resbj,"JIS","utf-8"))."?=";
$reheader="From: $to\nReply-To: ".$to2."\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
$reheader="From: $to\nBcc: sano@vizlead.com\nReply-To: ".$to2."\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
}
$body=mb_convert_encoding($body,"JIS","utf-8");
$sbj="=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($sbj,"JIS","utf-8"))."?=";
if($fromAdd == 1) {
  $from = $input_mail;
  $header="From: $from\nReply-To: ".$_POST['Email']."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
  $header="From: $from\nBcc: sano@vizlead.com\nReply-To: ".$_POST['Email']."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
  
  // 年末対応用　後で消して、上のコメントアウトを外す
  //$header="From: $from\nBcc: eikun@alfredcore.com,suetsugu@alfredcore.com,cb_dev@alfredcore.com,aizawa@alfredcore.com,k-sakakura@azb.nttls.co.jp,k-sakakura@azb.nttls.co.jp,ken1@quartz.ocn.ne.jp,mitsu_takeda@icloud.com\nReply-To: ".$_POST['Email']."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
} else {
  $header="Reply-To: ".$to2."\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
  $header="Reply-To: ".$to2."\nBcc: sano@vizlead.com\nContent-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
}

//mail($to2,$sbj,$body,$header);
//if($remail == 1) { mail($reto,$resbj,$rebody,$reheader); }
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$objDbConnect->close();
if(count($arr_err)==0){
	header("Location: end.php");
} else {
	$template = new Template();
	$template->assign('arr_err', $arr_err);

	$template->assign('input_lawyer_number',		 $input_lawyer_number);
	$template->assign('input_name',		 $input_name);
	//$template->assign('input_company',	 $input_company);
	//$template->assign('input_post',		 $input_post);
	//$template->assign('input_tel',		 $input_tel);
	$template->assign('input_mail',		 $input_mail);
	$template->assign('input_comment',	 $input_comment);

	$template->layout('inquiry/form.tpl');
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>
