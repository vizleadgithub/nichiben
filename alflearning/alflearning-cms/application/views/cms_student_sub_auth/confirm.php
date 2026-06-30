<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm(msg)){
				location.href = "<?=base_url()?>cms_student/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_student/edit/";
		}
	</script>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>


	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_student','受講者管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_student_comment','受講者を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_student/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_student/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $student['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_student_confirm','受講者情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_student_confirm','受講者情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_student_detail','受講者情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_student/commit")?>
					<? if( (isset($elm_stat)) && ($elm_stat != 200) ): ?><div class="error"><?= htmlspecialchars( $elm_message, ENT_QUOTES, 'UTF-8').'(code:'.htmlspecialchars( $elm_stat, ENT_QUOTES, 'UTF-8').')'; ?></div><? endif; ?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<?= htmlspecialchars( $student['student_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','弁護士番号') ?></th>
							<td>
								<?= htmlspecialchars( $student['lawyer_number'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','所属弁護士会') ?></th>
							<td>
								<?php if(isset($student['bar_association_id'])): ?>
									<?php if( isset($mtb_bar_association[$student['bar_association_id']]) ): ?>
										<?= htmlspecialchars( $mtb_bar_association[$student['bar_association_id']], ENT_QUOTES, 'UTF-8') ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','登録年') ?></th>
							<td>
								<?= htmlspecialchars( $student['regist_date'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','対象パスポート') ?></th>
							<td>
								<?= htmlspecialchars( $student['target_passport'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','パスポートの有無') ?></th>
							<td>
								<?= htmlspecialchars( $student['presence_passport'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<?= htmlspecialchars( $student['student_email'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','メールマガジンの可否') ?></th>
							<td>
								<?php if(isset($student['mailmagazine_flg'])): ?>
									<?php if( isset($mtb_mailmagazine_flg[$student['mailmagazine_flg']]) ): ?>
										<?= htmlspecialchars( $mtb_mailmagazine_flg[$student['mailmagazine_flg']], ENT_QUOTES, 'UTF-8') ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','倫理研修') ?></th>
							<td>
								<?php if(isset($student['ethic_training'])): ?>
									<?php if( isset($mtb_ethic_training[$student['ethic_training']]) ): ?>
										<?= htmlspecialchars( $mtb_ethic_training[$student['ethic_training']], ENT_QUOTES, 'UTF-8') ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','代替倫理研修権限') ?></th>
							<td>
								<?php if(isset($student['sub_auth_ethic_training'])): ?>
									<?php if( isset($mtb_sub_auth_ethic_training[$student['sub_auth_ethic_training']]) ): ?>
										<?= htmlspecialchars( $mtb_sub_auth_ethic_training[$student['sub_auth_ethic_training']], ENT_QUOTES, 'UTF-8') ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>


<!--
						<tr>
							<th><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
							<td style="vertical-align:middle;">
								<?php if( ($student['update_flg'] == 0) || (($student['update_flg'] != 0) && ($student['student_password_change'] == 1)) || ($btn_kirikae_flg == 2) ): ?>
									******
									<?php //法学館対応：メッセージの非表示固定 ?>
									<?php //if($student['student_password_identity'] == 1): ?>
									<?php if(1==0): ?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<font class="error">
											<?= $this->lang->line_or_def('msg_student_password_identity','※同じメールアドレスを持つ受講者のパスワードも、全て同じにする') ?>
										</font>
									<?php endif; ?>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_no_modify','変更なし'); ?>
								<?php endif; ?>
							</td>
						</tr>

						<? if( $this->config->item('language') != 'alfsales' ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_date_of_birth','生年月日') ?></th>
							<td >
								<?=$student['student_birthday']?>
							</td>
						</tr>
						<? endif; ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_attendance_class','受講講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($student['student_lectures_name']) ) {
										foreach( $student['student_lectures_name'] as $name) { 
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
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td style="word-break: break-all;"><?=nl2br($student['student_note']); ?></td>
						</tr>
						
						<?php if($btn_kirikae_flg === 2): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_history','閲覧履歴') ?></th>
							<td>
							<?php if(count($history_data)>0): ?>
								<table>
									<tr>
										<th width=" 50px"><?= $this->lang->line_or_def('common_id','ID') ?></th>
										<th width="250px"><?= $this->lang->line_or_def('common_video_name','ビデオ名') ?></th>
										<th width="100px"><?= $this->lang->line_or_def('common_local_reading_','閲覧率') ?></th>
										<th width="150px"><?= $this->lang->line_or_def('common_local_reading_date','閲覧日') ?></th>
									</tr>
								</table>
								<div style="overflow-y: scroll;width: 640px;height: 200px;">
								<table>
									<?php foreach($history_data as $history): ?>
									<tr style="border: 1px #808080 solid; border-style: none none solid none ;	">
										<td width=" 50px"><?= $history['video_id'] ?></td>
										<td width="250px"><?= $history['video_logic_name'] ?></td>
										<td width="100px"><?= $history['percent'] ?>%</td>
										<td width="150px"><?= $history['reading_date'] ?></td>
									</tr>
									<?php endforeach; ?>
								</table>
								</div>
							<?php else: ?>
								<?= $this->lang->line_or_def('common_no_local_reading_history','閲覧履歴なし') ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endif; ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_enrollment_datetime','入会日時') ?></th>
							<td >
								<?=$student['regist_at']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_withdrawal_datetime','退会日時') ?></th>
							<td >
								<?=$student['delete_at']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_student_name_kana','生徒氏名カナ') ?></th>
							<td >
								<?=$student['student_name_kana']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_sex','性別') ?></th>
							<td>
								<?php if(isset($student['sex'])): ?>
									<?php if( isset($mtb_gender[$student['sex']]) ): ?>
										<?= $mtb_gender[$student['sex']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_country_type','国種別') ?></th>
							<td>
								<?php if(isset($student['country_type'])): ?>
									<?php if( isset($mtb_country_type[$student['country_type']]) ): ?>
										<?= $mtb_country_type[$student['country_type']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<th><?= $this->lang->line_or_def('common_zip_code','郵便番号') ?></th>
						<td>
							<?= $student['zip']; ?>
						</td>
						<tr>
							<th><?= $this->lang->line_or_def('common_prefecture','都道府県') ?></th>
							<td>
								<?php if(isset($student['pref'])): ?>
									<?php if( isset($mtb_pref[$student['pref']]) ): ?>
										<?= $mtb_pref[$student['pref']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_municipality','市区町村') ?></th>
							<td>
								<?= $student['address1']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_address_number','番地') ?></th>
							<td>
								<?= $student['address2']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_building_name','建物名') ?></th>
							<td>
								<?= $student['address3']; ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_overseas_zip_code','海外郵便番号') ?></th>
							<td>
								<?= $student['zip_overseas']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_overseas_address_1','海外住所・住所1') ?></th>
							<td>
								<?= $student['address_overseas1']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_overseas_address_2','海外住所・住所2') ?></th>
							<td>
								<?= $student['address_overseas2']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_member_attribute','会員属性') ?></th>
							<td>
								<?php if(isset($student['member_type'])): ?>
									<?php if( isset($mtb_member_type[$student['member_type']]) ): ?>
										<?= $mtb_member_type[$student['member_type']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_phone_number_1','電話番号1') ?></th>
							<td>
								<?= $student['tel1']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_phone_number_2','電話番号2') ?></th>
							<td>
								<?= $student['tel2']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_phone_number_3','電話番号3') ?></th>
							<td>
								<?= $student['tel3']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_fax_number_1','FAX番号1') ?></th>
							<td>
								<?= $student['fax1']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_fax_number_2','FAX番号2') ?></th>
							<td>
								<?= $student['fax2']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_fax_number_3','FAX番号3') ?></th>
							<td>
								<?= $student['fax3']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mobile_mail_address','携帯メールアドレス') ?></th>
							<td>
								<?= $student['student_email_mobile']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_occupation','職業') ?></th>
							<td>
								<?php if(isset($student['job'])): ?>
									<?php if( isset($mtb_job[$student['job']]) ): ?>
										<?= $mtb_job[$student['job']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_business','業種') ?></th>
							<td>
								<?php if(isset($student['job_type'])): ?>
									<?php if( isset($mtb_job_type[$student['job_type']]) ): ?>
										<?= $mtb_job_type[$student['job_type']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<td>
								<?= $student['school_name']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_grade','学年') ?></th>
							<td>
								<?php if(isset($student['school_grade'])): ?>
									<?php if( isset($mtb_school_grade[$student['school_grade']]) ): ?>
										<?= $mtb_school_grade[$student['school_grade']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password_confirm_question','パスワード確認質問') ?></th>
							<td>
								<? if($btn_kirikae_flg==1): ?>
									<?php // 入力確認画面 ?>
									<? if($student['password_answer_change']==1): ?>
										<?php // 変更あり ?>
										<?php if(isset($student['password_question'])): ?>
											<?= $mtb_password_question[$student['password_question']]; ?>
										<?php endif; ?>
									<? else: ?>
										<?php // 変更なし ?>
										<?php if(isset($student['old_password_question'])): ?>
											<?php if( isset($mtb_password_question[$student['old_password_question']]) ): ?>
												<?= $mtb_password_question[$student['old_password_question']]; ?>
											<?php else: ?>
												<?= ''; ?>
											<?php endif; ?>
										<?php endif; ?>
									<? endif; ?>
								<? else: ?>
									<?php // 詳細画面 ?>
									<?php if(isset($student['password_question'])): ?>
										<?php if( isset($mtb_password_question[$student['password_question']]) ): ?>
											<?= $mtb_password_question[$student['password_question']]; ?>
										<?php else: ?>
											<?= ''; ?>
										<?php endif; ?>
									<?php endif; ?>
								<? endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password_confirm_answer','パスワード確認回答') ?></th>
							<td>
								<? if( ($btn_kirikae_flg==1) && ($student['password_answer_change']==1) ): ?>
									<?php // 入力確認画面かつ変更あり ?>
									<?= $student['password_answer']; ?>
								<? else: ?>
									**********
								<? endif; ?>
							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_magazine','メールマガジン') ?></th>
							<td>
								<?php if(isset($student['mailmagazine_flg'])): ?>
									<?php if( isset($mtb_mailmagazine_flg[$student['mailmagazine_flg']]) ): ?>
										<?= $mtb_mailmagazine_flg[$student['mailmagazine_flg']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_magazine_id','メルマガID') ?></th>
							<td>
								<?php $output = ""; ?>
								<?php foreach($mtb_mailmagazine_category as $index => $value): ?>
									<?php
										foreach($student['mailmagazine_ids_array'] as $selected_id){
											if($selected_id == $index){
												if($output == ""){
													$output = "[ID:".$index."] ".$value;
												}else{
													$output = $output."<br/>"."[ID:".$index."] ".$value;
												}
												break;
											}
										}
									?>
								<?php endforeach; ?>
								<?= $output; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_cognitive_media','認知媒体') ?></th>
							<td>
								<?php $output = ""; ?>
								<?php foreach($mtb_media as $index => $value): ?>
									<?php
										foreach($student['media_id_array'] as $selected_id){
											if($selected_id == $index){
												if($output == ""){
													$output = "[ID:".$index."] ".$value;
												}else{
													$output = $output."<br/>"."[ID:".$index."] ".$value;
												}
												break;
											}
										}
									?>
								<?php endforeach; ?>
								<?= $output; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_age_late','年代') ?></th>
							<td>
								<?php if(isset($student['age'])): ?>
									<?php if( isset($mtb_age[$student['age']]) ): ?>
										<?= $mtb_age[$student['age']]; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_itojuku_student_number','伊藤塾塾生番号') ?></th>
							<td>
								<?= $student['student_no']; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_aaa','最終更新日') ?></th>
							<td>
								<?= $student['user_last_update_date']; ?>
							</td>
						</tr>
-->						
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$student['student_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_student')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$student['student_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";
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
