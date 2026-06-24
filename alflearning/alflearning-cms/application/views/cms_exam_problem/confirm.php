<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 解答内容（選択系） - ul  */
		#answer_contents_choice_area LI{
			border-color  : #808080; 
			border-image  : none; 
			border-style  : none none solid; 
			border-width  : 1px;
		}
			#answer_contents_choice_area LI .div_word{
				float       : left; 
				word-break  : break-all;
				padding     : 6px 0 6px 0;
				width       : 500px;
			}
			#answer_contents_choice_area LI .div_correct{
				float        : left; 
				padding      : 6px 0 6px 10px;
			/*	line-height  : 18px; 
				*/
			}
	</style>
	<script type="text/javascript">
		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
		});
		
		//**************************************************
		//詳細確認画面　修正ボタン押下
		//**************************************************
		function edit_item(){
			location.href ="<?=base_url()?>cms_exam_problem/edit/";
		}
		//**************************************************
		//詳細確認画面　複製して新規登録ボタン押下
		//**************************************************
		function copy_newdata_item(id){
			location.href ="<?=base_url()?>cms_exam_problem/newdata/" + id;
		}
		//**************************************************
		//詳細確認画面　削除ボタン押下
		//**************************************************
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_exam_problem/delete_item/" + id ;
			}
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam','問題管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam_comment','問題（テスト）を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam_problem/_submenu', array(
				'selected'	=> 'exam_problem',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam_problem/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam_problem/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $exam_problem['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_exam_problem_confirm','設問情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_exam_problem_confirm','設問情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_exam_problem_detail','設問情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_exam_problem/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam_problem_name','設問名') ?></th>
							<td>
								<?=$exam_problem['exam_problem_name']; ?>
							</td>
						</tr>

