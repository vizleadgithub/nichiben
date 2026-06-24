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
				<div class="toolbar clearfix"></div>
				
				<?=form_open("cms_class_material/class_material_confirm")?>
					<input type=hidden name=class_id value='<?= $class_id ?>'>
					<h3><?= str_replace("class_name", $class_list[0]['class_name'], 
						 $this->lang->line_or_def('msg_class_material_del_title','授業名『class_name』に登録されている資料') ); ?></h3>
					<table class="list" style="width: 773px;"> <!--775px;-->
						<tr>
							<th colspan=7 class="list_common">
							<?=$this->lang->line_or_def('msg_class_material_del_comment','授業に登録している資料一覧　授業から削除したい資料にチェックをいれてください') ?>
							</th>
						</tr>
						<tr>
							<th style="width: 45px;"></th>
							<th style="width: 64px;"><?= $this->lang->line_or_def('common_deletion_target','削除対象') ?></th></th><!--97-->
							<th style="width:127px;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<th style="width: 64px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>						<!--97-->
							<th style="width: 70px;"><?= $this->lang->line_or_def('common_material_kind','資料種類') ?></th>	<!--64-->
							<th style="width: 70px;">状態</th>
							<th style="width:291px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>		<!--307-->

<!--
							<th style="width: 50px;"></th>
							<th style="width:103px;"><?= $this->lang->line_or_def('common_deletion_target','削除対象') ?></th></th>
							<th style="width:133px;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<th style="width:103px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th style="width: 70px;"><?= $this->lang->line_or_def('common_material_kind','資料種類') ?></th>
							<th style="width:316px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
-->
						</tr>
					</table>
					<div class="list_select">
						<?php if(isset($class_material_list)) { ?>

							<table border=0>
								<table style="margin-top: 0px;width: 51px;" align="left">
									<?php foreach($class_material_list as $class_material) { ?>
										<tr style="height: 66px;">
											<td class="center_middle">
												<input type="button" onClick="location.href='/cms_class_material/download_file/<?= $class_material['class_material_id'] ?>/<?= $class_id ?>';;return false;" value="<?= $this->lang->line_or_def('common_download_abbreviation','DL') ?>" class="btn_r">
											</td>
										</tr>
									<?php } ?>
								</table>

								<table class="list" style="margin-top: 0px;width: 722px;">
									<?php foreach($class_material_list as $class_material) { ?>
										<tr style="height: 66px;">
											<td style="vertical-align: middle;width: 64px;text-align: center;">
												<input type="checkbox" name="delete_check[]" value=<?=$class_material['class_material_id']?> 
													<?php if( in_array($class_material['class_material_id'], $delete_checked) ){ ?>
													<?= 'checked' ?>
													<?php } ?>>
											</td>
											<td style="vertical-align: middle;width: 127px;">
												<img class="img_thumbnail" src="/file_container/get_class_material_thubmnail/<?=$class_id?>/<?=$class_material['teacher_id']?>/<?=$class_material['student_id']?>/<?=$class_material['class_material_id']?>/" alt="" />
											</td>
											<td style="vertical-align: middle;width: 64px;">
												<?=$class_material['class_material_id']?>
											</td>
											<td style="vertical-align: middle;width: 70px;">
												<?php if($class_material['teacher_id'] == -1){ ?>
													<?= $this->lang->line_or_def('common_student_presentation','受講者提出') ?>
												<?php }elseif($class_material['submit_flag'] == 0){ ?>
													<?= $this->lang->line_or_def('common_teacher_material','講師資料') ?>
												<?php }else{ ?>
													<?= $this->lang->line_or_def('common_teacher_note','講師ノート') ?>
												<?php } ?>
											</td>
											<td style="vertical-align: middle;width: 70px;">
												<?php if($class_material['status'] == 1){ ?>
													<?= '使用可能' ?>
												<?php }elseif($class_material['status'] == 2){ ?>
													<?= '変換中' ?>
												<?php }elseif($class_material['status'] == 11){ ?>
													<?= '使用不可' ?>
												<?php }else{ ?>
													<?= '使用不可' ?>
												<?php } ?>
											</td>
											<td style="vertical-align: middle;width: 291px;word-wrap:break-word;"> <!--width: 310px;-->
												<?=$class_material['material_logic_name']?>
											</td>
										</tr>
									<?php } ?>
								</table>
							</table>
						<?php }else{ ?>
							<br/><div style="text-align: center;"><?= $this->lang->line_or_def('msg_class_material_no_del','登録されている資料はありません') ?></div><br />
						<?php } ?>
					</div>

					<h3><?= str_replace("class_name", $class_list[0]['class_name'], 
						 $this->lang->line_or_def('msg_class_material_add_title','授業名『class_name』に登録できる資料') ); ?></h3>
					<table class="list" style="width: 773px;"><!--width: 775px;-->
						<tr>
							<th colspan=6 class="list_common">
								<?=$this->lang->line_or_def('msg_class_material_add_comment','授業に登録できる資料一覧　授業に登録したい資料にチェックをいれてください') ?>
							</th>
						</tr>
						<tr>
							<th style="width: 45px;"></th>
							<th style="width: 82px;"><?= $this->lang->line_or_def('common_registration_target','登録対象') ?></th>
							<th style="width:113px;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<th style="width: 82px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th style="width:268px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<th style="width:147px;"><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
