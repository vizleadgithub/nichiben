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
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/0" class="on_select_button" ><?= $this->lang->line_or_def('common_','e-ラーニング'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/1" class="off_select_button"><?= $this->lang->line_or_def('common_','ライブ実務'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/2" class="off_select_button"><?= $this->lang->line_or_def('common_','日弁連以外主催'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= $student['student_id'] ?>/3" class="off_select_button"><?= $this->lang->line_or_def('common_','倫理研修'); ?></a>
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
						<th style=""><?= $this->lang->line_or_def('common_','商品名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','主催弁護士会') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','最終受講日') ?><br/><?= $this->lang->line_or_def('common_','申込') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','実施日') ?><br/>研修動画/設問</th>
						<th style=""><?= $this->lang->line_or_def('common_','進捗') ?>/合否<br/><?= $this->lang->line_or_def('common_','視聴済時間/総時間') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($show_record)) { ?>
						<?php foreach($show_record as $db_record) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<td class="tdc">
									<?
										// [商品名]
										print $db_record['product_name'];
									?>
								</td>
								<td class="tdc">
									<?
										// [主催弁護士会]
										//if($db_record['bar_association_branch_name']){
										//	print $db_record['bar_association_branch_name'];
										//}else{
											print '－';
										//}
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
										// [申込]
										if($db_record['web_flg']==0){
											print 'WEB以外';
										}elseif($db_record['web_flg']==1){
											print 'WEB申込';
										}else{
											print '－';
										}
									?>
								</td>
								<td class="tdc">
									<?
										// [実施日]
										print '－';
									?>
									<br>
									<?php if($db_record['product_kind_flg']=='3'){ ?>
										<a href="javascript:void(0)" onclick="progressVideo(<?= $db_record['product_id'] ?>,<?= $student['student_id'] ?>)">研修動画</a>：<?php if($db_record['complete_video_count']==$db_record['product_video_count']){echo '100%';}else{echo $db_record['max_percent_video'].'%';} ?>/
										<a href="javascript:void(0)" onclick="progressExam(<?= $db_record['product_id'] ?>,<?= $student['student_id'] ?>)">設問</a>：<?= $db_record['answer_val_exam'] ?>/<?= $db_record['max_val_exam'] ?>
									<?php } else { ?>
										－
									<?php } ?>
								</td>
								<td class="tdc">
									<?
print "<!--[".$db_record['product_video_count']."]-->";
print "<!--[".$db_record['complete_video_count']."]-->";
print "<!--[".$db_record['all_duration_reading']."]-->";
print "<!--[".$db_record['all_alfstream_duration_sec']."]-->";
										// [進捗]
										if( ($db_record['all_duration_reading']) && ($db_record['all_alfstream_duration_sec']) ){
											if( $db_record['product_video_count'] == $db_record['complete_video_count'] ){
												print '100％';
											}else{
												$per = round( ($db_record['all_duration_reading'] / $db_record['all_alfstream_duration_sec']) * 100 );
												print $per.'％';
											}
										}else{
											print '－';
										}
										
										// [合否]
										print '/';
										if($db_record['product_kind_flg']=='3'){
											print $db_record['gouhi'];
										} else {
											print '－';
										}
									?>
									<br/>
									<?
										// [視聴済時間/総時間]
										if( $db_record['product_video_count'] == $db_record['complete_video_count'] ){
											print sprintf('%02d:%s', $db_record['all_alfstream_duration_sec'] / 3600, gmdate('i:s', $db_record['all_alfstream_duration_sec'] % 3600));
											print '/';
											print sprintf('%02d:%s', $db_record['all_alfstream_duration_sec'] / 3600, gmdate('i:s', $db_record['all_alfstream_duration_sec'] % 3600));
										}else{
											print sprintf('%02d:%s', $db_record['all_duration_reading'] / 3600, gmdate('i:s', $db_record['all_duration_reading'] % 3600));
											print '/';
											print sprintf('%02d:%s', $db_record['all_alfstream_duration_sec'] / 3600, gmdate('i:s', $db_record['all_alfstream_duration_sec'] % 3600));
										}
									?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th class="pager" colspan="5"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
