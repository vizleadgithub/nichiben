<?php /* Smarty version 2.6.31, created on 2025-02-25 02:14:09
         compiled from /srv/alfproduct/smarty/templates/default/exam2/answer_save.tpl */ ?>
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
	<?php echo $this->_tpl_vars['return']['html']; ?>

</div>
<center>
<div>
	<?php if ($this->_tpl_vars['err_flg']): ?>
		<span style="color:red;font-size: 16px;">エラーが発生しました。</span>
	<?php else: ?>
		<span style="color:blue;font-size: 16px;">アンケートのご協力、ありがとうございました。</span>
	<?php endif; ?>
</div>
<div style="width:200px;">
	<a class="btn" style="margin-left: 0;" href="javascript:void(0)" onclick="<?php if (! $this->_tpl_vars['err_flg']): ?>pop_get_html('/exam2/result.php?e2id=<?php echo $this->_tpl_vars['e2id']; ?>
&pid=<?php echo $this->_tpl_vars['pid']; ?>
');<?php endif; ?>">閉じる</a>
</div>
</center>