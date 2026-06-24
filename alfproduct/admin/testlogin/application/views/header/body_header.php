<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?/*
<?php if($callview == "session_error"): ?>
	<div id="header">
		<div class="logout">
			<?php
				print '<a href="javascript:void(0);" onclick="logout_confirm(\''.base_url().'\');return false;">';
				print $this->lang->line_or_def('common_logout','ログアウト');
				print '</a>';
			?>
		</div>
	</div>
	<div id="navi_btn">
		<ul>
		</ul>
	</div>
<? else: ?>
*/?>
	<div id="header">
		<div id="header_top">
		<!--<a href="/"><img class="logo" src="<?= base_url() . $this->config->item('images_dir') . $this->config->item('logo_small'); ?>"></a>-->
			<a href="/">
				<? $check_language = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : $this->config->item('language'); ?>
				<? if( $check_language == 'alfsales' ): ?>
					<img class="logo" src="<?= base_url() . $this->config->item('images_dir') . $this->config->item('logo_small_alfsales'); ?>">
				<? elseif( $check_language == 'conference' ): ?>
					<img class="logo" src="<?= base_url() . $this->config->item('images_dir_conference') . $this->config->item('logo_small'); ?>">
				<? else: ?>
					<img class="logo" src="<?= base_url() . $this->config->item('images_dir') . $this->config->item('logo_small'); ?>">
				<? endif; ?>
			</a>
			<div class="header_top_right">
				<?php if($this->libauth->get_teacher_auth()): ?>
				<!--
					<div class="login_teacher_name"><?= $this->libauth->get_teacher_name(); ?>用</div>
					<div class="login_school_name">[<?= $this->libauth->get_school_name(); ?>]管理ページ</div>
					<div class="logout"><?='<a href="javascript:void(0);" onclick="logout_confirm(\''.base_url().'\');return false;">ログアウト</a>'?></div>
				//-->

					<div class="login_teacher_name">
						<?= $this->libauth->get_teacher_name(); ?>

						<?php
							if($this->libauth->get_teacher_id() < 0){
								print '';
							}else{
								$temp_teacher_auth = $this->libauth->get_teacher_auth();
								if($temp_teacher_auth['school_select'] == 1){
									print '';
								}else{
									if($temp_teacher_auth['school_admin'] == 1){
										print ' '.$this->lang->line_or_def('common_manager','管理者');
									}else{
										print ' '.$this->lang->line_or_def('common_teacher','講師');
									}
								}
							}
						?>
					</div>
					<div class="login_school_name">
						<?php
							$temp_school_name = $this->libauth->get_school_name();
							if( strlen($temp_school_name) > 0 ){
								print '['.$temp_school_name.']';
							}
							print $this->lang->line_or_def('common_management_page','管理ページ');
						?>
						
					</div>
					
					<?php 
						$show_outside_message = false;
						
						// 学校IDあり
						if($this->session->userdata['cms_master.login.school_id'] > 0){
							// 学校.契約形態取得
							$contract_param = $this->libauth->get_login_school_contract_param($this->session->userdata['cms_master.login.school_id']);
							// 外部連携 項目あり
							if(isset($contract_param['outside_elearningmanager'])){
								// 外部連携.契約 = あり
								if($contract_param['outside_elearningmanager']['contract']==='fixation'){
									// api_key or api_url が空白ならtrue
									if(empty($contract_param['outside_elearningmanager']['api_key'])) $show_outside_message = true;
									if(empty($contract_param['outside_elearningmanager']['api_url'])) $show_outside_message = true;
								}
							}
						}
					?>
					<?php if($show_outside_message == true): ?>
						<?php $msg_data = $this->lang->line_or_def('msg_outside_corporation_warning_elm','eLearning Manager の連携に必要な情報がないため<br/>eLearning Managerとの自動同期は行われません<br/><a href=%s>コチラ</a>から登録してください');
						?>
						<div class="error" style="margin-top: 10px; margin-left: 350px;">
							<?= str_replace("%s", "'/admin_top/outside_elearningmanager/'", $msg_data); ?>
						</div>
					<?php endif; ?>
					
					<div class="logout">
						<?php
							print '<a href="javascript:void(0);" onclick="logout_confirm(\'';
							print base_url();
							print '\', \'';
							print $this->lang->line_or_def('msg_logout','ログアウトしますか？');
							print '\');return false;">';
							print $this->lang->line_or_def('common_logout','ログアウト');
							print '</a>';





//							print '<a href="javascript:void(0);" onclick="logout_confirm(\''.base_url().'\');return false;">';
//							print $this->lang->line_or_def('body_header_logout','ログアウト');
//							print '</a>';
						?>
					</div>
				<? endif; ?>
			</div>
			<div class="clear"></div>
		</div>
		<div id="header_navi">
			<ul>
				<?php if($this->libauth->get_teacher_auth()): ?>
					<?php
						$user_auths = $this->libauth->get_user_auth_params();
						foreach($user_auths as $user_auth){
							$temp_li = '<li';
							
							if($callview == $user_auth['auth']){
								$temp_li = $temp_li.' class="selected"';
							}
							if(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $user_auth['url']) != 1) {
								$temp_li = $temp_li.'><a href="'.$user_auth['url'].'">'.$user_auth['name'].'</a></li>';
							}else if($user_auth['auth'] == 'outside_elm'){
								$temp_li = $temp_li.'><a href="'.$user_auth['url'].'" target="_blank"><img src="/static/image/logo_eLearningManager.png" />'.$user_auth['name'].'</a></li>';
							}else{
								$temp_li = $temp_li.'><a href="'.$user_auth['url'].'" target="_blank">'.$user_auth['name'].'</a></li>';
							}
							
							print($temp_li);
						}
					?>
				<?php endif; ?>
			</ul>
		</div>
	</div>

					<?/*
<?php endif; ?>
*/?>
