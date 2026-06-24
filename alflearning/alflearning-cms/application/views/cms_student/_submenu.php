<?php
	$this->lang->load('common');
?>

<div id="menu_sub" class="clearfix">
	<ul>
		<li class="selected"><a href="/cms_student/"><?= $this->lang->line_or_def('common_student','受講者') ?></a></li>
	<!--<li><a href="/cms_student_group/"><?=''; //$this->lang->line_or_def('common_group','グループ') ?></a></li>-->
		<? $login_bar_association_id = $this->libauth->get_bar_association_id(); ?>
		<? if($login_bar_association_id == 1): ?>
			<li><a href="/cms_student_sub_auth/" style="font-size: 16px;"><?= $this->lang->line_or_def('common_','代替倫理研修権限')."*" ?></a></li>
			<li><a href="/cms_student_csv_upload/" style="font-size: 16px;"><?= $this->lang->line_or_def('common_','メンテナンス')."*" ?></a></li>
		<? endif; ?>
	</ul>
</div>
