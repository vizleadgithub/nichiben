<?php /* Smarty version 2.6.31, created on 2024-12-20 15:52:13
         compiled from /srv/alfproduct/smarty/templates/admin/amount_product/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/amount_product/index.tpl', 68, false),array('modifier', 'urlencode', '/srv/alfproduct/smarty/templates/admin/amount_product/index.tpl', 68, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/amount_product/index.tpl', 99, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/amount_product/index.tpl', 86, false),)), $this); ?>
<h2>検索する内容を入力してください</h2>

<form action="index.php" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>購入期間</th>
			<td>
				<input type="text" name="search_start_buy_date" id="start_date" value="<?php echo $this->_tpl_vars['search_start_buy_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_buy_date" id="end_date" value="<?php echo $this->_tpl_vars['search_end_buy_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>月別集計</th>
			<td>
				<input type="checkbox" name="search_monthly" id="search_monthly" value="1" <?php if ($this->_tpl_vars['search_monthly'] == '1'): ?> checked="checked"<?php endif; ?> /><label for="search_monthly">する</label>
			</td>
		</tr>
		<tr>
			<th>商品名</th>
			<td>
				<input type="text" name="search_product_name" value="<?php echo $this->_tpl_vars['search_product_name']; ?>
" /> 
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td>
				<input type="text" name="search_product_code" value="<?php echo $this->_tpl_vars['search_product_code']; ?>
" /> 
			</td>
		</tr>
		<tr>
			<th>商品種別</th>
			<td>
				<?php $_from = $this->_tpl_vars['arr_product_type_add']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<input type="checkbox" name="search_product_type_add[]" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" id="search_product_type_add_<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if (in_array ( $this->_tpl_vars['row']['id'] , $this->_tpl_vars['search_product_type_add'] )): ?> checked="checked"<?php endif; ?>><label for="search_product_type_add_<?php echo $this->_tpl_vars['row']['id']; ?>
"><?php echo $this->_tpl_vars['row']['name']; ?>
</label>&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
		<?php if ($this->_tpl_vars['bar_association_id'] == '1'): ?>
		<tr>
			<th>決済方法</th>
			<td>
				<?php $_from = $this->_tpl_vars['arr_payment_type']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<input type="checkbox" name="search_payment_type[]" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" id="search_payment_type_<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if (in_array ( $this->_tpl_vars['row']['id'] , $this->_tpl_vars['search_payment_type'] )): ?> checked="checked"<?php endif; ?>><label for="search_payment_type_<?php echo $this->_tpl_vars['row']['id']; ?>
"><?php echo $this->_tpl_vars['row']['name']; ?>
</label>&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
		<tr>
			<th>請求書</th>
			<td>
				<?php $_from = $this->_tpl_vars['arr_claim_flg']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<input type="checkbox" name="search_claim_flg[]" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" id="search_claim_flg_<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if (in_array ( $this->_tpl_vars['row']['id'] , $this->_tpl_vars['search_claim_flg'] )): ?> checked="checked"<?php endif; ?>><label for="search_claim_flg_<?php echo $this->_tpl_vars['row']['id']; ?>
"><?php echo $this->_tpl_vars['row']['name']; ?>
</label>&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
		<?php endif; ?>

	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<?php if ($this->_tpl_vars['disp_flg']): ?>
	<a href="csv.php?data=<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['post_data'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
" target="_blank"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
	（全<?php echo $this->_tpl_vars['all_count']; ?>
件）

	<?php if ($this->_tpl_vars['search_monthly'] == ""): ?>
	<table class="list">
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:;">商品ID</th>
			<th>商品名</th>
			<th style="width:;">商品コード</th>
			<th style="width:;">販売単価</th>
			<th style="width:;">販売数</th>
			<th style="width:;">合計</th>
		</tr>
		<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

		<tr style="">
			<?php if ($this->_tpl_vars['row']['product_type_add'] == '1'): ?>
				<td class="tdc" style=""><a href="/alfproduct/product/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<?php elseif ($this->_tpl_vars['row']['product_type_add'] == '2'): ?>
				<td class="tdc" style=""><a href="/alfproduct/product_live/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<?php elseif ($this->_tpl_vars['row']['product_type_add'] == '3'): ?>
				<td class="tdc" style=""><a href="/alfproduct/product_ethics/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<?php elseif ($this->_tpl_vars['row']['product_type_add'] == '4'): ?>
				<td class="tdc" style=""><a href="/alfproduct/product_passport/info.php?mid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<?php endif; ?>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['buy_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['all_pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<th class="pager" colspan="4">
			</th>
			<td class="tdc"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['all_buy_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</th>
			<td class="tdc"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['all_pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</th>

		</tr>
		<?php endif; ?>
	</table>
	<?php elseif ($this->_tpl_vars['search_monthly'] == '1'): ?>
	<table class="list">
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:;">月</th>
			<th style="width:;">販売数</th>
			<th style="width:;">合計</th>
		</tr>
		<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

		<tr style="">
			<td class="tdc" style=""><a href=""><a href="?post_data=<?php echo ((is_array($_tmp=$this->_tpl_vars['post_data'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&buy_y=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['buy_y'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&buy_m=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['buy_m'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">[<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['buy_y'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['buy_m'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
]</a></td>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['buy_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
			<td class="tdc" style=""><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['all_pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<th class="pager">
			</th>
			<td class="tdc"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['all_buy_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</th>
			<td class="tdc"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['all_pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</th>

		</tr>
		<?php endif; ?>
	</table>
	<?php endif; ?>
<?php endif; ?>