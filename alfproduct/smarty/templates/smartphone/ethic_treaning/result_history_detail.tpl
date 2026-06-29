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
			<div style="width:100%;border-bottom:solid 1px #000000;">
				設問【<!--{$question_no|escape|string_format:"%02d"}-->】
				回答日：<!--{$history_answer.answer_date|escape}-->
			</div>
			<!--{$question|escape}-->
			<!--{if $reference!=''}-->
				<p>
				</p>
				<p>
					<!--{$reference|escape}-->
				</p>
			<!--{/if}-->
		</div>
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<form action="/ethic_treaning/question_answer.php?pid=<!--{$pid|escape}-->&qid=<!--{$qid|escape}-->" method="post" name="form_answer">
				<div style="width:100%;">
					<div style="width:100%;border-bottom:solid 1px #000000;">【選択肢】</div>

					<table style="width:100%;text-align:left;">
						<!--{foreach from=$arr_list key=key item=item}-->
						<!--{if $history_answer.answer_ethic_branch_id==$key && $item.answer_flg==1}-->
							<!--{assign var=style_background value='background-color:#f2dddc;'}-->
							<!--{assign var=str_disp  value='<span style="color:#00b050;">あなたの回答</span><br /><span style="color:#ff0000;">正答</span>'}-->
						<!--{elseif $history_answer.answer_ethic_branch_id==$key}-->
							<!--{assign var=style_background value='background-color:#d7e4bc;'}-->
							<!--{assign var=str_disp  value='<span style="color:#00b050;">あなたの回答</span>'}-->
						<!--{elseif $item.answer_flg==1}-->
							<!--{assign var=style_background value='background-color:#f2dddc;'}-->
							<!--{assign var=str_disp  value='<span style="color:#ff0000;">正答</span>'}-->
						<!--{else}-->
							<!--{assign var=style_background value=''}-->
							<!--{assign var=str_disp  value=''}-->
						<!--{/if}-->
						<tr style="border-bottom:solid 1px #000000;<!--{$style_background}-->">
							<th style="vertical-align:middle;width:150px;"><!--{$str_disp}--></th>
							<td style="vertical-align:middle;"><!--{$item.question_branch|escape}--></td>
						</tr>
						<!--{/foreach}-->
					</table>
				</div>
				<div style="text-align:center;padding:20px;">
					<input type="button" value="設問・解説動画一覧に戻る" onclick="javascript:location.href='/ethic_treaning/result_history.php?pid=<!--{$pid|escape}-->';">	
				</div>
			</form>
		</div>
	</div>
</div>
