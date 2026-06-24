<?php
	$this->lang->load('common');
	$this->lang->load('msg');
	$this->lang->load('error');
?>

<?php
	$data['callview'] = "material";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_material','資料管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_material_comment','資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_material/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_material/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_material/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_material_commit','資料情報更新') ?></h2>
				<?php
					if(!isset($upload_error)){
				?>
				<h3>
					<?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?>
				</h3>
				<?php
					} else {
				?>
				<h3 class="error">
					<?= $this->lang->line_or_def('error_book_library_upload_1','登録は完了しましたが、アップロードでエラーが発生しました。') ?>
				</h3>
				<?=$upload_error?>
				<h3 class="error">
					<?= $this->lang->line_or_def('error_book_library_upload_2','該当資料を削除後、再度新規登録をしてください。') ?>
				</h3>
				<?php
					}
				?>

				<div class="submit">
					<a href="<?=site_url('cms_material')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
