<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course";
	$this->load->view('header/header',$data);?>
	<style type="text/css"><!--
		TEXTAREA{
			width : 100%;
			height : 70px;
		}
		TEXTAREA[name="cource_caption"]{
			height : 200px;
		}
		
		#student_freeword{width: 300px;}	/* 所属受講生検索エリア */
		#student_ul{margin-top: 0px;}		/* 所属受講生選択エリア */
		#student_ul li{
			margin-left: 2px;
			margin-bottom: 0px;
		}

		#material_freeword{width: 300px;}	/* 所属資料検索エリア */
		#material_ul{margin-top: 0px;}		/* 所属資料選択エリア */
		#material_ul li{
			margin-left: 2px;
			margin-bottom: 0px;
		}
		
		#book_library_freeword{width: 300px;}	/* 所属図書室検索エリア */
		#book_library_ul{margin-top: 0px;}		/* 所属図書室選択エリア */
		#book_library_ul li{
			margin-left: 2px;
			margin-bottom: 0px;
		}
		
		#video_freeword{width: 300px;}		/* 所属ビデオ検索エリア */
		#video_ul{margin-top: 0px;}			/* 所属ビデオ選択エリア */
		#video_ul li{
			margin-left: 2px;
			margin-bottom: 0px;
		}
		
		#cource_open, #cource_close{width: 126px;}		/* 公開期間（開始・終了） */

		/* グループ表示関連  */
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
		var materials_checked     = {};
		var book_librarys_checked = {};
		var videos_checked        = {};
		
		// 一覧ボタン押下フラグ
		var student_all_search      = 0;
		var material_all_search     = 0;
		var book_library_all_search = 0;
		var video_all_search        = 0;
		
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datetimepicker設定
			$('#cource_open' ).datetimepicker(datetimepickeroption);
			$('#cource_close').datetimepicker(datetimepickeroption);

			// [2012/11/30]講座所属の受講生情報の取得
			<?php if( isset($cource['lecture_students']) ) {
				foreach( $cource['lecture_students'] as $lecture) { ?>
					students_checked[<?=$lecture; ?>] = true;
			<?php } } ?>

			// [2012/11/30]講座所属の資料情報の取得
			<?php if( isset($cource['lecture_materials']) ) {
				foreach( $cource['lecture_materials'] as $lecture) { ?>
					materials_checked[<?=$lecture; ?>] = true;
			<?php } } ?>

			// [2012/11/30]講座所属の図書室情報の取得
			<?php if( isset($cource['lecture_book_librarys']) ) {
				foreach( $cource['lecture_book_librarys'] as $lecture) { ?>
					book_librarys_checked[<?=$lecture; ?>] = true;
			<?php } } ?>

			// [2012/11/30]講座所属のビデオ情報の取得
			<?php if( isset($cource['lecture_videos']) ) {
				foreach( $cource['lecture_videos'] as $lecture) { ?>
					videos_checked[<?=$lecture; ?>] = true;
			<?php } } ?>

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
				var check_count = 0;
				for (var key in students_checked) {
					check_count = check_count + 1;
				}
				$("#student_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			});

			// [2012/11/30]チェックボックスと全選択ボタン連動（資料）
			$('#material_list').click(function (){

				$("#material_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						materials_checked[$(this).val()] = true;
					}else{
						delete materials_checked[$(this).val()];
					}
				});
				if($('#material_list input:checked').length == 0){
					$(".select_all_affiliation.select_material").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_material").css("background-position", "center bottom");
				}
				var check_count = 0;
				for (var key in materials_checked) {
					check_count = check_count + 1;
				}
				$("#material_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			});

			// [2012/11/30]チェックボックスと全選択ボタン連動（図書室）
			$('#book_library_list').click(function (){

				$("#book_library_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						book_librarys_checked[$(this).val()] = true;
					}else{
						delete book_librarys_checked[$(this).val()];
					}
				});
				if($('#book_library_list input:checked').length == 0){
					$(".select_all_affiliation.select_book_library").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_book_library").css("background-position", "center bottom");
				}
				var check_count = 0;
				for (var key in book_librarys_checked) {
					check_count = check_count + 1;
				}
				$("#book_library_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			});

			// [2012/11/30]チェックボックスと全選択ボタン連動（ビデオ）
			$('#video_list').click(function (){
				$("#video_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						videos_checked[$(this).val()] = true;
					}else{
						delete videos_checked[$(this).val()];
					}
				});
				if($('#video_list input:checked').length == 0){
					$(".select_all_affiliation.select_video").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_video").css("background-position", "center bottom");
				}
				var check_count = 0;
				for (var key in videos_checked) {
					check_count = check_count + 1;
				}
				$("#video_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			});
			
			// 受講生-一覧ボタン押下時
			$("#btn_all_student").click(function() {
				student_all_search = 1;
				document.getElementById('btn_search_student').click();
			});
			
			// [2012/11/30]受講生-検索ボタン押下時
			$("#btn_search_student").click(function() {
				var student_freeword  = $("#student_freeword").val();
				var cource_id         = $("*[name=cource_id]").val();
				var select_student_id = "0";
				
				// 一覧ボタン時は検索条件初期化
				if(student_all_search==1){
					student_freeword   = '';
					student_all_search = 0;
				}
				
				$.ajax({
					url: "/cms_cource/get_cource_student",
					type: "POST",
					data: "free_word="+student_freeword+"&cource_id="+cource_id,
					
					success: function(response) {
						// 元にある行を削除
						$("#student_ul li").remove();
						
						// 元にあるグループリンクを削除・非表示
						$("#student_group_list DIV").remove();
						$("#student_group_list").css('display','none');
						$("#student_group_list_msg").css('display','none');
						
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
									}
								}
							});
							
							$("#student_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:"+(i)+" / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['student_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_student").css('display','block');
						}else{
							$("#student_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:0 / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['student_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_student").css('display','none');
						}
					}
				});
			});

			// 資料-一覧ボタン押下時
			$("#btn_all_material").click(function() {
				material_all_search = 1;
				document.getElementById('btn_search_material').click();
			});
			
			// [2012/11/30]資料-検索ボタン押下時
			$("#btn_search_material").click(function() {
				var material_freeword = $("#material_freeword").val();
				var cource_id         = $("*[name=cource_id]").val();
				
				// 一覧ボタン時は検索条件初期化
				if(material_all_search==1){
					material_freeword   = '';
					material_all_search = 0;
				}
				
				$.ajax({
					url: "/cms_cource/get_cource_material",
					type: "POST",
					data: "free_word="+material_freeword+"&cource_id="+cource_id,
					
					success: function(response) {
						// 元にある行を削除
						$("#material_ul li").remove();
						
						// 全て選択ボタンの初期化
						$(".select_all_affiliation.select_material").css("background-position", "center top");

						if(response){
							// 取得したデータを行に入れる
							for (var i=0; i< response.length; i++) {
								var check_flag = '';
								if(materials_checked[response[i]['material_id']]){
									check_flag = 'checked';
									$(".select_all_affiliation.select_material").css("background-position", "center bottom");	// 選択済みが１つ以上ある場合、ボタン位置変更
								}
								$("#material_ul").append(
									$('<li>').append(
										$('<input type="checkbox" name="lecture_materials[]" id="material_'+response[i]['material_id']+'" value='+response[i]['material_id']+' '+check_flag+'>')
									).append(
									$('<label for="material_'+response[i]['material_id']+'">').text(
										' [No'+response[i]['material_id']+'] '+response[i]['material_logic_name']
									))
								);
							}
							$("#material_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:"+(i)+" / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['material_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_material").css('display','block');
						}else{
							$("#material_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:0 / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['material_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_material").css('display','none');
						}
					}
				});
			});

			// 図書室-一覧ボタン押下時
			$("#btn_all_book_library").click(function() {
				book_library_all_search = 1;
				document.getElementById('btn_search_book_library').click();
			});
			
			// [2012/11/30]図書室-検索ボタン押下時
			$("#btn_search_book_library").click(function() {
				var book_library_freeword = $("#book_library_freeword").val();
				var cource_id             = $("*[name=cource_id]").val();
				
				// 一覧ボタン時は検索条件初期化
				if(book_library_all_search==1){
					book_library_freeword   = '';
					book_library_all_search = 0;
				}
				
				$.ajax({
					url: "/cms_cource/get_cource_book_library",
					type: "POST",
					data: "free_word="+book_library_freeword+"&cource_id="+cource_id,
					
					success: function(response) {
						// 元にある行を削除
						$("#book_library_ul li").remove();
						
						// 全て選択ボタンの初期化
						$(".select_all_affiliation.select_book_library").css("background-position", "center top");
						
						if(response){
							// 取得したデータを行に入れる
							for (var i=0; i< response.length; i++) {
								var check_flag = '';
								if(book_librarys_checked[response[i]['book_library_id']]){
									check_flag = 'checked';
									$(".select_all_affiliation.select_book_library").css("background-position", "center bottom");	// 選択済みが１つ以上ある場合、ボタン位置変更
								}
								$("#book_library_ul").append(
									$('<li>').append(
										$('<input type="checkbox" name="lecture_book_librarys[]" id="book_library_'+response[i]['book_library_id']+'" value='+response[i]['book_library_id']+' '+check_flag+'>')
									).append(
									$('<label for="book_library_'+response[i]['book_library_id']+'">').text(
										' [No'+response[i]['book_library_id']+'] '+response[i]['book_library_logic_name']
									))
								);
							}
							$("#book_library_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:"+(i)+" / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['book_library_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_book_library").css('display','block');
						}else{
							$("#book_library_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:0 / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['book_library_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_book_library").css('display','none');
						}
					}
				});
			});

			// ビデオ-一覧ボタン押下時
			$("#btn_all_video").click(function() {
				video_all_search = 1;
				document.getElementById('btn_search_video').click();
			});
			
			// [2012/11/30]ビデオ-検索ボタン押下時
			$("#btn_search_video").click(function() {
				var video_freeword = $("#video_freeword").val();
				var cource_id      = $("*[name=cource_id]").val();
				
				// 一覧ボタン時は検索条件初期化
				if(video_all_search==1){
					video_freeword   = '';
					video_all_search = 0;
				}
				
				$.ajax({
					url: "/cms_cource/get_cource_video",
					type: "POST",
					data: "free_word="+video_freeword+"&cource_id="+cource_id,
					
					success: function(response) {
						// 元にある行を削除
						$("#video_ul li").remove();
						
						// 全て選択ボタンの初期化
						$(".select_all_affiliation.select_video").css("background-position", "center top");
						
						if(response){
							// 取得したデータを行に入れる
							for (var i=0; i< response.length; i++) {
								var check_flag = '';
								if(videos_checked[response[i]['video_id']]){
									check_flag = 'checked';
									$(".select_all_affiliation.select_video").css("background-position", "center bottom");	// 選択済みが１つ以上ある場合、ボタン位置変更
								}
								$("#video_ul").append(
									$('<li>').append(
										$('<input type="checkbox" name="lecture_videos[]" id="video_'+response[i]['video_id']+'" value='+response[i]['video_id']+' '+check_flag+'>')
									).append(
									$('<label for="video_'+response[i]['video_id']+'">').text(
										' [No'+response[i]['video_id']+'] '+response[i]['video_logic_name']
									))
								);
							}
							$("#video_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:"+(i)+" / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['video_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_video").css('display','block');
						}else{
							$("#video_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:0 / "
							+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $cource['video_all_count'], ENT_QUOTES, 'UTF-8') ?>);
							$(".select_all_affiliation.select_video").css('display','none');
						}
					}
				});
			});

			// [2012/11/30]確認ボタン押下時（submit実行前処理）
			$('form').submit(function(){
				//console.log('test1')
				$('form').serialize();

				// 選択済み受講者
				for (var key in students_checked) {
					if(students_checked[key] == true){
						$('<input />').attr('type', 'hidden')
									  .attr('name', 'lecture_students_array[]')
									  .attr('value', key)
									  .appendTo('form');
					}
				}
				
				// 選択済み資料
				for (var key in materials_checked) {
					if(materials_checked[key] == true){
						$('<input />').attr('type', 'hidden')
									  .attr('name', 'lecture_materials_array[]')
									  .attr('value', key)
									  .appendTo('form');
					}
				}
				
				// 選択済み図書室
				for (var key in book_librarys_checked) {
					if(book_librarys_checked[key] == true){
						$('<input />').attr('type', 'hidden')
									  .attr('name', 'lecture_book_librarys_array[]')
									  .attr('value', key)
									  .appendTo('form');
					}
				}
				
				// 選択済みビデオ
				for (var key in videos_checked) {
					if(videos_checked[key] == true){
						$('<input />').attr('type', 'hidden')
									  .attr('name', 'lecture_videos_array[]')
									  .attr('value', key)
									  .appendTo('form');
					}
				}
			});
		});

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
			var check_count = 0;
			for (var key in students_checked) {
				check_count = check_count + 1;
			}
			$("#student_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			return false;
		}

		// 資料チェックボックスALL-ON or ALL-OFF
		function select_all_material(){
			if($('#material_list input:checked').length){
				$('#material_list input').removeAttr('checked');
				$(".select_all_affiliation.select_material").css("background-position", "center top");
			}else{
				$('#material_list input').attr('checked','checked');
				$(".select_all_affiliation.select_material").css("background-position", "center bottom");
			}
			
			// [2012/11/30]値の初期化
			$("#material_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					materials_checked[$(this).val()] = true;
				}else{
					delete materials_checked[$(this).val()];
				}
			});
			var check_count = 0;
			for (var key in materials_checked) {
				check_count = check_count + 1;
			}
			$("#material_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			return false;
		}

		// 図書室チェックボックスALL-ON or ALL-OFF
		function select_all_book_library(){
			if($('#book_library_list input:checked').length){
				$('#book_library_list input').removeAttr('checked');
				$(".select_all_affiliation.select_book_library").css("background-position", "center top");
			}else{
				$('#book_library_list input').attr('checked','checked');
				$(".select_all_affiliation.select_book_library").css("background-position", "center bottom");
			}
			
			// [2012/11/30]値の初期化
			$("#book_library_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					book_librarys_checked[$(this).val()] = true;
				}else{
					delete book_librarys_checked[$(this).val()];
				}
			});
			var check_count = 0;
			for (var key in book_librarys_checked) {
				check_count = check_count + 1;
			}
			$("#book_library_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			return false;
		}

		// ビデオチェックボックスALL-ON or ALL-OFF
		function select_all_video(){
			if($('#video_list input:checked').length){
				$('#video_list input').removeAttr('checked');
				$(".select_all_affiliation.select_video").css("background-position", "center top");
			}else{
				$('#video_list input').attr('checked','checked');
				$(".select_all_affiliation.select_video").css("background-position", "center bottom");
			}
			
			// [2012/11/30]値の初期化
			$("#video_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					videos_checked[$(this).val()] = true;
				}else{
					delete videos_checked[$(this).val()];
				}
			});
			var check_count = 0;
			for (var key in videos_checked) {
				check_count = check_count + 1;
			}
			$("#video_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			return false;
		}

		// [2012/11/30] 受講者テキストボックス上でのEnterキーで検索ボタン押下
		function submitStop_student(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_search_student').click();
				return false;
			}
		}

		// [2012/11/30] 資料テキストボックス上でのEnterキーで検索ボタン押下
		function submitStop_material(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_search_material').click();
				return false;
			}
		}

		// [2012/11/30] 図書室テキストボックス上でのEnterキーで検索ボタン押下
		function submitStop_book_library(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_search_book_library').click();
				return false;
			}
		}

		// [2012/11/30] ビデオテキストボックス上でのEnterキーで検索ボタン押下
		function submitStop_video(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_search_video').click();
				return false;
			}
		}

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
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>


	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course','講座管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_course_comment','講座を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_cource/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach" href="/cms_cource/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add selected" href="/cms_cource/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_course_input','講座の情報を入力してください') ?></h2>

				<?=form_open("cms_cource/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<? if( (isset($elm_stat)) && ($elm_stat != 200) ): ?><div class="error"><?= htmlspecialchars( $elm_message, ENT_QUOTES, 'UTF-8').'(code:'.htmlspecialchars( $elm_stat, ENT_QUOTES, 'UTF-8').')'; ?></div><? endif; ?>
					
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $cource['update_flg'])?>'>
					<input type=hidden name=cource_id value='<?=set_value('cource_id', $cource['cource_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<input type="text" name="cource_name" size="45" value="<?=set_value('cource_name',$cource['cource_name'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
							<td >
								<input type="text" name="cource_open" size="19" value="<?=set_value('cource_open',$cource['cource_open'])?>" id="cource_open" readonly>
								<?= $this->lang->line_or_def('common_range','～') ?>
								<input type="text" name="cource_close" size="19" value="<?=set_value('cource_close',$cource['cource_close'])?>" id="cource_close" readonly>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="cource_caption" ><?=set_value('cource_caption',$cource['cource_caption'])?></textarea>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td >
								<textarea name="cource_note" ><?=set_value('cource_note',$cource['cource_note'])?></textarea>
							</td>
						</tr>

						<tr>
							<th>
								<?= $this->lang->line_or_def('common_student','受講者') ?>
								<div id="student_check_count" style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_students']); ?>]
								</div>
								<a href="#" onclick="select_all_student();return false;" class="select_all_affiliation select_student" style="display:none;"></a>
								<div id="student_group_list"></div>
								<div id="student_group_list_msg">※上記グループ名選択により、同グループ受講者にチェックが付加される</div>
							</th>
							<td>
								<?= $this->lang->line_or_def('common_freeword','フリーワード') ?>&nbsp;
								<input type="text" id="student_freeword" value="" onKeyPress="return submitStop_student(event);"/>&nbsp;
								<input type="button" id="btn_search_student" value="検索" />
								<input type="button" id="btn_all_student"    value="一覧" />
								<div style="float: right; margin-top: 6px; margin-right: 10px;">
									<label id="student_count">
										<?= $this->lang->line_or_def('common_indication','表示') ?>:0&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_total','総') ?>:<?= htmlspecialchars( $cource['student_all_count'], ENT_QUOTES, 'UTF-8') ?>
									</label>
								</div><div style="clear:both;"></div>
								<div id="student_list">
									<ul class="list" id="student_ul"></ul>
								</div>
							</td>
						</tr>

