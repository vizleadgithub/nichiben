<!--{*
<div class="pankuzu" style="color:#4b3921;font-size:14px;">
<ul>
<li><a href="/">TOP</a></li>
<li><img style="height:10px;padding:0 5px;" alt="＞" src="/img/c_ar_2.png"></li>
<li><a href="">日弁連倫理研修</a></li>
</ul>
</div>
*}-->

<div style="float:right;width:980px;border: solid 1px #EDECE0;background-color:#FFFFFF;">
	<div style="float:left;width:940px;height:36px;background-image: url( /img/lecture/h2_back.png );margin-left:10px;margin-top:20px;">
		<span style="font-size:17px;color:#5E4C33;font-weight: bold;padding-left: 10px;">日弁連倫理研修</span>
	</div>

	<div style="float:left;width:910px;margin-left:25px;margin-top:20px;border: solid 1px #EDECE0;border-style: none none solid;">
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			あなたの各設問の結果等が確認できます。

			<div style="padding:20px 0;">
				<div style="text-align:center;">
					<!--<!--{$str_result|escape}-->-->
					<br />
					<br />
					<!--{if $judge_flg == 1}-->
						<!--{$product_name|escape}-->の受講が終了しました。
					<!--{elseif $judge_flg == 2}-->
						講座詳細ページに戻り、追試を受けてください。
					<!--{elseif $judge_flg == 3}-->
						<!--{$product_name|escape}-->の受講が終了しました。
					<!--{elseif $judge_flg == 4}-->
						レポートを提出してください。<br />
						レポート課題は、後日、日弁連よりご連絡いたします。
					<!--{/if}-->
					<!--{if $judge_flg == 5 || $judge_flg == 6}-->
						日弁連倫理研修（レポート）の受講が終了しました。
					<!--{else}-->
						<br />判定日時：<!--{$judge_date|escape}-->
					<!--{/if}-->
				</div>
			</div>
<!--{if $judge_flg == 5}-->
	<table style="text-align:center;margin:20px 0 0 240px;">
	<tr>
	<td style="padding:10px;"><a href="/product/detail.php?pid=<!--{$pid}-->"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a></td>
	</tr>
	</table>
