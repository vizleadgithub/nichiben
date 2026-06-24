<style type="text/css">
#contents{
width:100%;
scroll-y:auto;
font-size: 16px;
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

<!--{$return.formhtml}-->
<div id="contents">
	<!--{$return.html}-->
</div>
<center>
	<!--{if $return.return_check}-->
	<div style="font-size: 16px;">
		この内容で提出してもよろしいですか？
	</div>
	<div style="width:420px;">
		<a class="btn1" href="javascript:void(0)" onclick="pop_get_html_sub('/exam2/answer_save.php?e2id=<!--{$e2id}-->&pid=<!--{$pid}-->','form_regist_answer')">提出する</a>
		<a class="btn2" href="javascript:void(0)" onclick="pop_close_sub()">戻る</a>
	</div>
	<!--{else}-->
	<div style="width:200px;">
		<a class="btn2" href="javascript:void(0)" onclick="pop_close_sub()">戻る</a>
	</div>
	<!--{/if}-->
</center>
