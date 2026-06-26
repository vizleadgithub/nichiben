<script type="text/javascript">
	<!--{if !$is_sp}-->
	<!--{else}-->
		<!--{* スクロール時にプレイヤーが起動しないようにするのに使用 *}-->
		var touchMoveFlag = false;
	<!--{/if}-->
	function playerFormSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		<!--{if !$is_sp}-->
			var w = window.open("about:blank","playerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
			setTimeout(function(){
				w.onLoad = playerOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}, 1000);
		<!--{else}-->
			if (!touchMoveFlag){
				playerOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}
			touchMoveFlag = false;
		<!--{/if}-->

	}
	function playerOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		document.getElementById("hid_vid").value = vid;
		document.getElementById("hid_vid2").value = vid2;
		document.getElementById("hid_codec").value = codec;
		document.getElementById("hid_ftn").value = ftn;
		document.getElementById("hid_ccno").value = ccno;
		document.getElementById("hid_view_btn").value = view_btn;
		<!--{if !$is_sp}-->
			document.playerForm.target = "playerDisp";
		<!--{/if}-->
		document.playerForm.method = "post";
		<!--{if !$is_sp}-->
			document.playerForm.action = "/player/index.php?term=pc";
		<!--{else}-->
			document.playerForm.action = "/player/index.php?term=sp";
		<!--{/if}-->
		document.playerForm.submit();
	}
	function playerEthicFormSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		<!--{if !$is_sp}-->
			var w = window.open("about:blank","playerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
			setTimeout(function(){
				w.onLoad = playerEthicOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}, 1000);
		<!--{else}-->
			if (!touchMoveFlag){
				playerEthicOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec);
			}
			touchMoveFlag = false;
		<!--{/if}-->
	}
	function playerEthicOpenWindowSubmit(vid,ftn,ccno,view_btn,vid2,codec){
		document.getElementById("hid_vid").value = vid;
		document.getElementById("hid_vid2").value = vid2;
		document.getElementById("hid_codec").value = codec;
		document.getElementById("hid_ftn").value = ftn;
		document.getElementById("hid_ccno").value = ccno;
		document.getElementById("hid_view_btn").value = view_btn;
		<!--{if !$is_sp}-->
			document.playerForm.target = "playerDisp";
		<!--{else}-->
		<!--{/if}-->
		document.playerForm.method = "post";
		<!--{if !$is_sp}-->
			document.playerForm.action = "/player/player_ethic.php?term=pc";
		<!--{else}-->
			document.playerForm.action = "/player/player_ethic.php?term=sp";
		<!--{/if}-->
		document.playerForm.submit();
	}

	function downloadFormSubmit(cdname){
		document.getElementById("hid_cdname").value = cdname;
		document.downloadForm.method = "post";
		document.downloadForm.action = "download.php?PHPSESSID=<!--{php}-->echo session_id();<!--{/php}-->";
		document.downloadForm.submit();
	}
	function downloadPopup(){
		<!--{if !$is_sp}-->
			window.open("about:blank","documentDisp","width=675,height=800,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
			document.downloadForm.target = "documentDisp";
			document.downloadForm.method = "post";
			document.downloadForm.action = "document.php?PHPSESSID=<!--{php}-->echo session_id();<!--{/php}-->";
			document.downloadForm.submit();
		<!--{else}-->
			if (!touchMoveFlag){
				document.downloadForm.method = "post";
				document.downloadForm.action = "document.php?PHPSESSID=<!--{php}-->echo session_id();<!--{/php}-->";
				document.downloadForm.submit();
			}
			touchMoveFlag = false;
		<!--{/if}-->
	}
</script>
<style type="text/css">
a.test_btn{
	display: block;
	text-align: center;
	vertical-align: middle;
	background: #0097dd;
	font-size: 16px;
	line-height: 40px;
	height: 40px;
	color: #ffffff;
	text-decoration: none;
	border-radius: 8px;
	width:230px;
}
a.test_btn_none{
	display: block;
	text-align: center;
	vertical-align: middle;
	background: #f2f2f2;
	font-size: 16px;
	line-height: 40px;
	height: 40px;
	color: #bebebe;
	text-decoration: none;
	border-radius: 8px;
	width:230px;
}
</style>

<!--{*$product_list|var_dump*}-->
<!--
[<!--{$product_list.contents_baisoku_flg1}-->]
[<!--{$product_list.contents_baisoku_flg2}-->]
[<!--{$product_list.contents_baisoku_flg3}-->]
-->
<!--{$pankuzu}-->

<!--{if $product_list.product_type_add == 1 && $buy_flg}-->
<!--<input type="button" value="　関連講座　" ontouchmove="touchMoveFlag=true;" onclick="window.location.href='#RelatedCourseBlock';" style="float:right;" />-->
<!--{/if}-->

<div style="clear:both;margin:0;" class="detail_h2_1">
	<h2><!--{$product_list.product_name|escape}-->詳細</h2>
</div>
<!--{*
<div style="clear:both;">
	<!--{$product_list.free_html_area1}-->
</div>
*}-->
<!--{* eラーニング(既存カスタマイズ) *}-->
<!--{if $product_list.product_type_add == 1}-->
	<!--{* ------------------------------------未購入orパスポート無------------------------------------ *}-->
	<!--{if $buy_flg}-->
		<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
			<div style="text-align:right;padding-top:10px;">
				<!--{if $favorite_flg}-->
				<form name="favoriteForm" action="<!--{*https://*}--><!--{*php}-->echo $_SERVER['SERVER_NAME'];<!--{/php*}-->/mypage/favorite.php" method="post">
				<input type="hidden" name="act" value="regist" />
				<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
					<input type="image" src="/img/list/favorite_btn.png" /><br />
				</form>
				<!--{/if}-->
				<!--{if $favorite_icon_flg}-->
				<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り商品" />
				<!--{/if}-->
			</div>

			<div class="detail_title" style="font-size:18px;color:#22730e;"><!--{$product_list.product_name|escape}--></div>
<!--{*
			<!--{foreach from=$arr_exam2 key=exam2_i item=exam2_row}-->
				<div style="text-align:left;">
					<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
					　（レビュー　<!--{$exam2_row.student|@count}-->件）
				</div>
			<!--{/foreach}-->
*}-->
			<div style="text-align:left;">
				<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
				　（レビュー　<!--{$arr_exam2|@count}-->件）
			</div>

			<div style="text-align:right;">
				<!--{foreach name=icon_loop from=$product_list.icon_img item=icon}-->
					<!--{assign var=icon_cnt value=$smarty.foreach.icon_loop.iteration}-->
					<!--{if $icon.src != ''}-->
						<img src="/img/<!--{$icon.src|escape}-->" alt="<!--{$icon.alt|escape}-->" />
						<!--{if $icon_cnt == 2}--><br /><!--{/if}-->
					<!--{/if}-->
				<!--{/foreach}-->
			</div>

			<center>
				<table style="background-color:#F5F8EF;width:680px;">
					<!--{if $product_list.teacher != ''}-->
						<tr style=" border: 2px #FFFFFF solid;">
							<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
							<td style="padding: 3px 10px;text-align:left;">
								<!--{$product_list.teacher|escape}-->
								<!--{if $product_list.teacher_student_id==$user_id}-->
									<!--{if $student_make_complete==0}-->
										<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
										<script type="text/javascript">
											function makingHistory(){
												var result = window.confirm('受講履歴を作成します');
												if(result){
													$.ajax({
														type: 'POST',
														url: '/product/complete.php?pid=<!--{$pid}-->',
														dataType: 'html',
														success: function(data) {
															location.reload(true);
														},
														error:function() {
															//alert('通信エラーが発生しました。');
															location.reload(true);
														}
													});
												}
											}
										</script>
									<!--{else}-->
										<br><div class="btn_graywhite">受講完了</div>
									<!--{/if}-->
								<!--{/if}-->
							</td>
						</tr>
					<!--{/if}-->
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">掲載期間</th><td style="padding: 3px 10px;text-align:left;">
						<!--{if $product_list.start_date!='' && $product_list.start_date!='0000-00-00 00:00:00' && $product_list.end_date!='' && $product_list.end_date!='0000-00-00 00:00:00'}-->
							<!--{$product_list.disp_start_date|escape}-->～<!--{$product_list.disp_end_date|escape}-->
						<!--{elseif $product_list.start_date!='' && $product_list.start_date!='0000-00-00 00:00:00'}-->
							<!--{$product_list.disp_start_date|escape}-->～
						<!--{elseif $product_list.end_date!='' && $product_list.end_date!='0000-00-00 00:00:00'}-->
							～<!--{$product_list.disp_end_date|escape}-->
						<!--{else}-->
							未定
						<!--{/if}-->
					</td></tr>
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">総時間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.all_play_time|escape}--></td></tr>
					<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">価格</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.price_intax==0}-->無料<!--{else}--><!--{$product_list.price_intax|escape|number_format}-->円(税込)<!--{/if}--></td></tr>-->
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">商品説明</th><td style="padding: 3px 10px;text-align:left;word-break:break-all;"><!--{$product_list.memo|nl2br}--></td></tr>
				</table>
			</center>

			<!--
			<!--{if $product_list.hantei_ari}-->
				<!--{if array_search('1', $product_list.arr_product_disp_warning_word)!==false}-->
					<div style="text-align:left;padding-top:20px;color:red;">
						※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
						※各パートのテストに全問正解しないと，次のパートに進むことができません。<br>
						※テストを全て受けて全問正解しないと，受講完了にはなりません。<br>
					</div>
				<!--{/if}-->
			<!--{else}-->
				<!--{if array_search('2', $product_list.arr_product_disp_warning_word)!==false}-->
					<div style="text-align:left;padding-top:20px;color:red;">
						※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
						※テストを全て受けないと，受講完了にはなりません。<br>
					</div>
				<!--{/if}-->
			<!--{/if}-->
			-->

			<!--{if
			($product_list.contents_contents1so!='') 
			|| ($product_list.contents_contents2so!='') 
			|| ($product_list.contents_contents3so!='') 
			|| ($product_list.contents_contents4so!='') 
			|| ($product_list.contents_contents5so!='') 
			|| ($product_list.contents_contents6so!='') 
			|| ($product_list.contents_contents7so!='') 
			|| ($product_list.contents_contents8so!='') 
			|| ($product_list.contents_contents9so!='') 
			|| ($product_list.contents_contents10so!='') 
			|| ($product_list.contents_contents11so!='') 
			|| ($product_list.contents_contents12so!='') 
			|| ($product_list.contents_contents13so!='') 
			|| ($product_list.contents_contents14so!='') 
			|| ($product_list.contents_contents15so!='') 
			|| ($product_list.contents_contents16so!='') 
			|| ($product_list.contents_contents17so!='') 
			|| ($product_list.contents_contents18so!='') 
			|| ($product_list.contents_contents19so!='') 
			|| ($product_list.contents_contents20so!='') 
			|| ($product_list.contents_contents21so!='') 
			|| ($product_list.contents_contents22so!='') 
			|| ($product_list.contents_contents23so!='') 
			|| ($product_list.contents_contents24so!='') 
			|| ($product_list.contents_contents25so!='') 
			}-->
				<div style="text-align:left;padding-top:20px;color:#000000;">
					※本講座は，音声のみを聴取することが可能です。ただし，動画を途中まで再生した後に，続きを音声のみ再生すること，及びその逆はできませんので，ご注意ください。また，講座の受講率及び受講完了確認は，動画での視聴を基に記録されます。
					<span style="color:red;">音声のみを最後まで聴取したとしても，講座の受講完了とは記録されませんので，ご注意ください。</span>
				</div>
			<!--{/if}-->
		</div>

		<div style="clear:both;text-align:center;padding:20px;">
			<!--{if $buy_wait_flg}-->
				<img src="/img/lecture/buy_wait.png" alt="購入手続き中" /><br />
			<!--{else}-->
				<form name="buyForm" action="/settlement/index.php" method="post" style="display:inline;">
				<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
				<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
				<input type="hidden" name="hid_product_type_add" value="<!--{$product_list.product_type_add|escape}-->" />
					<input type="image" src="/img/button/buy_process_btn.jpg" alt="買い物かごに入れる" /><br />
				</form>
			<!--{/if}-->
		</div>

	<!--{* ------------------------------------購入済みorパスポート有------------------------------------ *}-->
	<!--{else}-->
		<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
			<div style="text-align:right;padding-top:10px;">
				<!--{if $favorite_flg}-->
				<form name="favoriteForm" action="<!--{*https://*}--><!--{*php}-->echo $_SERVER['SERVER_NAME'];<!--{/php*}-->/mypage/favorite.php" method="post">
				<input type="hidden" name="act" value="regist" />
				<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
					<input type="image" src="/img/list/favorite_btn.png" /><br />
				</form>
				<!--{/if}-->
				<!--{if $favorite_icon_flg}-->
				<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り商品" />
				<!--{/if}-->
			</div>

			<div class="detail_title" style="font-size:18px;color:#22730e;"><!--{$product_list.product_name|escape}--></div>

<!--{*
			<!--{foreach from=$arr_exam2 key=exam2_i item=exam2_row}-->
				<div style="text-align:left;">
					<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
					　（レビュー　<!--{$exam2_row.student|@count}-->件）
				</div>
			<!--{/foreach}-->
*}-->
			<div style="text-align:left;">
				<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
				　（レビュー　<!--{$arr_exam2|@count}-->件）
			</div>

			<div style="text-align:right;">
				<!--{foreach name=icon_loop from=$product_list.icon_img item=icon}-->
				<!--{assign var=icon_cnt value=$smarty.foreach.icon_loop.iteration}-->
					<!--{if $icon.src != ''}-->
						<img src="/img/<!--{$icon.src|escape}-->" alt="<!--{$icon.alt|escape}-->" />
						<!--{if $icon_cnt == 2}--><br /><!--{/if}-->
					<!--{/if}-->
				<!--{/foreach}-->
			</div>
				
			<center>
				<table style="background-color:#F5F8EF;width:680px;">
					<!--{if $product_list.teacher != ''}-->
						<tr style=" border: 2px #FFFFFF solid;">
							<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
							<td style="padding: 3px 10px;text-align:left;">
								<!--{$product_list.teacher|escape}-->
								<!--{if $product_list.teacher_student_id==$user_id}-->
									<!--{if $student_make_complete==0}-->
										<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
										<script type="text/javascript">
											function makingHistory(){
												var result = window.confirm('受講履歴を作成します');
												if(result){
													$.ajax({
														type: 'POST',
														url: '/product/complete.php?pid=<!--{$pid}-->',
														dataType: 'html',
														success: function(data) {
															location.reload(true);
														},
														error:function() {
															//alert('通信エラーが発生しました。');
															location.reload(true);
														}
													});
												}
											}
										</script>
									<!--{else}-->
										<br><div class="btn_graywhite">受講完了</div>
									<!--{/if}-->
								<!--{/if}-->
							</td>
						</tr>
					<!--{/if}-->
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">掲載期間</th><td style="padding: 3px 10px;text-align:left;">
						<!--{if $product_list.start_date!='' && $product_list.start_date!='0000-00-00 00:00:00' && $product_list.end_date!='' && $product_list.end_date!='0000-00-00 00:00:00'}-->
							<!--{$product_list.disp_start_date|escape}-->～<!--{$product_list.disp_end_date|escape}-->
						<!--{elseif $product_list.start_date!='' && $product_list.start_date!='0000-00-00 00:00:00'}-->
							<!--{$product_list.disp_start_date|escape}-->～
						<!--{elseif $product_list.end_date!='' && $product_list.end_date!='0000-00-00 00:00:00'}-->
							～<!--{$product_list.disp_end_date|escape}-->
						<!--{else}-->
							未定
						<!--{/if}-->
					</td></tr>
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">総時間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.all_play_time|escape}--></td></tr>
					<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">価格</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.price_intax==0}-->無料<!--{else}--><!--{$product_list.price_intax|escape|number_format}-->円(税込)<!--{/if}--></td></tr>-->
					<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">商品説明</th><td style="padding: 3px 10px;text-align:left;word-break:break-all;"><!--{$product_list.memo|nl2br}--></td></tr>
					<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">新再生プレイヤー確認用URL</th><td style="padding: 3px 10px;text-align:left;word-break:break-all;"><a href="http://nichibenren-stg.alfcloud.com/product/detail_tkp.php?pid=24020" target="_blank" rel="noopener noreferrer">http://nichibenren-stg.alfcloud.com/product/detail_tkp.php?pid=24020</a></td></tr>-->
				</table>
			</center>

			<!--{if $product_list.hantei_ari}-->
				<!--{if array_search('1', $product_list.arr_product_disp_warning_word)!==false}-->
					<div style="text-align:left;padding-top:20px;color:red;">
						※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
						※各パートのテストに全問正解しないと，次のパートに進むことができません。<br>
						※テストを全て受けて全問正解しないと，受講完了にはなりません。<br>
					</div>
				<!--{/if}-->
			<!--{else}-->
				<!--{if array_search('2', $product_list.arr_product_disp_warning_word)!==false}-->
					<div style="text-align:left;padding-top:20px;color:red;">
						※本講座は，テスト付き研修です。各パートを視聴した後，そのパートのテストを受けて下さい。<br>
						※テストを全て受けないと，受講完了にはなりません。<br>
					</div>
				<!--{/if}-->
			<!--{/if}-->

			<!--{if
			($product_list.contents_contents1so!='') 
			|| ($product_list.contents_contents2so!='') 
			|| ($product_list.contents_contents3so!='') 
			|| ($product_list.contents_contents4so!='') 
			|| ($product_list.contents_contents5so!='') 
			|| ($product_list.contents_contents6so!='') 
			|| ($product_list.contents_contents7so!='') 
			|| ($product_list.contents_contents8so!='') 
			|| ($product_list.contents_contents9so!='') 
			|| ($product_list.contents_contents10so!='') 
			|| ($product_list.contents_contents11so!='') 
			|| ($product_list.contents_contents12so!='') 
			|| ($product_list.contents_contents13so!='') 
			|| ($product_list.contents_contents14so!='') 
			|| ($product_list.contents_contents15so!='') 
			|| ($product_list.contents_contents16so!='') 
			|| ($product_list.contents_contents17so!='') 
			|| ($product_list.contents_contents18so!='') 
			|| ($product_list.contents_contents19so!='') 
			|| ($product_list.contents_contents20so!='') 
			|| ($product_list.contents_contents21so!='') 
			|| ($product_list.contents_contents22so!='') 
			|| ($product_list.contents_contents23so!='') 
			|| ($product_list.contents_contents24so!='') 
			|| ($product_list.contents_contents25so!='') 
			}-->
				<div style="text-align:left;padding-top:20px;color:#000000;">
					※本講座は，音声のみを聴取することが可能です。ただし，動画を途中まで再生した後に，続きを音声のみ再生すること，及びその逆はできませんので，ご注意ください。また，講座の受講率及び受講完了確認は，動画での視聴を基に記録されます。
					<span style="color:red;">音声のみを最後まで聴取したとしても，講座の受講完了とは記録されませんので，ご注意ください。</span>
				</div>
			<!--{/if}-->

			<br style="clear:both;" /><div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>

			<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修資料</div>

			<div style="text-align:center;padding:20px 0;">
				<input type="image" src="/img/lecture/dl_btn.png" ontouchmove="touchMoveFlag=true;" onclick="downloadPopup();">
			</div>
				
			<!--{if array_search('3', $product_list.arr_product_disp_warning_word)!==false}-->
				<div style="text-align:left;padding-top:20px;color:red;">&nbsp;<!-- 注意文言（イ） --></div>
			<!--{/if}-->
				
			<div>
				<!--{if
				   ($product_list.contents_contents1!='' && $product_list.contents_view_flg1) 
				|| ($product_list.contents_contents2!='' && $product_list.contents_view_flg2) 
				|| ($product_list.contents_contents3!='' && $product_list.contents_view_flg3) 
				|| ($product_list.contents_contents4!='' && $product_list.contents_view_flg4) 
				|| ($product_list.contents_contents5!='' && $product_list.contents_view_flg5) 
				|| ($product_list.contents_contents6!='' && $product_list.contents_view_flg6) 
				|| ($product_list.contents_contents7!='' && $product_list.contents_view_flg7) 
				|| ($product_list.contents_contents8!='' && $product_list.contents_view_flg8) 
				|| ($product_list.contents_contents9!='' && $product_list.contents_view_flg9) 
				|| ($product_list.contents_contents10!='' && $product_list.contents_view_flg10) 
				|| ($product_list.contents_contents11!='' && $product_list.contents_view_flg11) 
				|| ($product_list.contents_contents12!='' && $product_list.contents_view_flg12) 
				|| ($product_list.contents_contents13!='' && $product_list.contents_view_flg13) 
				|| ($product_list.contents_contents14!='' && $product_list.contents_view_flg14) 
				|| ($product_list.contents_contents15!='' && $product_list.contents_view_flg15) 
				|| ($product_list.contents_contents16!='' && $product_list.contents_view_flg16) 
				|| ($product_list.contents_contents17!='' && $product_list.contents_view_flg17) 
				|| ($product_list.contents_contents18!='' && $product_list.contents_view_flg18) 
				|| ($product_list.contents_contents19!='' && $product_list.contents_view_flg19) 
				|| ($product_list.contents_contents20!='' && $product_list.contents_view_flg20) 
				|| ($product_list.contents_contents21!='' && $product_list.contents_view_flg21) 
				|| ($product_list.contents_contents22!='' && $product_list.contents_view_flg22) 
				|| ($product_list.contents_contents23!='' && $product_list.contents_view_flg23) 
				|| ($product_list.contents_contents24!='' && $product_list.contents_view_flg24) 
				|| ($product_list.contents_contents25!='' && $product_list.contents_view_flg25) 
				|| ($product_list.contents_contents1so!='' && $product_list.contents_view_flg1so) 
				|| ($product_list.contents_contents2so!='' && $product_list.contents_view_flg2so) 
				|| ($product_list.contents_contents3so!='' && $product_list.contents_view_flg3so) 
				|| ($product_list.contents_contents4so!='' && $product_list.contents_view_flg4so) 
				|| ($product_list.contents_contents5so!='' && $product_list.contents_view_flg5so) 
				|| ($product_list.contents_contents6so!='' && $product_list.contents_view_flg6so) 
				|| ($product_list.contents_contents7so!='' && $product_list.contents_view_flg7so) 
				|| ($product_list.contents_contents8so!='' && $product_list.contents_view_flg8so) 
				|| ($product_list.contents_contents9so!='' && $product_list.contents_view_flg9so) 
				|| ($product_list.contents_contents10so!='' && $product_list.contents_view_flg10so) 
				|| ($product_list.contents_contents11so!='' && $product_list.contents_view_flg11so) 
				|| ($product_list.contents_contents12so!='' && $product_list.contents_view_flg12so) 
				|| ($product_list.contents_contents13so!='' && $product_list.contents_view_flg13so) 
				|| ($product_list.contents_contents14so!='' && $product_list.contents_view_flg14so) 
				|| ($product_list.contents_contents15so!='' && $product_list.contents_view_flg15so) 
				|| ($product_list.contents_contents16so!='' && $product_list.contents_view_flg16so) 
				|| ($product_list.contents_contents17so!='' && $product_list.contents_view_flg17so) 
				|| ($product_list.contents_contents18so!='' && $product_list.contents_view_flg18so) 
				|| ($product_list.contents_contents19so!='' && $product_list.contents_view_flg19so) 
				|| ($product_list.contents_contents20so!='' && $product_list.contents_view_flg20so) 
				|| ($product_list.contents_contents21so!='' && $product_list.contents_view_flg21so) 
				|| ($product_list.contents_contents22so!='' && $product_list.contents_view_flg22so) 
				|| ($product_list.contents_contents23so!='' && $product_list.contents_view_flg23so) 
				|| ($product_list.contents_contents24so!='' && $product_list.contents_view_flg24so) 
				|| ($product_list.contents_contents25so!='' && $product_list.contents_view_flg25so) 
				}-->
					<div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>
					<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修パート一覧</div>
					<div style="width:100%;border-top:solid 1px #000000;margin:5px 0;"></div>

					<!--{section name=contents_contents loop=$section_max_contents start=1}-->
						<!--{assign var=ccno value=$smarty.section.contents_contents.index}-->

						<!--{assign var=contents_contents_key value="contents_contents"|cat:$ccno}-->
						<!--{assign var=contents_contents_name_key value="contents_contents"|cat:$ccno|cat:"_name"}-->

						<!--{assign var=contents_contents_so_key value="contents_contents"|cat:$ccno|cat:"so"}-->
						<!--{assign var=contents_contents_so_name_key value="contents_contents"|cat:$ccno|cat:"so_name"}-->

						<!--{assign var=contents_thumbnail_key value="contents_thumbnail"|cat:$ccno}-->
						<!--{assign var=contents_teacher_key value="contents_teacher"|cat:$ccno}-->
						<!--{assign var=contents_start_date_key value="contents_start_date"|cat:$ccno}-->
						<!--{assign var=contents_end_date_key value="contents_end_date"|cat:$ccno}-->
						<!--{assign var=contents_memo_key value="contents_memo"|cat:$ccno}-->
						<!--{assign var=contents_view_flg_key value="contents_view_flg"|cat:$ccno}-->
						<!--{assign var=contents_baisoku_flg_key value="contents_baisoku_flg"|cat:$ccno}-->
						<!--{assign var=contents_view_flg_so_key value="contents_view_flg"|cat:$ccno|cat:"so"}-->
						<!--{assign var=contents_free_time_key value="contents_free_time"|cat:$ccno}-->
						<!--{assign var=video_thumbnail_key value="video_thumbnail"|cat:$ccno}-->
						<!--{assign var=video_duration_key value="video_duration"|cat:$ccno}-->
						<!--{assign var=video_reading_key value="video_reading"|cat:$ccno}-->
						<!--{assign var=video_view_flg_key value="video_view_flg"|cat:$ccno}-->
						<!--{assign var=video_view_flg_so_key value="video_view_flg"|cat:$ccno|cat:"so"}-->
						<!--{assign var=video_complete_flg_key value="video_complete_flg"|cat:$ccno}-->
						<!--{assign var=exam_id_test_key value="exam_id_test"|cat:$ccno}-->
						<!--{assign var=exam_id_question_key value="exam_id_question"|cat:$ccno}-->
						<!--{assign var=exam_test_all_answered_key value="exam_test_all_answered"|cat:$ccno}-->
						<!--{assign var=exam_question_all_answered_key value="exam_question_all_answered"|cat:$ccno}-->
						<!--{assign var=exam_test_passing_flg_key value="exam_test_passing_flg"|cat:$ccno}-->
						<!--{assign var=exam_question_passing_flg_key value="exam_question_passing_flg"|cat:$ccno}-->
						<!--{assign var=display_format_test_key value="display_format_test"|cat:$ccno}-->
						<!--{assign var=display_format_question_key value="display_format_question"|cat:$ccno}-->
						<!--{assign var=public_flag_test_key value="public_flag_test"|cat:$ccno}-->
						<!--{assign var=public_flag_question_key value="public_flag_question"|cat:$ccno}-->
						<!--{assign var=resubmit_flag_test_key value="resubmit_flag_test"|cat:$ccno}-->
						<!--{assign var=resubmit_flag_question_key value="resubmit_flag_question"|cat:$ccno}-->
						<!--{assign var=submit_possible_flg_test_key value="submit_possible_flg_test"|cat:$ccno}-->
						<!--{assign var=submit_possible_flg_question_key value="submit_possible_flg_question"|cat:$ccno}-->
						<!--{assign var=btn_type_key value="btn_type"|cat:$ccno}-->
						<!--{assign var=disp_warning_word_key value="disp_warning_word"|cat:$ccno}-->
						
						<!--{* 設問付きeラーニングの場合 *}-->
						<!--{if $product_list.product_kind_flg==3}-->
							<!--{assign var=ccno_prev value=$smarty.section.contents_contents.index-1}-->
							<!--{assign var=video_complete_flg_key_prev value="video_complete_flg"|cat:$ccno_prev}-->
							<!--{assign var=exam_id_test_key_prev value="exam_id_test"|cat:$ccno_prev}-->
							<!--{assign var=exam_id_question_key_prev value="exam_id_question"|cat:$ccno_prev}-->
							<!--{assign var=exam_test_all_answered_key_prev value="exam_test_all_answered"|cat:$ccno_prev}-->
							<!--{assign var=exam_question_all_answered_key_prev value="exam_question_all_answered"|cat:$ccno_prev}-->
							<!--{assign var=exam_test_passing_flg_key_prev value="exam_test_passing_flg"|cat:$ccno_prev}-->
							<!--{assign var=exam_question_passing_flg_key_prev value="exam_question_passing_flg"|cat:$ccno_prev}-->
							
							<!--{if ($product_list.$contents_contents_key!='' && $product_list.$contents_view_flg_key) || ($product_list.$contents_contents_so_key!='' && $product_list.$contents_view_flg_so_key)}-->
								 <!--{if $ccno==1}-->
								 	<center>
										<table style="background-color:#F5F8EF;width:680px;">
											<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><!--{$ccno|escape}-->、<!--{$product_list.$contents_contents_name_key|escape}--></td></tr>
											<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.$video_duration_key|escape}--></td></tr>
											<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.$video_view_flg_key}--><!--{$product_list.$video_reading_key|escape}--><!--{else}-->未視聴<!--{/if}--></td></tr>
										</table>
									</center>
									
									<center style="float:left;display: inline-block;width: 400px;">
										<div class="detail_btn" style="text-align: left;">
											<!--[ TYPE1 ]-->
											<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
											<!-- PC -->
											<!--{if !$is_sp}-->
												<!--{if $product_list.$contents_contents_key>0}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_key}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<!--{else}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<!--{/if}-->
												<!--{/if}-->
												<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
													<br>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_so_key}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{else}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" alt="" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{/if}-->
												<!--{/if}-->
											<!--{/if}-->
											<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
											<!-- SP -->
											<!--{if $is_sp}-->
												<!--{if $product_list.$contents_contents_key>0}-->
													<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_key}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<!--{else}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
															<div class="btn_plyer_text">続きから再生</div>
														</div>
													<!--{/if}-->
												<!--{/if}-->
												<!--{if $product_list.$contents_baisoku_flg_key}-->
													<!-- x13 -->
													<!--
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
													-->

													<!-- x15 -->
													<!--
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
													-->

													<!-- x20 -->
													<!--
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
														<div class="btn_plyer_text">始めから再生</div>
													</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
													-->
												<!--{/if}-->
												<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
													<br>
													<br>
													<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_so_key}-->
														<!--
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
														-->
													<!--{else}-->
														<!--
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
														-->
													<!--{/if}-->

													<!-- x13 -->
													<!--
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_so_key}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{else}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{/if}-->
													-->

													<!-- x15 -->
													<!--
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_so_key}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{else}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{/if}-->
													-->

													<!-- x20 -->
													<!--
													<br>
													<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
													</div>
													<!--{if $product_list.$video_view_flg_so_key}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{else}-->
														<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
															<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
														</div>
													<!--{/if}-->
													-->
												<!--{/if}-->
											<!--{/if}-->
											<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										</div>

										<!--{if $product_list.$video_complete_flg_key==1}-->
											<!--{* テストとアンケートが両方設定されていた場合は、テストの後にアンケートを結合し、期限等の条件はテストを優先する *}-->
											<!--{if $product_list.$exam_id_test_key>0 && $product_list.$exam_id_question_key>0}-->
												<!--{if $product_list.$public_flag_test_key=='0'}-->
													<div class="detail_btn">
														<!--{if $product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='1'}-->
															<!--{* 全画面一括採点形式 *}-->
															<!--{if $product_list.$display_format_test_key==='0'}-->
																<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->">受講済</a>
															<!--{* 一問一答形式（都度採点） *}-->
															<!--{elseif $product_list.$display_format_test_key==='1'}-->
																<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
															<!--{* 一問一答形式（一括採点） *}-->
															<!--{elseif $product_list.$display_format_test_key==='2'}-->
																<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
															<!--{/if}-->
														<!--{else}-->
															<!--{if $product_list.$submit_possible_flg_test_key}-->
																<!--{if !$product_list.$exam_test_all_answered_key || ($product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='0' && $product_list.$resubmit_flag_test_key=='1')}-->
																	<!--{* 全画面一括採点形式 *}-->
																	<!--{if $product_list.$display_format_test_key==='0'}-->
																		<!--{if $product_list.$exam_test_all_answered_key}-->
																			<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{else}-->
																			<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{/if}-->
																	<!--{* 一問一答形式（都度採点） *}-->
																	<!--{elseif $product_list.$display_format_test_key==='1'}-->
																		<!--{if $product_list.$exam_test_all_answered_key}-->
																			<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{else}-->
																			<a class="test_btn" href="/exam/index1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{/if}-->
																	<!--{* 一問一答形式（一括採点） *}-->
																	<!--{elseif $product_list.$display_format_test_key==='2'}-->
																		<!--{if $product_list.$exam_test_all_answered_key}-->
																			<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{else}-->
																			<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{/if}-->
																	<!--{/if}-->
																<!--{else}-->
																	<!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}-->
																<!--{/if}-->
															<!--{else}-->
																提出期限外のため、受講不可です
															<!--{/if}-->
														<!--{/if}-->
													</div>
												<!--{/if}-->
											<!--{elseif $product_list.$exam_id_test_key>0}-->
												<!--{if $product_list.$public_flag_test_key=='0'}-->
													<div class="detail_btn">
														<!--{if $product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='1'}-->
															<!--{* 全画面一括採点形式 *}-->
															<!--{if $product_list.$display_format_test_key==='0'}-->
																<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->">受講済</a>
															<!--{* 一問一答形式（都度採点） *}-->
															<!--{elseif $product_list.$display_format_test_key==='1'}-->
																<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1">受講済</a>
															<!--{* 一問一答形式（一括採点） *}-->
															<!--{elseif $product_list.$display_format_test_key==='2'}-->
																<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1">受講済</a>
															<!--{/if}-->
														<!--{else}-->
															<!--{if $product_list.$submit_possible_flg_test_key}-->
																<!--{if !$product_list.$exam_test_all_answered_key || ($product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='0' && $product_list.$resubmit_flag_test_key=='1')}-->
																	<!--{* 全画面一括採点形式 *}-->
																	<!--{if $product_list.$display_format_test_key==='0'}-->
																		<!--{if $product_list.$exam_test_all_answered_key}-->
																			<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{else}-->
																			<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{/if}-->
																	<!--{* 一問一答形式（都度採点） *}-->
																	<!--{elseif $product_list.$display_format_test_key==='1'}-->
																		<!--{if $product_list.$exam_test_all_answered_key}-->
																			<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{else}-->
																			<a class="test_btn" href="/exam/index1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{/if}-->
																	<!--{* 一問一答形式（一括採点） *}-->
																	<!--{elseif $product_list.$display_format_test_key==='2'}-->
																		<!--{if $product_list.$exam_test_all_answered_key}-->
																			<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{else}-->
																			<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																		<!--{/if}-->
																	<!--{/if}-->
																<!--{else}-->
																	<!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}-->
																<!--{/if}-->
															<!--{else}-->
																提出期限外のため、受講不可です
															<!--{/if}-->
														<!--{/if}-->
													</div>
												<!--{/if}-->
											<!--{elseif $product_list.$exam_id_question_key>0}-->
												<!--{if $product_list.$public_flag_question_key=='0'}-->
													<div class="detail_btn">
														<!--{if $product_list.$submit_possible_flg_question_key}-->
															<!--{if !$product_list.$exam_question_all_answered_key || ($product_list.$exam_question_all_answered_key && $product_list.$resubmit_flag_question_key=='1')}-->
																<!--{if $product_list.$display_format_question_key==='0'}-->
																	<!--{if $product_list.$exam_question_all_answered_key}-->
																		<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																	<!--{else}-->
																		<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																	<!--{/if}-->
																<!--{elseif $product_list.$display_format_question_key==='1' || $product_list.$display_format_question_key==='2'}-->
																	<!--{if $product_list.$exam_question_all_answered_key}-->
																		<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																	<!--{else}-->
																		<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																	<!--{/if}-->
																<!--{/if}-->
															<!--{else}-->
																<!--{if $product_list.$display_format_question_key==='0'}-->
																	<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->">受講済</a>
																<!--{elseif $product_list.$display_format_question_key==='1' || $product_list.$display_format_question_key==='2'}-->
																	<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																<!--{/if}-->
															<!--{/if}-->
														<!--{else}-->
															提出期限外のため、受講不可です
														<!--{/if}-->
													</div>
												<!--{/if}-->
											<!--{/if}-->
										<!--{else}-->
											<div class="detail_btn"><!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}--></div>
										<!--{/if}-->
										</center>
										
										<!--{if $product_list.$public_flag_test_key=='0' && $product_list.$btn_type_key=='1' && array_search('3', $product_list.$disp_warning_word_key)!==false}-->
											<center style="float:right;width:40%;text-align:center;word-break:break-all;">
												<span>&nbsp;<!-- 注意文言(ウ) --></span>
											</center>
										<!--{/if}-->
										<br style="clear:both;" />
										<br style="clear:both;" />
									<!--{else}-->
										<center>
											<table style="background-color:#F5F8EF;width:680px;">
												<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><!--{$ccno|escape}-->、<!--{$product_list.$contents_contents_name_key|escape}--></td></tr>
												<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.$video_duration_key|escape}--></td></tr>
												<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.$video_view_flg_key}--><!--{$product_list.$video_reading_key|escape}--><!--{else}-->未視聴<!--{/if}--></td></tr>
											</table>
										</center>

										<center style="float:left;display: inline-block;width: 400px;">
											<div class="detail_btn" style="text-align: left;">
												<!--{* 1以降は前のテストチェック *}-->
												<!--{if $product_list.$exam_id_test_key_prev>0 || $product_list.$exam_id_question_key_prev>0}-->
													<!--{* 前のテストが設定されている場合は、テスト受講済の場合に表示する *}-->
													<!--{if ($product_list.$exam_test_all_answered_key_prev && $product_list.$exam_test_passing_flg_key_prev=='1') || ($product_list.$exam_question_all_answered_key_prev && $product_list.$exam_question_passing_flg_key_prev=='1')}-->
														<!--[ TYPE2 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<!--{if !$is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<!--{if $is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_baisoku_flg_key}-->
																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<br>
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->
															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<!--{* 前のテストが設定されていない場合は、前ビデオ視聴済みなら次を表示 *}-->
													<!--{elseif $product_list.$video_complete_flg_key_prev && $product_list.$exam_id_test_key_prev<=0 && $product_list.$exam_id_question_key_prev<=0}-->
														<!--[ TYPE3 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<!--{if !$is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<!--{if $is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_baisoku_flg_key}-->
																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<br>
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->
															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<!--{else}-->


														<!--[ TYPE4 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<!--{if !$is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_none.png);"  >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_none.png);"  >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);"  >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<!--{if $is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_baisoku_flg_key}-->
																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<br>
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->
															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<!--{/if}-->
												<!--{else}-->
													<!--{* すべてのテストに合格している場合は表示 *}-->
													<!--{if $product_list.all_passing_flg}-->


														<!--[ TYPE5 ]-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- PC -->
														<!--{if !$is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>

																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
														<!-- SP -->
														<!--{if $is_sp}-->
															<!--{if $product_list.$contents_contents_key>0}-->
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
															<!--{/if}-->
															<!--{if $product_list.$contents_baisoku_flg_key}-->
																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																	<div class="btn_plyer_text">始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																	<div class="btn_plyer_text">続きから再生</div>
																</div>
																-->
															<!--{/if}-->
															<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																<br>
																<br>
																<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->

																<!-- x13 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																</div>
																-->

																<!-- x15 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->

																<!-- x20 -->
																<!--
																<br>
																<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																	<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																</div>
																<!--{if $product_list.$video_view_flg_so_key}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{else}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																	</div>
																<!--{/if}-->
																-->
															<!--{/if}-->
														<!--{/if}-->
														<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


													<!--{else}-->
														<!--{* 合格したコンテンツ番号の次までは表示 *}-->
														<!--{if $product_list.now_passing_contents_no>0 && (($product_list.now_passing_contents_no+1)>=$ccno)}-->


															<!--[ TYPE6 ]-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- PC -->
															<!--{if !$is_sp}-->
																<!--{if $product_list.$contents_contents_key>0}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
																<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																	<br>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
															<!--{/if}-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- SP -->
															<!--{if $is_sp}-->
																<!--{if $product_list.$contents_contents_key>0}-->
																	<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
																<!--{if $product_list.$contents_baisoku_flg_key}-->
																	<!-- x13 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->

																	<!-- x15 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->

																	<!-- x20 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->
																<!--{/if}-->
																<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																	<br>
																	<br>
																	<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->

																	<!-- x13 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->

																	<!-- x15 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->

																	<!-- x20 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->
																<!--{/if}-->
															<!--{/if}-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


														<!--{* 前のテストが設定されていない場合は、前ビデオ視聴済みなら次を表示 *}-->
														<!--{elseif $product_list.$video_complete_flg_key_prev && $product_list.$exam_id_test_key_prev<=0 && $product_list.$exam_id_question_key_prev<=0}-->


															<!--[ TYPE7 ]-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- PC -->
															<!--{if !$is_sp}-->
																<!--{if $product_list.$contents_contents_key>0}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
																<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																	<br>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
															<!--{/if}-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- SP -->
															<!--{if $is_sp}-->
																<!--{if $product_list.$contents_contents_key>0}-->
																	<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
																<!--{if $product_list.$contents_baisoku_flg_key}-->
																	<!-- x13 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->

																	<!-- x15 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->

																	<!-- x20 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->
																<!--{/if}-->
																<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																	<br>
																	<br>
																	<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->

																	<!-- x13 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->

																	<!-- x15 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->

																	<!-- x20 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->
																<!--{/if}-->
															<!--{/if}-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


														<!--{else}-->


															<!--[ TYPE8 ]-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- PC -->
															<!--{if !$is_sp}-->
																<!--{if $product_list.$contents_contents_key>0}-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_none.png);" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																			<div class="btn_plyer_text">続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
																<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																	<br>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																<!--{/if}-->
															<!--{/if}-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
															<!-- SP -->
															<!--{if $is_sp}-->
																<!--{if $product_list.$contents_contents_key>0}-->
																	<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_none.png" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																<!--{/if}-->
																<!--{if $product_list.$contents_baisoku_flg_key}-->
																	<!-- x13 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x_none.png" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x_none.png" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->

																	<!-- x15 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x_none.png" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x_none.png" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->

																	<!-- x20 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x_none.png" >
																		<div class="btn_plyer_text">始めから再生</div>
																	</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x_none.png" >
																		<div class="btn_plyer_text">続きから再生</div>
																	</div>
																	-->
																<!--{/if}-->
																<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																	<br>
																	<br>
																	<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->

																	<!-- x13 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->

																	<!-- x15 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->

																	<!-- x20 -->
																	<!--
																	<br>
																	<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																	<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x_none.png" >
																		<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																	</div>
																	<!--{if $product_list.$video_view_flg_so_key}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x_none.png" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{else}-->
																		<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																			<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																		</div>
																	<!--{/if}-->
																	-->
																<!--{/if}-->
															<!--{/if}-->
															<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


														<!--{/if}-->
													<!--{/if}-->
												<!--{/if}-->
											</div>

											<!--{* 1以降は前のテストチェック *}-->
											<!--{if $product_list.$exam_id_test_key_prev>0 || $product_list.$exam_id_question_key_prev>0}-->
												<!--{* 前のテストが設定されている場合は、テスト受講済の場合に表示する *}-->
												<!--{if ($product_list.$exam_test_all_answered_key_prev && $product_list.$exam_test_passing_flg_key_prev=='1') || ($product_list.$exam_question_all_answered_key_prev && $product_list.$exam_question_passing_flg_key_prev=='1')}-->
													<!--{if $product_list.$video_complete_flg_key==1}-->
														<!--{* テストとアンケートが両方設定されていた場合は、テストの後にアンケートを結合し、期限等の条件はテストを優先する *}-->
														<!--{if $product_list.$exam_id_test_key>0 && $product_list.$exam_id_question_key>0}-->
															<!--{if $product_list.$public_flag_test_key=='0'}-->
																<div class="detail_btn">
																	<!--{if $product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='1'}-->
																		<!--{* 全画面一括採点形式 *}-->
																		<!--{if $product_list.$display_format_test_key==='0'}-->
																			<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->">受講済</a>
																		<!--{* 一問一答形式（都度採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='1'}-->
																			<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																		<!--{* 一問一答形式（一括採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='2'}-->
																			<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																		<!--{/if}-->
																	<!--{else}-->
																		<!--{if $product_list.$submit_possible_flg_test_key}-->
																			<!--{if !$product_list.$exam_test_all_answered_key || ($product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='0' && $product_list.$resubmit_flag_test_key=='1')}-->
																				<!--{* 全画面一括採点形式 *}-->
																				<!--{if $product_list.$display_format_test_key==='0'}-->
																					<!--{if $product_list.$exam_test_all_answered_key}-->
																						<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{else}-->
																						<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{/if}-->
																				<!--{* 一問一答形式（都度採点） *}-->
																				<!--{elseif $product_list.$display_format_test_key==='1'}-->
																					<!--{if $product_list.$exam_test_all_answered_key}-->
																						<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{else}-->
																						<a class="test_btn" href="/exam/index1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{/if}-->
																				<!--{* 一問一答形式（一括採点） *}-->
																				<!--{elseif $product_list.$display_format_test_key==='2'}-->
																					<!--{if $product_list.$exam_test_all_answered_key}-->
																						<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{else}-->
																						<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{/if}-->
																				<!--{/if}-->
																			<!--{else}-->
																				<!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}-->
																			<!--{/if}-->
																		<!--{else}-->
																			提出期限外のため、受講不可です
																		<!--{/if}-->
																	<!--{/if}-->
																</div>
															<!--{/if}-->
														<!--{elseif $product_list.$exam_id_test_key>0}-->
															<!--{if $product_list.$public_flag_test_key=='0'}-->
																<div class="detail_btn">
																	<!--{if $product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='1'}-->
																		<!--{* 全画面一括採点形式 *}-->
																		<!--{if $product_list.$display_format_test_key==='0'}-->
																			<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->">受講済</a>
																		<!--{* 一問一答形式（都度採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='1'}-->
																			<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1">受講済</a>
																		<!--{* 一問一答形式（一括採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='2'}-->
																			<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1">受講済</a>
																		<!--{/if}-->
																	<!--{else}-->
																		<!--{if $product_list.$submit_possible_flg_test_key}-->
																			<!--{if !$product_list.$exam_test_all_answered_key || ($product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='0' && $product_list.$resubmit_flag_test_key=='1')}-->
																				<!--{* 全画面一括採点形式 *}-->
																				<!--{if $product_list.$display_format_test_key==='0'}-->
																					<!--{if $product_list.$exam_test_all_answered_key}-->
																						<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{else}-->
																						<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{/if}-->
																				<!--{* 一問一答形式（都度採点） *}-->
																				<!--{elseif $product_list.$display_format_test_key==='1'}-->
																					<!--{if $product_list.$exam_test_all_answered_key}-->
																						<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{else}-->
																						<a class="test_btn" href="/exam/index1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{/if}-->
																				<!--{* 一問一答形式（一括採点） *}-->
																				<!--{elseif $product_list.$display_format_test_key==='2'}-->
																					<!--{if $product_list.$exam_test_all_answered_key}-->
																						<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{else}-->
																						<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																					<!--{/if}-->
																				<!--{/if}-->
																			<!--{else}-->
																				<!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}-->
																			<!--{/if}-->
																		<!--{else}-->
																			提出期限外のため、受講不可です
																		<!--{/if}-->
																	<!--{/if}-->
																</div>
															<!--{/if}-->
														<!--{elseif $product_list.$exam_id_question_key>0}-->
															<!--{if $product_list.$public_flag_question_key=='0'}-->
																<div class="detail_btn">
																	<!--{if $product_list.$submit_possible_flg_question_key}-->
																		<!--{if !$product_list.$exam_question_all_answered_key || ($product_list.$exam_question_all_answered_key && $product_list.$resubmit_flag_question_key=='1')}-->
																			<!--{if $product_list.$display_format_question_key==='0'}-->
																				<!--{if $product_list.$exam_question_all_answered_key}-->
																					<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																				<!--{else}-->
																					<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																				<!--{/if}-->
																			<!--{elseif $product_list.$display_format_question_key==='1' || $product_list.$display_format_question_key==='2'}-->
																				<!--{if $product_list.$exam_question_all_answered_key}-->
																					<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																				<!--{else}-->
																					<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																				<!--{/if}-->
																			<!--{/if}-->
																		<!--{else}-->
																			<!--{if $product_list.$display_format_question_key==='0'}-->
																				<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->">受講済</a>
																			<!--{elseif $product_list.$display_format_question_key==='1' || $product_list.$display_format_question_key==='2'}-->
																				<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																			<!--{/if}-->
																		<!--{/if}-->
																	<!--{else}-->
																		提出期限外のため、受講不可です
																	<!--{/if}-->
																</div>
															<!--{/if}-->
														<!--{/if}-->
													<!--{else}-->
														<div class="detail_btn"><!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}--></div>
													<!--{/if}-->
												<!--{else}-->
													<div class="detail_btn"><!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}--></div>
												<!--{/if}-->


											<!--{* 前のテストが設定されていない場合は無条件で表示する *}-->
											<!--{else}-->
											<!--{if $product_list.$video_complete_flg_key==1}-->
												<!--{* テストとアンケートが両方設定されていた場合は、テストの後にアンケートを結合し、期限等の条件はテストを優先する *}-->
												<!--{if $product_list.$exam_id_test_key>0 && $product_list.$exam_id_question_key>0}-->
													<!--{if $product_list.$public_flag_test_key=='0'}-->
														<div class="detail_btn">
															<!--{if $product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='1'}-->
																<!--{* 全画面一括採点形式 *}-->
																<!--{if $product_list.$display_format_test_key==='0'}-->
																	<a href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->">受講済</a>
																<!--{* 一問一答形式（都度採点） *}-->
																<!--{elseif $product_list.$display_format_test_key==='1'}-->
																	<a href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																<!--{* 一問一答形式（一括採点） *}-->
																<!--{elseif $product_list.$display_format_test_key==='2'}-->
																	<a href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																<!--{/if}-->
															<!--{else}-->
																<!--{if $product_list.$submit_possible_flg_test_key}-->
																	<!--{if !$product_list.$exam_test_all_answered_key || ($product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='0' && $product_list.$resubmit_flag_test_key=='1')}-->
																		<!--{* 全画面一括採点形式 *}-->
																		<!--{if $product_list.$display_format_test_key==='0'}-->
																			<!--{if $product_list.$exam_test_all_answered_key}-->
																				<a href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{else}-->
																				<a href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{/if}-->
																		<!--{* 一問一答形式（都度採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='1'}-->
																			<!--{if $product_list.$exam_test_all_answered_key}-->
																				<a href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{else}-->
																				<a href="/exam/index1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{/if}-->
																		<!--{* 一問一答形式（一括採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='2'}-->
																			<!--{if $product_list.$exam_test_all_answered_key}-->
																				<a href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{else}-->
																				<a href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&qid=<!--{$product_list.$exam_id_question_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{/if}-->
																		<!--{/if}-->
																	<!--{else}-->
																		<!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}-->
																	<!--{/if}-->
																<!--{else}-->
																	提出期限外のため、受講不可です
																<!--{/if}-->
															<!--{/if}-->
														</div>
													<!--{/if}-->
												<!--{elseif $product_list.$exam_id_test_key>0}-->
													<!--{if $product_list.$public_flag_test_key=='0'}-->
														<div class="detail_btn">
															<!--{if $product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='1'}-->
																<!--{* 全画面一括採点形式 *}-->
																<!--{if $product_list.$display_format_test_key==='0'}-->
																	<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->">受講済</a>
																<!--{* 一問一答形式（都度採点） *}-->
																<!--{elseif $product_list.$display_format_test_key==='1'}-->
																	<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1">受講済</a>
																<!--{* 一問一答形式（一括採点） *}-->
																<!--{elseif $product_list.$display_format_test_key==='2'}-->
																	<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1">受講済</a>
																<!--{/if}-->
															<!--{else}-->
																<!--{if $product_list.$submit_possible_flg_test_key}-->
																	<!--{if !$product_list.$exam_test_all_answered_key || ($product_list.$exam_test_all_answered_key && $product_list.$exam_test_passing_flg_key=='0' && $product_list.$resubmit_flag_test_key=='1')}-->
																		<!--{* 全画面一括採点形式 *}-->
																		<!--{if $product_list.$display_format_test_key==='0'}-->
																			<!--{if $product_list.$exam_test_all_answered_key}-->
																				<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{else}-->
																				<a class="test_btn" href="/exam/index.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{/if}-->
																		<!--{* 一問一答形式（都度採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='1'}-->
																			<!--{if $product_list.$exam_test_all_answered_key}-->
																				<a class="test_btn" href="/exam/result1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{else}-->
																				<a class="test_btn" href="/exam/index1.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{/if}-->
																		<!--{* 一問一答形式（一括採点） *}-->
																		<!--{elseif $product_list.$display_format_test_key==='2'}-->
																			<!--{if $product_list.$exam_test_all_answered_key}-->
																				<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{else}-->
																				<a class="test_btn" href="/exam/index2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_test_key}-->&eno=1"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a>
																			<!--{/if}-->
																		<!--{/if}-->
																	<!--{else}-->
																		<!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}-->
																	<!--{/if}-->
																<!--{else}-->
																	提出期限外のため、受講不可です
																<!--{/if}-->
															<!--{/if}-->
														</div>
													<!--{/if}-->
												<!--{elseif $product_list.$exam_id_question_key>0}-->
													<!--{if $product_list.$public_flag_question_key=='0'}-->
														<div class="detail_btn" style="text-align: left;">
															<!--{if $product_list.$submit_possible_flg_question_key}-->
																<!--{if !$product_list.$exam_question_all_answered_key || ($product_list.$exam_question_all_answered_key && $product_list.$resubmit_flag_question_key=='1')}-->


																	<!--[ TYPE9 ]-->
																	<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
																	<!-- PC -->
																	<!--{if !$is_sp}-->
																		<!--{if $product_list.$contents_contents_key>0}-->
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<!--{/if}-->
																		<!--{/if}-->
																		<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																			<br>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_so_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{/if}-->
																		<!--{/if}-->
																	<!--{/if}-->
																	<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
																	<!-- SP -->
																	<!--{if $is_sp}-->
																		<!--{if $product_list.$contents_contents_key>0}-->
																			<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
																					<div class="btn_plyer_text">続きから再生</div>
																				</div>
																			<!--{/if}-->
																		<!--{/if}-->
																		<!--{if $product_list.$contents_baisoku_flg_key}-->
																			<!-- x13 -->
																			<!--
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
																				<div class="btn_plyer_text">続きから再生</div>
																			</div>
																			-->

																			<!-- x15 -->
																			<!--
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
																				<div class="btn_plyer_text">続きから再生</div>
																			</div>
																			-->

																			<!-- x20 -->
																			<!--
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
																				<div class="btn_plyer_text">始めから再生</div>
																			</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
																				<div class="btn_plyer_text">続きから再生</div>
																			</div>
																			-->
																		<!--{/if}-->
																		<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
																			<br>
																			<br>
																			<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_so_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{/if}-->

																			<!-- x13 -->
																			<!--
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_so_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{/if}-->
																			-->

																			<!-- x15 -->
																			<!--
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_so_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{/if}-->
																			-->

																			<!-- x20 -->
																			<!--
																			<br>
																			<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
																			<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																				<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
																			</div>
																			<!--{if $product_list.$video_view_flg_so_key}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{else}-->
																				<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
																					<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
																				</div>
																			<!--{/if}-->
																			-->
																		<!--{/if}-->
																	<!--{/if}-->
																	<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


																<!--{else}-->
																	<!--{if $product_list.$display_format_question_key==='0'}-->
																		<a class="test_btn" href="/exam/result.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->">受講済</a>
																	<!--{elseif $product_list.$display_format_question_key==='1' || $product_list.$display_format_question_key==='2'}-->
																		<a class="test_btn" href="/exam/result2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$product_list.$exam_id_question_key}-->&eno=1">受講済</a>
																	<!--{/if}-->
																<!--{/if}-->
															<!--{else}-->
																提出期限外のため、受講不可です
															<!--{/if}-->
														</div>
													<!--{/if}-->
												<!--{/if}-->
											<!--{else}-->
												<div class="detail_btn"><!--{if $product_list.$exam_id_test_key>0 || $product_list.$exam_id_question_key>0}--><a class="test_btn_none" href="javascript:void(0)"><!--{if $product_list.$btn_type_key=='2'}-->アンケートに回答する<!--{else}-->テストを受ける<!--{/if}--></a><!--{/if}--></div>
											<!--{/if}-->
									<!--{/if}-->
									</center>
									
									<!--{if $product_list.$public_flag_test_key=='0' && $product_list.$btn_type_key=='1' && array_search('3', $product_list.$disp_warning_word_key)!==false}-->
										<center style="float:right;width:40%;text-align:center;word-break:break-all;">
											<span>&nbsp;<!-- 注意文言(ウ) --></span>
										</center>
									<!--{/if}-->
									<br style="clear:both;" />
									<br style="clear:both;" />
								<!--{/if}-->
							<!--{/if}-->
							
						<!--{* 通常のeラーニングの場合 *}-->
						<!--{else}-->
							<!--{if ($product_list.$contents_contents_key!='' && $product_list.$contents_view_flg_key) || ($product_list.$contents_contents_so_key!='' && $product_list.$contents_view_flg_so_key)}-->
								<center>
								<table style="background-color:#F5F8EF;width:680px;">
								<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><!--{$ccno|escape}-->、<!--{$product_list.$contents_contents_name_key|escape}--></td></tr>
								<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.$video_duration_key|escape}--></td></tr>
								<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.$video_view_flg_key}--><!--{$product_list.$video_reading_key|escape}--><!--{else}-->未視聴<!--{/if}--></td></tr>
								</table>
								</center>
								
								<center style="float:left;display: inline-block;width: 400px;">
									<div class="detail_btn" style="text-align: left;">


										<!--[ TYPE10 ]-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!-- PC -->
										<!--{if !$is_sp}-->
											<!--{if $product_list.$contents_contents_key>0}-->
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<!--{/if}-->
											<!--{/if}-->
											<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
												<br>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_so_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png" alt="" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{/if}-->
											<!--{/if}-->
										<!--{/if}-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!-- SP -->
										<!--{if $is_sp}-->
											<!--{if $product_list.$contents_contents_key>0}-->
												<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','')">
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','')" >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_none.png);"  >
														<div class="btn_plyer_text">続きから再生</div>
													</div>
												<!--{/if}-->
											<!--{/if}-->
											<!--{if $product_list.$contents_baisoku_flg_key}-->
												<!-- x13 -->
												<!--
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x13')" >
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x13')" >
													<div class="btn_plyer_text">続きから再生</div>
												</div>
												-->

												<!-- x15 -->
												<!--
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x15')" >
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x15')" >
													<div class="btn_plyer_text">続きから再生</div>
												</div>
												-->

												<!-- x20 -->
												<!--
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','','x20')" >
													<div class="btn_plyer_text">始めから再生</div>
												</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','','x20')" >
													<div class="btn_plyer_text">続きから再生</div>
												</div>
												-->
											<!--{/if}-->
											<!--{if $product_list.$contents_contents_key>0 && $product_list.$contents_contents_so_key>0}-->
												<br>
												<br>
												<!--<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.0倍&nbsp;</div>-->
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_so_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{/if}-->

												<!-- x13 -->
												<!--
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.3倍&nbsp;</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_so_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux13')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{/if}-->
												-->

												<!-- x15 -->
												<!--
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">1.5倍&nbsp;</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_so_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux15')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{/if}-->
												-->

												<!-- x20 -->
												<!--
												<br>
												<div style="float:left;width:50px;display:block;height:30px;line-height:30px;font-size:14px;">2.0倍&nbsp;</div>
												<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
													<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>始めから再生</div>
												</div>
												<!--{if $product_list.$video_view_flg_so_key}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_play_so_x.png);" ontouchmove="touchMoveFlag=true;" onclick="playerFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->','aux20')" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{else}-->
													<div class="btn_plyer" style="background-image: url(/img/lecture/btn_resume_so_none.png);" >
														<div class="btn_plyer_text"><span style="color:#1370C0;">音声のみ</span>続きから再生</div>
													</div>
												<!--{/if}-->
												-->
											<!--{/if}-->
										<!--{/if}-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->


									</div>
								</center>

								<br style="clear:both;" />
								<br style="clear:both;" />
							<!--{/if}-->
						<!--{/if}-->
					<!--{/section}-->

					<div style="padding:5px;text-align:center;" id="exam3_btn_area">
						※受講状況（受講率等）の表示は，１日１回更新されます。
					</div>
					<br />
				<!--{/if}-->
			</div>

		</div>
	<!--{/if}-->
	
