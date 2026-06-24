<?php
	$this->lang->load('common');
	$this->lang->load('msg');
?>

<?php
	$data['callview'] = "admin_top";
	$this->load->view('header/header',$data);?>
	<style type="text/css">
		#contents_main UL.list{
			min-height	: 200px;
		}

		/* [2012/10/05]サブメニュー用 */
		#wrapper #main #menu_sub UL .sub {
			background:center transparent;
			float: left;
			height: 40px;
			width: 160px;
			overflow:hidden;
		}
		#wrapper #main #menu_sub UL .selected_sub {
			background:center white;
			float: left;
			height: 40px;
			width: 160px;
			overflow:hidden;
		}
		
		#information_table{
			width: 100%;
		}
		#information_table .information_title{
			text-align: right;
			height: 25px;
			line-height: 25px;
			width: 10%;
		}
		#information_table .information_detail{
			text-align: left;
			width: 90%;
		}
	}
	
	</style>
</head>

<body>
	<?php 
		$this->load->view('header/body_header', array());
	?>

	<div id="wrapper">
		<h1 class="claerfix">
			<div class="title"><?= $this->lang->line_or_def('common_outside_corporation','外部連携') ?></div>
			<div class="comment">eLearning Manager</div>
		</h1>

		<div id="main">
			<? $this->load->view('admin_top/_submenu', array(
				'selected'		=> 'outside',
				'select_tag'	=> 'elearningmanager',
			));?>

			<div id="contents_main">
				<h2><?= $this->lang->line_or_def('common_setting_completion','設定完了') ?></h2>
				<h3><?= $this->lang->line_or_def('msg_outside_corporation_commit_elm','eLearning Managerの設定が完了しました') ?></h3>

				<div class="submit">
					<a href="/admin_top/"><?= $this->lang->line_or_def('common_back','戻る') ?></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php $this->load->view('header/body_footer');?>
</body>
</html>
