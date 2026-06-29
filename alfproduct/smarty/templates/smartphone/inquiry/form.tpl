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

			<!--{foreach from=$arr_err item="err"}-->
			<div class="error" style="color:#ff6666;">※<!--{$err}--></div>
			<!--{/foreach}-->
			<form name="form_inquiry" method="post" action="conf.php">
			<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<!--{* ====================================================================== *}-->
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">登録番号★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><!--{$smarty.session.user.lawyer_number|escape}--></td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">氏名★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><input size="30" type="text" name="input_name" value="<!--{$input_name|escape}-->" style="width:260px;" /></td>
				</tr>
				<tr>
					<th style="width:130px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">メールアドレス★</th>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><input size="30" type="text" name="input_mail" value="<!--{$input_mail|escape}-->" style="width:260px;" /></td>
				</tr>
				<!--{* 
					会社名<input size="30" type="text" name="input_company" value="<!--{$input_company|escape}-->" style="width:260px;" />
					部署名<input size="30" type="text" name="input_post" value="<!--{$input_post|escape}-->" style="width:260px;" />
					お電話番号（半角）<font color="red">※必須</font><input size="30" type="text" name="input_tel" value="<!--{$input_tel|escape}-->" style="width:260px;" />
				 *}-->
			</table>

			<hr>

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;font-weight:normal;">お問い合わせ内容</th>
				</tr>
				<tr>
					<td style="padding:5px;background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;font-weight:normal;"><textarea name="input_comment" cols="20" rows="5" style="width:630px;max-width:630px;height:260px;"><!--{$input_comment|escape}--></textarea></td>
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
