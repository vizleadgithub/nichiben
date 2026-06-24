<style type="text/css">
#contents{
	width:100%;
	height:450px;
	padding-top:50px;
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

<center>
<div id="contents">
	<!--{if !$err_flg}-->
		提出してもよろしいですか？<br><br>
		解答内容を確認する場合は「キャンセル」して解答一覧をご確認ください。
	<!--{else}-->
		エラーが発生しました。
	<!--{/if}-->
</div>

<!--{if !$err_flg}-->
	<div style="width:420px;">
		<a class="btn1" href="/exam/answer_save2.php?pid=<!--{$pid}-->&ccno=<!--{$ccno}-->&eid=<!--{$eid}--><!--{if $qid!=''}-->&qid=<!--{$qid}--><!--{/if}-->">提出する</a>
		<a class="btn2" href="javascript:void(0)" onclick="window.close();">キャンセル</a>
	</div>
<!--{else}-->
	<div style="width:210px;">
		<a class="btn2" href="javascript:void(0)" onclick="window.close();">キャンセル</a>
	</div>
<!--{/if}-->
</center>
