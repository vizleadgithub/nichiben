<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "material";
	$this->load->view('header/header',$data);?>
	<style>
	.cource_list{
		max-height	: 300px;
		overflow-y	: scroll;
	}
	.cources{
		border-bottom	: 1px dotted silver;
		margin			: 0px 0px 3px 0px;
		padding			: 0px 2px 0px 7px;
	}
		.cources input, .courcets label{
			height		: 17px;
			line-height	: 17px;
		}

	/* 所属講座選択エリア */
	#cource_ul{
		margin-top: 0px;
	}
	#cource_ul li{
		margin-left: 2px;
		margin-bottom: 0px;
	}

	</style>
	<script type="text/javascript"><!--
		$(function(){
			if($('.cource_list input:checked').length == 0){
				$("#select_all").css("background-position", "center top");
			}else{
				$("#select_all").css("background-position", "center bottom");
			}
			
			$('.cource_list').click(function (){
				if($('.cource_list input:checked').length == 0){
					$("#select_all").css("background-position", "center top");
				}else{
					$("#select_all").css("background-position", "center bottom");
				}
			});
		});

		function select_all(){
			if($('.cource_list input:checked').length){
				$('.cource_list input').removeAttr('checked');
				$("#select_all").css("background-position", "center top");
			}
			else{
				$('.cource_list input').attr('checked','checked');
				$("#select_all").css("background-position", "center bottom");
			}
			return false;
		}
	// --></script> 
	<style type="text/css">
		TEXTAREA{
			width	: 100%;
			height	: 200px;
		}
		.pdf_message{
			color				: #FF0000;
			font-size			: 11px;
		}
	</style>
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

				<h2><?= $this->lang->line_or_def('msg_material_input','登録する本の情報をを入力してください') ?></h2>

				<?=form_open_multipart("cms_material/commit")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($upload_error)?'<div class="error">'.$upload_error.'</div>':'')?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $material['update_flg'])?>'>
					<input type=hidden name=material_id value='<?=set_value('material_id', $material['material_id'])?>'>
					<input type=hidden name=material_logic_name value='<?=set_value('material_logic_name', $material['material_logic_name'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<td>
								<input type=text name="material_logic_name" maxlength="50" size="30" value='<?=set_value('material_logic_name',$material['material_logic_name'])?>'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->lang->line_or_def('msg_input_limit','※50文字まで指定') ?>
							</td>
						</tr>
						<tr>
							<th ><?= $this->lang->line_or_def('common_file','ファイル') ?></th>
							<td>
								<?php if($material['update_flg']==0){ ?>
									<input type="file" name="local_file" size="30" value="<?=set_value('local_file',$material['local_file'])?>">
									&nbsp;&nbsp;<a href="/static/html/supported_formats.html" onclick="window.open(this.href, 'mywindow6', 'width=400, height=300, menubar=no, toolbar=no, scrollbars=yes'); return false;">※対応ファイルについて</a>
									<div class="pdf_message">アップロードするファイルがPDFファイルの場合、PDF作成ツールによってはフォントが対応していない場合があります。<br />その場合は文字化け等の問題が発生する場合がありますので、PowerPoint等の変換元データの使用を推奨致します</div>
								<?php } else { ?>
									<?= $this->lang->line_or_def('common_no_change','変更できません') ?>
									<input type=hidden name=local_file value=''>
								<?php } ?>
							</td>
						</tr>

						<tr>
							<th >
								<?= $this->lang->line_or_def('common_position_course','所属講座') ?>
								<a href="#" onclick="select_all();return false;" id="select_all" name="select_all"></a>
							</th>
							<td>
								<div class="cource_list">
									<ul class="list" id="cource_ul">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<li>
												<input type="checkbox" name="material_lectures[]" id="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>" value=<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>
													<?php 
													if( isset($material['material_lectures']) ) {
														foreach( $material['material_lectures'] as $lecture) { 
															if($lecture == $cource['cource_id']) {
														?>
																checked
																<?php
																break;
															}
														}
													}
													?>
													>
												<label for="lectures_<?= htmlspecialchars( $cource['cource_id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars( $cource['cource_name'], ENT_QUOTES, 'UTF-8') ?></label>
												</li>
										<?php 
										}
									}else{ ?>
										<li>---</li>
									<?php
									}
									?>
										
									</ul>
								</div>
							</td>

<!--						<td>
								<div class="cource_list">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<div class="cources">
												<input type="checkbox" name="material_lectures[]" id="lectures_<?=$cource['cource_id']?>" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($material['material_lectures']) ) {
														foreach( $material['material_lectures'] as $lecture) { 
															if($lecture == $cource['cource_id']) {
														?>
																checked
																<?php
																break;
															}
														}
													}
													?>
													>
												<label for="lectures_<?=$cource['cource_id']?>"><?=$cource['cource_name']?></label>
												</div>
										<?php 
										}
									}
									?>
								</div>
							</td>	-->
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="material_caption" ><?=set_value('material_caption',$material['material_caption'])?></textarea>
							</td>
						</tr>

						<?php if($material['update_flg']!=0){ ?>
						<tr>
							<th style="vertical-align: middle;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<td>
								<img src="/file_container/get_material_thubmnail/<?= htmlspecialchars( $material['material_id'], ENT_QUOTES, 'UTF-8') ?>" alt="" style="height: 200px; padding: 1px;background-color:black;"/>
							</td>
						</tr>
						<?php } ?>
					</table>
					<div class="submit">
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
