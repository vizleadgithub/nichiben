<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>
	<style>
	#s_open{
		width: 126px;
	}
	#s_close{
		width: 126px;
	}
	
	</style>
	<script type="text/javascript">
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datetimepicker設定
			$('#s_open' ).datetimepicker(datetimepickeroption);
			$('#s_close').datetimepicker(datetimepickeroption);
		});
		
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_comment','授業を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_list" href="/cms_cource_class/"><span><?= $this->lang->line_or_def('common_list','一覧') ?></span></a>
					<a class="btn_seach selected" href="/cms_class/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_class/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_class", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<?=form_dropdown('s_cource',$cources_dropdown,set_value('s_cource',$s_cource));?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_time','時間') ?></th>
							<td>
								<input type="text" name="s_open" size="19" value="<?=set_value('s_open',$s_open)?>" id="s_open" readonly><a class="clear_date" href="#s_open"><?= $this->lang->line_or_def('common_clear','クリア') ?></a>
								<?= $this->lang->line_or_def('common_range','～') ?></a>
								<input type="text" name="s_close" size="19" value="<?=set_value('s_close',$s_close)?>" id="s_close" readonly><a class="clear_date" href="#s_close"><?= $this->lang->line_or_def('common_clear','クリア') ?></a>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
							<td >
								<?=form_dropdown('s_teacher',$teachers_dropdown,set_value('s_teacher',$s_teacher));?>
							</td>
						</tr>
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<td >
								<input type="text" name="s_id" size="10" value="<?=set_value('s_id',$s_id)?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_freeword','フリーワード') ?></th>
							<td>
								<input type="text" name="s_free_word" size="45" value="<?=set_value('s_free_word',$s_free_word)?>">
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type="image" name="btn_search" src="/static/image/btn_search.png" />
					</div>
				</form>

				<table class="list">
					<tr>
						<th style="width: 76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
						<th style="width:103px;"><?= $this->lang->line_or_def('common_date','日付') ?></th>
						<th style="width:166px;"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
						<th style="width:166px;"><?= $this->lang->line_or_def('common_class_name','授業名') ?></th>
						<th style="width:156px;"><?= $this->lang->line_or_def('common_time','時間') ?></th>
						<th><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
					</tr>
					<?php if(isset($class_list)) { ?>
						<?php foreach($class_list as $class) { ?>
							<tr <? if($class['effective_cource']==0): ?>class="out_of_the_period"<? endif; ?> >
								<td class="tdc"><a href="/cms_class/detail/<?= htmlspecialchars( $class['class_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $class['class_id'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars( $class['class_date'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars( $class['cource_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars( $class['class_name'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars( $class['class_opentime'], ENT_QUOTES, 'UTF-8') ?>～<?= htmlspecialchars( $class['class_closetime'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars( $class['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
					<tr>
					<!--<th colspan="9" class="pager"><?=$pagination?></th> -->
						<th colspan="7" class="pager"><?=$pagination?></th>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
