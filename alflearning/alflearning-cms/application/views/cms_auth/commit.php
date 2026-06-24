<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "auth";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header',array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_auth','権限管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_auth_comment','各講師の権限を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_auth/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_auth/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				</div>


				<h2><?= $this->lang->line_or_def('msg_auth_commit','講師情報更新') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?></h3>

				<div class="submit">
					<a href="<?=site_url('cms_auth')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
