<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "school_manage";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_school_manage','学校管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_school_manage_comment','学校を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_school_manage/_submenu', array(
				'selected'	=> 'index',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_school_manage/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_school_manage/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_school_manage_commit','学校情報更新') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?></h3>

				<?php if( $teacher_id > 0 ): ?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<td width="400"><?= $school_name ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_teacher_name','講師名') ?></th>
							<td><?= $teacher_name ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td><?= $teacher_email ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
							<td><?= $teacher_password ?></td>
						</tr>
					</table>
				<?php endif ?>

				<div class="submit">
					<a href="<?=site_url('cms_school_manage')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
