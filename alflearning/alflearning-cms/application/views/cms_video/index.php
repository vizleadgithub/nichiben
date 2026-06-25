<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "video";
	$this->load->view('header/header',$data);?>
	
	<style type="text/css">
		.video_re_upload{
			color:#A02424;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_video','ビデオ授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_video_comment','ビデオ授業用のビデオファイルを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_video/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_video/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_video/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_video", array('method'=>'post'))?>
					<table class="form">
					<!--<tr>
							<th width="160"><?= '';//$this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<?='';//form_dropdown('s_cource',$cources_dropdown, set_value('s_cource',$s_cource)); ?>
							</td>
						</tr>
					 -->
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td>
								<?=form_dropdown('s_tag',$tags_dropdown, set_value('s_tag',$s_tag),['style' => 'width:580px;']); ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_freeword','フリーワード') ?></th>
							<td colspan = "3">
								<input type="text" name="s_free_word" size="45" value="<?=set_value('s_free_word',$s_free_word)?>">
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_search.png' />
					</div>
				</form>
				<br />

				<table class="list">
					<tr>
						<th style="width: 76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
						<th><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
						<th style="width: 100px;"><?= $this->lang->line_or_def('common_reproduction_time','再生時間') ?></th>
						<th style="width: 126px;"><?= $this->lang->line_or_def('common_status','状態') ?></th>
					</tr>
					<?php if(isset($video_list)) { ?>
						<?php foreach($video_list as $video) { ?>
							<? $link_kind = -1; ?>
							<? if(
								($video['alfstream_status'] === 'NEW') 
							 OR (preg_match("/^.*FAILED.*$/", $video['alfstream_status'])) 
							 OR ($video['alfstream_status'] === 'Not Found') 
							 OR (preg_match("/^HTTP Status Code.*$/", $video['alfstream_status'])) 
							): ?>
								<? $link_kind = 1; ?>
							<? else: ?>
								<? $link_kind = 0; ?>
							<? endif; ?>

							<tr <?php if($link_kind==1): ?> class="video_re_upload" <?php endif; ?> >
								<td class="tdc">
									<?php if($link_kind==1):  ?>
										<a href="/cms_video/newdata/<?= htmlspecialchars( $video['video_id'], ENT_QUOTES, 'UTF-8') ?>/"><?= htmlspecialchars( $video['video_id'], ENT_QUOTES, 'UTF-8') ?></a>
									<? else: ?>
										<a href="/cms_video/detail/<?= htmlspecialchars( $video['video_id'], ENT_QUOTES, 'UTF-8') ?>/"><?= htmlspecialchars( $video['video_id'], ENT_QUOTES, 'UTF-8') ?></a>
									<? endif; ?>
								</td>
								
								<?php if($video['video_logic_name'] === ""): ?>
									<td class="tdc"><?= htmlspecialchars( $video['video_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<?php endif; ?>
								
								<?php if($video['video_logic_name'] !== ""): ?>
									<td class="tdc"><?= htmlspecialchars( $video['video_logic_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<?php endif; ?>
								
								<td class="tdc"><?= htmlspecialchars( $video['alfstream_duration'], ENT_QUOTES, 'UTF-8') ?></td>
								
								<td class="tdc" >
									<?php if($video['alfstream_status'] === 'ONLINE'): ?>
										<?= $this->lang->line_or_def('common_conversion','変換済');  ?>
									<?php elseif($video['alfstream_status'] === 'NEW'): ?>
										<?= $this->lang->line_or_def('common_wait_upload','アップロード待ち'); ?>
									<?php elseif(preg_match("/^.*FAILED.*$/", $video['alfstream_status'])): ?>
										<?= $this->lang->line_or_def('common_123','変換エラー').'['. htmlspecialchars( $video['alfstream_status'], ENT_QUOTES, 'UTF-8') .']'; ?>
									<?php elseif( ($video['alfstream_status'] === 'Not Found') OR (preg_match("/^HTTP Status Code.*$/", $video['alfstream_status'])) ): ?>
										<?= $this->lang->line_or_def('common_123','API接続エラー').'['. htmlspecialchars( $video['alfstream_status'], ENT_QUOTES, 'UTF-8') .']'; ?>
									<?php else: ?>
										<?= $this->lang->line_or_def('common_no_conversion','未変換').'['. htmlspecialchars( $video['alfstream_status'], ENT_QUOTES, 'UTF-8') .']'; ?>
									<?php endif; ?>
								</td>
								
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th class="pager" colspan="4"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
