<?php /* Smarty version 2.6.31, created on 2025-01-27 05:07:30
         compiled from reminder/form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'reminder/form.tpl', 25, false),)), $this); ?>
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
<div style="padding:10px; margin:10px; font-weight:bold; font-size:18px; background-color:#CEE6F6;">パスワードを忘れた方へ</div>
<p style="font-size:12px; padding:10px;">ご登録時のメールアドレスを入力して「送信する」ボタンをクリックしてください。<br />
※新しくパスワードを発行いたしますので、お忘れになったパスワードはご利用できなくなります。</p>
<form name="remineder_form" method="post" action="question.php">
	<?php $_from = $this->_tpl_vars['arr_err']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['err']):
?>
	<div class="error"><?php echo $this->_tpl_vars['err']; ?>
</div>
	<?php endforeach; endif; unset($_from); ?>
	<div style="padding:20px;">メールアドレス：<input type="text" name="input_mail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"></div>
	<div style="text-align:center" class="btn"><input type="submit" name="btn_submit" value="送信" style="background: url(/img/btn/submit.png)no-repeat ;width:200px;height:45px;border:0px;text-indent: -9999px;margin:10px auto;"></div>
</form>

</body>
</html>