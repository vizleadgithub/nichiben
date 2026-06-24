<?php /* Smarty version 2.6.31, created on 2025-06-18 19:46:35
         compiled from /srv/alfproduct/smarty/templates/admin/report_status/exam.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/admin/report_status/exam.tpl', 28, false),)), $this); ?>
<style type="text/css">
body{
font-size:14px;
}
h1{
font-size:16px;
}
tr,th,td{
text-align:left;
}
.btn_area{
text-align:center;
padding:20px 0;
}
</style>

<h1>設問の実施状況一覧</h1>
<table>
	<tr>
		<th style="width:60%;">問題名</th>
		<th style="width:20%;">解答状況</th>
		<th style="width:20%;">合否</th>
	</tr>
	<?php unset($this->_sections['contents_contents']);
$this->_sections['contents_contents']['name'] = 'contents_contents';
$this->_sections['contents_contents']['loop'] = is_array($_loop=@MAX_CONTENTS) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['contents_contents']['show'] = true;
$this->_sections['contents_contents']['max'] = $this->_sections['contents_contents']['loop'];
$this->_sections['contents_contents']['step'] = 1;
$this->_sections['contents_contents']['start'] = $this->_sections['contents_contents']['step'] > 0 ? 0 : $this->_sections['contents_contents']['loop']-1;
if ($this->_sections['contents_contents']['show']) {
    $this->_sections['contents_contents']['total'] = $this->_sections['contents_contents']['loop'];
    if ($this->_sections['contents_contents']['total'] == 0)
        $this->_sections['contents_contents']['show'] = false;
} else
    $this->_sections['contents_contents']['total'] = 0;
if ($this->_sections['contents_contents']['show']):

            for ($this->_sections['contents_contents']['index'] = $this->_sections['contents_contents']['start'], $this->_sections['contents_contents']['iteration'] = 1;
                 $this->_sections['contents_contents']['iteration'] <= $this->_sections['contents_contents']['total'];
                 $this->_sections['contents_contents']['index'] += $this->_sections['contents_contents']['step'], $this->_sections['contents_contents']['iteration']++):
$this->_sections['contents_contents']['rownum'] = $this->_sections['contents_contents']['iteration'];
$this->_sections['contents_contents']['index_prev'] = $this->_sections['contents_contents']['index'] - $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['index_next'] = $this->_sections['contents_contents']['index'] + $this->_sections['contents_contents']['step'];
$this->_sections['contents_contents']['first']      = ($this->_sections['contents_contents']['iteration'] == 1);
$this->_sections['contents_contents']['last']       = ($this->_sections['contents_contents']['iteration'] == $this->_sections['contents_contents']['total']);
?>
	<?php $this->assign('ccno', $this->_sections['contents_contents']['iteration']); ?>
		<?php if ($this->_tpl_vars['arr_list'][$this->_tpl_vars['ccno']]['exam_name'] != ''): ?>
		<tr>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_list'][$this->_tpl_vars['ccno']]['exam_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_list'][$this->_tpl_vars['ccno']]['exam_problem_answer_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['arr_list'][$this->_tpl_vars['ccno']]['exam_problem_count'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_list'][$this->_tpl_vars['ccno']]['gouhi'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
		</tr>
		<?php endif; ?>
	<?php endfor; endif; ?>
</table>
<div class="btn_area">
	<a href="javascript:void(0)" onclick="window.close();">閉じる</a>
</div>