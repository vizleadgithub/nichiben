<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);
?>

<style type="text/css">
	.select_change_or_default, .select_change_or_default INPUT[type='radio']{
		height		: 22px;
		line-height	: 22px;
	}
</style>
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
				<h2>
					<?= $this->lang->line_or_def('msg_school_photo_caption','受講者がログインする際のログインページ画像の設定が可能です') ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?= $this->lang->line_or_def('msg_school_photo_sample','ログイン画像のサンプル') ?>&nbsp;&gt;&gt;&nbsp;<a href="/static/psd/background_custom_base.zip">Download</a>
				</h2>

				<?=form_open_multipart("/admin_top/school_photo/")?>
					<? if($error){ print $this->upload->display_errors('<div class="error">', '</div>'); } ?>
					<table class="form">
						<tr>
							<th width="200"><?= $this->lang->line_or_def('common_school_photo_now','現在設定済みの画像') ?></th>
							<td>
								<?
									$imgUrl = "";
									$service_env = getenv('URL_SERVICE');
									if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
										$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/login/background_{$service_env}.png";
										if(file_exists($this->config->item('login_image_dir').'/background_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
											$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/login/background_{$service_env}_{$this->libauth->get_school_id()}.png";
										}
									}else{
										$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/login/background.png";
										if(file_exists($this->config->item('login_image_dir').'/background_'.$this->libauth->get_school_id().'.png')){
											$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/login/background_{$this->libauth->get_school_id()}.png";
										}
									}
								?>
								<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" width="auto" height="80" border="1">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('msg_school_photo_select', '設定する画像を選択して下さい') ?></th>
							<td>
								<div class="select_change_or_default">
									<input type="radio" name="change_type" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
									<input type="radio" name="change_type" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
								</div>
								<input type="file" name="upload_file" size="30" value="">
								<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
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
