<?php /* Smarty version 2.6.31, created on 2025-03-06 08:48:05
         compiled from /srv/alfproduct/smarty/templates/admin/product/search_contents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product/search_contents.tpl', 8, false),array('modifier', 'urlencode', '/srv/alfproduct/smarty/templates/admin/product/search_contents.tpl', 39, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product/search_contents.tpl', 35, false),)), $this); ?>
<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>コンテンツID</th>
			<td colspan = "3">
				<input type="text" name="search_contents_code" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_contents_code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">コンテンツ名</th>
			<td colspan = "3" style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_contents_name" size="45" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_contents_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
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
		<th>コンテンツ名</th>
		<th style="width:130px;">操作</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
		<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

		<tr style="">
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_logic_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="search_set.php?gid=<?php echo ((is_array($_tmp=$this->_tpl_vars['gid'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_id'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&name=<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['video_logic_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&comment=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_caption'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
">このコンテンツを登録する</a></td>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="4">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
</table>
<?php endif; ?>