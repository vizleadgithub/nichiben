<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_cource/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_cource/edit/";
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course','講座管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_course_comment','講座を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_cource/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach" href="/cms_cource/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add selected" href="/cms_cource/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
				<?php 
					if( $cource['update_flg'] == 0 ){
						print $this->lang->line_or_def('msg_course_confirm','講座情報の確認');
					}else{
						switch($btn_kirikae_flg){
							case 1://修正画面 "確認"
								print $this->lang->line_or_def('msg_course_confirm','講座情報の確認');
								break;
								
							case 2://詳細画面 "詳細"
								print $this->lang->line_or_def('msg_course_detail','講座情報の詳細');
								break;
						}
					}
				?>
			</h2>

			<?=form_open("cms_cource/commit")?>
				<table class="form"> <!--  style="table-layout: fixed;" -->
					<tr>
						<th width="160px"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
						<td >
							<?= htmlspecialchars( $cource['cource_name'], ENT_QUOTES, 'UTF-8') ?>
						</td>
					<tr>
						<th><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
						<td >
							<?= htmlspecialchars( $cource['cource_open'], ENT_QUOTES, 'UTF-8') ?><?= $this->lang->line_or_def('common_range','～') ?><?= htmlspecialchars( $cource['cource_close'], ENT_QUOTES, 'UTF-8') ?>
						</td>
					</tr>
					<tr>
						<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
						<td ><?= nl2br( htmlspecialchars( $cource['cource_caption'], ENT_QUOTES, 'UTF-8') ) ?></td>
					</tr>
					<tr>
						<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
						<td ><?= nl2br( htmlspecialchars( $cource['cource_note'], ENT_QUOTES, 'UTF-8') ) ?></td>
					</tr>

					<tr>
						<th>
							<?= $this->lang->line_or_def('common_student','受講者') ?>
							<div style="text-align:center;">[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_students']); ?>]</div>
						</th>
						<td ><?= htmlspecialchars( $cource['lecture_students_name'], ENT_QUOTES, 'UTF-8') ?></td>
					</tr>

<? $user_auths = $this->session->userdata; ?>
<? $contract_param = $this->libauth->get_login_school_contract_param($user_auths["cms_master.login.school_id"]); ?>
<? if( (isset($contract_param['live'])) && ($contract_param['live']['contract']==='fixation') ): ?>
					<tr>
						<th>
							<?= $this->lang->line_or_def('common_material','資料') ?>
							<div style="text-align:center;">[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_materials']); ?>]</div>
						</th>
						<td ><?= htmlspecialchars( $cource['lecture_materials_name'], ENT_QUOTES, 'UTF-8') ?></td>
					</tr>
<? endif; ?>

<? if( (isset($contract_param['book_library'])) && ($contract_param['book_library']['contract']==='fixation') ): ?>
					<tr>
						<th>
							<?= $this->lang->line_or_def('common_book_library','図書室') ?>
							<div style="text-align:center;">[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_book_librarys']); ?>]</div>
						</th>
						<td ><?= htmlspecialchars( $cource['lecture_book_librarys_name'], ENT_QUOTES, 'UTF-8') ?></td>
					</tr>
<? endif; ?>

<? if( (isset($contract_param['video'])) && ($contract_param['video']['contract']==='fixation') ): ?>
					<tr>
						<th>
							<?= $this->lang->line_or_def('common_video','ビデオ') ?>
							<div style="text-align:center;">[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($cource['lecture_videos']); ?>]</div>
						</th>
						<td ><?= htmlspecialchars( $cource['lecture_videos_name'], ENT_QUOTES, 'UTF-8') ?></td>
					</tr>
<? endif; ?>

				</table>
				<div class="submit">
					<?php
						switch($btn_kirikae_flg){
							case 1://修正画面
								print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$cource['cource_id'].");return false;' />";
								print "<input type='image' src='/static/image/btn_ok.png' />";
								break;
								
							case 2://詳細画面
								print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_cource')."\";return false;' />";
								//print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$cource['cource_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";
								print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
								break;
						}
					?>
				</div>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