<? $user_auths = $this->session->userdata; ?>
<? $contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]); ?>
<? if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ): ?>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_material','資料') ?>
								<div id="material_check_count" style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_materials']); ?>]
								</div>
								<a href="#" onclick="select_all_material();return false;" class="select_all_affiliation select_material" style="display:none;"></a>
							</th>
							<td>
								<?= $this->lang->line_or_def('common_freeword','フリーワード') ?>&nbsp;
								<input type="text" id="material_freeword" value="" onKeyPress="return submitStop_material(event);"/>&nbsp;
								<input type="button" id="btn_search_material" value="検索" />
								<input type="button" id="btn_all_material"    value="一覧" />
								<div style="float: right; margin-top: 6px; margin-right: 10px;">
									<label id="material_count">
										<?= $this->lang->line_or_def('common_indication','表示') ?>:0&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_total','総') ?>:<?= htmlspecialchars( $cource['material_all_count'], ENT_QUOTES, 'UTF-8') ?>
									</label>
								</div><div style="clear:both;"></div>
								<div id="material_list">
									<ul class="list" id="material_ul"></ul>
								</div>
							</td>
						</tr>
<? endif; ?>

<? if( (isset($contract_param['book_library'])) && ($contract_param['book_library']['contract']==='fixation') ): ?>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_book_library','図書室') ?>
								<div id="book_library_check_count" style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_book_librarys']); ?>]
								</div>
								<a href="#" onclick="select_all_book_library();return false;" class="select_all_affiliation select_book_library" style="display:none;"></a>
							</th>
							<td>
								<?= $this->lang->line_or_def('common_freeword','フリーワード') ?>&nbsp;
								<input type="text" id="book_library_freeword" value="" onKeyPress="return submitStop_book_library(event);"/>&nbsp;
								<input type="button" id="btn_search_book_library" value="検索" />
								<input type="button" id="btn_all_book_library"    value="一覧" />
								<div style="float: right; margin-top: 6px; margin-right: 10px;">
									<label id="book_library_count">
										<?= $this->lang->line_or_def('common_indication','表示') ?>:0&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_total','総') ?>:<?= htmlspecialchars( $cource['book_library_all_count'], ENT_QUOTES, 'UTF-8') ?>
									</label>
								</div><div style="clear:both;"></div>
								<div id="book_library_list">
									<ul class="list" id="book_library_ul"></ul>
								</div>
							</td>
						</tr>
