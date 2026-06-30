<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam2";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 管理講師「別の講師が選択されています」 */
		#teacher_change_message{
			color : #00A4E2;
		}
		/* 所属講座 */
		.cource_list{
			max-height	: 300px;
			overflow-y	: scroll;
		}
			/* 所属講座選択エリア */
			#cource_ul{
				margin-top: 0px;
			}
				#cource_ul li{
					margin-left: 2px;
					margin-bottom: 0px;
				}
		/* 設問内容 テキスト */
		/* 解答解説内容 テキスト */
		  #problem_contents_text 
		, #answer_explain_contents_text {
			width   : 100%;
			height  : 100px;
		}
		/* 設問内容 図書室ビデオ選択時に表示するサムネイル（予定） */
		/* 解答解説内容 図書室ビデオ選択時に表示するサムネイル（予定） */
		  #problem_contents_thum
		, #answer_explain_contents_thum{
			border    : 1px solid #000000;
			height    : auto;
			max-width : 150px; display:none;
		}
		
		/* 解答内容 テキスト */
		#answer_contents_text{
			width   : 100%;
			height  : 100px;
		}
		/* 解答内容 選択肢文言 */
		.answer_contents_word{
			width   : 400px;
			height  : 70px;
		}
		/* 解答内容（選択系） - ul  */
		#answer_contents_choice_area LI{
			border-color  : #808080; 
			border-image  : none; 
			border-style  : none none solid; 
			border-width  : 1px;
		}
			#answer_contents_choice_area LI .div_word{
				float    : left; 
				padding  : 6px 0 6px 0;
			}
			#answer_contents_choice_area LI .div_correct{
				float        : left; 
				line-height  : 18px; 
				padding      : 6px 0 6px 10px;
			}
		/* 解答内容（選択系） 追加するボタン */
		#div_append_button{
			margin      : 10px 0;
			text-align  : center;
		}
		/* 解答内容 削除ボタン（表示・非表示） */
		.delete_answer_contents{
			margin : 3px 3px 3px 10px;
		}
		.delete_answer_contents_hidden{
			margin     : 3px 3px 3px 10px;
			visibility : hidden;
		}
		/* 解答配点 */
		#answer_point{
			width: 84px;
		}
	</style>
	<script type="text/javascript"><!--
		var students_checked      = {};
		var cources_checked       = {};

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
				get_book_library_list();
				get_video_list();
			});
			
			// 設問種類→設問内容の初期処理
			change_problem_contents( $("*[name=problem_kind]").val() );
			
			// 設問種類コンボボックス変更時の処理
			$("*[name=problem_kind]").bind("change keyup",function(){
				change_problem_contents($(this).val());
			});
			
			// 解答解説種類→解答解説内容の初期処理
			change_answer_explain_contents( $("*[name=answer_explain_kind]").val() );
			
			// 解答解説種類コンボボックス変更時の処理
			$("*[name=answer_explain_kind]").bind("change keyup",function(){
				change_answer_explain_contents($(this).val());
			});
			
			// 解答種類→解答内容の初期処理
			change_answer_contents( $("*[name=answer_kind]").val(), 1);

			// 解答種類コンボボックス変更時の処理
			$("*[name=answer_kind]").bind("change keyup",function(){
				change_answer_contents($(this).val(), 0);
			});

			// 解答内容　単一形式の場合の処理
			// チェックボックスに付加できる数を１つに制限
			$("*[name='answer_contents_correct[]']").click(function (){
				// 解答内容のインデックスを取得
				var contents_index = $(this).parent("DIV").prevAll("*[name='answer_contents_no[]']").val();
				// チェック数を計算
				var check_count = 0;
				$("#answer_contents_choice_area input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						check_count = check_count + 1;
					}
				});
				// 解答種類の取得
				var answer_kind = $("*[name='answer_kind']").val();
				// 単一形式且つチェック数が1を超える場合
				if( (answer_kind == 1) && (check_count > 1) ){
					// 正解チェックボックスを初期化
					$("*[name='answer_contents_correct[]']").prop('checked', false);
					// チェックオンしたもののみ有効化
					$("#li_answer_contents_no_"+contents_index+"").find("*[name='answer_contents_correct[]']").prop("checked",true);
				}
			});
		//	$("*[name=problem_contents_video]").bind("change keyup",function(){
		//		var id = $(this).val();
		//		$("*[name=problem_contents_thum]").css("display","");
		//		$("*[name=problem_contents_thum]").attr("src","/video_files/"+id+"/thumbnail.jpg");
		//	});
		//	$("*[name=problem_contents_book_library]").bind("change keyup",function(){
		//		var id = $(this).val();
		//		$("*[name=problem_contents_thum]").css("display","");
		//		$("*[name=problem_contents_thum]").attr("src","/book_libraly_files/"+id+"/Page1/master-Page1-thum.jpg");
		//		
		//	});
		});

		//**************************************************
		// 設問種類→設問内容の連動処理
		//**************************************************
		function change_problem_contents(param){
			if(param == 1){
				$("*[name=problem_contents_text]").css("display","");
				$("*[name=problem_contents_video]").css("display","none");
				$("*[name=problem_contents_book_library]").css("display","none");
			}else if(param == 2){
				$("*[name=problem_contents_text]").css("display","none");
				$("*[name=problem_contents_video]").css("display","");
				$("*[name=problem_contents_book_library]").css("display","none");
			}else if(param == 3){
				$("*[name=problem_contents_text]").css("display","none");
				$("*[name=problem_contents_video]").css("display","none");
				$("*[name=problem_contents_book_library]").css("display","");
			}
		}
		
		//**************************************************
		// 解答解説種類→解答解説内容の連動処理
		//**************************************************
		function change_answer_explain_contents(param){
			if(param == 1){
				$("*[name=answer_explain_contents_text]").css("display","");
				$("*[name=answer_explain_contents_video]").css("display","none");
				$("*[name=answer_explain_contents_book_library]").css("display","none");
				$("*[name=tr_answer_explain_contents]").css("display","");
				$("*[name=tr_answer_explain_note]").css("display","");
			}else if(param == 2){
				$("*[name=answer_explain_contents_text]").css("display","none");
				$("*[name=answer_explain_contents_video]").css("display","");
				$("*[name=answer_explain_contents_book_library]").css("display","none");
				$("*[name=tr_answer_explain_contents]").css("display","");
				$("*[name=tr_answer_explain_note]").css("display","");
			}else if(param == 3){
				$("*[name=answer_explain_contents_text]").css("display","none");
				$("*[name=answer_explain_contents_video]").css("display","none");
				$("*[name=answer_explain_contents_book_library]").css("display","");
				$("*[name=tr_answer_explain_contents]").css("display","");
				$("*[name=tr_answer_explain_note]").css("display","");
			}else if(param == 9){
				$("*[name=answer_explain_contents_text]").css("display","none");
				$("*[name=answer_explain_contents_video]").css("display","none");
				$("*[name=answer_explain_contents_book_library]").css("display","none");
				$("*[name=tr_answer_explain_contents]").css("display","none");
				$("*[name=tr_answer_explain_note]").css("display","none");
			}
		}

		//**************************************************
		// 解答種類→解答内容の連動処理
		//**************************************************
		function change_answer_contents(param, f){
			if(param == 1){
				$("*[name=answer_contents_choice]").css("display","");
				$("*[name=answer_contents_text]").css("display","none");
				if(f==0){
					$("*[name='answer_contents_correct[]']").prop('checked', false);  // 正解チェックボックスを初期化
				}
			}else if(param == 2){
				$("*[name=answer_contents_choice]").css("display","");
				$("*[name=answer_contents_text]").css("display","none");
			}else if(param == 3){
				$("*[name=answer_contents_choice]").css("display","none");
				$("*[name=answer_contents_text]").css("display","");
			}
		}
		//**************************************************
		// 所属講座切り替え時、図書室名コンボボックスの内容変更
		//   起動時間 => 画面表示時(1)、講座切り替え時(0)
		//   画面表示時、編集画面の場合、前情報を表示する必要がある
		//**************************************************
		function get_book_library_list() {
			
			var AllVals = new Array;
			$('.cource_list input:checked').map(function() {
				AllVals.push($(this).val());
			});
			
			$.ajax({
				url: "/cms_exam2_problem/get_cource_book_library",
				type: "POST",
				data: "cource_id="+AllVals.join(","),

				success: function(response) {
					$("*[name='problem_contents_book_library'] option").remove();
					$("*[name='problem_contents_book_library']").append('<option selected="selected" value="-1"><?= $this->lang->line_or_def('msg_exclusive_tag_book_library_select','図書室を選択してください') ?></option>');
					$("*[name='answer_explain_contents_book_library'] option").remove();
					$("*[name='answer_explain_contents_book_library']").append('<option selected="selected" value="-1"><?= $this->lang->line_or_def('msg_exclusive_tag_book_library_select','図書室を選択してください') ?></option>');

					if(response){
						// 取得したデータを行に入れる
						for (var i=0; i< response.length; i++) {
							$("*[name='problem_contents_book_library']").append(
								'<option value='+response[i]['book_library_id']+'>'+'[No'+response[i]['book_library_id']+'] '+response[i]['book_library_logic_name']+'</option>');
							$("*[name='answer_explain_contents_book_library']").append(
								'<option value='+response[i]['book_library_id']+'>'+'[No'+response[i]['book_library_id']+'] '+response[i]['book_library_logic_name']+'</option>');
						}
					}
				}
			});
		}
		//**************************************************
		// 所属講座切り替え時、ビデオ名コンボボックスの内容変更
		//   起動時間 => 画面表示時(1)、講座切り替え時(0)
		//   画面表示時、編集画面の場合、前情報を表示する必要がある
		//**************************************************
		function get_video_list() {
			
			var AllVals = new Array;
			$('.cource_list input:checked').map(function() {
				AllVals.push($(this).val());
			});
			
			$.ajax({
				url: "/cms_exam2_problem/get_cource_video",
				type: "POST",
				data: "cource_id="+AllVals.join(","),

				success: function(response) {
					$("*[name='problem_contents_video'] option").remove();
					$("*[name='problem_contents_video']").append('<option selected="selected" value="-1"><?= $this->lang->line_or_def('msg_exam2_problem_video_select','ビデオを選択してください') ?></option>');
					$("*[name='answer_explain_contents_video'] option").remove();
					$("*[name='answer_explain_contents_video']").append('<option selected="selected" value="-1"><?= $this->lang->line_or_def('msg_exam2_problem_video_select','ビデオを選択してください') ?></option>');

					if(response){
						// 取得したデータを行に入れる
						for (var i=0; i< response.length; i++) {
							$("*[name='problem_contents_video']").append(
								'<option value='+response[i]['video_id']+'>'+'[No'+response[i]['video_id']+'] '+response[i]['video_logic_name']+'</option>');
							$("*[name='answer_explain_contents_video']").append(
								'<option value='+response[i]['video_id']+'>'+'[No'+response[i]['video_id']+'] '+response[i]['video_logic_name']+'</option>');
						}
					}
				}
			});
		}
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
		// 受講者グループ名リンク押下時の処理
		//**************************************************
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
			get_book_library_list();
			get_video_list();
			return false;
		}
		//**************************************************
		// 解答内容（選択系）、追加するボタン押下
		//**************************************************
		function add_answer_contents(){
			// 最後の li の有効チェックボックスの value を取得
			var cnt = 0;
			cnt = $('#answer_contents_choice_area li:last').find("*[name='answer_contents_no[]']").val();
			cnt = parseInt(cnt) + 1;
			
			// 最後の li を複製
			var liclone = $('#answer_contents_choice_area li:last').clone(true);
			$('#answer_contents_choice_area').append(liclone);

			// これ以降 li:last は複製した li が対象

			// li の id を変更
			$('#answer_contents_choice_area li:last').attr('id', "li_answer_contents_no_"+cnt+"");
			
			// 連番
			$('#answer_contents_choice_area li:last').find("*[name='answer_contents_no[]']").val(cnt);
			
			// 選択肢文言
			$('#answer_contents_choice_area li:last').find("*[name='answer_contents_word[]']").val('');
			
			// 正解チェック（初期値）
		//	$('#answer_contents_choice_area li:last').find("*[name='answer_contents_correct_default[]']").val(cnt);
			
			// 正解チェック
			$('#answer_contents_choice_area li:last').find("*[name='answer_contents_correct[]']").val(cnt);
			$('#answer_contents_choice_area li:last').find("*[name='answer_contents_correct[]']").attr("checked", false);
			
			
			// 削除ボタンの初期化（class名の変更、onclickの設定変更）
			$('#answer_contents_choice_area li:last').find(".delete_answer_contents_hidden").attr('class', "delete_answer_contents");
			$('#answer_contents_choice_area li:last').find(".delete_answer_contents").attr('onclick','');
			$('#answer_contents_choice_area li:last').find(".delete_answer_contents").attr('onclick','delete_answer_contents('+cnt+');return false;');
			
		}
		//**************************************************
		// 解答内容（選択系）、削除ボタン押下
		//**************************************************
		function delete_answer_contents(index){
			// １行目（index=0）は削除なし
			if(index > 0){
				$('#li_answer_contents_no_'+index+'').remove();
			}
		}
	// --></script> 
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam2','アンケート管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam2_comment','アンケートを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam2_problem/_submenu', array(
				'selected'	=> 'exam2_problem',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam2_problem/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam2_problem/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_exam2_problem_input','設問の情報を入力してください') ?></h2>

				<?=form_open_multipart("cms_exam2_problem/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($problem_error_msg)?'<div class="error">'.htmlspecialchars($problem_error_msg, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<?=(isset($answer_error_msg)?'<div class="error">'.htmlspecialchars($answer_error_msg, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<?=(isset($answer_explain_error_msg)?'<div class="error">'.htmlspecialchars($answer_explain_error_msg, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<input type="hidden" name="update_flg"      value='<?=set_value('update_flg',       $exam2_problem['update_flg'])?>'>
					<input type="hidden" name="exam2_problem_id" value='<?=set_value('exam2_problem_id',  $exam2_problem['exam2_problem_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam2_problem_name','設問名') ?></th>
							<td>
								<input type=text name="exam2_problem_name" maxlength="256" size="30" value='<?=set_value('exam2_problem_name',$exam2_problem['exam2_problem_name'])?>'>
							</td>
						</tr>

<?php if(false){ ?>
						<? if( getenv('URL_SERVICE')=='mitemo' ): ?>
							<input type="hidden" name="teacher_id"   value='<?=set_value('teacher_id',   $exam2_problem['teacher_id'])?>'>
						<? else: ?>
						<tr>
							<th ><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<?=form_dropdown('teacher_id',$teachers_dropdown,set_value('teacher_id',$exam2_problem['teacher_id']), 'id="teacher_id"'); ?>
								&nbsp;<lavel id="teacher_change_message" style=""><?= $this->lang->line_or_def('msg_different_teacher','別の講師が選択されています') ?></label>
							</td>
						</tr>
						<? endif; ?>
<?php } ?>
						<input type="hidden" name="teacher_id"   value="1">

						<tr>
							<th >
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
												<input type="checkbox" name="exam2_problem_lectures[]" id="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>
													<?php 
													if( isset($exam2_problem['exam2_problem_lectures']) ) {
														foreach( $exam2_problem['exam2_problem_lectures'] as $lecture) { 
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
							<th><?= $this->lang->line_or_def('common_problem_kind','設問種類') ?></th>
							<td >
								<?=form_dropdown('problem_kind', $problem_kind_list, set_value('problem_kind', $exam2_problem['problem_kind']));?>
							</td>
						</tr>
						<tr>
							<th width="160" style="vertical-align: top;"><?= $this->lang->line_or_def('common_problem_contents','設問内容') ?></th>
							<td>
								<? // 設問種類に応じて表示切替 ?>
								<textarea name="problem_contents_text" id="problem_contents_text" ><?=set_value('problem_contents_text',$exam2_problem['problem_contents_text'])?></textarea>
								<?= form_dropdown('problem_contents_book_library',$problem_contents_book_library, set_value('problem_contents_book_library', $exam2_problem['problem_contents_book_library']), 'name="problem_contents_book_library" class="problem_contents_book_library" '); ?>
								<?= form_dropdown('problem_contents_video',$problem_contents_video, set_value('problem_contents_video', $exam2_problem['problem_contents_video']), 'name="problem_contents_video" class="problem_contents_video" '); ?>
								 <img name="problem_contents_thum" id="problem_contents_thum" style="" src="">
							</td>
						</tr>
						
						<tr>
							<th width="160" style="vertical-align: top;"><?= $this->lang->line_or_def('common_problem_note','設問備考') ?></th>
							<td>
								<textarea name="problem_note" id="problem_note" style="height: 80px; width: 65%;"><?=set_value('problem_note',$exam2_problem['problem_note'])?></textarea>
							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_answer_kind','解答種類') ?></th>
							<td >
								<?=form_dropdown('answer_kind', $answer_kind_list, set_value('answer_kind', $exam2_problem['answer_kind']));?>
							</td>
						</tr>
						
						<tr>
							<th width="160" style="vertical-align: top;"><?= $this->lang->line_or_def('common_answer_contents','解答内容') ?></th>
							<td>
								<div id="answer_contents_choice" name="answer_contents_choice">
									<ul id="answer_contents_choice_area">
										
										<?php foreach($exam2_problem['answer_contents_no'] as $ino => $answer_contents_no): ?>
											<li id="li_answer_contents_no_<?= htmlspecialchars( $answer_contents_no, ENT_QUOTES, 'UTF-8') ?>">
												<input type="hidden" name="answer_contents_no[]" value='<?=set_value('answer_contents_no[]', $exam2_problem['answer_contents_no'][$ino])?>' />
												<div class="div_word">
													<textarea name="answer_contents_word[]" class="answer_contents_word"><?=set_value('answer_contents_word[]',$exam2_problem['answer_contents_word'][$ino])?></textarea>
												</div>
												
												<div class="div_correct">
													<?php if($exam2_problem['answer_contents_correct'][$ino] == 1): ?>
														<input type="hidden" value="<?= htmlspecialchars( $answer_contents_no, ENT_QUOTES, 'UTF-8') ?>" name="answer_contents_correct[]">
													<?php else: ?>
														<input type="hidden" value="<?= htmlspecialchars( $answer_contents_no, ENT_QUOTES, 'UTF-8') ?>" name="answer_contents_correct[]" >
													<?php endif; ?>
													<!--<?= $this->lang->line_or_def('msg_correct_answer_is_check','正解はチェック') ?>-->
													
												</div>
												<input class="<?= ($ino == 0) ? 'delete_answer_contents_hidden' : 'delete_answer_contents'; ?>" type="button" onclick="delete_answer_contents(<?= htmlspecialchars( $answer_contents_no, ENT_QUOTES, 'UTF-8') ?>);return false;" value="<?= $this->lang->line_or_def('common_deletion','削除') ?>" >
												<div style="clear:both;"></div>
											</li>
										<?php endforeach; ?>
									</ul>
									<div id="div_append_button">
										<a onclick="add_answer_contents();return false;" href="#" id="btn_gray_long_button"><?= $this->lang->line_or_def('common_add','追加する') ?></a>
									</div>
								</div>
								<textarea name="answer_contents_text" id="answer_contents_text"><?=set_value('answer_contents_text',$exam2_problem['answer_contents_text'])?></textarea>
							</td>
						</tr>
						<!--
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam2_answer_points','解答配点') ?></th>
							<td>
								<input type=text name="answer_point" id="answer_point" value='<?=set_value('answer_point',$exam2_problem['answer_point'])?>'>
							</td>
						</tr>
						-->
						<input type="hidden" name="answer_point" value="0">
						<!--
						<tr>
							<th><?= $this->lang->line_or_def('common_answer_explain_kind','解答解説種類') ?></th>
							<td >
								<?=form_dropdown('answer_explain_kind', $answer_explain_kind_list, set_value('answer_explain_kind', $exam2_problem['answer_explain_kind']));?>
							</td>
						</tr>
						-->
						<input type="hidden" name="answer_explain_kind" value="9">
						<!--
						<tr name="tr_answer_explain_contents" style="<?= ($exam2_problem['answer_explain_kind'] == 9) ? "display:none" : ""; ?>;">
							<th width="160" style="vertical-align: top;"><?= $this->lang->line_or_def('common_answer_explain_contents','解答解説内容') ?></th>
							<td>
								<? // 設問種類に応じて表示切替 ?>
								<textarea name="answer_explain_contents_text" id="answer_explain_contents_text" ><?=set_value('answer_explain_contents_text',$exam2_problem['answer_explain_contents_text'])?></textarea>
								<?= form_dropdown('answer_explain_contents_book_library',$problem_contents_book_library, set_value('answer_explain_contents_book_library', $exam2_problem['answer_explain_contents_book_library']), 'name="answer_explain_contents_book_library" class="answer_explain_contents_book_library" '); ?>
								<?= form_dropdown('answer_explain_contents_video',$problem_contents_video, set_value('answer_explain_contents_video', $exam2_problem['answer_explain_contents_video']), 'name="answer_explain_contents_video" class="answer_explain_contents_video" '); ?>
								 <img name="answer_explain_contents_thum" id="answer_explain_contents_thum" style="" src="">
							</td>
						</tr>
						-->
						<tr name="tr_answer_explain_note" style="<?= ($exam2_problem['answer_explain_kind'] == 9) ? "display:none" : ""; ?>">
							<th width="160" style="vertical-align: top;"><?= $this->lang->line_or_def('common_answer_explain_note','解答解説備考') ?></th>
							<td>
								<textarea name="answer_explain_note" id="answer_explain_note" style="height: 80px; width: 65%;"><?=set_value('answer_explain_note',$exam2_problem['answer_explain_note'])?></textarea>
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
