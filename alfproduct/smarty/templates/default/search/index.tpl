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
	.css_cat_icon4{
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
	.css_cat_icon4 {
	  float: left;
	  display: inline-block;
	  border-radius: 1px;
	  border: solid 1px #1BAED9;
	  height: 18px;
	  line-height: 18px;
	  font-size: 14px;
	  padding: 0 5px;
	  background-color: #1BAED9;
	  color: #fff;
	  font-weight: 600;
	  width: 160px;
	  text-align: center;
	}
</style>

<ul style="clear:both;color:#4b3921;font-size:14px;list-style:none;">
	<li style="float:left;"><a href="/">TOP</a></li>
	<li style="float:left;padding:0 5px;"><img style="height:10px;" alt="＞" src="/img/c_ar_2.png"></li>
	<li style="float:left;"><a href="">検索</a></li>
</ul>
<hr />


<h2 class="search_title">検索</h2>






<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!--{*
<!-- 検索条件 -->
<div style="clear:both;border:dotted 1px #cccccc; padding:10px; margin-bottom:10px;">
	<h2>検索条件</h2>
	<hr />
	<!--{if (is_array($arr_keyword) && $arr_keyword|@count>0) || (is_array($search_category) && $arr_search_category_disp|@count>0) }-->
		<!--{if is_array($arr_search_category_disp) && $arr_search_category_disp|@count>0}-->
			<table><tr><td>
			カテゴリ：</td><td>
			<!--{foreach from=$arr_search_category_disp item="row"}-->
				<!--{$row|escape}--><br>
			<!--{/foreach}-->
			</td></tr></table>
			<br>
		<!--{/if}-->
		<!--{if is_array($arr_keyword) && $arr_keyword|@count>0}-->
		<hr style="margin-bottom:5px;" />
			キーワード：
			<!--{foreach from=$arr_keyword item="row"}-->
				<!--{$row|escape}-->&nbsp;&nbsp;
			<!--{/foreach}-->
			<br>
		<!--{/if}-->
		<!--{if is_array($arr_keyword) && $arr_keyword|@count>0}-->
			無料・有料：
			<!--{if $search_price_free=="1"}-->指定無し<!--{/if}-->
			<!--{if $search_price_free=="2"}-->無料<!--{/if}-->
			<!--{if $search_price_free=="3"}-->有料<!--{/if}-->
			<br>
		<!--{/if}-->
	<!--{else}-->
		全件表示<br>
	<!--{/if}-->
</div>
*}-->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->



<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!--{if $disp_flg}-->
	<div style="clear:both;border:hidden 0px #ffffff;">

	<!--{if !empty($arr_list)}-->

		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!-- 改ページ -->
		<div class="pager">
			<div style="text-align:left; float:left;width:240px;">
				<!--{if $page_max==0}-->
					全<!--{$all_count|escape}-->件
				<!--{else}-->
					<!--{$list_start|escape}-->～<!--{$list_end|escape}-->件を表示中（全<!--{$all_count|escape}-->件中）
				<!--{/if}-->
			</div>
			<div style=" float:right;width:240px;">
				<!--{$pager}-->
			</div>
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!-- 一覧表示件数 -->
		<div class="list_title" style="border:solid 1px #f3f3f3;border-bottom:solid 2px #f3f3f3;height:115px;">
			<h2>
				<img src="/img/top_icon_3.png" alt="新着eラーニング"><span style="vertical-align: top;line-height: 30px;margin-left: 6px;">検索結果</span>
			</h2>
			<div class="selects">
				<form name="form_selects">
					<script type="text/javascript">
					<!--
					function sort_exe(){
						location.href = "" + "?pcid=<!--{$pcid}--><!--{$url_plam}-->&pagemax=" + document.form_selects.pagemax.options[document.form_selects.pagemax.selectedIndex].value + "&page=1&sort=" + document.form_selects.sort.options[document.form_selects.sort.selectedIndex].value;
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
					弁護士会主催研修は、各弁護士会からの情報に基づいています。
					詳細は各弁護士会にお問い合わせください。
				</div>
			</div>
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->




		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<!--{foreach from=$arr_list item="row"}-->
			<div class="list_box" style="width:698px;float:left;clear:both;background-color:#ffffff;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
				<!--{* e-ラーニング *}-->
				<!--{if $row.product_type_add == 1}-->
					<div style="width:100%;display:inline-block;">
						<div style="float:left;text-align:left;display:inline-block;width:162px;margin-top:8px;">
							<!--[<!--{$row.search_point|escape}-->]-->
							<!--{if $row.css_icon_etcmovie==1}-->
								<img src="/img/list/list_img003.jpg" alt="その他動画" />
							<!--{else}-->
								<img src="/img/list/list_img002.jpg" alt="eラーニング" />
							<!--{/if}-->
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
							<!--{if $row.css_icon_etcmovie==1}-->
								<!--<div class="css_cat_icon4">その他動画</div>-->
							<!--{/if}-->
						</div>
						<div style="float:right;text-align:right;display:inline-block;width:180px;">
							<!--{if $row.favorite_flg}-->
								<form name="favoriteForm" id="favoriteForm<!--{$row.product_id|escape}-->">
									<input type="hidden" name="act" value="regist" />
									<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
									<button type="button" style="border: none; background: none; padding: 0;cursor: pointer;" onclick="favoriteForm<!--{$row.product_id|escape}-->()">
										<img src="/img/list/favorite_btn.png">
									</button>
								</form>
								<script type="text/javascript">
								function favoriteForm<!--{$row.product_id|escape}-->(){
									let formData = $('#favoriteForm<!--{$row.product_id|escape}-->');
									$.ajax({
										type:  "post",
										url:   "/mypage/favorite.php",
										data:  formData.serialize(),
									}).done(function(resData) {
										//成功時の処理
										location.href = "" + "?pcid=<!--{$pcid}--><!--{$url_plam}-->&pagemax=<!--{$page_max}-->&page=<!--{$page}-->&sort=<!--{$sort}-->";
									}).fail(function(resData) {
										//エラー時の処理
									});
								}
								</script>
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
								<!--{* <?php /*
								<!--{if $row.start_date!='' && $row.start_date!='0000-00-00 00:00:00' && $row.end_date!='' && $row.end_date!='0000-00-00 00:00:00'}-->
									<!--{$row.disp_start_date|escape}-->～<!--{$row.disp_end_date|escape}-->
								<!--{elseif $row.start_date!='' && $row.start_date!='0000-00-00 00:00:00'}-->
									<!--{$row.disp_start_date|escape}-->～
								<!--{elseif $row.end_date!='' && $row.end_date!='0000-00-00 00:00:00'}-->
									～<!--{$row.disp_end_date|escape}-->
								<!--{else}-->
									未定
								<!--{/if}-->
								*/ ?> *}-->
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
							<!--[<!--{$row.search_point|escape}-->]-->
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
							<!--{if $row.css_icon_etcmovie==1}-->
								<div class="css_cat_icon4">その他動画</div>
							<!--{/if}-->
						</div>
						<div style="float:right;text-align:right;display:inline-block;width:180px;">
							<!--{if $row.favorite_flg}-->
								<form name="favoriteForm" id="favoriteForm<!--{$row.product_id|escape}-->">
									<input type="hidden" name="act" value="regist" />
									<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
									<button type="button" style="border: none; background: none; padding: 0;cursor: pointer;" onclick="favoriteForm<!--{$row.product_id|escape}-->()">
										<img src="/img/list/favorite_btn.png">
									</button>
								</form>
								<script type="text/javascript">
								function favoriteForm<!--{$row.product_id|escape}-->(){
									let formData = $('#favoriteForm<!--{$row.product_id|escape}-->');
									$.ajax({
										type:  "post",
										url:   "/mypage/favorite.php",
										data:  formData.serialize(),
									}).done(function(resData) {
										//成功時の処理
										location.href = "" + "?pcid=<!--{$pcid}--><!--{$url_plam}-->&pagemax=<!--{$page_max}-->&page=<!--{$page}-->&sort=<!--{$sort}-->";
									}).fail(function(resData) {
										//エラー時の処理
									});
								}
								</script>
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
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->

	<!--{else}-->
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<div class="nonProductMsg">
			該当する講座及び研修はありません。
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
	<!--{/if}-->


	</div>
<!--{/if}-->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->








<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<form name="search_form" method="post" action="index.php?search=new#main">


	<div style="border: solid 1px #a1c873;padding: 1px;">
		<table style="width:100%;background-color: #ffffff;" class="member_table">
			<tr>
				<th colspan="2" style="width:100px; background: url(/img/mushimegane.png)no-repeat 1% 50%; background-color:#a1c873; color:#ffffff;text-align:left;vertical-align:top;padding:6px 5px 6px 30px;font-size:14px;">ご希望の条件を選択して講座を絞り込むことができます（複数選択可）</th>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">検索条件</th>
				<td style="padding:5px;">
					<label><input type="radio" name="search_type" value="AND" <!--{if $search_type=="AND"}-->checked<!--{/if}--> <!--{*onclick="search_option_form_open('AND')"*}-->>AND検索</label>
					<label><input type="radio" name="search_type" value="OR" <!--{if $search_type!="AND"}-->checked<!--{/if}--> <!--{*onclick="search_option_form_open('OR')"*}-->>OR検索</label>
				</td>
			</tr>
			<script type="text/javascript">
				//function search_option_form_open( search_form_type ){
				//	if( search_form_type=="OR" ){
				//		$("#search_option_form").show();
				//	} else {
				//		$("#search_option_form").hide();
				//	}
				//}
			</script>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">キーワード</th>
				<td style="padding:5px;">
					<input type="text" name="search_keyword" value="<!--{$search_keyword|escape}-->">
				</td>
			</tr>
		</table>
	</div>


	<div id="search_option_form" style="border: solid 1px #a1c873;padding: 1px;<!--{*if $search_type=="OR"}-->display:block;<!--{else}-->display:none;<!--{/if*}-->">
		<table style="width:100%;background-color: #ffffff;" class="member_table">
			<tr>
				<th colspan="2" style="width:100px; background-color:#a1c873; color:#ffffff;text-align:left;vertical-align:top;padding:6px 5px 6px 30px;font-size:14px;">
					▼▼　下記の条件でさらに絞り込む（絞り込みたい事項にチェック）
				</th>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">講座種別</th>
				<td style="padding:5px;">
					<!--{html_checkboxes name='search_training' options=$arr_search_training selected=$search_training separator='<br />'}-->
				</td>
			</tr>
			<!--{*
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">主催</th>
				<td style="padding:5px;">
					<!--{html_checkboxes name='search_sponsor' options=$arr_search_sponsor selected=$search_sponsor}-->
				</td>
			</tr>
			*}-->
			<!--{*
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">研修料金</th>
				<td style="padding:5px;">
					<input type="radio" name="search_price_free" id="search_price_free1" value="1" <!--{if $search_price_free=="1"}--> checked=checked <!--{/if}-->><label for="search_price_free1">指定無し</label>
					<input type="radio" name="search_price_free" id="search_price_free2" value="2" <!--{if $search_price_free=="2"}--> checked=checked <!--{/if}-->><label for="search_price_free2">無料</label>
					<input type="radio" name="search_price_free" id="search_price_free3" value="3" <!--{if $search_price_free=="3"}--> checked=checked <!--{/if}-->><label for="search_price_free3">有料</label>
				</td>
			</tr>
			*}-->
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">eラーニング受講状況</th>
				<td style="padding:5px;">
					<!--{html_checkboxes name='search_state' options=$arr_search_state selected=$search_state}-->
				</td>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">eラーニング掲載期間</th>
				<td style="padding:5px;">
					<ul style="list-style:none;">
					<li style="float:left;"><input type="text" name="search_start_date" id="start_date" class="calendar" value="<!--{$search_start_date|escape}-->" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_date.value='';">クリア</a></li>
					<li style="float:left;padding:5px;">～</li>
					<li style="float:left;"><input type="text" name="search_end_date" id="end_date" class="calendar" value="<!--{$search_end_date|escape}-->" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_date.value='';">クリア</a></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">研修開催日</th>
				<td style="padding:5px;">
					<ul style="list-style:none;">
					<li style="float:left;"><input type="text" name="search_start_contents_date" id="start_contents_date" class="calendar" value="<!--{$search_start_contents_date|escape}-->" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.start_contents_date.value='';">クリア</a></li>
					<li style="float:left;padding:5px;">～</li>
					<li style="float:left;"><input type="text" name="search_end_contents_date" id="end_contents_date" class="calendar" value="<!--{$search_end_contents_date|escape}-->" size="9" readonly /></li>
					<li style="float:left;padding:5px;"><a class="clear_date" href="javascript:void(0);" onclick="javascript:document.search_form.end_contents_date.value='';">クリア</a></li>
					</ul>
				</td>
			</tr>
			<tr>
				<script type="text/javascript">
					/* ブラウザ判別 */
					var ie=document.all ? 1 : 0;
					var ns6=document.getElementById&&!document.all ? 1 : 0;
					var opera=window.opera ? 1 : 0;

					/* 子メニューの表示・非表示切替 */
					function openFolder(childObj, parentObj){
						var child="";
						var parent="";
						var sw="/img/list/show.gif"; /* フォルダ表示時のアイコン画像 */
						var hd="/img/list/hide.gif"; /* フォルダ非表示時のアイコン画像 */
						if(ie || ns6 || opera){
							child=ns6 ? document.getElementById(childObj).style : document.all(childObj).style;
							parent=ns6 ? document.getElementById(parentObj) : document.all(parentObj);
							if (child.display=="none"){
								child.display="block";
								parent.src=sw;
							}else{
								child.display="none";
								parent.src=hd;
							}
						}
					}
				</script>
				<th style="width:100px;background-color:#a1c873;color:#ffffff;text-align:left;vertical-align:top;padding:5px;">カテゴリ</th>
				<td style="padding:5px;">
					<input type="button" value="全てにチェックを入れる" onClick="categoryAllCheck(1)" />
					<input type="button" value="全てのチェックを外す" onClick="categoryAllCheck(0)" />
					<!--<input type="button" value="カテゴリ一覧開閉" onClick="opnClz('search_category_list_opclz')" />-->
					<br />
					<div id="search_category_list_opclz">
					<ul style="list-style-type:none;">
					<!--{foreach from=$arr_category item="row"}-->
						<li>
							<input type="checkbox" name="search_category[]" value="<!--{$row.term_id|escape}-->" id="arr_term_id<!--{$row.term_id|escape}-->" onclick="check_cat('<!--{$row.term_id|escape}-->');"<!--{if in_array($row.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row.term_id|escape}-->"><!--{$row.name|escape}--></label>&nbsp;&nbsp;
							<!--{if $row.categorys}--><img id="close_<!--{$row.term_id|escape}-->" src="/img/list/hide.gif" onclick="openFolder('open_<!--{$row.term_id|escape}-->', 'close_<!--{$row.term_id|escape}-->')" alt="" /><!--{/if}-->
						</li>
						<div id="open_<!--{$row.term_id|escape}-->" class="child" style="display:none;">
						<ul style="margin-left:15px;list-style-type:none;">
						<!--{foreach from=$row.categorys item="row2"}-->
							<li>
								→<input type="checkbox" name="search_category[]" value="<!--{$row2.term_id|escape}-->" id="arr_term_id<!--{$row2.term_id|escape}-->" onclick="check_cat('<!--{$row2.term_id|escape}-->');"<!--{if in_array($row2.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row2.term_id|escape}-->"><!--{$row2.name|escape}--></label>&nbsp;&nbsp;
								<!--{if $row2.categorys}--><img id="close_<!--{$row2.term_id|escape}-->" src="/img/list/hide.gif" onclick="openFolder('open_<!--{$row2.term_id|escape}-->', 'close_<!--{$row2.term_id|escape}-->')" alt="" /><!--{/if}-->
							</li>
							<div id="open_<!--{$row2.term_id|escape}-->" class="child" style="display:none;">
							<ul style="margin-left:15px;list-style-type:none;">
							<!--{foreach from=$row2.categorys item="row3"}-->
								<li>
									→→<input type="checkbox" name="search_category[]" value="<!--{$row3.term_id|escape}-->" id="arr_term_id<!--{$row3.term_id|escape}-->" onclick="check_cat('<!--{$row3.term_id|escape}-->');"<!--{if in_array($row3.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row3.term_id|escape}-->"><!--{$row3.name|escape}--></label>&nbsp;&nbsp;
									<!--{if $row3.categorys}--><img id="close_<!--{$row3.term_id|escape}-->" src="/img/list/hide.gif" onclick="openFolder('open_<!--{$row3.term_id|escape}-->', 'close_<!--{$row3.term_id|escape}-->')" alt="" /><!--{/if}-->
								</li>
								<div id="open_<!--{$row3.term_id|escape}-->" class="child" style="display:none;">
								<ul style="margin-left:15px;list-style-type:none;">
								<!--{foreach from=$row3.categorys item="row4"}-->
									<li>
										→→→<input type="checkbox" name="search_category[]" value="<!--{$row4.term_id|escape}-->" id="arr_term_id<!--{$row4.term_id|escape}-->" onclick="check_cat('<!--{$row4.term_id|escape}-->');"<!--{if in_array($row4.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row4.term_id|escape}-->"><!--{$row4.name|escape}--></label>&nbsp;&nbsp;
										<!--{if $row4.categorys}--><img id="close_<!--{$row4.term_id|escape}-->" src="/img/list/hide.gif" onclick="openFolder('open_<!--{$row4.term_id|escape}-->', 'close_<!--{$row4.term_id|escape}-->')" alt="" /><!--{/if}-->
									</li>
									<div id="open_<!--{$row4.term_id|escape}-->" class="child" style="display:none;">
									<ul style="margin-left:15px;list-style-type:none;">
									<!--{foreach from=$row4.categorys item="row5"}-->
										<li>
											→→→→<input type="checkbox" name="search_category[]" value="<!--{$row5.term_id|escape}-->" id="arr_term_id<!--{$row5.term_id|escape}-->" onclick="check_cat('<!--{$row5.term_id|escape}-->');"<!--{if in_array($row5.term_id,$search_category)}--> checked="checked"<!--{/if}--> ><label for="arr_term_id<!--{$row5.term_id|escape}-->"><!--{$row5.name|escape}--></label>&nbsp;&nbsp;
										</li>
									<!--{/foreach}-->
									</ul>
									</div>
								<!--{/foreach}-->
								</ul>
								</div>
							<!--{/foreach}-->
							</ul>
							</div>
						<!--{/foreach}-->
						</ul>
						</div>
					<!--{/foreach}-->
					</ul>
					</div>
				</td>
			</tr>
			<script type="text/javascript">
				var arr_cat_id = [];
				var arr_cat_name = [];
				var arr_par_id = [];
				<!--{foreach from=$arr_cat_list item=val}-->
					arr_cat_id[arr_cat_id.length] = "<!--{$val.term_id|escape}-->";
					arr_cat_name[arr_cat_name.length] = "<!--{$val.name|escape}-->";
					arr_par_id[arr_par_id.length] = "<!--{$val.parent|escape}-->";
				<!--{/foreach}-->
				function check_cat(cat_id){
					var temp_i = cat_id;
					if (document.getElementById("arr_term_id"+cat_id).checked ===true){
						while (temp_i!="0" && temp_i!="21" ){
							for (var i=0;i<arr_cat_id.length;i++){
								if(temp_i==arr_cat_id[i]){
									//alert(temp_i + ":" + arr_par_id[i]);
									if(document.getElementById("arr_term_id"+temp_i) != null){
										document.getElementById("arr_term_id"+temp_i).checked = true;
									}
									temp_i = arr_par_id[i];
								}
							}
						}
					} else {
						var select_cat_id = new Array(cat_id);
						while(select_cat_id.length>0){
							var select_par_id = new Array();
							for (var i=0;i<select_cat_id.length;i++){
								for (var n=0;n<arr_par_id.length;n++){
									if (arr_par_id[n]==select_cat_id[i]){
										select_par_id[select_par_id.length] = arr_cat_id[n];
									} 
								}
							}
							for (var i=0;i<select_par_id.length;i++){
								if(document.getElementById("arr_term_id"+select_par_id[i]) != null){
									document.getElementById("arr_term_id"+select_par_id[i]).checked = false;
								}
							}
							select_cat_id = select_par_id;
						}
					}
				}
				
				function categoryAllCheck(flg){
					if (flg==1){
						var str = 'check';
					} else {
						var str = '';
					}
					for(var count=0; count<document.search_form.elements["search_category[]"].length; count++){
						document.search_form.elements["search_category[]"][count].checked = str;
					}
				}
				<!--{*
				function checkDisabled(){
					if (document.getElementsByName("search_training[]").item(0).checked === true && document.getElementsByName("search_training[]").item(1).checked === true){
						for(var count=0; count<document.search_form.elements["search_sponsor[]"].length; count++){
							document.search_form.elements["search_sponsor[]"][count].disabled = false;
						}
						for(var count=0; count<document.search_form.elements["search_state[]"].length; count++){
							document.search_form.elements["search_state[]"][count].disabled = false;
						}
						
					} else if (document.getElementsByName("search_training[]").item(0).checked === false && document.getElementsByName("search_training[]").item(1).checked === false){
						for(var count=0; count<document.search_form.elements["search_sponsor[]"].length; count++){
							document.search_form.elements["search_sponsor[]"][count].disabled = true;
						}
						for(var count=0; count<document.search_form.elements["search_state[]"].length; count++){
							document.search_form.elements["search_state[]"][count].disabled = true;
						}
						
					}  else {
						if (document.getElementsByName("search_training[]").item(0).checked === true){
							for(var count=0; count<document.search_form.elements["search_sponsor[]"].length; count++){
								document.search_form.elements["search_sponsor[]"][count].disabled = true;
							}
						} else {
							for(var count=0; count<document.search_form.elements["search_sponsor[]"].length; count++){
								document.search_form.elements["search_sponsor[]"][count].disabled = false;
							}
						}
						
						if (document.getElementsByName("search_training[]").item(1).checked === true){
							for(var count=0; count<document.search_form.elements["search_state[]"].length; count++){
								document.search_form.elements["search_state[]"][count].disabled = true;
							}
						} else {
							for(var count=0; count<document.search_form.elements["search_state[]"].length; count++){
								document.search_form.elements["search_state[]"][count].disabled = false;
							}
						}
					}
				}
				
				window.onload = checkDisabled();
				*}-->
			</script>
		</table>
	</div>

	<div style="padding:20px 0;text-align:center;">
		弁護士会主催研修は各弁護士会からの情報に基づいています。<br />詳細は各弁護士会にお問い合わせ下さい。
	</div>

	<table style="width:100%;">
		<tr>
			<td style="padding:5px;text-align:center;width:100%;">
				<input type="image" src="/img/btn/search.png" name="btn_submit" value="　検索　">
			</td>
		</tr>
	</table>

</form>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


