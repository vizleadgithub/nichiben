<?php /* Smarty version 2.6.27, created on 2013-06-17 20:34:35
         compiled from /srv/alfproduct/smarty/templates/smartphone/member/regist_confirm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/member/regist_confirm.tpl', 18, false),)), $this); ?>
<script type="text/javascript">
function pageSubmit(flg){
if(flg == 1){
	document.getElementById("act").value = "back";
} else if(flg == 2){
	document.getElementById("act").value = "complete";
}
document.form1.action = "regist.php";
document.form1.submit();
}
</script>

<form name="form1" action="#" method="post">
<input type="hidden" id="act" name="act" value="" />
<?php $_from = $this->_tpl_vars['arr_input']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
	<?php if ($this->_tpl_vars['key'] == 'mail_magazine'): ?>
		<?php $_from = $this->_tpl_vars['arr_input']['arr_mail_magazine']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key2'] => $this->_tpl_vars['item2']):
?>
			<input type="hidden" name="mail_magazine[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		<?php endforeach; endif; unset($_from); ?>
	<?php elseif ($this->_tpl_vars['key'] == 'media'): ?>
		<?php $_from = $this->_tpl_vars['arr_input']['arr_media']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key2'] => $this->_tpl_vars['item2']):
?>
			<input type="hidden" name="media[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		<?php endforeach; endif; unset($_from); ?>
	<?php else: ?>
		<input type="hidden" name="<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

<div style="border: solid 1px #47a6d4;padding: 1px;">
<table style="width:100%;" class="member_table">
		<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">氏名</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['name1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['name2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">フリガナ</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['kana1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['kana2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">都道府県</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_pref'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">メールアドレス</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワード</th>
		<td style="padding:5px;">******</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(質問)</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_password_question'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(答え)</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['password_answer'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">職業</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_job'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">業種</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_job_type'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">学校名</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['school_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">学年</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_school_grade'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">年代</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_age'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">性別</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_gender'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">伊藤塾塾生番号</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['student_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	</table>

<hr>

<?php if (! empty ( $this->_tpl_vars['arr_media'] )): ?>
<div style="border: solid 1px #47a6d4;padding: 1px;">
<table style="width:100%;" class="member_table">
		<tr>
		<th colspan="2" style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">当サイトをどのようにお知りになりましたか？(複数回答可)</th>
	</tr>
	<tr>
		<td style="padding:5px;">
			<?php $_from = $this->_tpl_vars['mtb_media']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['media'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['media']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['media']['iteration']++;
?>
				<?php $_from = $this->_tpl_vars['val']['media_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['media_child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['media_child']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val1']):
        $this->_foreach['media_child']['iteration']++;
?>
					<?php $_from = $this->_tpl_vars['arr_media']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val2']):
        $this->_foreach['loop']['iteration']++;
?>
						<?php if ($this->_tpl_vars['val2'] == $this->_tpl_vars['val1']['id']): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['val1']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

							<?php if ($this->_foreach['loop']['iteration'] != $this->_tpl_vars['media_count']): ?>,<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				<?php endforeach; endif; unset($_from); ?>
			<?php endforeach; endif; unset($_from); ?>
		</td>
	</tr>
	
	</table>
</div>

<hr>
<?php endif; ?>

<div style="border: solid 1px #47a6d4;padding: 1px;">
<table style="width:100%;" class="member_table">
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">メールマガジンについて</th>
		<td style="padding:5px;"><?php if ($this->_tpl_vars['arr_input']['mail_magazine_flag'] == 1): ?>受け取る<?php else: ?>受け取らない<?php endif; ?></td>
	</tr>
	<?php if ($this->_tpl_vars['arr_input']['mail_magazine_flag'] == 1): ?>
	<tr>
		<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">受け取るメールマガジン</th>
		<td style="padding:5px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['str_mail_magazine'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
	</tr>
	<?php endif; ?>
</table>
</div>

<div style="border: hidden 0px #ffffff;padding: 1px;">
<table style="width:100%;" class="member_table">
	<tr>
		<td style="text-align:center;padding:5px;">
			<a href="javascript:void(0);"><img src="/img/btn/register.png" alt="登録する" onclick="pageSubmit(2);" /></a>
		</td>
	</tr>
	<tr>
		<td style="text-align:center;padding:5px;">
			<a href="javascript:void(0);"><img src="/img/btn/back.png" alt="戻る" onclick="pageSubmit(1);" /></a>
		</td>
	</tr>
</table>
</div>

</form>