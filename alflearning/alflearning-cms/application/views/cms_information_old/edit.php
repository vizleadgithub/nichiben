<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "information";
	$this->load->view('header/header',$data);?>
	<script type="text/javascript">
		$(function(){
			//日付項目クリアリンク
			$('.clear_date').click(function(){$(this).prev().val(''); return false;});
			//datepicker設定
			$('#information_date' ).datepicker(datepickeroption);
			//datetimepicker設定
			$('#information_open' ).datetimepicker(datetimepickeroption);
			$('#information_close').datetimepicker(datetimepickeroption);
		});
		
		function set_tag(cap){
			var now_tags       = $('#tags').val();
			var now_tags_array = now_tags.split(",");
			
			if(now_tags.length==0){
				$('#tags').val(cap);
			}else{
				var buffer    = [];
				var _del_flag = 0;
				
				for(var i = 0; now_tags_array.length > i; i++){
					if(now_tags_array[i] != cap){
						buffer.push(now_tags_array[i]);
					}
					else{
						_del_flag = 1;
					}
				}
				if(!_del_flag){
					buffer.push(cap);
				}
				$('#tags').val(buffer.join(','));
			}
		}

		function checkradio(disp) {
			if(disp=="block"){
				$("#selected_information_tags").slideDown();
			}else{
				$("#selected_information_tags").slideUp();
			}
		}

	</script>
	<style type="text/css">
		TEXTAREA{
			width		: 100%;
			height		: 200px;
		}
		.display_target_school{
			line-height: 21px;
		}
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_information','お知らせ') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_information_comment','お知らせを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_information/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_information/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_information/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_information_input','お知らせの内容を入力してください') ?></h2>

				<?=form_open("cms_information/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<input type=hidden name=update_flg value='<?=set_value('update_flg', $information['update_flg'])?>'>
					<input type=hidden name=information_id value='<?=set_value('information_id', $information['information_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_date','日付') ?></th>
							<td >
								<input type="text" name="information_date" size="10" value="<?=set_value('information_date',$information['information_date'])?>" id="information_date" readonly>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_title','タイトル') ?></th>
							<td>
								<input type="text" name="information_title" size="45" value="<?=set_value('information_title',$information['information_title'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="information_caption"><?=set_value('information_caption',$information['information_caption'])?></textarea>
							</td>
						</tr>

						<tr>
							<th><?= $this->lang->line_or_def('common_tag','タグ') ?></th>
							<td>
								<div id="selected_information_tags" <?= ($information['school_id']==0) ? "style='display:none;'" : ""; ?>>
								<input type=text name="information_tags" maxlength="256" size="50" value='<?=set_value('information_tags',$information['information_tags'])?>' id="tags">
								<?php if($tags_dropdown): ?>
									<?php $count = 0; ?>
									<?php foreach($tags_dropdown as $tagKey => $cnt): ?>
										<? if(($tagKey !== 'タグなし') && ($tagKey !== '') ){
											$count = $count + 1;
										} ?>
									<?php endforeach; ?>
									<?php if($count > 0): ?>
										<div style=" margin-top: 5px;line-height:20px;"><?= $this->lang->line_or_def('common_registered_tag','登録済みタグ') ?>&nbsp;:&nbsp;
											<?php foreach($tags_dropdown as $tagKey => $cnt) { ?>
												<? if(($tagKey !== 'タグなし') && ($tagKey !== '') ): ?>
													<a href="#" onclick="set_tag('<?= htmlspecialchars( $tagKey, ENT_QUOTES, 'UTF-8') ?>');return false;"><?= htmlspecialchars( $tagKey, ENT_QUOTES, 'UTF-8') ?></a>&nbsp;&nbsp;
												<? endif; ?>
											<?php } ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<div style="padding-top: 5px;"><?= $this->lang->line_or_def('msg_video_tag_input_limit','※カンマ含む256文字まで設定可能　入力例：記入例：数学 / 科学,科学 / 歴史,日本史,弥生時代') ?></div>
								</div>
							</td>
						</tr>

						<?php if($this->libauth->get_teacher_id() == -1): ?>
							<tr>
								<th width="160"><?= $this->lang->line_or_def('common_indication_school','表示対象の学校') ?></th>
								<td>
									<div class="display_target_school">
										<input type="radio" name="school_id" onclick="checkradio('block')" 
											value=<?=$this->libauth->get_school_id(); ?> <?=(set_value('school_id',$information['school_id']) > 0)?'checked':''?> >
										<label>
											<?= $this->lang->line_or_def('common_school','学校').'&nbsp;"'.$this->libauth->get_school_name().'"&nbsp;'.$this->lang->line_or_def('common_only','のみ'); ?>
										</label>
									</div>
									<div class="display_target_school">
										<input type="radio" name="school_id" onclick="checkradio('none')"
											value=0 <?=(set_value('school_id',$information['school_id'])==0)?'checked':''?> >
										<label>
											<?= $this->lang->line_or_def('common_to_all_school','全ての学校') ?><?= str_repeat("&nbsp;", 5); ?><?= $this->lang->line_or_def('msg_select_case_all_school','※こちらを選択した場合、上記タグは保存されません') ?> 
										</label>
									</div>
								</td>
							</tr>
						<?php else: ?>
							<input type=hidden name=school_id value=<?=set_value('school_id',$information['school_id'])?> >
						<?php endif; ?>

						<tr>
							<th><?= $this->lang->line_or_def('common_display_target','表示対象') ?></th>
							<td >
								<?= $this->lang->line_or_def('common_teacher','講師') ?>：<? echo form_checkbox('show_teacher', '1', $information['show_teacher']); ?>
								<?= str_repeat("&nbsp;", 6); ?>
								<?= $this->lang->line_or_def('common_student','受講者') ?>：<? echo form_checkbox('show_student', '1', $information['show_student']); ?>
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
							<td >
								<input type="text" name="information_open" size="19" value="<?=set_value('information_open',$information['information_open'])?>" id="information_open" readonly>
								<?= $this->lang->line_or_def('common_range','～') ?>
								<input type="text" name="information_close" size="19" value="<?=set_value('information_close',$information['information_close'])?>" id="information_close" readonly>
							</td>
						</tr>
					</table>
					<div class="submit">
						<input type='image' src='/static/image/btn_confirm.png' />
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
