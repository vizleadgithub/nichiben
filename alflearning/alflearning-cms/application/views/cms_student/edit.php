<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>
	<style>
	#student_birthday{
		width: 76px;
	}
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
	.password_identity{
		float : right;
		font-size : 12px;
		height : 23px;
		line-height : 14px;
		margin-right : 10px;
		width: 420px;
		color: #00A4E2;
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
	<script type="text/javascript">
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datepicker設定（生年月日）
			$('#student_birthday').datepicker(datepickeroption);
			//datetimepicker設定（入会日時・退会日時・最終更新日）
			$('#regist_at' ).datetimepicker(datetimepickeroption);
			$('#delete_at').datetimepicker(datetimepickeroption);
			$('#user_last_update_date').datetimepicker(datetimepickeroption);

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

		//------------------------------------------
		// パスワード・パスワード（確認）表示・非表示切り替え[2012/09/10]
		//------------------------------------------
		flag = false;
		function dspmsg(){
			if($("*[name=student_password_change]").val()=="1"){
				flag = true;
			}
		
			if(flag) {
				document.getElementById("changebutton").value= "<?= $this->lang->line_or_def('msg_change_password','パスワードを変更する') ?>";
				$("*[name=student_password]").attr("disabled", "disabled");
				$("*[name=student_password_check]").attr("disabled", "disabled");
				
				$("*[name=student_password]").css("background-color", "gainsboro ");
				$("*[name=student_password_check]").css("background-color", "gainsboro ");
				
				$("*[name=student_password]").val("");
				$("*[name=student_password_check]").val("");
				
				$("*[name=student_password_change]").val("0");
				
				$("*[name=password_identity]").css("display", "none");
			}
			else{
				document.getElementById("changebutton").value= "<?= $this->lang->line_or_def('msg_not_change_password','パスワードを変更しない') ?>";
				$("*[name=student_password]").removeAttr("disabled");
				$("*[name=student_password_check]").removeAttr("disabled");
				
				$("*[name=student_password]").css("background-color", "white");
				$("*[name=student_password_check]").css("background-color", "white");
				
				$("*[name=student_password_change]").val("1");

				$("*[name=password_identity]").css("display", "block");
		}
			flag = !flag;
		}


		//------------------------------------------
		// 法学館対応
		//------------------------------------------
		flag_hogaku = false;
		function dspmsg_hogaku(){
			if($("*[name=password_answer_change]").val()=="1"){
				flag_hogaku = true;
			}
		
			if(flag_hogaku) {
				document.getElementById("changebutton_hogaku").value= "<?= $this->lang->line_or_def('msg_change_password_confirm','パスワード確認を変更する') ?>";
				$("*[name=password_question]").attr("disabled", "disabled");
				$("*[name=password_answer]").attr("disabled", "disabled");
				
				$("*[name=password_question]").css("background-color", "gainsboro ");
				$("*[name=password_answer]").css("background-color", "gainsboro ");
				
				$("*[name=password_answer_change]").val("0");
			}
			else{
				document.getElementById("changebutton_hogaku").value= "<?= $this->lang->line_or_def('msg_not_change_password_confirm','パスワード確認を変更しない') ?>";
				$("*[name=password_question]").removeAttr("disabled");
				$("*[name=password_answer]").removeAttr("disabled");
				
				$("*[name=password_question]").css("background-color", "white ");
				$("*[name=password_answer]").css("background-color", "white ");
				
				$("*[name=password_answer_change]").val("1");
		}
			flag_hogaku = !flag_hogaku;
		}
		
		function clear_regist_at(){
			$("#regist_at").val("");
		}
		function clear_delete_at(){
			$("#delete_at").val("");
		}
		function clear_user_last_update_date(){
			$("#user_last_update_date").val("");
		}
		function clear_student_birthday(){
			$("#student_birthday").val("");
		}
	</script>
	<style type="text/css"><!--
		TEXTAREA{
			width : 100%;
			height : 70px;
		}
	// --></style>
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

				<h2><?= $this->lang->line_or_def('msg_student_input','受講者の情報を入力してください') ?></h2>

				<?=form_open("cms_student/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?= (isset($error_msg) && $error_msg ? '<div class="error">'.$error_msg.'</div>' : ''); ?>
					<? if( (isset($elm_stat)) && ($elm_stat != 200) ): ?><div class="error"><?= $elm_message.'(code:'.$elm_stat.')'; ?></div><? endif; ?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $student['update_flg'])?>'>
					<input type=hidden name=student_id value='<?=set_value('student_id', $student['student_id'])?>'>
					<input type=hidden name=student_password_change value='<?=set_value('student_password_change', $student['student_password_change'])?>'>

					<input type=hidden name=password_answer_change value='<?=set_value('password_answer_change', $student['password_answer_change'])?>'>
					<input type=hidden name=old_password_question value='<?=set_value('old_password_question', $student['old_password_question'])?>'>

					<? if( $this->config->item('language') == 'alfsales' ): ?>
					<input type=hidden name=student_birthday value='1999-01-07'>
					<? endif; ?>

					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<input type="text" name="student_name" size="45" value="<?=set_value('student_name',$student['student_name'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<input type="text" name="student_email" size="45" value="<?=set_value('student_email',$student['student_email'])?>">
							</td>
						</tr>
						<tr>
							<th style="vertical-align:middle;"><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
							<td>
								<?php if($student['update_flg']!=0): ?>
									<input type="button" id="changebutton" 
									 value="<?=(set_value('student_password_change',$student['student_password_change'])=='0')? $this->lang->line_or_def('msg_change_password','パスワードを変更する') : $this->lang->line_or_def('msg_not_change_password','パスワードを変更しない')?>" onclick="dspmsg()" style="margin-bottom: 5px;">
									
									<label name="password_identity" class="password_identity" <?= ($student['student_password_change']=='0') ? 'style="display:none;"' : 'style="display:block;"' ?> >
										<? // 法学館対応：パスワード変更時の注意文を非表示 ?>
										<? if(1==0): ?>
										<?= $this->lang->line_or_def('msg_fixed_password_identity','同じメールアドレスが存在する場合、パスワードは全て変更後のパスワードになります') ?>
										<? endif; ?>
									</label>
									<input type=hidden name=student_password_identity value=1>
									<br/>
								<?php endif; ?>
								
								<input type="password" name="student_password" size="48" 
								 <?=(set_value('student_password_change',$student['student_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?>
								 value="<?=set_value('student_password',$student['student_password'])?>">
								
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password_conf','パスワード（確認入力）') ?></th>
							<td>
								<input type="password" name="student_password_check" size="48" 
								 <?=(set_value('student_password_change',$student['student_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?>
								 value="<?=set_value('student_password_check',$student['student_password_check'])?>">
								
							</td>
						</tr>

						<? if( $this->config->item('language') != 'alfsales' ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_date_of_birth','生年月日') ?></th>
							<td >
								<input type="text" name="student_birthday" size="10" style="width: 100px;" value="<?=set_value('student_birthday',$student['student_birthday'])?>" id="student_birthday" readonly>
								<button onclick="clear_student_birthday();return false;">削除</button>
							</td>
						</tr>
						<? endif; ?>

						<tr>
							<th>
								<?= $this->lang->line_or_def('common_attendance_class','受講講座') ?>
								<a href="#" onclick="select_all();return false;" id="select_all" name="select_all"></a>
							</th>
							<td>
								<div class="cource_list">
									<ul class="list" id="cource_ul">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<li>
												<input type="checkbox" name="student_lectures[]" id="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>
													<?php 
													if( isset($student['student_lectures']) ) {
														foreach( $student['student_lectures'] as $lecture) { 
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
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td >
								<textarea name="student_note" ><?=set_value('student_note',$student['student_note'])?></textarea>
							</td>
						</tr>
						
						<? //法学館対応 ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_enrollment_datetime','入会日時') ?></th>
							<td >
								<input type="text" name="regist_at" size="19" value="<?=set_value('regist_at',$student['regist_at'])?>" id="regist_at" readonly>
								<button onclick="clear_regist_at();return false;">削除</button>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_withdrawal_datetime','退会日時') ?></th>
							<td >
								<input type="text" name="delete_at" size="19" value="<?=set_value('delete_at',$student['delete_at'])?>" id="delete_at" readonly>
								<button onclick="clear_delete_at();return false;">削除</button>
							</td>
						</tr>

						<th><?= $this->lang->line_or_def('common_student_name_kana','生徒氏名カナ') ?></th>
						<td>
							<input type="text" name="student_name_kana" size="45" value="<?=set_value('student_name_kana',$student['student_name_kana'])?>">
						</td>

						<tr>
							<th><?= $this->lang->line_or_def('common_sex','性別') ?></th>
							<td>
								<?=form_dropdown('sex',$mtb_gender, set_value('sex', $student['sex']));?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_country_type','国種別') ?></th>
							<td>
								<?=form_dropdown('country_type',$mtb_country_type, set_value('country_type', $student['country_type']));?>
							</td>
						</tr>

						<th><?= $this->lang->line_or_def('common_zip_code','郵便番号') ?></th>
						<td>
							<input type="text" name="zip" size="45" value="<?=set_value('zip',$student['zip'])?>">
						</td>
						<tr>
							<th><?= $this->lang->line_or_def('common_prefecture','都道府県') ?></th>
							<td>
								<?=form_dropdown('pref',$mtb_pref, set_value('pref', $student['pref']));?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_municipality','市区町村') ?></th>
							<td>
								<input type="text" name="address1" size="45" value="<?=set_value('address1',$student['address1'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_address_number','番地') ?></th>
							<td>
								<input type="text" name="address2" size="45" value="<?=set_value('address2',$student['address2'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_building_name','建物名') ?></th>
							<td>
								<input type="text" name="address3" size="45" value="<?=set_value('address3',$student['address3'])?>">
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_overseas_zip_code','海外郵便番号') ?></th>
							<td>
								<input type="text" name="zip_overseas" size="45" value="<?=set_value('zip_overseas',$student['zip_overseas'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_overseas_address_1','海外住所・住所1') ?></th>
							<td>
								<input type="text" name="address_overseas1" size="45" value="<?=set_value('address_overseas1',$student['address_overseas1'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_overseas_address_2','海外住所・住所2') ?></th>
							<td>
								<input type="text" name="address_overseas2" size="45" value="<?=set_value('address_overseas2',$student['address_overseas2'])?>">
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_member_attribute','会員属性') ?></th>
							<td>
								<?=form_dropdown('member_type',$mtb_member_type, set_value('member_type', $student['member_type']));?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_phone_number_1','電話番号1') ?></th>
							<td>
								<input type="text" name="tel1" size="45" value="<?=set_value('tel1',$student['tel1'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_phone_number_2','電話番号2') ?></th>
							<td>
								<input type="text" name="tel2" size="45" value="<?=set_value('tel2',$student['tel2'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_phone_number_3','電話番号3') ?></th>
							<td>
								<input type="text" name="tel3" size="45" value="<?=set_value('tel3',$student['tel3'])?>">
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_fax_number_1','FAX番号1') ?></th>
							<td>
								<input type="text" name="fax1" size="45" value="<?=set_value('fax1',$student['fax1'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_fax_number_2','FAX番号2') ?></th>
							<td>
								<input type="text" name="fax2" size="45" value="<?=set_value('fax2',$student['fax2'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_fax_number_3','FAX番号3') ?></th>
							<td>
								<input type="text" name="fax3" size="45" value="<?=set_value('fax3',$student['fax3'])?>">
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_mobile_mail_address','携帯メールアドレス') ?></th>
							<td>
								<input type="text" name="student_email_mobile" size="45" value="<?=set_value('student_email_mobile',$student['student_email_mobile'])?>">
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_occupation','職業') ?></th>
							<td>
								<?=form_dropdown('job',$mtb_job, set_value('job', $student['job']));?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_business','業種') ?></th>
							<td>
								<?=form_dropdown('job_type',$mtb_job_type, set_value('job_type', $student['job_type']));?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<td>
								<input type="text" name="school_name" size="45" value="<?=set_value('school_name',$student['school_name'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_grade','学年') ?></th>
							<td>
								<?=form_dropdown('school_grade',$mtb_school_grade, set_value('school_grade', $student['school_grade']));?>
							</td>
						</tr>

						<tr>
							<th style="vertical-align:middle;"><?= $this->lang->line_or_def('common_password_confirm_question','パスワード確認質問') ?></th>
							<td>
								<?php if($student['update_flg']!=0): ?>
									<input type="button" id="changebutton_hogaku" 
									 value="<?=(set_value('password_answer_change',$student['password_answer_change'])=='0')? $this->lang->line_or_def('v','パスワード確認を変更する') : $this->lang->line_or_def('msg_not_change_password_confirm','パスワード確認を変更しない')?>" onclick="dspmsg_hogaku()" style="margin-bottom: 5px;">
									<br/>
								<?php endif; ?>
								
								<?php
									$set_style = '';
									if($student['password_answer_change'] == '0'){
										$set_style = 'style="background-color: gainsboro;" disabled="disabled"';
									}
								?>
								
								<?=form_dropdown('password_question',$mtb_password_question, set_value('password_question', $student['password_question']), $set_style );?>
								<?php if($set_style != ''): ?>
								
								<?php endif; ?>
								
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password_confirm_answer','パスワード確認回答') ?></th>
							<td>
								<input type="text" name="password_answer" size="45" value="<?=set_value('password_answer',$student['password_answer'])?>" <?=$set_style; ?> >
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_mail_magazine','メールマガジン') ?></th>
							<td>
								<?=form_dropdown('mailmagazine_flg',$mtb_mailmagazine_flg, set_value('mailmagazine_flg', $student['mailmagazine_flg']));?>
							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_mail_magazine_id','メルマガID') ?></th>
							<td>
								<input type="hidden" name="mailmagazine_ids" value="<?=set_value('mailmagazine_ids',$student['mailmagazine_ids'])?>">
								<ul id="student_ul" class="list" style="margin-top: 0px;">
								<?php foreach($mtb_mailmagazine_category as $index => $value): ?>
									<?php
										$selected = "";
										foreach($student['mailmagazine_ids_array'] as $selected_id){
											if($selected_id == $index){
												$selected = "checked";
												break;
											}
										}
									?>
									<li>
										<input id="mailmagazine_category_<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>" type="checkbox" value="<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>" name="mailmagazine_ids_array[]" <?= htmlspecialchars( $selected, ENT_QUOTES, 'UTF-8') ?>>
										<label for="mailmagazine_category_<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $value, ENT_QUOTES, 'UTF-8') ?></label>
									</li>
								<?php endforeach; ?>
								</ul>
							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_cognitive_media','認知媒体') ?></th>
							<td>
								<?= '';//form_dropdown('media_id',$mtb_media, set_value('media_id', $student['media_id']));?>

								<input type="hidden" name="media_id" value="<?=set_value('media_id',$student['media_id'])?>">
								<ul id="media_ul" class="list" style="margin-top: 0px;">
								<?php foreach($mtb_media as $index => $value): ?>
									<?php
										$selected = "";
										foreach($student['media_id_array'] as $selected_id){
											if($selected_id == $index){
												$selected = "checked";
												break;
											}
										}
									?>
									<li>
										<input id="media_category_<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>" type="checkbox" value="<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>" name="media_id_array[]" <?= htmlspecialchars( $selected, ENT_QUOTES, 'UTF-8') ?>>
										<label for="media_category_<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $value, ENT_QUOTES, 'UTF-8') ?></label>
									</li>
								<?php endforeach; ?>
								</ul>

							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_age_late','年代') ?></th>
							<td>
								<?=form_dropdown('age',$mtb_age, set_value('age', $student['age']));?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_itojuku_student_number','伊藤塾塾生番号') ?></th>
							<td>
								<input type="text" name="student_no" size="45" value="<?=set_value('student_no',$student['student_no'])?>">
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_aaa','最終更新日') ?></th>
							<td >
								<input type="text" name="user_last_update_date" size="19" value="<?=set_value('user_last_update_date',$student['user_last_update_date'])?>" id="user_last_update_date" readonly>
								<button onclick="clear_user_last_update_date();return false;">削除</button>
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
