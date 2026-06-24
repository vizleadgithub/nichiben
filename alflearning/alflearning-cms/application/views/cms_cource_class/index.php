<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());?>
	
	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_comment','授業を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_cource_class/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_list selected" href="/cms_cource_class/"><span><?= $this->lang->line_or_def('common_list','一覧') ?></span></a>
					<a class="btn_seach" href="/cms_class/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_class/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h3><?= $this->lang->line_or_def('common_list_of_charge_classes','直近の担当授業一覧') ?></h3>

				<div>
					<table class="list">
						<tr>
						<th style="width: 76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
						<th style="width:103px;"><?= $this->lang->line_or_def('common_date','日付') ?></th>
						<th style="width:166px;"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
						<th style="width:166px;"><?= $this->lang->line_or_def('common_class_name','授業名') ?></th>
						<th style="width:156px;"><?= $this->lang->line_or_def('common_time','時間') ?></th>
						<th><?= $this->lang->line_or_def('common_teacher','講師') ?></th>
						</tr>
						<?php
							if(isset($class_list) && count($class_list) > 0 ) { 
								foreach($class_list as $class) { 
									?>
								<tr <? if($class['effective_cource']==0): ?>class="out_of_the_period"<? endif; ?> >
										<td><a href="/cms_class/detail/<?= htmlspecialchars( $class['class_id'], ENT_QUOTES, 'UTF-8')  ?>"><?= htmlspecialchars( $class['class_id'], ENT_QUOTES, 'UTF-8') ?></td>
										<td><?= htmlspecialchars( $class['class_date'], ENT_QUOTES, 'UTF-8') ?></td>
										<td><?= htmlspecialchars( $class['cource_name'], ENT_QUOTES, 'UTF-8') ?></td>
										<td><?= htmlspecialchars( $class['class_name'], ENT_QUOTES, 'UTF-8') ?></td>
										<td><?= htmlspecialchars( $class['class_opentime'], ENT_QUOTES, 'UTF-8') ?><?= $this->lang->line_or_def('common_range','～') ?><?= htmlspecialchars( $class['class_closetime'], ENT_QUOTES, 'UTF-8') ?></td>
										<td><?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
									</tr>
								<?php
								}
							}
						?>
						<tr>
						<!--<th colspan="9" class="pager">&nbsp;</th> -->
							<th colspan="7" class="pager">&nbsp;</th>
						</tr>
					</table>
				</div>
			</div>

			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
