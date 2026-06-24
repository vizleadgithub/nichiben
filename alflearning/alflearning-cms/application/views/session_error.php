<?php
	$this->lang->load('common');
	$this->lang->load('error');
?>

<?php
	$data['callview'] = "session_error";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title">Error</div>
			<div class="comment">error</div>
		</h1>

		<div id="main">
			<div id="menu_sub" class="clearfix">&nbsp;</div>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('error_session','セッションエラーです。') ?></h2>

				<br /><br />

				<?/* セッションタイムアウト後に編集画面に行こうとした時、ログインに行ってココに戻ってくるから、戻り先が無限ループしてしまう
				<center>
					<input type="button" onClick='history.back();' value="　　<?= $this->lang->line_or_def('common_back','戻る') ?>　　" class='btn_r'>
				</center>
				*/?>

				<br /><br />

			</div>

			<div class="clear"></div>
		</div>
	</div>

	<?php $this->load->view('header/body_footer');?>
</body>
</html>
