<?php /* Smarty version 2.6.27, created on 2017-10-15 17:23:53
         compiled from /srv/alfproduct/smarty/templates/smartphone/exam/answer_check2.tpl */ ?>
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
	<?php if (! $this->_tpl_vars['err_flg']): ?>
		提出してもよろしいですか？<br><br>
		解答内容を確認する場合は「キャンセル」して解答一覧をご確認ください。
	<?php else: ?>
		エラーが発生しました。
	<?php endif; ?>
</div>

<?php if (! $this->_tpl_vars['err_flg']): ?>
	<div style="width:420px;">
		<a class="btn1" href="/exam/answer_save2.php?pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
&eid=<?php echo $this->_tpl_vars['eid']; ?>
<?php if ($this->_tpl_vars['qid'] != ''): ?>&qid=<?php echo $this->_tpl_vars['qid']; ?>
<?php endif; ?>">提出する</a>
		<a class="btn2" href="javascript:void(0)" onclick="window.close();">キャンセル</a>
	</div>
<?php else: ?>
	<div style="width:210px;">
		<a class="btn2" href="javascript:void(0)" onclick="window.close();">キャンセル</a>
	</div>
<?php endif; ?>
</center>