<h2></h2>

<form accept-charset="utf-8" method="post" name="search_form">
	<input type="hidden" name="sid" value="<!--{$sid}-->">
	<input type="hidden" name="search_orderby" value="">
	<table class="form">
		<tr>
			<th style="width:100px;">登録番号</th>
			<td style="">
				<!--{$arr_student.lawyer_number|escape}-->
			</td>
		</tr>
		<tr>
			<th style="">氏名</th>
			<td style="">
				<!--{$arr_student.student_name|escape}-->
			</td>
		</tr>
		<tr>
			<th style="">登録年月日</th>
			<td style="">
				<!--{$arr_student.regist_date}-->
			</td>
		</tr>
		<tr>
			<th style="">所属弁護士会</th>
			<td style="">
				<!--{$arr_student.association_name|escape}-->
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<a href="csv.php?sid=<!--{$sid}-->" target="_blank" rel="noopener noreferrer"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
	</form>
	<tr>
		<th style="width:;">注文ID</th>
		<th>商品名</th>
		<th style="width:;">商品コード</th>
		<th style="width:;text-align:left;">
			入金
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<!--{if $search_orderby=="1"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<!--{if $search_orderby=="2"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
		</th>
		<th style="width:;">購入日</th>
		<th style="width:;">購入価格</th>
		<th style="width:;">申込</th>
		<th style="width:;">決済方法</th>
	</tr>
	<!--{if is_array($arr_list) && count($arr_list) > 0}-->
	<!--{foreach from=$arr_list item="row"}-->
	<tr style="">

		<td class="tdc"><!--{$row.order_no|escape}--></td>
		<td class="tdc"><!--{if $row.product_name_TOD!=""}--><!--{$row.product_name_TOD|escape}--><!--{elseif $row.product_name_TP!=""}--><!--{$row.product_name_TP|escape}--><!--{/if}--></td>
		<td class="tdc"><!--{if $row.product_code_TOD!=""}--><!--{$row.product_code_TOD|escape}--><!--{elseif $row.product_code_TP!=""}--><!--{$row.product_code_TP|escape}--><!--{/if}--></td>
		<td class="tdc">
			<!--{if $row.payment_status=="0"}-->未入金
			<!--{elseif $row.payment_status=="1"}-->未入金
			<!--{elseif $row.payment_status=="2"}-->入金済
			<!--{elseif $row.payment_status=="3"}-->一部未入金
			<!--{elseif $row.payment_status=="9"}-->キャンセル
			<!--{/if}-->
		</td>
		<td class="tdc"><!--{$row.create_date|escape}--></td>
		<td class="tdc"><!--{$row.pay_total|escape|number_format}-->円</td>
		<td class="tdc">
			<!--{if $row.web_flg=="1"}-->WEB
			<!--{else}-->WEB以外
			<!--{/if}-->
		</td>
		<td class="tdc">
			<!--{if $row.payment_type=="1"}-->カード
			<!--{elseif $row.payment_type=="12"}-->銀行振込
			<!--{else}-->
			<!--{/if}-->
		</td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th colspan="7">合計</th>
		<th><!--{$all_total|escape|number_format}-->円</th>
	</tr>
	<!--{/if}-->
</table>
