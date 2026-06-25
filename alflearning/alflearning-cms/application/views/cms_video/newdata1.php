<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "video";
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
		
		//------------------------------------------
		// iPadのローカル閲覧 ラジオボタン押下
		//------------------------------------------
		function checkradio(disp) {
			document.getElementById("select_admin_checkbox").style.display = disp;
		}

		//------------------------------------------
		// 全て選択・全て解除ボタン押下
		//------------------------------------------
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

		//------------------------------------------
		// 登録済みタグリンク押下
		//------------------------------------------
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

		//------------------------------------------
		// [2012/11/20]削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_video/delete_item/" + id ;
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

				<?=form_open_multipart("cms_video/new_table")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.$upload_error.'</div>':'')?>
					<?=(isset($overlap_error)?'<div class="error">'.$overlap_error.'</div>':'')?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $video['update_flg'])?>'>
					<input type=hidden name=video_id value='<?=set_value('video_id', $video['video_id'])?>'>
				<!--<input type=hidden name=video_logic_name value='<?=set_value('video_logic_name', $video['video_logic_name'])?>'>-->
					<input type=hidden name=idkey value='<?=set_value('idkey', $video['idkey'])?>'>
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
								<?= $this->lang->line_or_def('common_choice_next_page','次頁で選択') ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_sound_only','通常・音声のみ') ?></th>
							<td>
								<select name="sound_only" id="sound_only" >
									<option value="0" <?php if( $video['sound_only']=="0" ){ ?> selected<?php } ?> >通常</option>
									<option value="1" <?php if( $video['sound_only']=="1" ){ ?> selected<?php } ?> >音声のみ</option>
								</select>
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
					<!--
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
												<input type="checkbox" checked="checked" name="video_lectures[]" id="lectures_<?=$cource['cource_id']?>" value=<?=$cource['cource_id']?>
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
 -->
<!--							<td>
								<div class="cource_list">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<div class="cources">
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
												</div>
										<?php 
										}
									}
									?>
								</div>
							</td>	-->
<!--
						</tr>
 -->

						<? // 説明 は使用しない ?>
						<input type="hidden" name="video_caption" value='<?=set_value('video_caption', $video['video_caption'])?>'>
<!--
						<tr>
							<th><?='';//$this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="video_caption"><?='';//set_value('video_caption',$video['video_caption'])?></textarea>
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

					</table>
					<div class="submit">
						<?php if($video['video_id']>0): ?>
							<input type="image" src="/static/image/btn_back.png" onClick='location.href = "<?= site_url('cms_video/'); ?>";return false;' />
						<?php endif; ?>
						<input type='image' src='/static/image/btn_next.png' />
						<?php if($video['video_id']>0): ?>
							<input type="image" src="/static/image/btn_delete.png" 
							onClick='delete_item(<?= $video['video_id'] ?>,"<?= $this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？') ?>");return false;' />
						<?php endif; ?>
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