<?php if(false){ ?>
						<? if( getenv('URL_SERVICE')!='mitemo' ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td ><?= $exam_problem['teacher_name']; ?>
							</td>
						</tr>
						<? endif; ?>
<?php } ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($exam_problem['exam_problem_lectures_name']) ) {
										foreach( $exam_problem['exam_problem_lectures_name'] as $name) { 
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
						<?php //詳細画面時のみ表示 ?>
						<?php if($btn_kirikae_flg === 2): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_exam_problem_group','設問グループ') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($exam_problem_group_id) ) {
										foreach( $exam_problem_group_id as $id) { 
											if($flg){	?>
												,
											<?php } ?>
											<?=$exam_problem_groups_dropdown[$id]?>
										<?php
											$flg = TRUE;
										}
									}
								?>
							</td>
						</tr>
						<?php endif; ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_problem_kind','設問種類') ?></th>
							<td >
								<?php if($exam_problem['problem_kind'] == 1): ?>
									<?= $this->lang->line_or_def('common_text'         , 'テキスト'); ?>
								<?php elseif($exam_problem['problem_kind'] == 2): ?>
									<?= $this->lang->line_or_def('common_video'        , 'ビデオ'); ?>
								<?php elseif($exam_problem['problem_kind'] == 3): ?>
									<?= $this->lang->line_or_def('common_book_library' , '図書室'); ?>
								<?php else: ?>
									<td></td>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_problem_contents','設問内容') ?></th>
							<?php if($exam_problem['problem_kind'] == 1): ?>
								<td style="word-break: break-all;"><?= nl2br($exam_problem['problem_contents_text']); ?></td>
							<?php elseif($exam_problem['problem_kind'] == 2): ?>
								<td><?=$exam_problem['problem_contents_video_name']; ?></td>
							<?php elseif($exam_problem['problem_kind'] == 3): ?>
								<td><?=$exam_problem['problem_contents_book_library_name']; ?></td>
							<?php else: ?>
								<td></td>
							<?php endif; ?>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_problem_note','設問備考') ?></th>
								<td style="word-break: break-all;"><?= nl2br($exam_problem['problem_note']); ?></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_answer_kind','解答種類') ?></th>
							<td >
								<?php if($exam_problem['answer_kind'] == 1): ?>
									<?= $this->lang->line_or_def('common_single_forms', '単一形式'); ?>
								<?php elseif($exam_problem['answer_kind'] == 2): ?>
									<?= $this->lang->line_or_def('common_plural_forms', '複数形式'); ?>
								<?php elseif($exam_problem['answer_kind'] == 3): ?>
									<?= $this->lang->line_or_def('common_free_exam_answer', 'フリー回答'); ?>
								<?php else: ?>
									<td></td>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th style="vertical-align: top;"><?= $this->lang->line_or_def('common_answer_contents','解答内容') ?></th>
							<?php if( ($exam_problem['answer_kind'] == 1) || ($exam_problem['answer_kind'] == 2) ): ?>
								<td>

									<ul id="answer_contents_choice_area">
										
										<?php foreach($exam_problem['answer_contents_no'] as $ino => $answer_contents_no): ?>
											<li>
												<div class="div_word">
													<?= nl2br($exam_problem['answer_contents_word'][$ino]); ?>
												</div>
												<div class="div_correct">
													<?php if($exam_problem['answer_contents_correct'][$ino] == 1): ?>
														<?= $this->lang->line_or_def('common_correct_answer'  , '正解'); ?>
													<?php else: ?>
														--
													<?php endif; ?>
												</div>
												<div style="clear:both;"></div>
											</li>
										<?php endforeach; ?>
									</ul>
								</td>
							<?php elseif($exam_problem['answer_kind'] == 3): ?>
								<td style="word-break: break-all;"><?= nl2br($exam_problem['answer_contents_text']); ?></td>
							<?php else: ?>
								<td></td>
							<?php endif; ?>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?></th>
							<td ><?= $exam_problem['answer_point']; ?>
							</td>
						</tr>
						
						<tr>
							<th><?= $this->lang->line_or_def('common_answer_explain_kind','解答解説種類') ?></th>
							<td >
								<?php if($exam_problem['answer_explain_kind'] == 1): ?>
									<?= $this->lang->line_or_def('common_text'         , 'テキスト'); ?>
								<?php elseif($exam_problem['answer_explain_kind'] == 2): ?>
									<?= $this->lang->line_or_def('common_video'        , 'ビデオ'); ?>
								<?php elseif($exam_problem['answer_explain_kind'] == 3): ?>
									<?= $this->lang->line_or_def('common_book_library' , '図書室'); ?>
								<?php elseif($exam_problem['answer_explain_kind'] == 9): ?>
									<?= $this->lang->line_or_def('common_nothing' , 'なし'); ?>
								<?php else: ?>
									<td></td>
								<?php endif; ?>
							</td>
						</tr>
						
						<?php // 上記解答解説が 9:なし 以外のみ、表示 ?>
						<?php if($exam_problem['answer_explain_kind'] != 9): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_answer_explain_contents','解答解説内容') ?></th>
							<?php if($exam_problem['answer_explain_kind'] == 1): ?>
								<td style="word-break: break-all;"><?= nl2br($exam_problem['answer_explain_contents_text']); ?></td>
							<?php elseif($exam_problem['answer_explain_kind'] == 2): ?>
								<td><?=$exam_problem['answer_explain_contents_video_name']; ?></td>
							<?php elseif($exam_problem['answer_explain_kind'] == 3): ?>
								<td><?=$exam_problem['answer_explain_contents_book_library_name']; ?></td>
							<?php else: ?>
								<td></td>
							<?php endif; ?>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_answer_explain_note','解答解説備考') ?></th>
								<td style="word-break: break-all;"><?= nl2br($exam_problem['answer_explain_note']); ?></td>
						</tr>
						<?php endif; ?>
						
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_exam_problem')."\";return false;' />";

									$visible_edit_delete = ' style="visibility: hidden;" ';
									if($exam_problem_edit_delete_flag > 0) $visible_edit_delete = ' ';

									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' ".$visible_edit_delete."/>";
									print "<a href='#' onClick='copy_newdata_item(".$exam_problem['exam_problem_id'].");return false;' id='btn_blue_long_button'>".$this->lang->line_or_def('common_reproduce_new_registration','複製して新規登録')."</a>";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$exam_problem['exam_problem_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' ".$visible_edit_delete."/>";
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
