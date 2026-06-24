<script type="text/javascript">
function progressVideo(pid,sid){
	window.open("/alfproduct/report_status/video.php?pid="+pid+"&sid="+sid,"reportVideo","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}
function progressExam(pid,sid){
	window.open("/alfproduct/report_status/exam.php?pid="+pid+"&sid="+sid,"reportExam","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}
</script>

<h2>検索する内容を入力してください</h2>

<form action="#" accept-charset="utf-8" method="post" name="search_form">
	<input type="hidden" name="pid" value="<!--{$pid|escape}-->" id="pid">
	<input type="hidden" name="search_orderby" value="<!--{$search_orderby|escape}-->" id="search_orderby">
	<table class="form">
		<tr>
			<th>商品名</th>
			<td >
				<!--{$arr_product.product_name|escape}-->
			</td>
		</tr>
		<tr>
			<th>商品コード</th>
			<td >
				<!--{$arr_product.product_code|escape}-->
			</td>
		</tr>
		<tr>
			<th>商品種別</th>
			<td>
				<!--{if $arr_product.product_type_add=="1"}-->
					<!--{if $arr_product.product_kind_flg=="1"}-->e-ラーニング
					<!--{elseif $arr_product.product_kind_flg=="2"}-->e-ライブ
					<!--{else}-->e-ラーニング
					<!--{/if}-->
				<!--{elseif $arr_product.product_type_add=="2"}-->
					<!--{if $arr_product.training_kind_flg!=""}--><!--{$mtb_live_training_type[$arr_product.training_kind_flg]|escape}-->
					<!--{else}-->ライブ実務研修
					<!--{/if}-->
				<!--{elseif $arr_product.product_type_add=="3"}-->代替倫理研修
				<!--{elseif $arr_product.product_type_add=="4"}-->パスポート
				<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th>弁護士会</th>
			<td>
				<!--{$arr_product.bar_association_branch_name|escape}-->
			</td>
		</tr>
		<tr>
			<th>実施日</th>
			<td>
				<!--{$arr_product.dates|escape}-->
			</td>
		</tr>
		<tr>
			<th>総受験者数</th>
			<td>
			<!--{if $arr_product.product_type_add=="1"}-->
				<!--[product_type_add1_count:product_type_add1_end_count]-->
				<!--{$arr_product.product_type_add1_count|escape}-->
				(<!--{$arr_product.product_type_add1_end_count|escape}-->)名
			<!--{elseif $arr_product.product_type_add=="2"}-->
				<!--[product_type_add2_count:product_type_add2_end_count]-->
				<!--{$arr_product.product_type_add2_count|escape}-->
				(<!--{$arr_product.product_type_add2_end_count|escape}-->)名
			<!--{elseif $arr_product.product_type_add=="3"}-->
				<!--[product_type_add3_count:product_type_add3_end_count]-->
				<!--{$arr_product.product_type_add3_count|escape}-->
				(<!--{$arr_product.product_type_add3_end_count|escape}-->)名
			<!--{elseif $arr_product.product_type_add=="4"}-->
			<!--{/if}-->
			</td>
		</tr>
		<tr>
			<th>公開期間</th>
			<td>
				<!--{$arr_product.start_date|escape}-->
				<!--{if $arr_product.start_date=="" && $arr_product.end_date==""}--><!--{else}-->～<!--{/if}-->
				<!--{$arr_product.end_date|escape}-->
			</td>
		</tr>
	</table>
</form>
<br />

<a href="csv.php?pid=<!--{$pid|escape}-->&data=<!--{$post_data|escape|urlencode}-->&aid=<!--{$aid|escape}-->" target="_blank"><img src="/alfproduct/images/abtn_csv.png" alt="CSVダウンロード"></a>
<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
<table class="list">
	<form accept-charset="utf-8" method="get" name="list_form">
		
	</form>
	<tr>
		<th style="width:;">
			登録番号
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='1';document.search_form.submit();"<!--{if $search_orderby=="1"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▲</a>
			<a href="javascript:void(0);" onclick="javascript:document.search_form.search_orderby.value='2';document.search_form.submit();"<!--{if $search_orderby=="2"}--> style="color:#FFFFFF;"<!--{else}--> style="color:#00A4E2;"<!--{/if}-->>▼</a>
		</th>
		<th>氏名/ﾒｰﾙｱﾄﾞﾚｽ</th>
		<th style="width:;">弁護士会</th>
		<th style="width:;">FP</th>
		<th style="width:;">開始日/最終受講日/受講完了日</th>
		<th style="width:;">研修動画/設問</th>
		<th style="width:;">受講状況/合否</th>
	</tr>
	<!--{foreach from=$arr_order item="row"}-->
	<!--{cycle values="0,1" assign="cycle_bg"}-->
	<tr style="">
		<td class="tdc" style=""><!--{$row.lawyer_number|escape}--><!--[<!--{$row.student_id|escape}-->]--></td>
		<td class="tdc" style=""><!--{$row.student_name|escape}--><br><!--{$row.student_email|escape}--></td>
		<td class="tdc" style=""><!--{$row.association_name|escape}--></td>
		<td class="tdc" style=""><!--{if $row.presence_passport=="1"}-->○<!--{else}-->-<!--{/if}--></td>
		<td class="tdc" style="">
			<!--{if $arr_product.product_type_add=="1"}-->
				<!--{$row.start_view_date|escape}--><br>
				<!--{$row.end_view_date|escape}--><br>
				<!--{if $row.complete_date!="" && $row.complete_date!="0000-00-00 00:00:00"}-->
					<!--{$row.complete_date|escape}-->
				<!--{else}-->
					-
				<!--{/if}-->
			<!--{elseif $arr_product.product_type_add=="2"}-->
				<!--{$row.dates|escape}--><br>
				-<br>
				-
			<!--{elseif $arr_product.product_type_add=="3"}-->
				<!--{if $row.start_date1!="" && $row.start_date1!="0000-00-00 00:00:00"}-->
					<!--{$row.start_date1|escape}-->
				<!--{else}-->
					-
				<!--{/if}--><br>
				<!--{if $row.judge_date2!="" && $row.judge_date2!="0000-00-00 00:00:00"}-->
					<!--{$row.judge_date2|escape}-->
				<!--{elseif $row.judge_date1!="" && $row.judge_date1!="0000-00-00 00:00:00"}-->
					<!--{$row.judge_date1|escape}-->
				<!--{else}-->
					-
				<!--{/if}--><br>
				-
			<!--{elseif $arr_product.product_type_add=="4"}-->
				-<br>
				-<br>
				-
			<!--{/if}-->
		</td>
		<td class="tdc" style="">
		<!--{if $arr_product.product_type_add=="1" && $arr_product.product_kind_flg=="3"}-->
			<a href="javascript:void(0)" onclick="progressVideo(<!--{$pid|escape}-->,<!--{$row.student_id|escape}-->)">研修動画</a>：<!--{if $row.video_comp_count==$video_count}-->100%<!--{else}--><!--{$row.max_percent_video|escape}-->%<!--{/if}-->
			<br>
			<a href="javascript:void(0)" onclick="progressExam(<!--{$pid|escape}-->,<!--{$row.student_id|escape}-->)">設問</a>：<!--{$row.answer_val_exam|escape}-->/<!--{$row.max_val_exam|escape}-->
		<!--{else}-->
			-<br>-
		<!--{/if}-->
		</td>
		<td class="tdc" style="">
		<!--{if $arr_product.product_type_add=="1"}-->
			<!--[<!--{$row.all_end_view_count}-->][<!--{$video_count}-->]-->
			<!--{if $row.all_end_view_count==$video_count}-->
				100%
			<!--{else}-->
				<!--{if $arr_product.product_kind_flg=="3"}-->
					<!--{if $row.video_comp_count==$video_count}-->
						100%
					<!--{else}-->
						<!--{$row.percent|escape}-->%
					<!--{/if}-->
				<!--{else}-->
					<!--{$row.percent|escape}-->%
				<!--{/if}-->
<!--{*
				<!--{if $row.all_end_view_count=="0"}-->
					0%
				<!--{else}-->
					<!--{if $row.max_view_per!=""}-->
						<!--{$row.max_view_per|escape}-->%<!--<!--{$row.view_per|escape}-->%-->
					<!--{else}-->
						0%
					<!--{/if}-->
				<!--{/if}-->
*}-->
			<!--{/if}-->
		<!--{elseif $arr_product.product_type_add=="2"}-->
			<!--{if $row.participation_flg=="1"}-->
				100%
			<!--{else}-->
				0%
			<!--{/if}-->
		<!--{elseif $arr_product.product_type_add=="3"}-->
			<!--[<!--{$row.status|escape}-->]-->
			<!--ステータス（0:1次未受講 1:1次受講中 2:1次合格 3:1次不合格 4:2次受講中 5:2次合格 6:不合格 7:レポート 8:会場）-->
			<!--{if $row.status=="0" || $row.status==""}-->
				未受講<!--1次未受講-->
			<!--{elseif $row.status=="1"}-->
				受講中<!--1次受講中-->
			<!--{elseif $row.status=="2"}-->
				一次○<!--1次合格-->
			<!--{elseif $row.status=="3"}-->
				一次×<!--1次不合格-->
			<!--{elseif $row.status=="4"}-->
				一次×<!--2次受講中-->
			<!--{elseif $row.status=="5"}-->
				追試○<!--2次合格-->
			<!--{elseif $row.status=="6"}-->
				追試×<!--不合格-->
			<!--{elseif $row.status=="7"}-->
				レポート<!--レポート-->
			<!--{elseif $row.status=="8"}-->
				会場<!--会場-->
			<!--{/if}-->
		<!--{elseif $arr_product.product_type_add=="4"}-->
		<!--{/if}-->
		<br>
		<!--{if $arr_product.product_type_add=="1" && $arr_product.product_kind_flg=="3"}-->
			<!--{$row.gouhi|escape}-->
		<!--{else}-->
			-
		<!--{/if}-->
		</td>
	</tr>
	<!--{/foreach}-->
	<tr>
		<th class="pager" colspan="7">
<!--{$pager}-->
		</th>
	</tr>
</table>

