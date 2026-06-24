<?php
	$this->lang->load('common');
?>
<div id="menu_sub" class="clearfix">
	<ul>
		<?php 
		if(empty($selected)){
			$selected = 'exam2';
		}
		?>
		<li<? if($selected == 'exam2'): ?> class="selected"<? endif; ?>><a href="/cms_exam2/"><?= $this->lang->line_or_def('common_exam2','アンケート') ?></a></li>
		<li<? if($selected == 'exam2_problem'): ?> class="selected"<? endif; ?>><a href="/cms_exam2_problem/"><?= $this->lang->line_or_def('common_exam2_problem','設問') ?></a></li>
		<li<? if($selected == 'exam2_problem_group'): ?> class="selected"<? endif; ?>><a href="/cms_exam2_problem_group/"><?= $this->lang->line_or_def('common_exam2_problem_group','設問グループ') ?></a></li>
		<li<? if($selected == 'exam2_problem_import'): ?> class="selected"<? endif; ?>><a href="/cms_exam2_problem_import/" style="font-size:14px;">設問ｲﾝﾎﾟｰﾄ/ｴｸｽﾎﾟｰﾄ</a></li>
		<li<? if($selected == 'exam2_download'): ?> class="selected"<? endif; ?>><a href="/cms_exam2_download/" style="font-size:14px;">アンケート集計DL</a></li>
	</ul>
</div>
