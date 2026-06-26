<style type="text/css">
#contents{
	width:100%;
	scroll-y:auto;
	font-size: 16px;
}
a.btn{
	display: block;
	text-align: center;
	vertical-align: middle;
	background: #666666;
	font-size: 16px;
	line-height: 40px;
	height: 40px;
	color: #ffffff;
	text-decoration: none;
	border-radius: 8px;
	width:200px;
	margin-left: auto;
}
a.btn1{
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
	width:200px;
	float:left;
}
a.btn2{
	display: block;
	text-align: center;
	vertical-align: middle;
	background: #666666;
	font-size: 16px;
	line-height: 40px;
	height: 40px;
	color: #ffffff;
	text-decoration: none;
	border-radius: 8px;
	width:200px;
	float:right;
}
</style>
<div id="contents">
	<!--{$return.html}-->
</div>
<center>
<div>
	<!--{if $err_flg}-->
		<span style="color:red;font-size: 16px;">エラーが発生しました。</span>
	<!--{else}-->
		<span style="color:blue;font-size: 16px;">アンケートのご協力、ありがとうございました。</span>
	<!--{/if}-->
</div>
<div style="width:200px;">
	<a class="btn" style="margin-left: 0;" href="javascript:void(0)" onclick="<!--{if !$err_flg}-->pop_get_html('/exam2/result.php?e2id=<!--{$e2id|escape:'javascript'}-->&pid=<!--{$pid|escape:'javascript'}-->');<!--{/if}-->">閉じる</a>
</div>
</center>
