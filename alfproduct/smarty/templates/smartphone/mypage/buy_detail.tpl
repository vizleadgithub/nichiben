<!--{include file='mypage/side_menu.tpl'}-->

<div style="float:right;width:730px;border: none;font-size:15px;margin-bottom:10px;">
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/" style="color:#5E4D34">TOP</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/" style="color:#5E4D34">マイページ</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><a href="/mypage/buy_list.php" style="color:#5E4D34">購入履歴</a></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;"><img src="/img/pankuzu.png" style="padding-top: 2px;"></div>
	<div style="float:left;margin-right:2px;color:#5E4D34;">購入履歴詳細</div>
</div>

<div style="float:right;width:730px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:710px;height:36px;background-image: url( /img/mypage/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#F7982A;font-weight: bold;padding-left: 10px;">購入履歴</span>
	</div>

	<div style="float:left;width:680px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:650px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			このページはあなたの購入履歴の内容を表示しています。<br />
			領収書は「発行」ボタンをクリックして下さい。<br />
			また、領収書は１回の決済につき一度に限り発行が可能ですので紛失等にご注意下さい。


			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">注文日</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><!--{$arr_buy.order_date|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">購入手続き完了日</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><!--{if $arr_buy.payment_status==2}--><!--{$arr_buy.payment_date|escape}--><!--{else}-->-<!--{/if}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">決済内容</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><!--{$arr_buy.disp_payment_type|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">決済状況</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;"><!--{$arr_buy.disp_payment_status|escape}--></td>
				</tr>
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:left;vertical-align:middle;width:150px;">領収書発行</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:left;vertical-align:middle;">
					<!--{if $arr_buy.receipt_flg==1}-->
						<img src="/img/mypage/issue_btn_comp.png" alt="発行済" />
					<!--{else}-->
						<!--{if $arr_buy.payment_type != 99}-->
							<!--{if $arr_buy.payment_status==2}-->
								<script type="text/javascript">
									function non_download() {
										var atena = document.getElementById("atena").value;
										if (confirm("宛名は、以下のように発行されます。\n\n" + atena + "\n<!--{$smarty.session.user.name}--> 様\n\n発行しても宜しいですか？")){
											document.getElementById('download_btn').disabled = true;
											window.document.downloadForm.submit();
											document.getElementById('download_div').innerHTML = '<img src="/img/mypage/issue_btn_comp.png" alt="発行済" />';
										}
									}
								</script>
								<div id="download_div">
									<form name="downloadForm" action="receipt_download.php?oid=<!--{$arr_buy.order_id|escape}-->" method="post">
									宛名：<input type="text" id="atena" name="atena" size="28" />
									<a id="download_btn" href="javascript: void(0);" onclick="non_download();" ><img src="/img/mypage/issue_btn.png" style="vertical-align:middle;"></a>
									</form>
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
			</table>

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;width:70px;">入金確認</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;width:100px;">商品種別</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">商品名</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;width:100px;">小計(税込)</th>
				</tr>


				<!--{foreach from=$arr_buy_detail item="row"}-->
				<!--{cycle values="0,1" assign="cycle_bg"}-->
				<tr style="<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{else}--><!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}--><!--{/if}-->">
					<td style="<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{else}--><!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}--><!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$row.disp_payment_status|escape}--></td>
					<td style="<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{else}--><!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}--><!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{$row.disp_product_type_add|escape}--></td>
					<td style="<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{else}--><!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}--><!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:left;"><!--{if $row.del_flg==="0"}--><!--{if $row.link=="1"}--><a href="/product/detail.php?pid=<!--{$row.product_id|escape}-->"><!--{/if}--><!--{if $row.product_name_TOD!=''}--><!--{$row.product_name_TOD|escape}--><!--{elseif $row.product_name_TP!=''}--><!--{$row.product_name_TP|escape}--><!--{/if}--><!--{if $row.link=="1"}--></a><!--{/if}--><!--{else}--><!--{if $row.product_name_TOD!=''}--><!--{$row.product_name_TOD|escape}--><!--{elseif $row.product_name_TP!=''}--><!--{$row.product_name_TP|escape}--><!--{/if}--><br />※掲載終了しました。<!--{/if}--></td>
					<td style="<!--{if $row.payment_status=="9"}-->background-color:#363636;color:#FEFEFE;<!--{else}--><!--{if $cycle_bg=="1"}-->background-color: #F5F5F5;<!--{else}-->background-color: #FFFFFF;<!--{/if}--><!--{/if}-->border:solid 1px #AB9983;padding:5px;text-align:right;"><!--{$row.price|escape|number_format}-->円</td>
				</tr>
				<!--{/foreach}-->
				<tr>
					<th colspan="3" style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:right;">合計</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:right;"><!--{$total_price|escape|number_format}-->円</td>
				</tr>
			</table>
		</div>
	</div>
</div>
