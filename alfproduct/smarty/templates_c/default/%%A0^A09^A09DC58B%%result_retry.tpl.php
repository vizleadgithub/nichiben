<?php /* Smarty version 2.6.27, created on 2023-05-18 14:15:45
         compiled from /srv/alfproduct/smarty/templates/default/ethic_treaning/result_retry.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/ethic_treaning/result_retry.tpl', 29, false),)), $this); ?>

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">倫理研修代替措置研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<div style="width:100%;border-bottom:solid 1px #000000;">
				結果判定
			</div>
		</div>
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">

			<div style="text-align:center;padding:50px;">
			<?php if ($this->_tpl_vars['passed_flg']): ?>
				合格
				<br />
				<br />
				<?php echo ((is_array($_tmp=$this->_tpl_vars['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
の受講が終了しました。
			<?php else: ?>
				不合格（テストの正答数が３問以下）
				<br />
				<br />
				レポートを提出してください。<br />
				レポート課題は，後日，日弁連よりご連絡いたします。
			<?php endif; ?>
			<br />判定日時：<?php echo ((is_array($_tmp=$this->_tpl_vars['judge_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</div>

			<div style="text-align:center;padding:20px;">
				<a href="/product/detail.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a>
			</div>
		</div>
	</div>
</div>