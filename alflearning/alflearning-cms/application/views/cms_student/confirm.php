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
		//	var flag = $("#sub_auth_ethic_training:checked").val();
			var flag = $("input:radio[name=sub_auth_ethic_training]:checked").val();
			location.href ="<?=base_url()?>cms_student/edit_sub_auth_ethic_training/" + flag;
		}
	</script>

	<style type="text/css">
	<!--
	#th_sub_auth_ethic_training{
		line-height		: 30px;
		vertical-align	: top;
	}
	.select_sub_auth{
		float		: left;
		line-height	: 30px;
		width		: 200px;
	}
	-->
	</style>
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
				<!--<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a> -->
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
							<th width="160"><?= $this->lang->line_or_def('common_','氏名') ?></th>
							<td>
								<?= htmlspecialchars( $student['student_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','登録番号') ?></th>
							<td>
								<?= htmlspecialchars( $student['lawyer_number'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_','会員区分') ?></th>
							<td>
								<?php if(isset($student['lawyer_division'])): ?>
									<?php if( isset($mtb_lawyer_division[$student['lawyer_division']]) ): ?>

										<?php if($mtb_lawyer_division[$student['lawyer_division']] == ''): ?>
											<?= 'その他'; ?><? //= '－'; ?>
										<?php else: ?>
											<?= htmlspecialchars( $mtb_lawyer_division[$student['lawyer_division']], ENT_QUOTES, 'UTF-8') ?>
										<?php endif; ?>

									<?php else: ?>
										<? //= '－'; ?>
										<?php if($student['lawyer_division'] == ''): ?>
											<?= '事務局'; ?>
										<?php else: ?>
											<?= 'その他'; ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
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
							<th><?= $this->lang->line_or_def('common_','登録年月日') ?></th>
							<td>
								<?= htmlspecialchars( $student['regist_date'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_','パスポートの有効期限') ?></th>
							<td>
								<? if($student['presence_passport']==1): ?>
									<?= htmlspecialchars( $student['exp_date_passport'], ENT_QUOTES, 'UTF-8') ?>
								<? else: ?>
									－
								<? endif; ?>
							</td>
						</tr>
						<tr>
							<th>
								<? if($student['presence_passport']==1): ?>
									<?= $this->lang->line_or_def('common_','パスポート料金') ?>
								<? else: ?>
									<?= $this->lang->line_or_def('common_','現在のパスポート料金') ?>
								<? endif; ?>
							</th>
							<td>
								<?= htmlspecialchars( $student['target_passport'], ENT_QUOTES, 'UTF-8') ?>
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
							<? $login_bar_association_id = $this->libauth->get_bar_association_id(); ?>
							<? if($login_bar_association_id == 1): ?>
								<th id="th_sub_auth_ethic_training"><?= $this->lang->line_or_def('common_','代替倫理研修権限') ?></th>
								<td>
									<div class="select_sub_auth">
										<input type="radio" name="sub_auth_ethic_training" id="sub_auth_ethic_training" value='0' <?=(set_value('sub_auth_ethic_training',$student['sub_auth_ethic_training'])=='0')?'checked':''?> >
										<?= $this->lang->line_or_def('common_','禁止') ?>
									</div>
									<div class="select_sub_auth">
										<input type="radio" name="sub_auth_ethic_training" id="sub_auth_ethic_training" value='1' <?=(set_value('sub_auth_ethic_training',$student['sub_auth_ethic_training'])=='1')?'checked':''?> >
										<?= $this->lang->line_or_def('common_','許可') ?>
									</div>
									<div style="clear:both;"></div>
								</td>
							<? else: ?>
								<th><?= $this->lang->line_or_def('common_','代替倫理研修権限') ?></th>
								<td><?= ($student['sub_auth_ethic_training']==1) ? '許可' : '禁止' ; ?></td>
							<? endif; ?>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$student['student_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print '<a href="#" onClick='."'".'location.href = "'.$student['history_back_url'].'";return false;'."'".' style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_gray.png) no-repeat;font-size:13px;line-height: 30px;">'.$this->lang->line_or_def('common_','一覧に戻る').'</a>';

									if($login_bar_association_id == 1){
										print '<a href="#" onClick="edit_item();return false;" style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_blue.png) no-repeat;font-size:13px;line-height: 30px;">'.$this->lang->line_or_def('common_','変更を登録').'</a> ';
									}
								  //print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_student')."\";return false;' />";
								  //print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$student['student_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";
								  //print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
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
