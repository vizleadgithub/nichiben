<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "login";
	$this->load->view('header/header',$data);?>
</head>

<body>
	<?php 
		$this->load->view('header/body_header',$data);
	?>

	<? $enableSchools = $this->session->userdata('enableSchools'); ?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_login','ログイン') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_login_comment','ID・パスワード入力') ?></div>
		</h1>

		<div id="main">
			<div id="menu_sub" class="clearfix">&nbsp;</div>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('msg_login_input','ID・パスワードを入力してください') ?></h2>

				<? if(is_array($enableSchools) && count($enableSchools) <= 1): ?>
					<?=validation_errors('<div class="error">', '</div>') ?>
				<? endif; ?>
				<? if(isset($error_msg) && $error_msg!=""): ?>
					<div class="error"><?=$error_msg?></div>
				<? endif; ?>
				<form action="/login_page/login?backurl=<?= ($this->input->get('backurl') ? urlencode($this->input->get('backurl')) : '/'); ?>" name="form1" method="post">
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<td align = "left"><input type=text name=login_id size=50 value='<?=set_value('login_id',$login_id)?>'></td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_password','パスワード') ?></th>
							<td align = "left"><input type=password name=password size=50 value='<?=set_value('password',$password)?>'></td>
						</tr>
						<? if(is_array($enableSchools) && count($enableSchools) > 1): ?>
							<tr>
								<th>学校を選択して下さい</th>
								<td align = "left">
									<select name="school_select">
										<? foreach($enableSchools as $enableSchool): ?>
											<option value="<?= $enableSchool->school_id; ?>"><?= $enableSchool->school_name; ?></option>
										<? endforeach; ?>
									</select>
								</td>
							</tr>
						<? endif; ?>
					</table>
					<div class="submit">
						<input type=submit value = <?= "　　　".$this->lang->line_or_def('common_login','ログイン')."　　　" ?> class='btn_r'>
					</div>
				</form>
			</div>
		<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>

	<script type="text/javascript">
		document.form1.login_id.focus();
	</script>
</body>
</html>
