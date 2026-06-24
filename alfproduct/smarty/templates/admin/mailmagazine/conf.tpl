<div class="toolbar clearfix">
	<a class="btn_seach selected" href="index.php?search=new"><span>検索</span></a>
	<a class="btn_add" href="edit.php"><span>新規登録</span></a>
</div>


	<h2>対象会員の内容を確認</h2>
	<table class="form">
		<!--{if $mid!=""}-->
		<tr>
			<th style="width:120px;">ID</th>
			<td style=""><!--{$mid|escape}--></td>
		</tr>
		<!--{/if}-->

		<!--{if $post_all!="1"}-->
		<tr>
			<th style="width:120px;">弁護士番号</th>
			<td style="">
				<!--{$start_lawyer_number|escape}-->
				<!--{if $start_lawyer_number!="" || $end_lawyer_number!=""}-->
					&nbsp;～&nbsp;
				<!--{/if}-->
				<!--{$end_lawyer_number|escape}-->
				<!--{if $start_lawyer_number=="" && $end_lawyer_number==""}-->
					全て
				<!--{/if}-->
			</td>
		</tr>

		<tr>
			<th style="width:120px;">登録年</th>
			<td style="">
				<!--{$start_regist_date|escape}-->
				<!--{if $start_regist_date!="" || $end_regist_date!=""}-->
					&nbsp;～&nbsp;
				<!--{/if}-->
				<!--{$end_regist_date|escape}-->
				<!--{if $start_regist_date=="" && $end_regist_date==""}-->
					全て
				<!--{/if}-->
			</td>
		</tr>

		<tr>
			<th style="width:120px;">所属弁護士会</th>
			<td style="">
				<!--{if $bar_association_id==""}-->全て
				<!--{else}-->
					<!--{foreach from=$arr_bar_association item="row"}-->
						<!--{if $row.id==$bar_association_id}--><!--{$row.name|escape}--><!--{/if}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
		<!--{else}-->
		<tr>
			<th style="width:120px;"></th>
			<td style="">
				全て
			</td>
		</tr>
		<!--{/if}-->


