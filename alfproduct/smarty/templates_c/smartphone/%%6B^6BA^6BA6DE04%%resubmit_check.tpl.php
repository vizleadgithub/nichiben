<?php /* Smarty version 2.6.27, created on 2020-02-12 20:48:48
         compiled from /srv/alfproduct/smarty/templates/smartphone/exam2/resubmit_check.tpl */ ?>
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

<?php echo $this->_tpl_vars['return']['formhtml']; ?>

<div id="contents">
	<?php echo $this->_tpl_vars['return']['html']; ?>

</div>
<center>
	<?php if ($this->_tpl_vars['return']['return_check']): ?>
	<div style="font-size: 16px;">
		この内容で提出してもよろしいですか？
	</div>
	<div style="width:420px;">
		<a class="btn1" href="javascript:void(0)" onclick="pop_get_html_sub('/exam2/resubmit_exec.php?e2id=<?php echo $this->_tpl_vars['e2id']; ?>
&pid=<?php echo $this->_tpl_vars['pid']; ?>
','form_regist_answer')">提出する</a>
		<a class="btn2" href="javascript:void(0)" onclick="pop_close_sub()">戻る</a>
	</div>
	<?php else: ?>
	<div style="width:200px;">
		<a class="btn2" href="javascript:void(0)" onclick="pop_close_sub()">戻る</a>
	</div>
	<?php endif; ?>
</center>