<!--{* 会場研修 *}-->
<!--{elseif $product_list.product_type_add == 2}-->
	<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
		<div>
			<div style="text-align:right;">
				<!--{if $favorite_flg}-->
					<form name="favoriteForm" action="<!--{*https://*}--><!--{*php}-->echo $_SERVER['SERVER_NAME'];<!--{/php*}-->/mypage/favorite.php" method="post">
						<input type="hidden" name="act" value="regist" />
						<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
						<input type="image" src="/img/list/favorite_btn.png" /><br />
					</form>
				<!--{/if}-->
				<!--{if $favorite_icon_flg}-->
					<img src="/img/list/favorite_btn_comp02.png" alt="お気に入り商品" />
				<!--{/if}-->
			</div>

			<div class="detail_title" style="font-size:18px;color:#22730e; text-align:center;"><!--{$product_list.product_name|escape}--></div>
<!--{*
				<!--{foreach from=$arr_exam2 key=exam2_i item=exam2_row}-->
					<div style="text-align:left;">
						<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
						　（レビュー　<!--{$exam2_row.student|@count}-->件）
					</div>
				<!--{/foreach}-->

				<div style="text-align:left;">
					<a href="#exam3_btn_area" class="btn_gray">レビューを見る</a>
					　（レビュー　<!--{$arr_exam2|@count}-->件）
				</div>
*}-->
		<table style="background-color:#F5F8EF;width:680px;margin:20px;">
			<!--{if $product_list.memo2 != ''}-->
			<!--
			<tr style=" border: 2px #FFFFFF solid;">
				<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
				<td style="padding: 3px 10px;text-align:left;">
					<!--{$product_list.teacher|escape}-->
					<!--{if $product_list.teacher_student_id==$user_id}-->
						<!--{if $student_make_complete==0}-->
							<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
							<script type="text/javascript">
								function makingHistory(){
									var result = window.confirm('受講履歴を作成します');
									if(result){
										$.ajax({
											type: 'POST',
											url: '/product/complete.php?pid=<!--{$pid}-->',
											dataType: 'html',
											success: function(data) {
												location.reload(true);
											},
											error:function() {
												//alert('通信エラーが発生しました。');
												location.reload(true);
											}
										});
									}
								}
							</script>
						<!--{else}-->
							<br><div class="btn_graywhite">受講完了</div>
						<!--{/if}-->
					<!--{/if}-->
				</td>
			</tr>
			-->
			<!--{/if}-->
			<!--{if $product_list.memo2 != ''}-->
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.memo2|escape|nl2br}--></td></tr>
			<!--{/if}-->
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">主催</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.disp_sponsor}--></td></tr>
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">受付期間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.disp_start_date|escape}-->～<!--{$product_list.disp_end_date|escape}--></td></tr>
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">開催日</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.disp_dates|escape}--></td></tr>
		</table>
	</div>

	<div style="padding-top:20px;word-break:break-all;">
		<!--{if $product_list.memo1 != ''}-->
			■研修の内容<br />
			<!--{$product_list.memo1|escape|nl2br}--><br /><br />
		<!--{/if}-->
		<!--{if $product_list.memo3 != ''}-->
			■日時詳細<br />
			<!--{$product_list.memo3|escape|nl2br}--><br /><br />
		<!--{/if}-->
		<!--{if $product_list.memo4 != ''}-->
			■問い合わせ先<br />
			<!--{$product_list.memo4|escape|nl2br}--><br /><br />
		<!--{/if}-->
		<!--{if $product_list.memo5 != ''}-->
			■受講資格/他会員の受講等<br />
			<!--{$product_list.memo5|escape|nl2br}--><br /><br />
		<!--{/if}-->
		<!--{if $product_list.contents != ''}-->
			■備考<br />
			<!--{$product_list.contents|nl2br}-->
		<!--{/if}-->
	</div>
	
	<!--{if $nichibenren_tandoku_flg}-->
		<div id="search_info_area" style="padding-top:20px;">
			<form name="search_info" action="<!--{$search_url}-->" method="post">
			<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
			<input type="hidden" id="act" name="act" value="info_check" />
				<div>
					受講を希望する会場を選択する
				</div>
				<div style="text-align:center;">
					<!--{html_options name=bar_association_id options=$bar_association selected=$bar_association_id onchange="search_bar_association_branch()"}-->
					<!--{html_options name=bar_association_branch_id options=$bar_association_branch selected=$bar_association_branch_id}-->
					<br />
					<input type="image" src="/img/lecture/confirm_btn.png" name="submit">
					<!--<input type="submit" value="選択した研修会場の詳細情報を確認" />-->
				</div>
			</form>
			<!--{if $bar_association_branch_info}-->
				<div>
					<table style="background-color:#F5F8EF;width:450px;">
					<tr style=" border: 2px #FFFFFF solid;">
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:60px;">弁護士会名</th><td style="padding: 3px 10px;"><!--{$bar_association_branch_info.bar_association_name|escape}--></ td>
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;">定員</th><td style="padding: 3px 10px;"><!--{if $bar_association_branch_info.capacity==9999}-->制限なし<!--{else}--><!--{$bar_association_branch_info.capacity|escape}-->名<!--{/if}--></ td>
					</tr>
					<tr style=" border: 2px #FFFFFF solid;">
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:60px;">会場</th><td style="padding: 3px 10px;"><!--{$bar_association_branch_info.hall|escape}--></ td>
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;">受付</th><td style="padding: 3px 10px;"><!--{$bar_association_branch_info.receptionist_start_date|escape}-->～<!--{$bar_association_branch_info.receptionist_end_date|escape}--></ td>
					</tr>
					<tr style=" border: 2px #FFFFFF solid;">
					<th style="color:#663333;padding: 3px 10px;text-align:left;width:60px;">備考</th><td colspan="3" style="padding: 3px 10px;"><!--{$bar_association_branch_info.contents|nl2br}--></td>
					</tr>
					</table>
				</div>
				
				<div style="text-align:center;padding:20px 0 15px 0;">
					詳細は、各弁護士会にお問い合わせください。
				</div>
				<!--{if $disp_web_flg}-->
					<!--{if $bar_association_branch_info.web_flg == '1'}-->
						<div style="clear:both;text-align:center;padding:20px;">
						<!--{if $capacity_alert_flg}-->
							<span style="color:red;">お申込み人数が定員に達したため、お申込みすることができません。</span>
						<!--{elseif $limittime_alert_flg}-->
							<span style="color:red;">受付期間外のため、お申込みすることができません。</span>
						<!--{else}-->
							<!--{if $buy_wait_flg}-->
								<img src="/img/lecture/buy_wait.png" alt="購入手続き中" />
							<!--{else}-->
								<!--{if $buy_flg}-->
									<!--{if $kaijo_moushikomi_flg}-->
										<form name="buyForm" action="/settlement/index.php" method="post" style="display:inline;">
										<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
										<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
										<input type="hidden" name="hid_product_type_add" value="<!--{$product_list.product_type_add|escape}-->" />
										<input type="hidden" name="hid_bar_association_id" value="<!--{$bar_association_id|escape}-->" />
										<input type="hidden" name="hid_bar_association_branch_id" value="<!--{$bar_association_branch_id|escape}-->" />
											<input type="image" src="/img/btn/apply_btn.png" alt="この研修を申し込む" /><br />
										</form>
									<!--{else}-->
										<span style="color:red;font-size:18px;">現在お持ちの研修パスポートは，<br />研修開催日までに期限切れとなります。</span>
										<div style="text-align:left;padding-top:10px;">
											１ 現在お申し込みいただける方法<br />
											・弁護士会窓口で申し込む（申込方法は，実施会にお問い合わせください。）<br />
											受講料は，個別にお支払いいただくか，研修実施日までに，研修パスポートを更新（継続購入）してください。<br />
											<br />
											２ 研修パスポートの更新（継続購入）方法<br />
											研修パスポートは，有効期限の１ヶ月前から更新（継続購入）が可能です。<br />
											当サイトトップページの「研修パスポートのご案内」から購入の手続をしてください。
										</div>
									<!--{/if}-->
								<!--{else}-->
									<img src="/img/lecture/buy_fix.png" alt="申込み済" />
								<!--{/if}-->
							<!--{/if}-->
						<!--{/if}-->
						</div>
					<!--{elseif $bar_association_branch_info.web_flg == '2'}-->
						<div style="clear:both;text-align:center;padding:20px;font-size:18px;color:red;">
							研修を実施しません。
						</div>
					<!--{/if}-->
				<!--{/if}-->
			<!--{/if}-->
			<script type="text/javascript">
			function search_bar_association_branch(){
				document.getElementById("act").value = "search_branch";
				document.search_info.submit();
			}
			</script>
		</div>
	<!--{/if}-->
