<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		

		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下 '本当に削除してもよろしいですか？'
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_class/delete_item/" + id;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_class/edit/";
		}

		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_student_item(class_id){
			if(!class_id){
				location.href ="<?=base_url()?>cms_class/edit_student/";
			}else{
				location.href ="<?=base_url()?>cms_class/edit_student/" + class_id;
			}
		}
		
		//------------------------------------------
		//資料表示・非表示切り替え[2012/08/08]
		//------------------------------------------
		flag = false;
		function dspmsg(){
			if(flag) {
				document.getElementById("changebutton").value= "<?= $this->lang->line_or_def('common_material_indication','資料表示') ?>";
				$("#material_lists").slideUp();
			}
			else{
				document.getElementById("changebutton").value= "<?= $this->lang->line_or_def('common_material_non_indication','資料非表示') ?>";
				$("#material_lists").slideDown();
			}
			flag = !flag;
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_comment','授業を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_list" href="/cms_cource_class/"><span><?= $this->lang->line_or_def('common_list','一覧') ?></span></a>
					<a class="btn_seach selected" href="/cms_class/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_class/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $class['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_class_confirm','授業情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_class_confirm','授業情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_class_detail','授業情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_class/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<?= htmlspecialchars( $class['cource_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_datetime','日時') ?></th>
							<td >
								<?= htmlspecialchars( $class['class_date'], ENT_QUOTES, 'UTF-8') ?>&nbsp;<?= htmlspecialchars( $class['class_opentime'], ENT_QUOTES, 'UTF-8') ?>&nbsp;<?= $this->lang->line_or_def('common_class_time','授業時間') ?>&nbsp;<?= date("H:i", strtotime($class['class_closetime']) - strtotime($class['class_opentime']) - 32400); ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_class_type','授業タイプ') ?></th>
							<td>
								<?
									switch($class['class_type']){
										case 'school':
											print $this->lang->line_or_def('common_nomal_class','一般授業');
											break;
										case 'auditor':
											print $this->lang->line_or_def('common_attend_class','聴講授業');
											break;
									}
								?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_class_name','授業名') ?></th>
							<td>
								<?= htmlspecialchars( $class['class_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td>
								<?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<th ><?= $this->lang->line_or_def('common_teacher','講師') ?></th>
							<td>
								<?= htmlspecialchars( $class['sub_teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td ><?=nl2br( htmlspecialchars( $class['class_caption'], ENT_QUOTES, 'UTF-8') )?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_student','受講者') ?></th>
							<td ><?= htmlspecialchars( $class['lecture_students_name'], ENT_QUOTES, 'UTF-8') ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_note','備考') ?></th>
							<td ><?=nl2br( htmlspecialchars( $class['class_note'], ENT_QUOTES, 'UTF-8') )?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_material','資料') ?>
							</th>
							<td style="word-wrap:break-word">
								<?php if( isset($class['class_material']) && count($class['class_material']) > 0 ): ?>
									<form name="myForm"><input type="button" id="changebutton" value="<?= $this->lang->line_or_def('common_material_indication','資料表示') ?>" onclick="dspmsg()"></form>
									<div id="material_lists" style="display:none;">
									<table style="padding-left: 0px; margin-left: 0px; width: 600px;">
										<tr style="border: 1px #808080 solid; border-style: none none solid none ;">
											<th style="padding-bottom: 2px; padding-top: 2px;color: #333333;"><?= $this->lang->line_or_def('common_1234','種類') ?></th>
											<th style="padding-bottom: 2px; padding-top: 2px;color: #333333;"><?= $this->lang->line_or_def('common_1234','資料名') ?></th>
											<th style="padding-bottom: 2px; padding-top: 2px;color: #333333;"><?= $this->lang->line_or_def('common_1234','作成日') ?></th>
										</tr>
										<?php foreach($class['class_material'] as $class_material) { ?>
											<tr style="border: 1px #808080 solid; border-style: none none solid none ;">
											<td style="padding-bottom: 4px; padding-top: 4px;">
												<?php if( $class_material['kinds'] === $this->lang->line_or_def('common_material','資料') ): ?>
													<?= htmlspecialchars( $class_material['kinds'], ENT_QUOTES, 'UTF-8')  ?>
												<?php else: ?>
													<a href="" onclick = "window.open('/file_container/get_class_material_thubmnail/<?= htmlspecialchars( $class_material['class_id'], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars( $class_material['teacher_id'], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars( $class_material['student_id'], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars( $class_material['class_material_id'], ENT_QUOTES, 'UTF-8') ?>/', 'imgwindow', 'width=846,height=624, menubar=no, toolbar=no, scrollbars=yes, location=no, status=no'); return false;">
													<?= htmlspecialchars( $class_material['kinds'], ENT_QUOTES, 'UTF-8') ?></a>
												<?php endif; ?>
											</td>
											<td style="padding-bottom: 4px; padding-top: 4px;"><?= htmlspecialchars( $class_material['material_logic_name'], ENT_QUOTES, 'UTF-8') ?></td>
											<td style="padding-bottom: 4px; padding-top: 4px;"><?= htmlspecialchars( $class_material['update_at'], ENT_QUOTES, 'UTF-8') ?></td>
										</tr>
										<?php } ?>
									</table>
									</div>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_no_material','資料なし') ?>
								<?php endif; ?>
							</td>
						</tr>
					</table>
<!--
					<h3><a href="http://<?= $this->config->item('domain_name_master'); ?>/login/set_session/<?= $this->session->userdata('session_id'); ?>/?url=<?= urlencode('http://' . $this->config->item('domain_name_master') . '/live/in/'.$class['class_id'].'/'); ?>"><?= $this->lang->line_or_def('common_begin_class','教室に入る') ?></a></h3>
-->
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									if(strtotime($class['class_date'] .' '. $class['class_opentime']) > time()){
										print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$class['class_id'].");return false;' />";
									}else{
										print "<input type='image' src='/static/image/btn_back.png' onClick='edit_student_item(".$class['class_id'].");return false;' />";
									}
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_class')."\";return false;' />";
									if(strtotime($class['class_date'] .' '. $class['class_opentime']) > time()){	//ちゃんとcontrollerでもチェックすること
										print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$class['class_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'" '.  ");return false;' />";
										print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
									}else{
										print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_student_item();return false;' />";
									}
									print "<input type='image' src='/static/image/btn_textbook.png' onClick='location.href = \"".site_url('cms_class_material/material_select/'.$class['class_id'])."\";return false;' />";
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
