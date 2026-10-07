<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "teacher";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_teacher/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_teacher/edit/";
		}
	</script>
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

				<h2>
					<?php 
						if( $teacher['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_teacher_confirm','講師情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_teacher_confirm','講師情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_teacher_detail','講師情報の詳細');
									break;
							}
						}
					?>
				</h2>
			<!--<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?> <?= htmlspecialchars( $teacher['update_flg'], ENT_QUOTES, 'UTF-8') ?></h2>-->

				<?=form_open("cms_teacher/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<?= htmlspecialchars( $teacher['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
							<th>
								<?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?>
							</th>
							<td>
								<?= htmlspecialchars( $teacher['teacher_email'], ENT_QUOTES, 'UTF-8') ?>
								<?php
								if(isset($teacher['lock']) && $teacher['lock']==1){
									print('<span style="color:red;">アカウントロック中</span>');
								}
								?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
							<td style="vertical-align:middle;">
								<?php if( ($teacher['update_flg'] == 0) || (($teacher['update_flg'] != 0) && ($teacher['teacher_password_change'] == 1)) || ($btn_kirikae_flg == 2) ): ?>
									******
									<?php if($teacher['teacher_password_identity'] == 1): ?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<font class="error">
											<?= $this->lang->line_or_def('msg_teacher_password_identity','※同じメールアドレスを持つ講師のパスワードも、全て同じにする') ?>
										</font>
									<?php endif; ?>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_no_modify','変更なし'); ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_practice_authority','実行権限') ?></th>
							<td >
								<?='';//$teacher['authname']?>
								<?php if(isset($teacher['bar_association_id'])): ?>
									<?php if( isset($mtb_bar_association[ $teacher['bar_association_id'] ]) ): ?>
										<?= '';//$mtb_bar_association[$teacher['bar_association_id']]; ?>
										<? if($teacher['bar_association_id']==1): ?>
											<?= htmlspecialchars( $mtb_bar_association[$teacher['bar_association_id']], ENT_QUOTES, 'UTF-8')."（管理者）"; ?>
										<? else: ?>
											<?= htmlspecialchars( $mtb_bar_association[$teacher['bar_association_id']], ENT_QUOTES, 'UTF-8').""; ?>
										<? endif; ?>
									<?php else: ?>
										<?= ''; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						
						<?= '';//$this->lang->line_or_def('common_self_introduction_summary', '自己紹介 概要') ?>
						<?= '';//$this->lang->line_or_def('common_self_introduction_detail',  '自己紹介 詳細') ?>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td style="word-break: break-all;"><?=nl2br( htmlspecialchars( $teacher['teacher_note'] ?? "", ENT_QUOTES, 'UTF-8')  ); ?></td>
						</tr>
					<!--
						<tr>
							<th style="vertical-align:middle;"><?= '';//$this->lang->line_or_def('common_photo','写真') ?></th>
							<td >
								<img src="<?='';//site_url('master_photo/thumbnail/'. htmlspecialchars( $teacher['teacher_id'], ENT_QUOTES, 'UTF-8') )?>" height="80" width="80" border="1">
							</td>
						</tr>
					 -->
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(". htmlspecialchars( $teacher['teacher_id'], ENT_QUOTES, 'UTF-8').");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png'>";
									break;
									
								case 2://詳細画面
									print '<a href="#" onClick='."'".'location.href = "'. htmlspecialchars( $teacher['history_back_url'], ENT_QUOTES, 'UTF-8').'";return false;'."'".' style="display:inline-block;margin: 0 15px;text-decoration: none;font-weight: bold;color:white; text-align:center;vertical-align: middle; width:80px; height:28px;background: url(/static/image/btn_gray.png) no-repeat;font-size:13px;line-height: 30px;">'.$this->lang->line_or_def('common_','一覧に戻る').'</a>';
								//	print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_teacher')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(". htmlspecialchars( $teacher['teacher_id'], ENT_QUOTES, 'UTF-8').',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
								//	print "<input type='image' src='/static/image/btn_photo.png' onClick='location.href = \"".site_url('cms_teacher/photo_upload/'. htmlspecialchars( $teacher['teacher_id'], ENT_QUOTES, 'UTF-8') )."\";return false;' />";
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