</div>
	
<!--{* 倫理代替措置研修 *}-->
<!--{elseif $product_list.product_type_add == 3}-->
<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
	<div class="detail_title" style="font-size:18px;color:#22730e; text-align:"><!--{$product_list.product_name|escape}--></div>
	<div style="padding:30px 15px;text-align:center;font-size:20px;color:#000000;font-weight:bold;">
		「講義：<!--{if $all_view_flg}-->受講済<!--{elseif $view_flg}-->受講中<!--{else}-->未受講<!--{/if}-->」
		「設問・解説：<!--{if !$ethic_status}-->未受講<!--{elseif $ethic_status === '2' || $ethic_status === '5' || $ethic_status === '6' || $ethic_status === '7' || $ethic_status === '8'}-->受講済<!--{else}-->受講中<!--{/if}-->」
	</div>
	<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">商品説明</div>
	<div style="padding:10px 40px;word-break:break-all;">
		<!--{$product_list.memo|nl2br}-->
		<br />
		<!--
		マニュアルは<a href="/pdf/rinri-kenshu-manual.pdf" target="_blank" rel="noopener noreferrer">こちら</a><br />
		よくあるご質問（ＦＡＱ）は<a href="/pdf/rinri-kenshu-faq.pdf" target="_blank" rel="noopener noreferrer">こちら</a><br />
		-->
		<a href="/pdf/rinri-kenshu-manual.pdf" target="_blank" rel="noopener noreferrer">マニュアルはこちら</a><br />
		<a href="/pdf/rinri-kenshu-faq.pdf" target="_blank" rel="noopener noreferrer">ＦＡＱはこちら</a><br />
	</div>
