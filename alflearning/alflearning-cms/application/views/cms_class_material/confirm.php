<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_material_input','授業に使用する資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class_material/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix"></div>
				<?=form_open("cms_class_material/class_material_upload_exec")?>
					<input type=hidden name=class_id value='<?= $class_id ?>'>
					<input type=hidden name=delete_check_list value='<?= $delete_check_list ?>'>
					<input type=hidden name=insert_check_list value='<?= $insert_check_list ?>'>

					<?php if(isset($class_material_list)) { ?>
						<h3><?= $this->lang->line_or_def('msg_class_material_del_confirm','授業から削除する資料') ?></h3>
						<table class="list" style="width: 775px;">
							<tr>
								<th style="width: 103px;"></th>
								<th style="width: 153px;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
								<th style="width: 103px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
								<th style="width: 416px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							</tr>
						</table>
						<div style="height: 160px;overflow-x:hidden; overflow-y:auto;">
							<table class="list" style="margin-top: 0px;width: 775px;">
								<?php foreach($class_material_list as $class_material) { ?>
									<tr style="height: 66px;">
										<td style="vertical-align: middle;width: 97px;text-align: center;">
											<?= $this->lang->line_or_def('common_deletion','削除') ?>
										</td>
										<td style="vertical-align: middle;width: 147px;">
											<img src="/file_container/get_class_material_thubmnail/<?=$class_id?>/<?=$class_material['teacher_id']?>/<?=$class_material['student_id']?>/<?=$class_material['class_material_id']?>/" alt="" style="height: 50px; padding: 1px;background-color:black;"/>
										</td>
										<td style="vertical-align: middle;width: 97px;">
											<?=$class_material['class_material_id']?>
										</td>
										<td style="vertical-align: middle;width: 410px;word-wrap:break-word;">
											<?=$class_material['material_logic_name']?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					<?php } ?>

					<?php if(isset($material_list)) { ?>
						<h3><?= $this->lang->line_or_def('msg_class_material_add_confirm','授業に登録する資料') ?></h3>
						<table class="list" style="width: 775px;">
							<tr>
								<th style="width: 88px;"></th>
								<th style="width:139px;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
								<th style="width: 88px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
								<th style="width:307px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
								<th style="width:153px;"><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
							</tr>
						</table>
						<div style="height: 160px;overflow-x:hidden; overflow-y:auto;">
							<table class="list" style="margin-top: 0px;width: 775px;">
								<?php foreach($material_list as $material) { ?>
									<tr style="height: 66px;">
										<td style="vertical-align: middle;width: 82px;text-align: center;">
											<?= $this->lang->line_or_def('common_registration','登録') ?>
										</td>
										<td style="vertical-align: middle;width: 133px;">
											<img src="/file_container/get_material_thubmnail/<?=$material['material_id']?>/" alt="" style="height: 50px; padding: 1px;background-color:black;"/>
										</td>
										<td style="vertical-align: middle;width: 82px;">
											<?=$material['material_id']?>
										</td>
										<td style="vertical-align: middle;width:301px;word-wrap:break-word;">
											<?=$material['material_logic_name']?>
										</td>
										<td style="vertical-align: middle;width: 147px;">
											<?=$material['teacher_name']?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					<?php } ?>

					<div class="submit">
						<input type='image' src='/static/image/btn_back.png' onClick='location.href = "/cms_class_material/material_select/<?= $class_id ?>";return false;' />
						<input type="image" src="/static/image/btn_ok.png" />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
