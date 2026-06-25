<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="edit.php"><span>新規登録</span></a>
</div>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<table class="form">
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">配信日時</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_start_date" id="start_date" value="<!--{$search_start_date}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_date" id="end_date" value="<!--{$search_end_date}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th>フリーワード</th>
			<td colspan = "3">
				<input type="text" name="search_keyword" size="45" value="<!--{$search_keyword|escape}-->">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">登録年</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="search_start_regist_date" id="search_start_regist_date" value="<!--{$search_start_regist_date}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_start_regist_date.value='';">クリア</a>
				～
				<input type="text" name="search_end_regist_date" id="search_end_regist_date" value="<!--{$search_end_regist_date}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.search_end_regist_date.value='';">クリア</a>
			</td>
		</tr>
<!--{*
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">会員種別</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="radio" name="search_member_type" value="1" id="search_member_type_1"<!--{if $search_member_type=="1"}--> checked="checked"<!--{/if}-->><label for="search_member_type_1">全て</label>&nbsp;
				<input type="radio" name="search_member_type" value="2" id="search_member_type_2"<!--{if $search_member_type=="2"}--> checked="checked"<!--{/if}-->><label for="search_member_type_2">一般会員</label>&nbsp;
				<input type="radio" name="search_member_type" value="3" id="search_member_type_3"<!--{if $search_member_type=="3"}--> checked="checked"<!--{/if}-->><label for="search_member_type_3">月額会員</label>&nbsp;
			</td>
		</tr>
		<tr>
			<th style="">性別</th>
			<td style="">
				<input type="radio" name="search_sex" value="1" id="search_sex_1"<!--{if $search_sex=="1"}--> checked="checked"<!--{/if}-->><label for="search_sex_1">全て</label>&nbsp;
				<input type="radio" name="search_sex" value="2" id="search_sex_2"<!--{if $search_sex=="2"}--> checked="checked"<!--{/if}-->><label for="search_sex_2">男性</label>&nbsp;
				<input type="radio" name="search_sex" value="3" id="search_sex_3"<!--{if $search_sex=="3"}--> checked="checked"<!--{/if}-->><label for="search_sex_3">女性</label>&nbsp;
			</td>
		</tr>
		<tr>
			<th style="">都道府県</th>
			<td style="">
				<select name="search_pref">
					<option value="">----</option>
				<!--{foreach from=$arr_pref item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$search_pref}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">年代</th>
			<td style="">
				<select name="search_age">
					<option value="">----</option>
				<!--{foreach from=$arr_age item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$search_age}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">職業</th>
			<td style="">
				<select name="search_job">
					<option value="">----</option>
				<!--{foreach from=$arr_job item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$search_job}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">業種</th>
			<td style="">
				<select name="search_job_type">
					<option value="">----</option>
				<!--{foreach from=$arr_job_type item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$search_job_type}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">学年</th>
			<td style="">
				<select name="search_school_grade">
					<option value="">----</option>
				<!--{foreach from=$arr_school_grade item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$search_school_grade}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th>カテゴリ</th>
			<td colspan = "3">
				<!--{foreach from=$arr_mailmagazine_category item="row"}-->
				<input type="checkbox" name="search_mailmagazine_category[]" value="<!--{$row.id}-->" id="search_mailmagazine_category_<!--{$row.id}-->"<!--{if in_array($row.id,$search_mailmagazine_category)}--> checked="checked"<!--{/if}-->><label for="search_mailmagazine_category_<!--{$row.id}-->"><!--{$row.name}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
*}-->
	</table>
	<div class="submit">
		<input type='image' src='/alfproduct/images/btn_search.png' />
	</div>
</form>
<br />

<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:76px;">ID</th>
		<th>タイトル</th>
		<th style="width:110px;">状態</th>
		<th style="width:133px;">配信日</th>
	</tr>
	<!--{if is_array($arr_list) && count($arr_list) > 0}-->
	<!--{foreach from=$arr_list item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><a href="info.php?mid=<!--{$row.mailmagazine_id}-->"><!--{$row.mailmagazine_id}--></a></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.mail_title|escape}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{if $row.submit_status=="2"}-->配信済<!--{elseif $row.submit_status=="1"}-->配信中<!--{else}-->未配信<!--{/if}--></td>
		<td class="tdc" style="<!--{if $cycle_bg=="1"}-->background: none repeat scroll 0% 0% rgb(246, 246, 243);<!--{/if}-->"><!--{$row.submit_datetime}--></td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="4">
<!--{$pager}-->
		</th>
	</tr>
	<!--{/if}-->
</table>
