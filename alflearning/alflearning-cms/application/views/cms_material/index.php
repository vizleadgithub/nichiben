<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "material";
	$this->load->view('header/header',$data);?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_material','資料管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_material_comment','資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_material/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_material/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_material/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_material", array('method'=>'post'))?>
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
							<th style="width: 76px;"><?= $this->lang->line_or_def('common_material_id','資料ID') ?></th>
							<th style="width:406px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<th><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
							<th><?= $this->lang->line_or_def('common_status','状態') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($material_list)) { ?>
							<?php foreach($material_list as $material) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td><a href="/cms_material/detail/<?= htmlspecialchars( $material['material_id'], ENT_QUOTES, 'UTF-8') ?>/"><?= htmlspecialchars( $material['material_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td style="word-wrap:break-word;"><?= htmlspecialchars( $material['material_logic_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td><?= htmlspecialchars( $material['teacher_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<?php if($material['status'] == 1){ ?>
										<td><?= $this->lang->line_or_def('common_conversion','変換済'); ?></td>
									<?php }elseif($material['status'] == 11){ ?>
										<td><?= $this->lang->line_or_def('common_failure_conversion','変換失敗'); ?></td>
									<?php }else{ ?>
										<td><?= $this->lang->line_or_def('common_no_conversion','未変換'); ?></td>
									<?php } ?>
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
