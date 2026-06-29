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
			<div style="width:100%;border-bottom:solid 1px #000000;">設問【<!--{$question_no|escape|string_format:"%02d"}-->】</div>
			<!--{$question}-->
			<!--{if $reference!=''}-->
				<p>
				</p>
				<p>
					<!--{$reference}-->
				</p>
			<!--{/if}-->
		</div>
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">
			<form action="/ethic_treaning/question_answer.php?pid=<!--{$pid|escape}-->&qid=<!--{$qid}-->" method="post" name="form_answer">
				<div style="width:100%;">
					<!--{$str_answer|escape}-->
					<br />
					<br />
					解説動画を必ず視聴してください。
				</div>
				<div style="text-align:center;padding:20px;">
					<input type="button" value="設問・解説動画一覧に戻る" onclick="javascript:location.href='/ethic_treaning/?pid=<!--{$pid|escape}-->';">	
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
window.onunload = function(){};
history.forward();
</script>
