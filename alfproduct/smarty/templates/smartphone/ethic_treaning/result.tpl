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
				<!--結果判定-->
			</div>
		</div>
		<div style="float:left;font-size:16px;line-height: 22px;color:#525252;width:880px;margin-left:15px;margin-top:20px;border: none;margin-bottom:20px;">

			<div style="text-align:center;padding:50px;">
			<!--{if $passed_flg}-->
				<!--合格-->
				<br />
				<br />
				<!--{$product_name|escape}-->の受講が終了しました。
			<!--{else}-->
				不合格（テストの正答数が６問以下）
				<br />
				<br />
				講座詳細ページに戻り，追試を受けてください。
			<!--{/if}-->
			<br />判定日時：<!--{$judge_date|escape}-->
			</div>

			<div style="text-align:center;padding:20px;">
				<a href="/product/detail.php?pid=<!--{$pid|escape}-->"><img src="/img/lecture/detail_back_btn.png" alt="講座詳細ページに戻る" /></a>
			</div>
		</div>
	</div>
</div>
