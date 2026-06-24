<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "school_manage";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_school_manage/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_school_manage/edit/";
		}
	</script>
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

				<h2>
					<?php 
						$detail_confirm_flag = 0;
						if( $school['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_school_manage_confirm','学校情報の確認');
							$detail_confirm_flag = 1;
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_school_manage_confirm','学校情報の確認');
									$detail_confirm_flag = 1;
									break;
								case 2://詳細画面
									print $this->lang->line_or_def('msg_school_manage_detail','学校情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_school_manage/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<td><!-- width="400"-->
								<?=$school['school_name']?>
							</td>
						</tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td>
								<?=$school['school_caption']?>
							</td>
						</tr>
						</tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td>
								<?=$school['school_note']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
							<td >
								<!--<?=$school['contract']?>-->

								<?php if($school['contract'] === 'fixation'){ ?>
									<?= $this->lang->line_or_def('common_contract_fixation','本契約') ?>
								<?php }elseif($school['contract'] === 'demo'){ ?>
									<?= $this->lang->line_or_def('common_contract_demo','デモ版') ?>
								<?php }elseif($school['contract'] === 'presentation'){ ?>
									<?= $this->lang->line_or_def('common_contract_presentation','プレゼン版') ?>
								<?php }else{ ?>
									------
								<?php } ?>

							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_contents','契約内容') ?></th>
							<td><!--<?=$school['contract_param']?><br>-->
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_class','授業') ?></th><tr>
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php if($school['contract_param_live']['contract'] === 'fixation'){ ?>
											<?= $this->lang->line_or_def('common_monthly_basis_contract','月額契約') ?>
										<?php }elseif($school['contract_param_live']['contract'] === 'undefined'){ ?>
											<?= $this->lang->line_or_def('common_no_setting','未設定') ?>
										<?php }else{ ?>
											------
										<?php } ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_live']['contract']=='undefined')?'style="display:none;"':''?> >
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
								<tr <?=($school['contract_param_live']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_class_time','契約授業時間') ?></th>
									<td>
										<?=$school['contract_param_live']['time_convert']?><?= $this->lang->line_or_def('common_time','時間') ?>
									</td>

									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_class_time_now','現在の授業時間') ?></th>
									<td>
										<?= (isset($school['contract_param_live']['time_now']) ? Sec2Disp($school['contract_param_live']['time_now'], array('dd' => false, 'mm' => false, 'ss' => false)) : '-'); ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_live']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') ?></th>
									<td>
										<?=$school['contract_param_live']['strage_convert']?>G
									</td>

									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_storage_now','現在のストレージ使用量') ?></th>
									<td>
										<?= (isset($school['contract_param_live']['strage_now']) ? ConvertUnit($school['contract_param_live']['strage_now'],2) : '-'); ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_live']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_consumption_datetime','使用量取得日時') ?></th>
									<td colspan=3>
										<?= (isset($school['contract_param_live']['updated_at']) ? date('Y-m-d H:i:s',$school['contract_param_live']['updated_at']) : '-'); ?>
									</td>
								</tr>
								</table>
								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_video','ビデオ') ?></th><tr>
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php if($school['contract_param_video']['contract'] === 'fixation'){ ?>
											<?= $this->lang->line_or_def('common_monthly_basis_contract','月額契約') ?>
										<?php }elseif($school['contract_param_video']['contract'] === 'undefined'){ ?>
											<?= $this->lang->line_or_def('common_no_setting','未設定') ?>
										<?php }else{ ?>
											------
										<?php } ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_video']['contract']=='undefined')?'style="display:none;"':''?> >
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
								<tr <?=($school['contract_param_video']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_transfer','契約転送量') ?></th>
									<td>
										<?=$school['contract_param_video']['stream_convert']?>G
									</td>

									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_transfer_now','現在の転送量') ?></th>
									<td>
										<?= (isset($school['contract_param_video']['stream_now']) ? ConvertUnit($school['contract_param_video']['stream_now'],2) : '-'); ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_video']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') ?></th>
									<td>
										<?=$school['contract_param_video']['strage_convert']?>G
									</td>

									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_storage_now','現在のストレージ使用量') ?></th>
									<td>
										<?= (isset($school['contract_param_video']['strage_now']) ? ConvertUnit($school['contract_param_video']['strage_now'],2) : '-'); ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_video']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_consumption_datetime','使用量取得日時') ?></th>
									<td colspan=3>
										<?= (isset($school['contract_param_video']['updated_at']) ? date('Y-m-d H:i:s',$school['contract_param_video']['updated_at']) : '-'); ?>
									</td>
								</tr>
								</table>
								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								<table>
								<tr><th colspan=4><?= $this->lang->line_or_def('common_book_library','図書室') ?></th><tr>
								
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php if($school['contract_param_book_library']['contract'] === 'fixation'){ ?>
											<?= $this->lang->line_or_def('common_monthly_basis_contract','月額契約') ?>
										<?php }elseif($school['contract_param_book_library']['contract'] === 'undefined'){ ?>
											<?= $this->lang->line_or_def('common_no_setting','未設定') ?>
										<?php }else{ ?>
											------
										<?php } ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_book_library']['contract']=='undefined')?'style="display:none;"':''?> >
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
								<tr <?=($school['contract_param_book_library']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_transfer','契約転送量') ?></th>
									<td>
										<?=$school['contract_param_book_library']['stream_convert']?>G
									</td>

									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_transfer_now','現在の転送量') ?></th>
									<td>
										<?= (isset($school['contract_param_book_library']['stream_now']) ? ConvertUnit($school['contract_param_book_library']['stream_now'],2) : '-'); ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_book_library']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_storage','契約ストレージ使用量') ?></th>
									<td>
										<?=$school['contract_param_book_library']['strage_convert']?>G
									</td>

									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_storage_now','現在のストレージ使用量') ?></th>
									<td>
										<?= (isset($school['contract_param_book_library']['strage_now']) ? ConvertUnit($school['contract_param_book_library']['strage_now'],2) : '-'); ?>
									</td>
								</tr>
								<tr <?=($school['contract_param_book_library']['contract']=='undefined')?'style="display:none;"':''?> >
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_consumption_datetime','使用量取得日時') ?></th>
									<td colspan=3>
										<?= (isset($school['contract_param_book_library']['updated_at']) ? date('Y-m-d H:i:s',$school['contract_param_book_library']['updated_at']) : '-'); ?>
									</td>
								</tr>
								</table>

								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								<table>
								<tr>
									<th colspan=4><?= $this->lang->line_or_def('common_issue', '課題') ?></th>
								</tr>
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php if($school['contract_param_issue']['contract'] === 'fixation'){ ?>
											<?= $this->lang->line_or_def('common_monthly_basis_contract','月額契約') ?>
										<?php }elseif($school['contract_param_issue']['contract'] === 'undefined'){ ?>
											<?= $this->lang->line_or_def('common_no_setting','未設定') ?>
										<?php }else{ ?>
											------
										<?php } ?>
									</td>
								</tr>
								</table>

								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								<table>
								<tr><th colspan=2>ALF Stream</th><tr>
								<tr>
									<th>&bull;&nbsp;auth key</th>
									<td colspan=3>
										<?=$school['contract_param_alfstream']['auth_key']?>
									</td>
								</tr>
								<tr>
									<th>&bull;&nbsp;code</th>
									<td colspan=3>
										<?=$school['contract_param_alfstream']['code']?>
									</td>
								</tr>
								</table>
								
								<hr style="background-color:#72726E;border: none;height: 1px;"/>
								<table>
								<tr>
									<th colspan=4><?= $this->lang->line_or_def('common_outside_corporation', '外部連携') ?>&nbsp;:&nbsp;eLearning Manager</th>
								<tr>
								<tr>
									<th>&bull;&nbsp;<?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
									<td colspan=3>
										<?php if($school['contract_param_outside_elearningmanager']['contract'] === 'fixation'){ ?>
											<?= $this->lang->line_or_def('common_contract','契約あり') ?>
										<?php }elseif($school['contract_param_outside_elearningmanager']['contract'] === 'undefined'){ ?>
											<?= $this->lang->line_or_def('common_no_contract','契約なし') ?>
										<?php }else{ ?>
											------
										<?php } ?>
									</td>
								</tr>
								<tr>
									<th>&bull;&nbsp;Api Key</th>
									<td colspan=3>
										<?php if($school['contract_param_outside_elearningmanager']['api_key']==''): ?>
											-
										<?php else: ?>
											<?=$school['contract_param_outside_elearningmanager']['api_key']?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th>&bull;&nbsp;Api URL</th>
									<td colspan=3>
										<?php if($school['contract_param_outside_elearningmanager']['api_url']==''): ?>
											-
										<?php else: ?>
											<?=$school['contract_param_outside_elearningmanager']['api_url']?>
										<?php endif; ?>
									</td>
								</tr>
								</table>
							</td>
						</tr>
					<?php if( $detail_confirm_flag == 0 ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_school_admin_count','管理者数') ?></th>
							<td ><?=$school['school_admin_count']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_teacher_count','講師数') ?></th>
							<td ><?=$school['teacher_count']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_student_count','受講者数') ?></th>
							<td ><?=$school['student_count']?>
							</td>
						</tr>
					<?php endif ?>
					</table>

				<?php if( $detail_confirm_flag == 1 ): ?>
				<?php if( $school['school_id'] == 0): ?>
					<h2><?= $this->lang->line_or_def('common_','学校の管理者') ?></h2>
					<table class="form">
						<tr>
							<th width="160px"><?= $this->lang->line_or_def('common_teacher_name','講師名') ?></th>
							<td>
								<?=$school['school_admin_name']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<?=$school['school_admin_email']?>
							</td>
						</tr>
					</table>
				<?php endif ?>
				<?php endif ?>

					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$school['school_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png'>";
									break;
									
								case 2://詳細画面 /cms_school_manage


									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \""."/cms_school_manage"."\";return false;' />";
								#	print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$school['school_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
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