<!--{*
	<table style="background-color:#F5F8EF;width:680px;">
	<!--{if $product_list.teacher != ''}-->
	<tr style=" border: 2px #FFFFFF solid;">
		<th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>講師名</th>
		<td style="padding: 3px 10px;text-align:left;">
			<!--{$product_list.teacher|escape}-->
			<!--{if $product_list.teacher_student_id==$user_id}-->
				<!--{if $student_make_complete==0}-->
					<br><div class="btn_graywhite" ontouchmove="touchMoveFlag=true;" onclick="makingHistory()">講師受講確認</div>
					<script type="text/javascript">
						function makingHistory(){
							var result = window.confirm('受講履歴を作成します');
							if(result){
								$.ajax({
									type: 'POST',
									url: '/product/complete.php?pid=<!--{$pid}-->',
									dataType: 'html',
									success: function(data) {
										location.reload(true);
									},
									error:function() {
										//alert('通信エラーが発生しました。');
										location.reload(true);
									}
								});
							}
						}
					</script>
				<!--{else}-->
					<br><div class="btn_graywhite">受講完了</div>
				<!--{/if}-->
			<!--{/if}-->
		</td>
	</tr>
	<!--{/if}-->
	<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">掲載期間</th><td style="padding: 3px 10px;text-align:left;">
		<!--{if $product_list.start_date!='' && $product_list.start_date!='0000-00-00 00:00:00' && $product_list.end_date!='' && $product_list.end_date!='0000-00-00 00:00:00'}-->
			<!--{$product_list.disp_start_date|escape}-->～<!--{$product_list.disp_end_date|escape}-->
		<!--{elseif $product_list.start_date!='' && $product_list.start_date!='0000-00-00 00:00:00'}-->
			<!--{$product_list.disp_start_date|escape}-->～
		<!--{elseif $product_list.end_date!='' && $product_list.end_date!='0000-00-00 00:00:00'}-->
			～<!--{$product_list.disp_end_date|escape}-->
		<!--{else}-->
			未定
		<!--{/if}-->
	</td></tr>
	<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">備考</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.memo|nl2br}--></td></tr>
	</table>

	<br style="clear:both;" /><div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>
*}-->
	<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修資料</div>
	
	<div style="text-align:center;padding:20px 0;">
		<input type="image" src="/img/lecture/dl_btn.png" ontouchmove="touchMoveFlag=true;" onclick="downloadPopup();">
		<!--<input type="button" value="資料一覧からダウンロード" ontouchmove="touchMoveFlag=true;" onclick="downloadPopup();" />-->
	</div>
	
	<div>
		<!--{if
		   ($product_list.contents_contents1!='')
		|| ($product_list.contents_contents2!='')
		|| ($product_list.contents_contents3!='')
		|| ($product_list.contents_contents4!='')
		|| ($product_list.contents_contents5!='')
		|| ($product_list.contents_contents6!='')
		|| ($product_list.contents_contents7!='')
		|| ($product_list.contents_contents8!='')
		|| ($product_list.contents_contents9!='')
		|| ($product_list.contents_contents10!='')
		|| ($product_list.contents_contents11!='')
		|| ($product_list.contents_contents12!='')
		|| ($product_list.contents_contents13!='')
		|| ($product_list.contents_contents14!='')
		|| ($product_list.contents_contents15!='')
		|| ($product_list.contents_contents16!='')
		|| ($product_list.contents_contents17!='')
		|| ($product_list.contents_contents18!='')
		|| ($product_list.contents_contents19!='')
		|| ($product_list.contents_contents20!='')
		|| ($product_list.contents_contents21!='')
		|| ($product_list.contents_contents22!='')
		|| ($product_list.contents_contents23!='')
		|| ($product_list.contents_contents24!='')
		|| ($product_list.contents_contents25!='')
		|| ($product_list.contents_contents1so!='') 
		|| ($product_list.contents_contents2so!='') 
		|| ($product_list.contents_contents3so!='') 
		|| ($product_list.contents_contents4so!='') 
		|| ($product_list.contents_contents5so!='') 
		|| ($product_list.contents_contents6so!='') 
		|| ($product_list.contents_contents7so!='') 
		|| ($product_list.contents_contents8so!='') 
		|| ($product_list.contents_contents9so!='') 
		|| ($product_list.contents_contents10so!='') 
		|| ($product_list.contents_contents11so!='') 
		|| ($product_list.contents_contents12so!='') 
		|| ($product_list.contents_contents13so!='') 
		|| ($product_list.contents_contents14so!='') 
		|| ($product_list.contents_contents15so!='') 
		|| ($product_list.contents_contents16so!='') 
		|| ($product_list.contents_contents17so!='') 
		|| ($product_list.contents_contents18so!='') 
		|| ($product_list.contents_contents19so!='') 
		|| ($product_list.contents_contents20so!='') 
		|| ($product_list.contents_contents21so!='') 
		|| ($product_list.contents_contents22so!='') 
		|| ($product_list.contents_contents23so!='') 
		|| ($product_list.contents_contents24so!='') 
		|| ($product_list.contents_contents25so!='') 
		}-->
			<div style="height:3px;width:100%;border-top:solid 1px #F7F6F0;border-bottom:solid 1px #F7F6F0;margin:25px 0;clear:both;"></div>
			<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">研修パート一覧</div>
			<div style="width:100%;border-top:solid 1px #000000;margin:5px 0;"></div>
			
			<!--{section name=contents_contents loop=$section_max_contents start=1}-->
			<!--{assign var=ccno value=$smarty.section.contents_contents.index}-->
			<!--{assign var=contents_contents_key value="contents_contents"|cat:$ccno}-->
			<!--{assign var=contents_contents_name_key value="contents_contents"|cat:$ccno|cat:"_name"}-->

			<!--{assign var=contents_contents_so_key value="contents_contents"|cat:$ccno|cat:"so"}-->
			<!--{assign var=contents_contents_so_name_key value="contents_contents"|cat:$ccno|cat:"so_name"}-->

			<!--{assign var=contents_thumbnail_key value="contents_thumbnail"|cat:$ccno}-->
			<!--{assign var=contents_teacher_key value="contents_teacher"|cat:$ccno}-->
			<!--{assign var=contents_start_date_key value="contents_start_date"|cat:$ccno}-->
			<!--{assign var=contents_end_date_key value="contents_end_date"|cat:$ccno}-->
			<!--{assign var=contents_memo_key value="contents_memo"|cat:$ccno}-->
			<!--{assign var=contents_view_flg_key value="contents_view_flg"|cat:$ccno}-->
			<!--{assign var=contents_baisoku_flg_key value="contents_baisoku_flg"|cat:$ccno}-->
			<!--{assign var=contents_view_flg_so_key value="contents_view_flg"|cat:$ccno|cat:"so"}-->
			<!--{assign var=contents_free_time_key value="contents_free_time"|cat:$ccno}-->
			<!--{assign var=video_thumbnail_key value="video_thumbnail"|cat:$ccno}-->
			<!--{assign var=video_duration_key value="video_duration"|cat:$ccno}-->
			<!--{assign var=video_reading_key value="video_reading"|cat:$ccno}-->
			<!--{assign var=video_view_flg_key value="video_view_flg"|cat:$ccno}-->
			<!--{assign var=video_complete_flg_key value="video_complete_flg"|cat:$ccno}-->
			
			<!--{if $product_list.$contents_contents_key!='' || $product_list.$contents_contents_so_key!=''}-->

				<center>
				<table style="background-color:#F5F8EF;width:680px;">
				<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;" nowrap>タイトル</th><td style="padding: 3px 10px;text-align:left;"><!--{$ccno|escape}-->、<!--{$product_list.$contents_contents_name_key|escape}--></td></tr>
				<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">再生時間</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.$video_duration_key|escape}--></td></tr>
				<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:70px;text-align:left;">視聴済時間</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.$video_view_flg_key}--><!--{$product_list.$video_reading_key|escape}--><!--{else}-->未視聴<!--{/if}--></td></tr>
				</table>
				</center>
				<div class="detail_btn" style="text-align: left;">
					<center>
						<!--{if !$is_sp}-->
							<!--{if $product_list.$contents_contents_key>0}-->
								<img src="/img/lecture/play_btn_off.png" alt="始めから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','')" style="cursor:pointer;" />
								<!--{if $product_list.$video_view_flg_key}-->
									<img src="/img/lecture/resume_btn_off.png" alt="続きから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','')" style="cursor:pointer;" />
								<!--{else}-->
									<img src="/img/lecture/resume_btn_none.png" alt="続きから再生" style="cursor:pointer;" />
								<!--{/if}-->
							<!--{/if}-->
							<!--[<!--{$contents_contents_so_key}-->:<!--{$product_list.$contents_contents_so_key}-->]-->
							<!--{if $product_list.$contents_contents_so_key>0}-->
								<img src="/img/lecture/play_btn_off_so.png" alt="始めから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','start','<!--{$product_list.$contents_contents_so_key|escape}-->')" style="cursor:pointer;" />
								<!--{if $product_list.$video_view_flg_key}-->
									<img src="/img/lecture/resume_btn_off_so.png" alt="続きから再生" ontouchmove="touchMoveFlag=true;" onclick="playerEthicFormSubmit('<!--{$product_list.$contents_contents_key|escape}-->','<!--{$contents_free_time_key|escape}-->','<!--{$ccno|escape}-->','bookmark','<!--{$product_list.$contents_contents_so_key|escape}-->')" style="cursor:pointer;" />
								<!--{else}-->
									<img src="/img/lecture/resume_btn_none_so.png" alt="続きから再生" style="cursor:pointer;" />
								<!--{/if}-->
							<!--{/if}-->
						<!--{else}-->
							<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span>
						<!--{/if}-->
					</center>
				</div>
				<br />
			<!--{/if}-->
			<!--{/section}-->
			
			<div style="padding-top:5px;text-align:center;" id="exam3_btn_area">
				※受講状況（受講率等）の表示は、１日１回更新されます。
			</div>
			<div style="padding-top:5px;text-align:center;color:red;">
				『設問に回答する』『受講結果を確認する』ボタンが表示されない場合は、<br />更新ボタンを押してください。
			</div>
			<!--{if !$is_sp}-->
			<div style="padding-top:5px;text-align:center;color:red;">
				<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span>
			</div>
			<!--{/if}-->
			<br />
		<!--{/if}-->
	</div>
	
	<!--{if $all_view_flg}-->
		<div style="width:100%;border-top:solid 1px #000000;margin:5px 0;"></div>
		<center>
		<table style="background-color:#F5F8EF;width:300px;">
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:100px;">倫理研修設問</th><td style="padding: 3px 10px;">
			<!--{if !$ethic_status}-->
				未受講
				<br /><a href="/ethic_treaning?pid=<!--{$pid|escape}-->"><img src="/img/button/test_btn.png" alt="設問に回答する" /></a>
			<!--{elseif $ethic_status === '1'}-->
				受講中
				<br /><a href="/ethic_treaning?pid=<!--{$pid|escape}-->"><img src="/img/button/test_btn.png" alt="設問に回答する" /></a>
			<!--{elseif $ethic_status >= '2'}-->
				受講済
			<!--{/if}-->
			</td></tr>
		</table>
		</center>
		<!--{if $ethic_status === '3' || $ethic_status === '4' || $ethic_status === '5' || $ethic_status === '6' || $ethic_status === '7'}-->
		<center>
		<table style="background-color:#F5F8EF;width:300px;">
			<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:100px;">倫理研修追試</th><td style="padding: 3px 10px;">
				<!--{if $ethic_status === '3'}-->
					未受講
					<br /><a href="/ethic_treaning/retry.php?pid=<!--{$pid|escape}-->"><img src="/img/button/test_btn.png" alt="テストを受ける" /></a>
				<!--{elseif $ethic_status === '4'}-->
					受講中
					<br /><a href="/ethic_treaning/retry.php?pid=<!--{$pid|escape}-->"><img src="/img/button/test_btn.png" alt="テストを受ける" /></a>
				<!--{elseif $ethic_status === '5' || $ethic_status === '6' || $ethic_status === '7'}-->
					受講済
				<!--{/if}-->
			</td></tr>
		</table>
		</center>
		<!--{/if}-->
		<!--{if $ethic_status >= '2'}-->
			<div style="text-align:center;width:100%;border-bottom:solid 1px #000000;margin:5px 0;">
				<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;">受講結果</div>
				<br /><a href="/ethic_treaning/result_history.php?pid=<!--{$pid|escape}-->"><img src="/img/lecture/jyukoukekka_kakunin.png" alt="受講結果を確認する" /></a>
			</div>
		<!--{/if}-->
	<!--{else}-->
		<div style="text-align:center;width:100%;color:red;font-size:16px;">
			すべての動画を視聴した後、ボタンが表示され、<br />設問に回答することができるようになります。
		</div>
		<br />
	<!--{/if}-->
	
