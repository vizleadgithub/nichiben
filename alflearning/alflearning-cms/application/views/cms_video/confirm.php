<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "video";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_video/delete_item/" + id ;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_video/edit/";
		}
		
		//------------------------------------------
		//詳細確認画面　切り出しボタン押下
		//------------------------------------------
		function edit_moviecut(){
			location.href ="<?=base_url()?>cms_video/edit_moviecut/";
		}
	</script>
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

				<h2>
					<?php 
						if( $video['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_video_confirm','ビデオファイル情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_video_confirm','ビデオファイル情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_video_detail','ビデオファイル情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_video/commit")?>
					<table class="form">
						<tr>
							<th style="width:160px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<td>
								<?php if($video['video_logic_name'] == ""): ?>
									<?= htmlspecialchars( $video['video_name'], ENT_QUOTES, 'UTF-8') ?>
								<?php endif; ?>
								<?php if($video['video_logic_name'] != ""): ?>
									<?= htmlspecialchars( $video['video_logic_name'], ENT_QUOTES, 'UTF-8') ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_sound_only','通常・音声のみ') ?></th>
							<td>
								<?php if( $video['sound_only']=="0" ){ ?>通常<?php } ?>
								<?php if( $video['sound_only']=="1" ){ ?> 音声のみ<?php } ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_video_popup','確認ポップアップ') ?></th>
							<td>
								<?php if( $video['video_popup']=="0" ){ ?>なし<?php } ?>
								<?php if( $video['video_popup']=="1" ){ ?> あり<?php } ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_type_x15','1.5倍速') ?></th>
							<td>
								<?php if( $video['type_x15']=="0" ){ ?>通常<?php } ?>
								<?php if( $video['type_x15']=="1" ){ ?> 1.5倍速<?php } ?>
							</td>
						</tr>

<!--
						<tr>
							<th><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($video['video_lectures_name']) ) {
										foreach( $video['video_lectures_name'] as $name) { 
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

						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td ><?=$video['video_caption']?>
							</td>
						</tr>
 -->

						<tr>
							<th><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td ><?= htmlspecialchars( $video['video_tags'], ENT_QUOTES, 'UTF-8') ?></td>
						</tr>

						<?php if($this->Modelschoolcontract->enableService(array('serviceKey'=>'book_library'))): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_exclusive_tag','専属タグ') ?></th>
								<td >
									<?php if(!empty($video['exclusive_tag'][0])): ?>
										<table>
											<tr>
												<th><?= $this->lang->line_or_def('common_exclusive_tag','専属タグ') ?></th>
												<th><?= $this->lang->line_or_def('common_book_library_name','図書室名') ?></th>
												<th><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
												<th><?= $this->lang->line_or_def('common_page','ページ') ?></th>
												<th><?= $this->lang->line_or_def('common_effectively','有効') ?></th>
											</tr>
											<?php foreach($video['exclusive_tag'] as $ino => $exclusive_tag): ?>
												<?php if(!empty($exclusive_tag )): ?>
													<tr id="exclusive_<?= $ino; ?>">
														<td style="vertical-align: middle;width: 150px;"><?= htmlspecialchars( $exclusive_tag, ENT_QUOTES, 'UTF-8') ?></td>
														<td style="vertical-align: middle;width: 230px;">
															<?php foreach($book_library_exclusive as $ino2 => $temp2): ?>
																<?php if($ino2 == $video['exclusive_book_library'][$ino]): ?>
																	<?= htmlspecialchars( $temp2, ENT_QUOTES, 'UTF-8') ?>
																	<?php break; ?>
																<?php endif; ?>
															<?php endforeach; ?>
														</td>
														<td style="vertical-align: middle;text-align: center;">
															<?php 
																$temp3  = $this->config->item('stream_get_url');
																$temp3 .= '/school_'.$this->session->userdata['cms_master.login.school_id'];
																$temp3 .= '/book_library_'.$video['exclusive_book_library'][$ino];
																if($video['exclusive_page_number'][$ino]>0){
																	$temp3 .= '/Page'.$video['exclusive_page_number'][$ino].'/master-Page'.$video['exclusive_page_number'][$ino].'.jpg?token='.$this->session->userdata('session_id');
																}else{
																	$temp3 .= '/Page1/master-Page1.jpg?token='.$this->session->userdata('session_id');
																}
															?>
															<img src="<?= $temp3; ?>" alt="" style="height: 64px; padding:1px;background-color:black;"/ name="book_thumbnail[]">
														</td>
														<td style="vertical-align: middle;text-align: center;"><?= $video['exclusive_page_number'][$ino]; ?></td>
														<td style="vertical-align: middle;text-align: center;">
															<?php if($video['exclusive_status'][$ino] == 0): ?>
																<?= $this->lang->line_or_def('common_effectively','有効') ?>
															<?php else: ?>
																<?= $this->lang->line_or_def('common_invalidity','無効') ?>
															<?php endif; ?>
														</td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										</table>
									<?php endif; ?>
								</td>
							</tr>
						<?php endif; ?>

<!--
						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧') ?></th>
							<td >
								<?php if($video['local_reading_flag']=='0'): ?>
									<?= $this->lang->line_or_def('common_forbid','禁止') ?>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_admit','許可') ?>&nbsp;(<?=$video['local_reading_open']?>&nbsp;<?= $this->lang->line_or_def('common_range','～') ?>&nbsp;
									<?php if($video['local_reading_close']==''): ?>
										<?= $this->lang->line_or_def('common_no_limit','期限なし') ?>)
									<?php else: ?>
										<?=$video['local_reading_close']?>)
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
 -->						
						
						<?php if($btn_kirikae_flg === 2): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_history','閲覧履歴') ?></th>
							<td>
							<?php if(count($history_data)>0): ?>
								<table>
									<tr>
										<th width=" 50px"><?= $this->lang->line_or_def('common_id','ID') ?></th>
										<th width="250px"><?= $this->lang->line_or_def('common_student_name','受講者名') ?></th>
										<th width="100px"><?= $this->lang->line_or_def('common_local_reading_','閲覧率') ?></th>
										<th width="150px"><?= $this->lang->line_or_def('common_local_reading_date','閲覧日') ?></th>
									</tr>
								</table>
								<div style="overflow-y: scroll;width: 640px;height: 200px;">
								<table>
									<?php foreach($history_data as $history) { ?>
									<tr style="border: 1px #808080 solid; border-style: none none solid none ;	">
										<td width=" 50px"><?= $history['student_id'] ?></td>
										<td width="250px"><?= htmlspecialchars( $history['student_name'], ENT_QUOTES, 'UTF-8') ?></td>
										<td width="100px"><?= $history['percent'] ?>%</td>
										<td width="150px"><?= htmlspecialchars( $history['reading_date'], ENT_QUOTES, 'UTF-8') ?></td>
									</tr>
									<?php } ?>
								</table>
								</div>
							<?php else: ?>
								<?= $this->lang->line_or_def('common_no_local_reader','閲覧者なし') ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endif; ?>
						
<!--
						<?php if($btn_kirikae_flg === 2): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_chapter','チャプター') ?></th>
							<td>
							<?php if(count($chapter_data)>0): ?>
								<table>
									<tr>
										<th width="120px"><?= $this->lang->line_or_def('common_chapter_time','チャプター時間') ?></th>
										<th width="450px"><?= $this->lang->line_or_def('common_chapter_name','チャプター名称') ?></th>
									</tr>
								</table>
								<div style="overflow-y: scroll;width: 640px;height: 200px;">
									<table>
									<?php foreach($chapter_data as $chapter) { ?>
										<tr style="border: 1px #808080 solid; border-style: none none solid none ;	">
											<td width="120px"><?= $chapter['chapter_time']; ?></td>
											<td width="450px"><?= $chapter['chapter_name']; ?></td>
										</tr>
									<?php } ?>
									</table>
								</div>
							<?php else: ?>
								<?= $this->lang->line_or_def('common_no_chapter','チャプターなし') ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endif; ?>
 -->
						
					</table>
					<?php if($video['parent_video_id']>0):?>
					<div style="color: #00A4E2;text-align: center;">
						<?= $this->lang->line_or_def('msg_video_cannot_moviecut','切り出しにより作成したファイルのため、切り出しを行うことができません。<br/>元ファイルから切り出しを行ってください。'); ?>
					</div>
					<?php endif; ?>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$video['video_id'].");retufn false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
								  //print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_video/')."\";return false;' />";
								  //print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
								  //print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$video['video_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";

									print '<a href="#" onClick='."'".'location.href = "'.$video['history_back_url'].'";return false;'."'".' style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_gray.png) no-repeat;font-size:13px;line-height: 30px;">'.$this->lang->line_or_def('common_','一覧に戻る').'</a>';

								  //print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_video/')."\";return false;' style='display:inline-block;' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' style='display:inline-block;' />";
									
							  //	if($video['parent_video_id'] > 0){
							  //		print '<a href="/cms_video/detail/'.$video['parent_video_id'].'/" target="_blank" style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_blue.png) no-repeat;font-size:13px;line-height: 30px;">'.$this->lang->line_or_def('common_former_file','元ファイル').'</a> ';
							  //	}else{
							  //		print '<a href="#" onClick="edit_moviecut();return false;" style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_blue.png) no-repeat;font-size:13px;line-height: 30px;">'.$this->lang->line_or_def('common_beginning_to_cut','切り出し').'</a> ';
							  //	}

									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$video['video_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' style='display:inline-block;' />";
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
