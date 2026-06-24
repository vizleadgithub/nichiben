<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_material_input','授業に使用する資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
				</div>

				<h2><?= $this->lang->line_or_def('msg_class_commit','授業情報更新') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?></h3>
				<br /><br />
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
