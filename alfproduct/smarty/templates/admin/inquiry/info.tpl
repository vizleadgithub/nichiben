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

	<!--{if $arr_data.inquiry_status=="1"}-->
	<h2>返信内容を確認</h2>
	<table class="form">
		<tr>
			<th style="width:120px;">ステータス</th>
			<td style="">
				<!--{if $arr_data.inquiry_status=="3"}-->対応中
				<!--{elseif $arr_data.inquiry_status=="2"}-->対応済
				<!--{elseif $arr_data.inquiry_status=="1"}-->返信済
				<!--{elseif $arr_data.inquiry_status=="0"}--><span style="color:#ff0000;"><b>新規</b></span>
				<!--{else}--><span style="color:#ff0000;"><b>新規</b></span>
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th>返信日時</th>
			<td style="">
				<!--{$arr_data.update_date|escape}-->
			</td>
		</tr>
		<tr>
			<th>対応者</th>
			<td style="">
				<!--{$arr_data.return_name|escape}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">件名</th>
			<td style="">
				<!--{$arr_data.return_title|escape}-->
			</td>
		</tr>
		<tr>
			<th>本文</th>
			<td style="">
				<!--{$arr_data.return_comment|escape|nl2br}-->
			</td>
		</tr>
	</table>
	<!--{elseif $arr_data.inquiry_status=="2" || $arr_data.inquiry_status=="3"}-->
	<h2>返信内容を確認</h2>
	<table class="form">
		<tr>
			<th style="width:120px;">ステータス</th>
			<td style="">
				<!--{if $arr_data.inquiry_status=="3"}-->対応中
				<!--{elseif $arr_data.inquiry_status=="2"}-->対応済
				<!--{elseif $arr_data.inquiry_status=="1"}-->返信済
				<!--{elseif $arr_data.inquiry_status=="0"}--><span style="color:#ff0000;"><b>新規</b></span>
				<!--{else}--><span style="color:#ff0000;"><b>新規</b></span>
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th>対応者</th>
			<td style="">
				<!--{$arr_data.return_name|escape}-->
			</td>
		</tr>
	</table>
	<!--{/if}-->

	<div class="submit">
		<!--{if $arr_data.inquiry_status=="0" || $arr_data.inquiry_status=="3"}-->
		<script type="text/javascript">
			
			function delete_item(msg){
				if(window.confirm( msg )){
					location.href = "delete.php?iid=<!--{$iid}-->";
				}
			}
			function edit_item(){
				location.href ="edit.php?iid=<!--{$iid}-->";
			}
			function status_item(){
				location.href ="status.php?iid=<!--{$iid}-->";
			}
		</script>
		<form>
		<div style="width:45%;float:left;text-align:right;">
			<a href="javascript:void(0);" onclick="location.href ='index.php?page=<!--{$page}-->';return false;" /><img src="/alfproduct/images/btn_back.png"></a>
		</div>
		<div style="width:10%;float:left;text-align:center;">&nbsp;
			<!--{*<a href="javascript:void(0);" onclick="edit_item();return false;" /><img src="/alfproduct/images/btn_revise2.png"></a>*}-->
		</div>
		<div style="width:45%;float:left;text-align:left;">
			<a href="javascript:void(0);" onclick="status_item();return false;" /><img src="/alfproduct/images/btn_status.png"></a>
		</div>
		<!--{*
		<div style="width:40%;float:right;text-align:left;">
			<a href="javascript:void(0);" onclick="delete_item('本当に削除してもよろしいですか？' );return false;" /><img src="/alfproduct/images/btn_delete.png"></a>
		</div>
		*}-->
		</form>
		<!--{else}-->
		<script type="text/javascript">
			
			function delete_item(msg){
				if(window.confirm( msg )){
					location.href = "delete.php?iid=<!--{$mid}-->";
				}
			}
			
			function edit_item(){
				location.href ="edit.php?iid=<!--{$iid}-->";
			}
			function status_item(){
				location.href ="status.php?iid=<!--{$iid}-->";
			}
		</script>
		<form>
			<a href="javascript:void(0);" onclick="location.href ='index.php?page=<!--{$page}-->';return false;" /><img src="/alfproduct/images/btn_back.png"></a>
		</form>
		<!--{/if}-->
	</div>

<br />
