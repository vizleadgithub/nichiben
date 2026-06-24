<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>
	<style>
	#class_date{
		width: 76px;
	}
	#class_opentime{
		width: 51px;
	}
	#class_closetime{
		width: 51px;
	}
	.student_list{
		max-height	: 300px;
		overflow-y	: scroll;
	}
	.students{
		border-bottom	: 1px dotted silver;
		margin			: 0px 0px 3px 0px;
		padding			: 0px 2px 0px 7px;
	}
		.students input, .students label{
			height		: 17px;
			line-height	: 17px;
		}

	#student_group_list{
		display	: block;
	}
		#student_group_list DIV{
			width		: 160px;
			margin-top	: 10px;
			display		: block;
			word-wrap	: break-word;
		}

			#student_group_list DIV A{
	/*
				border			: 1px solid #777777;
				color			: #555555;
				display			: block;
				height			: 20px;
				line-height		: 20px;
				overflow		: hidden;
				text-decoration	: none;
				width			: 156px;
	*/
				border-color			: #888888 #888888 #888888 silver;
				border-image			: none;
				border-style			: solid;
				border-width			: 1px 1px 1px 19px;

				-webkit-border-radius	: 4px 4px 4px 4px;
				-moz-border-radius		: 4px 4px 4px 4px;
				border-radius			: 4px 4px 4px 4px;

				color					: #888888;
				display					: block;
				font-size				: 11px;
				font-weight				: bold;
				height					: 18px;
				line-height				: 18px;
				overflow				: hidden;
				padding-left			: 2px;
				text-decoration			: none;
				width					: 138px;
			}


	#student_group_list_msg{
		margin-top		: 20px;
		margin-bottom	: 10px;
		display			: block;
		text-indent		: -1em; 
		margin-left		: 1em;
	}
	</style>
	<script type="text/javascript">
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datetimepicker設定
			$('#class_date'     ).datepicker(datepickeroption);
			$('#class_opentime' ).timepicker(datetimepickeroption);
