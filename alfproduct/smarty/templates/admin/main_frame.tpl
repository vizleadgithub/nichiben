<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><!--{$admin_main_title|escape}-->　|　JFBA総合研修サイト</title>
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
	<title><!--{$admin_main_title|escape}-->　|　JFBA総合研修サイト</title>
</head>
<body>
<!--{php}-->
$objAlfSession = new AlfSession();
$arr_session = $objAlfSession->session_check();
//print("<!--[alfsession:\n");
//var_dump($arr_session);
//print("]-->\n");
//print("<!--[cms_master.login.bar_association_id:".$arr_session["cms_master.login.bar_association_id"]."]-->\n");
<!--{/php}-->
	<div id="header">
		<div id="header_top">
			<a href="/"><img class="logo" src="/static/image/logo_On-Tap.png" style="margin-top: 12px; margin-right: 4px; margin-bottom: 12px; margin-left: 4px;"></a>
			<div class="header_top_right">
				<div class="login_teacher_name"><!--{$admin_main_name|escape}--></div>
				<div class="login_school_name">[<!--{$admin_main_school|escape}-->]管理ページ</div>
				<div class="logout">
					<a href="javascript:void(0);" onclick="logout_confirm('/', 'ログアウトしますか？');return false;">ログアウト</a>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<!--{assign var='menu_count' value=0}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*トップ*}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*受講者*}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*管理者*}-->

<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*資料*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*図書室*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*コンテンツ*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*課題*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*お知らせ*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*学校管理*}-->
<!--{php}-->}<!--{/php}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*商品*}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*講座管理*}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*レポート*}-->
<!--{assign var='menu_count' value=$menu_count+1}--><!--{*集計*}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*問題*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*アンケート*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*ランキング*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*カテゴリ*}-->
<!--{php}-->}<!--{/php}-->
<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
	<!--{assign var='menu_count' value=$menu_count+1}--><!--{*お問い合わせ*}-->
<!--{php}-->}<!--{/php}-->
		<div id="header_navi" style="<!--{if $menu_count>11}-->height:70px;<!--{/if}-->">
			<ul>
				<li><a href="/admin_top">トップ</a></li><!--{assign var='menu_count' value=0}-->
				<!--{php}-->if (is_array($arr_session["cms_master.login.teacher_auth"]) && $arr_session["cms_master.login.teacher_auth"]["student"] == 1){<!--{/php}-->
					<li><a href="/cms_student">受講者</a></li>
				<!--{php}-->}<!--{/php}-->
				<!--{php}-->if (is_array($arr_session["cms_master.login.teacher_auth"]) && $arr_session["cms_master.login.teacher_auth"]["teacher"] == 1){<!--{/php}-->
					<li><a href="/cms_teacher">管理者</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
					<li><a href="/cms_material">資料</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
					<li><a href="/cms_book_library">図書室</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
					<li><a href="/cms_video">コンテンツ*</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
					<li><a href="/cms_issue">課題</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
					<li><a href="/cms_information">お知らせ</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.teacher_id"] == '-1'){<!--{/php}-->
					<li><a href="/cms_school_manage">学校管理</a></li>
				<!--{php}-->}<!--{/php}-->

				<li<!--{if $page_name=="product"}--> class="selected"<!--{/if}-->><a href="/alfproduct/product_live/">商品</a></li>
				<li<!--{if $page_name=="product_lecture" || $page_name=="product_lecture2" || $page_name=="product_lecture_ethics" }--> class="selected"<!--{/if}-->><a href="/alfproduct/product_lecture/">講座管理</a></li>

				<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
					<li<!--{if $page_name=="report" || $page_name=="report_product" || $page_name=="report_all"}--> class="selected"<!--{/if}-->><a href="/cms_report">レポート</a></li>
				<!--{php}-->} else {<!--{/php}-->
					<li<!--{if $page_name=="report" || $page_name=="report_product" || $page_name=="report_all"}--> class="selected"<!--{/if}-->><a href="/alfproduct/report_product">レポート</a></li>
				<!--{php}-->}<!--{/php}-->

				<li<!--{if $page_name=="amount_user" || $page_name=="amount_product" || $page_name=="amount_order" || $page_name=="bank_upload" || $page_name=="amount_passport"}--> class="selected"<!--{/if}-->><a href="/alfproduct/amount_order/">集計</a></li>

				<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
					<li><a href="/cms_exam">問題</a></li>
					<li><a href="/cms_exam2">アンケート</a></li>
					<li><a href="/cms_ranking">ランキング</a></li>
					<li><a href="/cms_category">カテゴリ</a></li>
				<!--{php}-->}<!--{/php}-->

				<!--{php}-->if ($arr_session["cms_master.login.bar_association_id"] == '1'){<!--{/php}-->
					<li<!--{if $page_name=="inquiry"}--> class="selected"<!--{/if}-->><a href="/alfproduct/inquiry/">お問い合わせ*</a></li>
				<!--{php}-->}<!--{/php}-->


				<!--{*<li><a href="/cms_report">レポート</a></li>*}-->
				<!--{*<li><a href="/cms_auth">権限</a></li>*}-->
				<!--{*<li><a href="/cms_cource">講座</a></li>*}-->
				<!--{* <li<!--{if $page_name=="mailmagazine"}--> class="selected"<!--{/if}-->><a href="/alfproduct/mailmagazine/">メルマガ*</a></li> *}-->
			</ul>
		</div>
	</div>

	<div id="wrapper">
		<h1 class="claerfix" style="margin-top:0px;margin-bottom:0px;">
			<div class="title"><!--{$admin_main_title|escape}-->　</div>
			<div class="comment"><!--{$admin_main_comment|escape}-->　</div>
		</h1>
		<div id="main">
			<div id="menu_sub" class="clearfix">
				<!--{$admin_main_side_menu}-->
				<br>
			</div>
			<div id="contents_main">
				<!--{include file=$include_template_file}-->
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
		<ul><!--{*
			<li><a target="_blank" rel="noopener noreferrer" href="http://alfredcore.com/">運営会社</a></li>
			<li><a target="_blank" rel="noopener noreferrer" href="http://alfredcore.com/privacy">個人情報保護方針</a></li>
		*}--></ul>
		<div style="text-align:right;margin-top:5px;"><!--{*ALF Learning 1.5.0 | Powered by Alfredcore,inc*}--></div>
	</div>

</body>
</html>