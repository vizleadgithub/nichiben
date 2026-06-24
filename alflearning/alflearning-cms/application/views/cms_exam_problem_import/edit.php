<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* h2内部 */
		#h2_title{
			color       : #fefefe;
			float       : left;
			line-height : 25px;
			width       : 55%;
		}
		#h2_result{
			float         : right;
			font-weight   : bold;
			line-height   : 25px;
			padding-right : 5px;
			text-align    : right;
			width         : 43%;
		}
		/* 管理講師「別の講師が選択されています」 */
		#teacher_change_message{
			color : #00A4E2;
		}
		/* 所属講座, 設問グループ */
		.cource_list, .group_list, .cource_list_ex, .group_list_ex{
			max-height	: 300px;
			overflow-y	: scroll;
		}
			/* 所属講座選択エリア, 設問グループ選択エリア */
			#cource_ul, #group_ul, #cource_ul_ex, #group_ul_ex{
				margin-top: 0px;
			}
				#cource_ul li, #group_ul li, #cource_ul_ex li, #group_ul_ex li{
					margin-left: 2px;
					margin-bottom: 0px;
				}
		/* ファイル 説明文 */
		.pdf_message{
			color     : #FF0000;
			font-size : 11px;
		}
	</style>

	<script type="text/javascript"><!--
		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
			// 講師コンボボックス変更時の処理
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
			// 所属講座「全て選択」ボタンの初期位置
			if($('.cource_list input:checked').length == 0){
				$("*[name=select_all_cource]").css("background-position", "center top");
			}else{
				$("*[name=select_all_cource]").css("background-position", "center bottom");
			}
			// 所属講座 配下のチェックボックス押下時の処理
			$('.cource_list').click(function (){
				if($('.cource_list input:checked').length == 0){
					$("*[name=select_all_cource]").css("background-position", "center top");
				}else{
					$("*[name=select_all_cource]").css("background-position", "center bottom");
				}
			});
			// 設問グループ「全て選択」ボタンの初期位置
			if($('.group_list input:checked').length == 0){
				$("*[name=select_all_group]").css("background-position", "center top");
			}else{
				$("*[name=select_all_group]").css("background-position", "center bottom");
			}
			// 設問グループ 配下のチェックボックス押下時の処理
			$('.group_list').click(function (){
				if($('.group_list input:checked').length == 0){
					$("*[name=select_all_group]").css("background-position", "center top");
				}else{
					$("*[name=select_all_group]").css("background-position", "center bottom");
				}
			});
		});

		//**************************************************
		// 画面読み込み後、講師コンボボックスの設定
		//**************************************************
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
			}else{
				$('#teacher_change_message').hide();
			}
		});
		//**************************************************
		// 所属講座「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_course(){
			if($('.cource_list input:checked').length){
				$('.cource_list input').removeAttr('checked');
				$("*[name=select_all_cource]").css("background-position", "center top");
			}
			else{
				$('.cource_list input').attr('checked','checked');
				$("*[name=select_all_cource]").css("background-position", "center bottom");
			}
			return false;
		}
		//**************************************************
		// 設問グループ「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_group(){
			if($('.group_list input:checked').length){
				$('.group_list input').removeAttr('checked');
				$("*[name=select_all_group]").css("background-position", "center top");
			}
			else{
				$('.group_list input').attr('checked','checked');
				$("*[name=select_all_group]").css("background-position", "center bottom");
			}
			return false;
		}
		//**************************************************
		// 所属講座「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_course_ex(){
			if($('.cource_list_ex input:checked').length){
				$('.cource_list_ex input').removeAttr('checked');
				$("*[name=select_all_cource_ex]").css("background-position", "center top");
			}
			else{
				$('.cource_list_ex input').attr('checked','checked');
				$("*[name=select_all_cource_ex]").css("background-position", "center bottom");
			}
			return false;
		}
		//**************************************************
		// 設問グループ「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_group_ex(){
			if($('.group_list_ex input:checked').length){
				$('.group_list_ex input').removeAttr('checked');
				$("*[name=select_all_group_ex]").css("background-position", "center top");
			}
			else{
				$('.group_list_ex input').attr('checked','checked');
				$("*[name=select_all_group_ex]").css("background-position", "center bottom");
			}
			return false;
		}
	// --></script> 
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam','問題管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam_comment','問題（テスト）を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam_problem_import/_submenu', array(
				'selected'	=> 'exam_problem_import',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix" style="min-height: 18px;">
				</div>
