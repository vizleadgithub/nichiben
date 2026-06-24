<?php
	$this->lang->load('common');
?>

<div id="menu_sub" class="clearfix">
	<ul>
		<li<? if($selected == 'index'): ?> class="selected"<? endif; ?>><a href="/cms_school_manage/"><?= $this->lang->line_or_def('common_school_manage_index','学校登録') ?></a></li>
		<li<? if($selected == 'report'): ?> class="selected"<? endif; ?>><a href="/cms_school_manage/report/"><?= $this->lang->line_or_def('common_school_manage_report','レポート') ?></a></li>
	</ul>
</div>
