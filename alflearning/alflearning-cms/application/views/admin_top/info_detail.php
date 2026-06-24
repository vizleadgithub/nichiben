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

		/* [2012/10/05]サブメニュー用 */
		#wrapper #main #menu_sub UL .sub {
			background:center transparent;
			float: left;
			height: 40px;
			width: 160px;
			overflow:hidden;
		}
		#wrapper #main #menu_sub UL .selected_sub {
			background:center white;
			float: left;
			height: 40px;
			width: 160px;
			overflow:hidden;
		}
		
		#information_table{
			width: 100%;
		}
		#information_table .information_title{
			text-align: right;
			height: 25px;
			line-height: 25px;
			width: 10%;
		}
		#information_table .information_detail{
			text-align: left;
			width: 90%;
		}
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
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_list_of_information','お知らせ一覧') ?></div>
			<div class="comment">
				<?php if($select_tag===''): ?>
					新着
				<?php else: ?>
					<?= $this->lang->line_or_def('common_tag','タグ') ?>&nbsp;:&nbsp;<?= $select_tag ?>
				<?php endif; ?>
			</div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'		=> 'info_detail',
				'info_tags'		=> $tags,
				'select_tag'	=> $select_tag,
			));?>

			<div id="contents_main">
				<ul>
					<?php
						if( isset($informations) && count($informations) > 0 ) {
							foreach($informations as $information) {
					?>
						<li>
							<h2 style="text-align: left;">
								[<?=$information['information_id']?>]&nbsp;<?=$information['information_date']?>&nbsp;&nbsp;
								
								<?php if( ($information['show_teacher']==1) && ($information['show_student']==1) ): ?>
									<?= $this->lang->line_or_def('common_information_target','お知らせ対象') ?>&nbsp;:&nbsp;<?= $this->lang->line_or_def('common_teacher','講師') ?>&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_student','受講者') ?>
								<?php elseif($information['show_teacher']==1): ?>
									<?= $this->lang->line_or_def('common_information_target','お知らせ対象') ?>&nbsp;:&nbsp;<?= $this->lang->line_or_def('common_teacher','講師') ?>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_information_target','お知らせ対象') ?>&nbsp;:&nbsp;<?= $this->lang->line_or_def('common_student','受講者') ?>
								<?php endif; ?>
								<span style="display: block;float: right;height: 25px;line-height: 25px;">
									<?=$information['information_open']?><?= $this->lang->line_or_def('common_range','～') ?><?=$information['information_close']?>&nbsp;&nbsp;
								</span>
							</h2>
							<table id="information_table" style=<?= ($information['school_id']==0) ? "background-color:#D3F5EA;" : ""; ?> >
								<tr>
									<th class="information_title" ><?= $this->lang->line_or_def('common_title','タイトル') ?>&nbsp;:&nbsp;</th>
									<td class="information_detail">
										<?php if($information['school_id']==0): ?>
											[<?= $this->lang->line_or_def('common_all_school_object','全学校対象') ?>]&nbsp;
										<?php endif; ?>
										<?=$information['information_title']?>
									</td>
								</tr>
								<tr>
									<th class="information_title" ><?= $this->lang->line_or_def('common_caption','説明') ?>&nbsp;:&nbsp;</th>
									<td class="information_detail"><?= nl2br($information['information_caption']); ?></td>
								</tr>
								<tr>
									<th class="information_title" ><?= $this->lang->line_or_def('common_tag','タグ') ?>&nbsp;:&nbsp;</th>
									<td class="information_detail">
										<?php if($information['school_id']==0): ?>
											<?= $this->lang->line_or_def('msg_news_from_support_service','サポートサービスからのお知らせ') ?>
										<?php else: ?>
											<?php if(empty($information['information_tags'])): ?>
												<?= $this->lang->line_or_def('common_nothing','なし') ?>
											<?php else: ?>
												<?= $information['information_tags']; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
								</tr>
							</table>
						</li>
					<?php
							}
						} else {
					?>
						<li>
							<h2><?= $this->lang->line_or_def('msg_admin_top_no_information','現在、お知らせはありません') ?></h2>
						</li>
					<?php
						}
					?>
				</ul>
			</div>
		<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
