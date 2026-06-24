<?php /* Smarty version 2.6.31, created on 2025-01-24 11:07:16
         compiled from /srv/alfproduct/smarty/templates/admin/inquiry/status.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/inquiry/status.tpl', 7, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/admin/inquiry/status.tpl', 40, false),)), $this); ?>
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>


<form action="<?php echo $this->_tpl_vars['next_url']; ?>
" accept-charset="utf-8" method="post" name="inquiry_form">
	<input type="hidden" name="iid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['iid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
	<h2>お問い合わせの内容を確認</h2>
	<?php $_from = $this->_tpl_vars['arr_err']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['err']):
?>
	<div class="error"><?php echo $this->_tpl_vars['err']; ?>
</div>
	<?php endforeach; endif; unset($_from); ?>
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

	<h2>変更内容を入力してください</h2>
	<table class="form">
		<tr>
			<th>ステータス</th>
			<td>
				<input type="radio" name="inquiry_status" id="inquiry_status4" value="3" <?php if ($this->_tpl_vars['inquiry_status'] == '3'): ?>checked="checked"<?php endif; ?>><label for="inquiry_status4">対応中</label>　
				<input type="radio" name="inquiry_status" id="inquiry_status3" value="2" <?php if ($this->_tpl_vars['inquiry_status'] == '2'): ?>checked="checked"<?php endif; ?>><label for="inquiry_status3">対応済</label>　
			</td>
		</tr>
		<tr>
			<th style="">対応者</th>
			<td style="">
				<input type="text" name="return_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['return_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:600px;">
			</td>
		</tr>
	</table>

	<div class="submit">
		<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
		<a href="javascript:void(0);" onclick="document.inquiry_form.submit();" /><img src="/alfproduct/images/btn_confirm.png"></a>
	</div>
</form>
<br />