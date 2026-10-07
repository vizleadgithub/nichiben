<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_class_list','授業一覧') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('common_list_of_class','直近の授業一覧') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'	=> 'classes',
			));?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('common_list_of_class','直近の授業一覧') ?></h2>
				<table class="list">
					<tr>
						<th width="60">ID</th>
						<th><?= $this->lang->line_or_def('common_date','日付') ?></th>
						<th><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
						<th width="60"><?= $this->lang->line_or_def('common_time','時間') ?></th>
						<th><?= $this->lang->line_or_def('common_teacher','講師') ?></th>
						<th><?= $this->lang->line_or_def('common_capacity','定員') ?></th>
						<th><?= $this->lang->line_or_def('common_situation','状況') ?></th>
					</tr>
					<?php
						if(isset($classes) && count($classes) > 0 ) { 
							foreach($classes as $class) { 
						?>
								<tr>
									<td><?= htmlspecialchars( $class['class_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?=date("m/d H:i", strtotime($class['class_open']))?></td>
									<td><?= htmlspecialchars( $class['cource_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= date("H:i", strtotime($class['class_close']) - strtotime($class['class_open']) - 32400); ?></td>
									<td><?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= $this->lang->line_or_def('common_last','あと') ?><?= htmlspecialchars( $class['fixed_number'], ENT_QUOTES, 'UTF-8') ?><?= $this->lang->line_or_def('common_people','人') ?></td>
									<td><?= (strtotime($class['class_open']) < time() ? $this->lang->line_or_def('common_class_now','授業中') : $this->lang->line_or_def('common_still','未')); ?></td>
								</tr>
					<?php
							}
						}
					?>
				</table>
			</div>
		<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
