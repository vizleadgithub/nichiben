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
	<link rel="stylesheet" href="<?=base_url()?>static/css/common.css?_=20250305" type="text/css" />
	<link rel="stylesheet" href="<?=base_url()?>static/css/header.css?_=20250305" type="text/css" />
	<link rel="stylesheet" href="<?=base_url()?>static/css/main.css?_=20250305" type="text/css" />
	<!--<link href="<?=base_url()?>static/css/jquery_smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />  -->
	<!--<script src="<?=base_url()?>static/js/jquery-1.6.2.min.js" type="text/javascript"></script> -->
	<!--<script type="text/javascript" src="/static/js/jquery-3.7.1.min.js"></script> -->
	<!--<script src="<?=base_url()?>static/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>  -->
	<!--<script src="<?=base_url()?>static/js/jquery.ui.datepicker.min.js" type="text/javascript"></script>  -->
	<!--<script src="<?=base_url()?>static/js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>  -->
	<!--<script src="<?=base_url()?>static/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>  -->

	<!--<script src="<?=base_url()?>static/js/jquery.ui.core.js" type="text/javascript"></script>  -->
	<!--<script src="<?=base_url()?>static/js/jquery.ui.resizable.js" type="text/javascript"></script>  -->

	<link rel="stylesheet" href="<?=base_url()?>static/js/jquery-ui-1.14.1/dist/themes/base/jquery-ui.min.css">

	<script src="<?=base_url()?>static/js/jquery-3.7.1.min.js"></script>
	<script src="<?=base_url()?>static/js/jquery-ui-1.14.1/dist/jquery-ui.min.js"></script>

	<!-- Datepicker 日本語化-->
	<script src="<?=base_url()?>static/js/jquery-ui-1.14.1/ui/i18n/datepicker-ja.js"></script>

	<!-- timepicker addon -->
	<script src="<?=base_url()?>static/js/jquery-timepicker-addon/dist/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" href="<?=base_url()?>static/js/jquery-timepicker-addon/dist/jquery-ui-timepicker-addon.css">


	<script src="<?=base_url()?>static/js/main.js" type="text/javascript"></script>
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
			,showOtherYears   : true
			,selectOtherYears : true
			,showOtherMonths  : true
			,selectOtherMonths: true
			,changeYear       : true
			,changeMonth      : true
			,yearRange        : <?= "'".$this->lang->line_or_def('common_js_yearRange','1900:2100')."'" ?>
			,showButtonPanel  : true
			,currentText      : <?= "'".$this->lang->line_or_def('common_js_currentText','現在')."'" ?>
			,closeText        : <?= "'".$this->lang->line_or_def('common_js_closeText','閉じる')."'" ?>
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
			"issue"         => $this->lang->line_or_def('common_title_issue','課題管理').          '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"information"   => $this->lang->line_or_def('common_title_information','お知らせ管理').'　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"report"        => $this->lang->line_or_def('common_title_report','レポート').         '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"auth"          => $this->lang->line_or_def('common_title_auth','権限管理').           '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"exam"          => $this->lang->line_or_def('common_title_exam','問題管理').           '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"exam2"         => $this->lang->line_or_def('common_title_exam2','アンケート管理').    '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"school_manage" => $this->lang->line_or_def('common_title_school_manage','学校管理').  '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"ranking"       => $this->lang->line_or_def('common_title_ranking','ランキング管理').  '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			"category"      => $this->lang->line_or_def('common_title_category','カテゴリ管理').   '　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
			
			"session_error" => $this->lang->line_or_def('common_title_session_error','セッションエラー').'　|　'. $this->lang->line_or_def('common_title_default','アルフラーニング'),
		);
	?>
	<?php if(isset($callview)): ?>
		<title><?= htmlspecialchars( $title_box[$callview], ENT_QUOTES, 'UTF-8') ?></title>
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

