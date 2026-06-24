<script type="text/javascript">
function approvalExe(productId){
	if (confirm('承認してもよろしいでしょうか？')){
		document.form1.action = "approval_exe.php?mid=" + productId;
		document.form1.submit();
	}
}
</script>

<h2>下記より選んで承認してください</h2>

<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
<form name="form1" action="#" method="post">
<table class="list">
	<tr>
		<th style="width:76px;">ID</th>
		<th>研修名</th>
		<th style="width:300px;" colspan="2">受付期間</th>
	</tr>
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="info.php?mid=<!--{$row.product_id}-->"><!--{$row.product_id}--></a></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.product_name|mb_truncate:60:"..."|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.start_date}--><!--{if $row.start_date!="" && $row.end_date!=""}-->～<!--{/if}--><!--{$row.end_date}--></td>
		<th style="background-color:#ffffff;text-align:center;"><input type="button" value="承認" onclick="approvalExe(<!--{$row.product_id}-->)" /></th>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="4">
<!--{$pager}-->
		</th>
	</tr>
</table>
</form>
