<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>
<?php
	$data['callview'] = "exam2";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* h2内部 */
		#h2_title{
			color       : #fefefe;
			float       : left;
			line-height : 25px;
			width       : 55%;
		}
		#h2_result{
			float         : right;
			font-weight   : bold;
			line-height   : 25px;
			padding-right : 5px;
			text-align    : right;
			width         : 43%;
		}
		/* 管理講師「別の講師が選択されています」 */
		#teacher_change_message{
			color : #00A4E2;
		}
		/* 所属講座, 設問グループ */
		.cource_list, .group_list, .cource_list_ex, .group_list_ex{
			max-height	: 300px;
			overflow-y	: scroll;
		}
			/* 所属講座選択エリア, 設問グループ選択エリア */
			#cource_ul, #group_ul, #cource_ul_ex, #group_ul_ex{
				margin-top: 0px;
			}
				#cource_ul li, #group_ul li, #cource_ul_ex li, #group_ul_ex li{
					margin-left: 2px;
					margin-bottom: 0px;
				}
		/* ファイル 説明文 */
		.pdf_message{
			color     : #FF0000;
			font-size : 11px;
		}
	</style>

	<script type="text/javascript"><!--
		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
			// 所属講座「全て選択」ボタンの初期位置
			if($('.cource_list input:checked').length == 0){
				$("*[name=select_all_cource]").css("background-position", "center top");
			}else{
				$("*[name=select_all_cource]").css("background-position", "center bottom");
			}
			// 所属講座 配下のチェックボックス押下時の処理
			$('.cource_list').click(function (){
				if($('.cource_list input:checked').length == 0){
					$("*[name=select_all_cource]").css("background-position", "center top");
				}else{
					$("*[name=select_all_cource]").css("background-position", "center bottom");
				}
			});
		});

		//**************************************************
		// 画面読み込み後、講師コンボボックスの設定
		//**************************************************
		$(function () {
			var $default_teacher_id  = '<?=$this->libauth->get_teacher_id();?>';	// ログイン中のログインID
			var $selected_teacher_id = $('#teacher_id option:selected').val();		// 現在選択されたもの
			
			if($default_teacher_id != -1){
				if($default_teacher_id == $selected_teacher_id){
					$('#teacher_id').css("color", "#FEFEFE");
					$('#teacher_id').css("background-color", "#333333");
					$('#teacher_change_message').hide();
				}else{
					$('#teacher_id').css("color", "black");
					$('#teacher_id').css("background-color", "white");
					$('#teacher_change_message').show();
				}
				$("#teacher_id option").each(function(i){
					if($(this).val() == $default_teacher_id){
						$(this).css("color", "#FEFEFE"); 
						$(this).css("background-color", "#333333"); 
					}else{
						$(this).css("color", "black"); 
						$(this).css("background-color", "white"); 
					}
				})
			}else{
				$('#teacher_change_message').hide();
			}
		});
		//**************************************************
		// 所属講座「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_course(){
			if($('.cource_list input:checked').length){
				$('.cource_list input').removeAttr('checked');
				$("*[name=select_all_cource]").css("background-position", "center top");
			}
			else{
				$('.cource_list input').attr('checked','checked');
				$("*[name=select_all_cource]").css("background-position", "center bottom");
			}
			return false;
		}
		//**************************************************
		// 所属講座「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_course_ex(){
			if($('.cource_list_ex input:checked').length){
				$('.cource_list_ex input').removeAttr('checked');
				$("*[name=select_all_cource_ex]").css("background-position", "center top");
			}
			else{
				$('.cource_list_ex input').attr('checked','checked');
				$("*[name=select_all_cource_ex]").css("background-position", "center bottom");
			}
			return false;
		}
		//**************************************************
		// 設問グループ「全て選択」ボタン押下時の処理
		//**************************************************
		function select_all_group_ex(){
			if($('.group_list_ex input:checked').length){
				$('.group_list_ex input').removeAttr('checked');
				$("*[name=select_all_group_ex]").css("background-position", "center top");
			}
			else{
				$('.group_list_ex input').attr('checked','checked');
				$("*[name=select_all_group_ex]").css("background-position", "center bottom");
			}
			return false;
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
			<? $this->load->view('cms_exam2_download/_submenu', array(
				'selected'	=> 'exam2_download',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix" style="min-height: 18px;">
				</div>
				<?=form_open_multipart("cms_exam2_download/export")?>
					<table class="form">
						<tr>
							<th colspan="2">■エクスポート</th>
						</tr>
						<tr>
							<th width="160" >
								<?= $this->lang->line_or_def('common_position_course','所属講座') ?>
								<a href="#" onclick="select_all_course_ex();return false;" class="select_all_affiliation" name="select_all_cource_ex"></a>
							</th>
							<td>
								<div class="cource_list_ex">
									<ul class="list" id="cource_ul_ex">
									<?php 
										if( isset($lecture_cources) ) { 
											foreach( $lecture_cources as $cource ){ ?>
												<li>
												<input type="checkbox" name="exam2_problem_lectures_ex[]" id="lectures_<?=$cource['cource_id']?>_ex" value=<?=$cource['cource_id']?>
													<?php 
													if( isset($exam2_problem['exam2_problem_lectures']) ) {
														foreach( $exam2_problem['exam2_problem_lectures'] as $lecture) { 
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
												<label for="lectures_<?=$cource['cource_id']?>_ex"><?= htmlspecialchars( $cource['cource_name'], ENT_QUOTES, 'UTF-8') ?></label>
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
						</tr>

						<tr>
							<th width="160" >
								<?= $this->lang->line_or_def('common_product_name','商品名') ?>
							</th>
							<td>
								<input type="text" name="product_name" value="">
							</td>
						</tr>
						<tr>
							<th width="160" >
								<?= $this->lang->line_or_def('common_product_code','商品コード') ?>
							</th>
							<td>
								<input type="text" name="product_code" value="">
							</td>
						</tr>

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
