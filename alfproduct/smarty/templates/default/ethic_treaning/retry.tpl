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
			【設問・解説動画一覧】<br />
			設問【11】～設問【16】の設問を受け、解説動画を全て視聴するまで、結果は判定されません。<br />
			全ての設問を受け解説動画を全て視聴した後、<b>必ず</b>「結果判定」ボタンを押して、結果を確認してください。<br />
			<font color="red"><b>※「結果判定」のボタンを押し、結果を確認しないと、合格していても受講履歴が反映されませんのでご注意ください。</b></font>

			<table style="width:100%;margin-top:20px;" class="member_table" cellspacing="0" cellpadding="0">
				<tr>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">&nbsp;</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問</th>
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">解説動画(再生時間)</th>
				</tr>
				<!--{foreach name=loop from=$arr_list item="row" key="key"}-->
				<tr style="background-color: #FFFFFF;">
					<th style="border:solid 1px #AB9983;background-color: #F5F8EF;padding:5px;text-align:center;">設問【<!--{$row.question_no|escape|string_format:"%02d"}-->】</th>
					<td style="background-color: #FFFFFF;border:solid 1px #AB9983;padding:5px;text-align:center;">
						<table><tr>
							<td style="text-align:center;width:180px;">
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<!--{if $row.test_flg}-->
									<a href="/ethic_treaning/question_retry.php?pid=<!--{$pid|escape}-->&qid=<!--{$key|escape}-->"><!--
										--><img src="/img/lecture/test_btn.png"><!--
									--></a>
								<!--{else}-->
									<a href="/ethic_treaning/answer_history.php?pid=<!--{$pid|escape}-->&qid=<!--{$key|escape}-->"><!--
										--><img src="/img/lecture/answer_btn.png"><!--
									--></a>
								<!--{/if}-->
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
								<!--{if $row.answer_ethic_branch_id!=''}-->
									<!--{if !$is_sp}-->
									<a href="javascript: void(0);" onclick="playerEthicCommentaryFormSubmit('<!--{$row.video_id|escape}-->');return false;">
										<img src="/img/lecture/commentary_btn.png" alt="解説動画" style="cursor:pointer;" />
									</a>
									<!--{/if}-->
									(<!--{$row.duration|escape}-->)
								<!--{else}-->
									<!--{if !$is_sp}-->
									<img src="/img/lecture/commentary_btn_02.png">
									<!--{/if}-->
									(<!--{$row.duration|escape}-->)
								<!--{/if}-->
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
							</td><td style="text-align:center;width:80px;">
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
								<!--{if $row.disp_view=="視聴完了"}-->
									<img src="/img/lecture/end_viwe.png">
								<!--{elseif $row.disp_view=="視聴中"}-->
									<img src="/img/lecture/now_viwe.png">
								<!--{elseif $row.disp_view=="未視聴"}-->
									<img src="/img/lecture/no_viwe.png">
								<!--{/if}-->
								<!--{*$row.disp_view|escape*}-->
								<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
							</td>
						</tr></table>
					</td>
				</tr>
				<!--{/foreach}-->
			</table>

			<div style="padding-top:10px;">
				※受講状況（受講率等）の表示は、１日１回更新されます。<br />
				<!--{if $is_sp}-->
				<span style="color:red; font-weight:bold">※スマートフォン・タブレットからは視聴できません。</span><br />
				<!--{/if}-->
				※「結果判定」ボタンは、全ての設問を受け解説動画を全て視聴した後に表示されます。<br />　表示されない場合は、「Ctrl+F5」等でページの更新を行ってください。
			</div>

			<div style="text-align:center;padding:20px;">
				<a href="/product/detail.php?pid=<!--{$pid|escape}-->"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a>
				<!--{if $hantei_flg}-->
				<a href="/ethic_treaning/result_retry.php?pid=<!--{$pid|escape}-->"><img src="/img/lecture/result_btn.png" alt="結果判定" /></a>
				<!--{/if}-->
			</div>

		</div>
	</div>
</div>

<!--{* 動画視聴ボタン用form *}-->
<form name="playerForm" action="#" method="post">
<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
<input type="hidden" name="vid" id="hid_vid" value="" />
<input type="hidden" name="pid" id="hid_pid" value="<!--{$pid|escape}-->" />
<input type="hidden" name="back_type" id="hid_back_type" value="retry" />
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
