<?php /* Smarty version 2.6.31, created on 2025-02-27 12:05:58
         compiled from /srv/alfproduct/smarty/templates/admin/amount_user/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/amount_user/info.tpl', 59, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/amount_user/info.tpl', 71, false),)), $this); ?>
<h2></h2>

<form accept-charset="utf-8" method="post" name="search_form">
	<input type="hidden" name="sid" value="<?php echo $this->_tpl_vars['sid']; ?>
">
	<input type="hidden" name="search_orderby" value="">
	<table class="form">
		<tr>
			<th style="width:100px;">登録番号</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_student']['lawyer_number']; ?>

			</td>
		</tr>
		<tr>
			<th style="">氏名</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_student']['student_name']; ?>

			</td>
		</tr>
		<tr>
			<th style="">登録年月日</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_student']['regist_date']; ?>

			</td>
		</tr>
		<tr>
			<th style="">所属弁護士会</th>
			<td style="">
				<?php echo $this->_tpl_vars['arr_student']['association_name']; ?>

			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<a href="csv.php?sid=<?php echo $this->_tpl_vars['sid']; ?>
" target="_blank"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
	</form>
	<tr>
		<th style="width:;">注文ID</th>
		<th>商品名</th>
		<th style="width:;">商品コード</th>
		<th style="width:;text-align:left;">
			入金
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '1'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▲</a>
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '2'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▼</a>
		</th>
		<th style="width:;">購入日</th>
		<th style="width:;">購入価格</th>
		<th style="width:;">申込</th>
		<th style="width:;">決済方法</th>
	</tr>
	<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<tr style="">

		<td class="tdc"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc"><?php if ($this->_tpl_vars['row']['product_name_TOD'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['row']['product_name_TP'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?></td>
		<td class="tdc"><?php if ($this->_tpl_vars['row']['product_code_TOD'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_code_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['row']['product_code_TP'] != ""): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_code_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?></td>
		<td class="tdc">
			<?php if ($this->_tpl_vars['row']['payment_status'] == '0'): ?>未入金
			<?php elseif ($this->_tpl_vars['row']['payment_status'] == '1'): ?>未入金
			<?php elseif ($this->_tpl_vars['row']['payment_status'] == '2'): ?>入金済
			<?php elseif ($this->_tpl_vars['row']['payment_status'] == '3'): ?>一部未入金
			<?php elseif ($this->_tpl_vars['row']['payment_status'] == '9'): ?>キャンセル
			<?php endif; ?>
		</td>
		<td class="tdc"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['create_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
		<td class="tdc">
			<?php if ($this->_tpl_vars['row']['web_flg'] == '1'): ?>WEB
			<?php else: ?>WEB以外
			<?php endif; ?>
		</td>
		<td class="tdc">
			<?php if ($this->_tpl_vars['row']['payment_type'] == '1'): ?>カード
			<?php elseif ($this->_tpl_vars['row']['payment_type'] == '12'): ?>銀行振込
			<?php else: ?>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th colspan="7">合計</th>
		<th><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['all_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</th>
	</tr>
	<?php endif; ?>
</table>