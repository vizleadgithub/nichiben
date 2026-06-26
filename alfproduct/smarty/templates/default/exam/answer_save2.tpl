<style type="text/css">
#contents{
	width:100%;
	height:450px;
	padding-top:50px;
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
}
</style>

<center>
<div id="contents">
	<!--{if !$err_flg}-->
		提出しました。
	<!--{else}-->
		エラーが発生しました。
	<!--{/if}-->
</div>
<div>
	<a class="btn" href="javascript:void(0)" onclick="<!--{if !$err_flg}-->opener.location.href='/exam/result2.php?eid=<!--{$eid|escape:'javascript'}-->&pid=<!--{$pid|escape:'javascript'}-->&ccno=<!--{$ccno|escape:'javascript'}--><!--{if $qid!=''}-->&qid=<!--{$qid|escape:'javascript'}--><!--{/if}-->';<!--{/if}-->window.close();">閉じる</a>
</div>
</center>
