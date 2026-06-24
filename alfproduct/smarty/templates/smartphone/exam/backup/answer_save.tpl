<style type="text/css">
#contents{
	width:100%;
	height:500px;
	scroll-y:auto;
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

<div id="contents">
	<!--{$return.html}-->
</div>
<center>
<div>
	<!--{if $err_flg}-->
		<span style="color:red;">エラーが発生しました。</span>
	<!--{else}-->
		<span style="color:blue;">提出しました。</span>
	<!--{/if}-->
</div>
<div>
	<a class="btn" href="javascript:void(0)" onclick="<!--{if !$err_flg}-->opener.location.href='/exam/result.php?eid=<!--{$eid}-->&pid=<!--{$pid}-->&ccno=<!--{$ccno}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->';<!--{/if}-->window.close();">閉じる</a>
</div>
</center>
