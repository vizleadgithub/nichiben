<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "video";
	$this->load->view('header/header',$data);?>
	<style>
	.cource_list, .exclusive_book_library_list{
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
	#cource_ul, #exclusive_book_library_ul{
		margin-top: 0px;
	}
	#cource_ul li, #exclusive_book_library_ul li{
		margin-left: 2px;
		margin-bottom: 0px;
	}

	</style>
	<script type="text/javascript"><!--
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datetimepicker設定
			$('#local_reading_open' ).datetimepicker(datetimepickeroption);
			$('#local_reading_close').datetimepicker(datetimepickeroption);

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
				get_book_library_list();
			});

			// [2012/12/07]図書室名コンボボックス変更時の処理
			$("*[name='exclusive_book_library[]']").change(function(){
				// チェンジしたコンボボックスが属するtrタグのIDを取得
				tr_id = $(this).parent().parent().attr("id");

				// チェンジしたコンボボックスの値（図書ID）
				select_id = $(this).val();
				
				$.ajax({
					url: "/cms_video/get_cource_book_library_exclusive",
					type: "POST",
					data: "book_library_id="+select_id,

					success: function(response) {
						
						if(response){
							// exclusive_book_library のループ
							$("*[name='exclusive_book_library[]']").each(function(idx){
							
								// 親要素trのIDが同じグループを更新
								if( tr_id == $(this).parent().parent().attr("id") ){

									$("*[name='exclusive_page_number[]']").each(function(idx2){
										if(idx == idx2){
											// ページ数の初期化
											$(this).empty();
											$(this).append('<option selected="selected" value=-1>---</option>');
											for (var i=0;i <= response[0]['page_num']; i++){
												$(this).append(
													'<option value='+i+'>'+i+'</option>');
											}
											return false;
										}
									});

									$("*[name='book_thumbnail[]']").each(function(idx3){
										if(idx == idx3){
											// サムネイルの初期化
											$(this).attr({ 
													src: "",
													alt: "[No Image]",
												});
											$(this).css("background-color", "white");

											$(this).attr({ 
												src: "<?= $this->config->item('stream_get_url')?>/school_<?= $this->session->userdata['cms_master.login.school_id'] ?>/book_library_"+select_id+"/Page1/master-Page1.jpg?token=<?= $this->session->userdata('session_id') ?>",
												alt: "",
											});

//											$(this).attr({
//												src: "<?= $this->config->item('stream_get_url')?>/school_<?= $this->session->userdata['cms_master.login.school_id'] ?>/book_library_"+select_id+"/Page1/master-Page1.jpg?token=12345678901234567890abcdefghijkl",
//												alt: "",
//											});


											$(this).css("background-color", "black");
											return false;
										}
									});

									$("*[name='exclusive_status[]']").each(function(idx4){
										if(idx == idx4){
											$(this).attr("checked", "checked");
											return false;
										}
									});
								}
							});
						}else{
							$("*[name='exclusive_book_library[]']").each(function(idx){

								// 親要素trのIDが同じグループを更新
								if( tr_id == $(this).parent().parent().attr("id") ){

									$("*[name='exclusive_page_number[]']").each(function(idx2){
										if(idx == idx2){
											// ページ数の初期化
											$(this).empty();
											$(this).append('<option selected="selected" value=-1>---</option>');
											return false;
										}
									});

									$("*[name='book_thumbnail[]']").each(function(idx3){
										if(idx == idx3){
											// サムネイルの初期化
											$(this).attr({ 
													src: "",
													alt: "[No Image]",
												});
											$(this).css("background-color", "white");
											return false;
										}
									});

									$("*[name='exclusive_status[]']").each(function(idx4){
										if(idx == idx4){
											$(this).attr("checked", "checked");
											return false;
										}
									});
								}
							});
						}
					}
				});
			});

			// 〓チャプター【追加】ボタン押下
			$("#btn_set_chapter").click(function() {
				// チャプター時間の取得
				var chapter_hour   = $("#chapter_hour").val();
				var chapter_minute = $("#chapter_minute").val();
				var chapter_second = $("#chapter_second").val();
				
				//チャプター名の取得（前後の全角半角空白の削除）
				var chapter_name = $("#chapter_name").val();
				chapter_name = chapter_name.replace(/(^[\s　]+)|([\s　]+$)/g, "");
				
				// 新しいチャプターのフォーマット
				var select_chapter_time = ""+ZeroFormat(chapter_hour,2)+":"+ZeroFormat(chapter_minute,2)+":"+ZeroFormat(chapter_second,2);
				var select_chapter_msg  = ""+select_chapter_time+"　"+chapter_name;
				
				// 新しいチャプター時間の秒変換
				var set_time_second = (chapter_hour * 60 * 60) + (chapter_minute * 60) + (chapter_second * 1);
				
				// ビデオ時間の秒変換
				var video_time        = $("[name=video_time]").val();
				var video_time_array  = video_time.split(":");
				var video_time_second = (video_time_array[0] * 60 * 60) + (video_time_array[1] * 60) + (video_time_array[2] * 1);
				
				// ビデオ時間を越えるチャプターは登録できない
				if( video_time_second < set_time_second ){
					alert("<?= $this->lang->line_or_def('error_video_chapter_time_over', '再生時間を越える時間は指定できません'); ?>");
					return false;
				}
				
				
				// 登録済みチャプターの取得
				var chapter_array = new Array();
				var update_flag   = false;
				var i             = 0;
				
				// 現在のチャプターを取得（値なしは取得しない）
				$('#chapter_list option').each(function() {
					var check_val = $(this).val();
					if(check_val.length > 0){
						chapter_array[i]    = new Array();
						chapter_array[i][0] = $(this).text();		// text値（画面に表示されている値）
						chapter_array[i][1] = $(this).val();		// value値
						
						// 同じチャプター時間がある場合、チャプター名を上書き
						if(chapter_array[i][1] == select_chapter_time){
							update_flag = true;
							chapter_array[i][0] = select_chapter_msg;
						}
						i = i + 1;
					}
				});
				
				// 同じチャプター時間がない場合、新規登録
				if(!update_flag){
					chapter_array[i] = new Array();
					chapter_array[i][0] = select_chapter_msg;
					chapter_array[i][1] = select_chapter_time;
				}
				
				// 設定チャプターの時間昇順処理
				var index = 0;
				chapter_array.sort(
					function(a, b){
						var x = a[index];
						var y = b[index];
						if (x > y) return 1;
						if (x < y) return -1;
					}
				);
				
				// 設定チャプターの初期化と再設置												<option class="colors" value="">　</option>-->

				$('#chapter_list').children().remove();
				for (var i=0;i<chapter_array.length;i++) {
					if( (i % 2) == 0 ) {
						$('#chapter_list').append('<option class="colors" value="'+chapter_array[i][1]+'">'+chapter_array[i][0]+'</option>');
					}else{
						$('#chapter_list').append('<option value="'+chapter_array[i][1]+'">'+chapter_array[i][0]+'</option>');
					}
				}
				for (var j=chapter_array.length;j<10;j++){
				//	var set_style = style;
					if( (j % 2) == 1 ) {
						$('#chapter_list').append('<option value="">　</option>');
					}else{
						$('#chapter_list').append('<option class="colors" value="">　</option>');
					}
				}
				
				// チャプター時間・チャプター名のリセット
				$("#chapter_hour").val("00");
				$("#chapter_minute").val("00");
				$("#chapter_second").val("00");
				$("#chapter_name").val("");
				
			  //var select_val  = $("#chapter_list").val();						// 選択されたoptionのvalue値
			  //var select_text = $("#chapter_list").find(':selected').text();	// 選択されたoptionのtext値（画面上に表示される値）
			});


			// 〓チャプター【選択したチャプターを削除】ボタン押下
			$("#btn_delete_chapter").click(function() {
				var select_val  = $("#chapter_list").val();
				if(select_val){
					// 選択したものを削除
					$('#chapter_list').find(':selected').remove();

					// 値を取得
					var chapter_array = new Array();
					var i             = 0;
					$('#chapter_list option').each(function() {
						var check_val = $(this).val();
						if(check_val.length > 0){
							chapter_array[i]    = new Array();
							chapter_array[i][0] = $(this).text();		// text値（画面に表示されている値）
							chapter_array[i][1] = $(this).val();		// value値
							i = i + 1;
						}
					});

					$('#chapter_list').children().remove();
					for (var i=0;i<chapter_array.length;i++) {
						if( (i % 2) == 0 ) {
							$('#chapter_list').append('<option class="colors" value="'+chapter_array[i][1]+'">'+chapter_array[i][0]+'</option>');
						}else{
							$('#chapter_list').append('<option value="'+chapter_array[i][1]+'">'+chapter_array[i][0]+'</option>');
						}
					}
					for (var j=chapter_array.length;j<10;j++){
					//	var set_style = style;
						if( (j % 2) == 1 ) {
							$('#chapter_list').append('<option value="">　</option>');
						}else{
							$('#chapter_list').append('<option class="colors" value="">　</option>');
						}
					}


				}else{
				  //alert("選択無し");
					return false;
				}
			});


			// 〓ＯＫボタン押下時（submit実行前処理）
			$('form').submit(function(){
				$('form').serialize();
				
				$('#chapter_list option').each(function() {
					$('<input />').attr('type', 'hidden')
								  .attr('name', 'chapter_list[]')
								  .attr('value', $(this).text())
								  .appendTo('form');
				});
			});

		});

		// [2012/12/07]所属講座切り替え時、図書室名コンボボックスの内容変更
		//   起動時間 => 画面表示時(1)、講座切り替え時(0)
		//   画面表示時、編集画面の場合、前情報を表示する必要がある
		function get_book_library_list() {
			
			var AllVals = new Array;
			$('.cource_list input:checked').map(function() {
				AllVals.push($(this).val());
			});
			
			$.ajax({
				url: "/cms_video/get_cource_book_library_exclusive",
				type: "POST",
				data: "cource_id="+AllVals.join(","),

				success: function(response) {
					$("*[name='exclusive_book_library[]'] option").remove();
					$("*[name='exclusive_book_library[]']").append('<option selected="selected" value="-1">----</option>');

					if(response){
						// 取得したデータを行に入れる
						for (var i=0; i< response.length; i++) {
							$("*[name='exclusive_book_library[]']").append(
								'<option value='+response[i]['book_library_id']+'>'+response[i]['book_library_logic_name']+'</option>');
						}
					}

					// ページ数の初期化
					$("*[name='exclusive_page_number[]'] option").remove();
					$("*[name='exclusive_page_number[]']").append('<option selected="selected" value=-1>---</option>');

					// サムネイルの初期化
					$("*[name='book_thumbnail[]']").attr({ 
							src: "",
							alt: "[No Image]",
						});
					$("*[name='book_thumbnail[]']").css("background-color", "white");

					// 有効フラグの初期化
					$("*[name='exclusive_status[]']").attr("checked", "checked");
				}
			});
		}

		function checkradio(disp) {
			document.getElementById("select_admin_checkbox").style.display = disp;
		}

		function select_all(){
			if($('.cource_list input:checked').length){
				$('.cource_list input').removeAttr('checked');
				$("#select_all").css("background-position", "center top");
			}
			else{
				$('.cource_list input').attr('checked','checked');
				$("#select_all").css("background-position", "center bottom");
			}
			get_book_library_list();	// 〓
			
			return false;
		}

		function set_tag(cap){
			var now_tags       = $('#tags').val();
			var now_tags_array = now_tags.split(",");
			
			if(now_tags.length==0){
				$('#tags').val(cap);
			}else{
				var buffer    = [];
				var _del_flag = 0;
				
				for(var i = 0; now_tags_array.length > i; i++){
					if(now_tags_array[i] != cap){
						buffer.push(now_tags_array[i]);
					}
					else{
						_del_flag = 1;
					}
				}
				if(!_del_flag){
					buffer.push(cap);
				}
				$('#tags').val(buffer.join(','));
			}
		}

		// 〓前ゼロ付き数字の作成
		function ZeroFormat(num,max){
			var tmp=""+num;
			while(tmp.length<max){
				tmp="0"+tmp;
			}
			return tmp;
		}

		// 〓テキストボックス上でのEnterキーで選択ボタン押下
		function submitStop_chapter(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_set_chapter').click();
				return false;
			}
		}

	// --></script> 
	
	<style type="text/css">
		TEXTAREA{
			width	: 100%;
			height	: 200px;
		}
		.auth_list DIV{
			line-height	: 21px;
		}
		
		#chapter_list option{
			padding: 2px 5px;
		}
		#chapter_list .colors{
			background:none repeat scroll 0 0 #E9E9E9;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_video','ビデオ授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_video_comment','ビデオ授業用のビデオファイルを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_video/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_video/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_video/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_video_input','ビデオファイルの内容を入力してください') ?></h2>

				<?=form_open_multipart("cms_video/commit")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<?=(isset($overlap_error)?'<div class="error">'.htmlspecialchars($overlap_error, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $video['update_flg'])?>'>
					<input type=hidden name=video_id value='<?=set_value('video_id', $video['video_id'])?>'>
					<input type=hidden name=video_logic_name value='<?=set_value('video_logic_name', $video['video_logic_name'])?>'>
					<input type=hidden name=idkey value='<?=set_value('idkey', $video['idkey'])?>'>

					<input type=hidden name=video_time value='<?=set_value('video_time', $video['video_time'])?>'>
					<input type=hidden name=max_hour   value='<?=set_value('max_hour',   $video['max_hour'])?>'>
					<input type=hidden name=max_minute value='<?=set_value('max_minute', $video['max_minute'])?>'>
					<input type=hidden name=max_second value='<?=set_value('max_second', $video['max_second'])?>'>

					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<td>
								<input type=text name="video_logic_name" maxlength="50" size="30" value='<?=set_value('video_logic_name',$video['video_logic_name'])?>'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->lang->line_or_def('msg_input_limit','※50文字まで指定') ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_file','ファイル') ?></th>
							<td>
								<?php if($video['update_flg']==0){ ?>
									<input type="file" name="local_file" size="30" value="<?=set_value('local_file',$video['local_file'])?>">
								<?php } else { ?>
									<?= $this->lang->line_or_def('common_no_change','変更できません') ?>
									<input type=hidden name=local_file value='temp_local_file'>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_sound_only','通常・音声のみ') ?></th>
							<td>
								<?php if( $video['sound_only']=="0" ){ ?>通常<?php } ?>
								<?php if( $video['sound_only']=="1" ){ ?>音声のみ<?php } ?>
								<input type=hidden name=sound_only value='<?=set_value('sound_only', $video['sound_only'])?>'>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_video_popup','確認ポップアップ') ?></th>
							<td>
								<select name="video_popup" id="video_popup" >
									<option value="0" <?php if( $video['video_popup']=="0" ){ ?> selected<?php } ?> >なし</option>
									<option value="1" <?php if( $video['video_popup']=="1" ){ ?> selected<?php } ?> >あり</option>
								</select>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_type_x15','1.5倍速') ?></th>
							<td>
								<input type="checkbox" name="type_x15" id="type_x15" value="1" <?php if( $video['type_x15']=="1" ){print('checked="checked"');} ?> >
							</td>
						</tr>

						<? // 所属講座 は固定 ?>
						<input type="hidden" name="video_lectures[]" value='<?= $this->config->item('nichibenren_cource_id') ?>'>
<!--					<tr>
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
												<input type="checkbox" name="video_lectures[]" id="lectures_<?=$cource['cource_id']?>" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($video['video_lectures']) ) {
														foreach( $video['video_lectures'] as $lecture) { 
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
 -->

						<? // 説明 は使用しない ?>
						<input type="hidden" name="video_caption" value='<?=set_value('video_caption', $video['video_caption'])?>'>
<!--
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="video_caption"><?=set_value('video_caption',$video['video_caption'])?></textarea>
							</td>
						</tr>
 -->
						<tr>
							<th><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td>
								<input type=text name="video_tags" maxlength="256" size="50" value='<?=set_value('video_tags',$video['video_tags'])?>'  id="tags">
								<?php if($tags_dropdown): ?>
									<?php $count = 0; ?>
									<?php foreach($tags_dropdown as $tagKey => $cnt): ?>
										<? if(($tagKey !== 'タグなし') && ($tagKey !== '') ){
											$count = $count + 1;
										} ?>
									<?php endforeach; ?>
									<?php if($count > 0): ?>
										<div style=" margin-top: 5px;line-height:20px;"><?= $this->lang->line_or_def('common_registered_tag','登録済みタグ') ?>&nbsp;:&nbsp;
											<?php foreach($tags_dropdown as $tagKey => $cnt) { ?>
												<? if(($tagKey !== 'タグなし') && ($tagKey !== '') ): ?>
													<a href="#" onclick="set_tag('<?= htmlspecialchars( $tagKey, ENT_QUOTES, 'UTF-8') ?>');return false;"><?= htmlspecialchars( $tagKey, ENT_QUOTES, 'UTF-8') ?></a>&nbsp;&nbsp;
												<? endif; ?>
											<?php } ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<div style="padding-top: 5px;"><?= $this->lang->line_or_def('msg_video_tag_input_limit','※カンマ含む256文字まで設定可能　入力例：記入例：数学 / 科学,科学 / 歴史,日本史,弥生時代') ?></div>
							</td>
						</tr>


						<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_exclusive_tag','専属タグ') ?></th>
								<td>
									<table style="width: 100%;"> <!-- width: 625px; -->
										<tr>
											<th><?= $this->lang->line_or_def('common_exclusive_tag','専属タグ') ?></th>
											<th><?= $this->lang->line_or_def('common_book_library_name','図書室名') ?></th>
											<th><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
											<th><?= $this->lang->line_or_def('common_page','ページ') ?></th>
											<th><?= $this->lang->line_or_def('common_effectively','有効') ?></th>
										</tr>
										

										<?php foreach($video['exclusive_tag'] as $ino => $exclusive_tag): ?>
											<tr sytle="height:100px;" id="exclusive_<?= $ino; ?>">
												<td style="vertical-align: middle;text-align: center;">
													<input type=text name="exclusive_tag[]" maxlength="256" size="15" value='<?= htmlspecialchars( $exclusive_tag, ENT_QUOTES, 'UTF-8') ?>'><!--   -->
												</td>
												
												<td style="vertical-align: middle;text-align: center;">
													<?=form_dropdown('exclusive_book_library[]',$book_library_exclusive, set_value('exclusive_book_library[]', $video['exclusive_book_library'][$ino]), 'name="exclusive_book_library[]" style="width: 190px;"'); ?>
												</td>

												<td style="vertical-align: middle;text-align: center;width: 100px;">
													<?php if($video['exclusive_book_library'][$ino] < 1): ?>
														<img src="" alt="[No Image]" style="height: 64px;width: 100px; padding:1px;background-color:white;"/ name="book_thumbnail[]">
													<?php else: ?>
													<img src="<?= $this->config->item('stream_get_url')?>/school_<?= $this->session->userdata['cms_master.login.school_id'] ?>/book_library_<?= $video['exclusive_book_library'][$ino]; ?>/Page1/master-Page1.jpg?token=<?= $this->session->userdata('session_id') ?>" alt="" style="height: 64px; padding:1px;background-color:black;"/ name="book_thumbnail[]">
							<!--
													<img src="<?= $this->config->item('stream_get_url')?>/school_<?= $this->session->userdata['cms_master.login.school_id'] ?>/book_library_<?= $video['exclusive_book_library'][$ino]; ?>/Page1/master-Page1.jpg?token=12345678901234567890abcdefghijkl" alt="" style="height: 64px; padding:1px;background-color:black;"/ name="book_thumbnail[]">
							-->
													<?php endif; ?>
												</td>
												
												<td style="vertical-align: middle;text-align: center;">
													<select name="exclusive_page_number[]" style="width: 50px;"><!--  -->

													<?php if($video['exclusive_page_number'][$ino] < 0): ?>
														<option selected="selected" value=-1>---</option>
													<?php else: ?>
														<option value=-1>---</option>

														<?php $book_max_num = $book_library_exclusive_num[$video['exclusive_book_library'][$ino]]; ?>
														<?php for($i=0; $i<=$book_max_num; $i++): ?>
															<?php if($i == $video['exclusive_page_number'][$ino]): ?>
																<option selected="selected" value=<?= $i; ?>><?= $i; ?></option>
															<?php else: ?>
																<option value=<?= $i; ?>><?= $i; ?></option>
															<?php endif; ?>
														<?php endfor; ?>



													<?php endif; ?>
													</select>
												</td>

												<td style="vertical-align: middle;text-align: center;">
													<?php if($video['exclusive_status'][$ino] == 0): ?>
														<input type="checkbox" value="<?= $ino; ?>" name="exclusive_status[]" checked>
													<?php else: ?>
														<input type="checkbox" value="<?= $ino; ?>" name="exclusive_status[]" >
													<?php endif; ?>
												</td>

											</tr>
										<?php endforeach; ?>
									</table>
								</td>
							</tr>
						<?php endif; ?>

						<? // iPadのローカル閲覧 は使用しない ?>
						<input type="hidden" name="local_reading_flag"       value='<?=set_value('local_reading_flag',       $video['local_reading_flag'])?>'>
						<input type="hidden" name="local_reading_open"       value='<?=set_value('local_reading_open',       $video['local_reading_open'])?>'>
						<input type="hidden" name="local_reading_close"      value='<?=set_value('local_reading_close',      $video['local_reading_close'])?>'>
						<input type="hidden" name="local_reading_close_flag" value='<?=set_value('local_reading_close_flag', $video['local_reading_close_flag'])?>'>

<!--
						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧') ?></th>
							<td class="auth_list">
								<div>
									<input type="radio" name="local_reading_flag" id="local_reading_flag_off" value='0' onclick="checkradio('none')" 
										<?=(set_value('local_reading_flag',$video['local_reading_flag'])=='0')?'checked':''?> >
									<label for="local_reading_flag_off"><?= $this->lang->line_or_def('common_forbid','禁止') ?></label>
								</div>
								
								<div>
									<input type="radio" name="local_reading_flag" id="local_reading_flag_on" value='1' onclick="checkradio('block')" 
										<?=(set_value('local_reading_flag',$video['local_reading_flag'])=='1')?'checked':''?> >
									<label for="local_reading_flag_on"><?= $this->lang->line_or_def('common_admit','許可') ?></label>
								</div>
								
								<div id="select_admin_checkbox" <?=(set_value('local_reading_flag',$video['local_reading_flag'])=='0')?'style="display:none;"':''?> > 
									<div style="padding-left: 40px;"><?= $this->lang->line_or_def('common_local_reading_period','閲覧可能期間') ?></div>
									<input type="text" name="local_reading_open" size="19" value="<?=set_value('local_reading_open',$video['local_reading_open'])?>" 
										id="local_reading_open" style="margin-left: 40px;" readonly>
									<?= $this->lang->line_or_def('common_range','～') ?>
									<input type="text" name="local_reading_close" size="19" value="<?=set_value('local_reading_close',$video['local_reading_close'])?>" 
										id="local_reading_close" readonly>
									
									<div style="margin-top: 5px; margin-bottom: 5px; margin-left: 40px;">
										<input type="checkbox" name="local_reading_close_flag" id="local_reading_close_flag" 
											value='1' <?=(set_value('local_reading_close_flag',$video['local_reading_close_flag'])=='1')?'checked':''?> >
										<label for="local_reading_close_flag">&nbsp;<?= $this->lang->line_or_def('msg_local_reading_end_check','※ローカル視聴期限を無しにする場合は、チェックをいれる。') ?></label>
									</div>
								</div>
							</td>
						</tr>
 -->

<!--
						<tr><? // chapter ?>
							<th style="vertical-align:top;">
								<?= $this->lang->line_or_def('common_chapter','チャプター') ?>
							</th>
							<td>
								<div style="margin-bottom: 5px;">
									<div style="float:left;line-height : 28px;width: 80px;">
										<?= $this->lang->line_or_def('common_time_required','時間（必須）') ?>
									</div>
								
									<div style="float:left;line-height : 28px;width: 80px;">
										<select style="width: 50px;" id="chapter_hour">
											<?php for($temp = 0; $temp <= intval($video['max_hour']); $temp++): ?>
												<option value="<?= sprintf('%02d', $temp); ?>"><?= sprintf('%02d', $temp); ?></option>
											<?php endfor; ?>
										</select>
										<?= $this->lang->line_or_def('common_js_hourText','時') ?>
									</div>
									<div style="float:left;line-height : 28px;width: 80px;">
										<select style="width: 50px;" id="chapter_minute">
											<?php for($temp = 0; $temp <= intval($video['max_minute']); $temp++): ?>
												<option value="<?= sprintf('%02d', $temp); ?>"><?= sprintf('%02d', $temp); ?></option>
											<?php endfor; ?>
										</select>
										<?= $this->lang->line_or_def('common_js_minuteText','分') ?>
									</div>
									<div style="float:left;line-height : 28px;width: 80px;">
										<select style="width: 50px;" id="chapter_second">
											<?php for($temp = 0; $temp <= intval($video['max_second']); $temp++): ?>
												<option value="<?= sprintf('%02d', $temp); ?>"><?= sprintf('%02d', $temp); ?></option>
											<?php endfor; ?>
										</select>
										<?= $this->lang->line_or_def('common_js_secondText','秒') ?>
									</div>
									<div style="float:left;line-height : 28px;width: 40px;">
										<input type="button" id="btn_set_chapter" value=<?= $this->lang->line_or_def('common_addition','追加') ?> style="height: 28px;" />
									</div>
									<div style="float:left;line-height : 28px;margin-left: 50px;">
										<?= $this->lang->line_or_def('common_reproduction_time','再生時間') ?> / <?= $video['video_time']; ?>
									</div>
									<div style="clear : both"></div>
								</div>
								<div style="margin-bottom: 5px;">
									<div style="float:left;line-height : 22px;width: 80px;">
										<?= $this->lang->line_or_def('common_name_other','名称') ?>
									</div>
									<div style="float:left;">
										<input type="text" id="chapter_name" size="24" maxlength="32" value="" onKeyPress="return submitStop_chapter(event);" />&nbsp;&nbsp;&nbsp;<?= $this->lang->line_or_def('msg_video_chapter_name_input_limit','※32文字まで指定') ?>
									</div>
									<div style="float:left;line-height : 23px;margin-left: 15px;">
										
									</div>

									<div style="clear : both"></div>
								</div>
								<div>
									<div style="float:left;">
										<select size="10" style="height: 200px;width: 400px;" id="chapter_list">
										<?php $counter = 0; ?>
										<?php $style = 'style="background:none repeat scroll 0 0 #E9E9E9; padding: 2px 5px;"'; ?>
										<?php foreach($chapter_data as $chapter): ?>
											<?php $counter += 1; ?>
											<?php $chapter_text = $chapter['chapter_time']."　".$chapter['chapter_name']; ?>
											<?php if($counter % 2 == 0): ?>
												<option value="<?= $chapter['chapter_time']; ?>"><?= $chapter_text; ?></option>
											<?php else: ?>
												<option class="colors" value="<?= $chapter['chapter_time']; ?>"><?= $chapter_text; ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
										<?php for ($i = $counter; $i < 10; $i++): ?>
											<?php $counter += 1; ?>
											<?php if($counter % 2 == 0): ?>
												<option value="">　</option>
											<?php else: ?>
												<option class="colors" value="">　</option>
											<?php endif; ?>
										<?php endfor; ?>
										</select>
									</div>
									<div style="float: left; margin-left: 10px;">
										<input type="button" id="btn_delete_chapter" value=<?= $this->lang->line_or_def('common_delete_selected_chapter','選択したチャプターを削除') ?> />
									</div>
									<div style="clear : both"></div>
								</div>
							</td>
						</tr>
 -->
						
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
