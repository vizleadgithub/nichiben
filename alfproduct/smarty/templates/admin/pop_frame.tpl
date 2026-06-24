<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><!--{$admin_main_title}-->　|　JFBA総合研修サイト</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="/alfproduct/image/favicon.ico" >
	<link rel="stylesheet" href="/alfproduct/css/common.css?_=20250305" type="text/css" />
	<link rel="stylesheet" href="/alfproduct/css/header.css?_=20250305" type="text/css" />
	<link rel="stylesheet" href="/alfproduct/css/main.css?_=20250305" type="text/css" />

	<link rel="stylesheet" media="all" type="text/css" href="/alfproduct/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="all" type="text/css" href="/alfproduct/css/jquery-ui-timepicker-addon.css" /> 
	<script type="text/javascript" src="/alfproduct/js/jquery-1.8.3.min.js"></script>
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
			$('#submit_datetime').datetimepicker(datetimepickeroption); 
			$('input.calendar').datetimepicker(datetimepickeroption); 
		}); 
		//$(function(){ 
		//	//日付項目クリアリンク
		//	$('.clear_date').click(function(){$(this).prev().val(''); return false;});
	</script>
</head>
<body>
	<div id="wrapper">
		<h1 class="claerfix" style="margin-top:0px;margin-bottom:0px;">
			<div class="title"><!--{$admin_main_title}-->　</div>
			<div class="comment"><!--{$admin_main_comment}-->　</div>
		</h1>
		<div id="main">
			<div id="menu_sub" class="clearfix"><br></div>
			<div id="contents_main">
				<!--{include file=$include_template_file}-->
			</div>
			<div class="clear"></div>
		</div>
	</div>

</body>
</html>