</div>
	
<!--{* パスポート *}-->
<!--{elseif $product_list.product_type_add == 4}-->
<div style="width:700px;float:left;clear:both;background-color:#fcfcfc;border-left:solid 1px #f3f3f3;border-right:solid 1px #f3f3f3;padding:5px 15px;">
	<div style="width:168px;float:left;text-align:center;">
		<!--{if $product_list.thumbnail != ''}-->
			<img src="/resize_image.php?image=<!--{$product_list.thumbnail|escape}-->&width=150&height=150" alt="" />
		<!--{else}-->
			<img src="/resize_image.php?image=noimage.jpg&width=150&height=150" alt="" />
		<!--{/if}-->
	</div>
	
	<div style="width:300px;float:right;">
		<div class="detail_title" style="font-size:14px;color:#22730e; text-align:center;"><!--{$product_list.product_name|escape}--></div>
	<table style="background-color:#F5F8EF;float:right;width:290px;">
		<!--<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;text-align:left;">価格</th><td style="padding: 3px 10px;text-align:left;"><!--{if $product_list.price_intax==0}-->無料<!--{else}--><!--{$product_list.price_intax|escape|number_format}-->円(税込)<!--{/if}--></td></tr>-->
		<tr style=" border: 2px #FFFFFF solid;"><th style="color:#663333;padding: 3px 10px;text-align:left;width:30px;text-align:left;">備考</th><td style="padding: 3px 10px;text-align:left;"><!--{$product_list.memo|nl2br}--></td></tr>
	</table>
	</div>
	
	<div style="clear:both;text-align:center;padding:20px;">
		<!--{if !$passport_flg}-->
			<!--{if $buy_wait_flg}-->
				<img src="/img/lecture/buy_wait.png" alt="購入手続き中" /><br />
			<!--{else}-->
				<form name="buyForm" action="/settlement/index.php" method="post" style="display:inline;">
				<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
				<input type="hidden" name="pid" value="<!--{$pid|escape}-->" />
				<input type="hidden" name="hid_product_type_add" value="<!--{$product_list.product_type_add|escape}-->" />
					<input type="image" src="/img/button/buy_process_btn.jpg" alt="買い物かごに入れる" /><br />
				</form>
			<!--{/if}-->
		<!--{/if}-->
	</div>
