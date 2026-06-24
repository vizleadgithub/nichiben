<?php
	$this->lang->load('common');
?>
<div id="menu_sub" class="clearfix">
	<ul>
		<li<? if($selected == 'category'): ?> class="selected"<? endif; ?>><a href="/cms_category/"><?= $this->lang->line_or_def('common_category','カテゴリ') ?></a></li>
	</ul>
</div>
