<?php /* Smarty version 2.6.27, created on 2014-01-24 14:42:23
         compiled from /srv/alfproduct/smarty/templates/smartphone/ethic_treaning/answer_history_all.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', '/srv/alfproduct/smarty/templates/smartphone/ethic_treaning/answer_history_all.tpl', 20, false),array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/ethic_treaning/answer_history_all.tpl', 31, false),array('modifier', 'string_format', '/srv/alfproduct/smarty/templates/smartphone/ethic_treaning/answer_history_all.tpl', 31, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">倫理研修代替措置研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">

<?php $_from = $this->_tpl_vars['arr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
        $this->_foreach['loop']['iteration']++;
?>
<?php $this->assign('loop_cnt', $this->_foreach['loop']['iteration']); ?>
<?php $this->assign('answer_date', ((is_array($_tmp='answer_date')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['loop_cnt']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['loop_cnt']))); ?>
<?php $this->assign('question', ((is_array($_tmp='question')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['loop_cnt']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['loop_cnt']))); ?>
		
		<?php if ($this->_tpl_vars['loop_cnt'] == 1): ?>
			<div style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;margin:10px;padding:15px;font-size:18px;font-weight:bold;clear:both;text-align:center;background-color:#F0F8FF;">倫理研修テスト</div>
		<?php elseif ($this->_tpl_vars['loop_cnt'] == 11): ?>
			<div style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;margin:10px;padding:15px;font-size:18px;font-weight:bold;clear:both;text-align:center;background-color:#F0F8FF;">倫理研修追試</div>
		<?php endif; ?>
		
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<div style="width:100%;border-bottom:solid 1px #000000;">
				設問【<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['question_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%02d") : smarty_modifier_string_format($_tmp, "%02d")); ?>
】
				回答日：<?php echo ((is_array($_tmp=$this->_tpl_vars['history_answer'][$this->_tpl_vars['answer_date']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</div>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['question'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

		</div>
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<div style="width:100%;">
				<div style="width:100%;border-bottom:solid 1px #000000;">【選択肢】</div>

				<table style="width:100%;text-align:left;">
					<?php $_from = $this->_tpl_vars['item']['branch_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key1'] => $this->_tpl_vars['item1']):
?>
					<?php $this->assign('answer_ethic_branch_id', ((is_array($_tmp='answer_ethic_branch_id')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['loop_cnt']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['loop_cnt']))); ?>
						<?php if ($this->_tpl_vars['history_answer'][$this->_tpl_vars['answer_ethic_branch_id']] == $this->_tpl_vars['key1'] && $this->_tpl_vars['item1']['answer_flg'] == 1): ?>
							<?php $this->assign('style_background', 'background-color:#f2dddc;'); ?>
							<?php $this->assign('str_disp', '<span style="color:#00b050;">あなたの回答</span><br /><span style="color:#ff0000;">正答</span>'); ?>
						<?php elseif ($this->_tpl_vars['history_answer'][$this->_tpl_vars['answer_ethic_branch_id']] == $this->_tpl_vars['key1']): ?>
							<?php $this->assign('style_background', 'background-color:#d7e4bc;'); ?>
							<?php $this->assign('str_disp', '<span style="color:#00b050;">あなたの回答</span>'); ?>
						<?php elseif ($this->_tpl_vars['item1']['answer_flg'] == 1): ?>
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
							<td style="vertical-align:middle;"><?php echo ((is_array($_tmp=$this->_tpl_vars['item1']['question_branch'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
						</tr>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			</div>
		</div>
<?php endforeach; endif; unset($_from); ?>

		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">

			<div style="text-align:center;padding:20px;">
				<input type="button" value="設問・解説動画一覧に戻る" onclick="javascript:location.href='/ethic_treaning/result_history.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
';">	
			</div>
		</div>
	</div>
</div>