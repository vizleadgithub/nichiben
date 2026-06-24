<?php
	$this->lang->load('common');
?>

<div id="menu_sub" class="clearfix">
	<ul>
		<li><a href="/cms_cource_class/"><?= $this->lang->line_or_def('common_list_of_class','直近の授業一覧') ?></a></li>
<!--	<li><a href="/cms_cource/"><?= $this->lang->line_or_def('common_course','講座') ?></a></li>	-->
		<li class="selected"><a href="/cms_class/"><?= $this->lang->line_or_def('common_class','授業') ?></a></li>
	</ul>
</div>
