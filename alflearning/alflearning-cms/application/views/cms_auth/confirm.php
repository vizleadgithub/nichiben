<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "auth";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item($id){
			location.href ="<?=base_url()?>cms_auth/edit/"+$id;
		}
	</script>
	<style type="text/css">
		.auth_list DIV{
			line-height	: 21px;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header',array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_title_auth','権限管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_auth_comment','各講師の権限を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_auth/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_auth/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $teacher['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_auth_confirm','講師情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_auth_confirm','講師情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_auth_detail','講師情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_auth/commit")?>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_name','名前') ?></th>
							<td>
								<?=  htmlspecialchars( $teacher['teacher_name'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
							<th><?= $this->lang->line_or_def('common_mail_address','メールアドレス') ?></th>
							<td>
								<?=  htmlspecialchars( $teacher['teacher_email'], ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_practice_authority','実行権限') ?></th>
							<td class="auth_list">
								<?= '<div>'.implode('</div><div>',$teacher['authnames']).'</div>'; ?>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$teacher['teacher_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
							}
						?>
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
