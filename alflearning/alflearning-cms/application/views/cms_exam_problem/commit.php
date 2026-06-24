<?php
	$this->lang->load('common');
	$this->lang->load('msg');
	$this->lang->load('error');
?>
<?php
	$data['callview'] = "exam";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		/* 画面上、複製して新規登録ボタン */
		#wrapper #main .toolbar A.btn_copy_newdata{
			display         : block;
			height          : 18px;
			float           : left;
			margin          : 0 10px 0 40px;
			text-decoration : none;
			text-align      : center;
			background      : url('/static/image/abtn_copy_newdata.png') no-repeat;
			width           : 134px;
		}
			#wrapper #main .toolbar A.btn_copy_newdata:hover
			{
				background-position	: 0px -18px;
			}
			#wrapper #main .toolbar A.btn_copy_newdata SPAN
			{
				display    : block;
				color      : #FFFFFF;
				font-size  : 11px;
				margin     : 3px 0 0 0;
			}
			#wrapper #main .toolbar A.btn_copy_newdata.selected
			{
				background-position : 0px -18px;
			}
	</style>
	<script type="text/javascript">
		//**************************************************
		//詳細確認画面　複製して新規登録ボタン押下
		//**************************************************
		function copy_newdata_item(id){
			location.href ="<?=base_url()?>cms_exam_problem/newdata/" + id;
		}
	</script>
<!-- head --></head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_heading_exam','問題管理') ?></div>
			<div class="comment"><?= $this->lang->line_or_def('msg_exam_comment','問題（テスト）を管理します') ?></div>
		</h1>

		<div id="main">
			<? $this->load->view('cms_exam_problem/_submenu', array(
				'selected'	=> 'exam_problem',
			));?>

			<div id="contents_main">
				<div class="toolbar clearfix">
					<a class="btn_seach selected" href="/cms_exam_problem/"><span><?= $this->lang->line_or_def('common_search','検索') ?></span></a>
					<a class="btn_add" href="/cms_exam_problem/newdata"><span><?= $this->lang->line_or_def('common_new_registration','新規登録') ?></span></a>
					<? if(isset($exam_problem_id)): ?>
						<a class="btn_copy_newdata" href="/cms_exam_problem/newdata/<?= $exam_problem_id; ?>"><span><?= $this->lang->line_or_def('common_reproduce_new_registration','複製して新規登録') ?></span></a>
					<? endif; ?>
				</div>
				<h2><?= $this->lang->line_or_def('msg_exam_problem_commit','設問情報更新') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_commit_detail','正常に完了しました。') ?></h3>

				<div class="submit">
					<a href="<?=site_url('cms_exam_problem')?>"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
