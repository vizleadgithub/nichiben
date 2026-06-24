<?php /* Smarty version 2.6.31, created on 2025-10-09 10:52:16
         compiled from /srv/alfproduct/smarty/templates/smartphone/mypage/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/smartphone/mypage/index.tpl', 114, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'mypage/side_menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">マイページ</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">マイページ</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<span style="font-size:20px;color:#5E4D34;font-weight: bold;"><?php echo $_SESSION['user']['name']; ?>
　様</span>

		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			日弁連総合研修サイトマイページへようこそ。<br />
			このページはあなたの研修情報が確認できます。<br />
			左のメニューからご希望のボタンをクリックしてください。<br />
		</div>
	</div>

	



	<?php if (! $_SESSION['user']['bar_association_duty_year']): ?>
	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:0px;border: none;margin-bottom:20px;">
			<div style="">
				<div style="float:left;height:32px;padding-top: 7px;">
					あなたの日弁連の倫理研修義務年度は完了しています。
				</div>
			</div>
		</div>
	</div>
	<?php else: ?>
	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:0px;border: none;margin-bottom:20px;">
			<div style="">
				<div style="float:left;height:32px;padding-top: 7px;">
					あなたの日弁連の倫理研修義務年度は
				</div>
				<div style="float:left;height:32px;padding-top: 7px;width:152px;background-image: url( /img/mypage/date_backpng.png );background-repeat : no-repeat;text-align:center;margin-right:15px;margin-left:15px;">
					<span style="color:#F69F3E;font-weight: bold;"><?php echo $_SESSION['user']['bar_association_duty_year']; ?>
度</span>
				</div>
				<div style="float:left;height:32px;padding-top: 7px;">
					です。
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($_SESSION['user']['sub_auth_ethic_training'] == 1): ?>
	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:0px;border: none;margin-bottom:50px;">
			<div style="float:left;height:auto;padding-top: 7px;">
				<a href="/product/detail.php?pid=<?php echo ((is_array($_tmp=$this->_tpl_vars['ethic_product_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><img src="/img/button/e_learning_btn.png" alt="倫理研修" onMouseOver="this.src='/img/button/e_learning_btn_on.png'" onMouseOut="this.src='/img/button/e_learning_btn.png'" /></a><br>
				※義務年度に関わらず、日弁連倫理研修（ｅラーニング）を受講することができます。<br>
				<br>
				※日弁連の研修受講義務は本研修の受講により履行することが可能です。<br>
				<br>
				<font color="red">※各弁護士会の研修受講義務については、義務の有無や履行の要件等、各会により異なります。<br>
				　日弁連倫理研修（ｅラーニング）を受講しても、所属弁護士会の倫理研修義務を履行したことにならない場合がありますので、詳細は所属弁護士会にご確認ください。</font>

			</div>
			<div>
				
			</div>
		</div>
	</div>
	<?php endif; ?>

</div>
