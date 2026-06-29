<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="edit.php"><span>新規登録</span></a>
</div>


<form action="<!--{$next_url|escape}-->" accept-charset="utf-8" method="post" name="mailmagazine_form">
	<h2>対象会員内容を入力してください</h2>
	<!--{foreach from=$arr_err item="err"}-->
	<div class="error"><!--{$err|escape}--></div>
	<!--{/foreach}-->
	<table class="form">
		<!--{if $mid!=""}-->
		<tr>
			<input type="hidden" name="mid" value="<!--{$mid|escape}-->">
			<th style="">ID</th>
			<td style=""><!--{$mid|escape}--></td>
		</tr>
		<!--{/if}-->
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">弁護士番号</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="start_lawyer_number" value="<!--{$start_lawyer_number|escape}-->" id="start_lawyer_number">&nbsp;～&nbsp;
				<input type="text" name="end_lawyer_number" value="<!--{$end_lawyer_number|escape}-->" id="end_lawyer_number">&nbsp;
			</td>
		</tr>
		<tr>
			<th style="">登録年</th>
			<td style="">
				<input type="text" name="start_regist_date" id="start_date" value="<!--{$start_regist_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.mailmagazine_form.start_regist_date.value='';">クリア</a>
				～
				<input type="text" name="end_regist_date" id="end_date" value="<!--{$end_regist_date|escape}-->" readonly="" /> 
				<a class="clear_date" href="javascript:void(0);" onclick="javascript:document.mailmagazine_form.end_regist_date.value='';">クリア</a>
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">所属弁護士会</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<select name="bar_association_id">
					<option value="">----</option>
				<!--{foreach from=$arr_bar_association item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$bar_association_id}--> selected="selected"<!--{/if}-->><!--{$row.name|escape}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">会員全員</th>
			<td style="">
				<input type="checkbox" name="post_all" id="post_all" value="1" <!--{if $post_all=="1"}-->checked=checked<!--{/if}--> onclick="change_flag()" /> 
				<script type="text/javascript">
					change_flag();
					function change_flag() {
						if( document.mailmagazine_form.post_all.checked == true ) {
							document.mailmagazine_form.start_lawyer_number.disabled = "true";
							document.mailmagazine_form.end_lawyer_number.disabled = "true";
							document.mailmagazine_form.start_regist_date.disabled = "true";
							document.mailmagazine_form.end_regist_date.disabled = "true";
							document.mailmagazine_form.bar_association_id.disabled = "true";
						} else {
							document.mailmagazine_form.start_lawyer_number.disabled = "";
							document.mailmagazine_form.end_lawyer_number.disabled = "";
							document.mailmagazine_form.start_regist_date.disabled = "";
							document.mailmagazine_form.end_regist_date.disabled = "";
							document.mailmagazine_form.bar_association_id.disabled = "";
						}
					}
				</script>
			</td>
		</tr>



		<!--
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">会員種別</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="radio" name="member_type" value="1" id="member_type_1"<!--{if $member_type=="1"}--> checked="checked"<!--{/if}-->><label for="member_type_1">全て</label>&nbsp;
				<input type="radio" name="member_type" value="2" id="member_type_2"<!--{if $member_type=="2"}--> checked="checked"<!--{/if}-->><label for="member_type_2">一般会員</label>&nbsp;
				<input type="radio" name="member_type" value="3" id="member_type_3"<!--{if $member_type=="3"}--> checked="checked"<!--{/if}-->><label for="member_type_3">月額会員</label>&nbsp;
			</td>
		</tr>
		<tr>
			<th style="">性別</th>
			<td style="">
				<input type="radio" name="sex" value="1" id="sex_1"<!--{if $sex=="1"}--> checked="checked"<!--{/if}-->><label for="sex_1">全て</label>&nbsp;
				<input type="radio" name="sex" value="2" id="sex_2"<!--{if $sex=="2"}--> checked="checked"<!--{/if}-->><label for="sex_2">男性</label>&nbsp;
				<input type="radio" name="sex" value="3" id="sex_3"<!--{if $sex=="3"}--> checked="checked"<!--{/if}-->><label for="sex_3">女性</label>&nbsp;
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">都道府県</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<select name="pref">
					<option value="">----</option>
				<!--{foreach from=$arr_pref item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$pref}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">年代</th>
			<td style="">
				<select name="age">
					<option value="">----</option>
				<!--{foreach from=$arr_age item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$age}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">職業</th>
			<td style="">
				<select name="job">
					<option value="">----</option>
				<!--{foreach from=$arr_job item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$job}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">業種</th>
			<td style="">
				<select name="job_type">
					<option value="">----</option>
				<!--{foreach from=$arr_job_type item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$job_type}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th style="">学年</th>
			<td style="">
				<select name="school_grade">
					<option value="">----</option>
				<!--{foreach from=$arr_school_grade item="row"}-->
					<option value="<!--{$row.id}-->"<!--{if $row.id==$school_grade}--> selected="selected"<!--{/if}-->><!--{$row.name}--></option>
				<!--{/foreach}-->
				</select>
			</td>
		</tr>
		<tr>
			<th>カテゴリ</th>
			<td colspan = "3">
				<!--{foreach from=$arr_mailmagazine_category item="row"}-->
				<input type="checkbox" name="mailmagazine_category[]" value="<!--{$row.id}-->" id="mailmagazine_category_<!--{$row.id}-->"<!--{if in_array($row.id,$mailmagazine_category)}--> checked="checked"<!--{/if}-->><label for="mailmagazine_category_<!--{$row.id}-->"><!--{$row.name}--></label>&nbsp;
				<!--{/foreach}-->
			</td>
		</tr>
		-->



	</table>

	<h2>配信内容を入力してください</h2>
	<table class="form">
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">配信日時</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
				<input type="text" name="submit_datetime" id="submit_datetime" value="<!--{$submit_datetime|escape}-->" readonly="" /> 
			</td>
		</tr>
		<tr>
			<th style="">件名</th>
			<td style="">
				<input type="text" name="mail_title" value="<!--{$mail_title|escape}-->" style="width:600px;">
			</td>
		</tr>
		<tr>
			<th style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">本文</th>
			<td style="background: none repeat scroll 0% 0% rgb(246, 246, 243);">
			生徒名を挿入する場合、「@name@」を入力してください。<br>
			<textarea name="mail_body" style="width:600px;height:300px;"><!--{$mail_body|escape}--></textarea>
			</td>
		</tr>
	</table>

	<div class="submit">
		<a href="javascript:void(0);" onclick="window.location='index.php';" /><img src="/alfproduct/images/btn_back.png"></a>
		<a href="javascript:void(0);" onclick="document.mailmagazine_form.submit();" /><img src="/alfproduct/images/btn_confirm.png"></a>
	</div>
</form>
<br />
