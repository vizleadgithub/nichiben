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
		<!--{if $answer_btn_disp_flg}-->
			<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
				<div style="width:100%;border-bottom:solid 1px #000000;">設問</div>
				<!--{$question}-->
			</div>


			<div style="width:100%;float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border-bottom:solid 1px #000000;">【選択肢】</div>
			<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
				<form action="/ethic_treaning/question_answer_retry.php?pid=<!--{$pid|escape}-->&qid=<!--{$qid}-->" method="post" name="form_answer">
					<input type="hidden" name="csrf_token" value="<!--{$csrf_token|escape}-->" />
					<div style="width:100%;">
						<!--{html_radios name='ethic_branch_id' options=$arr_list separator='<br />'}-->
					</div>
					<!--{if $reference!=''}-->
						<div style="width:100%;padding-top:10px;">
							<!--{$reference}-->
						</div>
					<!--{/if}-->
					<ul style="text-align:center;padding:20px;list-style:none;padding-left:280px;">
						<li style="float:left;padding:0 20px;"><a href="javascript:void(0);" onclick="javascript:location.href='/ethic_treaning/retry.php?pid=<!--{$pid|escape}-->';"><img src="/img/button/question1_btn.png" alt="今は回答しない" /></a></li>
						<li style="float:left;padding:0 20px;"><a href="javascript:void(0);" onclick="javascript:document.form_answer.submit();"><img src="/img/button/question2_btn.png" alt="回答する" /></a></li>
					</ul>
				</form>
			</div>
		<!--{else}-->
			<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
				<div style="text-align:center;">回答済みの問題です。</div>
			</div>
		<!--{/if}-->
	</div>
</div>
<script type="text/javascript">
window.onunload = function(){};
history.forward();
</script>
