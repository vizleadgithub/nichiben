<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="add.php"><span>新規登録</span></a>
</div>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>商品名</th>
			<td colspan = "3">
				<input type="text" name="search_product_name" size="45" value="<!--{$search_product_name|escape}-->">
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>

<!--{if $disp_flg}-->
<br />
<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>商品名</th>
	</tr>
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="info.php?mid=<!--{$row.product_id}-->"><!--{$row.product_id}--></a></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.product_name|mb_truncate:60:"..."|escape}--></td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="4">
<!--{$pager}-->
		</th>
	</tr>
</table>
<!--{/if}-->