<? endif; ?>

<? if( (isset($contract_param['video'])) && ($contract_param['video']['contract']==='fixation') ): ?>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_video','ビデオ') ?>
								<div id="video_check_count" style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_videos']); ?>]
								</div>
								<a href="#" onclick="select_all_video();return false;" class="select_all_affiliation select_video" style="display:none;"></a>
							</th>
							<td>
								<?= $this->lang->line_or_def('common_freeword','フリーワード') ?>&nbsp;
								<input type="text" id="video_freeword" value="" onKeyPress="return submitStop_video(event);"/>&nbsp;
								<input type="button" id="btn_search_video" value="検索" />
								<input type="button" id="btn_all_video"    value="一覧" />
								<div style="float: right; margin-top: 6px; margin-right: 10px;">
									<label id="video_count">
										<?= $this->lang->line_or_def('common_indication','表示') ?>:0&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_total','総') ?>:<?= htmlspecialchars( $cource['video_all_count'], ENT_QUOTES, 'UTF-8') ?>
									</label>
								</div><div style="clear:both;"></div>
								<div id="video_list">
									<ul class="list" id="video_ul"></ul>
								</div>
							</td>
						</tr>
<? endif; ?>

					</table>
					<div class="submit">
						<input type="image" src="/static/image/btn_confirm.png" />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
