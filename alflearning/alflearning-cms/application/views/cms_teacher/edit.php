<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "teacher";
	$this->load->view('header/header',$data);?>

	<script type="text/javascript"><!--
		function checkradio(disp) {
			if(disp=="block"){
				$("#select_admin_checkbox").slideDown();
			//	$("#select_admin_checkbox").slideToggle();
			//	$("#select_admin_checkbox").show();
			}else{
				$("#select_admin_checkbox").slideUp();
			//	$("#select_admin_checkbox").hide();
			}
		//	document.getElementById("select_admin_checkbox").style.display = disp;
		}

		//------------------------------------------
		// パスワード・パスワード（確認）表示・非表示切り替え[2012/09/10]
		//------------------------------------------
		flag = false;
		function dspmsg(){
			if($("*[name=teacher_password_change]").val()=="1"){
				flag = true;
			}
		
			if(flag) {
				document.getElementById("changebutton").value= "<?= $this->lang->line_or_def('msg_change_password','パスワードを変更する') ?>";
				$("*[name=teacher_password]").attr("disabled", "disabled");
				$("*[name=teacher_password_check]").attr("disabled", "disabled");
				$("*[name=teacher_password_identity]").attr("disabled", "disabled");
				$("*[name=teacher_password_old]").attr("disabled", "disabled");
				
				$("*[name=teacher_password]").css("background-color", "gainsboro ");
				$("*[name=teacher_password_check]").css("background-color", "gainsboro ");
				$("*[name=teacher_password_old]").css("background-color", "gainsboro ");
				
				$("*[name=teacher_password]").val("");
				$("*[name=teacher_password_check]").val("");
				$("*[name=teacher_password_old]").val("");
				
				$("*[name=teacher_password_identity]").removeAttr('checked');
				
				$("*[name=teacher_password_change]").val("0");
			}
			else{
				document.getElementById("changebutton").value= "<?= $this->lang->line_or_def('msg_not_change_password','パスワードを変更しない') ?>";
				$("*[name=teacher_password]").removeAttr("disabled");
				$("*[name=teacher_password_check]").removeAttr("disabled");
				$("*[name=teacher_password_identity]").removeAttr("disabled");
				$("*[name=teacher_password_old]").removeAttr("disabled");
				
				$("*[name=teacher_password]").css("background-color", "white");
				$("*[name=teacher_password_check]").css("background-color", "white");
				$("*[name=teacher_password_old]").css("background-color", "white");
				
				$("*[name=teacher_password_change]").val("1");
			}
			flag = !flag;
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
	
	.auth_list .disabled{
		color:gray;
	}
// --></style>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_teacher','講師管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_teacher_comment','講師を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_teacher/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_teacher/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_teacher/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_teacher_input','講師の情報を入力してください') ?></h2>

				<?=form_open("cms_teacher/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?= (isset($error_msg) && $error_msg ? '<div class="error">'.htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8').'</div>' : ''); ?>
					<input type=hidden name=update_flg               value='<?=set_value('update_flg', $teacher['update_flg'])?>'>
					<input type=hidden name=teacher_id               value='<?=set_value('teacher_id', $teacher['teacher_id'])?>'>
					<input type=hidden name=teacher_password_change  value='<?=set_value('teacher_password_change', $teacher['teacher_password_change'])?>'>
					<? //自己紹介 概要・自己紹介 詳細 の非表示 ?>
					<input type=hidden name=teacher_introduce        value='<?=set_value('teacher_introduce', $teacher['teacher_introduce'])?>'>
					<input type=hidden name=teacher_introduce_detail value='<?=set_value('teacher_introduce_detail', $teacher['teacher_introduce_detail'])?>'>

					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<input type="text" name="teacher_name" size="48" value="<?=set_value('teacher_name',$teacher['teacher_name'])?>">
							</td>
						</tr>
						<tr>
							<th style="vertical-align:middle;"><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<input type="text" name="teacher_email" size="48" style="margin-top: 5px;width:400px;" value="<?=set_value('teacher_email',$teacher['teacher_email'])?>">
								<?php
								if(isset($teacher['lock']) && $teacher['lock']==1){
									print('<span style="color:red;">アカウントロック中</span>');
								}
								?>
							</td>
						</tr>
						<?php if($teacher['update_flg']!=0){ ?>
							<tr>
								<th style="vertical-align:middle;"><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
								<td>
									<input type="button" id="changebutton" value="<?=(set_value('teacher_password_change',$teacher['teacher_password_change'])=='0')? $this->lang->line_or_def('msg_change_password','パスワードを変更する') : $this->lang->line_or_def('msg_not_change_password','パスワードを変更しない')?>" onclick="dspmsg()" style="margin-bottom: 5px;">
								</td>
							</tr>
							<tr>
								<th><?= $this->lang->line_or_def('common_old_password','現在のパスワード') ?></th>
								<td>
									<input type="password" name="teacher_password_old" size="48"  <?=(set_value('teacher_password_change',$teacher['teacher_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?> value="<?=set_value('teacher_password_old',$teacher['teacher_password_old'])?>">
								</td>
							</tr>
							<tr>
								<th><?= $this->lang->line_or_def('common_new_password','新パスワード') ?></th>
								<td>
									<input type="password" name="teacher_password" size="48"  <?=(set_value('teacher_password_change',$teacher['teacher_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?> value="<?=set_value('teacher_password',$teacher['teacher_password'])?>">
								</td>
							</tr>
							<tr>
								<th><?= $this->lang->line_or_def('common_new_password_conf','新パスワード（確認入力）') ?></th>
								<td>
									<input type="password" name="teacher_password_check" size="48"  <?=(set_value('teacher_password_change',$teacher['teacher_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?> value="<?=set_value('teacher_password_check',$teacher['teacher_password_check'])?>">
								</td>
							</tr>
						<?php } else { ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
								<td>
									<input type="password" name="teacher_password" size="48"  <?=(set_value('teacher_password_change',$teacher['teacher_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?> value="<?=set_value('teacher_password',$teacher['teacher_password'])?>">
								</td>
							</tr>
							<tr>
								<th><?= $this->lang->line_or_def('common_password_conf','パスワード（確認入力）') ?></th>
								<td>
									<input type="password" name="teacher_password_check" size="48"  <?=(set_value('teacher_password_change',$teacher['teacher_password_change'])=='0')? 'style="background-color: gainsboro;" disabled="disabled"':''?> value="<?=set_value('teacher_password_check',$teacher['teacher_password_check'])?>">
								</td>
							</tr>
						<?php } ?>
						<tr>
							<th style="vertical-align:middle;"><?= $this->lang->line_or_def('common_practice_authority','実行権限') ?></th>
							<td>
								<? //既存の権限を非表示 ?>
								<input type="hidden" name="a_school_admin" value='<?=set_value('a_school_admin', $teacher['a_school_admin'])?>'>
								<input type="hidden" name="a_cource_class" value='<?=set_value('a_cource_class', $teacher['a_cource_class'])?>'>
								<input type="hidden" name="a_student"      value='<?=set_value('a_student',      $teacher['a_student'])?>'>
								<input type="hidden" name="a_teacher"      value='<?=set_value('a_teacher',      $teacher['a_teacher'])?>'>
								<input type="hidden" name="a_material"     value='<?=set_value('a_material',     $teacher['a_material'])?>'>
								<input type="hidden" name="a_book_library" value='<?=set_value('a_book_library', $teacher['a_book_library'])?>'>
								<input type="hidden" name="a_video"        value='<?=set_value('a_video',        $teacher['a_video'])?>'>
								<input type="hidden" name="a_issue"        value='<?=set_value('a_issue',        $teacher['a_issue'])?>'>
								<input type="hidden" name="a_information"  value='<?=set_value('a_information',  $teacher['a_information'])?>'>
								<input type="hidden" name="a_report"       value='<?=set_value('a_report',       $teacher['a_report'])?>'>
								<input type="hidden" name="a_auth"         value='<?=set_value('a_auth',         $teacher['a_auth'])?>'>
								
								<select name="bar_association_id">
									<?php foreach($mtb_bar_association as $index => $val): ?>
										<?php
											$select_option = "";
											if( (isset($teacher['bar_association_id'])) && ($teacher['bar_association_id'] == $index) ){
												$select_option = "selected";
											}
											
											if($index == 1){
												$val .= "（管理者）";
											}else{
												$val .= "";
											}
										?>
										<option value="<?= htmlspecialchars( $index, ENT_QUOTES, 'UTF-8') ?>" <?= htmlspecialchars( $select_option, ENT_QUOTES, 'UTF-8') ?>><?= htmlspecialchars( $val, ENT_QUOTES, 'UTF-8') ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td>
								<textarea name="teacher_note"><?=set_value('teacher_note',$teacher['teacher_note'])?></textarea>
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
