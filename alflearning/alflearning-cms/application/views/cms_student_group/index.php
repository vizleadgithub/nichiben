<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>
	<style></style>
	<script type="text/javascript"></script>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_student','受講者管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_student_comment','受講者を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_student_group/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_student_group/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_student_group/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_student_group", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_group_name','グループ名') ?></th>
							<td>
								<input type="text" name="s_student_group_name" size="45" value="<?=set_value('s_student_group_name',$s_student_group_name)?>">
							</td>
						</tr>
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_student_name','受講者名') ?></th>
							<td>
								<?=form_dropdown('s_student_id',$students_dropdown,set_value('s_student_id',$s_student_id));?>
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
						<input type='image' src='/static/image/btn_search.png'>
					</div>
				</form>
				<br />
				<table class="list">
					<tr>
						<th style="width:76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
						<th><?= $this->lang->line_or_def('common_group_name','グループ名') ?></th>
						<th style="width:100px;"><?= $this->lang->line_or_def('common_position_student_count','所属受講者数') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($student_group_list)) { ?>
						<?php foreach($student_group_list as $student_group) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<td class="tdc"><a href="/cms_student_group/detail/<?= $student_group['student_group_id'] ?>"><?=$student_group['student_group_id']?></td>
								<td class="tdc"><?=$student_group['student_group_name']?></td>
								<td class="tdc"><?=$student_group['student_count']?></td>
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
						<th class="pager" colspan="3"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
