<?php /* Smarty version 2.6.31, created on 2025-09-25 13:59:54
         compiled from /srv/alfproduct/smarty/templates/default/exam/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/exam/index.tpl', 52, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/default/exam/index.tpl', 64, false),)), $this); ?>
<style type="text/css">
.problem .problem_title{
padding:10px 0px;
}
.problem .problem_contents{
padding:0px 40px 20px 40px;
}
.problem .problem_contents .problem_content{
padding:20px 0px;
}
.problem .problem_contents .problem_content .content1{
width:5%;
float:left;
}
.problem .problem_contents .problem_content .content2{
width:95%;
float:right;
}
a.btn{
display: block;
text-align: center;
vertical-align: middle;
background: #0097dd;
font-size: 16px;
line-height: 40px;
height: 40px;
color: #ffffff;
text-decoration: none;
border-radius: 8px;
width:250px;
margin-left:310px;
}
</style>

<script type="text/javascript">
function examFormSubmit(eid,pid,ccno){
	window.open("about:blank","examDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
	document.examForm.target = "examDisp";
	document.examForm.method = "post";
	<?php if ($this->_tpl_vars['qid'] != ''): ?>
		document.examForm.action = "/exam/answer_check.php?eid="+eid+"&pid="+pid+"&ccno="+ccno+"&qid="+<?php echo $this->_tpl_vars['qid']; ?>
;
	<?php else: ?>
		document.examForm.action = "/exam/answer_check.php?eid="+eid+"&pid="+pid+"&ccno="+ccno;
	<?php endif; ?>
	document.examForm.submit();
}
</script>

<form name="examForm" action="#" method="post">
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_list']['exam_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<?php $_from = $this->_tpl_vars['arr_list']['problem']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
        $this->_foreach['loop']['iteration']++;
?>
			<?php $this->assign('row_no', $this->_foreach['loop']['iteration']); ?>
				<div class="problem">
					<div class="problem_title">
						●設問<?php echo $this->_tpl_vars['row_no']; ?>
　<?php echo $this->_tpl_vars['row']['exam_problem_name']; ?>

					</div>
					<div class="problem_contents">
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['problem_contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

					</div>
					<div class="problem_title">
						●解答
					</div>
					<div class="problem_contents">
												<?php if ($this->_tpl_vars['row']['answer_kind'] == 1): ?>
							<?php $_from = $this->_tpl_vars['row']['answer_contents_select']['answer_contents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1'] => $this->_tpl_vars['row1']):
        $this->_foreach['loop1']['iteration']++;
?>
							<?php $this->assign('row_no1', $this->_foreach['loop1']['iteration']); ?>
								<div class="problem_content">
									<div class="content1"><input type="radio" id="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
" name="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1']['no']; ?>
" <?php if ($this->_tpl_vars['answered_list'][$this->_tpl_vars['row_no']]['answer1'] == $this->_tpl_vars['row1']['no']): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1']; ?>
.</div>
									<div class="content2"><label for="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row']['answer_kind'] == 2): ?>
							<?php $_from = $this->_tpl_vars['row']['answer_contents_select']['answer_contents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1'] => $this->_tpl_vars['row1']):
        $this->_foreach['loop1']['iteration']++;
?>
							<?php $this->assign('row_no1', $this->_foreach['loop1']['iteration']); ?>
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
" name="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1']['no']; ?>
" <?php if (array_search ( $this->_tpl_vars['row1']['no'] , $this->_tpl_vars['answered_list'][$this->_tpl_vars['row_no']]['answer2'] ) !== false): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1']; ?>
.</div>
									<div class="content2"><label for="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row']['answer_kind'] == 3): ?>
							<div class="problem_content"><textarea name="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
[]" cols="115" rows="10" maxlength="1000"><?php echo $this->_tpl_vars['answered_list'][$this->_tpl_vars['row_no']]['answer3']; ?>
</textarea></div>
							
						<?php else: ?>
							未設定
							
						<?php endif; ?>
						<input type="hidden" name="exam_problem_id[]" value="<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
">
					</div>
				</div>
				<hr>
			<?php endforeach; endif; unset($_from); ?>

			<?php $_from = $this->_tpl_vars['arr_list_q']['problem']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop_q'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_q']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key_q'] => $this->_tpl_vars['row_q']):
        $this->_foreach['loop_q']['iteration']++;
?>
			<?php $this->assign('row_no_q', $this->_foreach['loop_q']['iteration']+$this->_tpl_vars['row_no']); ?>
				<div class="problem">
					<div class="problem_title">
						●設問<?php echo $this->_tpl_vars['row_no_q']; ?>
　<?php echo $this->_tpl_vars['row_q']['exam_problem_name']; ?>

					</div>
					<div class="problem_contents">
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row_q']['problem_contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

					</div>
					<div class="problem_title">
						●解答
					</div>
					<div class="problem_contents">
												<?php if ($this->_tpl_vars['row_q']['answer_kind'] == 1): ?>
							<!--[単一形式]-->
							<?php $_from = $this->_tpl_vars['row_q']['answer_contents_select']['answer_contents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop1_q'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1_q']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1_q'] => $this->_tpl_vars['row1_q']):
        $this->_foreach['loop1_q']['iteration']++;
?>
							<?php $this->assign('row_no1_q', $this->_foreach['loop1_q']['iteration']); ?>
								<div class="problem_content">
									<div class="content1"><input type="radio" id="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
" name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1_q']['no']; ?>
" <?php if ($this->_tpl_vars['answered_list_q'][$this->_tpl_vars['row_no_q']]['answer1'] == $this->_tpl_vars['row1_q']['no']): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1_q']; ?>
.</div>
									<div class="content2"><label for="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1_q']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row_q']['answer_kind'] == 2): ?>
							<!--[複数形式]-->
							<?php $_from = $this->_tpl_vars['row_q']['answer_contents_select']['answer_contents']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop1_q'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1_q']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1_q'] => $this->_tpl_vars['row1_q']):
        $this->_foreach['loop1_q']['iteration']++;
?>
							<?php $this->assign('row_no1_q', $this->_foreach['loop1_q']['iteration']); ?>
								<div class="problem_content">
									<div class="content1"><input type="checkbox" id="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
" name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1_q']['no']; ?>
" <?php if (array_search ( $this->_tpl_vars['row1_q']['no'] , $this->_tpl_vars['answered_list_q'][$this->_tpl_vars['row_no_q']]['answer2'] ) !== false): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1_q']; ?>
.</div>
									<div class="content2"><label for="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1_q']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row_q']['answer_kind'] == 3): ?>
							<!--[フリー解答]-->
							<div class="problem_content"><textarea name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" cols="115" rows="10" maxlength="1000"><?php echo $this->_tpl_vars['answered_list_q'][$this->_tpl_vars['row_no_q']]['answer3']; ?>
</textarea></div>
							
						<?php else: ?>
							未設定
							
						<?php endif; ?>
						<input type="hidden" name="exam_problem_id_q[]" value="<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
">
					</div>
				</div>
				<hr>
			<?php endforeach; endif; unset($_from); ?>
			
			<div style="text-align:center;padding:20px;">
				<a class="btn" href="javascript:void(0)" onclick="examFormSubmit(<?php echo $this->_tpl_vars['arr_list']['exam_id']; ?>
,<?php echo $this->_tpl_vars['pid']; ?>
,<?php echo $this->_tpl_vars['ccno']; ?>
)">次へ</a>
			</div>

		</div>
	</div>
</div>
</form>