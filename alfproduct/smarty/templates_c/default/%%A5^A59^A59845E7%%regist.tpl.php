<?php /* Smarty version 2.6.31, created on 2025-01-25 10:27:13
         compiled from /srv/alfproduct/smarty/templates/default/member/regist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/member/regist.tpl', 14, false),)), $this); ?>
<form name="form1" action="regist.php" method="post">
<input type="hidden" name="act" value="confirm" />


<div style="border: solid 1px #cccccc; margin-bottom:20px;">
<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
		<tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['name1'] )): ?><?php $this->assign('name1_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['name2'] )): ?><?php $this->assign('name2_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<th  style="width:230px;background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">氏名<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<table><tr>
				<td colspan="3">姓<input type="text" name="name1" id="name1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['name1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['name1_style']; ?>
 style="width:40%" />
				&nbsp;
				名<input type="text" name="name2" id="name2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['name2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['name2_style']; ?>
 style="width:40%" /></td>
			</tr></table>
			<?php if (isset ( $this->_tpl_vars['err_msg']['name1'] )): ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['name1']; ?>
</span>
			<?php endif; ?>
			<?php if (isset ( $this->_tpl_vars['err_msg']['name2'] )): ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['name2']; ?>
</span>
			<?php endif; ?>
		</td>
	</tr>
		<tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['kana1'] )): ?><?php $this->assign('kana1_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['kana2'] )): ?><?php $this->assign('kana2_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">フリガナ<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<table><tr>
				<td colspan="3">セイ<input type="text" name="kana1" id="kana1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['kana1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['kana1_style']; ?>
 style="width:40%" />
				&nbsp;
				メイ<input type="text" name="kana2" id="kana2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['kana2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['kana2_style']; ?>
 style="width:40%" /></td>
			</tr></table>
			<?php if (isset ( $this->_tpl_vars['err_msg']['kana1'] )): ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['kana1']; ?>
</span>
			<?php endif; ?>
			<?php if (isset ( $this->_tpl_vars['err_msg']['kana2'] )): ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['kana2']; ?>
</span>
			<?php endif; ?>
		</td>
	</tr>
			<tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['pref_id'] )): ?><?php $this->assign('pref_id_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['address1'] )): ?><?php $this->assign('address1_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['address2'] )): ?><?php $this->assign('address2_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['address3'] )): ?><?php $this->assign('address3_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">住所</th>
		<td style="padding:5px;">
			<div id="japan">
				<table><tr>
					<th>都道府県<span style="color:red;">※必須</span></th>
					<td>
						<select name="pref_id" id="pref_id" <?php echo $this->_tpl_vars['pref_id_style']; ?>
>
						<option value="">選択してください</option> 
						<?php $_from = $this->_tpl_vars['mtb_pref']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['pref_id'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pref_id']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['pref_id']['iteration']++;
?>
						<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['pref_id']['iteration'] == $this->_tpl_vars['arr_input']['pref_id']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
						</select>
						<?php if (isset ( $this->_tpl_vars['err_msg']['pref_id'] )): ?>
						<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['pref_id']; ?>
</span>
						<?php endif; ?>
					</td>
				</tr></table>
			</div>
		</td>
	</tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['email'] )): ?><?php $this->assign('email_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['email_conf'] )): ?><?php $this->assign('email_conf_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">メールアドレス<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<table><tr>
				<td>
					<input type="text" name="email" id="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['email_style']; ?>
 /><br />
				</td>
			</tr><tr>
				<td>
					<input type="text" name="email_conf" id="email_conf" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['email_conf'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['email_conf_style']; ?>
 /><br />
				</td>
			</tr><tr>
				<td>
					確認のため2度入力してください。
					<?php if (isset ( $this->_tpl_vars['err_msg']['email'] )): ?>
						<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['email']; ?>
</span>
					<?php endif; ?>
					<?php if (isset ( $this->_tpl_vars['err_msg']['email_conf'] )): ?>
						<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['email_conf']; ?>
</span>
					<?php endif; ?>
				</td>
			</tr></table>
		</td>
	</tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['password'] )): ?><?php $this->assign('password_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['password_conf'] )): ?><?php $this->assign('password_conf_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">パスワード<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<table><tr>
				<td>
					<input type="password" name="password" id="password" value="<?php echo $this->_tpl_vars['arr_input']['password']; ?>
" <?php echo $this->_tpl_vars['password_style']; ?>
 />
				</td>
			</tr><tr>
				<td>
					半角英数字4～10文字でお願いします。(記号不可)
					<?php if (isset ( $this->_tpl_vars['err_msg']['password'] )): ?>
					<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password']; ?>
</span>
					<?php endif; ?>
				</td>
			</tr><tr>
				<td>
					<input type="password" name="password_conf" id="password_conf" value="<?php echo $this->_tpl_vars['arr_input']['password_conf']; ?>
" <?php echo $this->_tpl_vars['password_conf_style']; ?>
 />
				</td>
			</tr><tr>
				<td>
					確認のため2度入力してください。
					<?php if (isset ( $this->_tpl_vars['err_msg']['password_conf'] )): ?>
					<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password_conf']; ?>
</span>
					<?php endif; ?>
				</td>
			</tr></table>
		</td>
	</tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['password_question'] )): ?><?php $this->assign('password_question_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['password_answer'] )): ?><?php $this->assign('password_answer_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<table><tr>
				<th>質問</th>
				<td>
					<select name="password_question" id="password_question" <?php echo $this->_tpl_vars['password_question_style']; ?>
>
						<option value="">選択してください</option> 
					<?php $_from = $this->_tpl_vars['mtb_password_question']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['password_question'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['password_question']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['password_question']['iteration']++;
?>
					<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['password_question']['iteration'] == $this->_tpl_vars['arr_input']['password_question']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
					<?php endforeach; endif; unset($_from); ?>
					</select>
					<?php if (isset ( $this->_tpl_vars['err_msg']['password_question'] )): ?>
					<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password_question']; ?>
</span>
					<?php endif; ?>
				</td>
			</tr><tr>
				<th>答え</th>
				<td>
					<input type="text" name="password_answer" id="password_answer" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['password_answer'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['password_answer_style']; ?>
 />
					<?php if (isset ( $this->_tpl_vars['err_msg']['password_answer'] )): ?>
					<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password_answer']; ?>
</span>
					<?php endif; ?>
				</td>
			</tr></table>
		</td>
	</tr>
		<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">職業</th>
		<td style="padding:5px;">
			<select name="job" id="job">
			<option value="">選択してください</option> 
			<?php $_from = $this->_tpl_vars['mtb_job']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['job'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['job']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['job']['iteration']++;
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['job']['iteration'] == $this->_tpl_vars['arr_input']['job']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
		</td>
	</tr>
		<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">業種</th>
		<td style="padding:5px;">
			<select name="job_type" id="job_type">
			<option value="">選択してください</option> 
			<?php $_from = $this->_tpl_vars['mtb_job_type']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['job_type'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['job_type']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['job_type']['iteration']++;
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['job_type']['iteration'] == $this->_tpl_vars['arr_input']['job_type']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
		</td>
	</tr>
		<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">学校名</th>
		<td style="padding:5px;">
			<input type="text" name="school_name" id="school_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['school_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
		<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">学年</th>
		<td style="padding:5px;">
			<select name="school_grade" id="school_grade" <?php echo $this->_tpl_vars['school_grade_style']; ?>
>
			<option value="">選択してください</option> 
			<?php $_from = $this->_tpl_vars['mtb_school_grade']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['school_grade'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['school_grade']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['school_grade']['iteration']++;
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['school_grade']['iteration'] == $this->_tpl_vars['arr_input']['school_grade']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
		</td>
	</tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['age'] )): ?><?php $this->assign('age_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">年代<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<select name="age" id="age" <?php echo $this->_tpl_vars['age_style']; ?>
>
			<option value="">選択してください</option> 
			<?php $_from = $this->_tpl_vars['mtb_age']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['age'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['age']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['age']['iteration']++;
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['age']['iteration'] == $this->_tpl_vars['arr_input']['age']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
			<?php if (isset ( $this->_tpl_vars['err_msg']['age'] )): ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['age']; ?>
</span>
			<?php endif; ?>
		</td>
	</tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['gender'] )): ?><?php $this->assign('gender_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">性別<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<?php $_from = $this->_tpl_vars['mtb_gender']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
			<label <?php echo $this->_tpl_vars['gender_style']; ?>
><input type="radio" name="gender" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['val']['id'] == $this->_tpl_vars['arr_input']['gender']): ?>checked<?php endif; ?> /><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>
			<?php endforeach; endif; unset($_from); ?>
			<?php if (isset ( $this->_tpl_vars['err_msg']['gender'] )): ?>
			<?php $this->assign('gender_style', $this->_tpl_vars['err_style']); ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['gender']; ?>
</span>
			<?php endif; ?>
		</td>
	</tr>
		<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">伊藤塾塾生番号</th>
		<td style="padding:5px;">
			<input type="text" name="student_no" id="student_no" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['student_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		</td>
	</tr>
	</table>
</div>


<div style="border: solid 1px #cccccc; margin-bottom:20px;">
<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
		<tr>
		<th colspan="2" style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">当サイトをどのようにお知りになりましたか？(複数回答可)</th>
	</tr>
	<tr>
		<td style="padding:5px;">
			<?php $_from = $this->_tpl_vars['mtb_media']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['media'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['media']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['media']['iteration']++;
?>
				<?php $_from = $this->_tpl_vars['val']['media_child']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['media_child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['media_child']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val1']):
        $this->_foreach['media_child']['iteration']++;
?>
					<?php $this->assign('checked', ''); ?>
					<?php $_from = $this->_tpl_vars['arr_input']['arr_media']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val2']):
        $this->_foreach['loop']['iteration']++;
?>
						<?php if ($this->_tpl_vars['val2'] == $this->_tpl_vars['val1']['id']): ?>
						<?php $this->assign('checked', 'checked'); ?>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
					<div style="width:30%;float:left;"><label><input type="checkbox" name="media[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val1']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"  <?php echo $this->_tpl_vars['checked']; ?>
 /><?php echo ((is_array($_tmp=$this->_tpl_vars['val1']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label></div>
				<?php endforeach; endif; unset($_from); ?>
			<?php endforeach; endif; unset($_from); ?>
		</td>
	</tr>
	
	</table>
</div>



<div style="border: solid 1px #cccccc; margin-bottom:20px;">
<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
		<?php if (isset ( $this->_tpl_vars['err_msg']['mail_magazine_flag'] )): ?><?php $this->assign('mail_magazine_flag_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="width:230px;background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">メールマガジンについて<span style="color:red;">※必須</span></th>
		<td style="padding:5px;">
			<label <?php echo $this->_tpl_vars['mail_magazine_flag_style']; ?>
><input type="radio" name="mail_magazine_flag" value="1" <?php if ($this->_tpl_vars['arr_input']['mail_magazine_flag'] == '1' && $this->_tpl_vars['arr_input']['mail_magazine_flag'] != ""): ?>checked<?php endif; ?> />受け取る</label>&nbsp;
			<label <?php echo $this->_tpl_vars['mail_magazine_flag_style']; ?>
><input type="radio" name="mail_magazine_flag" value="0" <?php if ($this->_tpl_vars['arr_input']['mail_magazine_flag'] == '0' && $this->_tpl_vars['arr_input']['mail_magazine_flag'] != ""): ?>checked<?php endif; ?> />受け取らない</label>
			<?php if (isset ( $this->_tpl_vars['err_msg']['mail_magazine_flag'] )): ?>
			<?php $this->assign('gender_style', $this->_tpl_vars['err_style']); ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['mail_magazine_flag']; ?>
</span>
			<?php endif; ?>
		</td>
	</tr>
		<?php if (isset ( $this->_tpl_vars['err_msg']['mail_magazine'] )): ?><?php $this->assign('mail_magazine_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">受け取るメールマガジン</th>
		<td style="padding:5px;">
		<?php $_from = $this->_tpl_vars['mtb_mailmagazine_category']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
			<?php $this->assign('checked', ''); ?>
			<?php $_from = $this->_tpl_vars['arr_input']['arr_mail_magazine']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val2']):
        $this->_foreach['loop']['iteration']++;
?>
				<?php if ($this->_tpl_vars['val2'] == $this->_tpl_vars['val']['id']): ?>
					<?php $this->assign('checked', 'checked'); ?>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			<div style="width:30%;float:left;"><label><input type="checkbox" name="mail_magazine[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['checked']; ?>
 /><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label></div>
		<?php endforeach; endif; unset($_from); ?>
		</td>
	</tr>
	</table>
</div>

<div style="margin-top:20px;text-align:center;color:#ff0000;">【重要】 会員登録をされる前に、下記ご利用規約をよくお読みください。</div>
<div id="single_main" style="height:300px; overflow-y:scroll;width:690px;border: 1px solid #CCCCCC;margin:5px 0px;padding:20px;">
<h4>はじめに</h4>
<p>本利用規約（付随して策定されるガイドラインその他の細則等を含み以下「本規約」といいます）は、株式会社NTTスマートコネクト（以下当社といいます）が提供する動画配信サービス「On-Tap」、また、これに付随したサービス（これらを総称して以下「本サービス等」といいます）の利用に際して、以下のとおり利用規約を提示し、適用するものとします。</p>
<h4>第１条（規約の適用）</h4>
<p>１　本規約にご同意いただくことによって、本サービス等をご利用いただくことができます。なお、無料で提供しているサービスにつきましては、本規約にご同意いただく手続に代えて実際にご利用いただくことで本規約にご同意いただいたものとみなします。</p>
<p>２　本サービス等のうち、本規約とは別に固有の利用規約が定められたサービス・コンテンツ等がある場合において、本規約は排除され固有の規約が適用されるものとします。ただし、固有の規約において本規約を引用する文言が設けられている場合はこの限りではなく、固有の規約と矛盾・抵触しない範囲において、本規約の規定が準用されます。</p>
<h4>第２条（規約の変更）</h4>
<p>１　当社は、利用者にあらかじめ通知することなく、利用料金の改定を含む、本規約の変更をいつでも行うことができます。ただし、ご利用いただいている利用者に大きな影響を与える場合には、あらかじめ合理的な事前告知期間を設けることとします。</p>
<p>２　前項の変更がなされた場合の本サービス等の利用条件は、当該変更後の本規約によるものとします。</p>
<h4>第３条（当社からの通知）</h4>
<p>１　当社は、本サービス等上での掲示や電子メールの送付、その他当社が適当と判断する方法により、利用者に対し随時必要な事項を通知致します。</p>
<p>２　当社は、利用者が登録したアドレスに電子メールを送信した場合、当該電子メールが不着またはその他の事由のため、利用者が当該電子メールを確認できなかったことにより、利用者に損害または損失が生じたとしても責任を負いません。</p>
<h4>第４条（利用に際して）</h4>
<p>１　利用者は、本サービス等について、各種のデータ、文書、ソフトウェア、映像、音声、写真、画像等について、自己の責任において視聴および閲覧するものとします。</p>
<p>２　利用者は、本規約に同意し自己の責任と費用において本サービス等を利用するために必要な映像端末機器、ソフトウェア、通信機器、ハードウェア等の調達、並びにインターネット接続に必要な契約の締結等の利用者設備として必要なもの全てを用意するものとします。</p>
<h4>第５条（パスワードの管理と責任）</h4>
<p>１　利用者は、自己のパスワードを第三者に譲渡または質入れし、使用させることができません。</p>
<p>２　利用者は自己のパスワードの管理と使用について一切の責任を負い、当該パスワードの使用による本サービス等の利用については、それが自己によるものと第三者によるものとを問わず、その使用に関わる一切の債務を支払うものとします。パスワードの第三者使用により利用者が損害を被った場合にも、帰責事由の有無に関わらず、当社は一切の責任を負いません。</p>
<h4>第６条（利用料金と支払い方法）</h4>
<p>１　本サービス等の利用料金は映像作品毎の購入画面に表示する料金、またはオプションの内容に従います。</p>
<p>２　本サービス等の利用料金は、別途諸規定に定められた方法で支払うものとします。なお、一旦支払われた本サービス等の利用料金は、理由の如何を問わず返金致しません。</p>
<h4>第７条（登録内容の変更）</h4>
<p>１　利用者は、当社への利用者登録内容（以下「登録内容」といいます）に変更が生じた場合には、直ちに定められた方法で登録内容を変更するものとします。</p>
<p>２　利用者は、前項の登録内容の変更を怠ったために当社に損害を生じさせた場合、その損害を賠償する責任を負担するものとします。</p>
<p>３　当社は、利用者が第１項の登録内容の変更を怠ったために利用者に不利益が生じることがあっても、理由を問わず一切の責任を負いません。</p>
<h4>第８条（利用者による解除・資格の喪失による終了）</h4>
<p>１　利用者は、利用登録の解除を希望する場合、定められた方法で手続きを行うものとします。</p>
<p>２　利用者は、利用登録を解除した場合、もしくは第９条（利用にあたっての順守事項）に基づき強制解除された場合には、購入した映像の視聴期間内であっても、当該映像の配信・視聴を停止されます。また、その料金については払い戻しを行いません。</p>
<p>３　当社は、前項の解除により、既に発生した料金その他の債務を免除することまたは払い戻しを行うことは一切ありません。</p>
<h4>第９条（利用にあたっての順守事項）</h4>
<p>１　利用者は本サービス等の利用に際して、次の各号いずれかに該当すると当社が認めた場合、</p>
<p>当社は利用者に対して何らの事前の催告・通告・通知なく利用登録を一時停止または強制解除</p>
<p>することができるとします。なお、以下の行為に該当するか否かについて、当社は、自らの</p>
<p>判断で、その該当性を判断し認定することができます。</p>
<p>(1)　本サイトのサーバーの不正利用またはサーバーに保存されたデータの改ざんした場合<br />
(2)　本サービス等を利用して違法な行為を行った場合<br />
(3)　本サービス等の利用料金等の支払債務の履行を遅滞し、または支払いを拒否した場合<br />
(4)　他者になりすまして本サービス等を利用した場合<br />
(5)　利用者に本サービス等を提供する上で著しい支障がある、もしくは支障を生じる恐れがあると当社が判断した場合<br />
(6)　本規約に違反する場合<br />
(7)　その他法令若しくは公序良俗に違反し、他の利用者または第三者、若しくは当社に不利益を与えた場合。<br />
(8)　前各号に定める行為を助長した場合。<br />
(9)　その他当社が不適切と判断した場合</p>
<p>２　利用者は、強制解除されたときは、期限の利益を喪失し退会までに発生した本サービスの利用料金の支払い債務を一括して、直ちに支払うものとします。当社は、強制解除するまでに利用登録者が支払った料金を一切払い戻しません。</p>
<p>３　第１項に該当する利用登録者の行為によって、当社が損害を受けたときは、利用者資格の一時停止または強制解除の如何にかかわらず、当社は、利用者に対してかかる損害の賠償を請求できるものとします。</p>
<h4>第10条（コンテンツについて）</h4>
<p>利用者は、本サービス等を通じて配信されたコンテンツに対し、次の各号に定める事項を行ってはいけません。また、本サービス等を通じて配信されたコンテンツを、次の各号で定める目的または方法により使用することができません。</p>
<p>(1)　方法の如何を問わず編集・改変すること<br />
(2)　施されている複製制限や再送信制限等の技術的保護手段、暗号化技術およびコピーガード技術を解除する、改変する、減衰する又は無効化すること<br />
(3)　譲渡または質入その他担保に供すること<br />
(4)　放送、有線放送、公の上映または自己の営業等に使用すること<br />
(5)　転貸または第三者に配信、提供もしくは使用させること<br />
(6)　その他自己の私的利用外の目的に使用すること</p>
<h4>第11条（損害賠償請求）</h4>
<p>利用者が、本規約に違反したことによって当社に損害を与えた場合には、自己の責任と費用をもってかかる損害（合理的な範囲での弁護士費用を含みますが、これに限られません）の賠償を請求することができ、利用者は、当該請求に直ちに応じなければならないものとします。</p>
<h4>第12条（免責事項）</h4>
<p>当社は、利用者が被ったいかなる損害または損失などについては、一切責任を負わず、損害賠償義務を負わないものとします。利用者はこの事に同意するものとします。</p>
<h4>第13条（本サービス等の中断）</h4>
<p>１　当社は、次の各号のいずれかに該当する場合、本サービス等の提供を中断できるものとします。</p>
<p>(1)　本サービス等のシステムの保守を定期的にまたは緊急に行う場合<br />
(2)　戦争、暴動、騒乱、労働争議、地震、噴火、洪水、津波、火災、停電その他の非常事態により、本サービスの提供が通常通りできない場合<br />
(3)　本サービス等の提供が、技術的に困難または不可能となった場合<br />
(4)　その他、当社が本サービスの運営上、一時的な中断が必要と判断した場合</p>
<p>２　当社は、前項により、本サービス等を中断するときは、予めその旨を利用登録者に通知するものとします。ただし、緊急やむを得ない場合は、事後速やかに通知します。</p>
<p>３　当社は、本サービス等の中断や、システムおよび通信環境の障害などの発生により、利用者が被ったいかなる損害についても、理由を問わず一切の責任を負いません。</p>
<h4>第14条（本サービス等の終了）</h4>
<p>１　当社は、当社が判断する相当の期間をもって予め利用者に対して通知することによって、本サービス等の一部ないし全部を終了することができます。ただし、やむを得ない事情がある場合はこの限りではなく、当社は、事前の通知を行うことなく、即時に本サービス等の一部ないし全部を終了することができます。</p>
<p>２　当社は、前項の手続きを経た場合、利用者が被ったいかなる損害についても、理由を問わず一切の責任を負いません。</p>
<h4>第15条（未成年者による利用）</h4>
<p>１　未成年者が利用者となって本サービス等を利用する場合、当該利用者は、本サービス等の利用に際して、親権者その他の法定代理人の同意を得る必要があります。</p>
<p>２　未成年の利用者が本サービス等を利用した場合、本サービス等の利用について保護者の同意を得ていることを当社に対して保証したものとみなします。</p>
<p>３　未成年利用者の親権者その他の法定代理人は、本会員の本サービス等利用状況に責任を負うものとします。</p>
<h4>第16条（準拠法）</h4>
<p>本規約は、日本国内で有効に効力を有する法令に準拠します。</p>
<h4>第17条（裁判管轄）</h4>
<p>本サービス等または本規約に関連して当社と利用者の間で生じた紛争については、その訴額または紛争の性質に応じて、東京簡易裁判所または東京地方裁判所を第一審の専属的管轄裁判所とします。</p>
<h4>第18条（附則）</h4>
<p>2013年4月26日施行</p>
</div>
<div style="margin:20px 0px;text-align:center;">規約には、本サービスを使用するに当たってのあなたの権利と義務が規定されております。<br>
「確認ページへ」ボタンをクリックすると、あなたが本規約の全ての条件に同意したことになります。 </div>


<div style="text-align:center;">
	<input type="image" src="/img/btn/conf_btn.gif" value="確認ページへ" />
</div>
</form>