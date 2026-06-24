<?php
	$this->lang->load('common');
?>


<div id="menu_sub" class="clearfix">
	<ul>
		<li<? if($selected == 'info'): ?> class="selected"<? endif; ?>>
			<a href="/admin_top/" style="font-size: 15px;line-height: 40px;"><?= $this->lang->line_or_def('common_information', 'インフォメーション') ?></a>
		</li>
	
		<? // 法学館対応：ログイン画像、メニュー画像を非表示固定 ?>
		<? if(1==0): ?>
		<li<? if(($selected == 'info_detail') && ($select_tag == '')): ?> class="selected"<? endif; ?>>
			<a href="/admin_top/info_detail"><?= $this->lang->line_or_def('common_list_of_information', 'お知らせ一覧') ?></a>
		</li>
		<? endif; ?>

		<? if($selected == 'info_detail'): ?>
			<? if(isset($tags['タグなし'])):$tagKey = 'タグなし';unset($tags['タグなし']) ?>
				<li class="<?= ($select_tag === $tagKey ? 'selected_sub' : 'sub'); ?>" >
					<a href="/admin_top/info_detail/<?= urlencode($tagKey) ?>" style="margin-left: 25px;"><?= $tagKey ?></a>
				</li>
			<? endif; ?>
			
			<? foreach($tags as $tagKey => $cnt): ?>
				<li class="<?= ($select_tag === $tagKey ? 'selected_sub' : 'sub'); ?>" >
					<a href="/admin_top/info_detail/<?= urlencode($tagKey) ?>" style="margin-left: 25px;"><?= $tagKey ?></a>
				</li>
			<? endforeach; ?>
		<? endif; ?>

		<? $user_auths = $this->session->userdata; ?>
		<? $contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]); ?>
		<? if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ): ?>
			<li<? if($selected == 'classes'): ?> class="selected"<? endif; ?>><a href="/admin_top/classes/"><?= $this->lang->line_or_def('common_list_of_class', '直近の授業一覧') ?></a></li>
		<? endif; ?>

		<? if($user_auths['cms_master.login.teacher_auth']['school_admin']): ?>
		
		<? // 法学館対応：ログイン画像、メニュー画像を非表示固定 ?>
		<? if(1==0): ?>
			<li<? //if($selected == 'school_photo'): ?> class="selected"<? //endif; ?>><a href="/admin_top/school_photo/"><?= '';//$this->lang->line_or_def('common_login_photo', 'ログイン画像') ?></a></li>

			<li<? //if($selected == 'school_menu'): ?> class="selected"<? //endif; ?>><a href="/admin_top/school_menu/"><?= '';//$this->lang->line_or_def('common_menu_photo', 'メニュー画像') ?></a></li>
		<? endif; ?>
			<? 
				// サブメニュー「外部連携」表示条件
				// => Super User
				// => 学校管理者且つ、学校がeLM契約ありの場合 ※学校管理者のチェックは上で行っているため不要
				$show_outside = false;
				
				if($user_auths["cms_master.login.teacher_id"] < 0){
					// Super User
					$show_outside = true;
				}else{
					// 学校.契約形態取得
					//$contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]);
					// 外部連携 項目あり
					if(isset($contract_param['outside_elearningmanager'])){
						// 外部連携.契約 = あり
						if($contract_param['outside_elearningmanager']['contract']==='fixation'){
							$show_outside = true;
						}
					}
				}
			?>
			<? if($show_outside==true): ?>
				<li><a href="/admin_top/outside_elearningmanager/"><?= $this->lang->line_or_def('common_outside_corporation', '外部連携') ?></a></li>
				<? if($selected == 'outside'): ?>
					<!--※2012/10/30 本番に適応させないため、リンク無効化-->
					<li class="<?= ($select_tag === 'elearningmanager' ? 'selected_sub' : 'sub'); ?>" >
						<a href="/admin_top/outside_elearningmanager/" style="margin-left: 25px;">eLM</a>
					</li>
				<? endif; ?>
			<? endif; ?>

		<? endif; ?>
		<? if( (getenv('URL_SERVICE') != 'alfsales') && (getenv('URL_SERVICE') != 'conference') ): ?>
			<? // 法学館対応：更新履歴を非表示固定 ?>
			<? if(1==0): ?>
			<li<? if($selected == 'update_history'): ?> class="selected"<? endif; ?>><a href="/admin_top/update_history"><?= $this->lang->line_or_def('common_update_history', '更新履歴') ?></a></li>
			<? endif; ?>
		<? endif; ?>
	</ul>
</div>
