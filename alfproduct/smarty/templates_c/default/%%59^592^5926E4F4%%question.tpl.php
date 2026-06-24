<?php /* Smarty version 2.6.31, created on 2025-04-03 11:21:12
         compiled from /srv/alfproduct/smarty/templates/default/ethic_treaning/question.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', '/srv/alfproduct/smarty/templates/default/ethic_treaning/question.tpl', 35, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">日弁連倫理研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<?php if ($this->_tpl_vars['answer_btn_disp_flg']): ?>
			<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
				<div style="width:100%;border-bottom:solid 1px #000000;">設問</div>
				<?php echo $this->_tpl_vars['question']; ?>

				<?php if ($this->_tpl_vars['reference'] != ''): ?>
					<p>
					</p>
					<p>
						<?php echo $this->_tpl_vars['reference']; ?>

					</p>
				<?php endif; ?>
			</div>


			<div style="width:100%;float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border-bottom:solid 1px #000000;">【選択肢】</div>
			<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
				<form action="/ethic_treaning/question_answer.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&qid=<?php echo $this->_tpl_vars['qid']; ?>
" method="post" name="form_answer">
					<div style="width:100%;">
						<?php echo smarty_function_html_radios(array('name' => 'ethic_branch_id','options' => $this->_tpl_vars['arr_list'],'separator' => '<br />'), $this);?>

					</div>
					<?php if ($this->_tpl_vars['reference'] != ''): ?>
						<!--
						<div style="width:100%;padding-top:10px;">
							<?php echo $this->_tpl_vars['reference']; ?>

						</div>
						-->
					<?php endif; ?>
					<ul style="text-align:center;padding:20px;list-style:none;padding-left:280px;">
						<li style="float:left;padding:0 20px;"><a href="javascript:void(0);" onclick="javascript:location.href='/ethic_treaning/?pid=<?php echo $this->_tpl_vars['pid']; ?>
';"><img src="/img/button/question1_btn.png" alt="今は回答しない" /></a></li>
						<li style="float:left;padding:0 20px;"><a href="javascript:void(0);" onclick="javascript:document.form_answer.submit();"><img src="/img/button/question2_btn.png" alt="回答する" /></a></li>
					</ul>
				</form>
			</div>
		<?php else: ?>
			<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
				<div style="text-align:center;">回答済みの問題です。</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
window.onunload = function(){};
history.forward();
</script>