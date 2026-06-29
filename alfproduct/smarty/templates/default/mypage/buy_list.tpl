<!--{include file='mypage/side_menu.tpl'}-->

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">購入履歴</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">購入履歴</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたの購入履歴を表示しています。領収書は決済ごとに発行が可能です。<br />
			領収書は「発行」ボタンをクリックして下さい。<br />
			また、領収書は１回の決済につき一度に限り発行が可能ですので紛失等にご注意下さい。<br />
			ブラウザの「戻る」や「閉じる」の操作を行うと，領収証が発行済みとなる場合がありますので御注意ください。
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

			<table style="width:100%;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:130px;">購入手続き完了日</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;">決済内容</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">合計(税込)</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:50px;">詳細</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;vertical-align:middle;width:80px;">領収書</th>
				</tr>
				<!--{foreach from=$arr_list item=val}-->
				<!--{cycle values="0,1" assign="cycle_bg"}-->
				<tr style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->">
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><!--{if $val.payment_status == 2}--><!--{$val.payment_date|escape}--><!--{else}-->-<!--{/if}--></td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><!--{if $val.product_name_TOD!=''}--><!--{$val.product_name_TOD|escape}--><!--{elseif $val.product_name_TP!=''}--><!--{$val.product_name_TP|escape}--><!--{else}-->掲載終了しました。<!--{/if}--></td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:right;vertical-align:middle;"><!--{$val.price|escape|number_format}-->円</td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;"><a href="/mypage/buy_detail.php?oid=<!--{$val.order_id|escape}-->" style="color:#796A57;">詳細</a></td>
					<td style="<!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:center;vertical-align:middle;">
						<!--{if $val.receipt_flg == 1}-->
							<img src="/img/mypage/issue_btn_comp.png">
						<!--{else}-->
							<!--{if $val.payment_type != 99}-->
								<!--{if $val.payment_status == 2}-->
									<script type="text/javascript">
										function non_download<!--{$val.order_id|escape}-->() {
											if (confirm("宛名を指定して発行したい場合は、詳細ページから発行してください。\n\nこのページから発行した場合、宛名は「<!--{$smarty.session.user.name|escape:'javascript'}--> 様」となります。\n\nこのまま発行しても宜しいですか？")){
												document.getElementById('download_btn<!--{$val.order_id|escape}-->').disabled = true;
												document.getElementById('download_div<!--{$val.order_id|escape}-->').innerHTML = '<img src="/img/mypage/issue_btn_comp.png">';
												window.open("receipt_download.php?oid=<!--{$val.order_id|escape}-->", "", "width=10,height=10");
											}
										}
									</script>
									<div id="download_div<!--{$val.order_id|escape}-->">
										<a id="download_btn<!--{$val.order_id|escape}-->" href="javascript: void(0);" onclick="non_download<!--{$val.order_id|escape}-->();" ><img src="/img/mypage/issue_btn.png"></a>
									</div>
								<!--{else}-->
									未発行
								<!--{/if}-->
							<!--{else}-->
							-
							<!--{/if}-->
						<!--{/if}-->
					</td>
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
				購入履歴はありません。
				</div>
			<!--{/if}-->
		</div>
	</div>
</div>