<!--
				<h2>
					<?= $this->lang->line_or_def('msg_exam_problem_import_input','設問にインポートするファイルを入力してください') ?>
					<?= str_repeat("&nbsp;", 30);?><?= $this->lang->line_or_def('msg_exam_problem_import_sample','インポート用ファイルのサンプル') ?>&nbsp;&gt;&gt;&nbsp;<a href="/static/psd/exam_problem_import.zip">Download</a>
				</h2>
 -->
				<h2>
					<div id="h2_title"><!--<?= $this->lang->line_or_def('msg_exam_problem_import_input','設問にインポートするファイルを入力してください') ?>-->設問のインポート/エクスポートが行えます</div>
					<div id="h2_result"><?= $this->lang->line_or_def('msg_exam_problem_import_sample','インポート用ファイルのサンプル') ?>&nbsp;&gt;&gt;&nbsp;<a href="/static/psd/exam_problem_import.zip">Download</a></div>
					<div style="clear:both;"></div>
				</h2>
 
 
				<?=form_open_multipart("cms_exam_problem_import/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.$upload_error.'</div>':'')?>
					<?=(isset($lecture_error)?'<div class="error">'.$lecture_error.'</div>':'')?>
					<?=(isset($local_file_error)?'<div class="error">'.$local_file_error.'</div>':'')?>
					<table class="form">
						<tr>
							<th colspan="2">■インポート</th>
						</tr>

<?php if(false){ ?>
						<? if( getenv('URL_SERVICE')=='mitemo' ): ?>
							<input type="hidden" name="teacher_id"   value='<?=set_value('teacher_id',   $exam_problem['teacher_id'])?>'>
						<? else: ?>
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<?=form_dropdown('teacher_id',$teachers_dropdown,set_value('teacher_id',$exam_problem['teacher_id']), 'id="teacher_id"'); ?>
								&nbsp;<lavel id="teacher_change_message" style=""><?= $this->lang->line_or_def('msg_different_teacher','別の講師が選択されています') ?></label>
							</td>
						</tr>
						<? endif; ?>
<?php } ?>
						<input type="hidden" name="teacher_id"   value='<?=set_value('teacher_id',   $exam_problem['teacher_id'])?>'>

						<tr>
							<th width="160" >
								<?= $this->lang->line_or_def('common_position_course','所属講座') ?>
								<a href="#" onclick="select_all_course();return false;" class="select_all_affiliation" name="select_all_cource"></a>
							</th>
							<td>
								<div class="cource_list">
									<ul class="list" id="cource_ul">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<li>
												<input type="checkbox" name="exam_problem_lectures[]" id="lectures_<?=$cource['cource_id']?>" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($exam_problem['exam_problem_lectures']) ) {
														foreach( $exam_problem['exam_problem_lectures'] as $lecture) { 
															if($lecture == $cource['cource_id']) {
														?>
																checked
																<?php
																break;
															}
														}
													}
													?>
													>
												<label for="lectures_<?=$cource['cource_id']?>"><?=$cource['cource_name']?></label>
												</li>
										<?php 
										}
									}else{ ?>
										<li>---</li>
									<?php
									}
									?>
										
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<th >
								<?= $this->lang->line_or_def('common_exam_problem_group','設問グループ') ?>
								<a href="#" onclick="select_all_group();return false;" class="select_all_affiliation" name="select_all_group"></a>
							</th>
							<td>
								<div class="group_list">
									<ul class="list" id="group_ul">
									<?php if( isset($exam_problem_groups) ): ?>
										<?php foreach( $exam_problem_groups as $exam_problem_group ): ?>
											<li>
												<?php
													$checked_flag = '';
													if( isset($exam_problem['exam_problem_groups']) ) {
														foreach( $exam_problem['exam_problem_groups'] as $group) { 
															if($group == $exam_problem_group['exam_problem_group_id']) {
																$checked_flag = 'checked';
																break;
															}
														}
													}
												?>
												<input type="checkbox" name="exam_problem_groups[]" id="groups_<?=$exam_problem_group['exam_problem_group_id']?>" value=<?=$exam_problem_group['exam_problem_group_id']?> <?= $checked_flag ?> />
												<label for="groups_<?=$exam_problem_group['exam_problem_group_id']?>">
													<?=$exam_problem_group['exam_problem_group_name']?>&nbsp;&nbsp;[<?= $this->lang->line_or_def('common_position_exam_problem_count','所属設問数') ?>&nbsp;:&nbsp;<?=$exam_problem_group['exam_problem_count'];?>]
												</label>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
										<li>---</li>
									<?php endif; ?>
									</ul>
								</div>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_file','ファイル') ?></th>
							<td>
								<input type="file" name="local_file" size="30" value="<?=set_value('local_file',$exam_problem['local_file'])?>">
								<div class="pdf_message">
									Excel-csv形式
								</div>
							</td>
						</tr>

					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_ok.png' />
					</div>
				</form>
				
				<?=form_open_multipart("cms_exam_problem_import/export")?>
					<table class="form">
						<tr>
							<th colspan="2">■エクスポート</th>
						</tr>
						<tr>
							<th width="160" >
								<?= $this->lang->line_or_def('common_position_course','所属講座') ?>
								<a href="#" onclick="select_all_course_ex();return false;" class="select_all_affiliation" name="select_all_cource_ex"></a>
							</th>
							<td>
								<div class="cource_list_ex">
									<ul class="list" id="cource_ul_ex">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<li>
												<input type="checkbox" name="exam_problem_lectures_ex[]" id="lectures_<?=$cource['cource_id']?>_ex" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($exam_problem['exam_problem_lectures']) ) {
														foreach( $exam_problem['exam_problem_lectures'] as $lecture) { 
															if($lecture == $cource['cource_id']) {
														?>
																checked
																<?php
																break;
															}
														}
													}
													?>
													>
												<label for="lectures_<?=$cource['cource_id']?>_ex"><?=$cource['cource_name']?></label>
												</li>
										<?php 
										}
									}else{ ?>
										<li>---</li>
									<?php
									}
									?>
										
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<th >
								<?= $this->lang->line_or_def('common_exam_problem_group','設問グループ') ?>
								<a href="#" onclick="select_all_group_ex();return false;" class="select_all_affiliation" name="select_all_group_ex"></a>
							</th>
							<td>
								<div class="group_list_ex">
									<ul class="list" id="group_ul_ex">
									<?php if( isset($exam_problem_groups) ): ?>
										<?php foreach( $exam_problem_groups as $exam_problem_group ): ?>
											<li>
												<?php
													$checked_flag = '';
													if( isset($exam_problem['exam_problem_groups']) ) {
														foreach( $exam_problem['exam_problem_groups'] as $group) { 
															if($group == $exam_problem_group['exam_problem_group_id']) {
																$checked_flag = 'checked';
																break;
															}
														}
													}
												?>
												<input type="checkbox" name="exam_problem_groups_ex[]" id="groups_<?=$exam_problem_group['exam_problem_group_id']?>_ex" value=<?=$exam_problem_group['exam_problem_group_id']?> <?= $checked_flag ?> />
												<label for="groups_<?=$exam_problem_group['exam_problem_group_id']?>_ex">
													<?=$exam_problem_group['exam_problem_group_name']?>&nbsp;&nbsp;[<?= $this->lang->line_or_def('common_position_exam_problem_count','所属設問数') ?>&nbsp;:&nbsp;<?=$exam_problem_group['exam_problem_count'];?>]
												</label>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
										<li>---</li>
									<?php endif; ?>
									</ul>
								</div>
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_ok.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
