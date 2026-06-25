<?php
	$this->lang->load('common');
?>

<?php
	$data['callview'] = "teacher";
	$this->load->view('header/header',$data);?>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());?>
	
	<div id="wrapper">
		<div id="menu_sub">
			<div class="menu_list">
				<div id="noborder">
					<table width="190">
					</table>
				</div>
			</div>
		<!-- /#menu_sub --></div>
		
		<div id="contents_main">
				<br />
				<br />
				<br />
				<br />
				<br />
				<center>
					<?= htmlspecialchars( $error_message, ENT_QUOTES, 'UTF-8') ?>
				</center>
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<br />
				<center>
					<input type=button onClick='location.href = "<?= htmlspecialchars( $returnurl, ENT_QUOTES, 'UTF-8') ?>";' value="　　<?= $this->lang->line_or_def('common_back','戻る') ?>　　" class='btn_r'>
				</center>
		<!-- /#main --></div>
			
		<div style="clear:left;height:0px;margin:0px;padding:0px;"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
