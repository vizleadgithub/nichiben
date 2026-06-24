<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_student','受講者管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_student_comment','受講者を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_student_csv_upload/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix" style="height: 18px;">
				</div>

				<h2><?= $this->lang->line_or_def('msg_','取得してきたファイルをセットして、予約するボタンを押してください') ?></h2>
				<h3>
					<?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?>
					<br /><?= $this->lang->line_or_def('msg_','データ反映まで、しばらくお待ちください') ?>
				</h3>

				<div class="submit">
					<a href="<?=site_url('cms_student_csv_upload')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
