<?php /* Smarty version 2.6.31, created on 2025-01-24 02:37:27
         compiled from /srv/alfproduct/smarty/templates/default/inquiry/conf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/srv/alfproduct/smarty/templates/default/inquiry/conf.tpl', 28, false),array('modifier', 'nl2br', '/srv/alfproduct/smarty/templates/default/inquiry/conf.tpl', 52, false),)), $this); ?>
<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">お問い合わせ</div>
</div>

<div style="float:right;width:710px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:680px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">お問い合わせ</span>
	</div>

	<div style="float:left;width:660px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:640px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<p>このたびは総合研修サイトをご利用いただきありがとうございます。</p>
			<ul style="color:#ff6666;list-style:none;">
				<li>※お問い合わせいただく前に・・・</li>
				<li>・<a href="/question">「よくある質問」</a>を確認し、回答が見つからない場合にお問い合わせください。</li>
				<li>・本サイトの利用方法及び本サイトに掲載している講座の内容に関するご質問にご利用ください。上記を除く内容につきましては、回答を差し控えさせていただく場合がございます。予めご了承ください。</li>
			</ul>
			<br>
			お問い合わせ内容を確認し、「送信する」ボタンをクリックしてください。
			<hr>

			<table style="width:640px;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
								<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">登録番号★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><?php echo ((is_array($_tmp=$this->_tpl_vars['input_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">氏名★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><?php echo ((is_array($_tmp=$this->_tpl_vars['input_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">メールアドレス★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><?php echo ((is_array($_tmp=$this->_tpl_vars['input_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
							</table>

			<hr>

			<table style="width:640px;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">お問い合わせ内容</th>
				</tr>
				<tr>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['input_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
				</tr>
			</table>

			<hr>

			<table style="width:100%;">
				<tr>
					<td style="padding:5px;text-align:right;width:50%;">
						<form name="form_inquiry_back" method="post" action="index.php">
							<input type="hidden" name="input_lawyer_number" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_company" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_company'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_post" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_post'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_tel" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_tel'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_mail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_comment" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="image" src="/img/btn/back.png" name="btn_submit" alt="戻る" />
						</form>
					</td>
					<td style="padding:5px;text-align:left;width:50%;">
						<form name="form_inquiry_submit" method="post" action="send.php">
							<input type="hidden" name="input_lawyer_number" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_lawyer_number'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_company" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_company'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_post" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_post'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_tel" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_tel'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_mail" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_mail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="hidden" name="input_comment" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['input_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
							<input type="image" src="/img/btn/submit.png" name="btn_submit" alt="送信する" />
						</form>
					</td>
				</tr>
			</table>

		</div>
	</div>
</div>