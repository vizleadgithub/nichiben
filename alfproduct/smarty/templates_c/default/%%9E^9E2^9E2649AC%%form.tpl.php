<?php /* Smarty version 2.6.31, created on 2025-08-08 15:54:31
         compiled from /srv/alfproduct/smarty/templates/default/inquiry/form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/inquiry/form.tpl', 34, false),)), $this); ?>
<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">お問い合わせ</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">お問い合わせ</span>
	</div>

	<div style="float:left;width:710px;margin-left:25px;margin-top:10px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:680px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<p>このたびは総合研修サイトをご利用いただきありがとうございます。</p>
			<ul style="color:#ff6666;list-style:none;">
				<li>※お問い合わせいただく前に・・・</li>
				<li>・<a href="/question">「よくある質問」</a>を確認し、回答が見つからない場合にお問い合わせください。</li>
				<li>・本サイトの利用方法及び本サイトに掲載している講座の内容に関するご質問にご利用ください。上記を除く内容につきましては、回答を差し控えさせていただく場合がございます。予めご了承ください。</li>
			</ul>
			<br>
			★印は必須入力事項です。<br>
			お問い合わせ内容を入力し、「送信する」ボタンをクリックしてください。
			<hr>

			<?php $_from = $this->_tpl_vars['arr_err']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['err']):
?>
			<div class="error" style="color:#ff6666;">※<?php echo $this->_tpl_vars['err']; ?>
</div>
			<?php endforeach; endif; unset($_from); ?>
			<form name="form_inquiry" method="post" action="conf.php">

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
								<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">登録番号★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><?php echo ((is_array($_tmp=$_SESSION['user']['lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">氏名★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><input size="30" type="text" name="input_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:260px;" /></td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">メールアドレス★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><input size="30" type="text" name="input_mail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:260px;" /></td>
				</tr>
							</table>

			<hr>

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">お問い合わせ内容</th>
				</tr>
				<tr>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><textarea name="input_comment" cols="20" rows="5" style="width:630px;max-width:630px;height:260px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['input_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea></td>
				</tr>
			</table>

			<hr>

			<table style="width:100%;">
				<tr>
					<td style="padding:5px;text-align:center;">
						<input type="image" src="/img/btn/submit.png" name="btn_submit" alt="送信する" />
					</td>
				</tr>
			</table>

			</form>
		</div>
	</div>
</div>