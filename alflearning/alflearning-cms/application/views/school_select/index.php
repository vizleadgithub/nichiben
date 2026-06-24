<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "school_select";
	$this->load->view('header/header',$data);
?>

<style type="text/css">
	TABLE.form LI{
		border-bottom		: 1px dotted silver;
		padding				: 1px 0px;
		margin-bottom		: 2px;
	}
		TABLE.form LI INPUT{
			margin-right	: 5px;
		}
</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_school_select','学校選択') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_school_select_comment','管理者による学校選択') ?></div>
		</h1>

		<div id="main">
			<div id="menu_sub" class="clearfix">&nbsp;</div>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('msg_school_select','学校を選択してください') ?></h2>

				<?=validation_errors('<div class="error">', '</div>') ?>
				<div class="error"><?=$error_msg?></div>
				<?=form_open("school_select/select")?>
					<table class="form">
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_fixation','本契約'); ?></th>
							<td align = "left">
								<ul>
									<? if($school_list): ?>
									<? foreach($school_list as $school): ?>
										<? if($school['contract'] == 'fixation'): ?><li><input type="radio" name="school_id" value="<?= $school['school_id']; ?>" /><?= $school['school_name']; ?></li><? endif; ?>
									<? endforeach; ?>
									<? endif; ?>
								</ul>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_demo','デモ版'); ?></th>
							<td align = "left">
								<ul>
									<? if($school_list): ?>
									<? foreach($school_list as $school): ?>
										<? if($school['contract'] == 'demo'): ?><li><input type="radio" name="school_id" value="<?= $school['school_id']; ?>" /><?= $school['school_name']; ?></li><? endif; ?>
									<? endforeach; ?>
									<? endif; ?>
								</ul>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_contract_presentation','プレゼン版'); ?></th>
							<td align = "left">
								<ul>
									<? if($school_list): ?>
									<? foreach($school_list as $school): ?>
										<? if($school['contract'] == 'presentation'): ?><li><input type="radio" name="school_id" value="<?= $school['school_id']; ?>" /><?= $school['school_name']; ?></li><? endif; ?>
									<? endforeach; ?>
									<? endif; ?>
								</ul>
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type="image" src="/static/image/btn_ok.png" />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
