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
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/1" class="on_select_button" ><?= $this->lang->line_or_def('common_','ライブ実務'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/2" class="off_select_button"><?= $this->lang->line_or_def('common_','日弁連以外主催'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/3" class="off_select_button"><?= $this->lang->line_or_def('common_','倫理研修'); ?></a>
					<a href="/cms_report/cms_user_detail/<?= htmlspecialchars( $student['student_id'], ENT_QUOTES, 'UTF-8') ?>/4" class="off_select_button"><?= $this->lang->line_or_def('common_','全て'); ?></a>
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
						<th style=""><?= $this->lang->line_or_def('common_','商品名') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','実施弁護士会') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','申込') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','実施日') ?></th>
						<th style=""><?= $this->lang->line_or_def('common_','進捗') ?></th>
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
										// [実施弁護士会]
										if($db_record['bar_association_branch_name']){
											print $db_record['bar_association_branch_name'];
										}else{
											print '日弁連';
										}
									?>
								</td>
								<td class="tdc">
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
										if($db_record['dates']){
											// 月日に前ゼロを入れない場合は、Y/n/j
											print date( "Y/m/d", strtotime($db_record['dates']) );
										}else{
											print '－';
										}
									?>
								</td>
								<td class="tdc">
									<?
										// [進捗]
										if($db_record['participation_flg']==0){
											print '未受講';
										}elseif($db_record['participation_flg']==1){
											print '受講';
										}else{
											print '－';
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
