<?php /* Smarty version 2.6.31, created on 2025-01-24 11:03:39
         compiled from /srv/alfproduct/smarty/templates/admin/product/search_product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product/search_product.tpl', 8, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product/search_product.tpl', 46, false),)), $this); ?>
<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">商品名</th>
			<td colspan = "3" style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_product_name" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td colspan = "3">
				<input type="text" name="search_product_code" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">公開期間</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_start_date" id="start_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_start_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_end_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>

<?php if ($this->_tpl_vars['disp_flg']): ?>
<br />
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>商品名</th>
		<th style="width:300px;">公開期間</th>
		<th style="width:130px;">操作</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['product_id']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['product_name']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['start_date']; ?>
～<?php echo $this->_tpl_vars['row']['end_date']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="search_set.php?gid=<?php echo $this->_tpl_vars['gid']; ?>
&id=<?php echo $this->_tpl_vars['row']['product_id']; ?>
&name=<?php echo $this->_tpl_vars['row']['product_name']; ?>
">この商品を設定する</a></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="4">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
</table>
<?php endif; ?>