<?php /* Smarty version 2.6.31, created on 2025-04-03 11:33:42
         compiled from /srv/alfproduct/smarty/templates/default/ethic_treaning/question_answer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/ethic_treaning/question_answer.tpl', 18, false),array('modifier', 'string_format', '/srv/alfproduct/smarty/templates/default/ethic_treaning/question_answer.tpl', 18, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">日弁連倫理研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<div style="width:100%;border-bottom:solid 1px #000000;">設問【<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['question_no'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('string_format', true, $_tmp, "%02d") : smarty_modifier_string_format($_tmp, "%02d")); ?>
】</div>
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
					<?php echo ((is_array($_tmp=$this->_tpl_vars['str_answer'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

					<br />
					<br />
					解説動画を必ず視聴してください。
				</div>
				<div style="text-align:center;padding:20px;">
					<input type="button" value="設問・解説動画一覧に戻る" onclick="javascript:location.href='/ethic_treaning/?pid=<?php echo $this->_tpl_vars['pid']; ?>
';">	
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
window.onunload = function(){};
history.forward();
</script>