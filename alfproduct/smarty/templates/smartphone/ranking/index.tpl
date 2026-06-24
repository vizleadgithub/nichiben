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


<ul style="clear:both;color:#4b3921;font-size:14px;list-style:none;">
	<li style="float:left;"><a href="/">TOP</a></li>
	<li style="float:left;padding:0 5px;"><img style="height:10px;" alt="＞" src="/img/c_ar_2.png"></li>
	<li style="float:left;"><a href="">人気講座ランキング</a></li>
</ul>
<hr />

<h1 class="ranking_title" style="display: inline;"><img src="/img/ranking.png" alt="総合ランキング" />総合ランキング</h1>　　　　<img src="/img/ranking.png" alt="総合ランキング" /><font size="+1">その他のランキングは
	<!--{if !empty($file_list)}-->
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!--{foreach from=$file_list item="row"}-->
			<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
			<!--{if $row.file_text!=""}-->
				<a href="/custom_pages/cp-content/uploads/<!--{$row.file_path|escape}-->" target="_blank" rel="noopener noreferrer">こちら</a>
				<!--<a href="/custom_pages/cp-content/uploads/<!--{$row.file_path|escape}-->" target="_blank"><!--{$row.file_text|escape}--></a>-->
			<!--{/if}-->
			<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!--{/foreach}-->
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
	<!--{/if}-->
</font>

<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<div style="clear:both;border:hidden 0px #ffffff;">

	<!--{if !empty($arr_list)}-->


		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!--{foreach from=$arr_list item="row"}-->
			<div class="list_box" style="width:720px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!--{* e-ラーニング *}-->
				<!--{if $row.product_type_add == 1}-->
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;">
							<b><!--{$row.rank|escape}-->位</b>
						</div>
						<div style="float:left;text-align:left;display:inline-block;">
							<img src="/img/list/list_img002.jpg" alt="eラーニング" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;">
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
						<div style="float:right;text-align:right;display:inline-block;">
							<!--{if $row.favorite_flg}-->
								<form name="favoriteForm" action="/mypage/favorite.php" method="post">
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
							<div style="width:15%;display:inline-block;float:left;">総時間</div>
							<div style="float:left;"><!--{$row.all_play_time|escape}--></div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;float:left;">掲載期間</div>
							<div style="display:inline-block;float:left;">
								<!--{if $row.start_date!='' && $row.start_date!='0000-00-00 00:00:00' && $row.end_date!='' && $row.end_date!='0000-00-00 00:00:00'}-->
									<!--{$row.disp_start_date|escape}-->～<!--{$row.disp_end_date|escape}-->
								<!--{elseif $row.start_date!='' && $row.start_date!='0000-00-00 00:00:00'}-->
									<!--{$row.disp_start_date|escape}-->～
								<!--{elseif $row.end_date!='' && $row.end_date!='0000-00-00 00:00:00'}-->
									～<!--{$row.disp_end_date|escape}-->
								<!--{else}-->
									未定
								<!--{/if}-->
							</div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
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
						<div style="float:left;text-align:left;display:inline-block;">
							<b><!--{$row.rank|escape}-->位</b>
						</div>
						<div style="float:left;text-align:left;display:inline-block;">
							<img src="/img/list/list_img001.jpg" alt="会場研修" />
						</div>
						<div style="float:left;text-align:left;display:inline-block;width:330px;">
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
						<div style="float:right;text-align:right;display:inline-block;">
							<!--{if $row.favorite_flg}-->
								<form name="favoriteForm" action="/mypage/favorite.php" method="post">
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
							<div style="width:15%;display:inline-block;float:left;">受付期間</div>
							<div style="float:left;"><!--{$row.disp_start_date|escape}-->～<!--{$row.disp_end_date|escape}--></div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;float:left;">研修開催日</div>
							<div style="display:inline-block;float:left;"><!--{$row.disp_dates|escape}--></div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;display:inline-block;float:left;">主催</div>
							<div style="float:left;"><!--{$row.disp_sponsor}--></div>
						</div>
						<div style="width:49%;display:inline-block;float:left;">
							<div style="width:15%;float:left;">講師名</div>
							<div style="display:inline-block;float:left;"><!--{$row.memo2|escape|nl2br}--></div>
						</div>
					</div>
					<div style="width:100%;display:inline-block;">
						<!--{$row.memo|escape}-->
					</div>
					<div style="display:inline-block;float:right;">
						<img src="/img/list/ic_2ar.png" alt="" style="position:relative;top:5px;" />
						<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:57462e;font-size:14px;">講座詳細へ</a>
					</div>
				<!--{/if}-->
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
	<!--{/if}-->


</div>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->










