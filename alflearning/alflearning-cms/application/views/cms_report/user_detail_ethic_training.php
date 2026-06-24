<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "report";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
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

	#user_detail_table{
		margin-top			: 5px;
		width				: 790px;
		border-collapse		: collapse;
		border-spacing		: 0;
	}
	#user_detail_table TD{
		line-height	: 26px;
	}
	
	.on_select_button{
		display			: inline-block;
		margin			: 0 15px;
		text-decoration	: none;
		font-weight		: bold;
		color			: #FFFFFF;
		text-align		: center;
		vertical-align	: middle;
		width			: 120px;
		height			: 28px;
		background		: url("/static/image/btn_red12028.png") no-repeat;
		font-size		: 13px;
		line-height		: 30px;
		background-size	: 120px 28px;
	}
	.on_select_button:hover, .on_select_button:active{
		color			: #FFFFFF;
	}
	.off_select_button{
		display			: inline-block;
		margin			: 0 15px;
		text-decoration	: none;
		font-weight		: bold;
		color			: #FFFFFF;
		text-align		: center;
		vertical-align	: middle;
		width			: 120px;
		height			: 28px;
		background		: url("/static/image/btn_blue12028.png") no-repeat;
		font-size		: 13px;
		line-height		: 30px;
		background-size	: 120px 28px;
	}
	.off_select_button:hover, .off_select_button:active{
		color			: #FFFFFF;
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
			<div class="title"><?= $this->lang->line_or_def('common_heading_report','レポート') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_report_comment','月毎の集計レポートを表示します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_report/_submenu', array(
				'selected'	=> 'cms_user',
			)); ?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_report/cms_user/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				<!--<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a> -->
				</div>

				<h2>
					<?php
						print $this->lang->line_or_def('msg_student_confirm','受講者情報の詳細');
					?>
				</h2>
				
				<? // 画面上部、受講者詳細 ?>
				<table id="user_detail_table">
					<tr style="">
						<td><?= $this->lang->line_or_def('common_','氏名') ?></td>
						<td><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></td>
						<td><?= $this->lang->line_or_def('common_','登録番号') ?></td>
						<td><?= $this->lang->line_or_def('common_','FP') ?></td>
						<td><?= $this->lang->line_or_def('common_','登録年月日') ?></td>
						<td><?= $this->lang->line_or_def('common_','所属弁護士会') ?></td>
					</tr>
					<tr style="background: none repeat scroll 0 0 #F6F6F3;">
						<td><?=$student['student_name']?></td>
						<td><?=$student['student_email']?></td>
						<td><?=$student['lawyer_number']?></td>
						<td><?= ($student['presence_passport']==1) ? '○' : '－' ; ?></td>
						<td><?=$student['regist_date']?></td>
						<td>
							<?php if(isset($student['bar_association_id'])): ?>
								<?php if( isset($mtb_bar_association[$student['bar_association_id']]) ): ?>
									<?= $mtb_bar_association[$student['bar_association_id']]; ?>
								<?php else: ?>
									<?= ''; ?>
								<?php endif; ?>
							<?php endif; ?>
						</td>
					</tr>
				</table>
				<br/>
				
				<? // 画面中部、各種ボタン ?>
				<div class="submit">
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/0" class="off_select_button"><?= $this->lang->line_or_def('common_','e-ラーニング'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/1" class="off_select_button"><?= $this->lang->line_or_def('common_','ライブ実務'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/2" class="off_select_button"><?= $this->lang->line_or_def('common_','日弁連以外主催'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/3" class="on_select_button "><?= $this->lang->line_or_def('common_','倫理研修'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/4" class="off_select_button"><?= $this->lang->line_or_def('common_','全て'); ?></a>
				</div>
				<br />
				
				<? // 画面中部、ページング ?>
				<? if($total_rows > 0): ?>
					<div style="height: 30px;line-height: 30px;text-align: center;">
						<?=$start_rows;?>～<?=$end_rows;?>件を表示中（全<?=$total_rows;?>件）
					</div>
				<? endif; ?>

				<? // 画面下部、一覧 ?>
				<table class="list">
					<tr>
						<th style=""><?= $this->lang->line_or_def('common_','ID') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','講座名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','研修動画') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','テスト') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','解説動画') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','合否') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($show_record)) { ?>
						<?php foreach($show_record as $db_record) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<td class="tdc">
									<? 
										// [ID]
										print $db_record['product_id'];
									?>
								</td>
								<td class="tdc">
									<?
										// [講座名]
										print $db_record['product_name'];
									?>
								</td>
								<td class="tdc">
									<?
//print "[".$db_record['all_training_video_viewed_count']."]";
//print "[".$db_record['all_training_video_count']."]";
										// [研修動画]
										if( ($db_record['all_training_video_viewed_count']) && ($db_record['all_training_video_count']) ){
											$per = round( ($db_record['all_training_video_viewed_count'] / $db_record['all_training_video_count']) * 100 );
											print $this->lang->line_or_def('common_','研修動画').'：'.$per.'％';
										}else{
											print '研修動画：0％';
										}
									?>
								</td>
								<td class="tdc">
									<?
										// [テスト]
										// status に応じて表示分数の分母が確定する
										$denom_1 = 0;			// テスト 項目に使用する分母値
										$denom_2 = 0;			// テスト 項目に使用する分母値
										$denom_3 = 0;			// 解説動画 項目に使用する分母値
										$status_message = '－';	// 合否 に表示する文言
										if($db_record['product_type_add']=="2"){
											switch ($db_record['participation_flg']) {
												case 0:
													$denom_1 = 0;
													$denom_2 = 0;
													$denom_3 = 0;
													$status_message = $this->lang->line_or_def('common_','会場');
													break;
												case 1:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = 0;
													$denom_3 = $db_record['question_count1'] ?? 0;
													$status_message = $this->lang->line_or_def('common_','受講済');
													break;
											}
										} else {
											switch ($db_record['status']) {
												case 0:
													$denom_1 = 0;
													$denom_2 = 0;
													$denom_3 = 0;
													$status_message = $this->lang->line_or_def('common_','未受講');
													break;
												case 1:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = 0;
													$denom_3 = $db_record['question_count1'] ?? 0;
													$status_message = $this->lang->line_or_def('common_','受講中');
													break;
												case 2:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = 0;
													$denom_3 = $db_record['question_count1'] ?? 0;
													$status_message = $this->lang->line_or_def('common_','一次○');
													break;
												case 3:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = $db_record['question_count2'] ?? 0;
													$denom_3 = $denom_1 + $denom_2;
													$status_message = $this->lang->line_or_def('common_','一次×');
													break;
												case 4:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = $db_record['question_count2'] ?? 0;
													$denom_3 = $denom_1 + $denom_2;
													$status_message = $this->lang->line_or_def('common_','一次×');
													break;
												case 5:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = $db_record['question_count2'] ?? 0;
													$denom_3 = $denom_1 + $denom_2;
													$status_message = $this->lang->line_or_def('common_','追試○');
													break;
												case 6:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = $db_record['question_count2'] ?? 0;
													$denom_3 = $denom_1 + $denom_2;
													$status_message = $this->lang->line_or_def('common_','追試×');
													break;
												case 7:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = $db_record['question_count2'] ?? 0;
													$denom_3 = $denom_1 + $denom_2;
													$status_message = $this->lang->line_or_def('common_','レポート');
													break;
												case 8:
													$denom_1 = $db_record['question_count1'] ?? 0;
													$denom_2 = $db_record['question_count2'] ?? 0;
													$denom_3 = $denom_1 + $denom_2;
													$status_message = $this->lang->line_or_def('common_','会場');
													break;
											  //default:
												  //break;
											}
										}
										if( intval($db_record['answer_count'])<=0){$db_record['answer_count']=0;}
										if( intval($db_record['2nd_answer_count'])<=0){$db_record['2nd_answer_count']=0;}
										print $this->lang->line_or_def('common_','設問');
										print '：'.$db_record['answer_count'].'/'.$denom_1;
										print '　'.$db_record['2nd_answer_count'].'/'.$denom_2;
									?>
								</td>
								<td class="tdc">
									<?
										// [合否]
										print $this->lang->line_or_def('common_','解説動画');
										print '：'.$db_record['video_viewed_count'].'/'.$denom_3;
									?>
								</td>
								<td class="tdc">
									<?
										// [解説動画]
										print $status_message;
									?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th class="pager" colspan="6"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
