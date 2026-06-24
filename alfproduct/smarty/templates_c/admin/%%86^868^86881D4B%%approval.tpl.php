<?php /* Smarty version 2.6.31, created on 2024-12-20 16:06:43
         compiled from /srv/alfproduct/smarty/templates/admin/product_live/approval.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/product_live/approval.tpl', 12, false),array('modifier', 'mb_truncate', '/srv/alfproduct/smarty/templates/admin/product_live/approval.tpl', 24, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/admin/product_live/approval.tpl', 21, false),)), $this); ?>
<script type="text/javascript">
function approvalExe(productId){
	if (confirm('承認してもよろしいでしょうか？')){
		document.form1.action = "approval_exe.php?mid=" + productId;
		document.form1.submit();
	}
}
</script>

<h2>下記より選んで承認してください</h2>

<?php echo ((is_array($_tmp=$this->_tpl_vars['list_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
～<?php echo ((is_array($_tmp=$this->_tpl_vars['list_end'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件を表示中（全<?php echo ((is_array($_tmp=$this->_tpl_vars['all_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
件中）
<form name="form1" action="#" method="post">
<table class="list">
	<tr>
		<th style="width:76px;">ID</th>
		<th>研修名</th>
		<th style="width:300px;" colspan="2">受付期間</th>
	</tr>
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

	<tr style="">
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><a href="info.php?mid=<?php echo $this->_tpl_vars['row']['product_id']; ?>
"><?php echo $this->_tpl_vars['row']['product_id']; ?>
</a></td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['product_name'])) ? $this->_run_mod_handler('mb_truncate', true, $_tmp, 60, "...") : smarty_modifier_mb_truncate($_tmp, 60, "...")))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		<td class="tdc" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background: none repeat scroll 0% 0% rgb(246, 246, 243);<?php endif; ?>"><?php echo $this->_tpl_vars['row']['start_date']; ?>
<?php if ($this->_tpl_vars['row']['start_date'] != "" && $this->_tpl_vars['row']['end_date'] != ""): ?>～<?php endif; ?><?php echo $this->_tpl_vars['row']['end_date']; ?>
</td>
		<th style="background-color:#ffffff;text-align:center;"><input type="button" value="承認" onclick="approvalExe(<?php echo $this->_tpl_vars['row']['product_id']; ?>
)" /></th>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<th class="pager" colspan="4">
<?php echo $this->_tpl_vars['pager']; ?>

		</th>
	</tr>
</table>
</form>