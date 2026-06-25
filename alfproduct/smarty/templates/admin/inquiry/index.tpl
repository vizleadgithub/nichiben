<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
</div>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>投稿日時</th>
			<td>
				<input type="text" name="search_start_date" id="start_date" value="<!--{$search_start_date}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<!--{$search_end_date}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>フリーワード</th>
			<td>
				<input type="text" name="search_keyword" size="45" value="<!--{$search_keyword|escape}-->">
			</td>
		</tr>
		<tr>
			<th>ステータス</th>
			<td>
				<input type="radio" name="search_status" id="search_status0" value=""  <!--{if $search_status==""}-->checked="checked"<!--{/if}-->><label for="search_status0">全て</label>　
				<input type="radio" name="search_status" id="search_status1" value="0" <!--{if $search_status=="0"}-->checked="checked"<!--{/if}-->><label for="search_status1">新規</label>　
				<!--{*<input type="radio" name="search_status" id="search_status2" value="1" <!--{if $search_status=="1"}-->checked="checked"<!--{/if}-->><label for="search_status2">返信済</label>　*}-->
				<input type="radio" name="search_status" id="search_status4" value="3" <!--{if $search_status=="3"}-->checked="checked"<!--{/if}-->><label for="search_status4">対応中</label>　
				<input type="radio" name="search_status" id="search_status3" value="2" <!--{if $search_status=="2"}-->checked="checked"<!--{/if}-->><label for="search_status3">対応済</label>　
			</td>
		</tr>
		<tr>
			<th>登録番号</th>
			<td>
				<input type="text" name="search_lawyer_number" size="45" value="<!--{$search_lawyer_number|escape}-->">
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>登録番号</th>
		<th>お名前</th>
		<th>メールアドレス</th>
		<th style="width:110px;">状態</th>
		<th style="width:110px;">対応者</th>
		<th style="width:133px;">投稿日<br>(返信日)</th>
	</tr>
	<!--{if is_array($arr_list) && count($arr_list) > 0}-->
	<!--{foreach from=$arr_list item="row"}-->
	<tr style="">
		<td class="tdc" ><a href="info.php?iid=<!--{$row.inquiry_id}-->"><!--{$row.inquiry_id}--></a></td>
		<td class="tdc" ><!--{$row.inquiry_lawyer_number|escape}--></td>
		<td class="tdc" ><!--{$row.inquiry_name|escape}--></td>
		<td class="tdc" ><!--{$row.inquiry_mail|escape}--></td>
 		<td class="tdc" >
			<!--{if $row.inquiry_status=="3"}-->対応中
			<!--{elseif $row.inquiry_status=="2"}-->対応済
			<!--{elseif $row.inquiry_status=="1"}-->返信済
			<!--{elseif $row.inquiry_status=="0"}--><span style="color:#ff0000;"><b>新規</b></span>
			<!--{else}--><span style="color:#ff0000;"><b>新規</b></span>
			<!--{/if}-->
		</td>
		<td class="tdc" ><!--{$row.return_name|escape}--></td>
		<td class="tdc" >
			<!--{$row.regist_date}-->
			<!--{if $row.inquiry_status=="1"}--><br>(<!--{$row.update_date }-->)<!--{/if}-->
		</td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="7">
<!--{$pager}-->
		</th>
	</tr>
	<!--{/if}-->
</table>