<!--
							<th style="width: 50px;"></th>
							<th style="width: 88px;"><?= $this->lang->line_or_def('common_registration_target','登録対象') ?></th>
							<th style="width:119px;"><?= $this->lang->line_or_def('common_thumbnail','サムネイル') ?></th>
							<th style="width: 88px;"><?= $this->lang->line_or_def('common_id','ID') ?></th>
							<th style="width:277px;"><?= $this->lang->line_or_def('common_file_name','ファイル名') ?></th>
							<th style="width:153px;"><?= $this->lang->line_or_def('common_registrant','登録者') ?></th>
-->
						</tr>
					</table>
					<div class="list_select">
						<?php if(isset($material_list)) { ?>
							<table border=0>
								<table style="margin-top: 0px;width: 51px;" align="left">
									<?php foreach($material_list as $material) { ?>
										<tr style="height: 66px;">
											<td class="center_middle">
												<input type="button" onClick="window.open('/cms_material/detail/<?= $material['material_id']; ?>')" value="<?= $this->lang->line_or_def('common_detail','詳細') ?>" class='btn_r'>
											</td>
										</tr>
									<?php } ?>
								</table>

								<table class="list" style="margin-top: 0px;width: 722px; " align="right">
									<?php foreach($material_list as $material) { ?>
										<tr style="height: 66px;">
											<td style="vertical-align: middle;width: 82px;text-align: center;">
												<input type="checkbox" name="insert_check[]" value=<?=$material['material_id']?> 
													<?php if( in_array($material['material_id'], $insert_checked) ){ ?>
													<?= 'checked' ?>
													<?php } ?>>
											</td>
											<td style="vertical-align: middle;width: 113px;">
												<img class="img_thumbnail" src="/file_container/get_material_thubmnail/<?=$material['material_id']?>/" alt="" />
											</td>
											<td style="vertical-align: middle;width: 82px;">
												<?=$material['material_id']?>
											</td>
											<td style="vertical-align: middle;width: 268px; > <!--width: 271px; "-->
												<?=$material['material_logic_name']?>
											</td>
											<td style="vertical-align: middle;width: 147px;">
												<?=$material['teacher_name']?>
											</td>
										</tr>
									<?php } ?>
								</table>
							</table>
						<?php }else{ ?>
							<br/><div style="text-align: center;"><?= $this->lang->line_or_def('msg_class_material_no_add','選択できる資料はありません') ?></div><br />
						<?php } ?>
					</div>

				<div class="submit">
					<input type="image" src="/static/image/btn_back.png" onClick='location.href = "/cms_class/detail/<?= $class_id ?>";return false;' />
					<input type="image" src="/static/image/btn_register.png" />
					<input type="button" onClick="location.href='/cms_class_material/add_material/<?= $class_id ?>';;return false;" value="資料新規追加" class="btn_r">
				</div>
			</form>
			
		</div>
			<div class="clear"></div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
