<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
	<? // ドラック＆ドロップ用 ?>
	<!--
	<script src="<?=base_url()?>static/js/jquery.ui.widget.js" type="text/javascript"></script> 
	<script src="<?=base_url()?>static/js/jquery.ui.mouse.js" type="text/javascript"></script> 
	<script src="<?=base_url()?>static/js/jquery.ui.draggable.js" type="text/javascript"></script> 
	<script src="<?=base_url()?>static/js/jquery.ui.sortable.js" type="text/javascript"></script> 
	-->
	<style type="text/css">
		/* 管理講師「別の講師が選択されています」 */
		#teacher_change_message{
			color : #00A4E2;
		}
		/* 所属講座 */
		.cource_list{
			max-height : 300px;
			overflow-y : scroll;
		}
			/* 所属講座選択エリア */
			#cource_ul{
				margin-top : 0px;
			}
			#cource_ul li{
				margin-left   : 2px;
				margin-bottom : 0px;
			}
		/* 受講者 */
		#student_list{
			max-height : 1150px; /* 1行あたり23px × 50行 */
			overflow   : auto;
		}
			#student_ul{
				margin-top : 0px;
			}
				#student_ul li{
					margin-left   : 2px;
					margin-bottom : 0px;
				}
		/* 受講者 - 左側 */
		#student_group_list, #exam_problem_group_list{
			display : none;
		}
			#student_group_list DIV, #exam_problem_group_list DIV{
				width      : 160px;
				margin-top : 10px;
				display    : block;
				word-wrap  : break-word;
			}
				#student_group_list DIV A, #exam_problem_group_list DIV A{
					border-color          : #888888 #888888 #888888 silver;
					border-image          : none;
					border-style          : solid;
					border-width          : 1px 1px 1px 19px;
					
					-webkit-border-radius : 4px 4px 4px 4px;
					-moz-border-radius    : 4px 4px 4px 4px;
					border-radius         : 4px 4px 4px 4px;
					
					color                 : #888888;
					display               : block;
					font-size             : 11px;
					font-weight           : bold;
					height                : 18px;
					line-height           : 18px;
					overflow              : hidden;
					padding-left          : 2px;
					text-decoration       : none;
					width                 : 138px;
				}
				/* 受講者 - 左側 - 受講者グループ */
				#student_group_list .selected_group{
					border-color : #444444 #444444 #444444 #444444;
					color        : #444444;
				}
				#student_group_list .non_selected_group{
					border-color : #888888 #888888 #888888 #C0C0C0;
					color        : #888888;
				}
				/* 受講者 - 左側 - 該当者のみ表示 */
				#div_student_check_only{
					margin : 10px 0 10px 0;
				}
				
		/* 受講者 - 左側 - メッセージ */
		#student_group_list_msg, #exam_problem_group_list_msg{
			margin-top    : 20px;
			margin-bottom : 10px;
			display       : none;
			text-indent   : -1em; 
			margin-left   : 1em;
		}
		/* 説明 */
		TEXTAREA{
			width  : 100%;
			height : 100px;
		}
		
		/* 設問 - ドラック＆ドロップ設定 ---------- ---------- ---------- */
		/* ドロップ先DIV */
		#problem_drop_area{
			background : none repeat scroll 0 0 #ffffff;
			border     : 1px solid #a4a5b3;
			max-height : 150px;
			overflow-y : scroll;
		}
			/* ドロップ先DIV 内 UL */
			#ul_problem_drop_area{
				min-height : 150px;
			}
				#ul_problem_drop_area LI{
					display       : list-item; 
					border-bottom : 1px solid #a4a5b3;
					padding       : 5px;
				/*	width         : 579px;*/
				}
				/* ロールーオーバー時 */
				#ul_problem_drop_area LI:hover{
					background : none repeat scroll 0 0 #00a4e2 !important;
					color      : #fefefe;
					cursor     : pointer;
				}
				/* ロールーオーバー時 かつ Aタグ*/
				#ul_problem_drop_area LI:hover A{
					color:#fefefe;
				}
				/* 奇数行 */
				#ul_problem_drop_area LI.odd{
					background-color: #FFFFFF;
				}
				/* 偶数行 */
				#ul_problem_drop_area LI.even{
					background-color: #F6F6F3;
				}
		/* ドラック元DIV */
		#problem_drag_area{
			background : none repeat scroll 0 0 #ffffff;
			border     : 1px solid #a4a5b3;
			max-height : 150px;
			overflow-y : scroll;
		}
			/* ドラック元DIV 内 UL */
			#ul_problem_drag_area{
				min-height : 150px;
			}
				#ul_problem_drag_area LI{
					display       : list-item;
					border-bottom : 1px solid #a4a5b3;
					padding       : 5px;
				/*	width         : 579px; */
				}
				/* ロールーオーバー時 */
				#ul_problem_drag_area LI:hover{
					background : none repeat scroll 0 0 #00a4e2 !important;
					color      : #fefefe;
					cursor     : pointer;
				}
				/* ロールーオーバー時 かつ Aタグ*/
				#ul_problem_drag_area LI:hover A{
					color:#fefefe;
				}
				
				/* 奇数行 */
				#ul_problem_drag_area LI.odd{
					background-color: #FFFFFF;
				}
				/* 偶数行 */
				#ul_problem_drag_area LI.even{
					background-color: #F6F6F3;
				}
		/* ドロップ先、 プレースホルダ（ドロップ先を示す枠） */
		.problem_drop_placeholder {
			background: none repeat scroll 0 0 #CCCCCC;
		/*	padding: 20px 0;*/
			height:14px;
			border: dashed  1px #CDCDC5;
		}
		/* 設問 - ドラック＆ドロップ設定 ---------- ---------- ---------- */
		
		/* 設問 - 設問名（上段） */
		.problem_name{
			float : left;
		}
		/* 設問 - 削除ボタン・プレビューボタン */
		.problem_del_link{
			float : right;
		}
			/* 削除ボタン・プレビューボタン */
			.delete_button, .preview_button{
				margin: 0px 0px 0px 0px;
			}
		/* 設問 - 設問一括追加・設問一括削除 */
		#all_select_button, #all_delete_button{
			margin: 10px 0 10px 0;
		}
		
		/* 採点公開設定 - 即時公開・時限式公開*/
		  #marking_public_kind_area
		, #marking_public_kind_area DIV{
			padding : 5px;
		}
		
		/* 表示形式 説明文エリア（下表示） -- */
		.tooltip_bottom{
			width					: 16px;
			height					: 16px;
			-moz-border-radius		: 8px;
			-webkit-border-radius	: 8px;
			border-radius			: 8px;
			position				: relative;	/*吹き出しの表示位置指定の基準とするため追加*/
		}
		.tooltip_bottom IMG{
			vertical-align	: middle;
		}
		.tooltip_bottom .tooltip_message_bottom {
			display		: none;
			padding		: 2px 3px;
			width		: 230px;
			text-align	: left;
			
			font-weight: bold;
			padding: 7px;
			font-size: 12px;
		}
		.tooltip_bottom .tooltip_message_bottom:after {
			/* 吹き出しの足 */
			content		: "";
			display		: block;
			width		: 0px;
			height		: 0px;
			border-bottom	: 10px solid #a2a29e; /*  11px  #a2a29e #72726E  #dc0000;*/
			border-left	: 8px solid transparent;
			border-right: 8px solid transparent;
			position	: absolute;
			left		: 105px;	/* 102px; */
			top		: -9px;	/*-11px;*/
		}
		/* アニメーションの内容 */
		@-moz-keyframes bubbleDown {
			0% {top:15px; opacity:0;}
			100% {top:25px; opacity:1;}
		}
		@-webkit-keyframes bubbleDown {
			0% {top:15px; opacity:0;}
			100% {top:25px; opacity:1;}
		}
		@-o-keyframes bubbleDown {
			0% {top:15px; opacity:0;}
			100% {top:25px; opacity:1;}
		}
		@keyframes bubbleDown {
			0% {top:15px; opacity:0;}
			100% {top:25px; opacity:1;}
		}
		



		#problem_choices_list li .problem_del_link {
			display: none;
		}
		#problem_list li .problem_preview_link {
			display: none;
		}
		#ul_problem_drop_area LI {
			display: flex;
		}
		#ul_problem_drag_area li {
			display: flex;
		}
