<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "book_library";
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
			});
		});

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
		
	// --></script> 

	<style type="text/css">
		TEXTAREA{
			width	: 100%;
			height	: 200px;
		}
		.auth_list DIV{
			line-height	: 21px;
		}
		#tags{
			width		: 100%;
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
			<div class="title"><?= $this->lang->line_or_def('common_heading_book_library','図書室管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_book_library_comment','図書室内の資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_book_library/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_book_library/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_book_library/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_book_library_input','登録する本の情報をを入力してください') ?></h2>

				<?=form_open_multipart("cms_book_library/commit")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.$upload_error.'</div>':'')?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $book_library['update_flg'])?>'>
					<input type=hidden name=book_library_id value='<?=set_value('book_library_id', $book_library['book_library_id'])?>'>
					<input type=hidden name=book_library_logic_name value='<?=set_value('book_library_logic_name', $book_library['book_library_logic_name'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<td>
								<input type=text name="book_library_logic_name" maxlength="50" size="30" value='<?=set_value('book_library_logic_name',$book_library['book_library_logic_name'])?>'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->lang->line_or_def('msg_input_limit','※50文字まで指定') ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_file','ファイル') ?></th>
							<td>
								<?php if($book_library['update_flg']==0){ ?>
									<input type="file" name="local_file" size="30" value="<?=set_value('local_file',$book_library['local_file'])?>">
									&nbsp;&nbsp;<a href="/static/html/supported_formats.html" onclick="window.open(this.href, 'mywindow6', 'width=400, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;">※対応ファイルについて</a>
									<div class="pdf_message">アップロードするファイルがPDFファイルの場合、PDF作成ツールによってはフォントが対応していない場合があります。<br />その場合は文字化け等の問題が発生する場合がありますので、PowerPoint等の変換元データの使用を推奨致します</div>
								<?php } else { ?>
									<?= $this->lang->line_or_def('common_no_change','変更できません') ?>
									<input type=hidden name=local_file value='temp_local_file'>
								<?php } ?>
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
												<input type="checkbox" name="book_library_lectures[]" id="lectures_<?=$cource['cource_id']?>" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($book_library['book_library_lectures']) ) {
														foreach( $book_library['book_library_lectures'] as $lecture) { 
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

<!--						<td>
								<div class="cource_list">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<div class="cources">
												<input type="checkbox" name="book_library_lectures[]" id="lectures_<?=$cource['cource_id']?>" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($book_library['book_library_lectures']) ) {
														foreach( $book_library['book_library_lectures'] as $lecture) { 
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
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="book_library_caption" ><?=set_value('book_library_caption',$book_library['book_library_caption'])?></textarea>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td>
								<input type=text name="book_library_tags" maxlength="256" value='<?=set_value('book_library_tags',$book_library['book_library_tags'])?>' id="tags">
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
													<a href="#" onclick="set_tag('<?= $tagKey; ?>');return false;"><?= $tagKey; ?></a>&nbsp;&nbsp;
												<? endif; ?>
											<?php } ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<div style="padding-top: 5px;"><?= $this->lang->line_or_def('msg_video_tag_input_limit','※カンマ含む256文字まで設定可能　入力例：記入例：数学 / 科学,科学 / 歴史,日本史,弥生時代') ?></div>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧') ?></th>
							<td class="auth_list">
								<div>
									<input type="radio" name="local_reading_flag" id="local_reading_flag_off" value='0' onclick="checkradio('none')" 
										<?=(set_value('local_reading_flag',$book_library['local_reading_flag'])=='0')?'checked':''?> >
									<label for="local_reading_flag_off"><?= $this->lang->line_or_def('common_forbid','禁止') ?></label>
								</div>
								
								<div>
									<input type="radio" name="local_reading_flag" id="local_reading_flag_on" value='1' onclick="checkradio('block')" 
										<?=(set_value('local_reading_flag',$book_library['local_reading_flag'])=='1')?'checked':''?> >
									<label for="local_reading_flag_on"><?= $this->lang->line_or_def('common_admit','許可') ?></label>
								</div>
								
								<div id="select_admin_checkbox" <?=(set_value('local_reading_flag',$book_library['local_reading_flag'])=='0')?'style="display:none;"':''?> > 
									<div style="padding-left: 40px;"><?= $this->lang->line_or_def('common_local_reading_period','閲覧可能期間') ?></div>
									<input type="text" name="local_reading_open" size="19" value="<?=set_value('local_reading_open',$book_library['local_reading_open'])?>" 
										id="local_reading_open" style="margin-left: 40px;" readonly>
									<?= $this->lang->line_or_def('common_range','～') ?>
									<input type="text" name="local_reading_close" size="19" value="<?=set_value('local_reading_close',$book_library['local_reading_close'])?>" 
										id="local_reading_close" readonly>
									
									<div style="margin-top: 5px; margin-bottom: 5px; margin-left: 40px;">
										<input type="checkbox" name="local_reading_close_flag" id="local_reading_close_flag" 
											value='1' <?=(set_value('local_reading_close_flag',$book_library['local_reading_close_flag'])=='1')?'checked':''?> >
										<label for="local_reading_close_flag">&nbsp;<?= $this->lang->line_or_def('msg_local_reading_end_check','※ローカル視聴期限を無しにする場合は、チェックをいれる。') ?></label>
									</div>
								</div>
							</td>
						</tr>

						<?php if($book_library['update_flg']!=0){ ?>
						<tr>
							<th style="vertical-align: middle;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<td>
<img src="<?= $this->config->item('stream_get_url')?>/school_<?= $this->session->userdata['cms_master.login.school_id'] ?>/book_library_<?= $book_library['book_library_id']; ?>/Page1/master-Page1-thum.jpg?token=<?= $this->session->userdata('session_id') ?>" alt="" style="height: 200px; padding: 1px;background-color:black;"/>
							</td>
						</tr>
						<?php } ?>
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
