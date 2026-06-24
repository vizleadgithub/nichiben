<?php /* Smarty version 2.6.31, created on 2025-03-15 12:58:15
         compiled from /srv/alfproduct/smarty/templates/default/ethic_treaning/result_history.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/ethic_treaning/result_history.tpl', 22, false),array('modifier', 'string_format', '/srv/alfproduct/smarty/templates/default/ethic_treaning/result_history.tpl', 61, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">日弁連倫理研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			あなたの各設問の結果等が確認できます。

			<div style="padding:20px 0;">
				<div style="text-align:center;">
					<!--<?php echo ((is_array($_tmp=$this->_tpl_vars['str_result'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
-->
					<br />
					<br />
					<?php if ($this->_tpl_vars['judge_flg'] == 1): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
の受講が完了しました。
					<?php elseif ($this->_tpl_vars['judge_flg'] == 2): ?>
						講座詳細ページに戻り、追試を受けてください。
					<?php elseif ($this->_tpl_vars['judge_flg'] == 3): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
の受講が完了しました。
					<?php elseif ($this->_tpl_vars['judge_flg'] == 4): ?>
						レポートを提出してください。<br />
						レポート課題は、後日、日弁連よりご連絡いたします。
					<?php endif; ?>
					<?php if ($this->_tpl_vars['judge_flg'] == 5 || $this->_tpl_vars['judge_flg'] == 6): ?>
						日弁連倫理研修（レポート）の受講が完了しました。
					<?php else: ?>
						<br />判定日時：<?php echo ((is_array($_tmp=$this->_tpl_vars['judge_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<?php endif; ?>
				</div>
			</div>
<?php if ($this->_tpl_vars['judge_flg'] == 5): ?>
	<table style="text-align:center;margin:20px 0 0 240px;">
	<tr>
	<td style="padding:10px;"><a href="/product/detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a></td>
	</tr>
	</table>
<?php else: ?>
			<?php if (! empty ( $this->_tpl_vars['arr_list'] )): ?>
				<div style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;padding:15px;font-size:18px;font-weight:bold;clear:both;text-align:center;background-color:#F0F8FF;">倫理研修設問</div>
				<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
					<tr>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">&nbsp;</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">解説動画(再生時間)</th>
					</tr>
					<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
        $this->_foreach['loop']['iteration']++;
?>
					<?php if ($this->_tpl_vars['row']['complete_flag'] == '1' && $this->_tpl_vars['row']['answer_ethic_branch_id'] != ''): ?>
						<?php $this->assign('row_no', $this->_foreach['loop']['iteration']); ?>
						<tr style="background-color: #FFFFFF;">
							<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問【<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%02d") : smarty_modifier_string_format($_tmp, "%02d")); ?>
】</th>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:180px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<a href="/ethic_treaning/result_history_detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&qid=<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><!--
											--><img src="/img/lecture/answer_btn.png"><!--
										--></a>
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:100px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<?php if ($this->_tpl_vars['row']['disp_answer'] == "正答"): ?>
											<img src="/img/lecture/ok_test.png">
										<?php elseif ($this->_tpl_vars['row']['disp_answer'] == "誤答"): ?>
											<img src="/img/lecture/ng_test.png">
										<?php elseif ($this->_tpl_vars['row']['disp_answer'] == "未回答"): ?>
											<img src="/img/lecture/no_test.png">
										<?php endif; ?>
																				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:280px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<?php if (! $this->_tpl_vars['is_sp']): ?>
										<a href="javascript: void(0);" onclick="playerEthicCommentaryFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');return false;"><img src="/img/lecture/commentary_btn.png"></a>
										<?php endif; ?>
										(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['duration'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:80px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<img src="/img/lecture/end_viwe.png">
																				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
						</tr>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			<?php endif; ?>
			
			<?php if (! empty ( $this->_tpl_vars['arr_list_add'] )): ?>
				<div style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;margin-top:40px;padding:15px;font-size:18px;font-weight:bold;clear:both;text-align:center;background-color:#F0F8FF;">倫理研修追試</div>
				<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
					<tr>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">&nbsp;</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">解説動画(再生時間)</th>
					</tr>
					<?php $_from = $this->_tpl_vars['arr_list_add']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
        $this->_foreach['loop']['iteration']++;
?>
					<?php if ($this->_tpl_vars['row']['complete_flag'] == '1' && $this->_tpl_vars['row']['answer_ethic_branch_id'] != ''): ?>
						<?php $this->assign('row_no', $this->_foreach['loop']['iteration']+10); ?>
						<tr style="background-color: #FFFFFF;">
							<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問【<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%02d") : smarty_modifier_string_format($_tmp, "%02d")); ?>
】</th>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:180px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<a href="/ethic_treaning/result_history_detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&qid=<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><!--
											--><img src="/img/lecture/answer_btn.png"><!--
										--></a>
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:100px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<?php if ($this->_tpl_vars['row']['disp_answer'] == "正答"): ?>
											<img src="/img/lecture/ok_test.png">
										<?php elseif ($this->_tpl_vars['row']['disp_answer'] == "誤答"): ?>
											<img src="/img/lecture/ng_test.png">
										<?php elseif ($this->_tpl_vars['row']['disp_answer'] == "未回答"): ?>
											<img src="/img/lecture/no_test.png">
										<?php endif; ?>
																				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:280px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<?php if (! $this->_tpl_vars['is_sp']): ?>
										<a href="javascript: void(0);" onclick="playerEthicCommentaryFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');return false;"><img src="/img/lecture/commentary_btn.png"></a>
										<?php endif; ?>
										(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['duration'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:80px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<img src="/img/lecture/end_viwe.png">
																				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
						</tr>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</table>
			<?php endif; ?>

			<div style="padding-top:10px;">
				※受講状況（受講率等）の表示は、１日１回更新されます。<br />
				<?php if ($this->_tpl_vars['is_sp']): ?>
				<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span>
				<?php endif; ?>
			</div>

			<table style="text-align:center;margin:20px 0 0 60px;">
			<tr>
			<td style="padding:10px;"><a href="/product/detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a></td>
			<td style="padding:10px;"><a href="/ethic_treaning/answer_history_all.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/zen_kaito_ichiran.png" alt="全回答内容一覧" /></a></td>
			</tr>
			</table>
<?php endif; ?>
		</div>
	</div>
</div>
<form name="playerForm" action="#" method="post">
<input type="hidden" name="vid" id="hid_vid" value="" />
<input type="hidden" name="pid" id="hid_pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="back_type" id="hid_back_type" value="result_history" />
</form>

<script type="text/javascript">
function playerEthicCommentaryFormSubmit(vid,ftn,ccno,view_btn){
    var w = window.open("about:blank","playerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
    setTimeout(function(){
        w.onLoad = playerEthicCommentaryOpenWindowSubmit(vid,ftn,ccno,view_btn);
    }, 1000);
}
function playerEthicCommentaryOpenWindowSubmit(vid,ftn,ccno,view_btn){
    document.getElementById("hid_vid").value = vid;
    document.playerForm.target = "playerDisp";
    document.playerForm.method = "post";
    document.playerForm.action = "/player/player_ethic_commentary.php?term=pc";
    document.playerForm.submit();
}
</script>