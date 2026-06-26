<script type="text/javascript">
function pageSubmit(flg){
if(flg == 1){
	document.getElementById("act").value = "back";
} else if(flg == 2){
	document.getElementById("act").value = "complete";
}
document.form1.action = "regist.php";
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
	<!--{elseif $key=="media"}-->
		<!--{foreach from=$arr_input.arr_media item=item2 key=key2}-->
			<input type="hidden" name="media[]" value="<!--{$item2|escape}-->" />
		<!--{/foreach}-->
	<!--{else}-->
		<input type="hidden" name="<!--{$key}-->" value="<!--{$item|escape}-->" />
	<!--{/if}-->
<!--{/foreach}-->

<div style="border: solid 1px #cccccc; margin-bottom:20px;">
<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
	<!--{* ====================================================================== *}-->
	<tr>
		<th style="width:230px;background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">氏名</th>
		<td style="padding:5px;"><!--{$arr_input.name1|escape}-->&nbsp;<!--{$arr_input.name2|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">フリガナ</th>
		<td style="padding:5px;"><!--{$arr_input.kana1|escape}-->&nbsp;<!--{$arr_input.kana2|escape}--></td>
	</tr>
<!--{*
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">郵便番号</th>
		<td style="padding:5px;"><!--{$arr_input.zip|escape}--></td>
	</tr>
*}-->
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">都道府県</th>
		<td style="padding:5px;"><!--{$arr_input.str_pref|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">メールアドレス</th>
		<td style="padding:5px;"><!--{$arr_input.email|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">パスワード</th>
		<td style="padding:5px;">******<!--{*$arr_input.password|escape*}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(質問)</th>
		<td style="padding:5px;"><!--{$arr_input.str_password_question|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">パスワードを忘れた時のヒント(答え)</th>
		<td style="padding:5px;"><!--{$arr_input.password_answer|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">職業</th>
		<td style="padding:5px;"><!--{$arr_input.str_job|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">業種</th>
		<td style="padding:5px;"><!--{$arr_input.str_job_type|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">学校名</th>
		<td style="padding:5px;"><!--{$arr_input.school_name|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">学年</th>
		<td style="padding:5px;"><!--{$arr_input.str_school_grade|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">年代</th>
		<td style="padding:5px;"><!--{$arr_input.str_age|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">性別</th>
		<td style="padding:5px;"><!--{$arr_input.str_gender|escape}--></td>
	</tr>
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">伊藤塾塾生番号</th>
		<td style="padding:5px;"><!--{$arr_input.student_no|escape}--></td>
	</tr>
<!--{*
	<tr>
		<th style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">当サイトをどのようにお知りになりましたか？</th>
		<td style="padding:5px;"><!--{$arr_input.str_media|escape}--></td>
	</tr>
*}-->
	<!--{* ====================================================================== *}-->
</table>
</div>

<!--{if !empty($arr_media)}-->
<div style="border: solid 1px #cccccc; margin-bottom:20px;">
<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
	<!--{* ====================================================================== *}-->
	<tr>
		<th colspan="2" style="background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">当サイトをどのようにお知りになりましたか？(複数回答可)</th>
	</tr>
	<tr>
		<td style="padding:5px;">
			<!--{foreach name=media from=$mtb_media item=val}-->
				<!--{foreach name=media_child from=$val.media_child item=val1}-->
					<!--{foreach name=loop from=$arr_media item=val2}-->
						<!--{if $val2 == $val1.id}-->
							<!--{$val1.name|escape}-->
							<!--{if $smarty.foreach.loop.iteration != $media_count}-->,<!--{/if}-->
						<!--{/if}-->
					<!--{/foreach}-->
				<!--{/foreach}-->
			<!--{/foreach}-->
		</td>
	</tr>
	
<!--{* カテゴリが二階層になった場合は、ここを復活させる
	<!--{foreach from=$mtb_media item=val}-->
		<tr>
			<th style="width:230px;background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;"><!--{$val.parent_name|escape}--></th>
			<td>
				<!--{foreach from=$val.media_child item=val1}-->
					<!--{foreach from=$arr_media item=val2}-->
						<!--{if $val2 == $val1.id}-->
						<!--{$val1.name|escape}-->
						<!--{/if}-->
					<!--{/foreach}-->
				<!--{/foreach}-->
			</td>
		</tr>
	<!--{/foreach}-->
*}-->
	<!--{* ====================================================================== *}-->
</table>
</div>
<!--{/if}-->

<div style="border: solid 1px #cccccc; margin-bottom:20px;">
<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
	<!--{* ====================================================================== *}-->
	<tr>
		<th style="width:230px;background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">メールマガジンについて</th>
		<td style="padding:5px;"><!--{if $arr_input.mail_magazine_flag == 1}-->受け取る<!--{else}-->受け取らない<!--{/if}--></td>
	</tr>
	<!--{if $arr_input.mail_magazine_flag==1}-->
	<tr>
		<th style="width:230px;background-color:#e4f3fa;color:#666666;text-align:left;vertical-align:top;padding:5px;">受け取るメールマガジン</th>
		<td style="padding:5px;"><!--{$arr_input.str_mail_magazine|escape}--></td>
	</tr>
	<!--{/if}-->
	<!--{* ====================================================================== *}-->
</table>
</div>

<div style="border: hidden 0px #ffffff;padding: 1px;">
<table style="width:100%;" class="member_table">
	<tr style="border:none;">
		<td style="width:50%;text-align:right;padding:5px;">
			<a href="javascript:void(0);"><img src="/img/btn/back.png" alt="戻る" onclick="pageSubmit(1);" /></a>
		</td>
		<td style="width:50%;text-align:left;padding:5px;">
			<a href="javascript:void(0);"><img src="/img/btn/register.png" alt="登録する" onclick="pageSubmit(2);" /></a>
		</td>
	</tr>
</table>
</div>

</form>
