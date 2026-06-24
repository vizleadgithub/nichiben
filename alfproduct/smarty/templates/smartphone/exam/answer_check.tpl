<style type="text/css">
#contents{
width:100%;
height:500px;
scroll-y:auto;
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
<div>
	この内容で提出してもよろしいですか？
</div>
<div style="width:420px;">
	<a class="btn1" href="javascript:void(0)" onclick="form_regist_answer.submit();">提出する</a>
	<a class="btn2" href="javascript:void(0)" onclick="window.close();">キャンセル</a>
</div>
</center>
