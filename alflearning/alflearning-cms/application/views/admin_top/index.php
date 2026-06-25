<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		#contents_main UL.list{
			min-height	: 200px;
		}
	</style>
	<script type="text/javascript">
		function information_show_hide(_this){
			var list_caption = $(_this).parent().parent().find('.list_caption');
			if($(list_caption).css('display') == 'none'){
				$(list_caption).slideDown();
			}
			else{
				$(list_caption).slideUp();
			}
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header',array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_information','インフォメーション') ?></div>
			<div class="comment"></div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'	=> 'info',
			));?>

			<div id="contents_main">
				<? // 法学館対応：お知らせ一覧を非表示固定 ?>
				<? if(1==0): ?>
				<h2><?= $this->lang->line_or_def('common_list_of_information','お知らせ一覧') ?></h2>
				<ul class="list">
					<?php
						if( isset($informations) && count($informations) > 0 ) {
							foreach($informations as $information) {
					?>
						<li style=<?= ($information['school_id']==0) ? "background-color:#D3F5EA;" : ""; ?>>
							<div class="list_title">
								<a href="#" onclick="information_show_hide(this);return false;">
									<?= htmlspecialchars( $information['information_date'], ENT_QUOTES, 'UTF-8') ?>&nbsp;
									<?php if($information['school_id']==0): ?>
										[<?= $this->lang->line_or_def('common_all_school_object','全学校対象') ?>]&nbsp;
									<?php endif; ?>
									<?= htmlspecialchars( $information['information_title'], ENT_QUOTES, 'UTF-8') ?>
								<!-- <?=$information['information_date']?>&nbsp;<?=$information['information_title']?> -->
								</a>
							</div>
							<div class="list_caption" style="display:none;">
								<?= nl2br( htmlspecialchars( $information['information_caption'], ENT_QUOTES, 'UTF-8') ) ?>
							</div>
						</li>
					<?php
							}
						} else {
					?>
						<li><?= $this->lang->line_or_def('msg_admin_top_no_information','現在、お知らせはありません') ?></li>
					<?php
						}
					?>
				</ul>
				<? endif; ?>
				
				<h2><?= $this->lang->line_or_def('common_login_history','ログイン履歴') ?></h2>
					<?php if( isset($login_logs) && count($login_logs) > 0 ): ?>
						<?php if($this->libauth->get_teacher_id() < 0): ?>
							<table style="width: 750px; padding-left: 0px; margin-left: 10px;">
								<tr style="border: 1px #808080 solid; border-style: none none solid none ;">
									<th width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
									<th width="300px" style="padding-bottom: 2px; padding-top: 2px;"><?= $this->lang->line_or_def('common_teacher_name','講師名') ?></th>
									<th width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= 'Log Type'; ?></th>
									<th width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= $this->lang->line_or_def('common_login_date','ログイン日') ?></th>
								</tr>
								<?php foreach($login_logs as $login_log) { ?>
								<tr style="border: 1px #808080 solid; border-style: none none solid none ;">
								<td width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['teacher_id'], ENT_QUOTES, 'UTF-8') ?></td>
								<td width="300px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['log_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['added_at'], ENT_QUOTES, 'UTF-8') ?></td>
								</tr>
								<?php } ?>
							</table>
						<?php else: ?>
							<table style="width: 750px; padding-left: 0px; margin-left: 10px;">
								<tr style="border: 1px #808080 solid; border-style: none none solid none ;">
									<th width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
									<th width="300px" style="padding-bottom: 2px; padding-top: 2px;"><?= $this->lang->line_or_def('common_teacher_name','講師名') ?></th>
									<th width="300px" style="padding-bottom: 2px; padding-top: 2px;"><?= $this->lang->line_or_def('common_login_date','ログイン日') ?></th>
								</tr>
								<?php foreach($login_logs as $login_log) { ?>
								<tr style="border: 1px #808080 solid; border-style: none none solid none ;">
								<td width="150px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['teacher_id'], ENT_QUOTES, 'UTF-8') ?></td>
								<td width="300px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td width="300px" style="padding-bottom: 2px; padding-top: 2px;"><?= htmlspecialchars( $login_log['added_at'], ENT_QUOTES, 'UTF-8') ?></td>
								</tr>
								<?php } ?>
							</table>
						<?php endif; ?>
					<?php else: ?>
						<?= $this->lang->line_or_def('common_no_login_history','ログイン履歴なし') ?>
					<?php endif; ?>
			</div>
		<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
