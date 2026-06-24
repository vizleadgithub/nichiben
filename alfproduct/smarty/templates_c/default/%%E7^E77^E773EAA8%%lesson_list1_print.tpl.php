<?php /* Smarty version 2.6.31, created on 2025-04-24 14:05:34
         compiled from /srv/alfproduct/smarty/templates/default/mypage/lesson_list1_print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/mypage/lesson_list1_print.tpl', 9, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/default/mypage/lesson_list1_print.tpl', 98, false),)), $this); ?>


<div style="width:100%;border: none;font-size:15px;margin-bottom:10px;">
	<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
	<table class="print_table">
		<tr class="table_row"><td class="table_name2" colspan="2"><b>受講履歴（eラーニング）</b></td></tr>
		<tr class="table_row"><td class="table_name">登録番号</td><td class="table_value"><?php echo $this->_tpl_vars['student_info']['lawyer_number']; ?>
</td></tr>
		<tr class="table_row"><td class="table_name">氏名</td><td class="table_value"><?php echo $this->_tpl_vars['student_info']['student_name']; ?>
</td></tr>
		<tr class="table_row"><td class="table_name">講座名</td><td class="table_value"><?php if ($this->_tpl_vars['val']['product_name_TOD'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['val']['product_name_TP'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>掲載終了しました。<?php endif; ?></td></tr>

		<tr class="table_row"><td class="table_name2" colspan="2">受講状況</td></tr>
		<tr class="table_row"><td class="table_name">最終受講日</td><td class="table_value"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['reading_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
		<tr class="table_row"><td class="table_name">受講率</td><td class="table_value"><?php if ($this->_tpl_vars['val']['all_complete_flg']): ?>完了<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['max_percent'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
%<?php endif; ?></td></tr>
		<tr class="table_row"><td class="table_name">受講開始日</td><td class="table_value"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['TSUB_regist_at'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
		<tr class="table_row"><td class="table_name">受講終了日</td><td class="table_value"><?php if ($this->_tpl_vars['val']['all_complete_flg']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['TSUB_complete_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>-<?php endif; ?></td></tr>

		<tr class="table_row"><td class="table_name2" colspan="2">テスト</td></tr>
		<tr class="table_row"><td class="table_name">合否</td><td class="table_value"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['test_passing'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
		<tr class="table_row"><td class="table_name">進捗</td><td class="table_value"><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['test_progress'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td></tr>
	</table>
	<?php endforeach; endif; unset($_from); ?>

	<div style="clear:both;width:100%;" id="btn_area">
		<a class="print" href="javascript:void(0);" onclick="" id="print_btn">印刷する</a>
	</div>

<style type="text/css">
.print_table {
	width: 100%;
	font-size: 24px;
	line-height: 32px;
	border-bottom: double 4px #000000;
	border-right: double 4px #000000;
	border-left: double 4px #000000;
	border-top: double 4px #000000;
}

.table_row {
	border-bottom: solid 2px #000000;
	border-right: solid 2px #000000;
	border-left: solid 2px #000000;
	border-top: solid 2px #000000;
	padding: 0 16px;
}
.table_name {
	border-right: dashed 2px #000000;
	padding: 0 16px;
}
.table_name2 {
	border-right: dashed 2px #000000;
	padding: 0 16px;
	border-top: double 4px #000000;
	background-color: #C8F997;
}
.table_value{
	padding: 0 16px;
	word-wrap: break-word;
	word-break: break-word;
}

#print_btn {
    display: inline-block;
    line-height: 24px;
    height: 24px;
    color: #fff;
    background-color: #756B6B;
    text-decoration: none;
    width: 140px;
    text-align: center;
    border-radius: 4px;
    margin: 4px;
}
</style>
<script type="text/javascript">
$(function(){
  $('.print').click(function(){
    let header = $('header'),
        footer = $('footer');
    header.hide();
    footer.hide();
    
    window.print();
    
    header.show();
    footer.show();
  });
});
</script>
<!--
	<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
		<tr>
			<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:100px;">受講状況</th>
			<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:100px;">受講開始日/終了日</th>
			<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;">講座名</th>
			<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:100px;">テスト合否<br>進捗</th>
		</tr>
		<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
		<?php echo smarty_function_cycle(array('values' => "0,1",'assign' => 'cycle_bg'), $this);?>

		<tr style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>">
			<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">最終受講日<br /></td>
			<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">開始日<br /></td>
			<td rowspan="2" style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"></td>
			<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"></td>
		</tr>
		<tr style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>">
			<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">受講率<br /></td>
			<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">終了日<br /></td>
			<td style="<?php if ($this->_tpl_vars['cycle_bg'] == '1'): ?>background-color: #F5F5F5;<?php else: ?>background-color: #FFFFFF;<?php endif; ?>border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
	</table>
-->
</div>