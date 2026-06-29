<style type="text/css">
	.css_cat_icon{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #32A60C;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#32A60C;
	}
	.css_cat_icon1{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #FF9C17;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#FF9C17;
	}
	.css_cat_icon2{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #FF9C17;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#FF9C17;
	}
	.css_cat_icon3{
		float: left;
		text-align: left;
		display: inline-block;
		border-radius: 3px;
		border: solid 1px #FF9C17;
		height: 18px;
		line-height: 18px;
		font-size: 12px;
		padding: 0 5px;
		color:#FF9C17;
	}
</style>



<div style="clear:both;text-align:left;color:#4b3921;font-size:14px;">
<a href="/">TOP</a>&nbsp;
<img src="/img/c_ar_off.png" alt="＞" style="height:10px;" />&nbsp;
掲載終了間近の講座
</div>



<!--{if !empty($arr_list)}-->
	<div class="pager">
		<div style="text-align:left; float:left;width:240px;">
			<!--{if $page_max==0}-->
				全<!--{$all_count|escape}-->件
			<!--{else}-->
				<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
			<!--{/if}-->
		</div>
		<div style="text-align:right; float:right;width:240px;">
			<!--{$pager}-->
		</div>
	</div>



<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;height:115px;">
	<h2>講座一覧</h2>
	<div class="selects">
		<form name="form_selects">
			<script type="text/javascript">
			<!--
			function sort_exe(){
				location.href = "" + "?pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
			}
			// -->
			</script>
			<span style="position:relative;bottom:5px;right:10px;">
			<!--{html_options name=sort options=$sort_select selected=$sort}-->に
			<select name="pagemax">
				<option value="5"<!--{if $page_max==5}--> selected=selected<!--{/if}-->>5</option>
				<option value="10"<!--{if $page_max==10}--> selected=selected<!--{/if}-->>10</option>
				<option value="15"<!--{if $page_max==15}--> selected=selected<!--{/if}-->>15</option>
				<option value="20"<!--{if $page_max==20}--> selected=selected<!--{/if}-->>20</option>
				<option value="0"<!--{if $page_max==0}--> selected=selected<!--{/if}-->>すべて</option>
			</select>件ずつ
			<a href="javascript:void(0);" onClick="sort_exe()"><img src="/img/list/permutation_btn.png" alt="並替え" style="position:relative;top:8px;" /></a>
			</span>
		</form>
		<div style="padding-top:15px;text-align:center;">
弁護士会主催研修は、各弁護士会からの情報に基づいています。<br />
詳細は各弁護士会にお問い合わせください。
		</div>
	</div>
