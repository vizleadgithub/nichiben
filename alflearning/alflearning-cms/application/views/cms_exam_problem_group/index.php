<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
</head>

<body class="<?= getenv('URL_SERVICE'); ?>">
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam','問題管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam_comment','問題（テスト）を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam_problem_group/_submenu', array(
				'selected'	=> 'exam_problem_group',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam_problem_group/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam_problem_group/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_exam_problem_group", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam_problem_group_name','設問グループ名') ?></th>
							<td>
								<input type="text" name="s_exam_problem_group_name" size="45" value="<?=set_value('s_exam_problem_group_name',$s_exam_problem_group_name)?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_exam_problem_name','設問名') ?></th>
							<td>
								<?=form_dropdown('s_exam_problem_id',$exam_problem_dropdown,set_value('s_exam_problem_id',$s_exam_problem_id));?>
							</td>
						</tr>
						<? if( $this->config->item('language') != 'alfsales' ): ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<td >
								<input type="text" name="s_id" size="10" value="<?=set_value('s_id',$s_id)?>">
							</td>
						</tr>
						<? endif; ?>
						<tr>
							<th><?= $this->lang->line_or_def('common_freeword','フリーワード') ?></th>
							<td >
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
							<th style="width:76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th><?= $this->lang->line_or_def('common_exam_problem_group_name' ,'設問グループ名') ?></th>
							<th style="width:100px;"><?= $this->lang->line_or_def('common_position_exam_problem_count','所属設問数') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($exam_problem_group_list)) { ?>
							<?php foreach($exam_problem_group_list as $exam_problem_group) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td><a href="/cms_exam_problem_group/detail/<?= htmlspecialchars( $exam_problem_group['exam_problem_group_id'], ENT_QUOTES, 'UTF-8') ?>/"><?= htmlspecialchars( $exam_problem_group['exam_problem_group_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= htmlspecialchars( $exam_problem_group['exam_problem_group_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?=$exam_problem_group['exam_problem_count']?></td>
								</tr>
							<?php } ?>
						<?php } ?>
						<tr>
							<th class="pager" colspan="4"><?=$pagination?></th>
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
