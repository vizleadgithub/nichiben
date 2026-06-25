<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "course_class";
	$this->load->view('header/header',$data);?>

<script type="text/javascript">
	$(function() {
		$('.tablefix').tablefix({width: 500, height: 200, fixRows: 2, fixCols: 2});
	});
</script>
<style>
/* 各リストの説明文 */
TABLE.list TH.list_common{
	background-color:#dcdcdc;
	color:#000000;
}
/* 各リスト、スクロール範囲 */
.list_select{
	height: 160px;
	overflow-x:hidden;
	overflow-y:auto;
}
/* 縦横中央設定 */
.center_middle{
	vertical-align: middle;
	text-align: center;
}
/* 資料サムネイル用 */
.img_thumbnail{
	height: 50px;
	padding: 1px;
	background-color:black;
}
TEXTAREA{
	width		: 100%;
	height		: 50px;
}
</style>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());?>
	
	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_course_class','授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_class_material_input','授業に使用する資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_class_material/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<?= $this->lang->line_or_def('msg_','授業に追加する資料を入力してください') ?>
					&nbsp;&nbsp;<a href="/static/html/supported_formats.html" onclick="window.open(this.href, 'mywindow6', 'width=400, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;">※対応ファイルについて</a>
				</div>
				
				<?=form_open("cms_class_material/class_material_upload_exec_quick", array('enctype'=>"multipart/form-data"))?>
					<input type="hidden" name="class_id" value='<?= htmlspecialchars( $class_id, ENT_QUOTES, 'UTF-8') ?>'>
					<input type="hidden" name="list_count" value=4>
					<table class="list">
						<tr>
							<th>ファイル名(必須)</th>
							<th>ファイル</th>
						</tr>
						<?php for($loop_count=0; $loop_count <= 4; $loop_count++): ?>
						<tr>
							<td><input type="text" name="material_logic_name_<?= htmlspecialchars( $loop_count, ENT_QUOTES, 'UTF-8') ?>" maxlength="50" value="" style="width:90%"></td>
							<td>
								ファイル選択(必須)：<input type="file" name="local_file_<?= htmlspecialchars( $loop_count, ENT_QUOTES, 'UTF-8') ?>" value=""><br />
								説明：<textarea name="material_caption_<?= htmlspecialchars( $loop_count, ENT_QUOTES, 'UTF-8') ?>" ></textarea>
							</td>
						</tr>
						<?php endfor ?>
					</table>
					<div class="submit">
						<input type="image" src="/static/image/btn_back.png" onClick='location.href = "/cms_class_material/material_select/<?= htmlspecialchars( $class_id, ENT_QUOTES, 'UTF-8') ?>";return false;' />
						<input type="image" src="/static/image/btn_register.png" />
					</div>
				</form>
			
		</div>
			<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
