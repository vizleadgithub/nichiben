<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>


<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>

	<script type="text/javascript"><!--
	// --></script>
	
	<style type="text/css"><!--
		/* 予約するボタン（submit） */
		input#btn_submit_reservation {
			background: url(/static/image/btn_blue_trans.png) no-repeat 0 0;
			width           : 80px;
			height          : 28px;
			font-weight     : bold;
			color           : white;
			border:none;
			display:inline;
			vertical-align:middle;
			cursor: pointer;
		}
	// --></style>
	
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_student','受講者管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_student_comment','受講者を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_student_csv_upload/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix" style="height: 18px;">
				<!--<a class="btn_seach selected" href="/cms_student/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a> -->
				<!--<a class="btn_add" href="/cms_student/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a> -->
				</div>
				
				<h2><?= $this->lang->line_or_def('msg_','取得してきたファイルをセットして、予約するボタンを押してください') ?></h2>

				<? $form_paramattributes = array('name' => 'form_csv'); ?>
				
				<?=form_open_multipart("cms_student_csv_upload/student_csv_upload", $form_paramattributes)?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<table class="form">
						<tr>
							<th ><?= $this->lang->line_or_def('common_file','ファイル') ?></th>
							<td>
								<input type="file" onchange="setFilePath(this)" id="local_file" name="local_file" size="30" value="">
								<div style="margin-top: 10px;">※翌日04:00にデータ反映を行います</div>
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type="submit" name="btn_submit_reservation" value="予約する" id="btn_submit_reservation"  onclick='return confirm("ファイルをアップロードします。宜しいですか？");' />
					</div>
				</form>

			</div>
			<div class="clear"></div>
		</div>
	</div>

	<?php $this->load->view('header/body_footer');?>
</body>
</html>
