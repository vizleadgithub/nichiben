<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "report";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
	function progressVideo(pid,sid){
		window.open("/alfproduct/report_status/video.php?pid="+pid+"&sid="+sid,"reportVideo","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
	}
	function progressExam(pid,sid){
		window.open("/alfproduct/report_status/exam.php?pid="+pid+"&sid="+sid,"reportExam","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
	}
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
						<td><?= htmlspecialchars( $student['student_name'], ENT_QUOTES, 'UTF-8') ?></td>
						<td><?= htmlspecialchars( $student['student_email'], ENT_QUOTES, 'UTF-8') ?></td>
						<td><?= htmlspecialchars( $student['lawyer_number'], ENT_QUOTES, 'UTF-8') ?></td>
						<td><?= ($student['presence_passport']==1) ? '○' : '－' ; ?></td>
						<td><?= htmlspecialchars( $student['regist_date'], ENT_QUOTES, 'UTF-8') ?></td>
						<td>
							<?php if(isset($student['bar_association_id'])): ?>
								<?php if( isset($mtb_bar_association[$student['bar_association_id']]) ): ?>
									<?= htmlspecialchars( $mtb_bar_association[$student['bar_association_id']], ENT_QUOTES, 'UTF-8') ?>
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
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/0" class="off_select_button"><?= $this->lang->line_or_def('common_','e-ラーニング'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/1" class="off_select_button"><?= $this->lang->line_or_def('common_','ライブ実務'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/2" class="off_select_button"><?= $this->lang->line_or_def('common_','日弁連以外主催'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/3" class="off_select_button"><?= $this->lang->line_or_def('common_','倫理研修'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/4" class="on_select_button "><?= $this->lang->line_or_def('common_','全て'); ?></a>
				</div>
				<br />
				
				<? // 画面中部、ページング ?>
				<? if($total_rows > 0): ?>
					<div style="height: 30px;line-height: 30px;text-align: center;">
						<?= htmlspecialchars( $start_rows, ENT_QUOTES, 'UTF-8') ?>～<?= htmlspecialchars( $end_rows, ENT_QUOTES, 'UTF-8') ?>件を表示中（全<?= htmlspecialchars( $total_rows, ENT_QUOTES, 'UTF-8') ?>件）
					</div>
				<? endif; ?>

				<? // 画面下部、一覧 ?>
				<table class="list">
					<tr>
<? // ID	商品名,講座名	主催弁護士会	最終受講日	実施日		進捗 ?>
<? //		講座種別		研修動画		テスト		解説動画	合否 ?>

						<th style=""><?= $this->lang->line_or_def('common_','ID') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','講座名') ?><br/><?= $this->lang->line_or_def('common_','講座種別') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','主催弁護士会') ?><br/><?= $this->lang->line_or_def('common_','研修動画') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','最終受講日') ?><br/><?= $this->lang->line_or_def('common_','テスト') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','実施日') ?><br/><?= $this->lang->line_or_def('common_','解説動画') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','進捗') ?><br/><?= $this->lang->line_or_def('common_','合否') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($show_record)) { ?>
						<?php foreach($show_record as $db_record) { ?>
							<?php //var_dump($db_record); ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<?
									// テスト 解説動画 合否 計算
									$denom_1 = 0;			// テスト 項目に使用する分母値
									$denom_2 = 0;			// テスト 項目に使用する分母値
									$denom_3 = 0;			// 解説動画 項目に使用する分母値
									$status_message = '';	// 合否 に表示する文言
									if($db_record['product_type_add']==3){
										switch ($db_record['status']) {
											case 0:
												$denom_1 = 0;
												$denom_2 = 0;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','未受講');
												break;
											case 1:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = 0;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','受講中');
												break;
											case 2:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = 0;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','一次○');
												break;
											case 3:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = $db_record['ethic_question_count2'] ?? 6;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','一次×');
												break;
											case 4:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = $db_record['ethic_question_count2'] ?? 6;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','一次×');
												break;
											case 5:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = $db_record['ethic_question_count2'] ?? 6;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','追試○');
												break;
											case 6:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = $db_record['ethic_question_count2'] ?? 6;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','追試×');
												break;
											case 7:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = $db_record['ethic_question_count2'] ?? 6;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','レポート');
												break;
											case 8:
												$denom_1 = $db_record['ethic_question_count1'] ?? 10;
												$denom_2 = $db_record['ethic_question_count2'] ?? 6;
												$denom_3 = $denom_1 + $denom_2;
												$status_message = $this->lang->line_or_def('common_','会場');
												break;
										  //default:
											  //break;
										}

									}elseif($db_record['product_type_add']==2){
										$status_message = '会場';
									}else{
										$status_message = '－';
									}
								?>
								
								<td class="tdc">
									<?
										// [ID]
										print $db_record['product_id'];
									?>
								</td>
								<td class="tdc">
									<?
										// [講座名（商品名）]
										print $db_record['product_name'];
									?>
									<br/>
									<?
										// [講座種別（商品種別）]
										switch ($db_record['product_type_add']) {
											case 1:
												print '<p style="color: #FF0000;">'.$this->lang->line_or_def('common_','e-ラーニング').'</p>';
												break;
											case 2:
												if($db_record['ethic_flg']==0){
													print '<p style="color: #0000FF;">'.$this->lang->line_or_def('common_','会場研修').'</p>';
												}elseif($db_record['ethic_flg']==1){
													print '<p style="color: #008000;">'.$this->lang->line_or_def('common_','倫理研修').'</p>';
												}else{
													print '－';
												}
												break;
											case 3:
												print '<p style="color: #008000;">'.$this->lang->line_or_def('common_','倫理研修').'</p>';
												break;
											case 4:
												print '<p style="color: #696969;">'.$this->lang->line_or_def('common_','パスポート').'</p>';
												break;
											default:
												print '－';
												break;
										}
									?>
								</td>
								<td class="tdc">
									<?
										//if($db_record['sponsor']=="|1|"){
										//	print '日弁連';
										//} else {
											// [主催弁護士会]
											//if($db_record['bar_association_branch_name']){
											//	print $db_record['bar_association_branch_name'];
											if($db_record['bar_association_name']){
												print $db_record['bar_association_name'];
											}else{
												print '－';
											}
										//}
									?>
									<br/>
									<?
										// [研修動画]
										if($db_record['product_type_add']==3){
											if( ($db_record['all_training_video_viewed_count']) && ($db_record['all_training_video_count']) ){
												$per = round( ($db_record['all_training_video_viewed_count'] / $db_record['all_training_video_count']) * 100 );
												print $this->lang->line_or_def('common_','研修動画').'：'.$per.'％';
											}else{
												print '－';
											}
										}elseif($db_record['product_type_add']==1){
											// 設問付きeラーニングの場合に表示
											if($db_record['product_kind_flg']=='3'){
												print '<a href="javascript:void(0)" onclick="progressVideo('.$db_record['product_id'].','.$student['student_id'].')">研修動画</a>：';
												if($db_record['complete_video_count']==$db_record['product_video_count']){echo '100%';}else{echo $db_record['max_percent_video'].'%';}
											}else{
												print '－';
											}
										}else{
											print '－';
										}
									?>
								</td>
								<td class="tdc">
									<?
										// [最終受講日]
										if($db_record['max_reading_date']){
											// 月日に前ゼロを入れない場合は、Y/n/j
											print date( "Y/m/d", strtotime($db_record['max_reading_date']) );
										}else{
											print '－';
										}
									?>
									<br/>
									<?
										// [テスト]
										if($db_record['product_type_add']==3){
											if( intval($db_record['answer_count'])<=0){$db_record['answer_count']=0;}
											if( intval($db_record['2nd_answer_count'])<=0){$db_record['2nd_answer_count']=0;}
											print $this->lang->line_or_def('common_','設問');
											//print '：'.$db_record['answer_count'].'/'.$denom_1;
											//print '　'.$db_record['2nd_answer_count'].'/'.$denom_2;
											print '：'.$db_record['ethic_answer_count'].'/'.$denom_1;
											print '　'.$db_record['ethic_2nd_answer_count'].'/'.$denom_2;
										}elseif($db_record['product_type_add']==1){
											// 設問付きeラーニングの場合に表示
											if($db_record['product_kind_flg']=='3'){
												print '<a href="javascript:void(0)" onclick="progressExam('.$db_record['product_id'].','.$student['student_id'].')">設問</a>：';
												print $db_record['answer_val_exam'].'/'.$db_record['max_val_exam'];
											}else{
												print '－';
											}
										}else{
											print '－';
										}
									?>
								</td>
								<td class="tdc">
									<?
										// [実施日]
										if($db_record['dates']){
											// 月日に前ゼロを入れない場合は、Y/n/j
											print date( "Y/m/d", strtotime($db_record['dates']) );
										}else{
											print '－';
										}
									?>
									<br/>
									<?
										// [解説動画]
										if($db_record['product_type_add']==3){
											print $this->lang->line_or_def('common_','解説動画');
											print '：'.$db_record['ethic_video_viewed_count'].'/'.$denom_3;
										}else{
											print '－';
										}
									?>
								</td>
								<td class="tdc">
									<?php print("<!--[product_type_add:".$db_record['product_type_add']."]-->"); ?>
									<?php print("<!--[all_training_video_viewed_count:".$db_record['all_training_video_viewed_count']."]-->"); ?>
									<?php print("<!--[all_training_video_count:".$db_record['all_training_video_count']."]-->"); ?>
									<?php print("<!--[complete_video_count:".$db_record['complete_video_count']."]-->"); ?>
									<?php print("<!--[product_video_count:".$db_record['product_video_count']."]-->"); ?>
									<?php print("<!--[all_duration_reading:".$db_record['all_duration_reading']."]-->"); ?>
									<?php print("<!--[all_alfstream_duration_sec:".$db_record['all_alfstream_duration_sec']."]-->"); ?>
									<?
										// [進捗]
										if($db_record['product_type_add']==1){
											// eラーニング
											if( ($db_record['all_duration_reading']) && ($db_record['all_alfstream_duration_sec']) ){
												//if( $db_record['product_video_count'] == $db_record['complete_video_count'] ){
												if( $db_record['all_training_video_count'] == $db_record['all_training_video_viewed_count'] ){
													print '100％';
												}else{
//print "[".$db_record['all_duration_reading']."]";
//print "[".$db_record['all_alfstream_duration_sec']."]";
													$per = round( ($db_record['all_duration_reading'] / $db_record['all_alfstream_duration_sec']) * 100 );
													print $per.'％';
												}
											}else{
												print '－';
											}
										}elseif($db_record['product_type_add']==2){
											// 会場研修
											if($db_record['participation_flg']==0){
												print '未受講';
											}elseif($db_record['participation_flg']==1){
												print '受講';
											}else{
												print '－';
											}
										}else{
											print '－';
										}
									?>
									<br/>
									<?
										// [合否]
										if($db_record['product_type_add']==3){
											print $status_message;
										}elseif($db_record['product_type_add']==1){
											// 設問付きeラーニングの場合に表示
											if($db_record['product_kind_flg']=='3'){
												print $db_record['gouhi'];
											}else{
												print '－';
											}
										}else{
											print '－';
										}
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
