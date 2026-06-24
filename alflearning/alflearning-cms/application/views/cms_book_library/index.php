<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "book_library";
	$this->load->view('header/header',$data);?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_book_library','図書室管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_book_library_comment','図書室内の資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_book_library/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_book_library/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_book_library/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_book_library", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<?=form_dropdown('s_cource',$cources_dropdown, set_value('s_cource',$s_cource)); ?>
							</td>
						</tr>
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td>
								<?=form_dropdown('s_tag',$tags_dropdown, set_value('s_tag',$s_tag)); ?>
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

				<div id="list">
					<table class="list">
						<tr>
							<th style="width: 76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th style="width:406px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<th><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
							<th><?= $this->lang->line_or_def('common_status','状態') ?></th>
							<th><?= $this->lang->line_or_def('common_download_status','DL状態') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($book_library_list)) { ?>
							<?php foreach($book_library_list as $book_library) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td><a href="/cms_book_library/detail/<?= htmlspecialchars( $book_library['book_library_id'], ENT_QUOTES, 'UTF-8') ?>/"><?= htmlspecialchars( $book_library['book_library_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= htmlspecialchars( $book_library['book_library_logic_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= htmlspecialchars( $book_library['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<?php if($book_library['status'] == 1){ ?>
										<td><?= $this->lang->line_or_def('common_conversion','変換済'); ?></td>
									<?php }elseif($book_library['status'] == 11){ ?>
										<td><?= $this->lang->line_or_def('common_failure_conversion','変換失敗'); ?></td>
									<?php }else{ ?>
										<td><?= $this->lang->line_or_def('common_no_conversion','未変換'); ?></td>
									<?php } ?>
									
									<?php if($book_library['stream_flag'] == 1){ ?>
										<td><?= $this->lang->line_or_def('common_download_possible','DL可能'); ?></td>
									<?php }elseif($book_library['stream_flag'] == 11){ ?>
										<td><?= $this->lang->line_or_def('common_download_no_possible','DL不可'); ?></td>
									<?php }else{ ?>
										<td><?= $this->lang->line_or_def('common_download_prepare','DL準備中'); ?></td>
									<?php } ?>
								</tr>
							<?php } ?>
						<?php } ?>
						<tr>
							<th class="pager" colspan="5"><?=$pagination?></th>
						</tr>
					</table>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
