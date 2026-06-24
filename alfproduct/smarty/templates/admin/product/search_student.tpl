<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th>名前</th>
			<td colspan = "3">
				<input type="text" name="search_student_name" size="45" value="<!--{$search_student_name|escape}-->">
			</td>
		</tr>
		<tr>
			<th>登録番号</th>
			<td colspan = "3">
				<input type="text" name="search_lawyer_number" size="45" value="<!--{$search_lawyer_number|escape}-->">
			</td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td colspan = "3">
				<input type="text" name="search_student_email" size="45" value="<!--{$search_student_email|escape}-->">
			</td>
		</tr>
		<tr>
			<th>所属弁護士会</th>
			<td colspan = "3">
				<select name="search_bar_association_id">
					<option value="">--</option>
					<!--{foreach from=$bar_association_list item="row"}-->
						<option value="<!--{$row.id}-->" <!--{if $row.id==$search_bar_association_id}--> selected<!--{/if}-->><!--{$row.name}--></option>
					<!--{/foreach}-->
				</select>
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
		<th>受講者</th>
		<th style="width:130px;">操作</th>
	</tr>
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.student_id}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.student_name}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="search_set.php?gid=<!--{$gid|urlencode}-->&id=<!--{$row.student_id|urlencode}-->&name=<!--{$row.student_name|urlencode}-->&comment=">選択</a></td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="4">
<!--{$pager}-->
		</th>
	</tr>
</table>
<!--{/if}-->
