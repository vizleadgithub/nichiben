<?php /* Smarty version 2.6.31, created on 2024-12-20 16:12:52
         compiled from /srv/alfproduct/smarty/templates/admin/mailmagazine/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/mailmagazine/index.tpl', 124, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/mailmagazine/index.tpl', 137, false),)), $this); ?>
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="edit.php"><span>新規登録</span></a>
</div>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">配信日時</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_start_date" id="start_date" value="<?php echo $this->_tpl_vars['search_start_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<?php echo $this->_tpl_vars['search_end_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>フリーワード</th>
			<td colspan = "3">
				<input type="text" name="search_keyword" size="45" value="<?php echo $this->_tpl_vars['search_keyword']; ?>
">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">登録年</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_start_regist_date" id="search_start_regist_date" value="<?php echo $this->_tpl_vars['search_start_regist_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_start_regist_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_regist_date" id="search_end_regist_date" value="<?php echo $this->_tpl_vars['search_end_regist_date']; ?>
" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_end_regist_date.value='';">クリア</a>
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>タイトル</th>
		<th style="width:110px;">状態</th>
		<th style="width:133px;">配信日</th>
	</tr>
	<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="info.php?mid=<?php echo $this->_tpl_vars['row']['mailmagazine_id']; ?>
"><?php echo $this->_tpl_vars['row']['mailmagazine_id']; ?>
</a></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['mail_title']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php if ($this->_tpl_vars['row']['submit_status'] == '2'): ?>配信済<?php elseif ($this->_tpl_vars['row']['submit_status'] == '1'): ?>配信中<?php else: ?>未配信<?php endif; ?></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['submit_datetime']; ?>
</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="4">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
	<?php endif; ?>
</table>