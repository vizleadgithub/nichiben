<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "teacher";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_teacher','講師管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_teacher_comment','講師を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_teacher/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_teacher/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_teacher/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_teacher_photo_select','登録する写真を選択してください') ?></h2>

				<?=form_open_multipart("cms_teacher/photo_upload_exec")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?= htmlspecialchars( $upload_errors, ENT_QUOTES, 'UTF-8') ?>
					<input type=hidden name=teacher_id   value='<?=set_value('teacher_id',   $teacher['teacher_id'])?>'>
					<input type=hidden name=teacher_name value='<?=set_value('teacher_name', $teacher['teacher_name'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<?= htmlspecialchars( $teacher['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_photo_now','現在の写真') ?></th>
							<td>
								<img src="<?=site_url('master_photo/thumbnail/'.$teacher['teacher_id'])?>" height="80" width="80" border="1">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_photo_select','写真選択') ?></th>
							<td>
								<input type="file" name="upload_file" size="30" value="<?=set_value('upload_file',$teacher['upload_file'])?>">
								<br />&nbsp;(<?= $this->lang->line_or_def('common_photo_size','推奨画像サイズ') ?>&nbsp;:&nbsp;<?= htmlspecialchars( $rec_upload_pixel['width'], ENT_QUOTES, 'UTF-8') ?>px&nbsp;&times;&nbsp;<?= htmlspecialchars( $rec_upload_pixel['height'], ENT_QUOTES, 'UTF-8') ?>px
								&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->lang->line_or_def('common_photo_max_size','登録可能最大サイズ') ?>&nbsp;:&nbsp;<?= htmlspecialchars( $max_uplolad_pixel['width'], ENT_QUOTES, 'UTF-8') ?>px&nbsp;&times;&nbsp;<?= htmlspecialchars( $max_uplolad_pixel['height'], ENT_QUOTES, 'UTF-8') ?>px)&nbsp;
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_register.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
