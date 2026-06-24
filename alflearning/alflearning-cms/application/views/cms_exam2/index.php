<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam2";
	$this->load->view('header/header',$data);?>
</head>

<body class="<?= getenv('URL_SERVICE'); ?>">
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam2','アンケート管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam2_comment','アンケートを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam2/_submenu', array(
				'selected'	=> 'exam2',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam2/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam2/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_exam2", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<?=form_dropdown('s_cource',$cources_dropdown, set_value('s_cource',$s_cource)); ?>
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
							<th style="width: 76px;"><?= $this->lang->line_or_def('common_id'         ,'ID') ?></th>
							<th style="width:406px;"><?= $this->lang->line_or_def('common_exam2_name' ,'アンケート名') ?></th>
<?php if(false){ ?>
							<th>
								<?= (getenv('URL_SERVICE')=='mitemo')? "": $this->lang->line_or_def('common_management_teacher' ,'管理講師'); ?>
							</th>
<?php } ?>
							<th><?= $this->lang->line_or_def('common_submit_accept'      ,'提出受付') ?></th>
							<th><?= $this->lang->line_or_def('common_indication_status'  ,'公開設定') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($exam2_list)) { ?>
							<?php foreach($exam2_list as $exam2) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td><a href="/cms_exam2/detail/<?= $exam2['exam2_id']; ?>/"><?=$exam2['exam2_id']?></td>
									<td style="word-wrap:break-word;"><?=$exam2['exam2_name']?></td>
<?php if(false){ ?>
									<td>
										<?= (getenv('URL_SERVICE')=='mitemo')? "": $exam2['teacher_name']; ?>
									</td>
<?php } ?>
									<td><?=$exam2['disp_status']?></td>
									<?php if($exam2['public_flag'] == 0){ ?>
										<td><?= $this->lang->line_or_def('common_public','公開'); ?></td>
									<?php }elseif($exam2['public_flag'] == 9){ ?>
										<td><?= $this->lang->line_or_def('common_non_public','非公開'); ?></td>
									<?php }else{ ?>
										<td>-</td>
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
