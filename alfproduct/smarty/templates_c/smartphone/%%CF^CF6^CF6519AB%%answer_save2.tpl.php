<?php /* Smarty version 2.6.27, created on 2017-10-15 17:23:59
         compiled from /srv/alfproduct/smarty/templates/smartphone/exam/answer_save2.tpl */ ?>
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
	<?php if (! $this->_tpl_vars['err_flg']): ?>
		提出しました。
	<?php else: ?>
		エラーが発生しました。
	<?php endif; ?>
</div>
<div>
	<a class="btn" href="javascript:void(0)" onclick="<?php if (! $this->_tpl_vars['err_flg']): ?>opener.location.href='/exam/result2.php?eid=<?php echo $this->_tpl_vars['eid']; ?>
&pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
<?php if ($this->_tpl_vars['qid'] != ''): ?>&qid=<?php echo $this->_tpl_vars['qid']; ?>
<?php endif; ?>';<?php endif; ?>window.close();">閉じる</a>
</div>
</center>