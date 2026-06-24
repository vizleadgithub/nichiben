<?php /* Smarty version 2.6.31, created on 2025-01-24 11:07:00
         compiled from /srv/alfproduct/smarty/templates/admin/mailmagazine/form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/mailmagazine/form.tpl', 23, false),)), $this); ?>
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="edit.php"><span>新規登録</span></a>
</div>


<form action="<?php echo $this->_tpl_vars['next_url']; ?>
" accept-charset="utf-8" method="post" name="mailmagazine_form">
	<h2>対象会員内容を入力してください</h2>
	<?php $_from = $this->_tpl_vars['arr_err']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['err']):
?>
	<div class="error"><?php echo $this->_tpl_vars['err']; ?>
</div>
	<?php endforeach; endif; unset($_from); ?>
	<table class="form">
		<?php if ($this->_tpl_vars['mid'] != ""): ?>
		<tr>
			<input type="hidden" name="mid" value="<?php echo $this->_tpl_vars['mid']; ?>
">
			<th style="">ID</th>
			<td style=""><?php echo $this->_tpl_vars['mid']; ?>
</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">弁護士番号</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="start_lawyer_number" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['start_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="start_lawyer_number">&nbsp;～&nbsp;
				<input type="text" name="end_lawyer_number" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['end_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="end_lawyer_number">&nbsp;
			</td>
		</tr>
		<tr>
			<th style="">登録年</th>
			<td style="">
				<input type="text" name="start_regist_date" id="start_date" value="<?php echo $this->_tpl_vars['start_regist_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.mailmagazine_form.start_regist_date.value='';">クリア</a>
				～
				<input type="text" name="end_regist_date" id="end_date" value="<?php echo $this->_tpl_vars['end_regist_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.mailmagazine_form.end_regist_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">所属弁護士会</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<select name="bar_association_id">
					<option value="">----</option>
				<?php $_from = $this->_tpl_vars['arr_bar_association']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['bar_association_id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th style="">会員全員</th>
			<td style="">
				<input type="checkbox" name="post_all" id="post_all" value="1" <?php if ($this->_tpl_vars['post_all'] == '1'): ?>checked=checked<?php endif; ?> onclick="change_flag()" /> 
				<script type="text/javascript">
					change_flag();
					function change_flag() {
						if( document.mailmagazine_form.post_all.checked == true ) {
							document.mailmagazine_form.start_lawyer_number.disabled = "true";
							document.mailmagazine_form.end_lawyer_number.disabled = "true";
							document.mailmagazine_form.start_regist_date.disabled = "true";
							document.mailmagazine_form.end_regist_date.disabled = "true";
							document.mailmagazine_form.bar_association_id.disabled = "true";
						} else {
							document.mailmagazine_form.start_lawyer_number.disabled = "";
							document.mailmagazine_form.end_lawyer_number.disabled = "";
							document.mailmagazine_form.start_regist_date.disabled = "";
							document.mailmagazine_form.end_regist_date.disabled = "";
							document.mailmagazine_form.bar_association_id.disabled = "";
						}
					}
				</script>
			</td>
		</tr>



		<!--
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">会員種別</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="radio" name="member_type" value="1" id="member_type_1"<?php if ($this->_tpl_vars['member_type'] == '1'): ?> checked="checked"<?php endif; ?>><label for="member_type_1">全て</label>&nbsp;
				<input type="radio" name="member_type" value="2" id="member_type_2"<?php if ($this->_tpl_vars['member_type'] == '2'): ?> checked="checked"<?php endif; ?>><label for="member_type_2">一般会員</label>&nbsp;
				<input type="radio" name="member_type" value="3" id="member_type_3"<?php if ($this->_tpl_vars['member_type'] == '3'): ?> checked="checked"<?php endif; ?>><label for="member_type_3">月額会員</label>&nbsp;
			</td>
		</tr>
		<tr>
			<th style="">性別</th>
			<td style="">
				<input type="radio" name="sex" value="1" id="sex_1"<?php if ($this->_tpl_vars['sex'] == '1'): ?> checked="checked"<?php endif; ?>><label for="sex_1">全て</label>&nbsp;
				<input type="radio" name="sex" value="2" id="sex_2"<?php if ($this->_tpl_vars['sex'] == '2'): ?> checked="checked"<?php endif; ?>><label for="sex_2">男性</label>&nbsp;
				<input type="radio" name="sex" value="3" id="sex_3"<?php if ($this->_tpl_vars['sex'] == '3'): ?> checked="checked"<?php endif; ?>><label for="sex_3">女性</label>&nbsp;
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">都道府県</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<select name="pref">
					<option value="">----</option>
				<?php $_from = $this->_tpl_vars['arr_pref']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['pref']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th style="">年代</th>
			<td style="">
				<select name="age">
					<option value="">----</option>
				<?php $_from = $this->_tpl_vars['arr_age']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['age']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th style="">職業</th>
			<td style="">
				<select name="job">
					<option value="">----</option>
				<?php $_from = $this->_tpl_vars['arr_job']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['job']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th style="">業種</th>
			<td style="">
				<select name="job_type">
					<option value="">----</option>
				<?php $_from = $this->_tpl_vars['arr_job_type']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['job_type']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th style="">学年</th>
			<td style="">
				<select name="school_grade">
					<option value="">----</option>
				<?php $_from = $this->_tpl_vars['arr_school_grade']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['school_grade']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>カテゴリ</th>
			<td colspan = "3">
				<?php $_from = $this->_tpl_vars['arr_mailmagazine_category']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
				<input type="checkbox" name="mailmagazine_category[]" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" id="mailmagazine_category_<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if (in_array ( $this->_tpl_vars['row']['id'] , $this->_tpl_vars['mailmagazine_category'] )): ?> checked="checked"<?php endif; ?>><label for="mailmagazine_category_<?php echo $this->_tpl_vars['row']['id']; ?>
"><?php echo $this->_tpl_vars['row']['name']; ?>
</label>&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
		-->



	</table>

	<h2>配信内容を入力してください</h2>
	<table class="form">
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">配信日時</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="submit_datetime" id="submit_datetime" value="<?php echo $this->_tpl_vars['submit_datetime']; ?>
" readonly="" /> 
			</td>
		</tr>
		<tr>
			<th style="">件名</th>
			<td style="">
				<input type="text" name="mail_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['mail_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:600px;">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">本文</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
			生徒名を挿入する場合、「@name@」を入力してください。<br>
			<textarea name="mail_body" style="width:600px;height:300px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['mail_body'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
			</td>
		</tr>
	</table>

	<div class="submit">
		<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
		<a href="javascript:void(0);" onclick="document.mailmagazine_form.submit();" /><img src="/alfproduct/images/btn_confirm.png"></a>
	</div>
</form>
<br />