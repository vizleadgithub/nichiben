<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">商品名</th>
			<td colspan = "3" style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_product_name" size="45" value="<!--{$search_product_name|escape}-->">
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td colspan = "3">
				<input type="text" name="search_product_code" size="45" value="<!--{$search_product_code|escape}-->">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">公開期間</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_start_date" id="start_date" value="<!--{$search_start_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<!--{$search_end_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
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
		<th>商品名</th>
		<th style="width:300px;">公開期間</th>
		<th style="width:130px;">操作</th>
	</tr>
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.product_id}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.product_name}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.start_date}-->～<!--{$row.end_date}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="search_set.php?gid=<!--{$gid}-->&id=<!--{$row.product_id}-->&name=<!--{$row.product_name}-->">この商品を設定する</a></td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="4">
<!--{$pager}-->
		</th>
	</tr>
</table>
<!--{/if}-->
