<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 設問 ・ 解答 */
		.confirm_ul_detail{
			overflow-y : scroll;
			width      : 610px;    /* 640px; */
			min-height : 200px;
		}
			.confirm_ul_li_div{
				float   : left;
				padding : 5px;
			}
			.confirm_ul_li_line{
				-moz-border-bottom-colors : none;
				-moz-border-left-colors   : none;
				-moz-border-right-colors  : none;
				-moz-border-top-colors    : none;
				border-color              : #808080;
				border-image              : none;
				border-style              : none none solid none;
				border-width              : 1px;
			}
				.confirm_ul_li_div_detail{
					float      : left;
					padding    : 5px;
					line-height: 24px;
				}
				.detail_area{
				/*  display:none; */
					margin: 0 0 0 10px;
				}
					.confirm_ul_li_line_dotted{
						-moz-border-bottom-colors : none;
						-moz-border-left-colors   : none;
						-moz-border-right-colors  : none;
						-moz-border-top-colors    : none;
						border-color              : #808080;
						border-image              : none;
						border-style              : dotted none none none ;
						border-width              : 1px;
					}
						.confirm_ul_li_line_dotted .detail_title{
							float       : left;
							line-height : 20px;
							margin      : 5px 0 0 0;
							width       : 150px;
							color       : #999999;  /*  */
						}
						.confirm_ul_li_line_dotted .detail_values{
							float       : left;
							line-height : 20px;
							margin      : 5px 0 0 0;
							width       : 400px;
							word-break  : break-all;
							color       : #999999;  /*  */
						}
						.confirm_ul_li_line_dotted .detail_answer{
							<? //style="float:left;width:250px;margin:5px 0 5px 300px;  line-height: 20px;   " ?>
							float       : right;
							line-height : 20px;
							margin      : 5px 46px 5px 0;
						}
							.confirm_ul_li_line_dotted .detail_answer .exam_answer_mark{
								float  : left;
								margin : 0 0 0 0;
								
								width: 100px;  /*  */
							}
							.confirm_ul_li_line_dotted .detail_answer .exam_answer_point{
								float  : left;
								margin : 0 0 0 30px;
								
								width: 160px;  /*  */
							}
							.confirm_ul_li_line_dotted .detail_answer input[type="text"]{
								height     : 20px;
								text-align : right;
								width      : 70px;
							}
	</style>
	<script type="text/javascript">
		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
		});
		
		//**************************************************
		//詳細確認画面　戻るボタン押下
		//**************************************************
		function back_detail(id){
			// フォームの生成
			var form = document.createElement("form");
			form.setAttribute("action", "<?=base_url()?>cms_exam/detail/" + id + '/');
			form.setAttribute("method", "post");
			form.style.display = "none";
			document.body.appendChild(form);

			// CSRF token
			var csrf = document.createElement('input');
			csrf.setAttribute('type', 'hidden');
			csrf.setAttribute('name', '<?= $this->security->get_csrf_token_name() ?>');
			csrf.setAttribute('value', '<?= $this->security->get_csrf_hash() ?>');
			form.appendChild(csrf);

			// パラメタの設定
			$("#exam_answer_ul LI").map(function() {
				var exam_answer_data  = '';  // 解答ID・正誤（正解:1、不正解:0）・解答配点
				var li_kind_flag      = 0;
				
				// class名により処理切り分け（詳細の方の情報が必要）
				if( $(this).attr('class') == 'confirm_ul_li_line'){
					// 解答の概要の方
				}
				if( $(this).attr('class') == 'confirm_ul_li_line_dotted'){
					// 解答の詳細の方
					exam_answer_data  = $(this).children("[name='exam_answer_data[]']").val();
					li_kind_flag      = 1;
				}
				
				if(li_kind_flag==1){
					var input = document.createElement('input');
					input.setAttribute('type', 'hidden');
					input.setAttribute('name',  'exam_answer_data[]');
					input.setAttribute('value', exam_answer_data);
					form.appendChild(input);
				}
			});
			form.submit();
		}


		//------------------------------------------
		//詳細確認画面　評価登録ボタン押下
		//------------------------------------------
		function detail_item_eval(id){
			// フォームの生成
			var form = document.createElement("form");
			form.setAttribute("action", "<?=base_url()?>cms_issue/detail/" + id + '/');
			form.setAttribute("method", "post");
			form.style.display = "none";
			document.body.appendChild(form);

			// CSRF token
			var csrf = document.createElement('input');
			csrf.setAttribute('type', 'hidden');
			csrf.setAttribute('name', '<?= $this->security->get_csrf_token_name() ?>');
			csrf.setAttribute('value', '<?= $this->security->get_csrf_hash() ?>');
			form.appendChild(csrf);

			// パラメタの設定
			$("#cource_ul li").map(function() {
			//	eval_data = $(this).children("input:hidden").val();
				eval_data = $(this).children("[name='eval_data[]']").val();
				var input = document.createElement('input');
				input.setAttribute('type', 'hidden');
				input.setAttribute('name',  'eval_data[]');
				input.setAttribute('value', eval_data);
				form.appendChild(input);
			});
			form.submit();
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
			<? $this->load->view('cms_exam/_submenu', array(
				'selected'	=> 'exam',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php
						print $this->lang->line_or_def('msg_exam_answer_update_regist_confirm','解答修正登録の確認');
					?>
				</h2>
				
				<? $exam_answer_update_flag = 0; ?>
				
				<?=form_open("cms_exam/exam_answer_update_commit")?>
					<input type="hidden" name="exam_id" value='<?=set_value('exam_id', $exam['exam_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam_name','問題（テスト）名') ?></th>
							<td>
								<?= htmlspecialchars( $exam['exam_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td ><?= htmlspecialchars( $exam['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<th style="text-align: left;vertical-align: top;">
								<?= $this->lang->line_or_def('common_exam_answer','解答') ?>
							</th>
							<td>
								<?php //if(!empty($exam['answer_date'][0])): ?>
								<?php if( array_sum($exam['exam_answer_show_flag']) > 0): ?>
								<ul>
									<li>
										<div class="confirm_ul_li_div" style="width:150px;"><?= $this->lang->line_or_def('common_exam_answer_datetime','解答日時') ?></div>
										<div class="confirm_ul_li_div" style="width:230px;"><?= $this->lang->line_or_def('common_student_name','受講者名') ?></div>
										<div class="confirm_ul_li_div" style="width:150px;"><?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?></div>
										<div style="clear:both;"></div>
									</li>
								</ul>
								<div class="confirm_ul_detail">
									<ul id="exam_answer_ul">
										<?php foreach($exam['answer_date'] as $id => $answer_date): ?>
											<?
												if($exam['exam_answer_show_flag'][$id] == 0){
													continue;
												}else{
													$exam_answer_update_flag = 1;
												}
											?>
											<li class="confirm_ul_li_line" <?= ($exam['exam_answer_latest_flag'][$id]==0) ? 'name="not_latest"' : 'name="latest"' ; ?>>
												<input type="hidden" name="answer_no[]" value='<?= htmlspecialchars( $exam['answer_no'][$id], ENT_QUOTES, 'UTF-8') ?>' />
												<div class="confirm_ul_li_div_detail" style="width:150px;"><?= htmlspecialchars( $exam['answer_date'][$id], ENT_QUOTES, 'UTF-8') ?></div>
												<div class="confirm_ul_li_div_detail" style="width:230px;"><?= htmlspecialchars( $exam['answer_student_name'][$id], ENT_QUOTES, 'UTF-8') ?></div>
												<? if($exam['answer_point_total'][$id] != $exam['new_answer_point_total'][$id]): ?>
													<div class="confirm_ul_li_div_detail" style="width : 150px;">
														<?= htmlspecialchars( $exam['answer_point_total'][$id], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars( $exam['max_answer_point'], ENT_QUOTES, 'UTF-8') ?>
														-&gt;
														<?= htmlspecialchars( $exam['new_answer_point_total'][$id], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars( $exam['max_answer_point'], ENT_QUOTES, 'UTF-8') ?>
													</div>
												<? else: ?>
													<div class="confirm_ul_li_div_detail" style="width : 150px;color : #999999;">
														<?= htmlspecialchars( $exam['answer_point_total'][$id], ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars( $exam['max_answer_point'], ENT_QUOTES, 'UTF-8') ?>
													</div>
												<? endif; ?>
												<div style="clear:both;"></div>
												
												<div id="exam_answer_detail_<?= htmlspecialchars( $id, ENT_QUOTES, 'UTF-8') ?>" class="detail_area">
													<?php if(!empty($exam_answer[$id]['exam_answer_id'][0])): ?>
														<ul>
														<?php foreach($exam_answer[$id]['exam_answer_id'] as $id2 => $values): ?>
															<li class="confirm_ul_li_line_dotted">
																<input type="hidden" name="exam_answer_id[]" value='<?= htmlspecialchars( $exam_answer[$id]['exam_answer_id'][$id2], ENT_QUOTES, 'UTF-8') ?>' />
																<div class="detail_title">
																	<? $temp = $this->lang->line_or_def('common_exam_answer','解答') ?>
																	<?php if($exam_answer[$id]['answer_kind'][$id2]==1): ?>
																		<? $temp .= "[".$this->lang->line_or_def('common_single_forms','単一形式')."]"; ?>
																	<?php elseif($exam_answer[$id]['answer_kind'][$id2]==2): ?>
																		<? $temp .= "[".$this->lang->line_or_def('common_plural_forms','複数形式')."]"; ?>
																	<?php elseif($exam_answer[$id]['answer_kind'][$id2]==3): ?>
																		<? $temp .= "[".$this->lang->line_or_def('common_free_exam_answer','フリー回答')."]"; ?>
																	<?php endif; ?>
																	<?= $temp; ?>
																</div>
																<div class="detail_values">
																	<?= htmlspecialchars( $exam_answer[$id]['exam_answer_contents'][$id2], ENT_QUOTES, 'UTF-8') ?>
																</div>
																<div class="detail_answer">
																	<? if( ($exam_answer[$id]['new_exam_answer_mark'][$id2]>-1) && ($exam_answer[$id]['exam_answer_mark'][$id2] != $exam_answer[$id]['new_exam_answer_mark'][$id2]) ):?>
																		<div class="exam_answer_mark">
																			<?= htmlspecialchars( $exam_answer_mark_list[ $exam_answer[$id]['exam_answer_mark'][$id2] ], ENT_QUOTES, 'UTF-8') ?>
																			-&gt;
																			<?= htmlspecialchars( $exam_answer_mark_list[ $exam_answer[$id]['new_exam_answer_mark'][$id2] ], ENT_QUOTES, 'UTF-8') ?>
																		</div>
																	<? else: ?>
																		<div class="exam_answer_mark" style="color : #999999;"> 
																			<?= htmlspecialchars( $exam_answer_mark_list[ $exam_answer[$id]['exam_answer_mark'][$id2] ], ENT_QUOTES, 'UTF-8') ?>
																		</div>
																	<? endif;?>
																	
																	<? if( ($exam_answer[$id]['new_exam_answer_point'][$id2]>-1) && ($exam_answer[$id]['exam_answer_point'][$id2] != $exam_answer[$id]['new_exam_answer_point'][$id2]) ):?>
																		<div class="exam_answer_point">
																			<?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?>&nbsp;:&nbsp;
																			<?= htmlspecialchars( $exam_answer[$id]['exam_answer_point'][$id2], ENT_QUOTES, 'UTF-8') ?>
																			-&gt;
																			<?= htmlspecialchars( $exam_answer[$id]['new_exam_answer_point'][$id2], ENT_QUOTES, 'UTF-8') ?>
																		</div>
																	<? else: ?>
																		<div class="exam_answer_point" style="color: #999999;">
																			<?= $this->lang->line_or_def('common_exam_answer_points','解答配点') ?>&nbsp;:&nbsp;
																			<?= htmlspecialchars( $exam_answer[$id]['exam_answer_point'][$id2], ENT_QUOTES, 'UTF-8') ?>
																		</div>
																	<? endif;?>
																</div>
																<? 	
																	$exam_answer_data  = '';
																	$exam_answer_data .= $exam_answer[$id]['exam_answer_id'][$id2];
																	$exam_answer_data .= '/'.$exam_answer[$id]['new_exam_answer_mark'][$id2];
																	$exam_answer_data .= '/'.$exam_answer[$id]['new_exam_answer_point'][$id2];
																?>
																<input type="hidden" name="exam_answer_data[]" value='<?= htmlspecialchars( $exam_answer_data, ENT_QUOTES, 'UTF-8') ?>'> 
																<div style="clear:both;"></div>
															</li>
														<?php endforeach; ?>
														</ul>
													<?php else: ?>
													<?php endif; ?>
												</div>
												<div style="clear:both;"></div>

											</li>
										<?php endforeach; ?>
									</ul>
								</div>
								<?php else: ?>
									<?= $this->lang->line_or_def('common_no_exam_answer_update_regist','解答修正登録なし') ?>
								<?php endif; ?>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php
							// 詳細へ戻る（変更した内容を渡す）
							print "<input type='image' src='/static/image/btn_back.png' onClick='back_detail(".$exam['exam_id'].");return false;' />";
							// 解答修正登録の登録実行（解答修正がある場合のみボタン表示）
							if( array_sum($exam['exam_answer_show_flag']) > 0){
								print "<input type='image' src='/static/image/btn_ok.png' />";
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
