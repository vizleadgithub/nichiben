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
					<input type="hidden" name="update_flg"        value="<?=set_value('update_flg', $information['update_flg'])?>">
					<input type="hidden" name="information_id"    value="<?=set_value('information_id', $information['information_id'])?>">
					<input type="hidden" name="information_type"  value="<?=set_value('information_type',$information['information_type'])?>">
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_date','日付') ?></th>
							<td >
								<input type="text" name="information_date" size="10" value="<?=set_value('information_date',$information['information_date'])?>" id="information_date">
							</td>
						</tr
						<?php /*
						<tr>
							<th><?= $this->lang->line_or_def('common_information_type','種別') ?></th>
							<td>
								<div class="display_target_school">
									<label>
										<input type="radio" name="information_type" onclick="" value="0" <?=(set_value('information_type',$information['information_type']) == 0)?'checked':''?> >
										お知らせ
									</label>
									<label>
										<input type="radio" name="information_type" onclick="" value="1" <?=(set_value('information_type',$information['information_type']) == 1)?'checked':''?> >
										eラーニング
									</label>
								</div>
							</td>
						</tr>
						*/ ?>
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
							<th><?= $this->lang->line_or_def('common_url','URL') ?></th>
							<td>
								<input type="text" name="information_url" size="45" value="<?=set_value('information_url',$information['information_url'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_public_period','公開期間') ?></th>
							<td >
								<input type="text" name="information_open" size="19" value="<?=set_value('information_open',$information['information_open'])?>" id="information_open" >
								<?= $this->lang->line_or_def('common_range','～') ?>
								<input type="text" name="information_close" size="19" value="<?=set_value('information_close',$information['information_close'])?>" id="information_close" >
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_information_topfit','先頭枠への固定表示') ?></th>
							<td>
								<div class="display_target_school">
									<label>
									<input type="checkbox" name="information_topfit" onclick="" value="1" <?=(set_value('information_topfit',$information['information_topfit']) == 1)?'checked':''?> >
										する
									</label>
								</div>
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