<!--{*
		<tr>
			<th style="width:120px;">会員種別</th>
			<td style="">
				<!--{if $member_type=="1"}-->全て<!--{/if}-->
				<!--{if $member_type=="2"}-->一般会員<!--{/if}-->
				<!--{if $member_type=="3"}-->月額会員<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">性別</th>
			<td style="">
				<!--{if $sex=="1"}-->全て<!--{/if}-->
				<!--{if $sex=="2"}-->男性<!--{/if}-->
				<!--{if $sex=="3"}-->女性<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">都道府県</th>
			<td style="">
				<!--{if $pref==""}-->全て
				<!--{else}-->
					<!--{foreach from=$arr_pref item="row"}-->
						<!--{if $row.id==$pref}--><!--{$row.name|escape}--><!--{/if}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">年代</th>
			<td style="">
				<!--{if $age==""}-->全て
				<!--{else}-->
					<!--{foreach from=$arr_age item="row"}-->
						<!--{if $row.id==$age}--><!--{$row.name|escape}--><!--{/if}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">職業</th>
			<td style="">
				<!--{if $job==""}-->全て
				<!--{else}-->
					<!--{foreach from=$arr_job item="row"}-->
						<!--{if $row.id==$job}--><!--{$row.name|escape}--><!--{/if}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">業種</th>
			<td style="">
				<!--{if $job_type==""}-->全て
				<!--{else}-->
					<!--{foreach from=$arr_job_type item="row"}-->
						<!--{if $row.id==$job_type}--><!--{$row.name|escape}--><!--{/if}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">学年</th>
			<td style="">
				<!--{if $school_grade==""}-->全て
				<!--{else}-->
					<!--{foreach from=$arr_school_grade item="row"}-->
						<!--{if $row.id==$school_grade}--><!--{$row.name|escape}--><!--{/if}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">カテゴリ</th>
			<td style="">
				<!--{if $mailmagazine_category|@count==0}-->全て
				<!--{else}-->
					<!--{foreach from=$mailmagazine_category item="row"}-->
						<!--{foreach from=$arr_mailmagazine_category item="row_mst"}-->
							<!--{if $row==$row_mst.id}--><!--{$row_mst.name|escape}--><!--{/if}-->
						<!--{/foreach}-->
					<!--{/foreach}-->
				<!--{/if}-->
			</td>
		</tr>
*}-->
		<tr>
			<td style="" colspan="2">
				対象となる会員は　<!--{$member_count|escape}-->　人でした。<a href="javascript:void(0);" onclick="javascript:document.csv_send_user.submit();"><img alt="CSVダウンロード" src="/alfproduct/images/abtn_csv.png"></a><br>
				<span style="color:#ff6666;">※配信予定のユーザ数であり、配信後の人数と差異がある場合があります。</span>
			</td>
		</tr>
		<form action="csv_send_user.php" accept-charset="utf-8" method="post" name="csv_send_user" target="_blank">

		<input type="hidden" name="post_all" value="<!--{$post_all|escape}-->">
		<input type="hidden" name="bar_association_id" value="<!--{$bar_association_id|escape}-->">
		<input type="hidden" name="start_regist_date" value="<!--{$start_regist_date|escape}-->">
		<input type="hidden" name="end_regist_date" value="<!--{$end_regist_date|escape}-->">
		<input type="hidden" name="start_lawyer_number" value="<!--{$start_lawyer_number|escape}-->">
		<input type="hidden" name="end_lawyer_number" value="<!--{$end_lawyer_number|escape}-->">

		<input type="hidden" name="member_type" value="<!--{$member_type|escape}-->">
		<input type="hidden" name="sex" value="<!--{$sex|escape}-->">
		<input type="hidden" name="pref" value="<!--{$pref|escape}-->">
		<input type="hidden" name="age" value="<!--{$age|escape}-->">
		<input type="hidden" name="job" value="<!--{$job|escape}-->">
		<input type="hidden" name="job_type" value="<!--{$job_type|escape}-->">
		<input type="hidden" name="school_grade" value="<!--{$school_grade|escape}-->">
		<!--{foreach from=$mailmagazine_category item="row"}-->
			<input type="hidden" name="mailmagazine_category[]" value="<!--{$row|escape}-->">
		<!--{/foreach}-->
		</form>
	</table>

	<h2>配信内容を確認</h2>
	<table class="form">
		<tr>
			<th style="width:120px;">配信日時</th>
			<td style="">
				<!--{$submit_datetime|escape}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">件名</th>
			<td style="">
				<!--{$mail_title|escape}-->
			</td>
		</tr>
		<tr>
			<th style="width:120px;">本文</th>
			<td style="">
				<!--{$mail_body|escape|nl2br}-->
			</td>
		</tr>
	</table>


	<div class="submit">
		<div style="width:48%;float:left;text-align:right;">
			<form action="<!--{$prev_url}-->" accept-charset="utf-8" method="post" name="mailmagazine_form_prev">
			<input type="hidden" name="mid" value="<!--{$mid|escape}-->">
			<input type="hidden" name="member_type" value="<!--{$member_type|escape}-->">
			<input type="hidden" name="sex" value="<!--{$sex|escape}-->">
			<input type="hidden" name="pref" value="<!--{$pref|escape}-->">
			<input type="hidden" name="age" value="<!--{$age|escape}-->">
			<input type="hidden" name="job" value="<!--{$job|escape}-->">
			<input type="hidden" name="job_type" value="<!--{$job_type|escape}-->">
			<input type="hidden" name="school_grade" value="<!--{$school_grade|escape}-->">
			<!--{foreach from=$mailmagazine_category item="row"}-->
				<input type="hidden" name="mailmagazine_category[]" value="<!--{$row|escape}-->">
			<!--{/foreach}-->

			<input type="hidden" name="post_all" value="<!--{$post_all|escape}-->">
			<input type="hidden" name="bar_association_id" value="<!--{$bar_association_id|escape}-->">
			<input type="hidden" name="start_regist_date" value="<!--{$start_regist_date|escape}-->">
			<input type="hidden" name="end_regist_date" value="<!--{$end_regist_date|escape}-->">
			<input type="hidden" name="start_lawyer_number" value="<!--{$start_lawyer_number|escape}-->">
			<input type="hidden" name="end_lawyer_number" value="<!--{$end_lawyer_number|escape}-->">

			<input type="hidden" name="submit_datetime" value="<!--{$submit_datetime|escape}-->">
			<input type="hidden" name="mail_title" value="<!--{$mail_title|escape}-->">
			<input type="hidden" name="mail_body" value="<!--{$mail_body|escape}-->">
			<input type="image" src="/alfproduct/images/btn_back.png">
			</form>
		</div>
		<div style="width:48%;float:right;text-align:left;">
			<form action="<!--{$next_url}-->" accept-charset="utf-8" method="post" name="mailmagazine_form_next">
			<input type="hidden" name="mid" value="<!--{$mid|escape}-->">
			<input type="hidden" name="member_type" value="<!--{$member_type|escape}-->">
			<input type="hidden" name="sex" value="<!--{$sex|escape}-->">
			<input type="hidden" name="pref" value="<!--{$pref|escape}-->">
			<input type="hidden" name="age" value="<!--{$age|escape}-->">
			<input type="hidden" name="job" value="<!--{$job|escape}-->">
			<input type="hidden" name="job_type" value="<!--{$job_type|escape}-->">
			<input type="hidden" name="school_grade" value="<!--{$school_grade|escape}-->">
			<!--{foreach from=$mailmagazine_category item="row"}-->
				<input type="hidden" name="mailmagazine_category[]" value="<!--{$row|escape}-->">
			<!--{/foreach}-->

			<input type="hidden" name="post_all" value="<!--{$post_all|escape}-->">
			<input type="hidden" name="bar_association_id" value="<!--{$bar_association_id|escape}-->">
			<input type="hidden" name="start_regist_date" value="<!--{$start_regist_date|escape}-->">
			<input type="hidden" name="end_regist_date" value="<!--{$end_regist_date|escape}-->">
			<input type="hidden" name="start_lawyer_number" value="<!--{$start_lawyer_number|escape}-->">
			<input type="hidden" name="end_lawyer_number" value="<!--{$end_lawyer_number|escape}-->">

			<input type="hidden" name="submit_datetime" value="<!--{$submit_datetime|escape}-->">
			<input type="hidden" name="mail_title" value="<!--{$mail_title|escape}-->">
			<input type="hidden" name="mail_body" value="<!--{$mail_body|escape}-->">
			<input type="image" src="/alfproduct/images/btn_ok.png">
			</form>
		</div>
	</div>
<br />
