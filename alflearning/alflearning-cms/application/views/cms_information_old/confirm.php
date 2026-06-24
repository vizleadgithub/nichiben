<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "information";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_information/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_information/edit/";
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_information','お知らせ') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_information_comment','お知らせを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_information/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_information/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_information/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $information['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_information_confirm','お知らせ情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_information_confirm','お知らせ情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_information_detail','お知らせ情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_information/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_date','日付') ?></th>
							<td>
								<?=$information['information_date']?>
							</td>
						</tr>
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_title','タイトル') ?></th>
							<td>
								<?=$information['information_title']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td><?= nl2br($information['information_caption']); ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td ><?=$information['information_tags']?></td>
						</tr>

						<?php if($this->libauth->get_teacher_id() == -1): ?>
							<tr>
								<th><?= $this->lang->line_or_def('common_indication_school','表示対象の学校') ?></th>
								<td>
									<?php if($information['school_id'] > 0): ?>
										<?= $this->lang->line_or_def('common_school','学校').'&nbsp;"'.$this->libauth->get_school_name().'"&nbsp;'.$this->lang->line_or_def('common_123','のみ'); ?>
									<?php else: ?>
										<?= $this->lang->line_or_def('common_to_all_school','全ての学校') ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_display_target','表示対象') ?></th>
							<td >
									<?= ($information['show_teacher'] ? $this->lang->line_or_def('common_teacher','講師') : ''); ?>
									&nbsp;
									<?= ($information['show_student'] ? $this->lang->line_or_def('common_student','受講生') : ''); ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
							<td >
								<?=$information['information_open']?><?= $this->lang->line_or_def('common_range','～') ?><?=$information['information_close']?>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$information['information_id'].");return false' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_information')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$information['information_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'" '.  ");return false;' />";
									break;
							}
						?>
					</div>
				</form>
			</div>
		
		<div style="clear:left;height:0px;margin:0px;padding:0px;"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
