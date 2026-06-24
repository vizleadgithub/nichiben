<?php /* Smarty version 2.6.31, created on 2026-04-03 15:39:32
         compiled from /srv/alfproduct/smarty/templates/default/exam/result.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/exam/result.tpl', 100, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/default/exam/result.tpl', 100, false),)), $this); ?>
<style type="text/css">
.problem .problem_title{
padding:5px 0px;
}
.problem .problem_contents{
padding:10px 40px;
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
input.btn1{
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
width:200px;
float:right;
border:none;
}
input.btn2{
display: block;
text-align: center;
vertical-align: middle;
background: #666666;
font-size: 16px;
line-height: 40px;
height: 40px;
color: #ffffff;
text-decoration: none;
border-radius: 8px;
width:200px;
float:left;
border:none;
}
</style>

<script type="text/javascript">
function examFormSubmit(flg){
	if(flg=='prev'){
		document.examForm.action = "/product/detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
";
	} else if(flg=='exec'){
		document.examForm.action = "/exam/resubmit_exec.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['eid']; ?>
<?php if ($this->_tpl_vars['qid'] != ''): ?>&qid=<?php echo $this->_tpl_vars['qid']; ?>
<?php endif; ?>";
	} else {
		document.examForm.action = "/exam/result.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['eid']; ?>
<?php if ($this->_tpl_vars['qid'] != ''): ?>&qid=<?php echo $this->_tpl_vars['qid']; ?>
<?php endif; ?>";
	}
	document.examForm.submit();
}
</script>

<form name="examForm" action="#" method="post">
<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
			</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<?php if ($this->_tpl_vars['hantei_ari'] && ! $this->_tpl_vars['passing_flg']): ?>
				<div style="text-align:left;padding:10px 10px 30px 10px;font-size:14px;">
※本講座は，テストに全問正解しないと次のパートに進むことができません。<br>
※不正解と表示された設問の解答を修正して下さい（修正する設問の解答を修正→「解答を修正する」をクリック）。<br>
				</div>
			<?php endif; ?>
			
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
					<div class="problem_contents">
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['problem_note'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

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
" <?php if (isset ( $this->_tpl_vars['exam_answer'] ) && isset ( $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']] ) && isset ( $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'] ) && $this->_tpl_vars['row1']['no'] == $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'][0]): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1']; ?>
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
" <?php if (isset ( $this->_tpl_vars['exam_answer'] ) && isset ( $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']] ) && isset ( $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'] ) && array_search ( $this->_tpl_vars['row1']['no'] , $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'] ) !== false): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1']; ?>
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
[]" cols="115" rows="10"><?php echo $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents'][0]; ?>
</textarea></div>
							
						<?php else: ?>
							未設定
							
						<?php endif; ?>
						<input type="hidden" name="exam_problem_id[]" value="<?php echo $this->_tpl_vars['row']['exam_problem_id']; ?>
">
					</div>
					<?php if (! $this->_tpl_vars['question_flg']): ?>
						<?php if ($this->_tpl_vars['row']['answer_kind'] == 1 || $this->_tpl_vars['row']['answer_kind'] == 2): ?>
							<div class="problem_title">
								●採点結果
							</div>
							<div class="problem_contents problem_result">
								<div><?php if ($this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_mark'] == 1): ?>正解<?php else: ?>不正解<?php endif; ?></div>
								<?php if ($this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_mark'] == 1 || ! $this->_tpl_vars['hantei_ari']): ?>
									<div>正解は「<?php echo $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['correct_answer_str']; ?>
」、あなたの解答は「<?php echo $this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_contents_str']; ?>
」</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['row']['answer_explain_kind'] == 1): ?>
							<?php if ($this->_tpl_vars['exam_answer'][$this->_tpl_vars['row']['exam_problem_id']]['exam_answer_mark'] == 1 || ! $this->_tpl_vars['hantei_ari']): ?>
								<div class="problem_contents problem_comment">
									<div>解説</div>
									<div style="text-align:left;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['answer_explain_contents'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
									<div style="text-align:left;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['answer_explain_note'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<hr>
			<?php endforeach; endif; unset($_from); ?>
			
			<?php $_from = $this->_tpl_vars['arr_list_q']['problem']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop_q'] = array('total' => count($_from), 'iteration' => 0);
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
" <?php if (isset ( $this->_tpl_vars['exam_answer_q'] ) && isset ( $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']] ) && isset ( $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'] ) && $this->_tpl_vars['row1_q']['no'] == $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'][0]): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1_q']; ?>
.</div>
									<div class="content2"><label for="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1_q']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row_q']['answer_kind'] == 2): ?>
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
" <?php if (isset ( $this->_tpl_vars['exam_answer_q'] ) && isset ( $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']] ) && isset ( $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'] ) && array_search ( $this->_tpl_vars['row1_q']['no'] , $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'] ) !== false): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1_q']; ?>
.</div>
									<div class="content2"><label for="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1_q']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1_q']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row_q']['answer_kind'] == 3): ?>
							<div class="problem_content"><textarea name="exam_problem_q_<?php echo $this->_tpl_vars['row_q']['exam_problem_id']; ?>
[]" cols="115" rows="10"><?php echo $this->_tpl_vars['exam_answer_q'][$this->_tpl_vars['row_q']['exam_problem_id']]['exam_answer_contents'][0]; ?>
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
			
			<div style="text-align:center;width:420px;margin-left:246px;">
				<input class="btn2" type="button" onclick="examFormSubmit('prev');" value="終了する">
				<input class="btn1" type="button" onclick="examFormSubmit('exec');" value="<?php if ($this->_tpl_vars['btn_type'] == '2'): ?>回答を修正する<?php else: ?>解答を修正する<?php endif; ?>">
			</div>
		</div>
	</div>
</div>
</form>