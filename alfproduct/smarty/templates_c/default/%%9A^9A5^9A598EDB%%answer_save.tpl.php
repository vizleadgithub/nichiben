<?php /* Smarty version 2.6.31, created on 2026-04-03 15:39:29
         compiled from /srv/alfproduct/smarty/templates/default/exam/answer_save.tpl */ ?>
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
	<?php echo $this->_tpl_vars['return']['html']; ?>

</div>
<center>
<div>
	<?php if ($this->_tpl_vars['err_flg']): ?>
		<span style="color:red;">エラーが発生しました。</span>
	<?php else: ?>
		<span style="color:blue;">提出しました。</span>
	<?php endif; ?>
</div>
<div>
	<a class="btn" href="javascript:void(0)" onclick="<?php if (! $this->_tpl_vars['err_flg']): ?>opener.location.href='/exam/result.php?eid=<?php echo $this->_tpl_vars['eid']; ?>
&pid=<?php echo $this->_tpl_vars['pid']; ?>
&ccno=<?php echo $this->_tpl_vars['ccno']; ?>
<?php if ($this->_tpl_vars['qid'] != ''): ?>&qid=<?php echo $this->_tpl_vars['qid']; ?>
<?php endif; ?>';<?php endif; ?>window.close();">閉じる</a>
</div>
</center>