</div>
	
<!--{/if}-->



<!--{if $buy_flg}-->
<!--{else}-->
	<div style="clear:both;background-color: #ffffff;" id="exam2_btn_area">
		<!--{if $product_list.exam2_id>0}-->
			<!--{if count($exam2_answer)>0}-->
				<!--[回答済]-->
				<!--{if $exam2.resubmit_flag==1}-->
					<!--[再回答可]-->
					<a href="javascript:void(0);" ontouchmove="touchMoveFlag=true;" onclick="pop_get_html('/exam2/result.php?pid=<!--{$pid}-->&e2id=<!--{$product_list.exam2_id}-->')" id="exam2_btn">アンケートに答える</a>
				<!--{else}-->
					<!--[再回答不可]-->
				<!--{/if}-->
			<!--{else}-->
				<!--[未回答]-->
				<a href="javascript:void(0);" ontouchmove="touchMoveFlag=true;" onclick="pop_get_html('/exam2/index.php?pid=<!--{$pid}-->&e2id=<!--{$product_list.exam2_id}-->')" id="exam2_btn">アンケートに答える</a>
			<!--{/if}-->
		<!--{/if}-->
	</div>
	<script type="text/javascript">
		
	</script>
<!--{/if}-->
<script type="text/javascript">
	function pop_get_html(str_url){
		if ($('#exam2_pop_area').css('display') == 'block') {
		} else {
			$('#exam2_pop_area').show();
		}
		$('#exam2_pop_html_area').show();
		$('#exam2_pop_html_area_sub').hide();
		$.ajax({
			type: 'POST',
			url: str_url,
			dataType: 'html',
			success: function(data) {
				$('#exam2_pop_html_area').hide();
				$('#exam2_pop_html_area').html("");
				$('#exam2_pop_html_area').show();
				$('#exam2_pop_html_area').html(data);
			},
			error:function() {
				alert('通信エラーが発生しました。');
			}
		});
	}
	function pop_close(){
		$('#exam2_pop_area').hide();
	}
	function pop_close_reload(){
		//$('#exam2_pop_area').hide();
		location.reload();
	}

	function pop_get_html_sub(str_url,str_form_id){
		var $form = $("#"+ str_form_id);
		$('#exam2_pop_html_area').hide();
		$.ajax({
			type: 'POST',
			data: $form.serialize(),
			url: str_url,
			dataType: 'html',
			success: function(data) {
				$('#exam2_pop_html_area').hide();
				$('#exam2_pop_html_area_sub').html("");
				$('#exam2_pop_html_area_sub').show();
				$('#exam2_pop_html_area_sub').html(data);
			},
			error:function() {
				alert('通信エラーが発生しました。');
			}
		});
	}
	function pop_close_sub(){
		$('#exam2_pop_html_area').show();
		$('#exam2_pop_html_area_sub').hide();
		$('#exam2_pop_html_area_sub').html("");
	}
