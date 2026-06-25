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
				location.href = "<?=base_url()?>cms_student_group/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_student_group/edit/";
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
			<? $this->load->view('cms_student_group/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_student/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $student_group['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_student_group_confirm','受講者グループ情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_student_group_confirm','受講者グループ情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_student_group_detail','受講者グループ情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_student_group/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_group_name','グループ名') ?></th>
							<td>
								<?= htmlspecialchars( $student_group['student_group_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td ><?= nl2br( htmlspecialchars( $student_group['student_group_caption'], ENT_QUOTES, 'UTF-8') ) ?></td>
						</tr>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_student','受講者') ?>
								<div style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($student_group['position_students']); ?>]
								</div>
							</th>
							<td ><?= htmlspecialchars( $student_group['position_students_name'], ENT_QUOTES, 'UTF-8') ?></td>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$student_group['student_group_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_student_group')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$student_group['student_group_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";
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
