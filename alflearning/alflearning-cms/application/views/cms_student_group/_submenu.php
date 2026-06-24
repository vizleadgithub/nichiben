<?php
	$this->lang->load('common');
?>

<div id="menu_sub" class="clearfix">
	<ul>
		<li><a href="/cms_student/"><?= $this->lang->line_or_def('common_student','受講者') ?></a></li>
		<li class="selected"><a href="/cms_student_group/"><?= $this->lang->line_or_def('common_group','グループ') ?></a></li>
	</ul>
</div>
