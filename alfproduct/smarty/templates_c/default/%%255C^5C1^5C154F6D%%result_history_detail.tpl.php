<?php /* Smarty version 2.6.31, created on 2025-04-02 19:10:57
         compiled from /srv/alfproduct/smarty/templates/default/ethic_treaning/result_history_detail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/ethic_treaning/result_history_detail.tpl', 19, false),array('modifier', 'string_format', '/srv/alfproduct/smarty/templates/default/ethic_treaning/result_history_detail.tpl', 19, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">日弁連倫理研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<div style="width:100%;border-bottom:solid 1px #000000;">
				設問【<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['question_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%02d") : smarty_modifier_string_format($_tmp, "%02d")); ?>
】
				回答日：<?php echo ((is_array($_tmp=$this->_tpl_vars['history_answer']['answer_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</div>
			<?php echo $this->_tpl_vars['question']; ?>

			<?php if ($this->_tpl_vars['reference'] != ''): ?>
				<p>
				</p>
				<p>
					<?php echo $this->_tpl_vars['reference']; ?>

				</p>
			<?php endif; ?>
		</div>
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<form action="/ethic_treaning/question_answer.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&qid=<?php echo $this->_tpl_vars['qid']; ?>
" method="post" name="form_answer">
				<div style="width:100%;">
					<div style="width:100%;border-bottom:solid 1px #000000;">【選択肢】</div>

					<table style="width:100%;text-align:left;">
						<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
						<?php if ($this->_tpl_vars['history_answer']['answer_ethic_branch_id'] == $this->_tpl_vars['key'] && $this->_tpl_vars['item']['answer_flg'] == 1): ?>
							<?php $this->assign('style_background', 'background-color:#f2dddc;'); ?>
							<?php $this->assign('str_disp', '<span style="color:#00b050;">あなたの回答</span><br /><span style="color:#ff0000;">正答</span>'); ?>
						<?php elseif ($this->_tpl_vars['history_answer']['answer_ethic_branch_id'] == $this->_tpl_vars['key']): ?>
							<?php $this->assign('style_background', 'background-color:#d7e4bc;'); ?>
							<?php $this->assign('str_disp', '<span style="color:#00b050;">あなたの回答</span>'); ?>
						<?php elseif ($this->_tpl_vars['item']['answer_flg'] == 1): ?>
							<?php $this->assign('style_background', 'background-color:#f2dddc;'); ?>
							<?php $this->assign('str_disp', '<span style="color:#ff0000;">正答</span>'); ?>
						<?php else: ?>
							<?php $this->assign('style_background', ''); ?>
							<?php $this->assign('str_disp', ''); ?>
						<?php endif; ?>
						<tr style="border-bottom:solid 1px #000000;<?php echo $this->_tpl_vars['style_background']; ?>
">
							<th style="vertical-align:middle;width:150px;"><?php echo $this->_tpl_vars['str_disp']; ?>
</th>
							<td style="vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['question_branch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
					</table>
				</div>
				<div style="text-align:center;padding:20px;">
					<input type="button" value="設問・解説動画一覧に戻る" onclick="javascript:location.href='/ethic_treaning/result_history.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
';">	
				</div>
			</form>
		</div>
	</div>
</div>