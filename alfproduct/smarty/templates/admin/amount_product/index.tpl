<h2>検索する内容を入力してください</h2>

<form action="index.php" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>購入期間</th>
			<td>
				<input type="text" name="search_start_buy_date" id="start_date" value="<!--{$search_start_buy_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_buy_date" id="end_date" value="<!--{$search_end_buy_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>月別集計</th>
			<td>
				<input type="checkbox" name="search_monthly" id="search_monthly" value="1" <!--{if $search_monthly=="1"}--> checked="checked"<!--{/if}--> /><label for="search_monthly">する</label>
			</td>
		</tr>
		<tr>
			<th>商品名</th>
			<td>
				<input type="text" name="search_product_name" value="<!--{$search_product_name|escape}-->" />
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td>
				<input type="text" name="search_product_code" value="<!--{$search_product_code|escape}-->" />
			</td>
		</tr>
		<tr>
			<th>商品種別</th>
			<td>
				<!--{foreach from=$arr_product_type_add item="row"}-->
					<input type="checkbox" name="search_product_type_add[]" value="<!--{$row.id}-->" id="search_product_type_add_<!--{$row.id}-->"<!--{if in_array($row.id,$search_product_type_add)}--> checked="checked"<!--{/if}-->><label for="search_product_type_add_<!--{$row.id}-->"><!--{$row.name}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
		<!--{if $bar_association_id=="1"}-->
		<tr>
			<th>決済方法</th>
			<td>
				<!--{foreach from=$arr_payment_type item="row"}-->
					<input type="checkbox" name="search_payment_type[]" value="<!--{$row.id}-->" id="search_payment_type_<!--{$row.id}-->"<!--{if in_array($row.id,$search_payment_type)}--> checked="checked"<!--{/if}-->><label for="search_payment_type_<!--{$row.id}-->"><!--{$row.name}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
		<tr>
			<th>請求書</th>
			<td>
				<!--{foreach from=$arr_claim_flg item="row"}-->
					<input type="checkbox" name="search_claim_flg[]" value="<!--{$row.id}-->" id="search_claim_flg_<!--{$row.id}-->"<!--{if in_array($row.id,$search_claim_flg)}--> checked="checked"<!--{/if}-->><label for="search_claim_flg_<!--{$row.id}-->"><!--{$row.name}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
		<!--{/if}-->

	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<!--{if $disp_flg}-->
	<a href="csv.php?data=<!--{$post_data|escape|urlencode}-->" target="_blank" rel="noopener noreferrer"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
	（全<!--{$all_count}-->件）

	<!--{if $search_monthly==""}-->
	<table class="list">
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:;">商品ID</th>
			<th>商品名</th>
			<th style="width:;">商品コード</th>
			<th style="width:;">販売単価</th>
			<th style="width:;">販売数</th>
			<th style="width:;">合計</th>
		</tr>
		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<!--{cycle values="0,1" assign="cycle_bg"}-->
		<tr style="">
			<!--{if $row.product_type_add=="1"}-->
				<td class="tdc" style=""><a href="/alfproduct/product/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></td>
			<!--{elseif $row.product_type_add=="2"}-->
				<td class="tdc" style=""><a href="/alfproduct/product_live/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></td>
			<!--{elseif $row.product_type_add=="3"}-->
				<td class="tdc" style=""><a href="/alfproduct/product_ethics/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></td>
			<!--{elseif $row.product_type_add=="4"}-->
				<td class="tdc" style=""><a href="/alfproduct/product_passport/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></td>
			<!--{/if}-->
			<td class="tdc" style=""><!--{$row.product_name|escape}--></td>
			<td class="tdc" style=""><!--{$row.product_code|escape}--></td>
			<td class="tdc" style=""><!--{$row.pay_total|escape|number_format}-->円</td>
			<td class="tdc" style=""><!--{$row.buy_count|escape|number_format}--></td>
			<td class="tdc" style=""><!--{$row.all_pay_total|escape|number_format}-->円</td>
		</tr>
		<!--{/foreach}-->
		<tr>
			<th class="pager" colspan="4">
			</th>
			<td class="tdc"><!--{$all_buy_count|escape|number_format}--></th>
			<td class="tdc"><!--{$all_pay_total|escape|number_format}-->円</th>

		</tr>
		<!--{/if}-->
	</table>
	<!--{elseif $search_monthly=="1"}-->
	<table class="list">
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:;">月</th>
			<th style="width:;">販売数</th>
			<th style="width:;">合計</th>
		</tr>
		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<!--{cycle values="0,1" assign="cycle_bg"}-->
		<tr style="">
			<td class="tdc" style=""><a href=""><a href="?post_data=<!--{$post_data|escape}-->&buy_y=<!--{$row.buy_y|escape}-->&buy_m=<!--{$row.buy_m|escape}-->">[<!--{$row.buy_y|escape}-->/<!--{$row.buy_m|escape}-->]</a></td>
			<td class="tdc" style=""><!--{$row.buy_count|escape|number_format}--><!--{*$row.product_id|escape|number_format*}--></td>
			<td class="tdc" style=""><!--{$row.all_pay_total|escape|number_format}--><!--{*$row.pay_total|escape|number_format*}-->円</td>
		</tr>
		<!--{/foreach}-->
		<tr>
			<th class="pager">
			</th>
			<td class="tdc"><!--{$all_buy_count|escape|number_format}--></th>
			<td class="tdc"><!--{$all_pay_total|escape|number_format}-->円</th>

		</tr>
		<!--{/if}-->
	</table>
	<!--{/if}-->
<!--{/if}-->
