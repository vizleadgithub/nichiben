<?php /* Smarty version 2.6.31, created on 2024-12-20 15:52:11
         compiled from /srv/alfproduct/smarty/templates/admin/amount_user/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/amount_user/index.tpl', 4, false),array('modifier', 'urlencode', '/srv/alfproduct/smarty/templates/admin/amount_user/index.tpl', 56, false),)), $this); ?>
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
			<th>ふりがな</th>
			<td>
				<input type="text" name="search_kana" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_kana'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="search_kana">
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
		<tr>
			<th>登録年月日</th>
			<td>
				<input type="text" name="search_start_regist_date" id="search_start_regist_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_start_regist_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_start_regist_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_regist_date" id="search_end_regist_date" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_end_regist_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_end_regist_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>所属弁護士会</th>
			<td>
				<select name="search_association">
					<?php if ($this->_tpl_vars['nichibenren_flg']): ?><option value="">-</option><?php endif; ?>
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
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:100px;text-align:left;">
				登録番号
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '1'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<?php if ($this->_tpl_vars['search_orderby'] == '2'): ?> style="color:#FFFFFF;"<?php else: ?> style="color:#00A4E2;"<?php endif; ?>>▼</a>
			</th>
			<th style="width:160px;text-align:left;">氏名</th>
			<th style="width:180px;text-align:left;">メールアドレス</th>
			<th style="width:100px;text-align:left;">FP</th>
			<th style="width:100px;text-align:left;">代替権限</th>
			<th style="width:;text-align:left;">所属弁護士会</th>
		</tr>
		<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<tr style="">
			<td class="tdc"><a href="info.php?sid=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></td>
			<td class="tdc"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="tdc"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="tdc"><?php if ($this->_tpl_vars['row']['presence_passport'] == '1'): ?>○<?php else: ?>-<?php endif; ?></td>
			<td class="tdc"><?php if ($this->_tpl_vars['row']['sub_auth_ethic_training'] == '1'): ?>○<?php else: ?>-<?php endif; ?></td>
			<td class="tdc"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<tr>
			<th class="pager" colspan="6">
				<?php echo $this->_tpl_vars['pager']; ?>

			</th>
		</tr>
		<?php endif; ?>
	</table>
<?php endif; ?>