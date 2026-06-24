<?php /* Smarty version 2.6.31, created on 2024-12-20 15:52:12
         compiled from /srv/alfproduct/smarty/templates/admin/amount_passport/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/amount_passport/index.tpl', 4, false),array('modifier', 'urlencode', '/srv/alfproduct/smarty/templates/admin/amount_passport/index.tpl', 69, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/amount_passport/index.tpl', 111, false),)), $this); ?>
<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<input type="hidden" name="search_orderby" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_orderby'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="search_orderby">
	<table class="form">
		<tr>
			<th>氏名</th>
			<td>
				<input type="text" name="search_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="search_name">
				<p style="color:red;">※名前は姓と名の間にスペースを入力してください。</p>
			</td>
		</tr>
		<tr>
			<th>登録番号<span style="color:red;">※半角入力</span></th>
			<td>
				<input type="text" name="search_start_lawyer_number" id="start_lawyer_number" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_start_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /> 
				～
				<input type="text" name="search_end_lawyer_number" id="end_lawyer_number" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_end_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /> 
			</td>
		</tr>
		<?php if ($this->_tpl_vars['bar_association_id'] == '1'): ?>
		<tr>
			<th>所属弁護士会</th>
			<td>
				<select name="search_association">
					<option value="">-</option>
				<?php $_from = $this->_tpl_vars['arr_association']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
" <?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['search_association']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>購入期間</th>
			<td>
				<input type="text" name="search_start_buy_date" id="search_start_buy_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_start_buy_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_start_buy_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_buy_date" id="search_end_buy_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_end_buy_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_end_buy_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>パスポート種別</th>
			<td>
				<?php $_from = $this->_tpl_vars['arr_passport_target']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['passport_target_id'] => $this->_tpl_vars['passport_target_name']):
?>
					<input type="checkbox" name="search_passport_target[]" value="<?php echo $this->_tpl_vars['passport_target_id']; ?>
" id="search_passport_target_<?php echo $this->_tpl_vars['passport_target_id']; ?>
"<?php if (in_array ( $this->_tpl_vars['passport_target_id'] , $this->_tpl_vars['search_passport_target'] )): ?> checked="checked"<?php endif; ?>><label for="search_passport_target_<?php echo $this->_tpl_vars['passport_target_id']; ?>
"><?php echo $this->_tpl_vars['passport_target_name']; ?>
</label>&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
		<tr>
			<th>入金ステータス</th>
			<td>
				<?php $_from = $this->_tpl_vars['arr_payment_status']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
					<input type="checkbox" name="search_payment_status[]" value="<?php echo $this->_tpl_vars['row']['id']; ?>
" id="search_payment_status_<?php echo $this->_tpl_vars['row']['id']; ?>
"<?php if (in_array ( $this->_tpl_vars['row']['id'] , $this->_tpl_vars['search_payment_status'] )): ?> checked="checked"<?php endif; ?>><label for="search_payment_status_<?php echo $this->_tpl_vars['row']['id']; ?>
"><?php echo $this->_tpl_vars['row']['name']; ?>
</label>&nbsp;
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />


<?php if ($this->_tpl_vars['disp_flg']): ?>
	<a href="csv.php?data=<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['post_data'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
" target="_blank"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
	<?php echo $this->_tpl_vars['list_start']; ?>
～<?php echo $this->_tpl_vars['list_end']; ?>
件を表示中（全<?php echo $this->_tpl_vars['list_max']; ?>
件）
	<table class="list">
		<tr>
			<th style="text-align:left;">
				登録番号
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '1'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '2'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▼</a>
			</th>
			<th style="text-align:left;">氏名</th>
			<th style="text-align:left;">弁護士会</th>
			<th style="text-align:left;">購入日</th>
			<th style="text-align:left;">商品名</th>
			<th style="text-align:left;">パスポート種別</th>
			<th style="text-align:left;">
				入金
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='3';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '3'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='4';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '4'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▼</a>
			</th>
			<th style="text-align:left;">金額</th>
		</tr>
		<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<tr>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['order_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td>
				<?php if ($this->_tpl_vars['row']['product_name_TOD'] != ''): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php else: ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

				<?php endif; ?>
			</td>
			<td><?php echo $this->_tpl_vars['row']['disp_passport_target']; ?>
</td>
			<td>
				<?php if ($this->_tpl_vars['row']['payment_status'] == '0'): ?>未入金
				<?php elseif ($this->_tpl_vars['row']['payment_status'] == '1'): ?>未入金
				<?php elseif ($this->_tpl_vars['row']['payment_status'] == '2'): ?>入金済
				<?php endif; ?>
			</td>
			<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['pay_total'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<th class="pager" colspan="8">
				<?php echo $this->_tpl_vars['pager']; ?>

			</th>
		</tr>
		<?php endif; ?>
	</table>
<?php endif; ?>