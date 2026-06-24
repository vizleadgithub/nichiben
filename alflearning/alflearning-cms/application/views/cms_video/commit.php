<?php
	$this->lang->load('common');
	$this->lang->load('msg');

	$data['callview'] = "video";
	$this->load->view('header/header',$data);
?>
	<!-- head --></head>

	<body>
		<?php 
		$this->load->view('header/body_header', array());
		?>

		<div id="wrapper">
			<h1 class="claerfix">
				<div class="title"><?= $this->lang->line_or_def('common_heading_video','ビデオ授業管理') ?></div>
				<div class="comment"><?= $this->lang->line_or_def('msg_video_comment','ビデオ授業用のビデオファイルを管理します') ?></div>
			</h1>
			<div id="main">
				<? $this->load->view('cms_video/_submenu', array());?>

				<div id="contents_main">
					<div class="toolbar clearfix">
						<a class="btn_seach selected" href="/cms_video/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
						<a class="btn_add" href="/cms_video/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
					</div>

					<h2><?= $this->lang->line_or_def('msg_video_commit','ビデオ情報更新') ?></h2>
					<h3><?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?></h3>

					<div class="submit">
						<a href="<?=site_url('cms_video')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php $this->load->view('header/body_footer');?>
	</body>
</html>