</script>
<style type="text/css">
	#exam2_btn_area{
		display:block;
		text-align: center;
		background-color: #ffffff;
	}
	#exam2_btn{
		display: inline-block;
		line-height: 24px;
		height: 24px;
		color: #fff;
		background-color: #756B6B;
		text-decoration: none;
		width: 140px;
		text-align: center;
		border-radius: 4px;
		margin: 4px;

	}
	#exam2_pop_area{
		display: none;
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 999999;
		background-color: rgba(0, 0, 0, .65);
	}
	#exam2_pop_area_window{
		width: 900px;
		height: 90%;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		border-radius: 15px;
		background-color: #fff;
		overflow: hidden;
		padding: 15px;
	}
	#exam2_pop_html_area{
		/*
		padding:15px;
		overflow-y:auto;
		*/
		width: 100%;
		overflow-y: scroll;
		overflow-x: hidden;
		word-break: break-all;
		word-wrap: break-word;
		display: inline-block;
		height: 100%;
	}
	#exam2_pop_html_area_sub{
		width: 100%;
		overflow-y: scroll;
		overflow-x: hidden;
		word-break: break-all;
		word-wrap: break-word;
		display: none;
		height: 100%;
	}

	a.btn_gray:link,
	a.btn_gray:link,
	a.btn_gray:visited,
	a.btn_gray:hover,
	a.btn_gray:active {
		color: #ffffff;
	}
	.btn_gray {
		display: inline-block;
		line-height: 24px;
		height: 24px;
		color: #fff;
		background-color: #756B6B;
		text-decoration: none;
		width: 140px;
		text-align: center;
		border-radius: 4px;
		margin: 4px;
	}

	.btn_graywhite {
		display: inline-block;
		line-height: 24px;
		height: 24px;
		color: #756B6B;
		background-color: #FFFFFF;
		text-decoration: none;
		width: 140px;
		text-align: center;
		border-radius: 4px;
		margin: 4px;
		border: solid 1px #756B6B;
		cursor : pointer;
	}

