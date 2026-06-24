<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "issue";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_issue/delete_item/" + id ;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_issue/edit/";
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_issue','課題管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_issue_comment','課題を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_issue/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_issue/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_issue/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $issue['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_issue_confirm','課題情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_issue_confirm','課題情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_issue_detail','課題情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_issue/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_issue_name','課題名') ?></th>
							<td>
								<?=$issue['issue_name']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td ><?= $issue['teacher_name'] ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($issue['issue_lectures_name']) ) {
										foreach( $issue['issue_lectures_name'] as $name) { 
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
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td ><?= nl2br($issue['issue_caption']); ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_submit_period','提出期間') ?></th>
							<td >
								<?= $issue['issue_open'] ?><?= $this->lang->line_or_def('common_range','～') ?><?= $issue['issue_close'] ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_indication_status','表示状態') ?></th>
							<td >
								<?php if($issue['public_flag'] == 0){ ?>
									<?= $this->lang->line_or_def('common_public','公開'); ?>
								<?php }elseif($issue['public_flag'] == 9){ ?>
									<?= $this->lang->line_or_def('common_non_public','非公開'); ?>
								<?php }else{ ?>
									<td>-</td>
								<?php } ?>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_model','雛形') ?></th>
							<td >
								<?php if(!empty($issue['issue_temp_logic_name'][0])): ?>
									<table style="width:100%">
										<tr>
											<th style="backgrand-color:red;"><?= $this->lang->line_or_def('common_model_logic_name','雛形論理名') ?></th>
											<th style="backgrand-color:red;vertical-align: middle;text-align: center;"><?= $this->lang->line_or_def('common_model_file','雛形ファイル') ?></th>
										</tr>
										<?php foreach($issue['issue_temp_logic_name'] as $id => $issue_temp_logic_name): ?>
											<?php if(!empty($issue_temp_logic_name)): ?>

												<tr style="border-top:#999999 1px solid;">
													<td style="vertical-align: middle;text-align: left;" >
														<?= $issue_temp_logic_name  ?>
													</td>
													<td style="vertical-align: middle;text-align: center;">
						<img src="/file_container/get_issue_template_thubmnail/<?= $this->libauth->get_school_id(); ?>/<?= $issue['issue_id']; ?>/<?= $issue['issue_temp_id'][$id]; ?>/<?= $issue['issue_temp_name'][$id]; ?>/" alt="" style="height: 64px; padding:1px;background-color:white;border-color:#aaaaaa;border-width:1px;border-style:solid;"/ alt="image">
													</td>
												</tr>
											<?php endif; ?>
										<?php endforeach; ?>
									</table>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<th style="text-align: left;vertical-align: top;"><?= $this->lang->line_or_def('common_submission','提出物') ?></th>
							<td >
								<?php if(!empty($issue['issue_submit_date'][0])): ?>
									<div style="float: left; width: 85px; margin-left: 5px;"><?= $this->lang->line_or_def('common_submission_datetime','提出日時') ?></div>
									<div style="float: left; width: 120px;"><?= $this->lang->line_or_def('common_student_name','受講者名') ?></div>
									<div style="float: left; width: 150px;"><?= $this->lang->line_or_def('common_submission','提出物') ?></div>
									<div style="float: left; width: 220px;"><?= $this->lang->line_or_def('common_message','メッセージ') ?></div>
									<div style="clear:both;"></div>
									<div style="max-height: 300px; overflow-y: scroll;">
										
										<ul class="list" id="cource_ul">
											<?php foreach($issue['issue_submit_date'] as $id => $issue_submit_date): ?>
												<li style="margin-left: 0px;">
													<div style="float: left; width: 85px;"><?= $issue_submit_date; ?></div>
													<div style="float: left; width: 120px;"><?= $issue['issue_submit_student_name'][$id]; ?></div>
													<div style="float: left; width: 150px;">
														<a href="<?= $this->config->item('stream_get_url'); ?>/school_<?= $this->session->userdata['cms_master.login.school_id']; ?>/issue_<?= $issue['issue_id']; ?>/student_<?= $issue['issue_submit_student_id'][$id]; ?>/issue_submit_<?= $issue['issue_submit_id'][$id]; ?>/<?= $issue['issue_submit_name'][$id]; ?>?token=<?= $this->session->userdata('session_id'); ?>" target="_blank"><?= $issue['issue_submit_logic_name'][$id]; ?></a>
													</div>
													<div style="float: left; width: 220px;"><?= nl2br($issue['issue_submit_caption'][$id]); ?></div>
													<div style="clear:both;">
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_no_submission','提出物なし') ?>
								<?php endif; ?>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$issue['issue_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_issue')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$issue['issue_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";

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
