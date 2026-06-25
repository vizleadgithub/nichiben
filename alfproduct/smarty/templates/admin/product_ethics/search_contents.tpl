<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>コンテンツID</th>
			<td colspan = "3">
				<input type="text" name="search_contents_code" size="45" value="<!--{$search_contents_code|escape}-->">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">コンテンツ名</th>
			<td colspan = "3" style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_contents_name" size="45" value="<!--{$search_contents_name|escape}-->">
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>

<!--{if $disp_flg}-->
<br />
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>コンテンツ名</th>
		<th style="width:130px;">操作</th>
	</tr>
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.video_id}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.video_logic_name|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="search_set.php?gid=<!--{$gid|urlencode}-->&id=<!--{$row.video_id|urlencode}-->&name=<!--{$row.video_logic_name|escape|urlencode}-->&comment=<!--{$row.video_caption|urlencode}-->">このコンテンツを登録する</a></td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="4">
<!--{$pager}-->
		</th>
	</tr>
</table>
<!--{/if}-->
