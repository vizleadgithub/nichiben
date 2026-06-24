<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);
?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_school_photo','ログイン画像') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_school_photo_comment','ログイン画像設定') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'	=> 'school_photo',
			));?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('common_transmission_completion','送信完了') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_school_photo_commit_result','画像の設定が完了しました') ?></h3>

				<div class="submit">
					<a href="/admin_top/school_photo/"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
