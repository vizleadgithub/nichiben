<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "issue";
	$this->load->view('header/header',$data);?>
	<style>
	.cource_list{
		max-height	: 300px;
		overflow-y	: scroll;
	}
	.cources{
		border-bottom	: 1px dotted silver;
		margin			: 0px 0px 3px 0px;
		padding			: 0px 2px 0px 7px;
	}
		.cources input, .courcets label{
			height		: 17px;
			line-height	: 17px;
		}

	/* 所属講座選択エリア */
	#cource_ul{
		margin-top: 0px;
	}
	#cource_ul li{
		margin-left: 2px;
		margin-bottom: 0px;
	}

	</style>
	<script type="text/javascript"><!--
		$(function(){
			//日付項目クリアリンク
		//	$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datepicker設定
		//	$('#information_date' ).datepicker(datepickeroption);
			//datetimepicker設定
			$('#issue_open' ).datetimepicker(datetimepickeroption);
			$('#issue_close').datetimepicker(datetimepickeroption);
			
			// 講座リスト
			if($('.cource_list input:checked').length == 0){
				$("#select_all").css("background-position", "center top");
			}else{
				$("#select_all").css("background-position", "center bottom");
			}
			
			$('.cource_list').click(function (){
				if($('.cource_list input:checked').length == 0){
					$("#select_all").css("background-position", "center top");
				}else{
					$("#select_all").css("background-position", "center bottom");
				}
			});
			
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
		});

		function select_all(){
			if($('.cource_list input:checked').length){
				$('.cource_list input').removeAttr('checked');
				$("#select_all").css("background-position", "center top");
			}
			else{
				$('.cource_list input').attr('checked','checked');
				$("#select_all").css("background-position", "center bottom");
			}
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
	// --></script> 
	<style type="text/css">
		TEXTAREA{
			width	: 100%;
			height	: 100px;
		}
		.pdf_message{
			color				: #FF0000;
			font-size			: 11px;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_issue','課題管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_issue_comment','課題を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_issue/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_issue/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_issue/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_issue_input','課題の情報を入力してください') ?></h2>

				<?=form_open_multipart("cms_issue/commit")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<input type="hidden" name="update_flg" value='<?=set_value('update_flg', $issue['update_flg'])?>'>
					<input type="hidden" name="issue_id"   value='<?=set_value('issue_id',   $issue['issue_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_issue_name','課題名') ?></th>
							<td>
								<input type=text name="issue_name" maxlength="256" size="30" value='<?=set_value('issue_name',$issue['issue_name'])?>'>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<?=form_dropdown('teacher_id',$teachers_dropdown,set_value('teacher_id',$issue['teacher_id']), 'id="teacher_id"'); ?>
								&nbsp;<lavel id="teacher_change_message" style="color:#00A4E2;"><?= $this->lang->line_or_def('msg_different_teacher','別の講師が選択されています') ?></label>
							</td>
						</tr>

						<tr>
							<th >
								<?= $this->lang->line_or_def('common_position_course','所属講座') ?>
								<a href="#" onclick="select_all();return false;" id="select_all" name="select_all"></a>
							</th>
							<td>
								<div class="cource_list">
									<ul class="list" id="cource_ul">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<li>
												<input type="checkbox" name="issue_lectures[]" id="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>
													<?php 
													if( isset($issue['issue_lectures']) ) {
														foreach( $issue['issue_lectures'] as $lecture) { 
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
												<label for="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $cource['cource_name'], ENT_QUOTES, 'UTF-8') ?></label>
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
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="issue_caption" ><?=set_value('issue_caption',$issue['issue_caption'])?></textarea>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_submit_period','提出期間') ?></th>
							<td >
								<input type="text" name="issue_open" size="19" value="<?=set_value('issue_open',$issue['issue_open'])?>" id="issue_open" readonly>
								<?= $this->lang->line_or_def('common_range','～') ?>
								<input type="text" name="issue_close" size="19" value="<?=set_value('issue_close',$issue['issue_close'])?>" id="issue_close" readonly>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_indication_status','表示状態') ?></th>
							<td >
								<?=form_dropdown('public_flag', $public_flag_list, set_value('public_flag', $issue['public_flag']));?>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_123','雛形') ?></th>
							<td>
								<table style="width: 100%;"> <!-- width: 625px;  -->
									<tr>
										<th style="width: 280px;"><?= $this->lang->line_or_def('common_123','雛形論理名') ?></th>
										<th style="width: 240px;"><?= $this->lang->line_or_def('common_123','雛形ファイル') ?></th>
										<th style="vertical-align: middle;text-align: center;"><?= $this->lang->line_or_def('common_123','削除') ?></th>
									</tr>
									<?php foreach($issue['issue_temp_logic_name'] as $id => $value): ?>
										<tr>
											<td style="vertical-align: middle;text-align: center;">
												<input type="hidden" name="issue_temp_id[]" value='<?=set_value('issue_temp_id[]', $issue['issue_temp_id'][$id] ); ?>'>
												<input type="text" name="issue_temp_logic_name[]" maxlength="256" size="20" value='<?= htmlspecialchars( $value, ENT_QUOTES, 'UTF-8') ?>'>
											</td>
											<td style="vertical-align: middle;text-align: center;">
												<input type="hidden" name="issue_temp_name[]" value='<?=set_value('issue_temp_name[]', $issue['issue_temp_name'][$id] ); ?>'>
												<? if($issue['issue_temp_id'][$id] < 0): ?>
													<input type="file" name="issue_temp_file_<?= htmlspecialchars( $id, ENT_QUOTES, 'UTF-8') ?>" size="30" value=''>
												<? else: ?>
													<? $thumbnailName = $issue['issue_temp_name'][$id] ?>
													<img src="/file_container/get_issue_template_thubmnail/<?= $this->libauth->get_school_id(); ?>/<?= htmlspecialchars( $issue['issue_id'], ENT_QUOTES, 'UTF-8') ?>/<?= $issue['issue_temp_id'][$id]; ?>/<?= htmlspecialchars( $issue['issue_temp_name'][$id], ENT_QUOTES, 'UTF-8') ?>/" alt="" style="height: 64px; padding:1px;background-color:white;"/ name="">
												<? endif; ?>
											</td>
											<td style="vertical-align: middle;text-align: center;">
												<input type="checkbox" value="<?= $issue['issue_temp_id'][$id]; ?>" name="issue_temp_delete[]" <?= ($issue['issue_temp_id'][$id] < 0) ? 'disabled="disabled"' : '';  ?> >
											</td>
										</tr>
									<?php endforeach; ?>

								</table>
								<div style="color: #FF0000; font-size: 11px;">画像ファイル以外の場合、１頁目が対象となりますのでご注意ください。。</div>
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