<!--{else}-->
			<!--{if !empty($arr_list)}-->
				<div style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;padding:15px;font-size:18px;font-weight:bold;clear:both;text-align:center;background-color:#F0F8FF;">倫理研修テスト</div>
				<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
					<tr>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">&nbsp;</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">テスト</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">解説動画(再生時間)</th>
					</tr>
					<!--{foreach name=loop from=$arr_list item="row" key="key"}-->
					<!--{if $row.complete_flag == '1' && $row.answer_ethic_branch_id != ''}-->
						<!--{assign var=row_no value=$smarty.foreach.loop.iteration}-->
						<tr style="background-color: #FFFFFF;">
							<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問【<!--{$row_no|escape|string_format:"%02d"}-->】</th>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:180px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<a href="/ethic_treaning/result_history_detail.php?pid=<!--{$pid}-->&qid=<!--{$key|escape}-->"><!--
											--><img src="/img/lecture/answer_btn.png"><!--
										--></a>
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:100px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!--{if $row.disp_answer=="正答"}-->
											<img src="/img/lecture/ok_test.png">
										<!--{elseif $row.disp_answer=="誤答"}-->
											<img src="/img/lecture/ng_test.png">
										<!--{elseif $row.disp_answer=="未回答"}-->
											<img src="/img/lecture/no_test.png">
										<!--{/if}-->
										<!--{*$row.disp_answer|escape*}-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:280px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!--{if !$is_sp}-->
										<a href="javascript: void(0);" onclick="playerEthicCommentaryFormSubmit('<!--{$row.video_id|escape}-->');return false;"><img src="/img/lecture/commentary_btn.png"></a>
										<!--{/if}-->
										(<!--{$row.duration|escape}-->)
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:80px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<img src="/img/lecture/end_viwe.png">
										<!--{*$row.disp_view|escape*}-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
						</tr>
					<!--{/if}-->
					<!--{/foreach}-->
				</table>
			<!--{/if}-->
			
			<!--{if !empty($arr_list_add)}-->
				<div style="border-top:solid 1px #000000;border-bottom:solid 1px #000000;margin-top:40px;padding:15px;font-size:18px;font-weight:bold;clear:both;text-align:center;background-color:#F0F8FF;">倫理研修追試</div>
				<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
					<tr>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">&nbsp;</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">テスト</th>
						<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">解説動画(再生時間)</th>
					</tr>
					<!--{foreach name=loop from=$arr_list_add item="row" key="key"}-->
					<!--{if $row.complete_flag == '1' && $row.answer_ethic_branch_id != ''}-->
						<!--{assign var=row_no value=$smarty.foreach.loop.iteration+10}-->
						<tr style="background-color: #FFFFFF;">
							<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問【<!--{$row_no|escape|string_format:"%02d"}-->】</th>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:180px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<a href="/ethic_treaning/result_history_detail.php?pid=<!--{$pid}-->&qid=<!--{$key|escape}-->"><!--
											--><img src="/img/lecture/answer_btn.png"><!--
										--></a>
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:100px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!--{if $row.disp_answer=="正答"}-->
											<img src="/img/lecture/ok_test.png">
										<!--{elseif $row.disp_answer=="誤答"}-->
											<img src="/img/lecture/ng_test.png">
										<!--{elseif $row.disp_answer=="未回答"}-->
											<img src="/img/lecture/no_test.png">
										<!--{/if}-->
										<!--{*$row.disp_answer|escape*}-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
							<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
								<table><tr>
									<td style="text-align:center;width:280px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<!--{if !$is_sp}-->
										<a href="javascript: void(0);" onclick="playerEthicCommentaryFormSubmit('<!--{$row.video_id|escape}-->');return false;"><img src="/img/lecture/commentary_btn.png"></a>
										<!--{/if}-->
										(<!--{$row.duration|escape}-->)
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td><td style="text-align:center;width:80px;">
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
										<img src="/img/lecture/end_viwe.png">
										<!--{*$row.disp_view|escape*}-->
										<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
									</td>
								</tr></table>
							</td>
						</tr>
					<!--{/if}-->
					<!--{/foreach}-->
				</table>
			<!--{/if}-->

			<div style="padding-top:10px;">
				※受講状況（受講率等）の表示は、１日１回更新されます。<br />
				<!--{if $is_sp}-->
				<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span>
				<!--{/if}-->
			</div>

			<table style="text-align:center;margin:20px 0 0 60px;">
			<tr>
			<td style="padding:10px;"><a href="/product/detail.php?pid=<!--{$pid}-->"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a></td>
			<td style="padding:10px;"><a href="/ethic_treaning/answer_history_all.php?pid=<!--{$pid}-->"><img src="/img/lecture/zen_kaito_ichiran.png" alt="全回答内容一覧" /></a></td>
			</tr>
			</table>
<!--{/if}-->
		</div>
	</div>
</div>
<!--{* 動画視聴ボタン用form *}-->
<form name="playerForm" action="#" method="post">
<input type="hidden" name="vid" id="hid_vid" value="" />
<input type="hidden" name="pid" id="hid_pid" value="<!--{$pid|escape}-->" />
<input type="hidden" name="back_type" id="hid_back_type" value="result_history" />
</form>

<script type="text/javascript">
function playerEthicCommentaryFormSubmit(vid,ftn,ccno,view_btn){
    var w = window.open("about:blank","playerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
    setTimeout(function(){
        w.onLoad = playerEthicCommentaryOpenWindowSubmit(vid,ftn,ccno,view_btn);
    }, 1000);
}
function playerEthicCommentaryOpenWindowSubmit(vid,ftn,ccno,view_btn){
    document.getElementById("hid_vid").value = vid;
    document.playerForm.target = "playerDisp";
    document.playerForm.method = "post";
    document.playerForm.action = "/player/player_ethic_commentary.php?term=pc";
    document.playerForm.submit();
}
</script>
