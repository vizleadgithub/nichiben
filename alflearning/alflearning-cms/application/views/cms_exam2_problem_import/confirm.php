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
			width       : 60%;
		}
		#h2_result{
			float         : right;
			font-weight   : bold;
			line-height   : 25px;
			padding-right : 5px;
			text-align    : right;
			width         : 38%;
		}
		/* ローディングバー */
		#div_loading_bar{
			text-align : center; 
			margin     : 50px 0 50px 0;
		}
		/* インポート結果 */
		#div_import_result{
			display : none;
		}
		/* インポート結果 - タイトル */
		#ul_import_result_title{
			margin        : 10px 10px 0 10px;
			border-bottom : 1px solid #a4a5b3;
		}
			#ul_import_result_title .div_no{
				float : left;
				width : 70px;
			}
			#ul_import_result_title .div_value{
				float : left;
				width : 70px;
			}
			#ul_import_result_title .div_message{
				float : left;
				width : 610px;
			}
		/* インポート結果 - リスト */
		#ul_import_result_values{
			margin : 0 10px 10px 10px;
		}
		#ul_import_result_values .li_nomal{
			margin : 5px 0 5px 0;
			color  : #000000;
		}
			#ul_import_result_values .li_error{
				margin : 5px 0 5px 0;
				color  : #FF0000;
			}
				#ul_import_result_values .div_no{
					float : left;
					width : 70px;
				}
				#ul_import_result_values .div_value{
					float : left;
					width : 70px;
				}
				#ul_import_result_values .div_message{
					float : left;
					width : 610px;
				}
	</style>
	<script type="text/javascript">
		//--------------------------------------------------
		//--------------------------------------------------
		$(function(){
		});

		//**************************************************
		// 画面読み込み後、インポート処理を実行
		//**************************************************
		$(function () {
			setTimeout(function(){
				$.ajax({
					url: "/cms_exam2_problem_import/commit",
					type: "POST",
					data: "select_school_id=<?= $this->libauth->get_school_id(); ?>",

					success: function(response) {
						if(response){
							var $output = '';
							var $line_count_all = 0;
							var $line_count_ok  = 0;
							for (var i=0; i< response.length; i++) {
								$line_count_all = $line_count_all + 1;
								if(response[i]['value']=='OK'){
									$line_count_ok  = $line_count_ok + 1;
								}
								
								if(response[i]['value']=='NG'){
									$output = $output + '<li class="li_error">';
								}else{
									$output = $output + '<li class="li_nomal">';
								}
								
								$output = $output + '<div class="div_no">' + response[i]['no'] + '</div>';
								$output = $output + '<div class="div_value">' + response[i]['value'] + '</div>';
								$output = $output + '<div class="div_message">' + response[i]['message'] + '</div>';
								$output = $output + '<div style="clear:both;"></div>';
								$output = $output + '</li>';
							}
							
							$("#ul_import_result_values").append($output);
							
							$("#div_loading_bar").css("display","none");
							$("#div_import_result").css("display","block");
							
							$("#h2_title").text("<?= $this->lang->line_or_def('msg_exam2_problem_import_commit','設問へのインポート結果') ?>");
							$("#h2_result").append($line_count_ok+"&nbsp;/&nbsp;"+$line_count_all+"&nbsp;"+"<?= $this->lang->line_or_def('common_cases','件') ?>&nbsp;<?= $this->lang->line_or_def('common_preservation_success','保存成功') ?>");
						}
					}
				});
			}, 500);
		});
		
		//**************************************************
		//詳細確認画面　修正ボタン押下
		//**************************************************
		function edit_item(){
			location.href ="<?=base_url()?>cms_exam2_problem/edit/";
		}
		//**************************************************
		//詳細確認画面　複製して新規登録ボタン押下
		//**************************************************
		function copy_newdata_item(id){
			location.href ="<?=base_url()?>cms_exam2_problem/newdata/" + id;
		}
		//**************************************************
		//詳細確認画面　削除ボタン押下
		//**************************************************
		function delete_item(id, msg){
			if(window.confirm( msg )){
				location.href = "<?=base_url()?>cms_exam2_problem/delete_item/" + id ;
			}
		}
	</script>
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
			<? $this->load->view('cms_exam2_problem_import/_submenu', array(
				'selected'	=> 'exam2_problem_import',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix" style="min-height: 18px;">
				</div>
				
				<h2>
					<div id="h2_title"><?= $this->lang->line_or_def('msg_exam2_problem_import_loading','設問へのインポート中') ?></div>
					<div id="h2_result"></div>
					<div style="clear:both;"></div>
				</h2>
				
				<? // 前画面で設定した管理講師・所属講座・設問グループの表示 ?>
				<table class="form">
<?php if(false){ ?>
				<? if( getenv('URL_SERVICE') != 'mitemo' ): ?>
					<tr>
						<th width="160"><?= $this->lang->line_or_def('common_management_teacher','管理講師') ?></th>
						<td ><?= $exam2_problem['teacher_name']; ?>
						</td>
					</tr>
				<? endif; ?>
<?php } ?>
					
					<tr>
						<th width="160"><?= $this->lang->line_or_def('common_position_course','所属講座') ?></th>
						<td >
							<?php
								$flg = FALSE;
								if( isset($exam2_problem['exam2_problem_lectures_name']) ) {
									foreach( $exam2_problem['exam2_problem_lectures_name'] as $name) { 
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
						<th><?= $this->lang->line_or_def('common_exam2_problem_group','設問グループ') ?></th>
						<td >
							<?php
								$flg = FALSE;
								if( isset($exam2_problem['exam2_problem_groups_name']) ) {
									foreach( $exam2_problem['exam2_problem_groups_name'] as $name) { 
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
				</table>
				
				<? // ローディングバー画像 ?>
				<div id="div_loading_bar">
					<img src="/static/image/loading_bar.gif" alt="loading_bar">
				</div>
				
				<? // インポート結果 ?>
				<div id="div_import_result">
					<ul id="ul_import_result_title">
						<li>
							<div class="div_no"><?= $this->lang->line_or_def('common_line_number','行番号') ?></div>
							<div class="div_value"><?= $this->lang->line_or_def('common_result','結果') ?></div>
							<div class="div_message"><?= $this->lang->line_or_def('common_note','備考') ?></div>
							<div style="clear:both;"></div>
						</li>
					</ul>
					<ul id="ul_import_result_values">
					</ul>
					
					<div class="submit">
						<input type="image" src="/static/image/btn_back.png" onClick="location.href='<?=base_url()?>cms_exam2_problem_import';return false;" />
					</div>
				</div>
				
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
