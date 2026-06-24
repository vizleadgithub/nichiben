<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 設問 ・ 解答 */
		.confirm_ul_detail{
			overflow-y : scroll;
			width      : 610px;    /* 640px; */
			min-height : 200px;
		}
			.confirm_ul_li_div{
				float   : left;
				padding : 5px;
			}
			.confirm_ul_li_line{
				-moz-border-bottom-colors : none;
				-moz-border-left-colors   : none;
				-moz-border-right-colors  : none;
				-moz-border-top-colors    : none;
				border-color              : #808080;
				border-image              : none;
				border-style              : none none solid none;
				border-width              : 1px;
			}
				.confirm_ul_li_div_detail{
					float      : left;
					padding    : 5px;
					line-height: 24px;
				}
				.detail_area{
					display:none;
					margin: 0 0 0 10px;
				}
					.confirm_ul_li_line_dotted{
						-moz-border-bottom-colors : none;
						-moz-border-left-colors   : none;
						-moz-border-right-colors  : none;
						-moz-border-top-colors    : none;
						border-color              : #808080;
						border-image              : none;
						border-style              : dotted none none none ;
						border-width              : 1px;
					}
						.confirm_ul_li_line_dotted .detail_title{
							float       : left;
							line-height : 20px;
							margin      : 5px 0 0 0;
							width       : 150px;
						}
						.confirm_ul_li_line_dotted .detail_values{
							float       : left;
							line-height : 20px;
							margin      : 5px 0 0 0;
							width       : 400px;
							word-break  : break-all;
						}
						.confirm_ul_li_line_dotted .detail_answer{
							<? //style="float:left;width:250px;margin:5px 0 5px 300px;  line-height: 20px;   " ?>
							float       : right;
							line-height : 20px;
							margin      : 5px 46px 5px 0;
						}
							.confirm_ul_li_line_dotted .detail_answer .exam_answer_mark{
								float  : left;
								margin : 0 0 0 0;
							}
							.confirm_ul_li_line_dotted .detail_answer .exam_answer_point{
								float  : left;
								margin : 0 0 0 30px;
							}
							.confirm_ul_li_line_dotted .detail_answer input[type="text"]{
								height     : 20px;
								text-align : right;
								width      : 70px;
							}
		/* csvダウンロード */
		.div_download{
			display:block;margin-top: 10px;
		}
		#csv_link_latest_summary, #csv_link_summary, #csv_link_detail{
			border-color: #888888 #888888 #888888 #C0C0C0;
			border-image: none;
			border-radius: 4px;
			border-style: solid;
			border-width: 1px 1px 1px 19px;
			color: #333333;
			display: block;
			font-size: 11px;
			font-weight: bold;
			height: 18px;
			line-height: 18px;
			overflow: hidden;
			padding-left: 2px;
			text-decoration: none;
			width: 138px;
		}
		#csv_link_latest_summary_none, #csv_link_summary_none, #csv_link_detail_none{
			border-color: #888888 #888888 #888888 #C0C0C0;
			border-image: none;
			border-radius: 4px;
			border-style: solid;
			border-width: 1px 1px 1px 19px;
			color: #888888;
			display: block;
			font-size: 11px;
			font-weight: bold;
			height: 18px;
			line-height: 18px;
			overflow: hidden;
			padding-left: 2px;
			text-decoration: none;
			width: 138px;
			display:none;
			text-align: center;
		}
	</style>
	<script type="text/javascript">
		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
			// 課題提出物
			// 同一受講者の提出物ありの場合は初期表示時は最新提出物のみ表示するように対応
			
			// 重複提出物の非表示
			$("*[name=not_latest]").css("display", "none");
			
			// チェックボックスにより表示を切り替え
			$('#show_answer').change(function(){
				if ($(this).is(':checked')) {
					$("*[name=not_latest]").css("display", "none");
				} else {
					$("*[name=not_latest]").css("display", "");
				}
			});
			
		//	// Ajax読み込みに変更に伴い不要
		//	$("*[name='exam_answer_mark[]']").bind("change keyup",function(){
		//		var exam_answer_mark = $(this).val();
		//		var answer_point     = $(this).next("*[name='answer_point[]']").val();
		//		if(exam_answer_mark==0){
		//			// 不正解
		//			$(this).parents(".confirm_ul_li_line_dotted").find("*[name='exam_answer_point[]']").val(0);
		//		}else{
		//			// 正解
		//			$(this).parents(".confirm_ul_li_line_dotted").find("*[name='exam_answer_point[]']").val(answer_point);
		//		}
		//	});
		
		});
		
		//**************************************************
		//詳細確認画面　削除ボタン押下
		//**************************************************
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_exam/delete_item/" + id ;
			}
		}
		//**************************************************
		//詳細確認画面　修正ボタン押下
		//**************************************************
		function edit_item(){
			//location.href ="<?=base_url()?>cms_exam/edit/";
			
			// Set Form
			var form = document.createElement("form");
			form.setAttribute("action", "<?=base_url()?>cms_exam/edit/");
			form.setAttribute("method", "post");
			form.style.display = "none";
			document.body.appendChild(form);
			
			// Set Parameter
			$("*[name='exam_students[]']").map(function() {
				var input = document.createElement('input');
				input.setAttribute('type',  'hidden');
				input.setAttribute('name',  'exam_students[]');
				input.setAttribute('value', $(this).val() );
				form.appendChild(input);
			});
			$("*[name='exam_problems[]']").map(function() {
				var input = document.createElement('input');
				input.setAttribute('type',  'hidden');
				input.setAttribute('name',  'exam_problems[]');
				input.setAttribute('value', $(this).val() );
				form.appendChild(input);
			});
			
			form.submit();
		}
		//**************************************************
		//詳細確認画面　設問詳細表示ボタン押下
		//**************************************************
		function show_exam_problem_detail(id, exam_problem_id){
			if( $("#"+id).css("display") == 'none' ){
				//if( $("#"+id+" ul").size()){
				//}else{
				//}
				// Ajax により都度取得
				$.ajax({
					url: "/cms_exam_problem/get_exam_problem_detail",
					type: "POST",
					data: "exam_problem_id="+exam_problem_id,
					
					success: function(response) {
						if(response){
							$("#"+id+" ul").remove();
							
							var problem_kind      = "<?= $this->lang->line_or_def('common_exam_problem','設問'); ?>";
							var problem_kind_link = '';
							if(response['exam_problems_problem_kind']==1){
								problem_kind = problem_kind + "[<?= $this->lang->line_or_def('common_text','テキスト'); ?>]";
							}else if(response['exam_problems_problem_kind']==2){
								problem_kind      = problem_kind + "[<?= $this->lang->line_or_def('common_video','ビデオ'); ?>]";
								problem_kind_link = '/cms_video/detail/'+response['exam_problems_problem_contents_id']+'/';
							}else if(response['exam_problems_problem_kind']==3){
								problem_kind      = problem_kind + "[<?= $this->lang->line_or_def('common_book_library','図書室'); ?>]";
								problem_kind_link = '/cms_book_library/detail/'+response['exam_problems_problem_contents_id']+'/';
							}
							
							var problem_kind_detail = response['exam_problems_problem_contents'];
							if(problem_kind_link != ''){
								problem_kind_detail = '[No'+response['exam_problems_problem_contents_id']+'] '+'<a style="text-decoration: none;" href="'+problem_kind_link+'" target="_blank">'+response['exam_problems_problem_contents']+'</a>';
							}
							
							var answer_kind = "<?= $this->lang->line_or_def('common_exam_answer','解答'); ?>";
							if(response['exam_problems_answer_kind']==1){
								answer_kind = answer_kind + "[<?= $this->lang->line_or_def('common_single_forms','単一形式'); ?>]";
							}else if(response['exam_problems_answer_kind']==2){
								answer_kind = answer_kind + "[<?= $this->lang->line_or_def('common_plural_forms','複数形式'); ?>]";
							}else if(response['exam_problems_answer_kind']==3){
								answer_kind = answer_kind + "[<?= $this->lang->line_or_def('common_free_exam_answer','フリー解答'); ?>]";
							}
							
							$("#"+id).append(
								$('<ul>').append(
									$('<li class="confirm_ul_li_line_dotted">').append(
									    '<div class="detail_title">'+problem_kind+'</div>'
									   +'<div class="detail_values">'+problem_kind_detail+'</div>'
									   +'<div class="detail_title">'+answer_kind+'</div>'
									   +'<div class="detail_values">'+response['exam_problems_answer_contents']+'</div>'
									   +'<div style="clear:both;"></div>'
									)
								)
							);
							
							// 対象を表示、それ以外を非表示
							$("[id^=exam_problem_detail_]").slideUp();
							$("#"+id).slideDown();
						}
					},
					error: function(data) {
					}
				});
			}else{
				$("#"+id).slideUp();
			}
			
		}
		//**************************************************
		//詳細確認画面　解答詳細表示ボタン押下
		//**************************************************
		function show_exam_answer_detail(id, exam_id, answer_student_id, answer_no){
			if( $("#"+id).css("display") == 'none' ){
				var exam_answer_data = $("#string_exam_answer_data").val()+"";
				var post_data        = {exam_id:exam_id, answer_student_id:answer_student_id, answer_no:answer_no, exam_answer_data:exam_answer_data};
				
				$.ajax({
					url: "/cms_exam_problem/get_exam_answer_detail",
					type: "POST",
					data: post_data,
					success: function(response) {
						if(response){
							$("#"+id+" ul").remove();
							$("#"+id).append('<ul></ul>');
							
							for (var i=0; i< response.length; i++) {
								var answer_kind = "<?= $this->lang->line_or_def('common_exam_answer','解答'); ?>";
								if(response[i]['answer_kind']==1){
									answer_kind = answer_kind + "[<?= $this->lang->line_or_def('common_single_forms','単一形式'); ?>]";
								}else if(response[i]['answer_kind']==2){
									answer_kind = answer_kind + "[<?= $this->lang->line_or_def('common_plural_forms','複数形式'); ?>]";
								}else if(response[i]['answer_kind']==3){
									answer_kind = answer_kind + "[<?= $this->lang->line_or_def('common_free_exam_answer','フリー解答'); ?>]";
								}
								
								var answer_mark_non_correct = '';
								var answer_mark_correct     = 'selected="selected"';
								if(response[i]['exam_answer_mark']==0){
									answer_mark_non_correct = 'selected="selected"';
									answer_mark_correct     = '';
								}
								
								var answer_index = "answer_index_"+response[i]['exam_answer_id'];
								
								$("#"+id+" ul").append(
									$('<li class="confirm_ul_li_line_dotted" id="'+answer_index+'">').append(
										 '<input type="hidden" name="exam_answer_id[]" value='+response[i]['exam_answer_id']+' />'
										 +'<div class="detail_title">'+answer_kind+'</div>'
										 +'<div class="detail_values">'+response[i]['exam_answer_contents']+'</div>'
										 +'<div class="detail_answer">'
										   +'<div class="exam_answer_mark">'
										     +'<select name="exam_answer_mark[]" onChange="change_exam_answer_mark('+"'"+answer_index+"'"+');return false;">'
										       +'<option '+answer_mark_non_correct+' value="0">'+"<?= $this->lang->line_or_def('common_non_correct_answer', '不正解') ?>"+'</option>'
										       +'<option '+answer_mark_correct+' value="1">'+"<?= $this->lang->line_or_def('common_correct_answer', '正解') ?>"+'</option>'
										     + '</select>'
										     +'<input type="hidden" name="answer_point[]" value='+response[i]['answer_point']+' />'
										   +'</div>'
										   +'<div class="exam_answer_point">'
										     +"<?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?>&nbsp;:&nbsp;"+'<input type=text name="exam_answer_point[]" maxlength="5" value='+response[i]['exam_answer_point']+'>'
										   +'</div>'
										 +'</div>'
										 +'<div style="clear:both;"></div>'
									)
								);
							}
							// 対象を表示、それ以外を非表示
							$("[id^=exam_answer_detail_]").slideUp();
							$("#"+id).slideDown();
						}
					},
					error: function(data) {
					}
				});
			}else{
				$("#"+id).slideUp();
			}
		}

		//**************************************************
		//詳細確認画面　解答詳細 - 正解・不正解切り換え時の処理
		//**************************************************
		function change_exam_answer_mark(id){
			var exam_answer_mark = $("#"+id).find("*[name='exam_answer_mark[]']").val();
			var answer_point     = $("#"+id).find("*[name='answer_point[]']").val();

			if(exam_answer_mark==0){
				// 不正解
				$("#"+id).find("*[name='exam_answer_point[]']").val(0);
			}else{
				// 正解
				$("#"+id).find("*[name='exam_answer_point[]']").val(answer_point);
			}
		}

		//**************************************************
		//詳細確認画面　解答修正登録ボタン押下（問題ID）
		//**************************************************
		function exam_answer_update(id){
			// フォームの生成
			var form = document.createElement("form");
			form.setAttribute("action", "<?=base_url()?>cms_exam/exam_answer_update_confirm/" + id);
			form.setAttribute("method", "post");
			form.style.display = "none";
			document.body.appendChild(form);
			
			// パラメタの設定
			$("#exam_answer_ul LI").map(function() {
				var exam_answer_id    = '';  // 解答ID
				var exam_answer_mark  = '';  // 正誤（正解:1、不正解:0）
				var exam_answer_point = '';  // 解答配点
				var li_kind_flag      = 0;
				
				// class名により処理切り分け（詳細の方の情報が必要）
				if( $(this).attr('class') == 'confirm_ul_li_line'){
					// 解答の概要の方
				}
				if( $(this).attr('class') == 'confirm_ul_li_line_dotted'){
					// 解答の詳細の方
					exam_answer_id    = '' + $(this).children("[name='exam_answer_id[]']").val();  // 解答ID
					exam_answer_mark  = '' + $(this).find("[name='exam_answer_mark[]']").val();    // 正誤（正解:1、不正解:0）
					exam_answer_point = '' + $(this).find("[name='exam_answer_point[]']").val();   // 解答配点
					if(exam_answer_point==''){
						exam_answer_point = '0';
					}
					li_kind_flag      = 1;
				}
				
				if(li_kind_flag==1){
					var input = document.createElement('input');
					input.setAttribute('type', 'hidden');
					input.setAttribute('name',  'exam_answer_data[]');
					input.setAttribute('value', exam_answer_id+'/'+exam_answer_mark+'/'+exam_answer_point);
					form.appendChild(input);
				}
			});
			form.submit();
		}
		//**************************************************
		//詳細確認画面　解答の最新概要CSVダウンロード実行
		//**************************************************
		function csv_download_latest_summary(id){
			$("#csv_link_latest_summary").css("display","none");
			$("#csv_link_latest_summary_none").css("display","block");
			location.href ="<?=base_url()?>cms_exam/answer_csv_download_summary/" + id + "/1";
			$("#csv_link_latest_summary").css("display","block");
			$("#csv_link_latest_summary_none").css("display","none");
			return false;
		}
		//**************************************************
		//詳細確認画面　解答の概要CSVダウンロード実行
		//**************************************************
		function csv_download_summary(id){
			$("#csv_link_summary").css("display","none");
			$("#csv_link_summary_none").css("display","block");
			location.href ="<?=base_url()?>cms_exam/answer_csv_download_summary/" + id;
			$("#csv_link_summary").css("display","block");
			$("#csv_link_summary_none").css("display","none");
			return false;
		}
		//**************************************************
		//詳細確認画面　解答の詳細CSVダウンロード実行
		//**************************************************
		function csv_download_detail(id){
			$("#csv_link_detail").css("display","none");
			$("#csv_link_detail_none").css("display","block");
			location.href ="<?=base_url()?>cms_exam/answer_csv_download_detail/" + id;
			$("#csv_link_detail").css("display","block");
			$("#csv_link_detail_none").css("display","none");
			return false;
		}

	</script>
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
			<? $this->load->view('cms_exam/_submenu', array(
				'selected'	=> 'exam',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $exam['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_exam_confirm','問題情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_exam_confirm','問題情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_exam_detail','問題情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_exam/commit")?>
					<input type="hidden" name="exam_id" value='<?= $exam['exam_id']; ?>' />
					
					<?php if( isset($exam['exam_students']) ): ?>
						<?php foreach( $exam['exam_students'] as $student): ?>
							<input type="hidden" name="exam_students[]" value='<?= $student; ?>' />
						<?php endforeach; ?>
					<?php endif; ?>
					
					<?php if( isset($exam['exam_problems']) ): ?>
						<?php foreach( $exam['exam_problems'] as $student): ?>
							<input type="hidden" name="exam_problems[]" value='<?= $student; ?>' />
						<?php endforeach; ?>
					<?php endif; ?>
					
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam_name','問題（テスト）名') ?></th>
							<td>
								<?=$exam['exam_name']?>
							</td>
						</tr>

<?php if(false){ ?>
						<? if( getenv('URL_SERVICE')!='mitemo' ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td ><?= $exam['teacher_name'] ?>
							</td>
						</tr>
						<? endif; ?>
<?php } ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($exam['exam_lectures_name']) ) {
										foreach( $exam['exam_lectures_name'] as $name) { 
											if($flg){	?>
												,
											<?php } ?>
											<?=$name?>
										<?php
											$flg = TRUE;
										}
									}
								?>
							</td>
						</tr>
<?php if(false){ ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_student','受講者') ?></th>
							<td ><?= $exam['exam_students_name']; ?></td>
						</tr>
<?php } ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td style="word-break: break-all;"><?= nl2br($exam['exam_caption']); ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_submit_period','提出期間') ?></th>
							<td >
								<?= $exam['exam_open'] ?><?= $this->lang->line_or_def('common_range','～') ?><?= $exam['exam_close'] ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_indication_status','公開設定') ?></th>
							<td >
								<?php if($exam['public_flag'] == 0){ ?>
									<?= $this->lang->line_or_def('common_public','公開'); ?>
								<?php }elseif($exam['public_flag'] == 9){ ?>
									<?= $this->lang->line_or_def('common_non_public','非公開'); ?>
								<?php }else{ ?>
									<td>-</td>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_resubmit','再提出') ?></th>
							<td >
								<?php if($exam['resubmit_flag'] == 0){ ?>
									<?= $this->lang->line_or_def('common_impossible','不可'); ?>
								<?php }elseif($exam['resubmit_flag'] == 1){ ?>
									<?= $this->lang->line_or_def('common_possible','可'); ?>
								<?php }else{ ?>
									<td>-</td>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_marking_public_status','採点公開設定') ?></th>
							<td >
								<?php if($exam['marking_public_flag'] == 0): // 0:公開 ?>
									<?= $this->lang->line_or_def('common_public', '公開'); ?>
<?php if(false){ ?>
									<?php if($exam['marking_public_kind'] == 1): // 1:即時公開 ?>
										<?= $this->lang->line_or_def('common_public', '公開'); ?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $this->lang->line_or_def('common_spot_public','即時公開') ?>
									<?php else: // 2:時限式公開 ?>
										<?= $this->lang->line_or_def('common_public', '公開'); ?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $this->lang->line_or_def('common_timed_public','時限式公開') ?>&nbsp;&nbsp;[<?= $exam['marking_public_open'] ?>]
									<?php endif; ?>
<?php } ?>
								<?php else: // 9:非公開 ?>
									<?= $this->lang->line_or_def('common_non_public', '非公開'); ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_display_format'  ,'表示形式') ?></th>
							<td >
								<?php if($exam['display_format'] == 0){ ?>
									<?= $this->lang->line_or_def('common_full_screen_collectively_scoring_format' , '全画面一括採点形式'); ?>
								<?php }elseif($exam['display_format'] == 1){ ?>
									<?= $this->lang->line_or_def('common_q_and_a_format_each_time_scoring'        , '一問一答形式（都度採点）'); ?>
								<?php }elseif($exam['display_format'] == 2){ ?>
									<?= $this->lang->line_or_def('common_q_and_a_format_collectively_scoring'     , '一問一答形式（一括採点）'); ?>
								<?php }else{ ?>
									-
								<?php } ?>
							</td>
						</tr>
						<tr>
							<th style="text-align: left;vertical-align: top;">
								<?= $this->lang->line_or_def('common_exam_problem','設問') ?>
							</th>
							<td >
								<?php if(!empty($exam['exam_problems'][0])): ?>
									<ul>
										<li>
											<div class="confirm_ul_li_div" style="width:280px;"><?= $this->lang->line_or_def('common_exam_problem_name','設問名') ?></div>
											<div class="confirm_ul_li_div" style="width:100px;">
												<? if( getenv('URL_SERVICE')!='mitemo' ): ?>
													<?= $this->lang->line_or_def('common_management_teacher','管理講師') ?>
												<? endif; ?>
											</div>
											<div class="confirm_ul_li_div" style="width: 70px;"><?= $this->lang->line_or_def('common_answer_points','解答配点') ?></div>
											<div class="confirm_ul_li_div" style="width: 70px;"></div>
											<div style="clear:both;"></div>
										</li>
									</ul>
									<div class="confirm_ul_detail">
										<ul>
											<?php foreach($exam['exam_problems'] as $id => $exam_problem): ?>
												<li class="confirm_ul_li_line">
													<input type="hidden" name="exam_problem_id[]" value='<?= $exam['exam_problems'][$id]; ?>' />
													<div class="confirm_ul_li_div_detail" style="width:280px;">[設問<?= $id+1 ?>]&nbsp;<?= $exam['exam_problems_name'][$id] ?></div>
													<div class="confirm_ul_li_div_detail" style="width:100px;">
														<? if( getenv('URL_SERVICE')!='mitemo' ): ?>
															<?= $exam['exam_problems_teacher_name'][$id] ?>
														<? endif; ?>
													</div>
													<div class="confirm_ul_li_div_detail" style="width: 70px;"><?= $exam['exam_problems_answer_point'][$id] ?></div>
													<div class="confirm_ul_li_div_detail" style="width: 70px;">
														<? if($btn_kirikae_flg==2): ?>
															<input type="button" value="<?= $this->lang->line_or_def('common_exam_problem_detail','設問詳細') ?>" onClick="show_exam_problem_detail('exam_problem_detail_<?= $id; ?>',<?= $exam['exam_problems'][$id] ?>);return false;" />
														<? endif; ?>
													</div>
													<div style="clear:both;"></div>
													<?php // ajaxによる表示設定 ?>
													<div id="exam_problem_detail_<?= $id; ?>" class="detail_area">
														<ul><li></li></ul>
													</div>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_criteria','判定基準') ?></th>
							<td >
								<?= $exam['criteria_value'] ?>
								<?php if($exam['criteria_type'] == 1){ ?>
									点
								<?php }elseif($exam['criteria_type'] == 2){ ?>
									％
								<?php }elseif($exam['criteria_type'] == 3){ ?>
									数
								<?php } ?>
							</td>
						</tr>

<?php if(false){ ?>
						<tr>
							<th style="text-align: left;vertical-align: top;">
								<?= $this->lang->line_or_def('common_exam_answer','解答') ?>
								<?php if(!empty($exam['answer_date'][0])): ?>
									<div style="line-height: 21px;margin: 10px 0 0 0;">
										<input type="checkbox" name="show_answer" id="show_answer" value="1" checked />
										<?= $this->lang->line_or_def('msg_only_latest_exam_answer','最新の解答のみ表示') ?>
									</div>
									<div class="div_download">
										<a href='#' onClick='csv_download_summary(<?= $exam['exam_id']?>);return false;' id="csv_link_summary"><?= $this->lang->line_or_def('common_summary','概要') ?> Excel-csv DL</a>
										<div id="csv_link_summary_none"></div>
									</div>
									<div class="div_download">
										<a href='#' onClick='csv_download_detail(<?= $exam['exam_id']?>);return false;' id="csv_link_detail"><?= $this->lang->line_or_def('common_detail','詳細') ?> Excel-csv DL</a>
										<div id="csv_link_detail_none"></div>
									</div>
									<div class="div_download"><?= $this->lang->line_or_def('msg_exam_answer_download_1','※ダウンロード対象は保存済み解答が対象となります') ?></div>
									<div class="div_download"><?= $this->lang->line_or_def('msg_exam_answer_download_2','※解答が多い場合、ダウンロードに時間がかかる場合があります') ?></div>
								<?php endif; ?>
							</th>
							<td>
								<?php if(!empty($exam['answer_date'][0])): ?>
								
								<?php $string_exam_answer_data = (isset($exam['string_exam_answer_data']))?$exam['string_exam_answer_data']:""; ?>
								<input type="hidden" id="string_exam_answer_data" value='<?= $string_exam_answer_data; ?>' />
								
								<ul>
									<li>
										<div class="confirm_ul_li_div" style="width:150px;"><?= $this->lang->line_or_def('common_exam_answer_datetime','解答日時') ?></div>
										<div class="confirm_ul_li_div" style="width:230px;"><?= $this->lang->line_or_def('common_student_name','受講者名') ?></div>
										<div class="confirm_ul_li_div" style="width: 70px;"><?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?></div>
										<div class="confirm_ul_li_div" style="width: 70px;"></div>
										<div style="clear:both;"></div>
									</li>
								</ul>
								<div class="confirm_ul_detail">
									<ul id="exam_answer_ul">
										<?php foreach($exam['answer_date'] as $id => $answer_date): ?>
											<li class="confirm_ul_li_line" <?= ($exam['exam_answer_latest_flag'][$id]==0) ? 'name="not_latest"' : 'name="latest"' ; ?>>
												<input type="hidden" name="answer_no[]" value='<?= $exam['answer_no'][$id]; ?>' />
												<div class="confirm_ul_li_div_detail" style="width:150px;"><?= $exam['answer_date'][$id]; ?></div>
												<div class="confirm_ul_li_div_detail" style="width:230px;"><?= $exam['answer_student_name'][$id]; ?></div>
												<div class="confirm_ul_li_div_detail" style="width: 70px;"><?= $exam['answer_point_total'][$id]; ?>/<?= $exam['max_answer_point']; ?></div>
												<div class="confirm_ul_li_div_detail" style="width: 70px;">
													<? if($btn_kirikae_flg==2): ?>
													<input type="button" value="<?= $this->lang->line_or_def('common_exam_answer_detail','解答詳細') ?>" onClick="show_exam_answer_detail('exam_answer_detail_<?= $id; ?>',<?= $exam['exam_id']; ?>,<?= $exam['answer_student_id'][$id] ?>,<?= $exam['answer_no'][$id] ?>);return false;" />
													<? endif; ?>
												</div>
												<div style="clear:both;"></div>
												
												<?php // ajaxによる表示設定 ?>
												<div id="exam_answer_detail_<?= $id; ?>" class="detail_area">
													<ul><li></li></ul>
												</div>
												<div style="clear:both;"></div>

											</li>
										<?php endforeach; ?>
									</ul>
								</div>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_no_exam_answer','解答なし') ?>
								<?php endif; ?>
							</td>
						</tr>
<?php } ?>

					</table>
					
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_exam')."\";return false;' />";
									
									$visible_issue_edit = ' style="visibility: hidden;" ';
									if($exam_edit_delete_flag > 0) $visible_issue_edit = ' ';
									
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' ".$visible_issue_edit."/>";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$exam['exam_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' ".$visible_issue_edit."/>";
if(false){
									if(!empty($exam['answer_date'][0])){
										print "<a id='btn_blue_long_button' onclick='exam_answer_update(".$exam['exam_id'].");return false;' href='#' ".$visible_issue_edit.">".$this->lang->line_or_def('common_exam_answer_update_regist','解答修正登録')."</a>";
									}
}
									break;
							}
						?>
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
