<?php /* Smarty version 2.6.31, created on 2025-02-20 00:35:03
         compiled from /srv/alfproduct/smarty/templates/admin/product/search_student.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product/search_student.tpl', 8, false),array('modifier', 'urlencode', '/srv/alfproduct/smarty/templates/admin/product/search_student.tpl', 55, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product/search_student.tpl', 51, false),)), $this); ?>
<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>名前</th>
			<td colspan = "3">
				<input type="text" name="search_student_name" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th>登録番号</th>
			<td colspan = "3">
				<input type="text" name="search_lawyer_number" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td colspan = "3">
				<input type="text" name="search_student_email" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_student_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th>所属弁護士会</th>
			<td colspan = "3">
				<select name="search_bar_association_id">
					<option value="">--</option>
					<?php $_from = $this->_tpl_vars['bar_association_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
						<option value="<?php echo $this->_tpl_vars['row']['id']; ?>
" <?php if ($this->_tpl_vars['row']['id'] == $this->_tpl_vars['search_bar_association_id']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['row']['name']; ?>
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

<?php if ($this->_tpl_vars['disp_flg']): ?>
<br />
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>受講者</th>
		<th style="width:130px;">操作</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['student_id']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['student_name']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="search_set.php?gid=<?php echo ((is_array($_tmp=$this->_tpl_vars['gid'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_id'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&name=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['student_name'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&comment=">選択</a></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="4">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
</table>
<?php endif; ?>