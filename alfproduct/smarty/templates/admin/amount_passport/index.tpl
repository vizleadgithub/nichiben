<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<input type="hidden" name="search_orderby" value="<!--{$search_orderby|escape}-->" id="search_orderby">
	<table class="form">
		<tr>
			<th>氏名</th>
			<td>
				<input type="text" name="search_name" value="<!--{$search_name|escape}-->" id="search_name">
				<p style="color:red;">※名前は姓と名の間にスペースを入力してください。</p>
			</td>
		</tr>
		<tr>
			<th>登録番号<span style="color:red;">※半角入力</span></th>
			<td>
				<input type="text" name="search_start_lawyer_number" id="start_lawyer_number" value="<!--{$search_start_lawyer_number|escape}-->" /> 
				～
				<input type="text" name="search_end_lawyer_number" id="end_lawyer_number" value="<!--{$search_end_lawyer_number|escape}-->" /> 
			</td>
		</tr>
		<!--{if $bar_association_id=="1"}-->
		<tr>
			<th>所属弁護士会</th>
			<td>
				<select name="search_association">
					<option value="">-</option>
				<!--{foreach from=$arr_association item="row"}-->
					<option value="<!--{$row.id}-->" <!--{if $row.id==$search_association}--> selected="selected"<!--{/if}-->><!--{$row.name|escape}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<!--{/if}-->
		<tr>
			<th>購入期間</th>
			<td>
				<input type="text" name="search_start_buy_date" id="search_start_buy_date" value="<!--{$search_start_buy_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_start_buy_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_buy_date" id="search_end_buy_date" value="<!--{$search_end_buy_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_end_buy_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>パスポート種別</th>
			<td>
				<!--{foreach from=$arr_passport_target key="passport_target_id" item="passport_target_name"}-->
					<input type="checkbox" name="search_passport_target[]" value="<!--{$passport_target_id}-->" id="search_passport_target_<!--{$passport_target_id}-->"<!--{if in_array($passport_target_id,$search_passport_target)}--> checked="checked"<!--{/if}-->><label for="search_passport_target_<!--{$passport_target_id}-->"><!--{$passport_target_name|escape}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
		<tr>
			<th>入金ステータス</th>
			<td>
				<!--{foreach from=$arr_payment_status item="row"}-->
					<input type="checkbox" name="search_payment_status[]" value="<!--{$row.id}-->" id="search_payment_status_<!--{$row.id}-->"<!--{if in_array($row.id,$search_payment_status)}--> checked="checked"<!--{/if}-->><label for="search_payment_status_<!--{$row.id}-->"><!--{$row.name|escape}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />


<!--{if $disp_flg}-->
	<a href="csv.php?data=<!--{$post_data|escape|urlencode}-->" target="_blank" rel="noopener noreferrer"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
	<!--{$list_start}-->～<!--{$list_end}-->件を表示中（全<!--{$list_max}-->件）
	<table class="list">
		<tr>
			<th style="text-align:left;">
				登録番号
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<!--{if $search_orderby=="1"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<!--{if $search_orderby=="2"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
			</th>
			<th style="text-align:left;">氏名</th>
			<th style="text-align:left;">弁護士会</th>
			<th style="text-align:left;">購入日</th>
			<th style="text-align:left;">商品名</th>
			<th style="text-align:left;">パスポート種別</th>
			<th style="text-align:left;">
				入金
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='3';document.search_form.submit();"<!--{if $search_orderby=="3"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='4';document.search_form.submit();"<!--{if $search_orderby=="4"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
			</th>
			<th style="text-align:left;">金額</th>
		</tr>
		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<tr>
			<td><!--{$row.lawyer_number|escape}--></td>
			<td><!--{$row.student_name|escape}--></td>
			<td><!--{$row.association_name|escape}--></td>
			<td><!--{$row.order_date|escape}--></td>
			<td>
				<!--{if $row.product_name_TOD != ''}-->
					<!--{$row.product_name_TOD|escape}-->
				<!--{else}-->
					<!--{$row.product_name_TP|escape}-->
				<!--{/if}-->
			</td>
			<td><!--{$row.disp_passport_target|escape}--></td>
			<td>
				<!--{if $row.payment_status=="0"}-->未入金
				<!--{elseif $row.payment_status=="1"}-->未入金
				<!--{elseif $row.payment_status=="2"}-->入金済
				<!--{/if}-->
			</td>
			<td><!--{$row.pay_total|escape|number_format}-->円</td>
		</tr>
		<!--{/foreach}-->
		<tr>
			<th class="pager" colspan="8">
				<!--{$pager}-->
			</th>
		</tr>
		<!--{/if}-->
	</table>
<!--{/if}-->
