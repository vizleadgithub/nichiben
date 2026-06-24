<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php
		$this->lang->load('common');
	?>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<? $check_language = ( getenv('URL_SERVICE') ) ? getenv('URL_SERVICE') : $this->config->item('language'); ?>
	<? if( $check_language == 'alfsales' ): ?>
		<link rel="shortcut icon" href="/static/image/favicon_alfsales.ico" >
	<? elseif( $check_language == 'conference' ): ?>
		<link rel="shortcut icon" href="/static/image/favicon.ico" >
	<? else: ?>
		<link rel="shortcut icon" href="/static/image/favicon.ico" >
	<? endif; ?>
	<link rel="stylesheet" href="/static/css/common.css" type="text/css" />
	<link rel="stylesheet" href="/static/css/header.css" type="text/css" />
	<link rel="stylesheet" href="/static/css/main.css" type="text/css" />
	<link href="/static/css/jquery_smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" /> 
	<script src="/static/js/jquery-1.6.2.min.js" type="text/javascript"></script> 
	<script src="/static/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script> 
	<script src="/static/js/jquery.ui.datepicker.min.js" type="text/javascript"></script> 
	<script src="/static/js/jquery.ui.datepicker-ja.js" type="text/javascript"></script> 
	<script src="/static/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script> 

	<script src="/static/js/main.js" type="text/javascript"></script>
	<script type="text/javascript">
		var datetimepickeroption = {
			 dateFormat       : <?= "'".$this->lang->line_or_def('common_js_dateFormat','yy/mm/dd')."'" ?>
			,timeFormat       : <?= "'".$this->lang->line_or_def('common_js_timeFormat','hh:mm:ss')."'" ?>
			,showSecond       : true
			,showOtherMonths  : true
			,selectOtherMonths: true
			,changeYear       : true
			,changeMonth      : true
			,yearRange        : <?= "'".$this->lang->line_or_def('common_js_yearRange','1900:2100')."'" ?>
			,showButtonPanel  : true
			,currentText      : <?= "'".$this->lang->line_or_def('common_js_currentText','現在')."'" ?>
			,closeText        : <?= "'".$this->lang->line_or_def('common_js_closeText','閉じる')."'" ?>
			,timeText         : <?= "'".$this->lang->line_or_def('common_js_timeText','時刻')."'" ?>
			,hourText         : <?= "'".$this->lang->line_or_def('common_js_hourText','時')."'" ?>
			,minuteText       : <?= "'".$this->lang->line_or_def('common_js_minuteText','分')."'" ?>
			,secondText       : <?= "'".$this->lang->line_or_def('common_js_secondText','秒')."'" ?>
		};
		
		var datepickeroption = {
			 dateFormat       : <?= "'".$this->lang->line_or_def('common_js_dateFormat','yy/mm/dd')."'" ?>
			,showOtherMonths  : true
			,selectOtherMonths: true
			,changeYear       : true
			,changeMonth      : true
			,yearRange        : <?= "'".$this->lang->line_or_def('common_js_yearRange','1900:2100')."'" ?>
			,showButtonPanel  : true
		};
		
	</script>
	
	<?php
		$title_box = array(
			"login"         => $this->lang->line_or_def('common_title_login','管理者ログイン').    '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"admin_top"     => $this->lang->line_or_def('common_title_admin_top','管理画面トップ').'　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"school_select" => $this->lang->line_or_def('common_title_school_select','学校選択').  '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"course"        => $this->lang->line_or_def('common_title_course','講座管理').         '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"course_class"  => $this->lang->line_or_def('common_title_course_class','授業管理').   '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"student"       => $this->lang->line_or_def('common_title_student','受講者管理').      '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"teacher"       => $this->lang->line_or_def('common_title_teacher','講師管理').        '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"material"      => $this->lang->line_or_def('common_title_material','資料管理').       '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"book_library"  => $this->lang->line_or_def('common_title_book_library','図書館管理'). '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"video"         => $this->lang->line_or_def('common_title_video','ビデオ管理').        '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"information"   => $this->lang->line_or_def('common_title_information','お知らせ管理').'　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"report"        => $this->lang->line_or_def('common_title_report','レポート').         '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"auth"          => $this->lang->line_or_def('common_title_auth','権限管理').           '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			
			"school_manage" => $this->lang->line_or_def('common_title_school_manage','学校管理').  '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			
			"session_error" => $this->lang->line_or_def('common_title_session_error','セッションエラー').'　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
		);
	?>
	<?php if(isset($callview)): ?>
		<title><?=$title_box[$callview]?></title>
	<?php endif; ?>
	<?php if(!isset($callview)): ?>
		<title><?=$this->lang->line_or_def('common_title_default','アルフラーニング') ?></title>
	<?php endif; ?>

<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-35170629-1']);
	_gaq.push(['_setDomainName', 'alflearning.com']);
	_gaq.push(['_trackPageview']);
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
