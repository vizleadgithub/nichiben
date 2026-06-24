<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>

<script type="text/javascript">
history.forward();
</script>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_comment','授業を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_class/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_class/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_class_commit','授業情報更新') ?></h2>
				<h3>
					<?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?>
					<br /><br />
					<?= $this->lang->line_or_def('msg_commit_detail_resume_add_material','続けて、授業に資料を新たに追加する場合は、'); ?><a href="/cms_class_material/add_material/<?= $prev_id ?>">こちら</a>
					<br /><br />
					<?= $this->lang->line_or_def('msg_commit_detail_resume_set_material','既存の資料を追加する場合は、'); ?><a href="/cms_class_material/material_select/<?= $prev_id ?>">こちら</a>
				</h3>
				<br /><br /><br />

				<div class="submit">
					<a href="<?=site_url('cms_class')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
