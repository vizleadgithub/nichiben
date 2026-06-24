<?php /* Smarty version 2.6.27, created on 2019-11-01 15:34:27
         compiled from /srv/alfproduct/smarty/templates/smartphone/mypage/lesson_list1_print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/mypage/lesson_list1_print.tpl', 10, false),array('function', 'cycle', '/srv/alfproduct/smarty/templates/smartphone/mypage/lesson_list1_print.tpl', 65, false),)), $this); ?>


<div style="width:100%;border: none;font-size:15px;margin-bottom:10px;">
	<?php $_from = $this->_tpl_vars['arr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
	<p><b>受講履歴（eラーニング）</b></p>
	<br>
	<p>登録番号：<?php echo $this->_tpl_vars['student_info']['lawyer_number']; ?>
</p>
	<p>氏名：<?php echo $this->_tpl_vars['student_info']['student_name']; ?>
</p>
	<br>
	<p>講座名：<?php if ($this->_tpl_vars['val']['product_name_TOD'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TOD'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php elseif ($this->_tpl_vars['val']['product_name_TP'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['product_name_TP'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>掲載終了しました。<?php endif; ?></p>
	<br>
	<p>受講状況</p>
	<p>最終受講日：<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['reading_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
	<p>受講率：<?php if ($this->_tpl_vars['val']['all_complete_flg']): ?>完了<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['max_percent'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
%<?php endif; ?></p>
	<p>受講開始日：<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['TSUB_regist_at'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
	<p>受講終了日：<?php if ($this->_tpl_vars['val']['all_complete_flg']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['val']['TSUB_complete_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>-<?php endif; ?></p>
	<br>
	<p>テスト</p>
	<p>合否：<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['test_passing'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
	<p>進捗：<?php echo ((is_array($_tmp=$this->_tpl_vars['val']['test_progress'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</p>
	<?php endforeach; endif; unset($_from); ?>

	<div style="clear:both;width:100%;" id="btn_area">
		<a class="print" href="javascript:void(0);" onclick="" id="print_btn">印刷する</a>
	</div>

<style type="text/css">
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
		<?php $_from = $this->_tpl_vars['arr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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