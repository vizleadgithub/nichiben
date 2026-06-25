<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "information";
	$this->load->view('header/header',$data);?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_information','お知らせ') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_information_comment','お知らせを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_information/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_information/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_information/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_information", array('method'=>'get'))?>
					<table class="form">
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

				<table class="list">
					<tr>
						<th style="width:76px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
						<th style="width:103px;"><?= $this->lang->line_or_def('common_date','日付') ?></th>
						<th><?= $this->lang->line_or_def('common_title','タイトル') ?></th>
						<th><?= $this->lang->line_or_def('common_display_target','表示対象') ?></th>
						<th style="width:110px;"><?= $this->lang->line_or_def('common_status','状態') ?></th>
					</tr>
					<?php if(isset($information_list)) { ?>
						<?php foreach($information_list as $information) { ?>
							<tr style=<?= ($information['school_id']==0) ? "background-color:#D3F5EA;" : ""; ?> >
								<td class="tdc"><a href="/cms_information/detail/<?= htmlspecialchars( $information['information_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $information['information_id'], ENT_QUOTES, 'UTF-8') ?>
								</td>
								<td class="tdc"><?= htmlspecialchars( $information['information_date'], ENT_QUOTES, 'UTF-8') ?></td>
								<td class="tdc"><?= htmlspecialchars( $information['information_title'], ENT_QUOTES, 'UTF-8') ?></td>
								<td class="tdc">
									<span style="visibility:<?= ($information['show_teacher'] ? 'vilible' : 'hidden'); ?>"><?= $this->lang->line_or_def('common_teacher','講師'); ?></span>
									&nbsp;
									<span style="visibility:<?= ($information['show_student'] ? 'vilible' : 'hidden'); ?>"><?= $this->lang->line_or_def('common_student','受講生'); ?></span>
								</td>
								<td class="tdc">
									<?= htmlspecialchars( $information['disp_status'], ENT_QUOTES, 'UTF-8') ?>&nbsp;<?= ($information['school_id']==0) ? '('.$this->lang->line_or_def('common_all_school','全学校').')' : ""; ?>
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
