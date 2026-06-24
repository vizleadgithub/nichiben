<?php
	$this->lang->load('common');
	$this->lang->load('msg');
	$this->lang->load('error');
?>

<?php
	$data['callview'] = "video";
	$this->load->view('header/header',$data);?>

	<script type="text/javascript"><!--
		var filePath = "";

		/**
		 * 選択したファイルのパスをセットする
		 * @param fileElem inputタグ(file)の要素
		 */
		function setFilePath(fileElem) {
			filePath = fileElem.value;
		}

		/**
		 *  ファイルが選択されているか簡単なチェックをする
		 */
		function filename_check(){
			
			// 設定開始（必須にする項目を設定してください）
			if(filePath == "") {
				// ビデオファイル名なし
				document.getElementById('filename_error').innerHTML = "<?= $this->lang->line_or_def('error_file_no_select','ファイルを指定してください') ?>";
				
				return false;	// 送信を中止
			}
			else{
				document.getElementById('submit_ok').innerHTML = "<?= $this->lang->line_or_def('msg_video_uploading_now','送信中です...しばらくお待ちください') ?>";
				return true;	// 送信を実行
			}
		}

	// --></script> 
	
	<style type="text/css">
		TEXTAREA{
			width	: 100%;
			height	: 200px;
		}
		.auth_list DIV{
			line-height	: 21px;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_video','ビデオ授業管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_video_comment','ビデオ授業用のビデオファイルを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_video/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_video/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_video/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_video_select','ビデオファイルを選択してください') ?></h2>
				<form name="video_form" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="<?= $video['upload_url'] ?>" onSubmit="return filename_check()">
					<div class="error" id="filename_error"></div>

					<table class="form">
						<tr>
							<th ><?= $this->lang->line_or_def('common_file','ファイル') ?></th>
							<td>
								<input type="file" onchange="setFilePath(this)"  name="uploadfile" size="30" value="">
								<br/>
								<div style="padding-top: 10px; padding-bottom: 5px;"><?= $this->lang->line_or_def('msg_video_upload_limit','※ビデオファイルのファイルサイズ3GBまで可能') ?></div>
							</td>
						</tr>
					</table>
					<div class="submit" id="submit_ok">
						<input type='image' src='/static/image/btn_ok.png' />
					</div>
				</form>
			</div>

			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
