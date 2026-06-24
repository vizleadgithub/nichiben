<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "book_library";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_book_library/delete_item/" + id ;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_book_library/edit/";
		}
	</script>
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

				<h2>
					<?php 
						if( $book_library['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_book_library_confirm','本情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_book_library_confirm','本情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_book_library_detail','本情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_book_library/commit")?>
					<table class="form">
						<tr>
							<th width="120"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<td>
								<?php if($book_library['book_library_logic_name'] == ""): ?>
									<?= htmlspecialchars( $book_library['book_library_name'], ENT_QUOTES, 'UTF-8') ?>
								<?php endif; ?>
								<?php if($book_library['book_library_logic_name'] != ""): ?>
									<?= htmlspecialchars( $book_library['book_library_logic_name'], ENT_QUOTES, 'UTF-8') ?>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($book_library['book_library_lectures_name']) ) {
										foreach( $book_library['book_library_lectures_name'] as $name) { 
											if($flg){	?>
												,
											<?php } ?>
											<?= htmlspecialchars( $name, ENT_QUOTES, 'UTF-8') ?>
										<?php
											$flg = TRUE;
										}
									}
								?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
							<td ><?= htmlspecialchars( $book_library['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td ><?= nl2br( htmlspecialchars( $book_library['book_library_caption'], ENT_QUOTES, 'UTF-8') ) ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td ><?= htmlspecialchars( $book_library['book_library_tags'], ENT_QUOTES, 'UTF-8') ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_of_ipad','iPadのローカル閲覧') ?></th>
							<td >
								<?php if($book_library['local_reading_flag']=='0'): ?>
									<?= $this->lang->line_or_def('common_forbid','禁止') ?>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_admit','許可') ?>&nbsp;(<?= htmlspecialchars( $book_library['local_reading_open'], ENT_QUOTES, 'UTF-8') ?>&nbsp;<?= $this->lang->line_or_def('common_range','～') ?>&nbsp;
									<?php if($book_library['local_reading_close']==''): ?>
										<?= $this->lang->line_or_def('common_no_limit','期限なし') ?>)
									<?php else: ?>
										<?= htmlspecialchars( $book_library['local_reading_close'], ENT_QUOTES, 'UTF-8') ?>)
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th style="vertical-align: middle;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<td>
								<img src="<?= $this->config->item('stream_get_url')?>/school_<?= $this->session->userdata['cms_master.login.school_id'] ?>/book_library_<?= $book_library['book_library_id']; ?>/Page1/master-Page1.jpg?token=<?= $this->session->userdata('session_id') ?>" alt="" style="height: 200px; padding: 1px;background-color:black;"/>
							</td>
						</tr>

						<?php if($btn_kirikae_flg === 2): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_local_reading_history','閲覧履歴') ?></th>
							<td>
							<?php if(count($library_logs)>0): ?>
								<table>
									<tr>
										<th width="50px"><?= $this->lang->line_or_def('common_id','ID') ?></th>
										<th width="250px"><?= $this->lang->line_or_def('common_student_name','受講者名') ?></th>
										<th width="150px"><?= $this->lang->line_or_def('common_local_reading_date','閲覧日') ?></th>
									</tr>
								</table>
								<div style="overflow-y: scroll;width: 640px;height: 200px;">
								<table>
									<?php foreach($library_logs as $library_log) { ?>
									<tr style="border: 1px #808080 solid; border-style: none none solid none ;	">
									<td width="50px"><?= htmlspecialchars( $library_log['student_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td width="250px"><?= htmlspecialchars( $library_log['student_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td width="150px"><?= htmlspecialchars( $library_log['added_at'], ENT_QUOTES, 'UTF-8') ?></td>
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

					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$book_library['book_library_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_book_library')."\";return false;' />";
//print "<input type='image' src='/static/image/btn_download.png' onClick='location.href = \"".site_url('cms_book_library/download_file/'.$book_library['book_library_id'])."\";return false;' />";
									print "<input type='image' src='/static/image/btn_download.png' onClick='location.href = \"".$this->config->item('stream_get_url')."/school_".$this->session->userdata['cms_master.login.school_id']."/book_library_".$book_library['book_library_id']."/".$book_library['book_library_name']."?token=".$this->session->userdata('session_id')."\";return false;' />";

									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$book_library['book_library_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";

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
