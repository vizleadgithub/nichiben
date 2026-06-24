<!--
<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="add.php"><span>新規登録</span></a>
</div>
-->

<!--<h2>検索する内容を入力してください</h2>-->

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th colspan="2">
			研修内容 <a href="csv.php?type=info&pid=<!--{$pid}-->">CSV取得</a>
			</th>
		</tr>
		<tr>
			<th>研修名</th>
			<td style="width:70%">
				<!--{$arr_input.product_name}-->
			</td>
		</tr>
	</table>
	<div class="submit">
	</div>
</form>
<br />

<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>

	<tr>
		<th>ID</th>
		<th>申込期限</th>
		<th>WEB受付</th>
		<th>弁護士会名/支部名</th>
		<th>定員</th>
		<th>申込数</th>
		<th>受講数</th>
		<th>受講率</th>
		<th>申込者管理</th>
	</tr>

	<!--{if is_array($arr_list) && count($arr_list) > 0}-->
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.bar_association_branch_id}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{if strlen($row.limit_date) > 0}--><!--{$row.limit_date}--><!--{else}-->-<!--{/if}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{if $row.web_flg==1}-->○<!--{else}-->-<!--{/if}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.bar_association_name|escape}--><br /><!--{$row.bar_association_branch_name|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.capacity|number_format}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.entry_number|number_format}-->(<!--{$row.entry_number_passport|number_format}-->)</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.attend_number|number_format}-->(<!--{$row.attend_number_passport|number_format}-->)</td></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.number_percent|number_format}-->%</td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{if $row.kanri_flg}--><a href="info_user.php?pid=<!--{$pid}-->&aid=<!--{$row.bar_association_branch_id}-->">管理</a><!--{/if}--></td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<td colspan="5">&nbsp;</td>
		<td><!--{$all_entry_number|number_format}-->(<!--{$all_entry_number_passport|number_format}-->)</td>
		<td><!--{$all_attend_number|number_format}-->(<!--{$all_attend_number_passport|number_format}-->)</td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<!--{/if}-->
</table>

<div class="submit">
	<a href="javascript:void(0);" onclick="window.location='index.php?page=1';" /><img src="/alfproduct/images/btn_back.png"></a>
</div>
<a name="page_bottom"></a>
