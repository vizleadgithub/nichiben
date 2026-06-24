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
		<tr>
			<th>所属弁護士会</th>
			<td>
				<select name="search_association">
					<!--{if $nichibenren_flg}--><option value="">-</option><!--{/if}-->
				<!--{foreach from=$arr_association item="row"}-->
					<option value="<!--{$row.id}-->" <!--{if $row.id==$search_association}--> selected="selected"<!--{/if}-->><!--{$row.name|escape}--></option>
				<!--{/foreach}-->
				</select>
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
		<form accept-charset="utf-8" method="get" name="list_form">
			
		</form>
		<tr>
			<th style="width:100px;text-align:left;">
				登録番号
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<!--{if $search_orderby=="1"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
				<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<!--{if $search_orderby=="2"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
			</th>
			<th style="width:160px;text-align:left;">氏名</th>
			<th style="width:180px;text-align:left;">メールアドレス</th>
			<th style="width:100px;text-align:left;">FP</th>
			<th style="width:100px;text-align:left;">代替権限</th>
			<th style="width:;text-align:left;">所属弁護士会</th>
		</tr>
		<!--{if is_array($arr_list) && count($arr_list) > 0}-->
		<!--{foreach from=$arr_list item="row"}-->
		<tr style="">
			<td class="tdc"><a href="info.php?sid=<!--{$row.student_id|escape}-->"><!--{$row.lawyer_number|escape}--></a></td>
			<td class="tdc"><!--{$row.student_name|escape}--></td>
			<td class="tdc"><!--{$row.student_email|escape}--></td>
			<td class="tdc"><!--{if $row.presence_passport=="1"}-->○<!--{else}-->-<!--{/if}--></td>
			<td class="tdc"><!--{if $row.sub_auth_ethic_training=="1"}-->○<!--{else}-->-<!--{/if}--></td>
			<td class="tdc"><!--{$row.association_name|escape}--></td>
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
