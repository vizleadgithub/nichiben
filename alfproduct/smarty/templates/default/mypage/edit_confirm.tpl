<!--{include file='mypage/side_menu.tpl'}-->
<div style="float:right;width:720px;">

		<div id="single_title" style="margin-bottom:20px;">
			<h3>登録情報変更</h3>
			<h6>edit</h6>
		</div>

	<script type="text/javascript">
	function pageSubmit(flg){
	if(flg == 1){
		document.getElementById("act").value = "back";
	} else if(flg == 2){
		document.getElementById("act").value = "complete";
	}
	document.form1.action = "edit.php";
	document.form1.submit();
	}
	</script>

	<form name="form1" action="#" method="post">
	<input type="hidden" id="act" name="act" value="" />
	<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
	<!--{foreach from=$arr_input item=item key=key}-->
		<!--{if $key=="mail_magazine"}-->
			<!--{foreach from=$arr_input.arr_mail_magazine item=item2 key=key2}-->
				<input type="hidden" name="mail_magazine[]" value="<!--{$item2|escape}-->" />
			<!--{/foreach}-->
		<!--{else}-->
			<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
		<!--{/if}-->
	<!--{/foreach}-->

	<div style="border: solid 1px #47a6d4;padding: 1px;">
	<table style="width:100%;" class="member_table">
		<!--{* ====================================================================== *}-->
		<tr>
			<th style="width:230px;background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">氏名</th>
			<td style="padding:5px;"><!--{$arr_input.name1|escape}-->&nbsp;<!--{$arr_input.name2|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">フリガナ</th>
			<td style="padding:5px;"><!--{$arr_input.kana1|escape}-->&nbsp;<!--{$arr_input.kana2|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">都道府県</th>
			<td style="padding:5px;"><!--{$arr_input.str_pref|escape}--></td>
		</tr>
<!--{*
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">郵便番号</th>
			<td style="padding:5px;"><!--{$arr_input.zip|escape}--></td>
		</tr>
*}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">市区町村</th>
			<td style="padding:5px;"><!--{$arr_input.address1|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">番地</th>
			<td style="padding:5px;"><!--{$arr_input.address2|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">ビル名・マンション名</th>
			<td style="padding:5px;"><!--{$arr_input.address3|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">メールアドレス</th>
			<td style="padding:5px;"><!--{$arr_input.email|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワード</th>
			<td style="padding:5px;">******<!--{*$arr_input.password|escape*}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(質問)</th>
			<td style="padding:5px;"><!--{$arr_input.str_password_question|escape}--></td>
		</tr>
		<!--{if $arr_input.password_answer==""}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(答え)</th>
			<td style="padding:5px;">変更無し</td>
		</tr>
		<!--{else}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(答え)</th>
			<td style="padding:5px;"><!--{$arr_input.password_answer|escape}--></td>
		</tr>
		<!--{/if}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">職業</th>
			<td style="padding:5px;"><!--{$arr_input.str_job|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">業種</th>
			<td style="padding:5px;"><!--{$arr_input.str_job_type|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">学校名</th>
			<td style="padding:5px;"><!--{$arr_input.school_name|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">学年</th>
			<td style="padding:5px;"><!--{$arr_input.str_school_grade|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">年代</th>
			<td style="padding:5px;"><!--{$arr_input.str_age|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">性別</th>
			<td style="padding:5px;"><!--{$arr_input.str_gender|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">伊藤塾塾生番号</th>
			<td style="padding:5px;"><!--{$arr_input.student_no|escape}--></td>
		</tr>
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">メールマガジンについて</th>
			<td style="padding:5px;"><!--{if $arr_input.mail_magazine_flag == 1}-->受け取る<!--{else}-->受け取らない<!--{/if}--></td>
		</tr>
		<!--{if $arr_input.mail_magazine_flag == 1}-->
		<tr>
			<th style="background-color:#47a6d4;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">受け取るメールマガジン</th>
			<td style="padding:5px;"><!--{$arr_input.str_mail_magazine|escape}--></td>
		</tr>
		<!--{/if}-->
		<!--{* ====================================================================== *}-->
	</table>
	</div>

	<hr>

	<table style="width:100%;" class="member_table">
		<tr style="border-bottom: 0px hidden #ffffff;">
			<td style="width:50%;text-align:right;padding:5px;">
				<a href="javascript:void(0);" onclick="pageSubmit(1);" >
					<img src="/img/btn/back.png">
				</a>
			</td>
			<td style="width:50%;text-align:left;padding:5px;">
				<a href="javascript:void(0);" onclick="pageSubmit(2);" >
					<img src="/img/btn/register.png">
				</a>
			</td>
		</tr>
	</table>

</form>
</div>