.problem_name {
	float: none;
	text-align: left;
	width: 100%;
}
.problem_preview_link {
	width: 80px;
}
.problem_del_link {
	width: 80px;
}



	</style>
	<script type="text/javascript"><!--
		var students_checked      = {};
		var cources_checked       = {};

		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
			/* datetimepicker設定 */
			$('#exam_open' ).datetimepicker(datetimepickeroption);
			$('#exam_close').datetimepicker(datetimepickeroption);
			$('#marking_public_open').datetimepicker(datetimepickeroption);
			
			/* 選択済み設問・選択候補設問の色の初期化 */
			listColorReset('#problem_drop_area');
			listColorReset('#problem_drag_area');
			
			/* 選択済み設問 に付加される削除リンクの処理  ->  編集画面時の場合、選択済み設問がある場合があるため必要 */
			//add_link_delete();
			
			/* 選択済み設問、ソート */
			$('#ul_problem_drop_area' ).sortable( {
				scrollSpeed: 200,
				revert: false,
				placeholder: 'problem_drop_placeholder',

				receive: function (event, ui) {
					// ドロップされた要素（jQueryオブジェクト）
					const $elem = ui.item;
					$elem.css({ width: '', height: '' }); // 念のためクリア

					// 以降は従来処理をこれに渡す
					li_drop($elem);
					//add_link_delete();
				},

				update: function (event, ui) {
					ui.item.css({ width:'', height:'' }); // 念のためクリア
					listColorReset('#problem_drop_area');
				},
			});
			
			/* ドラック対象の指定（下記は img と li ） */
			$('#problem_drag_area').find('IMG, LI').draggable( {
				connectToSortable: '#ul_problem_drop_area',
				helper: 'clone',
				scrollSpeed: '200',
				start: function(event, ui){
				//	alert("start");
				//	$('#problem_drag_area').find('img,li').draggable( 'disable' );
				},
				stop: function(event, ui){
				//	alert("stop");
				//	$('#problem_drag_area').find('img,li').draggable( 'enable' );
				},
			});
			
			/* ドラック内文字列選択不可 */
			$('#problem_drag_area').disableSelection();
			
			/* 設問一括削除 ボタン処理 */
			$('#all_delete_button').click(function(e){
				$('#ul_problem_drop_area li').remove();
				return false;
			});
			
			/* 設問一括登録 ボタン処理 */
			$('#all_select_button').click(function(e){
				// 選択済み設問 初期化
				$('#ul_problem_drop_area li').remove();
				// 選択候補設問 ループ、設問のコピー
				$('#ul_problem_drag_area').find('li').each(function(idx, item) {
					var elem = $(item).clone();
					li_drop(elem);
					$('#ul_problem_drop_area').append(elem);
				});
				//add_link_delete();                     // 選択済み設問 に付加される削除リンクの処理
				listColorReset('#problem_drop_area');  // 選択済み設問の色の初期化
				return false;
			});
			
			/* 選択候補設問、プレビューボタン */
			//add_link_preview();
			
			/* 講師コンボボックス変更時の処理 */
			$("#teacher_id").bind("change keyup",function(){
				var $default_teacher_id  = '<?=$this->libauth->get_teacher_id();?>';    // ログイン中のログインID
				var $selected_teacher_id = $('#teacher_id option:selected').val();      // 現在選択されたもの
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

			/* 問題に所属する受講者情報の取得 */
			<?php if( isset($exam['exam_students']) ) {
				foreach( $exam['exam_students'] as $lecture) { ?>
					students_checked[<?= htmlspecialchars( $lecture, ENT_QUOTES, 'UTF-8') ?>] = true;
			<?php } } ?>
			
			/* 講座に所属する受講者・設問の表示（初期表示） */
			<?php $cource_id_list = implode('-', $exam['exam_lectures']); ?>
			//ajax_search_cource_students("<?= htmlspecialchars( $cource_id_list, ENT_QUOTES, 'UTF-8') ?>");
			ajax_search_cource_exam_problems("<?= htmlspecialchars( $cource_id_list, ENT_QUOTES, 'UTF-8') ?>");
			
			/* チェックボックスと全選択ボタン連動（受講者） */
			$('#student_list').click(function (){
				// 受講者グループと受講者IDチェックボックスとの連動
				var select_student_id = "0";
				
				$("#student_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						students_checked[$(this).val()] = true;
						
						// 受講者グループと受講者IDチェックボックスとの連動
						select_student_id = ""+select_student_id+","+$(this).val()+""; 
					}else{
						delete students_checked[$(this).val()];
					}
				});
				if($('#student_list input:checked').length == 0){
					$(".select_all_affiliation.select_student").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_student").css("background-position", "center bottom");
				}
				
				// 受講者グループと受講者IDチェックボックスとの連動
				var arraySelect = select_student_id.split(",");
				$("#student_group_list a").map(function() {
					var t_id  = $(this).attr('id');      // id名取得（例：sg0, sg1 等）
					var lists = $("#h"+t_id).val();      // 隣接するheddenの受講者ID群取得（例：1,2,3  のカンマ区切り文字列）
					var arrayLists  = lists.split(",");  // 受講者ID群の配列化
					
					// チェック済み受講者ID群に、受講者グループ対象の受講者IDがある場合、ヒット数カウントアップ
					var hitCount = 0;
					for (var i = 0; i < arrayLists.length; i++) {
						if($.inArray(arrayLists[i], arraySelect)>-1){
							hitCount = hitCount + 1;
						}
					}
					// ヒット数 と 受講者グループ内受講者ID数を比較
					if(hitCount == i){
						// 一致の場合、受講者グループをオン状態
						$("#"+t_id).removeClass("non_selected_group").addClass("selected_group");
					}else{
						// 不一致の場合、受講者グループをオフ状態
						$("#"+t_id).removeClass("selected_group").addClass("non_selected_group");
					}
				});
			});
			
			/* 所属講座「全て選択」ボタンの初期位置 */
			if($('.cource_list input:checked').length == 0){
				$("*[name=select_all_cource]").css("background-position", "center top");
			}else{
				$("*[name=select_all_cource]").css("background-position", "center bottom");
			}
			
			/* 所属講座 配下のチェックボックス押下時の処理 */
			$('.cource_list').click(function (){
				var class_lectures_list = '';
				$('.cource_list input:checked').map(function() {
					if( $(this).attr('checked')) {
						if(class_lectures_list == '' ){
							class_lectures_list = $(this).val();
						}else{
							class_lectures_list = class_lectures_list + '-' + $(this).val();
						}
					}
					
				});
				//ajax_search_cource_students(class_lectures_list);
				ajax_search_cource_exam_problems(class_lectures_list);
				
				if($('.cource_list input:checked').length == 0){
					$("*[name=select_all_cource]").css("background-position", "center top");
				}else{
					$("*[name=select_all_cource]").css("background-position", "center bottom");
				}
			});
			
			/* 受講者、該当者のみ表示 */
			$("#student_check_only").change(function(){
				flag_value = $(this).attr("checked")
				if(flag_value == "checked"){
					// チェックオン→チェックがある受講者以外を非表示
					$("#student_list input:checkbox").map(function() {
						if( $(this).attr('checked') ) {
							// チェックあり
						}else{
							// チェックなし
							$(this).parent("LI").css("display","none");
						}
					});
				}else{
					// チェックオフ→非表示を解除
					$("#student_list input:checkbox").map(function() {
						$(this).parent("LI").css("display","");
					});
				}
			});

			/* 採点公開設定 ドロップダウン 変更時の処理 */
			$("#marking_public_flag").bind("change keyup",function(){
				var $selected_marking_public_flag = $('#marking_public_flag option:selected').val();	// 現在選択されたもの
				
				if( $selected_marking_public_flag == 9 ){
					$("#marking_public_kind_area").slideUp();
				}else{
					$("#marking_public_kind_area").slideDown();
				}
			});
		});

		//**************************************************
		// 採点公開設定 - 即時公開・時限式公開チェックボックス
		//**************************************************
		function marking_public_kind_checkradio(disp) {
			if(disp=="block"){
			//	$("#marking_public_open").slideDown();
				$("#marking_public_open").css("display","");
			}else{
			//	$("#marking_public_open").slideUp();
				$("#marking_public_open").css("display","none");
			}
		}

		//**************************************************
		// 選択済み設問 へドロップ時の処理
		//**************************************************
		function li_drop(target){
			//【name="exam_problem_id[]"】を【name="exam_problems[]"】に変更
			target.children("*[name='exam_problem_id[]']").attr("name","exam_problems[]");
			
			// プレビューボタンの削除
			//target.children('.problem_del_link').empty();
			
			// 削除ボタンの追加
			//target.children('.problem_del_link').append(
			//	'<input class="delete_button" type="button" value="<?= $this->lang->line_or_def('common_deletion','削除') ?>" onclick="" />'
			//);
			
			// 削除ボタンの機能追加
			// ※機能追加は変換設置後にまとめて行う
			//add_link_delete();

			$('#ul_problem_drop_area input[name="exam_problem_id[]"]').attr('name','exam_problems[]');
			$('#ul_problem_drag_area input[name="exam_problems[]"]').attr('name','exam_problem_id[]');
		}

		//**************************************************
		// 選択候補設問、プレビューボタン
		//**************************************************
		/*
		function add_link_preview(){
			$('#problem_drag_area .preview_button').click(function(e){
console.log("[add_link_preview]");
				var exam_problem_id = $(this).closest('LI').children("*[name='exam_problem_id[]']").val();
console.log("[exam_problem_id:"+exam_problem_id+"]");
				var url = "/cms_exam/preview_exam_problem/" + exam_problem_id + "";
				//window.open(url, 'mywindow6', 'width=616, height=418, menubar=no, toolbar=no, scrollbars=no');
				window.open(url, 'mywindow6', 'width=636, height=418, menubar=no, toolbar=no, scrollbars=yes');
				return false;
			});
		}
		*/
		$(function () {
			// プレビューボタン（ドラッグ側・ドロップ側どちらでも動くよう両方に委譲）
			$('#problem_drag_area, #problem_drop_area').on('click', '.preview_button', function (e) {
				e.preventDefault();
				e.stopPropagation();
				const exam_problem_id = $(this).data('epid');
				const url = "/cms_exam/preview_exam_problem/" + exam_problem_id;
				window.open(url, 'mywindow6', 'width=636,height=418,menubar=no,toolbar=no,scrollbars=yes');
			});
		});

		//**************************************************
		// 選択済み設問 に付加される削除リンクの処理
		//**************************************************
		/*
		function add_link_delete(){
			$('#problem_drop_area .delete_button').click(function(e){
console.log("[add_link_delete]");
				var exam_problem_id = $(this).closest('LI').children("*[name='exam_problem_id[]']").val();
console.log("[exam_problem_id:"+exam_problem_id+"]");
				$(this).closest('LI').remove();          // 直近の親要素を削除
				listColorReset('#problem_drop_area');    // 選択済み設問の色の初期化
				return false;
			});
		}
		*/
		$(function () {
			// 削除ボタン（ドロップ側）
			$('#problem_drop_area').on('click', '.delete_button', function (e) {
				e.preventDefault();
				e.stopPropagation();
				$(this).closest('li').remove();
				listColorReset('#problem_drop_area');
			});
		});

		//**************************************************
		// 選択済み設問 LI 列の着色
		//**************************************************
		function listColorReset(target) {
			// 対象内LIループ処理
			$(target).find("UL LI").each(function(idx, elem) {
				if(idx % 2){
					if($(elem).hasClass("odd")){
						$(elem).removeClass("odd");
					}
					$(elem).addClass("even");
				}else{
					if($(elem).hasClass("even")){
						$(elem).removeClass("even");
					}
					$(elem).addClass("odd");
				}
			});
		}

		//**************************************************
		// [ajax]講座所属の設問取得
		//**************************************************
		function ajax_search_cource_exam_problems(cource_id){
			var select_exam_problem_id = "0";
			
			$.ajax({
				url: "/cms_cource/get_cource_exam_problem",
				type: "POST",
				data: "cource_id="+cource_id+"&cource_flag=1",
				
				success: function(response) {
					// 選択済み設問の全削除 → 選択済み設問から、講座に属さない選択済み設問を削除
					$("#ul_problem_drop_area").find("LI").each(function(idx, elem) {
						var target_val = $(elem).children("*[name='exam_problems[]']").val();
						var delete_flg = 1;
						for (var i=0; i< response.length; i++) {
							if(target_val == response[i]['exam_problem_id']){
								delete_flg = 0;
								break;
							}
						}
						if(delete_flg == 1){
							$(elem).remove();
						}
					});
					listColorReset('#problem_drop_area');
					
					// 選択対象設問の全削除
					$("#ul_problem_drag_area LI").remove();
					
					if(response){
						for (var i=0; i< response.length; i++) {
							// 設問グループ取得のため設問IDをカンマ区切りで取得
							select_exam_problem_id = ""+select_exam_problem_id+","+response[i]['exam_problem_id']+""; 
							
							// 取得したデータを行に入れる
							$("#ul_problem_drag_area").append(
								$('<li>').append(
									$('<input type="hidden" name="exam_problem_id[]" value='+response[i]['exam_problem_id']+' />')
								).append(
									$('<div class="problem_name">').text(
										'[No'+response[i]['exam_problem_id']+'] '+response[i]['exam_problem_name']+' [<?= $this->lang->line_or_def('common_management_teacher','管理講師') ?>:'+response[i]['teacher_name']+'] [<?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?>:'+response[i]['answer_point']+']'
									)
								).append(
									$('<div class="problem_preview_link">').html(
										'<input class="preview_button" type="button" value="<?= $this->lang->line_or_def('common_preview','プレビュー') ?>" onclick="" data-epid="' + response[i]['exam_problem_id'] + '" />'
									)
								).append(
									$('<div class="problem_del_link">').html(
										'<input class="delete_button" type="button" value="<?= $this->lang->line_or_def('common_deletion','削除') ?>" onclick="" data-epid="' + response[i]['exam_problem_id'] + '" />'
									)
								).append(
									$('<div style="clear:both;">').text('')
								)
							);
						}
						
						// ドラック対象の指定（下記は IMG と LI 
						$('#problem_drag_area').find('IMG, LI').draggable( {
							connectToSortable: '#ul_problem_drop_area',
							helper: 'clone',
							scrollSpeed: '200',
							start: function(event, ui){
							//	alert("start");
							//	$('#problem_drag_area').find('img,li').draggable( 'disable' );
							},
							stop: function(event, ui){
							//	alert("stop");
							//	$('#problem_drag_area').find('img,li').draggable( 'enable' );
							},
						});
						listColorReset('#problem_drag_area');
						
						// 選択候補設問、プレビューボタン
						//add_link_preview();
						
						// ajax 実行により、設問グループ名=>設問ID群を取得
						$.ajax({
							url: "/cms_exam_problem_group/get_cource_exam_problem_group",
							type: "POST",
							data: "select_exam_problem_id="+select_exam_problem_id,
							
							success: function(response) {
								// 元にあるリンクを削除
								$("#exam_problem_group_list DIV").remove();
								if(response){
									// 取得したデータを行に入れる
									for (var keyString in response) {
										$("#exam_problem_group_list").append(
											$('<div>').append(
												$('<a href="#" onclick="select_exam_problem_group('+"'"+response[keyString]+"'"+');return false;">'+keyString+'</a>')
											)
										);
									}
									$("#exam_problem_group_list").css('display','block');
									$("#exam_problem_group_list_msg").css('display','block');
								}else{
									$("#exam_problem_group_list").css('display','none');
									$("#exam_problem_group_list_msg").css('display','none');
								}
							}
						});
					}else{
						// 設問がない場合
						$("#exam_problem_group_list").css('display','none');
						$("#exam_problem_group_list_msg").css('display','none');
					}
				}
			});
			return false;
		}

		//**************************************************
		// [ajax]講座所属の受講者取得	get_cource_student→get_cource_student_array
		//**************************************************
		function ajax_search_cource_students(cource_id){
			var select_student_id = "0";
			
			$.ajax({
				url: "/cms_cource/get_cource_student_array",
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
								$('<li></li>').append(
									$('<input type="checkbox" name="exam_students[]" id="student_'+response[i]['student_id']+'" value='+response[i]['student_id']+' '+check_flag+'>')
								).append(
								$('<label for="student_'+response[i]['student_id']+'"></label>').text(
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
								// 該当者のみ表示チェックをオフに変更
								$('#student_check_only').removeAttr('checked');
								
								// 元にあるリンクを削除
								$("#student_group_list DIV").remove();
								if(response){
									// 受講者グループ内連番
									count = -1;
									// 取得したデータを行に入れる
									for (var keyString in response) {
										// 受講者グループ内受講者が全てチェックorそれ以外で、CSSを指定
										var array_response_keyString = response[keyString].split(",");
										var check_css   = 'non_selected_group';
										var check_count = 0;
										for (var i = 0; i < array_response_keyString.length; i++) {
											if(students_checked[ array_response_keyString[i] ]){
												check_count = check_count + 1;
											}
										}
										if(i==check_count){
											var check_css   = 'selected_group';
										}

										count = count+1;
										$("#student_group_list").append(
											$('<div>').append(
											//  $('<a href="#" onclick="select_student_group('+"'"+response[keyString]+"'"+');return false;">'+keyString+'</a>')
											$('<a href="#" class="'+check_css+'" id="sg'+count+'" onclick="select_student_group('+"'"+response[keyString]+"','"+'sg'+count+"'"+');return false;">'+keyString+'</a>'+'<input type="hidden" id="hsg'+count+'" value="'+response[keyString]+'" />')
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
						$("#student_group_list").css('display','block');
						$("#student_group_list_msg").css('display','block');
						$("#div_student_check_only").css('display','block');
					}else{
						// 受講者がない場合
						$("#student_ul").append(
							$('<li style="border-bottom: 0px solid silver;">').append('---'))
						$(".select_all_affiliation.select_student").css('display','none');
						$("#student_group_list").css('display','none');
						$("#student_group_list_msg").css('display','none');
						$("#div_student_check_only").css('display','none');
					}
				}
			});
			return false;
		}

		//**************************************************
		// 所属受講者チェックボックスALL-ON or ALL-OFF
		//**************************************************
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
		
		//**************************************************
		// 画面読み込み後、講師コンボボックスの設定
		//**************************************************
		$(function () {
			var $default_teacher_id  = '<?=$this->libauth->get_teacher_id();?>';    // ログイン中のログインID
			var $selected_teacher_id = $('#teacher_id option:selected').val();      // 現在選択されたもの
			
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
		// 受講者グループ「全て選択」ボタン押下時の処理
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
		// 受講者グループ名リンク押下時の処理
		//**************************************************
		function select_student_group(student_id_list, idname){
			if(student_id_list.length == 0){
				return false;
			}
			// 押下されたリンクのCSS変更
			var class_name = $("#"+idname).attr('class');
			if(class_name == "non_selected_group"){
				$("#"+idname).removeClass("non_selected_group").addClass("selected_group");
			}else{
				$("#"+idname).removeClass("selected_group").addClass("non_selected_group");
			}
			// 受講者グループから、チェックオン・チェックオフの受講者ID群を取得
			var check_id_list     = "0";  // チェックオン受講者ID
			var non_check_id_list = "0";  // チェックオフ受講者ID
			$("#student_group_list a").map(function() {
				var t_id    = $(this).attr('id');
				var t_class = $(this).attr('class');
				
				var lists   = $("#h"+t_id).val();
				
				if(t_class == "non_selected_group"){
					non_check_id_list = non_check_id_list + "," + lists;
				}else{
					check_id_list = check_id_list + "," + lists;
				}
			});
			
			// チェックオフ受講者IDのチェックボックスを全てオフにする
			var non_resArray = non_check_id_list.split(",");
			for (var i = 0; i < non_resArray.length; i ++) {
				$('#student_list #student_'+non_resArray[i]).removeAttr('checked');
			}
			// チェックオフ受講者IDのチェックボックスを全てオンにする
			var resArray = check_id_list.split(",");
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

			var class_lectures_list = '';
			$('.cource_list input:checked').map(function() {
				if( $(this).attr('checked')) {
					if(class_lectures_list == '' ){
						class_lectures_list = $(this).val();
					}else{
						class_lectures_list = class_lectures_list + '-' + $(this).val();
					}
				}
				
			});
			//ajax_search_cource_students(class_lectures_list);
			ajax_search_cource_exam_problems(class_lectures_list);
			return false;
		}

		//**************************************************
		// 所属受講者チェックボックスALL-ON or ALL-OFF
		//**************************************************
		function select_all_student(){
			if($('#student_list input:checked').length){
				$('#student_list input').removeAttr('checked');
				$(".select_all_affiliation.select_student").css("background-position", "center top");

				// 受講者グループ、全てチェックオフ状態に
				$("#student_group_list a").map(function() {
					$(this).removeClass("selected_group").addClass("non_selected_group");
				});
			}else{
				$('#student_list input').attr('checked','checked');
				$(".select_all_affiliation.select_student").css("background-position", "center bottom");

				// 受講者グループ、全てチェックオン状態に
				$("#student_group_list a").map(function() {
					$(this).removeClass("non_selected_group").addClass("selected_group");
				});
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
		
		//**************************************************
		// 設問 - 設問グループ名リンク押下時の処理
		//**************************************************
		function select_exam_problem_group(exam_problem_id_list){
			// 設問IDがない場合は終了
			if(exam_problem_id_list.length == 0){
				return false;
			}
			// 設問IDを配列変換
			var resArray = exam_problem_id_list.split(",");
			
			// 選択候補設問 ループ
			$('#ul_problem_drag_area').find('li').each(function(idx, item) {
				// LI の複製
				var elem = $(item).clone();
				// LI から 設問IDを取得
				var target_val = elem.children("*[name='exam_problem_id[]']").val();
				// LI の設問ID ＝ 引数の設問IDの場合、コピー実行
				if($.inArray(target_val, resArray)>-1){
					li_drop(elem);
					$('#ul_problem_drop_area').append(elem);
				}
			});
			//add_link_delete();                     // 選択済み設問 に付加される削除リンクの処理
			listColorReset('#problem_drop_area');  // 選択済み設問の色の初期化
			return false;
		}
		
		//------------------------------------------
		// 表示形式説明文表示・非表示処理（下表示）
		//------------------------------------------
		function tooltip_message_bottom(){
			var value = $(".tooltip_message_bottom").css("display"); 
			if(value == "none"){
				$('.tooltip_message_bottom').css("display", "block");
				$('.tooltip_message_bottom').css("position", "absolute");
			//	$('.tooltip_message_bottom').css("background", "#72726E");
				$('.tooltip_message_bottom').css("background", "-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #a2a29e), color-stop(1, #72726e))");
				$('.tooltip_message_bottom').css("background", "-moz-linear-gradient(top, #a2a29e 5%, #72726e 100%)");
				$('.tooltip_message_bottom').css("background", "-webkit-linear-gradient(top, #a2a29e 5%, #72726e 100%)");
				$('.tooltip_message_bottom').css("background", "-o-linear-gradient(top, #a2a29e 5%, #72726e 100%)");
				$('.tooltip_message_bottom').css("background", "-ms-linear-gradient(top, #a2a29e 5%, #72726e 100%)");
				$('.tooltip_message_bottom').css("background", "linear-gradient(to bottom, #a2a29e 5%, #72726e 100%)");
				$('.tooltip_message_bottom').css("color", "#FFF");
				$('.tooltip_message_bottom').css("top", "25px");	// ("bottom", "25px");
				$('.tooltip_message_bottom').css("left", "-105px"); //-100px
				$('.tooltip_message_bottom').css("-moz-animation", "bubbleDown 0.2s 1");
				$('.tooltip_message_bottom').css("-webkit-animation", "bubbleDown 0.2s 1");
				$('.tooltip_message_bottom').css("-o-animation", "bubbleDown 0.2s 1");
				$('.tooltip_message_bottom').css("animation", "bubbleDown 0.2s 1");
			//	$('.tooltip_message_bottom').css("border-color", "silver #888888 #888888 silver");
			//	$('.tooltip_message_bottom').css("border-image", "none");
			//	$('.tooltip_message_bottom').css("border-style", "solid");
			//	$('.tooltip_message_bottom').css("border-width", "3px 3px 3px 3px");
				$('.tooltip_message_bottom').css("-webkit-border-radius", "4px 4px 4px 4px");
				$('.tooltip_message_bottom').css("-moz-border-radius", "4px 4px 4px 4px");
				$('.tooltip_message_bottom').css("border-radius", "4px 4px 4px 4px");
				$('.tooltip_message_bottom').css("box-shadow", "1px 2px 2px rgba(0,0,0,0.3), 0px 1px 0px rgba(255,255,255,0.5) inset, 0px -1px 0px rgba(255,255,255,0.2) inset");
			}else{
				$('.tooltip_message_bottom').css("display", "none");
				$('.tooltip_message_bottom').css("position", "");
				$('.tooltip_message_bottom').css("background", "");
				$('.tooltip_message_bottom').css("color", "");
				$('.tooltip_message_bottom').css("top", "");
				$('.tooltip_message_bottom').css("left", "");
				$('.tooltip_message_bottom').css("-moz-animation", "");
				$('.tooltip_message_bottom').css("-webkit-animation", "");
				$('.tooltip_message_bottom').css("-o-animation", "");
				$('.tooltip_message_bottom').css("animation", "");
				$('.tooltip_message_bottom').css("-webkit-border-radius", "");
				$('.tooltip_message_bottom').css("-moz-border-radius", "");
				$('.tooltip_message_bottom').css("border-radius", "");
				$('.tooltip_message_bottom').css("box-shadow", "");
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

				<h2><?= $this->lang->line_or_def('msg_exam_input','問題の情報を入力してください') ?></h2>

				<?=form_open_multipart("cms_exam/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<input type="hidden" name="update_flg" value='<?=set_value('update_flg', $exam['update_flg'])?>'>
					<input type="hidden" name="exam_id"   value='<?=set_value('exam_id',   $exam['exam_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam_name','問題（テスト）名') ?></th>
							<td>
								<input type=text name="exam_name" maxlength="256" size="30" value='<?=set_value('exam_name',$exam['exam_name'])?>'>
							</td>
						</tr>

<?php if(false){ ?>
						<? if( getenv('URL_SERVICE')=='mitemo' ): ?>
							<input type="hidden" name="teacher_id"   value='<?=set_value('teacher_id',   $exam['teacher_id'])?>'>
						<? else: ?>
						<tr>
							<th ><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<?=form_dropdown('teacher_id',$teachers_dropdown,set_value('teacher_id',$exam['teacher_id']), 'id="teacher_id"'); ?>
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
												<input type="checkbox" name="exam_lectures[]" id="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>
													<?php 
													if( isset($exam['exam_lectures']) ) {
														foreach( $exam['exam_lectures'] as $lecture) { 
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
<?php if(false){ ?>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_student','受講者') ?>
								<a href="#" onclick="select_all_student();return false;" class="select_all_affiliation select_student" style="display:none;"></a>
								<div id="student_group_list"></div>
								<div id="student_group_list_msg">
									<?= $this->lang->line_or_def('msg_select_group_caption','※上記グループ名選択により、同グループ受講者にチェックが付加される') ?>
								</div>
								<div id="div_student_check_only">
									<input type="checkbox" value="1" id="student_check_only">
									<label for="student_check_only"><?= $this->lang->line_or_def('common_checked_person_only','該当者のみ表示') ?></label>
								</div>
							</th>
							<td>
								<div id="student_list">
									<ul class="list" id="student_ul"><li>---</li></ul>
								</div>
							</td>
						</tr>
<?php } ?>
						<tr>
							<th style="vertical-align: top;"><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="exam_caption" ><?=set_value('exam_caption',$exam['exam_caption'])?></textarea>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_submit_period','提出期間') ?></th>
							<td >
								<input type="text" name="exam_open" size="19" value="<?=set_value('exam_open',$exam['exam_open'])?>" id="exam_open" readonly>
								<?= $this->lang->line_or_def('common_range','～') ?>
								<input type="text" name="exam_close" size="19" value="<?=set_value('exam_close',$exam['exam_close'])?>" id="exam_close" readonly>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_indication_status','公開設定') ?></th>
							<td >
								<?=form_dropdown('public_flag', $public_flag_list, set_value('public_flag', $exam['public_flag']));?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_resubmit','再提出') ?></th>
							<td >
								<?=form_dropdown('resubmit_flag', $resubmit_flag_list, set_value('resubmit_flag', $exam['resubmit_flag']));?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_marking_public_status','採点公開設定') ?></th>
							<td >
								<?=form_dropdown('marking_public_flag', $public_flag_list, set_value('marking_public_flag', $exam['marking_public_flag']), 'id="marking_public_flag"' );?>
<?php if(false){ ?>
								<div id="marking_public_kind_area" style="<?=(set_value('marking_public_flag',$exam['marking_public_flag'])==9) ? 'display:none;' : ''?> ">
									<div>
										<input type="radio" onclick="marking_public_kind_checkradio('none')" value="1" id="marking_public_kind" name="marking_public_kind" <?=(set_value('marking_public_kind',$exam['marking_public_kind'])==1) ? 'checked' : ''?> />
										<label for="marking_public_kind_now"><?= $this->lang->line_or_def('common_spot_public','即時公開') ?></label>
									</div>
									<div>
										<input type="radio" onclick="marking_public_kind_checkradio('block')" value="2" id="marking_public_kind" name="marking_public_kind" <?=(set_value('marking_public_kind',$exam['marking_public_kind'])==2) ? 'checked' : ''?> />
										<label for="marking_public_kind_time"><?= $this->lang->line_or_def('common_timed_public','時限式公開') ?></label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name="marking_public_open" size="19" value="<?=set_value('marking_public_open',$exam['marking_public_open'])?>" id="marking_public_open" readonly style="<?=(set_value('marking_public_kind',$exam['marking_public_kind'])==1) ? 'display:none;' : ''?>" >
									</div>
								</div>
<?php } else { ?>
								<input type="hidden" name="marking_public_kind" value="1">
<?php } ?>
							</td>
						</tr>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_display_format'  ,'表示形式') ?>
								&nbsp;&nbsp;<a class="tooltip_bottom" href="#" onclick="tooltip_message_bottom();return false;"><img src="/static/image/question.png" alt="question" />
												<span class="tooltip_message_bottom">
													<?= $this->lang->line_or_def('common_full_screen_collectively_scoring_format'      , '全画面一括採点形式'); ?><br />&nbsp;&nbsp;
													<?= $this->lang->line_or_def('msg_tooltip_full_screen_collectively_scoring_format' , '１画面に全ての問題が表示され、解答欄に全て入力した上で一括採点となる形式'); ?>
													<br/><br/>
													<?= $this->lang->line_or_def('common_q_and_a_format_each_time_scoring'      , '一問一答形式（都度採点）'); ?><br />&nbsp;&nbsp;
													<?= $this->lang->line_or_def('msg_tooltip_q_and_a_format_each_time_scoring' , '１問ごとに１画面が表示され、問題と解答入力をし、１問ごとに１画面で解答解説を閲覧することを繰り返す形式'); ?>
													<br/><br/>
													<?= $this->lang->line_or_def('common_q_and_a_format_collectively_scoring'      , '一問一答形式（一括採点）'); ?><br />&nbsp;&nbsp;
													<?= $this->lang->line_or_def('msg_tooltip_q_and_a_format_collectively_scoring' , '１問ごとに１画面が表示され、問題と解答入力を繰り返し、最後に一括で解答解説を閲覧する形式'); ?>
												</span></a>
							</th>
							<td >
								<input type="radio" value="0" id="display_format" name="display_format" <?=(set_value('display_format',$exam['display_format'])==0) ? 'checked' : ''?> /><?= $this->lang->line_or_def('common_full_screen_collectively_scoring_format','全画面一括採点形式'); ?>&nbsp;
								<input type="radio" value="1" id="display_format" name="display_format" <?=(set_value('display_format',$exam['display_format'])==1) ? 'checked' : ''?> /><?= $this->lang->line_or_def('common_q_and_a_format_each_time_scoring','一問一答形式（都度採点）'); ?>
								<input type="radio" value="2" id="display_format" name="display_format" <?=(set_value('display_format',$exam['display_format'])==2) ? 'checked' : ''?> /><?= $this->lang->line_or_def('common_q_and_a_format_collectively_scoring','一問一答形式（一括採点）'); ?>
							</td>
						</tr>
						<tr>
							<th style="vertical-align: top;">
								<?= $this->lang->line_or_def('common_exam_problem','設問') ?>

								<div id="exam_problem_group_list"></div>
								<div id="exam_problem_group_list_msg">
									<?= $this->lang->line_or_def('msg_select_exam_problem_group_caption','※上記設問グループ名選択により、同グループ設問が選択されます') ?>
								</div>
							</th>
							<td>
								<div id="problem_list">
									<div id="problem_drop_area">
										<ul id="ul_problem_drop_area">
											<?php if( (isset($exam_problems_list)) && (isset($exam['exam_problems'])) ): ?>
												<?php foreach( $exam['exam_problems'] as $exam_problem_id ): ?>
													<?php foreach( $exam_problems_list as $exam_problem ): ?>
														<?php if($exam_problem['exam_problem_id'] == $exam_problem_id): ?>
															<li>
																<input type="hidden" name="exam_problems[]" value=<?= htmlspecialchars( $exam_problem['exam_problem_id'], ENT_QUOTES, 'UTF-8') ?> />
																<div class="problem_name">
																
																<? if( getenv('URL_SERVICE')=='mitemo' ): ?>
																	[No<?= htmlspecialchars( $exam_problem['exam_problem_id'], ENT_QUOTES, 'UTF-8') ?>] <?= htmlspecialchars( $exam_problem['exam_problem_name'], ENT_QUOTES, 'UTF-8') ?> [<?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?>:<?= htmlspecialchars( $exam_problem['answer_point'], ENT_QUOTES, 'UTF-8') ?>]
																<? else: ?>
																	[No<?= htmlspecialchars( $exam_problem['exam_problem_id'], ENT_QUOTES, 'UTF-8') ?>] <?= htmlspecialchars( $exam_problem['exam_problem_name'], ENT_QUOTES, 'UTF-8') ?> [<?= $this->lang->line_or_def('common_management_teacher','管理講師') ?>:<?= htmlspecialchars( $exam_problem['teacher_name'], ENT_QUOTES, 'UTF-8') ?>] [<?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?>:<?= htmlspecialchars( $exam_problem['answer_point'], ENT_QUOTES, 'UTF-8') ?>]
																<? endif; ?>
																
																</div>
																<div class="problem_preview_link">
																	<input class="preview_button" type="button" value="<?= $this->lang->line_or_def('common_preview','プレビュー') ?>" onclick="" data-epid="<?= htmlspecialchars( $exam_problem['exam_problem_id'], ENT_QUOTES, 'UTF-8') ?>" />
																</div>
																<div class="problem_del_link">
																	<input class="delete_button" type="button" value="<?= $this->lang->line_or_def('common_deletion','削除') ?>" onclick="" data-epid="<?= htmlspecialchars( $exam_problem['exam_problem_id'], ENT_QUOTES, 'UTF-8') ?>" />
																</div>
																<div style="clear:both;"></div>
															</li>
															<?php break; ?>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endforeach; ?>
											<?php endif; ?>
										
										</ul>
									</div>
									<input id="all_delete_button" type="button" value="<?= $this->lang->line_or_def('common_exam_problem_all_delete','設問一括削除') ?>" onclick="">
								</div>
								<br>
								<div id="problem_choices_list">
									<div id="problem_drag_area">
										<ul id="ul_problem_drag_area">
										</ul>
									</div>
									<input id="all_select_button" type="button" value="<?= $this->lang->line_or_def('common_exam_problem_all_add','設問一括追加') ?>" onclick="">
								</div>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_criteria','判定基準') ?></th>
							<td>
								<?=form_dropdown('criteria_type', $criteria_type_list, set_value('criteria_type', $exam['criteria_type']));?>
								<input type=text name="criteria_value" maxlength="256" size="10" value='<?=set_value('criteria_value',$exam['criteria_value'])?>'>
								点/％/数
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
