<?php
	$this->lang->load('common');
?>

<div id="menu_sub" class="clearfix">
	<ul>
		<? // [20131115] 日弁連のユーザのみ、リンク表示するように修正 ?>
		<? if($this->libauth->get_bar_association_id() == 1): ?>
			<? $user_auths = $this->session->userdata; ?>
			<? $contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]); ?>
			<? if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ): ?>
				<li<? if($selected == 'cms_class'): ?> class="selected"<? endif; ?>><a href="/cms_report/cms_class"><?= $this->lang->line_or_def('common_class','授業')."*" ?></a></li>
			<? endif; ?>
			<? if( (isset($contract_param['video'])) && ($contract_param['video']['contract']==='fixation') ): ?>
				<li<? if($selected == 'cms_video'): ?> class="selected"<? endif; ?>><a href="/cms_report/cms_video"><?= $this->lang->line_or_def('common_video','ビデオ')."*" ?></a></li>
			<? endif; ?>
			<? if( (isset($contract_param['book_library'])) && ($contract_param['book_library']['contract']==='fixation') ): ?>
				<li<? if($selected == 'cms_book_library'): ?> class="selected"<? endif; ?>><a href="/cms_report/cms_book_library"><?= $this->lang->line_or_def('common_menu_book_library','図書室')."*" ?></a></li>
			<? endif; ?>
		<? endif; ?>
		
		<? // 日弁連対応　リンク追加 ?>
		<li><a href="/alfproduct/report_product/">商品</a></li>
		<li<? if($selected == 'cms_user'): ?> class="selected"<? endif; ?>><a href="/cms_report/cms_user/"><?= $this->lang->line_or_def('common_','ユーザ') ?></a></li>
		<? // <li><a href="/alfproduct/report_user/">ユーザ</a></li> ?>
		<li><a href="/alfproduct/report_all/">全体確認</a></li>
	</ul>
</div>
