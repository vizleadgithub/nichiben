<h2></h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>注文ID</th>
			<td>
				<!--{$arr_order[0].order_no}-->
			</td>
		</tr>
		<!--{if $arr_order[0].payment_type=="1"}-->
		<tr>
			<th>決済ID</th>
			<td>
				<!--{$arr_order[0].order_id}-->
			</td>
		</tr>
		<!--{/if}-->
		<tr>
			<th style="">登録番号</th>
			<td style="">
				<!--{$arr_order[0].lawyer_number|escape}-->
			</td>
		</tr>
		<tr>
			<th style="">氏名</th>
			<td style="">
				<!--{$arr_order[0].student_name|escape}-->
			</td>
		</tr>
		<tr>
			<th style="">登録年月日</th>
			<td style="">
				<!--{$arr_order[0].regist_date}-->
			</td>
		</tr>
		<tr>
			<th style="">所属弁護士会</th>
			<td style="">
				<!--{$arr_order[0].association_name|escape}-->
			</td>
		</tr>
		<tr>
			<th style="">購入日</th>
			<td style="">
				<!--{$arr_order[0].create_date}-->
			</td>
		</tr>
		<tr>
			<th style="">請求書</th>
			<td style="">
				<!--{if $arr_order[0].claim_flg=="1"}-->
					希望する
				<!--{else}-->
					希望しない
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="">決済方法</th>
			<td style="">
				<!--{if $arr_order[0].payment_type=="1"}-->
					カード
				<!--{elseif $arr_order[0].payment_type=="12"}-->
					銀行振込
				<!--{else}-->
				<!--{/if}-->
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<script type="text/javascript">
	function non_download() {
		var atena = document.getElementById("atena").value;
		if (confirm("宛名は、以下のように発行されます。\n\n" + atena + "\n<!--{$arr_order[0].student_name|escape}--> 様\n\n発行しても宜しいですか？")){
			document.getElementById('download_btn').disabled = true;
			window.document.downloadForm.submit();
		}
	}
</script>
<div id="download_div">
	<form name="downloadForm" action="pdf.php?oid=<!--{$oid}-->" method="post">
	宛名：<input type="text" id="atena" name="atena" size="28" />
	<a id="download_btn" href="javascript: void(0);" onclick="non_download();">領収書発行</a>
	</form>
