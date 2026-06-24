<?php /* Smarty version 2.6.27, created on 2017-10-15 17:09:33
         compiled from /srv/alfproduct/smarty/templates/smartphone/exam/result.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/exam/result.tpl', 42, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/exam/result.tpl', 42, false),)), $this); ?>
<style type="text/css">
.problem .problem_title{
padding:5px 0px;
}
.problem .problem_contents{
padding:10px 40px;
}
.problem .problem_contents .problem_content{
padding:5px 0px;
}
.problem_result{
color:blue;
text-align:center;
}
.problem_result div{
padding:5px 0px;
}
.problem_comment{
background-color:#ececec;
text-align:center;
}
.problem_comment div{
padding:5px 0px;
}
</style>

<form name="examForm" action="#" method="post">
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
			</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<?php $_from = $this->_tpl_vars['arr_list']['problem']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
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
							<?php $_from = $this->_tpl_vars['row']['answer_contents_select']['answer_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1'] => $this->_tpl_vars['row1']):
        $this->_foreach['loop1']['iteration']++;
?>
							<?php $this->assign('row_no1', $this->_foreach['loop1']['iteration']); ?>
								<div class="problem_content"><input type="radio" id="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
" name="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1']['no']; ?>
" disabled <?php if ($this->_tpl_vars['row1']['no'] == $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'][0]): ?>checked<?php endif; ?>><label for="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
"><?php echo $this->_tpl_vars['row_no1']; ?>
. <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row']['answer_kind'] == 2): ?>
							<?php $_from = $this->_tpl_vars['row']['answer_contents_select']['answer_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1'] => $this->_tpl_vars['row1']):
        $this->_foreach['loop1']['iteration']++;
?>
							<?php $this->assign('row_no1', $this->_foreach['loop1']['iteration']); ?>
								<div class="problem_content"><input type="checkbox" id="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
" name="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1']['no']; ?>
" disabled <?php if (array_search ( $this->_tpl_vars['row1']['no'] , $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'] ) !== false): ?>checked<?php endif; ?>><label for="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
"><?php echo $this->_tpl_vars['row_no1']; ?>
. <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row']['answer_kind'] == 3): ?>
							<div class="problem_content"><textarea name="exam_problem_<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
[]" cols="115" rows="10" disabled><?php echo $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'][0]; ?>
</textarea></div>
							
						<?php else: ?>
							未設定
							
						<?php endif; ?>
					</div>
					<?php if (! $this->_tpl_vars['question_flg']): ?>
												<?php if ($this->_tpl_vars['row']['answer_kind'] == 1 || $this->_tpl_vars['row']['answer_kind'] == 2): ?>
							<div class="problem_title">
								●採点結果
							</div>
							<div class="problem_contents problem_result">
								<div><?php if ($this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_mark'] == 1): ?>正解<?php else: ?>不正解<?php endif; ?></div>
								<div>正解は「<?php echo $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['correct_answer_str']; ?>
」、あなたの解答は「<?php echo $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents_str']; ?>
」</div>
							</div>
						<?php endif; ?>
						
												<?php if ($this->_tpl_vars['row']['answer_explain_kind'] == 1): ?>
							<div class="problem_contents problem_comment">
								<div>解説</div>
								<div><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['answer_explain_contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
								<div><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['answer_explain_note'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<hr>
			<?php endforeach; endif; unset($_from); ?>
			
			<?php $_from = $this->_tpl_vars['arr_list_q']['problem']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop_q'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop_q']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key_q'] => $this->_tpl_vars['row_q']):
        $this->_foreach['loop_q']['iteration']++;
?>
			<?php $this->assign('row_no_q', $this->_foreach['loop_q']['iteration']); ?>
				<div class="problem">
					<div class="problem_title">
						●設問<?php echo $this->_tpl_vars['row_no_q']+$this->_tpl_vars['row_no']; ?>
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
							<?php $_from = $this->_tpl_vars['row_q']['answer_contents_select']['answer_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop1_q'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1_q']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1_q'] => $this->_tpl_vars['row1_q']):
        $this->_foreach['loop1_q']['iteration']++;
?>
							<?php $this->assign('row_no1_q', $this->_foreach['loop1_q']['iteration']); ?>
								<div class="problem_content"><input type="radio" id="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
" name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1_q']['no']; ?>
" disabled <?php if ($this->_tpl_vars['row1_q']['no'] == $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'][0]): ?>checked<?php endif; ?>><label for="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
"><?php echo $this->_tpl_vars['row_no1_q']; ?>
. <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1_q']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row_q']['answer_kind'] == 2): ?>
							<?php $_from = $this->_tpl_vars['row_q']['answer_contents_select']['answer_contents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop1_q'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop1_q']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key1_q'] => $this->_tpl_vars['row1_q']):
        $this->_foreach['loop1_q']['iteration']++;
?>
							<?php $this->assign('row_no1_q', $this->_foreach['loop1_q']['iteration']); ?>
								<div class="problem_content"><input type="checkbox" id="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
" name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1_q']['no']; ?>
" disabled <?php if (array_search ( $this->_tpl_vars['row1_q']['no'] , $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'] ) !== false): ?>checked<?php endif; ?>><label for="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
"><?php echo $this->_tpl_vars['row_no1_q']; ?>
. <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1_q']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row_q']['answer_kind'] == 3): ?>
							<div class="problem_content"><textarea name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" cols="115" rows="10" disabled><?php echo $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'][0]; ?>
</textarea></div>
							
						<?php else: ?>
							未設定
							
						<?php endif; ?>
					</div>
				</div>
				<hr>
			<?php endforeach; endif; unset($_from); ?>
			
			<div style="text-align:center;padding:20px;">
				<a href="/product/detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a>
			</div>

		</div>
	</div>
</div>
</form>