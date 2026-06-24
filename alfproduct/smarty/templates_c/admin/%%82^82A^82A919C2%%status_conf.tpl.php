<?php /* Smarty version 2.6.31, created on 2025-01-24 11:07:18
         compiled from /srv/alfproduct/smarty/templates/admin/inquiry/status_conf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/inquiry/status_conf.tpl', 10, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/admin/inquiry/status_conf.tpl', 34, false),)), $this); ?>
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>

	<h2>お問い合わせの内容を確認</h2>
	<table class="form">
		<?php if ($this->_tpl_vars['iid'] != ""): ?>
		<tr>
			<th style="width:120px;">ID</th>
			<td style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['iid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>投稿日</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['regist_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>お名前</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['inquiry_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_data']['inquiry_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
		<tr>
			<th>お問い合わせ内容</th>
			<td style="">
				<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arr_data']['inquiry_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</td>
		</tr>
	</table>

	<h2>変更内容を確認</h2>
	<table class="form">
		<tr>
			<th>ステータス</th>
			<td style="">
				<?php if ($this->_tpl_vars['inquiry_status'] == '3'): ?>対応中
				<?php elseif ($this->_tpl_vars['inquiry_status'] == '2'): ?>対応済
				<?php elseif ($this->_tpl_vars['inquiry_status'] == '1'): ?>返信済
				<?php elseif ($this->_tpl_vars['inquiry_status'] == '0'): ?><span style="color:#ff0000;"><b>新規</b></span>
				<?php else: ?><span style="color:#ff0000;"><b>新規</b></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th style="width:120px;">対応者</th>
			<td style="">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
		</tr>
	</table>


	<div class="submit">
		<div style="width:48%;float:left;text-align:right;">
			<form action="<?php echo $this->_tpl_vars['prev_url']; ?>
" accept-charset="utf-8" method="post" name="inquiry_form_prev">
			<input type="hidden" name="iid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['iid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			<input type="hidden" name="inquiry_status" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['inquiry_status'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			<input type="hidden" name="return_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			<input type="image" src="/alfproduct/images/btn_back.png">
			</form>
		</div>
		<div style="width:48%;float:right;text-align:left;">
			<form action="<?php echo $this->_tpl_vars['next_url']; ?>
" accept-charset="utf-8" method="post" name="inquiry_form_next">
			<input type="hidden" name="iid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['iid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			<input type="hidden" name="inquiry_status" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['inquiry_status'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			<input type="hidden" name="return_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			<input type="image" src="/alfproduct/images/btn_ok.png">
			</form>
		</div>
	</div>
<br />