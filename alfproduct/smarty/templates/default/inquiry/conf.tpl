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
				<!--{* ====================================================================== *}-->
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">登録番号★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><!--{$input_lawyer_number|escape}--></td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">氏名★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><!--{$input_name|escape}--></td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">メールアドレス★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><!--{$input_mail|escape}--></td>
				</tr>
				<!--{* 
					会社名<!--{$input_company|escape}-->
					部署名<!--{$input_post|escape}-->
					お電話番号（半角）<!--{$input_tel|escape}-->
				 *}-->
			</table>

			<hr>

			<table style="width:640px;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">お問い合わせ内容</th>
				</tr>
				<tr>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><!--{$input_comment|escape|nl2br}--></td>
				</tr>
			</table>

			<hr>

			<table style="width:100%;">
				<tr>
					<td style="padding:5px;text-align:right;width:50%;">
						<form name="form_inquiry_back" method="post" action="index.php">
							<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
							<input type="hidden" name="input_lawyer_number" value="<!--{$input_lawyer_number|escape}-->" />
							<input type="hidden" name="input_name" value="<!--{$input_name|escape}-->" />
							<input type="hidden" name="input_company" value="<!--{$input_company|escape}-->" />
							<input type="hidden" name="input_post" value="<!--{$input_post|escape}-->" />
							<input type="hidden" name="input_tel" value="<!--{$input_tel|escape}-->" />
							<input type="hidden" name="input_mail" value="<!--{$input_mail|escape}-->" />
							<input type="hidden" name="input_comment" value="<!--{$input_comment|escape}-->" />
							<input type="image" src="/img/btn/back.png" name="btn_submit" alt="戻る" />
						</form>
					</td>
					<td style="padding:5px;text-align:left;width:50%;">
						<form name="form_inquiry_submit" method="post" action="send.php">
							<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
							<input type="hidden" name="input_lawyer_number" value="<!--{$input_lawyer_number|escape}-->" />
							<input type="hidden" name="input_name" value="<!--{$input_name|escape}-->" />
							<input type="hidden" name="input_company" value="<!--{$input_company|escape}-->" />
							<input type="hidden" name="input_post" value="<!--{$input_post|escape}-->" />
							<input type="hidden" name="input_tel" value="<!--{$input_tel|escape}-->" />
							<input type="hidden" name="input_mail" value="<!--{$input_mail|escape}-->" />
							<input type="hidden" name="input_comment" value="<!--{$input_comment|escape}-->" />
							<input type="image" src="/img/btn/submit.png" name="btn_submit" alt="送信する" />
						</form>
					</td>
				</tr>
			</table>

		</div>
	</div>
</div>
