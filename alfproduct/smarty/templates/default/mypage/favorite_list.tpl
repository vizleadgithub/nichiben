<!--{include file='mypage/side_menu.tpl'}-->

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">お気に入りの講座</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">お気に入りの講座</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたがお気に入りに登録した講座を表示しています。<br />
			自由に並び替えや削除ができます。

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

					<div style="font-size:14px;text-align:right; float:right;width:180px;vertical-align:top;margin-right:20px;padding: 0;">
						<script type="text/javascript">
						<!--
						function select_pagemax(){
							location.href = "" + "?pagemax=" + document.form_pagemax.pagemax.options[document.form_pagemax.pagemax.selectedIndex].value;
						}
						// -->
						</script>
						<form name="form_pagemax" style="vertical-align:top;margin:0px;padding: 0;font-size:14px;">
						<select name="pagemax">
							<option value="5"<!--{if $page_max==5}--> selected=selected<!--{/if}-->>5</option>
							<option value="10"<!--{if $page_max==10}--> selected=selected<!--{/if}-->>10</option>
							<option value="15"<!--{if $page_max==15}--> selected=selected<!--{/if}-->>15</option>
							<option value="20"<!--{if $page_max==20}--> selected=selected<!--{/if}-->>20</option>
							<option value="0"<!--{if $page_max==0}--> selected=selected<!--{/if}-->>すべて</option>
						</select>件ずつ
						<a href="javascript: void(0)" onclick="select_pagemax();return false;" style="vertical-align:top;margin:0px;padding: 0;"><img src="/img/mypage/listing_btn.png" style="vertical-align:top;margin:0;padding-bottom: 8px;"></a>
						</form>
					</div>
				</div>

				<div style="width:650px;height:40px;border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;clear: both;font-fize:16px;color:#333333;font-weight: bold;margin:0;padding:0;">
					<div style="margin-top:15px;">お気に入りの講座</div>
				</div>
				<!--{foreach from=$arr_list item="row" name="fav"}-->
				<!--{cycle values="0,1" assign="cycle_bg"}-->
				<div style="width:650px;height:135px;float:left;clear:both;<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;border-style: none solid solid;margin:0;padding:0;">
					<!--{* eラーニング *}-->
					<!--{if $row.product_type_add == 1}-->
					<div style="float:left;width:160px;height:115px;text-align:center;background-color:#99e5fd;margin:10px;" class="thumb thumb2">
						<img src="/img/list/list_img002.jpg" alt="eラーニング" />
						<div style="margin-top:5px;">
						<!--{if $row.thumbnail_flg == 1}-->
							<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=<!--{$row.thumbnail}-->&width=155&height=85" alt="" style="" /></a>
						<!--{elseif $row.thumbnail_flg == 2}-->
							<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_video_image.php?image=<!--{$row.video_thumbnail}-->&width=155&height=85" alt="" style="" /></a>
						<!--{else}-->
							<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=noimage.jpg&width=155&height=85" alt="" style="" /></a>
						<!--{/if}-->
						</div>
					</div>
					<!--{* 会場研修 *}-->
					<!--{elseif $row.product_type_add == 2}-->
					<div style="float:left;width:160px;height:115px;text-align:center;background-color:#8EBD55;margin:10px;" class="thumb thumb2">
						<div style="margin-top:5px;">
						<img src="/img/list/list_img001.jpg" alt="会場研修" />
						<!--{if $row.thumbnail != ""}-->
							<a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=<!--{$row.thumbnail}-->&width=155&height=85" alt="" style="" /></a>
						<!--{else}-->
							<!--{if $row.echic_flg == 1}-->
								<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=product_ethic.jpg&width=155&height=85" alt="" style="" /></a>
							<!--{elseif $row.training_kind_flg == 1}-->
								<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=product_live.jpg&width=155&height=85" alt="" style="" /></a>
							<!--{else}-->
								<a href="./../product/detail.php?pid=<!--{$row.product_id|escape}-->"><img src="/resize_image.php?image=product_bar_association.jpg&width=155&height=85" alt="" style="" /></a>
							<!--{/if}-->
						<!--{/if}-->
						</div>
						<div style="position:relative;top:20px;">
							<!--{$row.icon|escape}-->
						</div>
					</div>
					<!--{/if}-->

				
					<div style="float:right;width:470px;height:135px;text-align:center;">
						<div style="float:left;width:330px;height:125px; border: solid 1px #AB9983;padding-top:10px;border-style: none solid none none;overflow:hidden;">
							<table>
								<tr>
									<th colspan="2" style="text-align:left;vertical-align:top;"><a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->" style="color:#5E4D34;font-size:18px;font-weight:normal;"><!--{$row.product_name|mb_substr:0:70:'utf-8'|escape}--><!--{if mb_strlen($row.product_name, 'utf-8') > 70}-->...<!--{/if}--></a></th>
								</tr>
								<!--{* eラーニング *}-->
							<!--{*
								<!--{if $row.product_type_add == 1}-->
									<tr>
										<th colspan="2" style="text-align:left;vertical-align:top;color:#333333;font-size:16px;font-weight:normal;"><!--{$row.memo|escape|nl2br}--></th>
									</tr>
							*}-->
								<!--{* 会場研修 *}-->
							<!--{*
								<!--{elseif $row.product_type_add == 2}-->
									<tr>
										<th colspan="2" style="text-align:left;vertical-align:top;color:#333333;font-size:16px;font-weight:normal;"><!--{$row.memo1|escape|nl2br}--></th>
									</tr>
								<!--{/if}-->
							*}-->
							</table>
							
						</div>
						<div style="float:right;width:139px;height:135px;">
							<table style="border: solid 0px #000000;width:100%;height:100%;">
							<tr style="border: solid 1px #AB9983;border-style: none none dotted none;height:55px;font-size:14px;">
								<td colspan="2" style="text-align:center;font-size:14px;padding-top:5px;">お気に入りから<br>
									<form name="form_delete<!--{$row.product_id|escape}-->" action="favorite.php" method="post">
									<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
									<input type="hidden" name="act" value="delete" />
									<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
									<!--<input type="submit" value="削除" />-->
									<a href="javascript:void(0);" onclick="document.form_delete<!--{$row.product_id|escape}-->.submit();"><img src="/img/mypage/delete_btn.png" alt="削除" /></a>
									</form>
								</td>
							</tr>
							<!--{if $all_count > 1}-->
							<tr style="border: none;border-style: none;font-size:14px;">
								<td colspan="2" style="text-align:center;font-size:14px;padding-top:5px;">
									この講座を
								</td>
							</tr>
							<tr style="font-size:14px;">
								<!--[page:<!--{$page}-->]-->
								<!--[list_end:<!--{$list_end}-->]-->
								<!--[all_count:<!--{$all_count}-->]-->
								<td style="text-align:center;font-size:14px;">
									<!--{if !($page == 1 && $smarty.foreach.fav.first) && !($page_max==0 && $smarty.foreach.fav.first)}-->
										<form name="form_up<!--{$row.product_id|escape}-->" action="favorite.php" method="post">
										<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
										<input type="hidden" name="act" value="up" />
										<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
										<!--<input type="submit" value="↑" />-->
										<a href="javascript:void(0);" onclick="document.form_up<!--{$row.product_id|escape}-->.submit();" style="color:#5E4D34"><img src="/img/mypage/ic_up.png" alt="上へ" />上へ</a>
										</form>
									<!--{/if}-->
								</td>
								<td style="text-align:center;font-size:14px;">
									<!--{if !($list_end == $all_count && $smarty.foreach.fav.last) && !($page_max==0 && $smarty.foreach.fav.last)}-->
										<form name="form_down<!--{$row.product_id|escape}-->" action="favorite.php" method="post">
										<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
										<input type="hidden" name="act" value="down" />
										<input type="hidden" name="pid" value="<!--{$row.product_id|escape}-->" />
										<!--<input type="submit" value="↓" />-->
										<a href="javascript:void(0);" onclick="document.form_down<!--{$row.product_id|escape}-->.submit();" style="color:#5E4D34"><img src="/img/mypage/ic_dn.png" alt="下へ" />下へ</a>
										</form>
									<!--{/if}-->
								</td>
							</tr>
							<!--{/if}-->
							</table>
						</div>
					</div>
				</div>
				<!--{/foreach}-->

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
				お気に入りはありません。
				</div>
			<!--{/if}-->
		</div>
	</div>
</div>
