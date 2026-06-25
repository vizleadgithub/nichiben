<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "auth";
	$this->load->view('header/header',$data);?>

	<script type="text/javascript"><!--
		function checkradio(disp) {
			if(disp=="block"){
				$("#select_admin_checkbox").slideDown();
			//	$("#select_admin_checkbox").slideToggle();
			//	$("#select_admin_checkbox").show();
			}else{
				$("#select_admin_checkbox").slideUp();
			//	$("#select_admin_checkbox").hide();
			}
		//	document.getElementById("select_admin_checkbox").style.display = disp;
		}
	// --></script> 

	<style type="text/css">
		.auth_list DIV{
			line-height	: 21px;
		}
		.auth_list .disabled{
			color:gray;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_auth','権限管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_auth_comment','各講師の権限を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_auth/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_auth/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_auth_input','各権限を設定してください') ?></h2>

				<?=form_open("cms_auth/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $teacher['update_flg'])?>'>
					<input type=hidden name=teacher_id value='<?=set_value('teacher_id', $teacher['teacher_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<?= htmlspecialchars( $teacher['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
								<input type="hidden" name="teacher_name" size="45" value="<?=set_value('teacher_name',$teacher['teacher_name'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<?= htmlspecialchars( $teacher['teacher_email'], ENT_QUOTES, 'UTF-8') ?>
								<input type="hidden" name="teacher_email" size="45" value="<?=set_value('teacher_email',$teacher['teacher_email'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_practice_authority','実行権限') ?></th>
							<td class="auth_list">
								<div>
									<input type="radio" name="a_school_admin" id="a_school_admin" value='1' onclick="checkradio('none')" <?=(set_value('a_school_admin',$teacher['a_school_admin'])=='1')?'checked':''?> >
									<label for="a_school_admin_on"><?= $this->lang->line_or_def('common_manager','管理者') ?></label>
								</div>
								<div>
									<input type="radio" name="a_school_admin" id="a_school_admin" value='0' onclick="checkradio('block')" <?=(set_value('a_school_admin',$teacher['a_school_admin'])=='0')?'checked':''?> >
									<label for="a_school_admin_off"><?= $this->lang->line_or_def('common_teacher','講師') ?></label>
								</div>
								<div id="select_admin_checkbox" <?=(set_value('a_school_admin',$teacher['a_school_admin'])=='1')?'style="display:none;"':''?> > 
								
									<?php $this->load->model('Modelschoolcontract'); ?>
								
									<div style="padding-left:30px;width: 270px;float:left;">
										<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))): ?>
											<input type="checkbox" name="a_cource_class"  id="a_cource_class"  value='1' <?=(set_value('a_cource_class',$teacher['a_cource_class'])=='1')?'checked':''?> >
											<label for="a_cource_class"><?= $this->lang->line_or_def('common_course_class_manage','授業管理') ?></label>
										<?php else: ?>
											<input type="hidden" name="a_cource_class" value='0'>
										<!--<input type="checkbox" name="a_cource_class"  id="a_cource_class"  value='0' disabled >-->
										<!--<label for="a_cource_class" class="disabled"><?= '';//$this->lang->line_or_def('common_course_class_manage','授業管理') ?></label>-->
										<?php endif; ?>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
										<input type="checkbox" name="a_student"       id="a_student"       value='1' <?=(set_value('a_student',$teacher['a_student'])=='1')?'checked':''?> >
										<label for="a_student"><?= $this->lang->line_or_def('common_student_manage','受講者管理') ?></label>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
										<input type="checkbox" name="a_teacher"       id="a_teacher"       value='1' <?=(set_value('a_teacher',$teacher['a_teacher'])=='1')?'checked':''?> >
										<label for="a_teacher"><?= $this->lang->line_or_def('common_teacher_manage','講師管理') ?></label>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
										<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'live'))): ?>
											<input type="checkbox" name="a_material"      id="a_material"      value='1' <?=(set_value('a_material',$teacher['a_material'])=='1')?'checked':''?> >
											<label for="a_material"><?= $this->lang->line_or_def('common_material_manage','資料管理') ?></label>
										<?php else: ?>
											<input type="hidden" name="a_material" value='0'>
										<!--<input type="checkbox" name="a_material"      id="a_material"      value='0' disabled >-->
										<!--<label for="a_material" class="disabled"><?= '';//$this->lang->line_or_def('common_material_manage','資料管理') ?></label>-->
										<?php endif; ?>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
										<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))): ?>
											<input type="checkbox" name="a_book_library"  id="a_book_library"  value='1' <?=(set_value('a_book_library',$teacher['a_book_library'])=='1')?'checked':''?> >
											<label for="a_book_library"><?= $this->lang->line_or_def('common_book_library_manage','図書室管理') ?></label>
										<?php else: ?>
											<input type="hidden" name="a_book_library" value='0'>
										<!--<input type="checkbox" name="a_book_library"  id="a_book_library"  value='0' disabled >-->
										<!--<label for="a_book_library" class="disabled"><?= '';//$this->lang->line_or_def('common_book_library_manage','図書室管理') ?></label>-->
										<?php endif; ?>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
										<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'video'))): ?>
											<input type="checkbox" name="a_video"         id="a_video"         value='1' <?=(set_value('a_video',$teacher['a_video'])=='1')?'checked':''?> >
											<label for="a_video"><?= $this->lang->line_or_def('common_video_manage','ビデオ管理') ?></label>
										<?php else: ?>
											<input type="hidden" name="a_video" value='0'>
										<!--<input type="checkbox" name="a_video"         id="a_video"         value='1' disabled >-->
										<!--<label for="a_video" class="disabled"><?= '';//$this->lang->line_or_def('common_video_manage','ビデオ管理') ?></label>-->
										<?php endif; ?>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
										<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'issue'))): ?>
											<input type="checkbox" name="a_issue"         id="a_issue"         value='1' <?=(set_value('a_issue',$teacher['a_issue'])=='1')?'checked':''?> >
											<label for="a_issue"><?= $this->lang->line_or_def('common_issue_manage','課題管理') ?></label>
										<?php else: ?>
											<input type="hidden" name="a_issue" value='0'>
										<!--<input type="checkbox" name="a_issue"         id="a_issue"         value='1' disabled >-->
										<!--<label for="a_issue" class="disabled"><?= '';//$this->lang->line_or_def('common_issue_manage','課題管理') ?></label>-->
										<?php endif; ?>
									</div>
									<div style="padding-left:30px;width: 270px;float:left;">
								<!--<div style="padding-left:30px;width: 270px;float:both;">-->
								
									<? // 法学館対応：お知らせ管理を非表示固定 ?>
									<input type="hidden" name="a_information" value='0'>
									<? if(1==0): ?>
										<input type="checkbox" name="a_information"   id="a_information"   value='1' <?=(set_value('a_information',$teacher['a_information'])=='1')?'checked':''?> >
										<label for="a_information"><?= $this->lang->line_or_def('common_information_manage','お知らせ管理') ?></label>
									<? endif; ?>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_confirm.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
