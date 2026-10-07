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
			<div class="title"><?= $this->lang->line_or_def('common_heading_menu_photo','メニュー画像') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_menu_photo_comment','メニュー画像設定') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'	=> 'school_menu',
			));?>

			<div id="contents_main">
				<h2>
					<?= $this->lang->line_or_def('msg_menu_photo_caption','受講者メニューに表示するボタン画像の設定の設定が可能です') ?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?= $this->lang->line_or_def('msg_menu_photo_sample','ボタン画像のサンプル') ?>&nbsp;&gt;&gt;&nbsp;<a href="/static/psd/btn_template.zip">Download</a>
				</h2>

				<?=form_open_multipart("/admin_top/school_menu/")?>
					<? if($error){ print $this->upload->display_errors('<div class="error">', '</div>'); } ?>
					<table class="form">
						<tr>
							<th></th>
							<th><?= $this->lang->line_or_def('common_school_photo_now','現在設定済みの画像') ?></th>
							<th><?= $this->lang->line_or_def('msg_menu_photo_select','設定する画像を選択して下さい') ?></th>
						</tr>
						<? if($menuFlag['live'] == 1): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_menu_live','生授業') ?></th>
								<td>
									<?
										$imgUrl = "";
										$service_env = getenv('URL_SERVICE');
										if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_live_{$service_env}.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_live_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_live_{$service_env}_{$this->libauth->get_school_id()}.png";
											}
										}else{
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_live.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_live_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_live_{$this->libauth->get_school_id()}.png";
											}
										}
									?>
									<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
								</td>
								<td style="vertical-align:top;">
									<div class="select_change_or_default">
										<input type="radio" name="change_type_live" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
										<input type="radio" name="change_type_live" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
									</div>
									<input type="file" name="upload_file_live" size="30" value="">
									<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
								</td>
							</tr>
						<? endif; ?>
						
						<? if($menuFlag['video'] == 1): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_menu_video','ビデオ授業') ?></th>
								<td>
									<?
										$imgUrl = "";
										$service_env = getenv('URL_SERVICE');
										if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_video_{$service_env}.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_video_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_video_{$service_env}_{$this->libauth->get_school_id()}.png";
											}
										}else{
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_video.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_video_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_video_{$this->libauth->get_school_id()}.png";
											}
										}
									?>
									<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
								</td>
								<td style="vertical-align:top;">
									<div class="select_change_or_default">
										<input type="radio" name="change_type_video" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
										<input type="radio" name="change_type_video" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
									</div>
									<input type="file" name="upload_file_video" size="30" value="">
									<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
								</td>
							</tr>
						<? endif; ?>
						
						<? if($menuFlag['library'] == 1): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_menu_library','図書室') ?></th>
								<td>
									<?
										$imgUrl = "";
										$service_env = getenv('URL_SERVICE');
										if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_library_{$service_env}.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_library_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_library_{$service_env}_{$this->libauth->get_school_id()}.png";
											}
										}else{
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_library.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_library_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_library_{$this->libauth->get_school_id()}.png";
											}
										}
									?>
									<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
								</td>
								<td style="vertical-align:top;">
									<div class="select_change_or_default">
										<input type="radio" name="change_type_library" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
										<input type="radio" name="change_type_library" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
									</div>
									<input type="file" name="upload_file_library" size="30" value="">
									<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
								</td>
							</tr>
						<? endif; ?>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_menu_mypage','マイページ') ?></th>
							<td>
								<?
									$imgUrl = "";
									$service_env = getenv('URL_SERVICE');
									if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
										$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_mypage_{$service_env}.png";
										if(file_exists($this->config->item('menu_image_dir').'/btn_mypage_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
											$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_mypage_{$service_env}_{$this->libauth->get_school_id()}.png";
										}
									}else{
										$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_mypage.png";
										if(file_exists($this->config->item('menu_image_dir').'/btn_mypage_'.$this->libauth->get_school_id().'.png')){
											$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_mypage_{$this->libauth->get_school_id()}.png";
										}
									}
								?>
								<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
							</td>
							<td style="vertical-align:top;">
								<div class="select_change_or_default">
									<input type="radio" name="change_type_mypage" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
									<input type="radio" name="change_type_mypage" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
								</div>
								<input type="file" name="upload_file_mypage" size="30" value="">
								<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
							</td>
						</tr>
						
						<? if($menuFlag['test'] == 1): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_test','テスト') ?></th>
								<td>
									<?
										$imgUrl = "";
										$service_env = getenv('URL_SERVICE');
										if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_test_{$service_env}.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_test_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_test_{$service_env}_{$this->libauth->get_school_id()}.png";
											}
										}else{
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_test.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_test_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_test_{$this->libauth->get_school_id()}.png";
											}
										}
									?>
									<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
								</td>
								<td style="vertical-align:top;">
									<div class="select_change_or_default">
										<input type="radio" name="change_type_test" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
										<input type="radio" name="change_type_test" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
									</div>
									<input type="file" name="upload_file_test" size="30" value="">
									<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
								</td>
							</tr>
						<? endif; ?>
						
						<? if($menuFlag['issue'] == 1): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_issue','課題') ?></th>
								<td>
									<?
										$imgUrl = "";
										$service_env = getenv('URL_SERVICE');
										if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_issue_{$service_env}.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_issue_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_issue_{$service_env}_{$this->libauth->get_school_id()}.png";
											}
										}else{
											$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_issue.png";
											if(file_exists($this->config->item('menu_image_dir').'/btn_issue_'.$this->libauth->get_school_id().'.png')){
												$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_issue_{$this->libauth->get_school_id()}.png";
											}
										}
									?>
									<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
								</td>
								<td style="vertical-align:top;">
									<div class="select_change_or_default">
										<input type="radio" name="change_type_issue" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
										<input type="radio" name="change_type_issue" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
									</div>
									<input type="file" name="upload_file_issue" size="30" value="">
									<br /><?= $this->lang->line_or_def('msg_school_photo_rule','pngのみ許可') ?>
								</td>
							</tr>
						<? endif; ?>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_menu_default','デフォルト') ?></th>
							<td>
								<?
									$imgUrl = "";
									$service_env = getenv('URL_SERVICE');
									if( ($service_env == 'conference') || ($service_env == 'alfsales') ){
										$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_default_{$service_env}.png";
										if(file_exists($this->config->item('menu_image_dir').'/btn_default_'.$service_env.'_'.$this->libauth->get_school_id().'.png')){
											$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_default_{$service_env}_{$this->libauth->get_school_id()}.png";
										}
									}else{
										$imgUrl = "http://{$this->config->item('domain_follower')}/static/img/menu/btn_default.png";
										if(file_exists($this->config->item('menu_image_dir').'/btn_default_'.$this->libauth->get_school_id().'.png')){
											$imgUrl = "http://{$this->config->item('domain_follower')}//static/img/menu/btn_default_{$this->libauth->get_school_id()}.png";
										}
									}
								?>
								<img src="<?= htmlspecialchars( $imgUrl, ENT_QUOTES, 'UTF-8') ?>" height="80" width="80" border="1">
							</td>
							<td style="vertical-align:top;">
								<div class="select_change_or_default">
									<input type="radio" name="change_type_default" value="change" checked="checked"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','変更する') ?>
									<input type="radio" name="change_type_default" value="default"><?= $this->lang->line_or_def('msg_school_photo_select_change_or_default_change','デフォルトに戻す') ?>
								</div>
								<input type="file" name="upload_file_default" size="30" value="">
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