</div>
<table class="list2">
	<form accept-charset="utf-8" method="post" name="list_form" action="#">
		<input type="hidden" name="mode" value="">
		<input type="hidden" name="order_detail_id" value="">
	</form>
	<tr>
		<th style="width:80px;text-align:left;">商品コードID</th>
		<th style="width:160px;text-align:left;">商品名</th>
		<th style="width:80px;text-align:left;">金額</th>
		<th style="width:120px;text-align:left;">購入日</th>
		<th style="width:100px;text-align:left;">ステータス</th>
		<th style="width:;text-align:left;"></th>
	</tr>
	<!--{if is_array($arr_order_detail) && count($arr_order_detail) > 0}-->
	<!--{foreach from=$arr_order_detail item="row"}-->
	<tr style="<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
		<td <!--{if $row.payment_status!="9"}-->class="tdc"<!--{/if}--> colspan="6" <!--{if $row.payment_status=="9"}-->style="background-color:#363636;color:#FEFEFE;"<!--{/if}-->>
			<div>
				<div style="width:100%;float:left;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
					<div style="float:left;width:80px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
						<!--{if $row.product_type_add=="1"}-->
							<a href="../product/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></a>
						<!--{elseif $row.product_type_add=="2"}-->
							<a href="../product_live/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></a>
						<!--{elseif $row.product_type_add=="3"}-->
							<a href="../product_ethics/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></a>
						<!--{elseif $row.product_type_add=="4"}-->
							<a href="../product_passport/info.php?mid=<!--{$row.product_id|escape}-->"><!--{$row.product_id|escape}--></a>
						<!--{else}-->
							<!--{$row.product_id|escape}-->
						<!--{/if}-->
					</div>
					<div style="float:left;width:160px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->"><!--{if $row.product_name_TOD!=""}--><!--{$row.product_name_TOD|escape}--><!--{elseif $row.product_name_TP!=""}--><!--{$row.product_name_TP|escape}--><!--{/if}--></div>
					<div style="float:left;width:80px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->"><!--{$row.pay_total|escape}-->円</div>
					<div style="float:left;width:120px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->"><!--{$row.buy_date|escape}--></div>
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
						<!--{if $row.payment_status=="0"}-->未入金
						<!--{elseif $row.payment_status=="1"}-->未入金
						<!--{elseif $row.payment_status=="2"}-->入金済
						<!--{elseif $row.payment_status=="3"}-->一部未入金
						<!--{elseif $row.payment_status=="9"}-->キャンセル
						<!--{/if}-->
					</div>
					<div style="float:left;width:;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
						<!--{if $row.payment_status=="0"}--><!--未入金-->
							<input type="button" value="入金済に変更" onclick="document.list_form.mode.value='pay';document.list_form.order_detail_id.value='<!--{$row.order_detail_id|escape}-->';document.list_form.submit();">
							<input type="button" value="キャンセル" onclick="document.list_form.mode.value='cancel';document.list_form.order_detail_id.value='<!--{$row.order_detail_id|escape}-->';document.list_form.submit();">
						<!--{elseif $row.payment_status=="1"}--><!--入金待ち-->
							<input type="button" value="入金済に変更" onclick="document.list_form.mode.value='pay';document.list_form.order_detail_id.value='<!--{$row.order_detail_id|escape}-->';document.list_form.submit();">
							<input type="button" value="キャンセル" onclick="document.list_form.mode.value='cancel';document.list_form.order_detail_id.value='<!--{$row.order_detail_id|escape}-->';document.list_form.submit();">
						<!--{elseif $row.payment_status=="2"}--><!--入金済-->
							<input type="button" value="未入金に変更" onclick="document.list_form.mode.value='nopay';document.list_form.order_detail_id.value='<!--{$row.order_detail_id|escape}-->';document.list_form.submit();">
						<!--{elseif $row.payment_status=="9"}--><!--キャンセル-->
							<input type="button" value="キャンセルを取り消す" onclick="document.list_form.mode.value='nocancel';document.list_form.order_detail_id.value='<!--{$row.order_detail_id|escape}-->';document.list_form.submit();">
						<!--{/if}-->
					</div>
				</div>
				<div style="width:100%;float:left;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">入金日：</div>
					<div style="float:left;width:140px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
						<!--{if $row.payment_status=="0"}--><!--未入金-->
						<!--{elseif $row.payment_status=="1"}--><!--入金待ち-->
						<!--{elseif $row.payment_status=="2"}--><!--入金済-->
						<!--{$row.receipt_date|escape}-->
						<!--{elseif $row.payment_status=="3"}--><!--一部未入金-->
						<!--{elseif $row.payment_status=="9"}--><!--キャンセル-->
						<!--{$row.receipt_date|escape}-->（キャンセル）
						<!--{/if}-->
					</div>
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">権限付与日：</div>
					<div style="float:left;width:140px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->">
						<!--{if $row.payment_status=="0"}--><!--未入金-->
						<!--{elseif $row.payment_status=="1"}--><!--入金待ち-->
						<!--{elseif $row.payment_status=="2"}--><!--入金済-->
						<!--{$row.take_date|escape}-->
						<!--{elseif $row.payment_status=="3"}--><!--一部未入金-->
						<!--{elseif $row.payment_status=="9"}--><!--キャンセル-->
						<!--{/if}-->
					</div>
					<div style="float:left;width:100px;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->"></div>
					<div style="float:left;width:;text-align:left;padding: 1px 3px;<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{/if}-->"></div>
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
