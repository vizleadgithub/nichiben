<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "material";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		
		//------------------------------------------
		//詳細確認画面　削除ボタン押下
		//------------------------------------------
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_material/delete_item/" + id ;
			}
		}
		
		//------------------------------------------
		//詳細確認画面　修正ボタン押下
		//------------------------------------------
		function edit_item(){
			location.href ="<?=base_url()?>cms_material/edit/";
		}
	</script>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_material','資料管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_material_comment','資料を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_material/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_material/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_material/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2>
					<?php 
						if( $material['update_flg'] == 0 ){
							print $this->lang->line_or_def('msg_material_confirm','資料情報の確認');
						}else{
							switch($btn_kirikae_flg){
								case 1://修正画面
									print $this->lang->line_or_def('msg_material_confirm','資料情報の確認');
									break;
									
								case 2://詳細画面
									print $this->lang->line_or_def('msg_material_detail','資料情報の詳細');
									break;
							}
						}
					?>
				</h2>

				<?=form_open("cms_material/commit")?>
					<table class="form">
						<tr>
							<th width="120"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<td>
								<?php if($material['material_logic_name'] == ""): ?>
									<?=$material['material_name']?>
								<?php endif; ?>
								<?php if($material['material_logic_name'] != ""): ?>
									<?=$material['material_logic_name']?>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
							<td >
								<?php
									$flg = FALSE;
									if( isset($material['material_lectures_name']) ) {
										foreach( $material['material_lectures_name'] as $name) { 
											if($flg){	?>
												,
											<?php } ?>
											<?=$name?>
										<?php
											$flg = TRUE;
										}
									}
								?>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
							<td ><?=$material['teacher_name']?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td ><?= nl2br($material['material_caption']); ?>
							</td>
						</tr>
						<tr>
							<th style="vertical-align: middle;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<td>
								<img src="/file_container/get_material_thubmnail/<?= $material['material_id']; ?>/" alt="" style="height: 200px; padding: 1px;background-color:black;"/>
							</td>
						</tr>
					</table>
					<div class="submit">
						<?php
							switch($btn_kirikae_flg){
								case 1://修正画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='edit_item(".$material['material_id'].");return false;' />";
									print "<input type='image' src='/static/image/btn_ok.png' />";
									break;
									
								case 2://詳細画面
									print "<input type='image' src='/static/image/btn_back.png' onClick='location.href = \"".site_url('cms_material')."\";return false;' />";
									print "<input type='image' src='/static/image/btn_download.png' onClick='location.href = \"".site_url('cms_material/download_file/'.$material['material_id'])."\";return false;' />";
									print "<input type='image' src='/static/image/btn_revise.png' onClick='edit_item();return false;' />";
									print "<input type='image' src='/static/image/btn_delete.png' onClick='delete_item(".$material['material_id'].',"'.$this->lang->line_or_def('msg_delete','本当に削除してもよろしいですか？').'"'.");return false;' />";

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