</style>
<div id="exam2_pop_area">
	<div id="exam2_pop_area_window">
		<div id="exam2_pop_html_area">
		</div>
		<div id="exam2_pop_html_area_sub">
		</div>
	</div>
</div>





<a id="review_list" name="review_list"></a>
<div id="list" style="background-color: #ffffff;">
<!--{*
<!--{foreach from=$arr_exam2 key='exam2_i' item='exam2_row'}-->
	<br><br>
	<p><span style="border-bottom: solid 2px skyblue;"><strong><font size="3" color="skyblue">レビュー一覧</font></strong>　（レビュー　<!--{$arr_exam2|@count}-->件）</span></p>
	<p>本レビューは，みなさまからいただいたアンケートを基に掲載しております。<br>
	レビュー内容（修習期／弁護士経験年数）</p>
	<hr>
	<!--{foreach from=$exam2_row.student key='student_i' item='student_row'}-->
		<div id="student_<!--{$student_row.student_id}-->">
			<!--{foreach from=$exam2_row.exam2_problem key='exam2_problem_i' item='exam2_problem_row'}-->
				<div id="exam2_problem_<!--{$exam2_problem_row.exam2_problem_id}-->">
					<div>
						<!--{$exam2_problem_i+1}-->.
						<!--[<!--{$exam2_problem_row.exam2_problem_id}-->]-->
						<!--{$exam2_problem_row.problem_contents}-->
					</div>

					<div>
						<!--[<!--{$exam2_problem_row.answer_kind}-->]-->
						<!--{if $exam2_problem_row.answer_kind=="1"}-->
							<!-- ++++++++++++++++++++++++++++++++++++++++ -->
							<!--{foreach from=$exam2_problem_row.answer_contents_arr.answer_contents key='exam2_problem_row_answer_contents_i' item='exam2_problem_row_answer_contents_row'}-->
								<!--{if $student_row.answer[$exam2_problem_i].exam2_answer_contents==$exam2_problem_row_answer_contents_row.no}-->
									・<!--{$exam2_problem_row_answer_contents_row.word}--><br>
								<!--{/if}-->
							<!--{/foreach}-->
							<!-- ++++++++++++++++++++++++++++++++++++++++ -->
						<!--{/if}-->
						<!--{if $exam2_problem_row.answer_kind=="2"}-->
							<!--{foreach from=$student_row.answer[$exam2_problem_i].exam2_answer_contents_arr key='exam2_answer_contents_arr_row_i' item='exam2_answer_contents_arr_row'}-->
								<!-- ++++++++++++++++++++++++++++++++++++++++ -->
								<!--{foreach from=$exam2_problem_row.answer_contents_arr.answer_contents key='exam2_problem_row_answer_contents_i' item='exam2_problem_row_answer_contents_row'}-->
									<!--{if $exam2_answer_contents_arr_row==$exam2_problem_row_answer_contents_row.no}-->
										・<!--{$exam2_problem_row_answer_contents_row.word}--><br>
									<!--{/if}-->
								<!--{/foreach}-->
								<!-- ++++++++++++++++++++++++++++++++++++++++ -->
							<!--{/foreach}-->
						<!--{/if}-->
						<!--{if $exam2_problem_row.answer_kind=="3"}-->
							<!--{$student_row.answer[$exam2_problem_i].exam2_answer_contents}-->
						<!--{/if}-->
						<br>
					</div>

				</div>
			<!--{/foreach}-->
			<!--{foreach from=$student_row.info[0] key='student_info_i' item='student_info_row'}-->
				<!--{if $student_info_i|trim==="regist_date"}-->
					<!--[<!--{$student_info_i}-->:<!--{$student_info_row}-->]-->
					<!--{assign var="reist_date_year" value=$student_info_row|date_format:"%Y"}-->
					<!--{assign var="now_date_year" value=$smarty.now|date_format:"%Y"}-->
					<!--[<!--{$reist_date_year}-->:<!--{$now_date_year}-->]-->
					<!--{if ($now_date_year-$reist_date_year)>=10}-->
						(弁護士経験：１０年以上)
					<!--{elseif ($now_date_year-$reist_date_year)>=3}-->
						(弁護士経験：３年～１０年未満)
					<!--{elseif ($now_date_year-$reist_date_year)>=1}-->
						(弁護士経験：１年～３年未満)
					<!--{else}-->
						(弁護士経験：１年未満)
					<!--{/if}-->

				<!--{/if}-->
			<!--{/foreach}-->
		</div>
		<hr>
	<!--{/foreach}-->
<!--{/foreach}-->
*}-->

<!--{if !empty($arr_exam2)}-->
	<br><br>
	<p><span style="border-bottom: solid 2px skyblue;"><strong><font size="3" color="skyblue">レビュー一覧</font></strong>　（レビュー　<!--{$arr_exam2|@count}-->件）</span></p>
	<p>本レビューは，みなさまからいただいたアンケートを基に掲載しております。<br>
	レビュー内容（修習期／弁護士経験年数）</p>
	<hr>
	<!--{foreach from=$arr_exam2 key='exam2_i' item='exam2_row'}-->
		<div>
			<!--{$exam2_row|nl2br}-->
		</div>
		<hr>
	<!--{/foreach}-->
<!--{/if}-->
</div>































<!--{*
<div style="clear:both;">
	<!--{$product_list.free_html_area2}-->
</div>
*}-->

<!--{*
<div style="clear:both;" class="detail_h2_2">
	<h2>おすすめ商品</h2>
</div>
<div style="clear:both;">
	<ul style="list-style:none;margin:0;">
	<!--{foreach name=recommend_products from=$arr_recommend_list item=recommend_products}-->
		<li style="float:left;width:90px;padding:5px;text-align:center;<!--{if $smarty.foreach.recommend_products.index==5}-->clear:both;<!--{/if}-->">
		<a href="/product/detail.php?pid=<!--{$recommend_products.product_id|escape}-->" alt="<!--{$recommend_products.product_name|escape}-->" title="<!--{$recommend_products.product_name|escape}-->"><img src="/resize_image.php?image=<!--{$recommend_products.thumbnail|escape}-->&width=90&height=90" alt="" /></a><br />
		<a href="/product/detail.php?pid=<!--{$recommend_products.product_id|escape}-->" alt="<!--{$recommend_products.product_name|escape}-->" title="<!--{$recommend_products.product_name|escape}-->"><!--{$recommend_products.product_name|mb_truncate:14:"..."|escape}--></a><br />
		<!--{$recommend_products.price_intax|escape|number_format}-->円(税込)
		</li>
	<!--{/foreach}-->
	</ul>
</div>
*}-->

<!--{*
<div style="clear:both;">
	<!--{$product_list.free_html_area3}-->
</div>
*}-->


<!--{* 関連講座はeラーニングと会場研修の時のみ表示する *}-->
<!--{if $product_list.product_type_add == 1 || $product_list.product_type_add == 2}-->
	<div style="clear:both;margin:0;" class="detail_h2_3" id="RelatedCourseBlock">
		<h3>関連講座</h3>
	</div>
	<!--{if empty($arr_related_list)}-->
		<div class="nonProductMsg">
		関連講座はありません。
		</div>
	<!--{else}-->
		<div style="clear:both;">
			<ul style="list-style:none;margin:0;">
			<!--{foreach name=related_products from=$arr_related_list item=related_products}-->
				<a href="/product/detail.php?pid=<!--{$related_products.pid|escape}-->" alt="<!--{$related_products.name|escape}-->" title="<!--{$related_products.name|escape}-->"><!--{$related_products.name|escape}--></a><br>
				<!--
				<li style="float:left;width:90px;padding:5px;text-align:center;<!--{if $smarty.foreach.related_products.index==5}-->clear:both;<!--{/if}-->">
				<!--{if $related_products.thumbnail != ''}-->
					<a href="/product/detail.php?pid=<!--{$related_products.pid|escape}-->" alt="<!--{$related_products.name|escape}-->" title="<!--{$related_products.name|escape}-->"><img src="/resize_image.php?image=<!--{$related_products.thumbnail|escape}-->&width=90&height=90" alt="" /></a><br />
				<!--{else}-->
					<a href="/product/detail.php?pid=<!--{$related_products.pid|escape}-->" alt="<!--{$related_products.name|escape}-->" title="<!--{$related_products.name|escape}-->"><img src="/resize_image.php?image=noimage.jpg&width=90&height=90" alt="" /></a><br />
				<!--{/if}-->
				<a href="/product/detail.php?pid=<!--{$related_products.pid|escape}-->" alt="<!--{$related_products.name|escape}-->" title="<!--{$related_products.name|escape}-->"><!--{$related_products.name|mb_truncate:14:"..."|escape}--></a><br />
				<!--
				単品価格：<!--{$related_products.price_intax|escape|number_format}-->円<br />
				受講期間：
					<!--{if $related_products.start_date == '' && $related_products.end_date == ''}-->
						未定
					<!--{else}-->
						<!--{$related_products.start_date|escape}-->～<!--{$related_products.end_date|escape}-->
					<!--{/if}-->
				</li>
				-->
			<!--{/foreach}-->
			</ul>
		</div>
	<!--{/if}-->
<!--{/if}-->

<!--{* 動画視聴ボタン用form *}-->
<form name="playerForm" action="#" method="post">
	<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
	<input type="hidden" name="pid" value="<!--{$pid}-->" />
	<input type="hidden" name="vid" id="hid_vid" value="" />
	<input type="hidden" name="vid2" id="hid_vid2" value="" />
	<input type="hidden" name="codec" id="hid_codec" value="" />
	<input type="hidden" name="ftn" id="hid_ftn" value="" />
	<input type="hidden" name="ccno" id="hid_ccno" value="" />
	<input type="hidden" name="view_btn" id="hid_view_btn" value="" />
</form>

<!--{* 資料ダウンロードボタン用form *}-->
<form name="downloadForm" action="#" method="post">
<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
<input type="hidden" name="pid" value="<!--{$pid}-->" />
<input type="hidden" name="cdname" id="hid_cdname" value="" />
</form>

<style type="text/css">
.detail_btn {
	text-align: left;
	display: inline-block;
}
.btn_plyer{
	display:block;
	height:30px;
	cursor:pointer;	
	width: 166px;
	margin-bottom: 8px;
	float: left;
	margin-right: 8px;
}
.btn_plyer_text{
	display:block;
	margin-left:30px;
	line-height:30px;
	font-size:12px;
	color:#666666;
}
.btn_plyer:hover{
	opacity:0.6;
}
</style>
