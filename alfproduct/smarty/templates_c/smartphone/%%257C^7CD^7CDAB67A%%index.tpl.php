<?php /* Smarty version 2.6.31, created on 2025-03-03 17:32:14
         compiled from /srv/alfproduct/smarty/templates/smartphone/exam2/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/exam2/index.tpl', 36, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/smartphone/exam2/index.tpl', 48, false),)), $this); ?>
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
<form name="exam2Form" method="post" id="exam2Form">
	<div style="float:left;width:100%;height:36px;background-image: url( /img/lecture/h2_back.png );">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['arr_list']['exam2_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
	</div>

	<div style="float:left;width:100%;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:100%;">
			<?php $_from = $this->_tpl_vars['arr_list']['problem']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
        $this->_foreach['loop']['iteration']++;
?>
				<?php $this->assign('row_no', $this->_foreach['loop']['iteration']); ?>
				<div class="problem" style="font-size: 14px;">
					<div class="problem_title">
						●設問<?php echo $this->_tpl_vars['row_no']; ?>
　<?php echo $this->_tpl_vars['row']['exam2_problem_name']; ?>

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
									<div class="content1"><input type="radio" id="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
" name="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1']['no']; ?>
" <?php if ($this->_tpl_vars['answered_list'][$this->_tpl_vars['row_no']]['answer1'] == $this->_tpl_vars['row1']['no']): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1']; ?>
.</div>
									<div class="content2"><label for="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
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
									<div class="content1"><input type="checkbox" id="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
" name="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
[]" value="<?php echo $this->_tpl_vars['row1']['no']; ?>
" <?php if (array_search ( $this->_tpl_vars['row1']['no'] , $this->_tpl_vars['answered_list'][$this->_tpl_vars['row_no']]['answer2'] ) !== false): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['row_no1']; ?>
.</div>
									<div class="content2"><label for="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
_<?php echo $this->_tpl_vars['row_no1']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row1']['word'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</label></div>
								</div>
								<br style="clear:both;">
							<?php endforeach; endif; unset($_from); ?>
							
												<?php elseif ($this->_tpl_vars['row']['answer_kind'] == 3): ?>
							<div class="problem_content"><textarea name="exam2_problem_<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
[]" cols="60" rows="10" maxlength="1000"><?php echo $this->_tpl_vars['answered_list'][$this->_tpl_vars['row_no']]['answer3']; ?>
</textarea></div>
							
						<?php else: ?>
							未設定
							
						<?php endif; ?>
						<input type="hidden" name="exam2_problem_id[]" value="<?php echo $this->_tpl_vars['row']['exam2_problem_id']; ?>
">
					</div>
				</div>
				<hr>
			<?php endforeach; endif; unset($_from); ?>
			
			<div style="text-align:center;width:100%;display:block;height:auto;">
				<div style="text-align:center;margin:0 auto;width:520px;display:block;height:auto;">
					<a style="float:left; margin-left:0;    margin-right:10px;" class="btn" href="javascript:void(0)" onclick="pop_get_html_sub('/exam2/answer_check.php?e2id=<?php echo $this->_tpl_vars['arr_list']['exam2_id']; ?>
&pid=<?php echo $this->_tpl_vars['pid']; ?>
','exam2Form')">次へ</a>
					<a style="float:right;margin-left:10px; margin-right:0;" class="btn" href="javascript:void(0)" onclick="pop_close()">閉じる</a>
				</div>
			</div>

		</div>
	</div>
</form>