</div>







		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!--{foreach from=$arr_list item="row"}-->
			<div class="list_box" style="width:698px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!--{* e-ラーニング *}-->
				<!--{if $row.product_type_add == 1}-->
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;width:162px;margin-top:8px;">
							<img src="/img/list/list_img002.jpg" alt="eラーニング" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;margin-top:8px;">
							<!--{foreach name=icon_loop from=$row.icon_img item=icon}-->
								<!--{assign var=icon_cnt value=$smarty.foreach.icon_loop.iteration}-->
								<!--{if $icon.src != ''}-->
									<img src="/img/<!--{$icon.src|escape}-->" alt="<!--{$icon.alt|escape}-->" style="display:inline-block;float:left;" />
								<!--{/if}-->
							<!--{/foreach}-->
							<!--{foreach name=icon_loop_i from=$row.css_icon_cat item=icon_row}-->
								<!--<div class="css_cat_icon"><!--{$icon_row.name|escape}--></div>-->
							<!--{/foreach}-->
							<!--{if $row.css_icon_new==1}-->
								<div class="css_cat_icon1">NEW</div>
							<!--{/if}-->
							<!--{if $row.css_icon_ninki==1}-->
								<div class="css_cat_icon2">人気</div>
							<!--{/if}-->
							<!--{if $row.css_icon_syokyu==1}-->
								<div class="css_cat_icon3">初級</div>
							<!--{/if}-->
						</div>
						<div style="float:right;text-align:right;display:inline-block;width:180px;">
							<!--{if $row.favorite_flg}-->
								<form name="favoriteForm" action="/mypage/favorite.php" method="post">
									<input type="hidden" name="csrf_token" value="<!--{|escape}-->" />
									<input type="hidden" name="act" value="regist" />
									<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
									<input type="image" src="/img/list/favorite_btn.png" />
								</form>
							<!--{/if}-->
							<!--{if $row.favorite_icon_flg}-->
								<img src="/img/list/favorite_btn_comp01.png" alt="お気に入り登録済" />
							<!--{/if}-->
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:#523d26;font-size:14px;font-weight:bold;"><!--{$row.product_name|escape}--></a>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;display:inline-block;float:left;">総時間</div>
							<div style="float:left;"><!--{$row.all_play_time|escape}--></div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;float:left;"><!--掲載期間-->掲載日</div>
							<div style="display:inline-block;float:left;">
								<!--{$row.regist_date|escape|date_format:"%Y/%m/%d"}-->
								<?php /*
								<!--{if $row.start_date!='' && $row.start_date!='0000-00-00 00:00:00' && $row.end_date!='' && $row.end_date!='0000-00-00 00:00:00'}-->
									<!--{$row.disp_start_date|escape}-->～<!--{$row.disp_end_date|escape}-->
								<!--{elseif $row.start_date!='' && $row.start_date!='0000-00-00 00:00:00'}-->
									<!--{$row.disp_start_date|escape}-->～
								<!--{elseif $row.end_date!='' && $row.end_date!='0000-00-00 00:00:00'}-->
									～<!--{$row.disp_end_date|escape}-->
								<!--{else}-->
									未定
								<!--{/if}-->
								*/ ?>
							</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;overflow:hidden;height:36px;" class="textOverflowTest4">
						<!--{$row.memo|escape}-->
					</div>
					<div style="display:inline-block;float:right;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!--{* 会場研修 *}-->
				<!--{elseif $row.product_type_add == 2}-->
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;width:162px;margin-top:8px;">
							<img src="/img/list/list_img001.jpg" alt="会場研修" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;margin-top:8px;">
							<!--{foreach name=icon_loop from=$row.icon_img item=icon}-->
								<!--{assign var=icon_cnt value=$smarty.foreach.icon_loop.iteration}-->
								<!--{if $icon.src != ''}-->
									<img src="/img/<!--{$icon.src|escape}-->" alt="<!--{$icon.alt|escape}-->" style="display:inline-block;float:left;" />
								<!--{/if}-->
							<!--{/foreach}-->
							<!--{foreach name=icon_loop_i from=$row.css_icon_cat item=icon_row}-->
								<!--<div class="css_cat_icon"><!--{$icon_row.name|escape}--></div>-->
							<!--{/foreach}-->
							<!--{if $row.css_icon_new==1}-->
								<div class="css_cat_icon1">NEW</div>
							<!--{/if}-->
							<!--{if $row.css_icon_ninki==1}-->
								<div class="css_cat_icon2">人気</div>
							<!--{/if}-->
							<!--{if $row.css_icon_syokyu==1}-->
								<div class="css_cat_icon3">初級</div>
							<!--{/if}-->
						</div>
						<div style="float:right;text-align:right;display:inline-block;width:180px;">
							<!--{if $row.favorite_flg}-->
								<form name="favoriteForm" action="/mypage/favorite.php" method="post">
									<input type="hidden" name="csrf_token" value="<!--{|escape}-->" />
									<input type="hidden" name="act" value="regist" />
									<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
									<input type="image" src="/img/list/favorite_btn.png" />
								</form>
							<!--{/if}-->
							<!--{if $row.favorite_icon_flg}-->
								<img src="/img/list/favorite_btn_comp01.png" alt="お気に入り登録済" />
							<!--{/if}-->
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:#523d26;font-size:14px;font-weight:bold;"><!--{$row.product_name|escape}--></a>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;display:inline-block;float:left;">受付期間</div>
							<div style="float:left;"><!--{$row.disp_start_date|escape}-->～<!--{$row.disp_end_date|escape}--></div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;float:left;">研修開催日</div>
							<div style="display:inline-block;float:left;"><!--{$row.disp_dates|escape}--></div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;display:inline-block;float:left;">主催</div>
							<div style="float:left;"><!--{$row.disp_sponsor}--></div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:20%;float:left;">講師名</div>
							<div style="display:inline-block;float:left;"><!--{$row.memo2|escape|nl2br}--></div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;overflow:hidden;height:36px;" class="textOverflowTest4">
						<!--{$row.memo|escape}-->
					</div>
					<div style="display:inline-block;float:right;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!--{* 情報なし(ここに入ることはない想定) *}-->
				<!--{else}-->
					<div class="nonProductMsg">
					講座及び研修情報が存在しません。
					</div>
				<!--{/if}-->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!--{*
				<div style="float:left;width:100px;text-align:center;" class="thumb">
					<!--{if $row.thumbnail==''}-->
						<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=noimage.jpg&width=100&height=100" alt="" /></a>
					<!--{else}-->
						<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=<!--{$row.thumbnail}-->&width=100&height=100" alt="" /></a>
					<!--{/if}-->
				</div>

				<div style="float:right;width:385px">
					<table>
						<tr>
							<th colspan="2" style="text-align:left;vertical-align:top;color: #47A6D4;" class="title"><a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color: #47A6D4;"><!--{$row.product_name|escape}--></a></th>
						</tr>
						<tr>
							<th style="width:80px;text-align:left;vertical-align:top;">講師</th>
							<td style="text-align:left;vertical-align:top;"><!--{$row.teacher|escape}--></td>
						<tr>
						<tr>
							<th style="width:80px;text-align:left;vertical-align:top;">再生時間</th>
							<td style="text-align:left;vertical-align:top;"><!--{$row.play_time|escape}--></td>
						<tr>
						<tr>
							<th style="width:80px;text-align:left;vertical-align:top;">視聴期間</th>
							<td style="text-align:left;vertical-align:top;"><!--{if $row.open_period==0}-->購入日から無期限<!--{else}-->購入日から<!--{$row.open_period|escape}-->日間<!--{/if}--></td>
						<tr>
						<tr>
							<th style="width:80px;text-align:left;vertical-align:top;">価格</th>
							<td style="text-align:left;vertical-align:top;"><!--{if $row.price_intax==0}-->無料<!--{else}--><!--{$row.price_intax|escape|number_format}-->円(税込)<!--{/if}--></td>
						<tr>
						<tr>
							<td colspan="2" style="text-align:left;vertical-align:top;"><!--{$row.memo|mb_truncate:120:"..."|escape|nl2br}--></td>
						<tr>
					</table>
					<div style="float:right;width:300px;text-align:right;padding:5px;">
						<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->">→講座詳細へ</a>
					</div>
				</div>
				*}-->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<div style="text-align:center;">
					<img src="/img/list/dotline.png" alt="" style="width:100%;" />
				</div>
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
			</div>
		<!--{/foreach}-->
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


	<div class="pager">
		<div style="text-align:left; float:left;width:240px;">
			<!--{if $page_max==0}-->
				全<!--{$all_count|escape}-->件
			<!--{else}-->
				<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
			<!--{/if}-->
		</div>
		<div style="text-align:right; float:right;width:240px;">
			<!--{$pager}-->
		</div>
	</div>
<!--{else}-->
	<div class="nonProductMsg">
	該当商品はありません。
	</div>
<!--{/if}-->
