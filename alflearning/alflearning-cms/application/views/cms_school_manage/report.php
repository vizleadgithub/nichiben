<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "school_manage";
	$this->load->view('header/header',$data);
?>
</head>

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
				'selected'	=> 'report',
			));?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('msg_school_manage_report_date_input','取得する年月を入力してください') ?></h2>

				<form action="/cms_school_manage/report/">
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_date','年月') ?></th>
							<td>
								<input type="text" name="date" value="<?= date('Y-m', strtotime(date('Y-m-01') . '-1 month')); ?>" />
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_confirm.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
