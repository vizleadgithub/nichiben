<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>パスワードを忘れた方へ</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
.btn input:hover{
cursor:pointer;
opacity:0.7;
filter: alpha(opacity=70);        /* ie lt 8 */
-ms-filter: "alpha(opacity=70)";  /* ie 8 */
-moz-opacity:0.7;                 /* FF lt 1.5, Netscape */
-khtml-opacity: 0.7;              /* Safari 1.x */
}
	</style>
</head>
<body style="color:#999999;font-family: 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'メイリオ', Meiryo, Osaka, 'ＭＳ Ｐゴシック', 'MS PGothic', sans-serif;">
<div style="padding:10px; margin:10px; font-weight:bold; font-size:18px; background-color:#CEE6F6;">ひみつの質問</div>
<form name="remineder_form" method="post" action="conf.php">
	<!--{foreach from=$arr_err item="err"}-->
	<div class="error" style="font-size:12px; padding:10px;"><!--{$err}--></div>
	<!--{/foreach}-->
<div style="padding:20px;">
	<input type="hidden" name="input_mail" value="<!--{$input_mail|escape}-->">
	<!--{$input_question|escape}--></br>
	<input type="text" name="input_answer" value="<!--{$input_answer|escape}-->"><br>
</div>
	<div style="text-align:center" class="btn"><input type="submit" name="btn_submit" value="送信" style="background: url(/img/btn/submit.png)no-repeat ;width:200px;height:45px;border:0px;text-indent: -9999px;margin:10px auto;"></div>
</form>

</body>
</html>
