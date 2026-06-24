<?php /* Smarty version 2.6.31, created on 2025-03-31 14:29:29
         compiled from /srv/alfproduct/smarty/templates/default/mypage/user_info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/mypage/user_info.tpl', 24, false),array('modifier', 'number_format', '/srv/alfproduct/smarty/templates/default/mypage/user_info.tpl', 41, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'mypage/side_menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">会員情報確認</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">会員情報確認</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたの会員情報を表示しています。<br />
			氏名・登録番号・登録年月日・所属弁護士会はご自身で変更ができません。<br />
			メールアドレス・メールマガジンの受信について変更する場合は、右下のボタンをクリックして会員専用ページで変更してください。
			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;width:230px;">氏名</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['student_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">登録番号</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">登録年月日</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['regist_date'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">所属弁護士会</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['mtb_bar_association'][$this->_tpl_vars['user_info']['bar_association_id']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
<!--
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">研修パスポート料金</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['passport_price'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
円</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">研修パスポートの有無</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php if ($this->_tpl_vars['user_info']['presence_passport'] == 1): ?>有<?php else: ?>無&nbsp;&nbsp;&nbsp;<input type="button" value="研修パスポートのご案内" onClick="location.href = '/product/list_passport.php';" /><?php endif; ?></td>
				</tr>
-->
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">メールアドレス</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_info']['student_email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">メールマガジンの受信</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><?php if ($this->_tpl_vars['user_info']['mailmagazine_flg'] == 1): ?>有<?php else: ?>無<?php endif; ?></td>
				</tr>
			</table>
			<div style="padding-top:10px;">
			会員専用ページで情報を変更する→<input type="button" value="会員専用ページ" onClick="var w=window.open();w.location.href='https://member.nichibenren.or.jp/memberinfo/'" />
			</div>
		</div>
	</div>
</div>