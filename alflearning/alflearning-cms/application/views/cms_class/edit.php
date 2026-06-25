<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>


	<style type="text/css"><!--
		TEXTAREA{
			width : 100%;
			height : 70px;
		}
		TEXTAREA[name="class_caption"]{
			height : 200px;
		}
		
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

		/* 所属受講生選択エリア */
		#student_ul{
			margin-top: 0px;
		}
		#student_ul li{
			margin-left: 2px;
			margin-bottom: 0px;
		}
		
		.sub_teacher_id{
			margin-top		: 5px;
			margin-bottom	: 5px;
		}

		#student_group_list{
			display	: none;
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
			display			: none;
			text-indent		: -1em; 
			margin-left		: 1em;
		}

	// --></style>
	
	<script type="text/javascript">
		var students_checked      = {};

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
				
				,Minhour : '10'
				,Maxhour : '23'
				
				,showButtonPanel  : true
				,currentText: '現在'
				,closeText: '閉じる'
				,timeText: '時間'
				,hourText: '時'
				,minuteText: '分'
				,secondText: '秒'
			});

			// [2012/08/30]講師コンボボックス変更時の処理
			$("#teacher_id").bind("change keyup",function(){
				var $default_teacher_id  = '<?=$this->libauth->get_teacher_id();?>';	// ログイン中のログインID
				var $selected_teacher_id = $('#teacher_id option:selected').val();		// 現在選択されたもの

				if($default_teacher_id != -1){
					if($default_teacher_id == $selected_teacher_id){
						$('#teacher_id').css("color", "#FEFEFE");
						$('#teacher_id').css("background-color", "#333333");
						$('#teacher_change_message').hide();
					}else{
						$('#teacher_id').css("color", "black");
						$('#teacher_id').css("background-color", "white");
						$('#teacher_change_message').show();
					}
					$("#teacher_id option").each(function(i){
						if($(this).val() == $default_teacher_id){
							$(this).css("color", "#FEFEFE"); 
							$(this).css("background-color", "#333333"); 
						}else{
							$(this).css("color", "black"); 
							$(this).css("background-color", "white"); 
						}
					})
				}
			});
			
			// [2012/11/30]講座所属の受講生情報の取得
			<?php if( isset($class['lecture_students']) ) {
				foreach( $class['lecture_students'] as $lecture) { ?>
					students_checked[<?= htmlspecialchars( $lecture, ENT_QUOTES, 'UTF-8') ?>] = true;
			<?php } } ?>

			// [2012/11/30]講座所属の受講者の表示（初期表示）
			ajax_search_cource_students(<?= htmlspecialchars( $class['cource_id'], ENT_QUOTES, 'UTF-8') ?>);

			// [2012/11/30]講座名変更時の処理
			$("*[name=cource_id]").change(function() {
				var cource_id = $("*[name=cource_id]").val();
				ajax_search_cource_students(cource_id);
			});

			// [2012/11/30]チェックボックスと全選択ボタン連動（受講者）
			$('#student_list').click(function (){
				$("#student_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						students_checked[$(this).val()] = true;
					}else{
						delete students_checked[$(this).val()];
					}
				});
				if($('#student_list input:checked').length == 0){
					$(".select_all_affiliation.select_student").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_student").css("background-position", "center bottom");
				}
			});
		});

		// [ajax]講座所属の受講者取得
		function ajax_search_cource_students(cource_id){
			var select_student_id = "0";
			
			$.ajax({
				url: "/cms_cource/get_cource_student",
				type: "POST",
				data: "cource_id="+cource_id+"&cource_flag=1",
				
				success: function(response) {
					// 元にある行を削除
					$("#student_ul li").remove();
					
					// 全て選択ボタンの初期化
					$(".select_all_affiliation.select_student").css("background-position", "center top");
					
					if(response){
						// 取得したデータを行に入れる
						for (var i=0; i< response.length; i++) {
							select_student_id = ""+select_student_id+","+response[i]['student_id']+""; 
							var check_flag = '';
							if(students_checked[response[i]['student_id']]){
								check_flag = 'checked';
								$(".select_all_affiliation.select_student").css("background-position", "center bottom");	// 選択済みが１つ以上ある場合、ボタン位置変更
							}
							$("#student_ul").append(
								$('<li>').append(
									$('<input type="checkbox" name="lecture_students[]" id="student_'+response[i]['student_id']+'" value='+response[i]['student_id']+' '+check_flag+'>')
								).append(
								$('<label for="student_'+response[i]['student_id']+'">').text(
									' [No'+response[i]['student_id']+'] '+response[i]['student_name'] + ' <' + response[i]['student_email'] + '>'
								))
							);
						}
						
						// ajax 実行により、グループ名=>受講者IDを取得
						$.ajax({
							url: "/cms_student_group/get_cource_student_group",
							type: "POST",
							data: "select_student_id="+select_student_id,
							
							success: function(response) {
								// 元にあるリンクを削除
								$("#student_group_list DIV").remove();
								if(response){
									// 取得したデータを行に入れる
									for (var keyString in response) {
										$("#student_group_list").append(
											$('<div>').append(
												$('<a href="#" onclick="select_student_group('+"'"+response[keyString]+"'"+');return false;">'+keyString+'</a>')
											)
										);
									}
									$("#student_group_list").css('display','block');
									$("#student_group_list_msg").css('display','block');
								}else{
									$("#student_group_list").css('display','none');
									$("#student_group_list_msg").css('display','none');
								}
							}
						});
						
						$(".select_all_affiliation.select_student").css('display','block');
					}else{
						$("#student_ul").append(
							$('<li style="border-bottom: 0px solid silver;">').append('---'))
						$(".select_all_affiliation.select_student").css('display','none');
					}
				}
			});
			return false;
		}

		// 所属受講者チェックボックスALL-ON or ALL-OFF
		function select_all_student(){
			if($('#student_list input:checked').length){
				$('#student_list input').removeAttr('checked');
				$(".select_all_affiliation.select_student").css("background-position", "center top");
			}else{
				$('#student_list input').attr('checked','checked');
				$(".select_all_affiliation.select_student").css("background-position", "center bottom");
			}
			
			// [2012/11/30]値の初期化
			$("#student_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					students_checked[$(this).val()] = true;
				}else{
					delete students_checked[$(this).val()];
				}
			});
			return false;
		}
		
		// [2012/08/30]画面読み込み後、講師コンボボックスの設定
		$(function () {
			var $default_teacher_id  = '<?=$this->libauth->get_teacher_id();?>';	// ログイン中のログインID
			var $selected_teacher_id = $('#teacher_id option:selected').val();		// 現在選択されたもの
			
			if($default_teacher_id != -1){
				if($default_teacher_id == $selected_teacher_id){
					$('#teacher_id').css("color", "#FEFEFE");
					$('#teacher_id').css("background-color", "#333333");
					$('#teacher_change_message').hide();
				}else{
					$('#teacher_id').css("color", "black");
					$('#teacher_id').css("background-color", "white");
					$('#teacher_change_message').show();
				}
				$("#teacher_id option").each(function(i){
					if($(this).val() == $default_teacher_id){
						$(this).css("color", "#FEFEFE"); 
						$(this).css("background-color", "#333333"); 
					}else{
						$(this).css("color", "black"); 
						$(this).css("background-color", "white"); 
					}
				})
			//	$('#teacher_id').css("color", "#FEFEFE");				// コンボボックス内、文字色を変更。
			//	$('#teacher_id').css("background-color", "#333333");	// コンボボックス内、背景色を変更。
			//	$('#teacher_id option').css("color", "black");				// コンボボックスのドロップダウンリストの文字色を変更。
			//	$('#teacher_id option').css("background-color", "white");	// コンボボックスのドロップダウンリストの背景色を変更。
			//	$('#teacher_id option:selected').css("color", "#FEFEFE");					// コンボボックスのドロップダウンリストの選択リストの文字色を変更。
			//	$('#teacher_id option:selected').css("background-color", "#333333");		// コンボボックスのドロップダウンリストの選択リストの背景色を変更。
			}else{
				$('#teacher_change_message').hide();
			}
		});
		
		// グループ名リンク押下時の処理
		function select_student_group(student_id_list){
			if(student_id_list.length == 0){
				return false;
			}
			
			var resArray = student_id_list.split(",");
			
			//$('#student_list input').removeAttr('checked');
			
			for (var i = 0; i < resArray.length; i ++) {
				$('#student_list #student_'+resArray[i]).attr('checked','checked');
			}

			if($('#student_list input:checked').length){
				$(".select_all_affiliation.select_student").css("background-position", "center bottom");
			}else{
				$(".select_all_affiliation.select_student").css('background-position', 'center top');
			}

			
			$("#student_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					students_checked[$(this).val()] = true;
				}else{
					delete students_checked[$(this).val()];
				}
			});
			var check_count = 0;
			for (var key in students_checked) {
				check_count = check_count + 1;
			}
			$("#student_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			
			return false;
		}

	</script>
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
								<?=form_dropdown('cource_id',$cources_dropdown,set_value('cource_id', $class['cource_id']));?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_class_type','授業タイプ') ?></th>
							<td>
								<?=form_dropdown('class_type', array(
									''	=> $this->lang->line_or_def('common_null_class','授業タイプを選択して下さい'),
									'school'	=> $this->lang->line_or_def('common_nomal_class','一般授業'),
									'auditor'	=> $this->lang->line_or_def('common_attend_class','聴講授業'),
								), set_value('class_type', $class['class_type']));?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_datetime','日時') ?></th>
							<td>
								<input type="text" name="class_date" size="10" value="<?=set_value('class_date', ($class['class_date'] ? $class['class_date'] : date('Y/m/d')))?>" id="class_date" readonly>
								<input type="text" name="class_opentime"  size="6" value="<?=set_value('class_opentime', ($class['class_opentime'] ? $class['class_opentime'] : date('H:00:00', time()+(60*60))))?>"   id="class_opentime" readonly>
								&nbsp;<?= $this->lang->line_or_def('common_class_time','授業時間') ?>&nbsp;<input type="text" name="class_closetime" size="6" value="<?=set_value('class_closetime', ($class['class_closetime'] ? date('H:i', strtotime($class['class_closetime']) - strtotime($class['class_opentime']) - 32400) : '01:00'))?>" id="class_closetime" readonly>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_class_name','授業名') ?></th>
							<td>
								<input type="text" name="class_name" size="45" value="<?=set_value('class_name',$class['class_name'])?>">
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<? //担当授業 or SuperUser or 学校管理者 のパターン ?>
								<? //$work_auth = $this->libauth->get_teacher_auth(); ?>
								<? //if( ($class['teacher_id'] == $this->libauth->get_teacher_id()) || ($work_auth['school_admin'] == 1) || ($this->libauth->get_teacher_id() < 0) ): ?>
								
								<? //担当授業 or SuperUser のパターン ?>
								<? if($class['update_flg']==0): ?>
									<?= form_dropdown('teacher_id',$teachers_dropdown,set_value('teacher_id',$class['teacher_id']), 'id="teacher_id"'); ?>
									&nbsp;<lavel id="teacher_change_message" style="color:#00A4E2;"><?= $this->lang->line_or_def('msg_different_teacher','別の講師が選択されています') ?></label>
								<? else: ?>
									<? if( ($class['teacher_id'] == $this->libauth->get_teacher_id()) || ($this->libauth->get_teacher_id() < 0) ): ?>
										<?= form_dropdown('teacher_id',$teachers_dropdown,set_value('teacher_id',$class['teacher_id']), 'id="teacher_id"'); ?>
										&nbsp;<lavel id="teacher_change_message" style="color:#00A4E2;"><?= $this->lang->line_or_def('msg_different_teacher','別の講師が選択されています') ?></label>
									<? else: ?>
										<input type="hidden" name="teacher_id" value='<?=set_value('teacher_id',$class['teacher_id'])?>'>
										<?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
									<? endif; ?>
								<? endif; ?>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_teacher','講師') ?></th>
							<td>
								<?php $count = 0; ?>
								<?php if(isset($class['sub_teacher_id'])) { ?>
									<?php foreach($class['sub_teacher_id'] as $class_teacher) { ?>
										<div class="sub_teacher_id"><?=form_dropdown('sub_teacher_id[]',$teachers_dropdown,set_value('sub_teacher_id[]',$class_teacher)); ?></div>
										<?php $count = $count + 1; ?>
									<?php } ?>
								<?php } ?>
								<?php for($i=$count; $i<5; $i++) { ?>
									<div class="sub_teacher_id"><?=form_dropdown('sub_teacher_id[]',$teachers_dropdown,set_value('sub_teacher_id[]','')); ?></div>
								<?php } ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="class_caption" ><?=set_value('class_caption',$class['class_caption'])?></textarea>
							</td>
						</tr>

						<tr>
							<th>
								<?= $this->lang->line_or_def('common_student','受講者') ?>
								<a href="#" onclick="select_all_student();return false;" class="select_all_affiliation select_student" style="display:none;"></a>
								<div id="student_group_list"></div>
								<div id="student_group_list_msg">※上記グループ名選択により、同グループ受講者にチェックが付加される</div>
							</th>
							<td>
								<div id="student_list">
									<ul class="list" id="student_ul"><li>---</li></ul>
								</div>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td >
								<textarea name="class_note" ><?=set_value('class_note',$class['class_note'])?></textarea>
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
