<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "school_manage";
	$this->load->view('header/header',$data);?>

	<script type="text/javascript"><!--
		function live_contract_function() {
		//	nIndex = document.getElementsByName("live_contract")[0].selectedIndex;
			nValue = document.getElementsByName("live_contract")[0].value;
			
			if(nValue == "undefined"){
				$("#list_live").slideUp();
			//	$("*[name=list_live]").slideUp();
			}else{
				$("#list_live").slideDown();
			//	$("*[name=list_live]").slideDown();
			}
		}

		function video_contract_function() {
			nValue = document.getElementsByName("video_contract")[0].value;
			
			if(nValue == "undefined"){
				$("#list_video").slideUp();
			}else{
				$("#list_video").slideDown();
			}
		}

		function book_library_contract_function() {
			nValue = document.getElementsByName("book_library_contract")[0].value;
			
			if(nValue == "undefined"){
				$("#list_book_library").slideUp();
			}else{
				$("#list_book_library").slideDown();
			}
		}

	// --></script> 

<style type="text/css"><!--
	TEXTAREA{
		width : 100%;
		height : 70px;
	}
	TEXTAREA[name="teacher_introduce_detail"]{
		height : 200px;
	}
	
	.auth_list DIV{
		line-height	: 21px;
	}
// --></style>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_school_manage','学校管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_school_manage_comment','学校を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_school_manage/_submenu', array(
				'selected'	=> 'index',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_school_manage/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_school_manage/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_school_manage_input','学校の情報を入力してください') ?></h2>

				<?=form_open("cms_school_manage/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?= (isset($error_msg) && $error_msg ? '<div class="error">'.$error_msg.'</div>' : ''); ?>
					<input type="hidden" name="update_flg" value='<?=set_value('update_flg' ,$school['update_flg'])?>'>
					<input type="hidden" name="school_id" value='<?=set_value('school_id'  ,$school['school_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<td>
								<input type="text" name="school_name" size="48" value="<?=set_value('school_name',$school['school_name'])?>">
							</td>
						</tr>
						</tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td>
								<textarea name="school_caption"><?=set_value('school_caption',$school['school_caption'])?></textarea>
							</td>
						</tr>
						</tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td>
								<textarea name="school_note"><?=set_value('school_note',$school['school_note'])?></textarea>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
							<td >
							<!--<input type="text" name="contract" size="48" value="<?=set_value('contract',$school['contract'])?>">-->
							
								<?=form_dropdown('contract', $school_contract, set_value('contract', $school['contract']));?>

							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_contents','契約内容') ?></th>
							<td >
								<input type="hidden" name="contract_param" value='<?=set_value('contract_param'  ,$school['contract_param'])?>'>
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_class','授業') ?></th><tr>
								<tr>
									<th style="width:142px;">&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php $check_env = getenv('URL_SERVICE'); ?>
										<?php if( $check_env == 'alfsales' ): ?>
											<?php $js = 'id="live_contract" onChange="live_contract_function();" disabled="disabled"'; ?>
											<?=form_dropdown('live_contract_2', 
												$contract_dropdown, set_value('live_contract_2', 'undefined'), $js);?>
											<input type="hidden" name="live_contract" value='undefined'>
										<?php else: ?>
											<?php $js = 'id="live_contract" onChange="live_contract_function();"'; ?>
											<?=form_dropdown('live_contract', 
												$contract_dropdown, set_value('live_contract', 
													$school['contract_param_live']['contract']), $js);?>
										<?php endif; ?>
									</td>
								</tr>
								</table>
								
								<div id="list_live" <?=($school['contract_param_live']['contract']=='undefined')?'style="display:none;"':''?> >
									<table>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_status','状態') ?></th>
										<td colspan=3>
											<?php if(isset($school['contract_param_live']['stat']) && $school['contract_param_live']['stat'] == 1){ ?>
												<?= $this->lang->line_or_def('common_contract_range','契約範囲内') ?>
											<?php }elseif(isset($school['contract_param_live']['stat']) && $school['contract_param_live']['stat'] == 0){ ?>
												<?= $this->lang->line_or_def('common_contract_range_excess','契約範囲超過') ?>
											<?php }else{ ?>
												------
											<?php } ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_class_time','契約授業時間') ?></th>
										<td>
											<input type="text" name="live_time" style="width: 50px;" value="<?=set_value('live_time',
												$school['contract_param_live']['time_convert'])?>"><?= $this->lang->line_or_def('common_time','時間') ?>
										</td>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_class_time_now','現在の授業時間') ?></th>
										<td>
											<?= (isset($school['contract_param_live']['time_now']) ? Sec2Disp($school['contract_param_live']['time_now'], array('dd' => false, 'mm' => false, 'ss' => false)) : '-'); ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') ?></th>
										<td>
											<input type="text" name="live_strage" style="width: 50px;" value="<?=set_value('live_strage',
												$school['contract_param_live']['strage_convert'])?>">G
										</td>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_storage_now','現在のストレージ使用量') ?></th>
										<td>
											<?= (isset($school['contract_param_live']['strage_now']) ? ConvertUnit($school['contract_param_live']['strage_now'],2) : '-'); ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_consumption_datetime','使用量取得日時') ?></th>
										<td colspan=3>
											<?= (isset($school['contract_param_live']['updated_at']) ? date('Y-m-d H:i:s',$school['contract_param_live']['updated_at']) : '-'); ?>
										</td>
									</tr>
									</table>
								</div>
								
								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_video','ビデオ') ?></th><tr>
								<tr>
									<th style="width:142px;">&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php $js = 'id="video_contract" onChange="video_contract_function();"'; ?>
										<?=form_dropdown('video_contract', 
											$contract_dropdown, set_value('video_contract', 
												$school['contract_param_video']['contract']), $js);?>
									</td>
								</tr>
								</table>
								
								<div id="list_video" <?=($school['contract_param_video']['contract']=='undefined')?'style="display:none;"':''?> >
									<table>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_status','状態') ?></th>
										<td colspan=3>
											<?php if(isset($school['contract_param_video']['stat']) && $school['contract_param_video']['stat'] == 1){ ?>
												<?= $this->lang->line_or_def('common_contract_range','契約範囲内') ?>
											<?php }elseif(isset($school['contract_param_video']['stat']) && $school['contract_param_live']['stat'] == 0){ ?>
												<?= $this->lang->line_or_def('common_contract_range_excess','契約範囲超過') ?>
											<?php }else{ ?>
												------
											<?php } ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_transfer','契約転送量') ?></th>
										<td>
											<input type="text" name="video_stream" style="width: 50px;" value="<?=set_value('video_stream',
												$school['contract_param_video']['stream_convert'])?>">G
										</td>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_transfer_now','現在の転送量') ?></th>
										<td>
											<?= (isset($school['contract_param_video']['stream_now']) ? ConvertUnit($school['contract_param_video']['stream_now'],2) : '-'); ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') ?></th>
										<td>
											<input type="text" name="video_strage" style="width: 50px;" value="<?=set_value('video_strage',
												$school['contract_param_video']['strage_convert'])?>">G
										</td>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_storage_now','現在のストレージ使用量') ?></th>
										<td>
											<?= (isset($school['contract_param_video']['strage_now']) ? ConvertUnit($school['contract_param_video']['strage_now'],2) : '-'); ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_consumption_datetime','使用量取得日時') ?></th>
										<td colspan=3>
											<?= (isset($school['contract_param_video']['updated_at']) ? date('Y-m-d H:i:s',$school['contract_param_video']['updated_at']) : '-'); ?>
										</td>
									</tr>
									</table>
								</div>
								
								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_book_library','図書室') ?></th><tr>
								<tr>
									<th style="width:142px;">&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php $js = 'id="book_library_contract" onChange="book_library_contract_function();"'; ?>
										<?=form_dropdown('book_library_contract', 
											$contract_dropdown, set_value('book_library_contract', 
												$school['contract_param_book_library']['contract']), $js);?>
									</td>
								</tr>
								</table>
								
								<div id="list_book_library" <?=($school['contract_param_book_library']['contract']=='undefined')?'style="display:none;"':''?> >
									<table>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_status','状態') ?></th>
										<td colspan=3>
											<?php if(isset($school['contract_param_book_library']['stat']) && $school['contract_param_book_library']['stat'] == 1){ ?>
												<?= $this->lang->line_or_def('common_contract_range','契約範囲内') ?>
											<?php }elseif(isset($school['contract_param_book_library']['stat']) && $school['contract_param_live']['stat'] == 0){ ?>
												<?= $this->lang->line_or_def('common_contract_range_excess','契約範囲超過') ?>
											<?php }else{ ?>
												------
											<?php } ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_transfer','契約転送量') ?></th>
										<td>
											<input type="text" name="book_library_stream" style="width: 50px;" value="<?=set_value('book_library_stream',
												$school['contract_param_book_library']['stream_convert'])?>">G
										</td>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_transfer_now','現在の転送量') ?></th>
										<td>
											<?= (isset($school['contract_param_book_library']['stream_now']) ? ConvertUnit($school['contract_param_book_library']['stream_now'],2) : '-'); ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') ?></th>
										<td>
											<input type="text" name="book_library_strage" style="width: 50px;" value="<?=set_value('book_library_strage',
												$school['contract_param_book_library']['strage_convert'])?>">G
										</td>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_storage_now','現在のストレージ使用量') ?></th>
										<td>
											<?= (isset($school['contract_param_book_library']['strage_now']) ? ConvertUnit($school['contract_param_book_library']['strage_now'],2) : '-'); ?>
										</td>
									</tr>
									<tr>
										<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_consumption_datetime','使用量取得日時') ?></th>
										<td colspan=3>
											<?= (isset($school['contract_param_book_library']['updated_at']) ? date('Y-m-d H:i:s',$school['contract_param_book_library']['updated_at']) : '-'); ?>
										</td>
									</tr>
									</table>
								</div>


								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_issue', '課題') ?></th></tr>
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?=form_dropdown('issue_contract', 
											$contract_dropdown, set_value('issue_contract', 
												$school['contract_param_issue']['contract']), $js);?>
									</td>
								</tr>
								</table>


								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								
								<table>
								<tr><th colspan=4>ALF Stream
									<?php if( $school['school_id'] == 0 ): ?>
										<font color="#FF0000">&nbsp;&nbsp;<?= $this->lang->line_or_def('msg_school_manage_no_input','新規登録時は入力不可') ?></font>
									<? endif; ?>
								</th><tr>
								<tr>
									<th>&bull;&nbsp;auth key</th>
									<td colspan=3>
										<input type="text" name="alfstream_auth_key" style="width:330px;" value="<?=set_value('alfstream_auth_key',
											$school['contract_param_alfstream']['auth_key'])?>"
											<?php if( $school['school_id'] == 0 ): ?>disabled="disabled"<? endif; ?> >
									</td>
								</tr>
								<tr>
									<th>&bull;&nbsp;code</th>
									<td colspan=3>
										<input type="text" name="alfstream_auth_code" style="width:150px;" value="<?=set_value('alfstream_auth_code',
											$school['contract_param_alfstream']['code'])?>"
											<?php if( $school['school_id'] == 0 ): ?>disabled="disabled" <? endif; ?> >
									</td>
								</tr>
								</table>
								
								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_outside_corporation', '外部連携') ?>&nbsp;:&nbsp;eLearning Manager</th>
								<tr>
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?=form_dropdown('outside_elearningmanager_contract', 
											$outside_contract, set_value('outside_elearningmanager_contract', 
												$school['contract_param_outside_elearningmanager']['contract']), $js);?>
									</td>
								</tr>
								<tr>
									<th>&bull;&nbsp;Api Key</th>
									<td colspan=3>
										<input type="hidden" name="outside_elearningmanager_api_key" value="<?= htmlspecialchars( $school['contract_param_outside_elearningmanager']['api_key'], ENT_QUOTES, 'UTF-8') ?>">
										<?php if($school['contract_param_outside_elearningmanager']['api_key']==''): ?>
											-
										<?php else: ?>
											<?= htmlspecialchars( $school['contract_param_outside_elearningmanager']['api_key'], ENT_QUOTES, 'UTF-8') ?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th>&bull;&nbsp;Api URL</th>
									<td colspan=3>
										<input type="hidden" name="outside_elearningmanager_api_url" value="<?= htmlspecialchars( $school['contract_param_outside_elearningmanager']['api_url'], ENT_QUOTES, 'UTF-8') ?>">
										<?php if($school['contract_param_outside_elearningmanager']['api_url']==''): ?>
											-
										<?php else: ?>
											<?= htmlspecialchars( $school['contract_param_outside_elearningmanager']['api_url'], ENT_QUOTES, 'UTF-8') ?>
										<?php endif; ?>
									</td>
								</tr>
								</table>

							</td>
						</tr>
					</table>

				<?php if( $school['school_id'] == 0 ): ?>
					<h2><?= $this->lang->line_or_def('msg_school_manage_admin_input','学校の管理者を入力してください') ?></h2>
					<table class="form">
						<tr>
							<th width="160px"><?= $this->lang->line_or_def('common_teacher_name','講師名') ?></th>
							<td>
								<input type="text" name="school_admin_name" size="48" value="<?= set_value('school_admin_name',$school['school_admin_name']) ?>">
							</td>
						</tr>
						<tr>
							<th width="160px"><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<input type="text" name="school_admin_email" size="48" value="<?= set_value('school_admin_email',$school['school_admin_email']) ?>">
							</td>
						</tr>
					</table>
				<?php endif ?>

			<?php if( $school['school_id'] > 0 ): ?>
				<?php if( isset($school['school_admin_name'])): ?>
					<input type="hidden" name="school_admin_name"  value="<?= set_value('school_admin_name'  ,$school['school_admin_name']) ?>">
					<input type="hidden" name="school_admin_email" value="<?= set_value('school_admin_email' ,$school['school_admin_email']) ?>">
				<?php endif ?>
				<?php if( !isset($school['school_admin_name'])): ?>
					<input type="hidden" name="school_admin_name"  value="<?= set_value('school_admin_name'  ,'') ?>">
					<input type="hidden" name="school_admin_email" value="<?= set_value('school_admin_email' ,'') ?>">
				<?php endif ?>
			<?php endif ?>

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