//			$('#class_closetime').timepicker(datetimepickeroption);
			$('#class_closetime').timepicker({
				 dateFormat: 'yy/mm/dd'
				,timeFormat: 'hh:mm'
				,showSecond: false
				,showOtherMonths  : true
				,selectOtherMonths: true
				,changeYear       : true
				,changeMonth      : true
				,yearRange        :'1900:2100'
				,showButtonPanel  : true
				,currentText: '現在'
				,closeText: '閉じる'
				,timeText: '時間'
				,hourText: '時'
				,minuteText: '分'
				,secondText: '秒'
			});

			if($('.student_list input:checked').length == 0){
				$("#select_all").css("background-position", "center top");
			}else{
				$("#select_all").css("background-position", "center bottom");
			}
			
			$('.student_list').click(function (){
				if($('.student_list input:checked').length == 0){
					$("#select_all").css("background-position", "center top");
				}else{
					$("#select_all").css("background-position", "center bottom");
				}
			});
		});

		function select_all(){
			if($('.student_list input:checked').length){
				$('.student_list input').removeAttr('checked');
				$("#select_all").css("background-position", "center top");
			}
			else{
				$('.student_list input').attr('checked','checked');
				$("#select_all").css("background-position", "center bottom");
			}
			return false;
		}

		// グループ名リンク押下時の処理
		function select_student_group(student_id_list){
			if(student_id_list.length == 0){
				return false;
			}
			
			var resArray = student_id_list.split(",");
			
			//$('#student_list input').removeAttr('checked');
			
			for (var i = 0; i < resArray.length; i ++) {
				$('.student_list #student_'+resArray[i]).attr('checked','checked');
			}

			if($('.student_list input:checked').length){
				$(".select_all_affiliation.select_student").css("background-position", "center bottom");
			}else{
				$(".select_all_affiliation.select_student").css('background-position', 'center top');
			}
			return false;
		}

	</script>
	<style type="text/css"><!--
		TEXTAREA{
			width : 100%;
			height : 70px;
		}
		TEXTAREA[name="class_caption"]{
			height : 200px;
		}
	// --></style>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_comment','授業を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_list" href="/cms_cource_class/"><span><?= $this->lang->line_or_def('common_list','一覧') ?></span></a>
					<a class="btn_seach selected" href="/cms_class/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_class/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_class_input','授業の情報を入力してください') ?></h2>

				<?=form_open("cms_class/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $class['update_flg'])?>'>
					<input type=hidden name=class_id value='<?=set_value('class_id', $class['class_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<input type=hidden name=cource_id value='<?=set_value('cource_id', $class['cource_id'])?>'>
								<?= htmlspecialchars( $class['cource_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_class_type','授業タイプ') ?></th>
							<td>
								<input type=hidden name=class_type value='<?=set_value('class_type', $class['class_type'])?>'>
								<?
									switch($class['class_type']){
										case 'school':
											print $this->lang->line_or_def('common_nomal_class','一般授業');
											break;
										case 'auditor':
											print $this->lang->line_or_def('common_attend_class','聴講授業');
											break;
									}
								?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_datetime','日時') ?></th>
							<td>
								<input type=hidden name="class_date" 
									value="<?=set_value('class_date', ($class['class_date'] ? $class['class_date'] : date('Y/m/d')))?>" 
									id="class_date" readonly>
								<input type=hidden name="class_opentime" 
									value="<?=set_value('class_opentime', ($class['class_opentime'] ? $class['class_opentime'] : date('H:00:00', time()+(60*60))))?>" 
									id="class_opentime" readonly>
								
								<?= htmlspecialchars( $class['class_date'], ENT_QUOTES, 'UTF-8') ?>&nbsp;
								<?= htmlspecialchars( $class['class_opentime'], ENT_QUOTES, 'UTF-8') ?>&nbsp;
								<?= $this->lang->line_or_def('common_class_time','授業時間') ?>&nbsp;
								
								<?php if($class['class_maxtime_flag']==1): ?>
									<?= date('H:i', strtotime($class['class_closetime']) - strtotime($class['class_opentime']) - 32400) ?>
									<input type="hidden" name="class_closetime" 
										value="<?= date('H:i', strtotime($class['class_closetime']) - strtotime($class['class_opentime']) - 32400) ?>" 
										id="class_closetime" readonly>
								<?php else:?>
									<input type="text" name="class_closetime" size="6" value="<?=set_value('class_closetime', ($class['class_closetime'] ? date('H:i', strtotime($class['class_closetime']) - strtotime($class['class_opentime']) - 32400) : '01:00'))?>" id="class_closetime" readonly>
								<?php endif; ?>
							
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_class_name','授業名') ?></th>
							<td>
								<input type="text" name="class_name" size="45" value="<?=set_value('class_name',$class['class_name'])?>">
								<!-- <input type=hidden name=class_name value='<?='';//set_value('class_name', $class['class_name'])?>'><?= htmlspecialchars( $class['class_name'], ENT_QUOTES, 'UTF-8') ?> -->
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<input type=hidden name=teacher_id value='<?=set_value('teacher_id', $class['teacher_id'])?>'>
								<?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_teacher','講師') ?></th>
							<td>
								<?php $count = 0; ?>
								<?php if(isset($class['sub_teacher_id'])) { ?>
									<?php foreach($class['sub_teacher_id'] as $class_teacher) { ?>
										<input type="hidden" name="sub_teacher_id[]" value='<?= htmlspecialchars( $class_teacher, ENT_QUOTES, 'UTF-8') ?>'>
										<?php $count = $count + 1; ?>
									<?php } ?>
								<?php } ?>
								<?php //for($i=$count; $i<5; $i++) { ?>
								<!--	<input type="hidden" name="sub_teacher_id[]" value=''>-->
								<?php //} ?>
								<?= htmlspecialchars( $class['sub_teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="class_caption" ><?=set_value('class_caption',$class['class_caption'])?></textarea>
								<!-- <input type=hidden name=class_caption value='<?='';//set_value('class_caption', $class['class_caption'])?>'><?=nl2br( htmlspecialchars( $class['class_caption'], ENT_QUOTES, 'UTF-8') )?> -->
							</td>
						</tr>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_student','受講者') ?>
								<a href="#" onclick="select_all();return false;" id="select_all" name="select_all"></a>
								<div id="student_group_list">
									<?php if( isset($student_group) ): ?>
										<?php foreach( $student_group as $key => $value): ?>
											<div>
												<a onclick="select_student_group('<?= htmlspecialchars( $value, ENT_QUOTES, 'UTF-8') ?>');return false;" href="#"><?= htmlspecialchars( $key, ENT_QUOTES, 'UTF-8') ?></a>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
								<?php if( isset($student_group) ): ?>
									<div id="student_group_list_msg">※上記グループ名選択により、同グループ受講者にチェックが付加される</div>
								<?php endif; ?>
							</th>
							<td>
								<div class="student_list">
									<?php if( isset($students) ): ?> 
										<?php foreach( $students as $student ): ?>
											<div class="students">
												<?php if( isset($class['lecture_students']) ): ?>

													<?php $checkflag = 0; ?>
													<?php foreach( $class['lecture_students'] as $lecture): ?>
														<?php if($lecture == $student['student_id']): ?>
															■
															<input type="hidden" name="lecture_students[]"     id="student_<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?> >
															<input type="hidden" name="lecture_students_old[]" id="student_<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?> >
															<?php $checkflag = 1; ?>
															<?php break; ?>
														<?php endif; ?>
													<?php endforeach; ?>
													
													<?php if($checkflag == 0): ?>
														<input type="checkbox" name="lecture_students[]" id="student_<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?> >
													<?php endif; ?>
													
												<?php endif; ?>
												<label for="student_<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>">[No<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>]&nbsp;<?= htmlspecialchars( $student['student_name'], ENT_QUOTES, 'UTF-8') ?>&nbsp;&lt;<?= htmlspecialchars( $student['student_email'], ENT_QUOTES, 'UTF-8') ?>&gt;</label>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td >
								<textarea name="class_note" ><?=set_value('class_note',$class['class_note'])?></textarea>
								<!-- <input type=hidden name=class_note value='<?='';//set_value('class_note', $class['class_note'])?>'><?=nl2br( htmlspecialchars( $class['class_note'], ENT_QUOTES, 'UTF-8') )?> -->
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
