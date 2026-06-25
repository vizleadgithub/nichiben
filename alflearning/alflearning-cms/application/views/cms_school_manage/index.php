<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "school_manage";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_school_manage','学校管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_school_manage_comment','学校を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_school_manage/_submenu', array(
				'selected'	=> 'index',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_school_manage/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_school_manage/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_search','検索する内容を入力してください') ?></h2>

				<?=form_open("cms_school_manage", array('method'=>'post'))?>
					<table class="form">
						<tr>
							<th><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<td >
								<input type="text" name="s_school_id" size="45" value="<?=set_value('s_school_id',$s_school_id)?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<td>
								<input type="text" name="s_school_name" size="45" value="<?=set_value('s_school_name',$s_school_name)?>">
							</td>
						</tr>
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

				<div id="list">
					<table class="list">
						<tr>
							<th><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th><?= $this->lang->line_or_def('common_school_name','学校名') ?></th>
							<th><?= $this->lang->line_or_def('common_contract_form','契約形態') ?></th>
						</tr>
						<?php $line=0;?>
						<?php if(isset($school_list)) { ?>
							<?php foreach($school_list as $school) { ?>
								<?php $line++;?>
								<tr class="<?=(($line % 2)==0 ? 'koi' : '')?>">
									<td class="tdc"><a href="/cms_school_manage/detail/<?= htmlspecialchars( $school['school_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $school['school_id'], ENT_QUOTES, 'UTF-8') ?></td>
									<td class="tdc"><?= htmlspecialchars( $school['school_name'], ENT_QUOTES, 'UTF-8') ?></td>
									<td class="tdc">
									<!--<?= $school['contract'] ?>-->

										<?php if($school['contract'] === 'fixation'){ ?>
											<?= $this->lang->line_or_def('common_contract_fixation','本契約') ?>
										<?php }elseif($school['contract'] === 'demo'){ ?>
											<?= $this->lang->line_or_def('common_contract_demo','デモ版') ?>
										<?php }elseif($school['contract'] === 'presentation'){ ?>
											<?= $this->lang->line_or_def('common_contract_presentation','プレゼン版') ?>
										<?php }else{ ?>
											------
										<?php } ?>


									</td>
								</tr>
							<?php } ?>
						<?php } ?>
						<tr>
							<th colspan="3" class="pager"><?=$pagination?></th>
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
