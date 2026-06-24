<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);
?>

<style type="text/css">
	.form .td_left_blank{
		width: 230px;
	}
	.form .td_right_blank{
		width: 150px;
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
				<h2><?= $this->lang->line_or_def('common_transmission_completion','送信完了') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_school_photo_commit_result','画像の設定が完了しました') ?></h3>
				<br />
				<table class="form" style=" margin-left: auto; margin-right: auto;">
					<? if($updateFlag['live'] != 'no contract'): ?>
						<tr>
							<td class="td_left_blank"></td>
							<th><?= $this->lang->line_or_def('common_menu_live','生授業') ?></th>
							<td>
								<? switch ($updateFlag['live']) {
									case 'success update':
										echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
										break;
									case 'failure update':
									case 'no action':
										echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
										break;
									case 'success default':
										echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
										break;
									case 'failure default':
										echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
										break;
									case 'no contract':
										echo $this->lang->line_or_def('common_no_contract','契約なし');
										break;
								}  ?>
							</td>
							<td class="td_right_blank"></td>
						</tr>
					<? endif; ?>
					
					<? if($updateFlag['video'] != 'no contract'): ?>
						<tr>
							<td class="td_left_blank"></td>
							<th><?= $this->lang->line_or_def('common_menu_video','ビデオ授業') ?></th>
							<td>
								<? switch ($updateFlag['video']) {
									case 'success update':
										echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
										break;
									case 'failure update':
									case 'no action':
										echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
										break;
									case 'success default':
										echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
										break;
									case 'failure default':
										echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
										break;
									case 'no contract':
										echo $this->lang->line_or_def('common_no_contract','契約なし');
										break;
								}  ?>
							</td>
							<td class="td_right_blank"></td>
						</tr>
					<? endif; ?>
					
					<? if($updateFlag['library'] != 'no contract'): ?>
						<tr>
							<td class="td_left_blank"></td>
							<th><?= $this->lang->line_or_def('common_menu_library','図書室') ?></th>
							<td>
								<? switch ($updateFlag['library']) {
									case 'success update':
										echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
										break;
									case 'failure update':
									case 'no action':
										echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
										break;
									case 'success default':
										echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
										break;
									case 'failure default':
										echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
										break;
									case 'no contract':
										echo $this->lang->line_or_def('common_no_contract','契約なし');
										break;
								}  ?>
							</td>
							<td class="td_right_blank"></td>
						</tr>
					<? endif; ?>
					
					<tr>
						<td class="td_left_blank"></td>
						<th><?= $this->lang->line_or_def('common_menu_mypage','マイページ') ?></th>
						<td>
							<? switch ($updateFlag['mypage']) {
								case 'success update':
									echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
									break;
								case 'failure update':
								case 'no action':
									echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
									break;
								case 'success default':
									echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
									break;
								case 'failure default':
									echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
									break;
								case 'no contract':
									echo $this->lang->line_or_def('common_no_contract','契約なし');
									break;
							}  ?>
						</td>
						<td class="td_right_blank"></td>
					</tr>
					
					<? if($updateFlag['test'] != 'no contract'): ?>
					<tr>
						<td class="td_left_blank"></td>
						<th><?= $this->lang->line_or_def('common_test','テスト') ?></th>
						<td>
							<? switch ($updateFlag['test']) {
								case 'success update':
									echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
									break;
								case 'failure update':
								case 'no action':
									echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
									break;
								case 'success default':
									echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
									break;
								case 'failure default':
									echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
									break;
								case 'no contract':
									echo $this->lang->line_or_def('common_no_contract','契約なし');
									break;
							}  ?>
						</td>
						<td class="td_right_blank"></td>
					</tr>
					<? endif; ?>
					
					<? if($updateFlag['issue'] != 'no contract'): ?>
					<tr>
						<td class="td_left_blank"></td>
						<th><?= $this->lang->line_or_def('common_issue','課題') ?></th>
						<td>
							<? switch ($updateFlag['issue']) {
								case 'success update':
									echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
									break;
								case 'failure update':
								case 'no action':
									echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
									break;
								case 'success default':
									echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
									break;
								case 'failure default':
									echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
									break;
								case 'no contract':
									echo $this->lang->line_or_def('common_no_contract','契約なし');
									break;
							}  ?>
						</td>
						<td class="td_right_blank"></td>
					</tr>
					<? endif; ?>
					
					<tr>
						<td class="td_left_blank"></td>
						<th><?= $this->lang->line_or_def('common_menu_default','デフォルト') ?></th>
						<td>
							<? switch ($updateFlag['default']) {
								case 'success update':
									echo $this->lang->line_or_def('common_image_update_success','画像更新完了');
									break;
								case 'failure update':
								case 'no action':
									echo $this->lang->line_or_def('common_image_no_update','画像更新なし');
									break;
								case 'success default':
									echo $this->lang->line_or_def('common_image_default_success','デフォルト画像更新完了');
									break;
								case 'failure default':
									echo $this->lang->line_or_def('common_image_default_failure','デフォルト画像更新失敗');
									break;
								case 'no contract':
									echo $this->lang->line_or_def('common_no_contract','契約なし');
									break;
							}  ?>
						</td>
						<td class="td_right_blank"></td>
					</tr>
				</table>
				<br />
				<div class="submit">
					<a href="/admin_top/school_menu/"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
