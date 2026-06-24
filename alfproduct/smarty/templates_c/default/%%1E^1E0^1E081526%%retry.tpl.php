<?php /* Smarty version 2.6.31, created on 2025-02-18 07:39:09
         compiled from /srv/alfproduct/smarty/templates/default/ethic_treaning/retry.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/ethic_treaning/retry.tpl', 31, false),array('modifier', 'string_format', '/srv/alfproduct/smarty/templates/default/ethic_treaning/retry.tpl', 31, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">倫理研修代替措置研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			【設問・解説動画一覧】<br />
			設問【11】～設問【16】のテストを受け、解説動画を全て視聴するまで、結果は判定されません。<br />
			全てのテストを受け解説動画を全て視聴した後、<b>必ず</b>「結果判定」ボタンを押して、結果を確認してください。<br />
			<font color="red"><b>※「結果判定」のボタンを押し、結果を確認しないと、合格していても受講履歴が反映されませんのでご注意ください。</b></font>

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">&nbsp;</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">テスト</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">解説動画(再生時間)</th>
				</tr>
				<?php $_from = $this->_tpl_vars['arr_list']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }$this->_foreach['loop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['loop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['row']):
        $this->_foreach['loop']['iteration']++;
?>
				<tr style="background-color: #FFFFFF;">
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問【<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']['question_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%02d") : smarty_modifier_string_format($_tmp, "%02d")); ?>
】</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
						<table><tr>
							<td style="text-align:center;width:180px;">
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<?php if ($this->_tpl_vars['row']['test_flg']): ?>
									<a href="/ethic_treaning/question_retry.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&qid=<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><!--
										--><img src="/img/lecture/test_btn.png"><!--
									--></a>
								<?php else: ?>
									<a href="/ethic_treaning/answer_history.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&qid=<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><!--
										--><img src="/img/lecture/answer_btn.png"><!--
									--></a>
								<?php endif; ?>
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
								<?php if ($this->_tpl_vars['row']['answer_ethic_branch_id'] != ''): ?>
									<?php if (! $this->_tpl_vars['is_sp']): ?>
									<a href="javascript: void(0);" onclick="playerEthicCommentaryFormSubmit('<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['video_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');return false;">
										<img src="/img/lecture/commentary_btn.png" alt="解説動画" style="cursor:pointer;" />
									</a>
									<?php endif; ?>
									(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['duration'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)
								<?php else: ?>
									<?php if (! $this->_tpl_vars['is_sp']): ?>
									<img src="/img/lecture/commentary_btn_02.png">
									<?php endif; ?>
									(<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['duration'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)
								<?php endif; ?>
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
							</td><td style="text-align:center;width:80px;">
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<?php if ($this->_tpl_vars['row']['disp_view'] == "視聴完了"): ?>
									<img src="/img/lecture/end_viwe.png">
								<?php elseif ($this->_tpl_vars['row']['disp_view'] == "視聴中"): ?>
									<img src="/img/lecture/now_viwe.png">
								<?php elseif ($this->_tpl_vars['row']['disp_view'] == "未視聴"): ?>
									<img src="/img/lecture/no_viwe.png">
								<?php endif; ?>
																<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
							</td>
						</tr></table>
					</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			</table>

			<div style="padding-top:10px;">
				※受講状況（受講率等）の表示は、１日１回更新されます。<br />
				<?php if ($this->_tpl_vars['is_sp']): ?>
				<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span><br />
				<?php endif; ?>
				※「結果判定」ボタンは、全てのテストを受け解説動画を全て視聴した後に表示されます。<br />　表示されない場合は、「Ctrl+F5」等でページの更新を行ってください。
			</div>

			<div style="text-align:center;padding:20px;">
				<a href="/product/detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a>
				<?php if ($this->_tpl_vars['hantei_flg']): ?>
				<a href="/ethic_treaning/result_retry.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/result_btn.png" alt="結果判定" /></a>
				<?php endif; ?>
			</div>

		</div>
	</div>
</div>

<form name="playerForm" action="#" method="post">
<input type="hidden" name="vid" id="hid_vid" value="" />
<input type="hidden" name="pid" id="hid_pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['pid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="back_type" id="hid_back_type" value="retry" />
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