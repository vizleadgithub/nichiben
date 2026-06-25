<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam2";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		.form SELECT{
			min-width : 100px;
			height    : 22px;
		}
	</style>
</head>

<body class="<?= htmlspecialchars( getenv('URL_SERVICE'), ENT_QUOTES, 'UTF-8') ?>">
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam2','アンケート管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam2_comment','アンケートを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam2_problem/_submenu', array(
				'selected'	=> 'exam2_problem',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam2_problem/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam2_problem/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_exam2_problem", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<?=form_dropdown('s_cource',$cources_dropdown, set_value('s_cource',$s_cource)); ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_exam2_problem_group','設問グループ') ?></th>
							<td>
								<?=form_dropdown('s_exam2_problem_group',$exam2_problem_groups_dropdown, set_value('s_exam2_problem_group',$s_exam2_problem_group), '');?>
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
							<th style="width: 76px;"><?= $this->lang->line_or_def('common_id', 'ID') ?></th>
							<th style="width:494px;"><?= $this->lang->line_or_def('common_exam2_problem_name', '設問名') ?></th>
<?php if(false){ ?>
							<th>
								<?= (getenv('URL_SERVICE')=='mitemo')? "": $this->lang->line_or_def('common_management_teacher' ,'管理講師'); ?>
							</th>
<?php } ?>
							<th><?= $this->lang->line_or_def('common_exam2_answer_points', '解答配点') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($exam2_problem_list)) { ?>
							<?php foreach($exam2_problem_list as $exam2_problem) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td><a href="/cms_exam2_problem/detail/<?= $exam2_problem['exam2_problem_id']; ?>/"><?=$exam2_problem['exam2_problem_id']?></td>
									<td style="word-wrap:break-word;"><?= htmlspecialchars( $exam2_problem['exam2_problem_name'], ENT_QUOTES, 'UTF-8') ?></td>
<?php if(false){ ?>
									<td>
										<?= (getenv('URL_SERVICE')=='mitemo')? "": htmlspecialchars( $exam2_problem['teacher_name'], ENT_QUOTES, 'UTF-8'); ?>
									</td>
<?php } ?>
									<td><?=$exam2_problem['answer_point']?></td>
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
