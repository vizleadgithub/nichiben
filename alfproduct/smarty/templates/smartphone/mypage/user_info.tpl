<!--{include file='mypage/side_menu.tpl'}-->

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
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$user_info.student_name|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">登録番号</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$user_info.lawyer_number|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">登録年月日</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$user_info.regist_date|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">所属弁護士会</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$mtb_bar_association[$user_info.bar_association_id]|escape}--></td>
				</tr>
<!--
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">研修パスポート料金</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{*$user_info.target_passport|escape*}--><!--{$passport_price|number_format}-->円</td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">研修パスポートの有無</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{if $user_info.presence_passport==1}-->有<!--{else}-->無&nbsp;&nbsp;&nbsp;<input type="button" value="研修パスポートのご案内" onClick="location.href = '/product/list_passport.php';" /><!--{/if}--></td>
				</tr>
-->
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">メールアドレス</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$user_info.student_email|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;">メールマガジンの受信</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{if $user_info.mailmagazine_flg==1}-->有<!--{else}-->無<!--{/if}--></td>
				</tr>
			</table>
			<div style="padding-top:10px;">
			会員専用ページで情報を変更する→<input type="button" value="会員専用ページ" onClick="var w=window.open();w.location.href='https://member.nichibenren.or.jp/memberinfo/'" />
			</div>
		</div>
	</div>
</div>
