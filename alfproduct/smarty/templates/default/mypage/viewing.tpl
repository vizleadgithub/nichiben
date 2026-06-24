<!--{include file='mypage/side_menu.tpl'}-->

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">受講中の講座</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">受講中の講座</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたがこれまで受講した講座のうち、受講率が100%に達していない講座を表示しています。<br />
			※受講状況（受講率等）の表示は，１日１回更新されます。
			<!--{if !empty($arr_list)}-->
			<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:20px;padding: 0;">
				<div style="font-size:14px;text-align:left; float:left;width:245px;vertical-align:top;margin:0px;padding: 0;">
					<!--{if $page_max==0}-->
						全<!--{$all_count|escape}-->件
					<!--{else}-->
						<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
					<!--{/if}-->
				</div>
				<div style="font-size:14px;text-align:right; float:right;vertical-align:top;margin:0px;padding: 0;">
					<!--{$pager}-->
				</div>
			</div>
			<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:20px;padding: 0;">
				<div style="font-size:14px;text-align:right; float:right;width:350px;vertical-align:top;margin-right:20px;padding: 0;">
					<script type="text/javascript">
					<!--
					function select_pagemax(){
						location.href = "" + "?pagemax=" + document.form_pagemax.pagemax.options[document.form_pagemax.pagemax.selectedIndex].value;
					}
					function sort_exe(){
						location.href = "" + "?pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
					}
					// -->
					</script>
					<!--{*<form name="form_pagemax" style="vertical-align:top;margin:0px;padding: 0;font-size:14px;">*}-->
					<form name="form_selects" style="vertical-align:top;margin:0px;padding: 0;font-size:14px;">
					<!--{html_options name=sort options=$sort_select selected=$sort}-->に
					<select name="pagemax">
						<option value="5"<!--{if $page_max==5}--> selected=selected<!--{/if}-->>5</option>
						<option value="10"<!--{if $page_max==10}--> selected=selected<!--{/if}-->>10</option>
						<option value="15"<!--{if $page_max==15}--> selected=selected<!--{/if}-->>15</option>
						<option value="20"<!--{if $page_max==20}--> selected=selected<!--{/if}-->>20</option>
						<option value="0"<!--{if $page_max==0}--> selected=selected<!--{/if}-->>すべて</option>
					</select>件ずつ
					<a href="javascript: void(0)" onclick="sort_exe();return false;" style="vertical-align:top;margin:0px;padding: 0;"><img src="/img/mypage/listing_btn.png" style="vertical-align:top;margin:0;padding-bottom: 8px;"></a>
					</form>
				</div>
			</div>

			<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:100px;">受講状況</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;">講座名</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">総時間<br />残り時間</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:100px;">テスト合否<br>進捗</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">詳細</th>
				</tr>
				<!--{foreach from=$arr_list item=val}-->
				<!--{cycle values="0,1" assign="cycle_bg"}-->
				<tr style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->">
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">最終受講日<br /><!--{$val.reading_date|escape}--></td>
					<td rowspan="2" style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><!--{if $val.product_name_TOD!=''}--><!--{$val.product_name_TOD|escape}--><!--{elseif $val.product_name_TP!=''}--><!--{$val.product_name_TP|escape}--><!--{else}-->掲載終了しました。<!--{/if}--></td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><!--{$val.total_duration|escape}--></td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><!--{$val.test_passing|escape}--></td>
					<td rowspan="2" style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><!--{if $val.koukai_flg}--><a href="/product/detail.php?pid=<!--{$val.product_id|escape}-->" style="color:#796A57;">講座詳細へ</a><!--{else}-->公開終了<!--{/if}--></td>
				</tr>
				<tr style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->">
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">受講率<br /><!--{$val.max_percent|escape}-->%</td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><!--{$val.all_remaining|escape}--></td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><!--{$val.test_progress|escape}--></td>
				</tr>
				<!--{/foreach}-->
			</table>

			<div style="width:100%;font-size:10px;border-style: none;clear: both;height:;margin-top:10px;padding: 0;">
				<div style="font-size:14px;text-align:left; float:left;width:245px;vertical-align:top;margin:0px;padding: 0;">
					<!--{if $page_max==0}-->
						全<!--{$all_count|escape}-->件
					<!--{else}-->
						<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
					<!--{/if}-->
				</div>

				<div style="font-size:14px;text-align:right; float:right;vertical-align:top;margin:0px;padding: 0;">
					<!--{$pager}-->
				</div>

				<div style="font-size:14px;text-align:right; float:right;width:180px;vertical-align:top;margin-right:20px;padding: 0;">
				</div>
			</div>
			<!--{else}-->
				<div class="nonProductMsg">
				受講中の研修はありません。
				</div>
			<!--{/if}-->
		</div>
	</div>
</div>