<script type="text/javascript">
	var notification_id_list = '0';
	
	$(function(){
		// 通知情報の取得（ajax）の呼び出し
		ajax_search_notification();
		
		// ウィンドウリサイズ時、通知エリアの位置調整
		$(window).resize(function(){
			var window_height            = $(window).height();
			var notification_area_height = $('#notification_bottom_area').height();
			window_height = window_height - notification_area_height;
			
			$( "#notification_bottom_area" ).css( "top", window_height );
		});
	});
	
	// [ajax]通知情報の表示
	function ajax_search_notification(){
		$.ajax({
			url: "/cms_notification/get_notification",
			type: "POST",
			
			success: function(response) {
				if(response){
					console.log(response.length);
					if( response.length>0 ){
					} else {
						$("#notification_bottom_area").remove();
						$("body").css('margin','0px 0px 0px 0px');
						return false;
					}

					$("body").css('margin','0px 0px 120px 0px');
					
					$("body").append(
						$('<div id="notification_bottom_area" class="ui-resizable-handle-n">')
						.append(
							$('<div style="position:absolute;left: 10px;top: 10px;"><a id="change_link" href="#" style="text-decoration: none;font-weight: bold;" onclick="ajax_update_notification_all('+"'"+'全ての通知を既読にしますか'+"'"+');return false;">全て既読</a></div>')
						)
						.append(
							$('<ul id="notification_list_area">')
						)
					);
					
					// 取得したデータを行に入れる
					for (var i=0; i< response.length; i++) {
						notification_id_list = notification_id_list + ',' + response[i]['notification_id'] + '';
						
						if(response[i]['notice_judge']=='NG'){
							$("#notification_list_area").append(
								$('<li id="notification_id_'+response[i]['notification_id']+'" style="color:#FB8282;">').append(
	'<div class="date_area">'+response[i]['added_at']+'</div><div class="caption_area">'+response[i]['notice_caption']+'</div><div class="link_area"><a class="read_button" onclick="ajax_update_notification('+response[i]['notification_id']+');return false;" href="#"></a></div><div style="clear:both;">'
								)
							);
						}else{
							$("#notification_list_area").append(
								$('<li id="notification_id_'+response[i]['notification_id']+'" >').append(
	'<div class="date_area">'+response[i]['added_at']+'</div><div class="caption_area">'+response[i]['notice_caption']+'</div><div class="link_area"><a class="read_button" onclick="ajax_update_notification('+response[i]['notification_id']+');return false;" href="#"></a></div><div style="clear:both;">'
								)
							);
						}
					}
					
					/* リサイズ開始と、リサイズ時のドラック位置の指定 */
					$("#notification_bottom_area").resizable( {
						handles: 'n',
					});
					
					// 通知エリアtopの指定（Chrome等、topを指定しないとresizable の挙動がおかしくなる
					var default_top = $('#notification_bottom_area').offset().top;
					$('#notification_bottom_area').offset( { top: default_top, left: 0 } );
				}else{
				//	$("body").css('margin','0px 0px 0px 0px');
				}
			}
		});
		return false;
	}
	
	// [ajax]通知既読処理
	function ajax_update_notification(notification_id){
		$.ajax({
			url: "/cms_notification/update_notification",
			type: "POST",
			data: "notification_id="+notification_id+"",
			
			success: function(response) {
				if(response){
					// 既読処理成功の場合、リスト削除
					$("#notification_id_"+notification_id).remove();
					
					// リスト削除後、リスト数ゼロの場合表示エリアを削除
					if( $("#notification_list_area li").size() == 0 ){
						$("#notification_bottom_area").remove();
						$("body").css('margin','0px 0px 0px 0px');
					}
				}
			}
		});
		return false;
	}
	
	// [ajax]全ての通知既読処理
	function ajax_update_notification_all(msg){
		if(window.confirm(msg)){
			$.ajax({
				url: "/cms_notification/update_notification_all",
				type: "POST",
				data: "notification_id_list="+notification_id_list+"",
				
				success: function(response) {
					if(response){
						// 表示エリアを削除
						$("#notification_bottom_area").remove();
						$("body").css('margin','0px 0px 0px 0px');
					}
				}
			});
			return false;
		}
	}
	
	// 通知エリアの位置を初期値に修正
	function change_notification_bottom_area(){
		// 現在のウィンドウの高さ - 通知エリアの初期値高さ
		var window_height = $(window).height() - 120;
		
		// 通知エリアのtop heightの初期化
		$( "#notification_bottom_area" ).css( "top", window_height );
		$("#notification_bottom_area").css('height','120px');
		
		return false;
	}
	
</script>

