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
			<th>ふりがな</th>
			<td>
				<input type="text" name="search_kana" value="<!--{$search_kana|escape}-->" id="search_kana">
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
		<tr>
			<th>登録年月日</th>
			<td>
				<input type="text" name="search_start_regist_date" id="search_start_regist_date" value="<!--{$search_start_regist_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_start_regist_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_regist_date" id="search_end_regist_date" value="<!--{$search_end_regist_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_end_regist_date.value='';">クリア</a>
			</td>
		</tr>
		<!--{if $bar_association_id=="1"}-->
		<tr>
			<th>所属弁護士会</th>
			<td>
				<select name="search_association">
					<option value="">-</option>
				<!--{foreach from=$arr_association item="row"}-->
					<option value="<!--{$row.id}-->" <!--{if $row.id==$search_association}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
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
			<th>入金ステータス</th>
			<td>
				<!--{foreach from=$arr_payment_status item="row"}-->
					<input type="checkbox" name="search_payment_status[]" value="<!--{$row.id}-->" id="search_payment_status_<!--{$row.id}-->"<!--{if in_array($row.id,$search_payment_status)}--> checked="checked"<!--{/if}-->><label for="search_payment_status_<!--{$row.id}-->"><!--{$row.name}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td>
				<input type="text" name="search_product_code" value="<!--{$search_product_code|escape}-->" id="search_product_code">
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
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />


<!--{if $disp_flg}-->
	<a href="csv.php?data=<!--{$post_data|escape|urlencode}-->" target="_blank"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
	<!--{$list_start}-->～<!--{$list_end}-->件を表示中（全<!--{$list_max}-->件）
	<table class="list">
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:100px;text-align:left;">注文ID</th>
			<th style="width:160px;text-align:left;">氏名</th>
			<th style="width:100px;text-align:left;">
				登録番号
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<!--{if $search_orderby=="1"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<!--{if $search_orderby=="2"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
			</th>
			<th style="width:120px;text-align:left;">弁護士会</th>
			<th style="width:160px;text-align:left;">商品名</th>
			<th style="width:;text-align:left;">商品コード</th>
		</tr>
		<tr>
			<th style="width:100px;text-align:left;">
				入金
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='3';document.search_form.submit();"<!--{if $search_orderby=="3"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='4';document.search_form.submit();"<!--{if $search_orderby=="4"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
			</th>
			<th style="width:160px;text-align:left;">購入日</th>
			<th style="width:100px;text-align:left;">決済方法</th>
			<th style="width:120px;text-align:left;">申込</th>
			<th style="width:160px;text-align:left;">決済ID</th>
			<th style="width:;text-align:left;">請求書</th>
		</tr>
		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<tr style="">
			<td class="tdc" colspan="6">
				<div>
					<div style="width:100%;float:left;">
						<div style="float:left;width:100px;text-align:left;padding: 1px 3px;"><a href="info.php?oid=<!--{$row.order_id|escape}-->"><!--{$row.order_no|escape}--></a></div>
						<div style="float:left;width:160px;text-align:left;padding: 1px 3px;"><!--{$row.student_name|escape}--></div>
						<div style="float:left;width:100px;text-align:left;padding: 1px 3px;"><!--{$row.lawyer_number|escape}--></div>
						<div style="float:left;width:120px;text-align:left;padding: 1px 3px;"><!--{$row.association_name|escape}--></div>
						<div style="float:left;width:160px;text-align:left;padding: 1px 3px;"><!--{if $row.product_name_TOD!=""}--><!--{$row.product_name_TOD|escape}--><!--{elseif $row.product_name_TP!=""}--><!--{$row.product_name_TP|escape}--><!--{/if}--></div>
						<div style="float:left;width:;text-align:left;padding: 1px 3px;"><!--{if $row.product_code_TOD!=""}--><!--{$row.product_code_TOD|escape}--><!--{elseif $row.product_code_TP!=""}--><!--{$row.product_code_TP|escape}--><!--{/if}--></div>
					</div>
					<div style="width:100%;float:left;">
						<div style="float:left;width:100px;text-align:left;padding: 1px 3px;">
						<!--{if $row.payment_status=="0"}-->未入金
						<!--{elseif $row.payment_status=="1"}-->未入金
						<!--{elseif $row.payment_status=="2"}-->入金済
						<!--{elseif $row.payment_status=="3"}-->一部入金
						<!--{elseif $row.payment_status=="9"}-->キャンセル
						<!--{/if}-->
						</div>
						<div style="float:left;width:160px;text-align:left;padding: 1px 3px;"><!--{$row.create_date|escape}--></div>
						<div style="float:left;width:100px;text-align:left;padding: 1px 3px;">
						<!--{if $row.payment_type=="1"}-->カード
						<!--{elseif $row.payment_type=="12"}-->銀行振込
						<!--{/if}-->
						</div>
						<div style="float:left;width:120px;text-align:left;padding: 1px 3px;">
						<!--{if $row.web_flg=="1"}-->WEB
						<!--{else}-->WEB以外
						<!--{/if}-->
						</div>
						<div style="float:left;width:160px;text-align:left;padding: 1px 3px;"><!--{if $row.payment_type=='1'}--><!--{$row.order_id|escape}--><!--{else}-->-<!--{/if}--></div>
						<div style="float:left;width:;text-align:left;padding: 1px 3px;"><!--{if $row.claim_flg=='1'}-->○<!--{else}-->×<!--{/if}--></div>
					</div>
				</div>
			</td>
		</tr>
		<!--{/foreach}-->
		<tr>
			<th class="pager" colspan="6">
				<!--{$pager}-->
			</th>
		</tr>
		<!--{/if}-->
	</table>
<!--{/if}-->
