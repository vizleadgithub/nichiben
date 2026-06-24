<h2>ファイルをアップロードして銀行振込csvを取り込みます</h2>

<form name="form1" action="index.php" method="post" enctype="multipart/form-data">
<table class="form">
	<tr>
		<th>
			<input type="file" size="50" name="csv_upload">
			<input type="submit" size="50" name="btn_submit" value="アップロード">
		</th>
	</tr>
	<tr>
		<td>
			<!--{if $err_msg!=""}-->
			<!--{$err_msg}-->
			<!--{/if}-->
			<!--{if $ok_msg!=""}-->
			<!--{$ok_msg}-->
			<!--{/if}-->
		</td>
	</tr>
</table>


<br />

<!--{if $disp_flg}-->
	<!--{if is_array($csv) && count($csv) > 0}-->
		<table class="list">
			<form accept-charset="utf-8" method="get" name="list_form">
				
			</form>
			<tr>
				<th style="width:;">注文No</th>
				<th style="width:;">商品名（ID）</th>
				<th style="width:;">金額</th>
				<th style="width:;">会員名</th>
				<th style="width:;">入金日</th>
				<th style="width:;">取り込み日</th>
			</tr>

			<!--{foreach from=$csv item="row"}-->
			<tr>
				<td class="tdc" style=""><!--{$row.order_no|escape}--></td>
				<td class="tdc" style=""><!--{$row.product_name|escape}-->(<!--{$row.product_id|escape}-->)</td>
				<td class="tdc" style=""><!--{$row.pay_total|escape|number_format}--></td>
				<td class="tdc" style=""><!--{$row.student_name|escape}--></td>
				<td class="tdc" style=""><!--{$row.receipt_date|escape}--></td>
				<td class="tdc" style=""><!--{$row.take_date|escape}--></td>
			</tr>
			<!--{/foreach}-->
		</table>
		<!--{if $ok_msg!=""}-->
		<!--{$ok_msg}-->
		<!--{/if}-->

	<!--{/if}-->
<!--{/if}-->

<a name="page_bottom"></a>
</form>
