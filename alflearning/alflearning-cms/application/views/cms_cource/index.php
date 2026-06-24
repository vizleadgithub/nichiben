<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course";
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
		$this->load->view('header/body_header', array());?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course','講座管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_course_comment','講座を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_cource/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_cource/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_cource/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_cource", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
							<td>
								<input type="text" name="s_name" size="45" value="<?=set_value('s_name',$s_name)?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
							<td >
								<input type="text" name="s_open" size="19" value="<?=set_value('s_open',$s_open)?>" id="s_open" readonly><a class="clear_date" href="#s_open"><?= $this->lang->line_or_def('common_clear','クリア') ?></a>
								<?= $this->lang->line_or_def('common_range','～') ?></a>
								<input type="text" name="s_close" size="19" value="<?=set_value('s_close',$s_close)?>" id="s_close" readonly><a class="clear_date" href="#s_close"><?= $this->lang->line_or_def('common_clear','クリア') ?></a>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<td >
								<input type="text" name="s_id" size="45" value="<?=set_value('s_id',$s_id)?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_freeword','フリーワード') ?></th>
							<td >
								<input type="text" name="s_free_word" size="45" value="<?=set_value('s_free_word',$s_free_word)?>">
							</td>
						</tr>
					<!--	<tr>
							<th class="pager"><?=$pagination?></th>
						</tr>-->
					</table>
					<div class="submit">
						<input type="image" src="/static/image/btn_search.png" />
					</div>
				</form>

				<table class="list">
					<tr>
						<th style="width: 76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
						<th><?= $this->lang->line_or_def('common_course_name','講座名') ?></th>
						<th style="width:426px;"><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
					</tr>
					<?php $line=0;?>
					<?php if(isset($cource_list)) { ?>
						<?php foreach($cource_list as $cource) { ?>
							<?php $line++;?>
							<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
								<td class="tdc"><a href="/cms_cource/detail/<?= $cource['cource_id'] ?>"><?= $cource['cource_id'] ?></td>
								<td class="tdc"><?=$cource['cource_name']?></td>
								<td class="tdc"><?=$cource['cource_open']?><?= $this->lang->line_or_def('common_range','～') ?><?=$cource['cource_close']?></td>
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
