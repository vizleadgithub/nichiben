<?php /* Smarty version 2.6.31, created on 2025-10-20 16:56:43
         compiled from main_frame.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'main_frame.tpl', 129, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $this->_tpl_vars['admin_main_title']; ?>
　|　JFBA総合研修サイト</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="/static/image/favicon.ico" >
	<link rel="stylesheet" href="/alfproduct/css/common.css?_=20250305" type="text/css" />
	<link rel="stylesheet" href="/alfproduct/css/header.css?_=20250305" type="text/css" />
	<link rel="stylesheet" href="/alfproduct/css/main.css?_=20250305" type="text/css" />

	<link rel="stylesheet" media="all" type="text/css" href="/alfproduct/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="all" type="text/css" href="/alfproduct/css/jquery-ui-timepicker-addon.css" /> 
	<!--<script type="text/javascript" src="/alfproduct/js/jquery-1.8.3.min.js"></script>-->
	<script type="text/javascript" src="/static/js/jquery-3.7.1.min.js"></script>
	<script type="text/javascript" src="/alfproduct/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="/alfproduct/js/jquery.ui.datepicker-ja.js"></script>
	<script type="text/javascript" src="/alfproduct/js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="/alfproduct/js/jquery-ui-sliderAccess.js"></script>
	<script type="text/javascript">
		var datetimepickeroption = {
			dateFormat       : 'yy/mm/dd'
			,timeFormat       : 'HH:00'
			,showHour         : true
			,showMinute       : false
			,showSecond       : false
			,showOtherMonths  : true
			,selectOtherMonths: true
			,changeYear       : true
			,changeMonth      : true
			,yearRange        : '1900:2100'
			,showButtonPanel  : true
			,currentText      : '現在'
			,closeText        : '閉じる'
			,timeText         : '時刻'
			,hourText         : '時'
			,minuteText       : '分'
			,secondText       : '秒'
		};
		var datepickeroption = {
			dateFormat       : 'yy/mm/dd'
			,showOtherMonths  : true
			,selectOtherMonths: true
			,changeYear       : true
			,changeMonth      : true
			,yearRange        : '1900:2100'
			,showButtonPanel  : true
		};
		$(function() { 
			$('#start_date').datetimepicker(datetimepickeroption); 
			$('#end_date').datetimepicker(datetimepickeroption); 
			$('#search_start_regist_date').datetimepicker(datetimepickeroption); 
			$('#search_end_regist_date').datetimepicker(datetimepickeroption); 
			$('#search_start_buy_date').datetimepicker(datetimepickeroption); 
			$('#search_end_buy_date').datetimepicker(datetimepickeroption); 
			$('#submit_datetime').datetimepicker(datetimepickeroption); 
			$('input.calendar').datetimepicker(datetimepickeroption); 
		}); 
		//$(function(){ 
		//	//日付項目クリアリンク
		//	$('.clear_date').click(function(){$(this).prev().val(''); return false;});
	</script>
	<script type="text/javascript" src="/alfproduct/js/main.js"></script>
	<title><?php echo $this->_tpl_vars['admin_main_title']; ?>
　|　JFBA総合研修サイト</title>
</head>
<body>
<?php 
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
//print("<!--[alfsession:\n");
//var_dump($arr_session);
//print("]-->\n");
//print("<!--[cms_master.login.bar_association_id:".$arr_session["cms_master.login.bar_association_id"]."]-->\n");
 ?>
	<div id="header">
		<div id="header_top">
			<a href="/"><img class="logo" src="/static/image/logo_On-Tap.png" style="margin-top: 12px; margin-right: 4px; margin-bottom: 12px; margin-left: 4px;"></a>
			<div class="header_top_right">
				<div class="login_teacher_name"><?php echo $this->_tpl_vars['admin_main_name']; ?>
</div>
				<div class="login_school_name">[<?php echo $this->_tpl_vars['admin_main_school']; ?>
]管理ページ</div>
				<div class="logout">
					<a href="javascript:void(0);" onclick="logout_confirm('/', 'ログアウトしますか？');return false;">ログアウト</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<?php $this->assign('menu_count', 0); ?>
<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?>
<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>
<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
	<?php $this->assign('menu_count', $this->_tpl_vars['menu_count']+1); ?><?php } ?>

		<?php $this->assign('temp_param_dummy_val', ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y%m%d%H%M%S") : smarty_modifier_date_format($_tmp, "%Y%m%d%H%M%S"))); ?>
		<div id="header_navi" style="<?php if ($this->_tpl_vars['menu_count'] > 11): ?>height:70px;<?php endif; ?>">
			<ul>
				<li><a href="/admin_top?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">トップ</a></li><?php $this->assign('menu_count', 0); ?>
				<li><a href="/cms_student?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">受講者</a></li>
				<li><a href="/cms_teacher?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">管理者</a></li>

				<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
					<li><a href="/cms_material?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">資料</a></li>
				<?php } ?>

				<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
					<li><a href="/cms_book_library?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">図書室</a></li>
				<?php } ?>

				<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
					<li><a href="/cms_video?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">コンテンツ*</a></li>
				<?php } ?>

				<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
					<li><a href="/cms_issue?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">課題</a></li>
				<?php } ?>

				<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
					<li><a href="/cms_information?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">お知らせ</a></li>
				<?php } ?>

				<?php if ($arr_session["cms_master.login.teacher_id"] == '-1'){ ?>
					<li><a href="/cms_school_manage?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">学校管理</a></li>
				<?php } ?>

				<li<?php if ($this->_tpl_vars['page_name'] == 'product'): ?> class="selected"<?php endif; ?>><a href="/alfproduct/product_live/?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">商品</a></li>
				<li<?php if ($this->_tpl_vars['page_name'] == 'product_lecture' || $this->_tpl_vars['page_name'] == 'product_lecture2' || $this->_tpl_vars['page_name'] == 'product_lecture_ethics'): ?> class="selected"<?php endif; ?>><a href="/alfproduct/product_lecture/?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">講座管理</a></li>

				<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
					<li<?php if ($this->_tpl_vars['page_name'] == 'report' || $this->_tpl_vars['page_name'] == 'report_product' || $this->_tpl_vars['page_name'] == 'report_all'): ?> class="selected"<?php endif; ?>><a href="/cms_report?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">レポート</a></li>
				<?php } else { ?>
					<li<?php if ($this->_tpl_vars['page_name'] == 'report' || $this->_tpl_vars['page_name'] == 'report_product' || $this->_tpl_vars['page_name'] == 'report_all'): ?> class="selected"<?php endif; ?>><a href="/alfproduct/report_product?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">レポート</a></li>
				<?php } ?>

				<li<?php if ($this->_tpl_vars['page_name'] == 'amount_user' || $this->_tpl_vars['page_name'] == 'amount_product' || $this->_tpl_vars['page_name'] == 'amount_order' || $this->_tpl_vars['page_name'] == 'bank_upload' || $this->_tpl_vars['page_name'] == 'amount_passport'): ?> class="selected"<?php endif; ?>><a href="/alfproduct/amount_order/?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">集計</a></li>

				<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
					<li><a href="/cms_exam?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">問題</a></li>
					<li><a href="/cms_exam2?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">アンケート</a></li>
					<li><a href="/cms_ranking?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">ランキング</a></li>
					<li><a href="/cms_category?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">カテゴリ</a></li>
				<?php } ?>

				<?php if ($arr_session["cms_master.login.bar_association_id"] == '1'){ ?>
					<li<?php if ($this->_tpl_vars['page_name'] == 'inquiry'): ?> class="selected"<?php endif; ?>><a href="/alfproduct/inquiry/?_=<?php echo $this->_tpl_vars['temp_param_dummy_val']; ?>
">お問い合わせ*</a></li>
				<?php } ?>


																			</ul>
		</div>
	</div>

	<div id="wrapper">
		<h1 class="claerfix" style="margin-top:0px;margin-bottom:0px;">
			<div class="title"><?php echo $this->_tpl_vars['admin_main_title']; ?>
　</div>
			<div class="comment"><?php echo $this->_tpl_vars['admin_main_comment']; ?>
　</div>
		</h1>
		<div id="main">
			<div id="menu_sub" class="clearfix">
				<?php echo $this->_tpl_vars['admin_main_side_menu']; ?>

				<br>
			</div>
			<div id="contents_main">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['include_template_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<style type="text/css">
		#footer UL{
			background		: white;
			height			: 30px;
			margin-top		: -10px;
		}
		#footer UL LI{
			display			: inline-block;
			line-height		: 30px;
			margin			: 0px 20px;
		}
		#footer UL LI A{
			color			: #222222;
			text-decoration	: none;
		}
	</style>
	<div id="footer">
		<ul></ul>
		<div style="text-align:right;margin-top:5px;"></div>
	</div>

</body>
</html>