<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "student";
	$this->load->view('header/header',$data);?>
	<style type="text/css"><!--
		TEXTAREA{
			width : 100%;
			height : 70px;
		}
	// --></style>
	<script type="text/javascript">
		var students_checked   = {};
		var student_all_search = 0;// 一覧ボタン押下フラグ
		
		$(function(){
			// グループ所属の受講生情報の取得
			<?php if( isset($student_group['position_students']) ) {
				foreach( $student_group['position_students'] as $lecture) { ?>
					students_checked[<?= htmlspecialchars( $lecture, ENT_QUOTES, 'UTF-8') ?>] = true;
			<?php } } ?>

			// チェックボックスと全選択ボタン連動（受講者）
			$('#student_list').click(function (){
				$("#student_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						students_checked[$(this).val()] = true;
					}else{
						delete students_checked[$(this).val()];
					}
				});
				if($('#student_list input:checked').length == 0){
					$(".select_all_affiliation.select_student").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_student").css("background-position", "center bottom");
				}
				var check_count = 0;
				for (var key in students_checked) {
					check_count = check_count + 1;
				}
				$("#student_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			});

			// 受講生-一覧ボタン押下時
			$("#btn_all_student").click(function() {
				student_all_search = 1;
				document.getElementById('btn_search_student').click();
			});
			
			// 受講生-検索ボタン押下時
			$("#btn_search_student").click(function() {
				var student_freeword = $("#student_freeword").val();
				var cource_id        = 0;	//$("*[name=cource_id]").val();
				
				// 一覧ボタン時は検索条件初期化
				if(student_all_search==1){
					student_freeword   = '';
					student_all_search = 0;
				}
				
				$.ajax({
					url: "/cms_cource/get_cource_student",
					type: "POST",
					data: "free_word="+student_freeword+"&cource_id="+cource_id,
					
					success: function(response) {
						// 元にある行を削除
						$("#student_ul li").remove();
						
						// 全て選択ボタンの初期化
						$(".select_all_affiliation.select_student").css("background-position", "center top");
						
						if(response){
							// 取得したデータを行に入れる
							for (var i=0; i< response.length; i++) {
								var check_flag = '';
								if(students_checked[response[i]['student_id']]){
									check_flag = 'checked';
									$(".select_all_affiliation.select_student").css("background-position", "center bottom");	// 選択済みが１つ以上ある場合、ボタン位置変更
								}
								$("#student_ul").append(
									$('<li>').append(
										$('<input type="checkbox" name="position_students[]" id="student_'+response[i]['student_id']+'" value='+response[i]['student_id']+' '+check_flag+'>')
									).append(
									$('<label for="student_'+response[i]['student_id']+'">').text(
										' [No'+response[i]['student_id']+'] '+response[i]['student_name'] + ' <' + response[i]['student_email'] + '>'
									))
								);
							}
							
							$("#student_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:"+(i)+" / "+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= $student_group['student_all_count']; ?>);
							$(".select_all_affiliation.select_student").css('display','block');
						}else{
							$("#student_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:0 / "+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= $student_group['student_all_count']; ?>);
							$(".select_all_affiliation.select_student").css('display','none');
						}
					}
				});
			});

			// 確認ボタン押下時（submit実行前処理）
			$('form').submit(function(){
				//console.log('test1')
				$('form').serialize();
				
				// 選択済み受講者
				for (var key in students_checked) {
					if(students_checked[key] == true){
						$('<input />').attr('type', 'hidden')
									  .attr('name', 'position_students_array[]')
									  .attr('value', key)
									  .appendTo('form');
					}
				}
			});
		});

		// 所属受講者チェックボックスALL-ON or ALL-OFF
		function select_all_student(){
			if($('#student_list input:checked').length){
				$('#student_list input').removeAttr('checked');
				$(".select_all_affiliation.select_student").css("background-position", "center top");
			}else{
				$('#student_list input').attr('checked','checked');
				$(".select_all_affiliation.select_student").css("background-position", "center bottom");
			}
			
			// 値の初期化
			$("#student_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					students_checked[$(this).val()] = true;
				}else{
					delete students_checked[$(this).val()];
				}
			});
			var check_count = 0;
			for (var key in students_checked) {
				check_count = check_count + 1;
			}
			$("#student_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			return false;
		}

		//  受講者テキストボックス上でのEnterキーで検索ボタン押下
		function submitStop_student(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_search_student').click();
				return false;
			}
		}
	</script>

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
			<? $this->load->view('cms_student_group/_submenu', array());?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_student_group/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_student_group/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_〓','受講者グループの情報を入力してください') ?></h2>

				<?=form_open("cms_student_group/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?= (isset($error_msg) && $error_msg ? '<div class="error">'.$error_msg.'</div>' : ''); ?>
					<input type="hidden" name="update_flg"       value='<?=set_value('update_flg'       , $student_group['update_flg'])?>'>
					<input type="hidden" name="student_group_id" value='<?=set_value('student_group_id' , $student_group['student_group_id'])?>'>

					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_group_name','グループ名') ?></th>
							<td>
								<input type="text" name="student_group_name" size="45" value="<?=set_value('student_group_name',$student_group['student_group_name'])?>">
							</td>
						</tr>
						<tr>
							<th><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="student_group_caption" ><?=set_value('student_group_caption',$student_group['student_group_caption'])?></textarea>
							</td>
						</tr>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_student','受講者') ?>
								<div id="student_check_count" style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($student_group['position_students']); ?>]
								</div>
								<a href="#" onclick="select_all_student();return false;" class="select_all_affiliation select_student" style="display:none;"></a>
							</th>
							<td>
								<?= $this->lang->line_or_def('common_freeword','フリーワード') ?>&nbsp;
								<input type="text" id="student_freeword" value="" onKeyPress="return submitStop_student(event);"/>&nbsp;
								<input type="button" id="btn_search_student" value="検索" />
								<input type="button" id="btn_all_student"    value="一覧" />
								<div style="float: right; margin-top: 6px; margin-right: 10px;">
									<label id="student_count">
										<?= $this->lang->line_or_def('common_indication','表示') ?>:0&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_total','総') ?>:<?= $student_group['student_all_count']; ?>
									</label>
								</div><div style="clear:both;"></div>
								<div id="student_list">
									<ul class="list" id="student_ul"></ul>
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
