<?php
	$this->lang->load('common');
?>
<div id="menu_sub" class="clearfix">
	<ul>
		<?php 
		if(empty($selected)){
			$selected = 'exam_problem';
		}
		?>
		<li<? if($selected == 'exam'): ?> class="selected"<? endif; ?>><a href="/cms_exam/"><?= $this->lang->line_or_def('common_exam','問題（テスト）') ?></a></li>
		<li<? if($selected == 'exam_problem'): ?> class="selected"<? endif; ?>><a href="/cms_exam_problem/"><?= $this->lang->line_or_def('common_exam_problem','設問') ?></a></li>
		<li<? if($selected == 'exam_problem_group'): ?> class="selected"<? endif; ?>><a href="/cms_exam_problem_group/"><?= $this->lang->line_or_def('common_exam_problem_group','設問グループ') ?></a></li>
		<li<? if($selected == 'exam_problem_import'): ?> class="selected"<? endif; ?>><a href="/cms_exam_problem_import/" style="font-size:14px;">設問ｲﾝﾎﾟｰﾄ/ｴｸｽﾎﾟｰﾄ</a></li>
	</ul>
</div>
