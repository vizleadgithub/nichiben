<?php /* Smarty version 2.6.27, created on 2013-06-17 20:30:53
         compiled from /srv/alfproduct/smarty/templates/smartphone/mypage/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/mypage/edit.tpl', 16, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'mypage/side_menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h2 class="titleh2">登録情報変更</h2>

<form name="form1" action="edit.php" method="post">
<input type="hidden" name="act" value="confirm" />


<div style="margin-bottom:20px;">
<table style="width:100%;" class="member_table">
<tr>
	<?php if (isset ( $this->_tpl_vars['err_msg']['name1'] )): ?><?php $this->assign('name1_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['name2'] )): ?><?php $this->assign('name2_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<th  style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">氏名<br><span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		姓<input type="text" name="name1" id="name1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['name1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['name1_style']; ?>
 /><br>
		名<input type="text" name="name2" id="name2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['name2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['name2_style']; ?>
 /><br>
		<?php if (isset ( $this->_tpl_vars['err_msg']['name1'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['name1']; ?>
</span><br />
		<?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['name2'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['name2']; ?>
</span><br />
		<?php endif; ?>
	</td>
</tr>
<tr>
	<?php if (isset ( $this->_tpl_vars['err_msg']['kana1'] )): ?><?php $this->assign('kana1_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['kana2'] )): ?><?php $this->assign('kana2_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">フリガナ<br><span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		セイ<input type="text" name="kana1" id="kana1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['kana1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['kana1_style']; ?>
 /><br>
		メイ<input type="text" name="kana2" id="kana2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['kana2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['kana2_style']; ?>
 /><br>
		<?php if (isset ( $this->_tpl_vars['err_msg']['kana1'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['kana1']; ?>
</span><br />
		<?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['kana2'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['kana2']; ?>
</span><br />
		<?php endif; ?>
	</td>
</tr>
<tr>
	<?php if (isset ( $this->_tpl_vars['err_msg']['pref_id'] )): ?><?php $this->assign('pref_id_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['address1'] )): ?><?php $this->assign('address1_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['address2'] )): ?><?php $this->assign('address2_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<?php if (isset ( $this->_tpl_vars['err_msg']['address3'] )): ?><?php $this->assign('address3_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">住所</th>
	<td style="padding:5px;border:solid 1px #999999;">
		都道府県<span style="color:red;">※必須</span><br />
		<select name="pref_id" id="pref_id" <?php echo $this->_tpl_vars['pref_id_style']; ?>
>
		<option value="">選択してください</option> 
		<?php $_from = $this->_tpl_vars['mtb_pref']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['pref_id'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pref_id']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val']):
        $this->_foreach['pref_id']['iteration']++;
?>
		<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_foreach['pref_id']['iteration'] == $this->_tpl_vars['arr_input']['pref_id']): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
		</select><br />
		<?php if (isset ( $this->_tpl_vars['err_msg']['pref_id'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['pref_id']; ?>
</span><br />
		<?php endif; ?>

		市区町村<span style="color:red;"></span><br>
		<input type="text" name="address1" id="address1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['address1'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['address1_style']; ?>
 /><br />
		<?php if (isset ( $this->_tpl_vars['err_msg']['address1'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['address1']; ?>
</span><br />
		<?php endif; ?>
		番地<span style="color:red;"></span><br />
		<input type="text" name="address2" id="address2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['address2'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['address2_style']; ?>
 /><br />
		<?php if (isset ( $this->_tpl_vars['err_msg']['address2'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['address2']; ?>
</span><br />
		<?php endif; ?>
		ビル名・マンション名<br />
		<input type="text" name="address3" id="address3" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['address3'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['address3_style']; ?>
 /><br />
		<?php if (isset ( $this->_tpl_vars['err_msg']['address3'] )): ?>
		<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['address3']; ?>
</span><br />
		<?php endif; ?>

	</td>
</tr>
<?php if (isset ( $this->_tpl_vars['err_msg']['email'] )): ?><?php $this->assign('email_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['err_msg']['email_conf'] )): ?><?php $this->assign('email_conf_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">メールアドレス</th>
	<td style="padding:5px;border:solid 1px #999999;">
		<input type="text" name="email" id="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['email_style']; ?>
 /><br />
		<input type="text" name="email_conf" id="email_conf" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['email_conf'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['email_conf_style']; ?>
 /><br />
		確認のため2度入力してください。<br />
		<?php if (isset ( $this->_tpl_vars['err_msg']['email'] )): ?>
			<br /><span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['email']; ?>
</span><br />
		<?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['err_msg']['email_conf'] )): ?>
			<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['email_conf']; ?>
</span><br />
		<?php endif; ?>
	</td>
</tr>
<?php if (isset ( $this->_tpl_vars['err_msg']['password'] )): ?><?php $this->assign('password_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['err_msg']['password_conf'] )): ?><?php $this->assign('password_conf_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">パスワード<br><span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		<input type="password" name="password" id="password" value="<?php echo $this->_tpl_vars['arr_input']['password']; ?>
" <?php echo $this->_tpl_vars['password_style']; ?>
 /><br />
		半角英数字4～10文字でお願いします。(記号不可)<br />
			<?php if (isset ( $this->_tpl_vars['err_msg']['password'] )): ?>
			<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password']; ?>
</span><br />
			<?php endif; ?>
		<input type="password" name="password_conf" id="password_conf" value="<?php echo $this->_tpl_vars['arr_input']['password_conf']; ?>
" <?php echo $this->_tpl_vars['password_conf_style']; ?>
 /><br>
		確認のため2度入力してください。<br />
		<?php if (isset ( $this->_tpl_vars['err_msg']['password_conf'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password_conf']; ?>
</span><br />
		<?php endif; ?>
	</td>
</tr>
<?php if (isset ( $this->_tpl_vars['err_msg']['password_question'] )): ?><?php $this->assign('password_question_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['err_msg']['password_answer'] )): ?><?php $this->assign('password_answer_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント<br><span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		質問<br>
		<select name="password_question" id="password_question" <?php echo $this->_tpl_vars['password_question_style']; ?>
>
			<option value="">選択してください</option> 
			<?php $_from = $this->_tpl_vars['mtb_password_question']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['password_question'] = array('total' => count($_from), 'iteration' => 0);
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
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password_question']; ?>
</span><br />
		<?php endif; ?>
		答え<br>
		<input type="text" name="password_answer" id="password_answer" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['password_answer'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['password_answer_style']; ?>
 /><br>
		変更する場合は入力してください。<br>
		<?php if (isset ( $this->_tpl_vars['err_msg']['password_answer'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['password_answer']; ?>
</span><br />
		<?php endif; ?>
	</td>
</tr>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">職業</th>
	<td style="padding:5px;border:solid 1px #999999;">
		<select name="job" id="job">
		<option value="">選択してください</option> 
		<?php $_from = $this->_tpl_vars['mtb_job']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['job'] = array('total' => count($_from), 'iteration' => 0);
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
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">業種</th>
	<td style="padding:5px;border:solid 1px #999999;">
		<select name="job_type" id="job_type">
		<option value="">選択してください</option> 
		<?php $_from = $this->_tpl_vars['mtb_job_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['job_type'] = array('total' => count($_from), 'iteration' => 0);
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
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">学校名</th>
	<td style="padding:5px;border:solid 1px #999999;">
		<input type="text" name="school_name" id="school_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['school_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	</td>
</tr>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">学年</th>
	<td style="padding:5px;border:solid 1px #999999;">
		<select name="school_grade" id="school_grade" <?php echo $this->_tpl_vars['school_grade_style']; ?>
>
		<option value="">選択してください</option> 
		<?php $_from = $this->_tpl_vars['mtb_school_grade']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['school_grade'] = array('total' => count($_from), 'iteration' => 0);
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
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">年代<span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		<select name="age" id="age" <?php echo $this->_tpl_vars['age_style']; ?>
>
		<option value="">選択してください</option> 
		<?php $_from = $this->_tpl_vars['mtb_age']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['age'] = array('total' => count($_from), 'iteration' => 0);
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
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">性別<span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		<?php $_from = $this->_tpl_vars['mtb_gender']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">伊藤塾塾生番号</th>
	<td style="padding:5px;border:solid 1px #999999;">
		<input type="text" name="student_no" id="student_no" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_input']['student_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	</td>
</tr>
</table>
</div>



<div style="margin-bottom:20px;">
<table style="width:100%;" class="member_table">
<?php if (isset ( $this->_tpl_vars['err_msg']['mail_magazine_flag'] )): ?><?php $this->assign('mail_magazine_flag_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">メールマガジンについて<br><span style="color:red;">※必須</span></th>
	<td style="padding:5px;border:solid 1px #999999;">
		<label <?php echo $this->_tpl_vars['mail_magazine_flag_style']; ?>
><input type="radio" name="mail_magazine_flag" value="1" <?php if ($this->_tpl_vars['arr_input']['mail_magazine_flag'] == 1): ?>checked<?php endif; ?> />受け取る</label><br>
		<label <?php echo $this->_tpl_vars['mail_magazine_flag_style']; ?>
><input type="radio" name="mail_magazine_flag" value="0" <?php if ($this->_tpl_vars['arr_input']['mail_magazine_flag'] == 0): ?>checked<?php endif; ?> />受け取らない</label><br>
		<?php if (isset ( $this->_tpl_vars['err_msg']['mail_magazine_flag'] )): ?>
		<span style="color:red;"><?php echo $this->_tpl_vars['err_msg']['mail_magazine_flag']; ?>
</span><br>
		<?php endif; ?>
	</td>
</tr>
<?php if (isset ( $this->_tpl_vars['err_msg']['mail_magazine'] )): ?><?php $this->assign('mail_magazine_style', $this->_tpl_vars['err_style']); ?><?php endif; ?>
<tr>
	<th style="border:solid 1px #999999;background-color: #CEE6F6;text-align:left;vertical-align:top;padding:5px;">受け取るメールマガジン</th>
	<td style="padding:5px;border:solid 1px #999999;">
	<?php $_from = $this->_tpl_vars['mtb_mailmagazine_category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
		<?php $this->assign('checked', ''); ?>
		<?php $_from = $this->_tpl_vars['arr_input']['arr_mail_magazine']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['val2']):
        $this->_foreach['loop']['iteration']++;
?>
			<?php if ($this->_tpl_vars['val2'] == $this->_tpl_vars['val']['id']): ?>
				<?php $this->assign('checked', 'checked'); ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<label><input type="checkbox" name="mail_magazine[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php echo $this->_tpl_vars['checked']; ?>
 /><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label><br>
	<?php endforeach; endif; unset($_from); ?>
	</td>
</tr>
</table>
</div>


<div style="text-align:center;">
	<input type="image" src="/img/btn/conf_btn.gif" value="確認" />
</div>
</form>
