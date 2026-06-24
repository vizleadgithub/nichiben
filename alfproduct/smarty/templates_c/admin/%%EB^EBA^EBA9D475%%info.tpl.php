<?php /* Smarty version 2.6.31, created on 2025-01-31 06:43:58
         compiled from /srv/alfproduct/smarty/templates/admin/product_lecture2/info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product_lecture2/info.tpl', 48, false),array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_lecture2/info.tpl', 53, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/admin/product_lecture2/info.tpl', 54, false),)), $this); ?>
<!--
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="add.php"><span>新規登録</span></a>
</div>
-->

<!--<h2>検索する内容を入力してください</h2>-->

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th colspan="2">
			研修内容 <a href="csv.php?type=info&pid=<?php echo $this->_tpl_vars['pid']; ?>
">CSV取得</a>
			</th>
		</tr>
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<?php echo $this->_tpl_vars['arr_input']['product_name']; ?>

			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>

	<tr>
		<th>ID</th>
		<th>申込期限</th>
		<th>WEB受付</th>
		<th>弁護士会名/支部名</th>
		<th>定員</th>
		<th>申込数</th>
		<th>受講数</th>
		<th>受講率</th>
		<th>申込者管理</th>
	</tr>

	<?php if (is_array ( $this->_tpl_vars['arr_list'] ) && count ( $this->_tpl_vars['arr_list'] ) > 0): ?>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['bar_association_branch_id']; ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php if (strlen ( $this->_tpl_vars['row']['limit_date'] ) > 0): ?><?php echo $this->_tpl_vars['row']['limit_date']; ?>
<?php else: ?>-<?php endif; ?></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php if ($this->_tpl_vars['row']['web_flg'] == 1): ?>○<?php else: ?>-<?php endif; ?></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['bar_association_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['bar_association_branch_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['capacity'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['entry_number'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['entry_number_passport'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
)</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['attend_number'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['attend_number_passport'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
)</td></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['number_percent'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
%</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php if ($this->_tpl_vars['row']['kanri_flg']): ?><a href="info_user.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&aid=<?php echo $this->_tpl_vars['row']['bar_association_branch_id']; ?>
">管理</a><?php endif; ?></td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<td colspan="5">&nbsp;</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['all_entry_number'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
(<?php echo ((is_array($_tmp=$this->_tpl_vars['all_entry_number_passport'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
)</td>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['all_attend_number'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
(<?php echo ((is_array($_tmp=$this->_tpl_vars['all_attend_number_passport'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
)</td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<?php endif; ?>
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php?page=1';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>