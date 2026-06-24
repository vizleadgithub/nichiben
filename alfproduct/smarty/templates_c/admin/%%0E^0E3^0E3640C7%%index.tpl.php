<?php /* Smarty version 2.6.31, created on 2024-12-20 15:52:14
         compiled from /srv/alfproduct/smarty/templates/admin/bank_upload/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/bank_upload/index.tpl', 43, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/bank_upload/index.tpl', 45, false),)), $this); ?>
<h2>ファイルをアップロードして銀行振込csvを取り込みます</h2>

<form name="form1" action="index.php" method="post" enctype="multipart/form-data">
<table class="form">
	<tr>
		<th>
			<input type="file" size="50" name="csv_upload">
			<input type="submit" size="50" name="btn_submit" value="アップロード">
		</th>
	</tr>
	<tr>
		<td>
			<?php if ($this->_tpl_vars['err_msg'] != ""): ?>
			<?php echo $this->_tpl_vars['err_msg']; ?>

			<?php endif; ?>
			<?php if ($this->_tpl_vars['ok_msg'] != ""): ?>
			<?php echo $this->_tpl_vars['ok_msg']; ?>

			<?php endif; ?>
		</td>
	</tr>
</table>


<br />

<?php if ($this->_tpl_vars['disp_flg']): ?>
	<?php if (is_array ( $this->_tpl_vars['csv'] ) && count ( $this->_tpl_vars['csv'] ) > 0): ?>
		<table class="list">
			<form accept-charset="utf-8" method="get" name="list_form">
				
			</form>
			<tr>
				<th style="width:;">注文No</th>
				<th style="width:;">商品名（ID）</th>
				<th style="width:;">金額</th>
				<th style="width:;">会員名</th>
				<th style="width:;">入金日</th>
				<th style="width:;">取り込み日</th>
			</tr>

			<?php $_from = $this->_tpl_vars['csv']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
			<tr>
				<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)</td>
				<td class="tdc" style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
				<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['receipt_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['take_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
		</table>
		<?php if ($this->_tpl_vars['ok_msg'] != ""): ?>
		<?php echo $this->_tpl_vars['ok_msg']; ?>

		<?php endif; ?>

	<?php endif; ?>
<?php endif; ?>

<a name="page_bottom"></a>
</form>