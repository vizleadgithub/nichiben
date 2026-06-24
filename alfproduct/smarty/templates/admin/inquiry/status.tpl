<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>


<form action="<!--{$next_url}-->" accept-charset="utf-8" method="post" name="inquiry_form">
	<input type="hidden" name="iid" value="<!--{$iid|escape}-->">
	<h2>お問い合わせの内容を確認</h2>
	<!--{foreach from=$arr_err item="err"}-->
	<div class="error"><!--{$err}--></div>
	<!--{/foreach}-->
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

	<h2>変更内容を入力してください</h2>
	<table class="form">
		<tr>
			<th>ステータス</th>
			<td>
				<input type="radio" name="inquiry_status" id="inquiry_status4" value="3" <!--{if $inquiry_status=="3"}-->checked="checked"<!--{/if}-->><label for="inquiry_status4">対応中</label>　
				<input type="radio" name="inquiry_status" id="inquiry_status3" value="2" <!--{if $inquiry_status=="2"}-->checked="checked"<!--{/if}-->><label for="inquiry_status3">対応済</label>　
			</td>
		</tr>
		<tr>
			<th style="">対応者</th>
			<td style="">
				<input type="text" name="return_name" value="<!--{$return_name|escape}-->" style="width:600px;">
			</td>
		</tr>
	</table>

	<div class="submit">
		<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
		<a href="javascript:void(0);" onclick="document.inquiry_form.submit();" /><img src="/alfproduct/images/btn_confirm.png"></a>
	</div>
</form>
<br />
