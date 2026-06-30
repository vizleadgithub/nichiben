<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam2";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 設問グループ 説明 */
		TEXTAREA{
			width  : 100%;
			height : 70px;
		}
		/* 設問 ローディングバー */
		#exam2_problem_ul .li_loading_bar{
			border-bottom  : 0px none;
			height         : 40px;
			vertical-align : middle;
			line-height    : 40px;
			text-align     : left;
			margin-left    : 75px;
		}
	</style>
	<script type="text/javascript"><!--
		var exam2_problems_checked   = {};    // 選択した設問
		var exam2_problem_all_search = 0;     // 一覧ボタン押下フラグ

		$(function(){
			//--------------------------------------------------
			// 設問グループ所属の設問情報の取得
			//--------------------------------------------------
			<?php if( isset($exam2_problem_group['position_exam2_problems']) ) {
				foreach( $exam2_problem_group['position_exam2_problems'] as $lecture) { ?>
					exam2_problems_checked[<?= htmlspecialchars( $lecture, ENT_QUOTES, 'UTF-8') ?>] = true;
			<?php } } ?>
			//--------------------------------------------------
			// チェックボックスと全選択ボタン連動（設問）
			//--------------------------------------------------
			$('#exam2_problem_list').click(function (){
				$("#exam2_problem_list input:checkbox").map(function() {
					if( $(this).attr('checked')) {
						exam2_problems_checked[$(this).val()] = true;
					}else{
						delete exam2_problems_checked[$(this).val()];
					}
				});
				if($('#exam2_problem_list input:checked').length == 0){
					$(".select_all_affiliation.select_exam2_problem").css("background-position", "center top");
				}else{
					$(".select_all_affiliation.select_exam2_problem").css("background-position", "center bottom");
				}
				var check_count = 0;
				for (var key in exam2_problems_checked) {
					check_count = check_count + 1;
				}
				$("#exam2_problem_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			});
			//--------------------------------------------------
			// 設問 一覧ボタン押下時
			//--------------------------------------------------
			$("#btn_all_exam2_problem").click(function() {
				exam2_problem_all_search = 1;
				document.getElementById('btn_search_exam2_problem').click();
			});
			//--------------------------------------------------
			// 設問 検索ボタン押下時
			//--------------------------------------------------
			$("#btn_search_exam2_problem").click(function() {
				var exam2_problem_freeword = $("#exam2_problem_freeword").val();
				var cource_id             = 0;
				
				// 一覧ボタン時は検索条件初期化
				if(exam2_problem_all_search==1){
					exam2_problem_freeword   = '';
					exam2_problem_all_search = 0;
				}
				// ローディングバーの表示
				$("#exam2_problem_ul li").remove();
				$("#exam2_problem_ul").append(
					$('<li class="li_loading_bar">').append(
						$('<img src="/static/image/loading_bar.gif" alt="loading_bar">'
					))
				);
				// 設問取得・表示処理
				setTimeout(function(){
					$.ajax({
						url: "/cms_cource/get_cource_exam2_problem",
						type: "POST",
						data: "free_word="+exam2_problem_freeword+"&cource_id="+cource_id,
						
						success: function(response) {
							// 元にある行を削除
							$("#exam2_problem_ul li").remove();
							
							// 全て選択ボタンの初期化
							$(".select_all_affiliation.select_exam2_problem").css("background-position", "center top");
							
							if(response){
								// 取得したデータを行に入れる
								for (var i=0; i< response.length; i++) {
									var check_flag = '';
									if(exam2_problems_checked[response[i]['exam2_problem_id']]){
										check_flag = 'checked';
										$(".select_all_affiliation.select_exam2_problem").css("background-position", "center bottom");	// 選択済みが１つ以上ある場合、ボタン位置変更
									}
									$("#exam2_problem_ul").append(
										$('<li></li>').append(
											$('<input type="checkbox" name="position_exam2_problems[]" id="exam2_problem_'+response[i]['exam2_problem_id']+'" value='+response[i]['exam2_problem_id']+' '+check_flag+'>')
										).append(

									<? if( getenv('URL_SERVICE')=='mitemo' ): ?>
										$('<label for="exam2_problem_'+response[i]['exam2_problem_id']+'"></label>').text(
											' [No'+response[i]['exam2_problem_id']+'] '+response[i]['exam2_problem_name'] + ' [' + "<?= $this->lang->line_or_def('common_answer_points','解答配点'); ?>" + ':' + response[i]['answer_point'] + ']'
										))
									<? else: ?>
										$('<label for="exam2_problem_'+response[i]['exam2_problem_id']+'"></label>').text(
											' [No'+response[i]['exam2_problem_id']+'] '+response[i]['exam2_problem_name'] + ' [' + "<?= $this->lang->line_or_def('common_management_teacher','管理講師'); ?>" + ':' + response[i]['teacher_name'] + '] [' + "<?= $this->lang->line_or_def('common_answer_points','解答配点'); ?>" + ':' + response[i]['answer_point'] + ']'
										))
									<? endif; ?>
									);
								}
								
								$("#exam2_problem_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:"+(i)+" / "+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $exam2_problem_group['exam2_problem_all_count'], ENT_QUOTES, 'UTF-8') ?>);
								$(".select_all_affiliation.select_exam2_problem").css('display','block');
							}else{
								$("#exam2_problem_count").text("<?= $this->lang->line_or_def('common_indication','表示') ?>:0 / "+"<?= $this->lang->line_or_def('common_total','総') ?>:"+<?= htmlspecialchars( $exam2_problem_group['exam2_problem_all_count'], ENT_QUOTES, 'UTF-8') ?>);
								$(".select_all_affiliation.select_exam2_problem").css('display','none');
							}
						}
					});
				}, 500);
			});
			//--------------------------------------------------
			// 確認ボタン押下時（submit実行前処理）
			//--------------------------------------------------
			$('form').submit(function(){
				//console.log('test1')
				$('form').serialize();
				
				// 選択済み受講者
				for (var key in exam2_problems_checked) {
					if(exam2_problems_checked[key] == true){
						$('<input />').attr('type', 'hidden')
									  .attr('name', 'position_exam2_problems_array[]')
									  .attr('value', key)
									  .appendTo('form');
					}
				}
			});
		
		});
		
		//**************************************************
		// 設問チェックボックスALL-ON or ALL-OFF
		//**************************************************
		function select_all_exam2_problem(){
			if($('#exam2_problem_list input:checked').length){
				$('#exam2_problem_list input').removeAttr('checked');
				$(".select_all_affiliation.select_exam2_problem").css("background-position", "center top");
			}else{
				$('#exam2_problem_list input').attr('checked','checked');
				$(".select_all_affiliation.select_exam2_problem").css("background-position", "center bottom");
			}
			// 値の初期化
			$("#exam2_problem_list input:checkbox").map(function() {
				if( $(this).attr('checked') ) {
					exam2_problems_checked[$(this).val()] = true;
				}else{
					delete exam2_problems_checked[$(this).val()];
				}
			});
			var check_count = 0;
			for (var key in exam2_problems_checked) {
				check_count = check_count + 1;
			}
			$("#exam2_problem_check_count").text("[<?= $this->lang->line_or_def('common_choice_count','選択数') ?> : "+check_count+"]");
			return false;
		}
		//**************************************************
		//  設問テキストボックス上でのEnterキーで検索ボタン押下
		//**************************************************
		function submitStop_exam2_problem(e){
			if (!e) var e = window.event;
			if(e.keyCode == 13){
				document.getElementById('btn_search_exam2_problem').click();
				return false;
			}
		}
	// --></script> 
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam2','アンケート管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam2_comment','アンケートを管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam2_problem_group/_submenu', array(
				'selected'	=> 'exam2_problem_group',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam2_problem_group/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam2_problem_group/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
				</div>

				<h2><?= $this->lang->line_or_def('msg_exam2_problem_group_input','設問グループの情報を入力してください') ?></h2>

				<?=form_open_multipart("cms_exam2_problem_group/confirm")?>
					<?=validation_errors('<div class="error">', '</div>'); ?>
					<?=(isset($overlap_error_msg)?'<div class="error">'.htmlspecialchars($overlap_error_msg, ENT_QUOTES, 'UTF-8').'</div>':'')?>
					<input type="hidden" name="update_flg"            value='<?=set_value('update_flg'                , $exam2_problem_group['update_flg'])?>'>
					<input type="hidden" name="exam2_problem_group_id" value='<?=set_value('cms_exam2_problem_group_id' , $exam2_problem_group['exam2_problem_group_id'])?>'>
					<table class="form">
						<tr>
							<th width="160"><?= $this->lang->line_or_def('common_exam2_problem_group_name','設問グループ名') ?></th>
							<td>
								<input type=text name="exam2_problem_group_name" maxlength="256" size="30" value='<?=set_value('exam2_problem_group_name',$exam2_problem_group['exam2_problem_group_name'])?>'>
							</td>
						</tr>
						<tr>
							<th style="vertical-align: top;"><?= $this->lang->line_or_def('common_caption','説明') ?></th>
							<td >
								<textarea name="exam2_problem_group_caption" ><?=set_value('exam2_problem_group_caption',$exam2_problem_group['exam2_problem_group_caption'])?></textarea>
							</td>
						</tr>
						<tr>
							<th>
								<?= $this->lang->line_or_def('common_exam2_problem','設問') ?>
								<div id="exam2_problem_check_count" style="text-align:center;">
									[<?= $this->lang->line_or_def('common_choice_count','選択数') ?>&nbsp;:&nbsp;<?= count($exam2_problem_group['position_exam2_problems']); ?>]
								</div>
								<a href="#" onclick="select_all_exam2_problem();return false;" class="select_all_affiliation select_exam2_problem" style="display:none;"></a>
							</th>
							<td>
								<?= $this->lang->line_or_def('common_freeword','フリーワード') ?>&nbsp;
								<input type="text" id="exam2_problem_freeword" value="" onKeyPress="return submitStop_exam2_problem(event);"/>&nbsp;
								<input type="button" id="btn_search_exam2_problem" value="検索" />
								<input type="button" id="btn_all_exam2_problem"    value="一覧" />
								<div style="float: right; margin-top: 6px; margin-right: 10px;">
									<label id="exam2_problem_count">
										<?= $this->lang->line_or_def('common_indication','表示') ?>:0&nbsp;/&nbsp;<?= $this->lang->line_or_def('common_total','総') ?>:<?= htmlspecialchars( $exam2_problem_group['exam2_problem_all_count'], ENT_QUOTES, 'UTF-8') ?>
									</label>
								</div><div style="clear:both;"></div>
								<div id="exam2_problem_list">
									<ul class="list" id="exam2_problem_ul"></ul>
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
