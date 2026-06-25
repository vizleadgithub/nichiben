<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>

	<h2>お問い合わせの内容を確認</h2>
	<table class="form">
		<!--{if $iid!=""}-->
		<tr>
			<th style="width:120px;">ID</th>
			<td style=""><!--{$iid|escape}--></td>
		</tr>
		<!--{/if}-->
		<tr>
			<th>投稿日</th>
			<td style="">
				<!--{$arr_data.regist_date|escape}-->
			</td>
		</tr>
		<tr>
			<th>お名前</th>
			<td style="">
				<!--{$arr_data.inquiry_name|escape}-->
			</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td style="">
				<!--{$arr_data.inquiry_mail|escape}-->
			</td>
		</tr>
		<tr>
			<th>お問い合わせ内容</th>
			<td style="">
				<!--{$arr_data.inquiry_comment|escape|nl2br}-->
			</td>
		</tr>
	</table>

	<h2>変更内容を確認</h2>
	<table class="form">
		<tr>
			<th>ステータス</th>
			<td style="">
				<!--{if $inquiry_status=="3"}-->対応中
				<!--{elseif $inquiry_status=="2"}-->対応済
				<!--{elseif $inquiry_status=="1"}-->返信済
				<!--{elseif $inquiry_status=="0"}--><span style="color:#ff0000;"><b>新規</b></span>
				<!--{else}--><span style="color:#ff0000;"><b>新規</b></span>
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">対応者</th>
			<td style="">
				<!--{$return_name|escape}-->
			</td>
		</tr>
	</table>


	<div class="submit">
		<div style="width:48%;float:left;text-align:right;">
			<form action="<!--{$prev_url|escape}-->" accept-charset="utf-8" method="post" name="inquiry_form_prev">
			<input type="hidden" name="iid" value="<!--{$iid|escape}-->">
			<input type="hidden" name="inquiry_status" value="<!--{$inquiry_status|escape}-->">
			<input type="hidden" name="return_name" value="<!--{$return_name|escape}-->">
			<input type="image" src="/alfproduct/images/btn_back.png">
			</form>
		</div>
		<div style="width:48%;float:right;text-align:left;">
			<form action="<!--{$next_url|escape}-->" accept-charset="utf-8" method="post" name="inquiry_form_next">
			<input type="hidden" name="iid" value="<!--{$iid|escape}-->">
			<input type="hidden" name="inquiry_status" value="<!--{$inquiry_status|escape}-->">
			<input type="hidden" name="return_name" value="<!--{$return_name|escape}-->">
			<input type="image" src="/alfproduct/images/btn_ok.png">
			</form>
		</div>
	</div>
<br />
