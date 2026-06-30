<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<meta http-equiv="Pragma" content="no-cache">
<meta name="description" content="日本弁護士連合会　総合研修サイト" />
<meta name="keywords" content="日本弁護士連合会　総合研修サイト" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	}/* else { echo ' | ' . "真の法律家・行政官を育成する 「On-Tap」"; }*/ ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css" />
<!-- Start WOWSlider.com HEAD section -->
<link rel="stylesheet" type="text/css" href="/engine1/style.css" />
<!--<script type="text/javascript" src="/jquery/jquery-1.7.1.min.js"></script>-->
<script type="text/javascript" src="/jquery/jquery-3.7.1.min.js"></script>
<?php /* <script type="text/javascript" src="/engine1/jquery.js"></script> */ ?>
<script type="text/javascript" src="/jquery/jquery.smoothScroll.js"></script>
<script type="text/javascript" src="/js/smartRollover.js"></script>

<script type="text/javascript">
/**/
jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();
/**/
</script>


<!-- -->
<link href="/css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.datepicker-ja.js"></script>
<script type="text/javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
<!-- -->
<!--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.5.5/jquery-ui-timepicker-addon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/i18n/jquery-ui-timepicker-ja.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/i18n/jquery-ui-timepicker-ja.js"></script>
-->

<script type="text/javascript" src="/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/js/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="/js/jquery.textOverflowEllipsis.js"></script>
<script type="text/javascript">
//$(function() {
//  $('.textOverflowTest4').textOverflowEllipsis({
//    resize: true, // ウィンドウリサイズ時に追従するか
//    numOfCharactersToReduce : 1, // 高さ計算するときに削る文字数
//    suffix: '...' // 省略記号
//  });
//});
</script>
<script type="text/javascript">
	/*
	var datetimepickeroption = {
		dateFormat       : 'yy/mm/dd'
		,timeFormat       : 'HH'
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
	*/
	var datetimepickeroption = {
	    dateFormat       : 'yy/mm/dd',
	    timeFormat       : 'HH:mm',
	    stepHour        : 1,
	    showHour         : false,
	    showMinute       : false,
	    showSecond       : false,
	    showOtherMonths  : true,
	    selectOtherMonths: true,
	    changeYear       : true,
	    changeMonth      : true,
	    yearRange        : '1900:2100',
	    showButtonPanel  : true,
	    currentText      : '現在',
	    closeText        : '閉じる',
	    timeText         : '時刻',
	    hourText         : '時',
	    minuteText       : '分',
	    secondText       : '秒'
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
</script>

<!-- End WOWSlider.com HEAD section -->

        <link href="/js/src/perfect-scrollbar.css " rel="stylesheet">
        <script src="/js/src/jquery.mousewheel.js"></script>
        <script src="/js/src/perfect-scrollbar.js"></script>

	<link href="/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="/facebox/facebox.js" type="text/javascript"></script>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('a[rel*=facebox]').facebox() 
	})
	</script>

	<link rel="stylesheet" href="/popupwindow/css/popupwindow.css" type="text/css" media="all" />
	<script type="text/javascript" src="/popupwindow/popupwindow-1.8.1.js"></script>

	<style type="text/css">
	div#header div#head_top_area{

	}
	div#header ul#head_menu{
	list-style:none;
	}
	div#header ul#head_menu li{
	float:left;
	/*width:192px;*/
	text-align:center;
	}

	</style>

	<!--[if IE]>
	<style type="text/css">
	div#header ul#head_menu{
	list-style:none;
	}
	div#header ul#head_menu li{
	float:left;
	/*width:192px;*/
	text-align:center;
	}

	.lc_st ol{
		margin:0px;
		margin-top:-18px;
		padding:0px;
	}
	</style>
	<![endif]-->


<script type="text/javascript">
<!--
$(function(){  
    $("ol").hide();
<?php
$productcategory_list = get_product_category();
$id_data = 0;
foreach ($productcategory_list as $val){
	if($_GET["pcid"]==$val['big']['term_id']){
		if ($val['big']['term_id']=="519"){
			?>
			document.getElementById("menu<?php echo $id_data ?>").style.display="block";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_blue_on.png)";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
			document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
			document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
			document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_blue_on_w.png";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.background= "no-repeat scroll 6% center #61A6C3";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.color= "#ffffff";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>_a").style.color= "#ffffff";
			<?php
		}
	}
	$id_data = $id_data + 1;
}
foreach ($productcategory_list as $val){
	if($_GET["pcid"]==$val['big']['term_id']){
		//$id_data = $val['big']['term_id'];
		if( $val['big']['term_group']=="0" && $val['big']['term_id']!="519" ){ 
?>
			//b0
			document.getElementById("menu<?php echo $id_data ?>").style.display="block";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_orange_on.png)";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
			document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
			document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
			document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_orange_on_w.png";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.background= "url(/img/c_ar_orange_on.png) no-repeat scroll 6% center #FF9C17";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.color= "#ffffff";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>_a").style.color= "#ffffff";
<?php
		} elseif( $val['big']['term_group']=="1" && $val['big']['term_id']!="519" ){ 
?>
			//b1
			document.getElementById("menu<?php echo $id_data ?>").style.display="block";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_green_on.png)";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
			document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
			document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
			document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_green_on_w.png";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.background= "url(/img/c_ar_green_on.png) no-repeat scroll 6% center #8dbd55";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.color= "#ffffff";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>_a").style.color= "#ffffff";
<?php
		} elseif( $val['big']['term_group']=="2" && $val['big']['term_id']!="519" ){ 
?>
			//b2
			document.getElementById("menu<?php echo $id_data ?>").style.display="block";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_blue_on.png)";
			document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
			document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
			document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
			document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_blue_on_w.png";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.background= "url(/img/c_ar_blue_on_w.png) no-repeat scroll 6% center #61A6C3";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>").style.color= "#ffffff";
			document.getElementById("category<?php echo $val['big']['term_id'] ?>_a").style.color= "#ffffff";
<?php
		}
	}
	foreach ($val['small'] as $val2){
		if($_GET["pcid"]==$val2['term_id']){
			//$id_data = $val['big']['term_id'];
			if( $val['big']['term_group']=="0" ){ 
?>
				//s0
				document.getElementById("menu<?php echo $id_data ?>").style.display="block";
				document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_orange_on.png)";
				document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
				document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
				document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
				document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_orange_on_w.png";
				document.getElementById("category<?php echo $val2['term_id'] ?>").style.background= "no-repeat scroll 6% center #FF9C17";
				document.getElementById("category<?php echo $val2['term_id'] ?>").style.color= "#ffffff";
				document.getElementById("category<?php echo $val2['term_id'] ?>_a").style.color= "#ffffff";
<?php
			} elseif( $val['big']['term_group']=="1" ){ 
?>
				//s1
				document.getElementById("menu<?php echo $id_data ?>").style.display="block";
				document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_green_on.png)";
				document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
				document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
				document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
				document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_green_on_w.png";
				document.getElementById("category<?php echo $val2['term_id'] ?>").style.background= "no-repeat scroll 6% center #8dbd55";
				document.getElementById("category<?php echo $val2['term_id'] ?>").style.color= "#ffffff";
				document.getElementById("category<?php echo $val2['term_id'] ?>_a").style.color= "#ffffff";
<?php
			} elseif( $val['big']['term_group']=="2" ){ 
?>
				//s2
				document.getElementById("menu<?php echo $id_data ?>").style.display="block";
				document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundImage = "url(/img/l_cateback_blue_on.png)";
				document.getElementById("menu_big<?php echo $id_data ?>").style.backgroundRepeat = 'no-repeat';  
				document.getElementById("menu_big_font<?php echo $id_data ?>").style.color = "#000000";
				document.getElementById("menu_big<?php echo $id_data ?>").classList.add('select_menu');
				document.getElementById("image<?php echo $id_data ?>").src = "/img/c_ar_blue_on_w.png";
				document.getElementById("category<?php echo $val2['term_id'] ?>").style.background= "no-repeat scroll 6% center #61A6C3";
				document.getElementById("category<?php echo $val2['term_id'] ?>").style.color= "#ffffff";
				document.getElementById("category<?php echo $val2['term_id'] ?>_a").style.color= "#ffffff";
<?php
			}
		}
	}
	$id_data = $id_data + 1;
}
?>

	//$("a").click(function(){  
		//$(this).next().slideToggle();

		//$temp1 = String($(this).attr("id")).replace("menu_big", "menu");
		//$temp2 = String($(this).attr("id")).replace("menu_big", "image");
		//if(document.getElementById($temp1).style.display=="block"){
		//	document.getElementById($temp2).src = "/img/c_ar_off.png";
		//}else if(document.getElementById($temp1).style.display=="none"){
		//	document.getElementById($temp2).src = "/img/c_ar_on.png";
		//}
	//});  
}) 

// -->

<!--
function menuAco(n){
	console.log("["+ n +":"+ document.getElementById("menu"+n).style.display +"]");
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu"+n).style.display="none";
		document.getElementById("image"+n).src = "/img/c_ar_off_w.png";

	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu"+n).style.display="block";
		document.getElementById("image"+n).src = "/img/c_ar_on_w.png";
	}
}
/*
function BigCatBacIn(n){
	document.getElementById("menu_big"+n).style.backgroundImage = "url(/img/l_cateback01_on.png)";
	document.getElementById("menu_big"+n).style.backgroundRepeat = 'no-repeat';  
	//document.getElementById("menu_big"+n).style.backgroundPosition = 'right bottom';  
	document.getElementById("menu_big_font"+n).style.color = "#FFFFFF";
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("image"+n).src = "/img/c_ar_on_w.png";
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("image"+n).src = "/img/c_ar_off_w.png";
	}
}
function BigCatBacOut(n){
	document.getElementById("menu_big"+n).style.backgroundImage = "url(/img/l_cateback02.png)";
	document.getElementById("menu_big"+n).style.backgroundRepeat = 'no-repeat';  
	//document.getElementById("menu_big"+n).style.backgroundPosition = 'right bottom';  
	document.getElementById("menu_big_font"+n).style.color = "#412D11";
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("image"+n).src = "/img/c_ar_on_w.png";
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("image"+n).src = "/img/c_ar_off_w.png";
	}
}
*/
//+++++++++++++++++++++++++++++++++++++
function menuAco0(n){
	console.log("["+ n +":"+ document.getElementById("menu"+n).style.display +"]");
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu"+n).style.display="none";
		document.getElementById("image"+n).src = "/img/c_ar_orange_off_w.png";

		document.getElementById("menu_big"+n).classList.remove('select_menu');

	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu"+n).style.display="block";
		document.getElementById("image"+n).src = "/img/c_ar_orange_on_w.png";

		document.getElementById("menu_big"+n).classList.add('select_menu');

	}
}
function BigCatBacIn0(n){
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}
}
function BigCatBacOut0(n){
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu_big"+n).style.opacity=0.6;
	}
}
/*
function BigCatBacIn0(n){
	document.getElementById("menu_big"+n).style.backgroundImage = "url(/img/l_cateback_orange_on.png)";
	document.getElementById("menu_big"+n).style.backgroundRepeat = 'no-repeat';  
	document.getElementById("menu_big_font"+n).style.color = "#FFFFFF";
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("image"+n).src = "/img/c_ar_orange_on_w.png";
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("image"+n).src = "/img/c_ar_orange_off_w.png";
	}
}
function BigCatBacOut0(n){
	document.getElementById("menu_big"+n).style.backgroundImage = "url(/img/l_cateback_orange_on.png)";
	document.getElementById("menu_big"+n).style.backgroundRepeat = 'no-repeat';  
	document.getElementById("menu_big_font"+n).style.color = "#412D11";
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("image"+n).src = "/img/c_ar_orange_on_w.png";
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("image"+n).src = "/img/c_ar_orange_off_w.png";
	}
}
*/
//+++++++++++++++++++++++++++++++++++++
function menuAco1(n){
	console.log("["+ n +":"+ document.getElementById("menu"+n).style.display +"]");
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu"+n).style.display="none";
		document.getElementById("image"+n).src = "/img/c_ar_green_off_w.png";

		document.getElementById("menu_big"+n).classList.remove('select_menu');

	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu"+n).style.display="block";
		document.getElementById("image"+n).src = "/img/c_ar_green_on_w.png";

		document.getElementById("menu_big"+n).classList.add('select_menu');
	}
}
function BigCatBacIn1(n){
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}
}
function BigCatBacOut1(n){
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu_big"+n).style.opacity=0.6;
	}
}
//+++++++++++++++++++++++++++++++++++++
function menuAco2(n){
	console.log("["+ n +":"+ document.getElementById("menu"+n).style.display +"]");
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu"+n).style.display="none";
		document.getElementById("image"+n).src = "/img/c_ar_blue_off_w.png";

		document.getElementById("menu_big"+n).classList.remove('select_menu');

	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu"+n).style.display="block";
		document.getElementById("image"+n).src = "/img/c_ar_blue_on_w.png";

		document.getElementById("menu_big"+n).classList.add('select_menu');
	}
}
function BigCatBacIn2(n){
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}
}
function BigCatBacOut2(n){
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("menu_big"+n).style.opacity=1;
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("menu_big"+n).style.opacity=0.6;
	}
}
/*
function BigCatBacIn2(n){
	document.getElementById("menu_big"+n).style.backgroundImage = "url(/img/l_cateback_blue_on.png)";
	document.getElementById("menu_big"+n).style.backgroundRepeat = 'no-repeat';  
	document.getElementById("menu_big_font"+n).style.color = "#FFFFFF";
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("image"+n).src = "/img/c_ar_blue_on_w.png";
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("image"+n).src = "/img/c_ar_blue_off_w.png";
	}
}
function BigCatBacOut2(n){
	document.getElementById("menu_big"+n).style.backgroundImage = "url(/img/l_cateback_blue_on.png)";
	document.getElementById("menu_big"+n).style.backgroundRepeat = 'no-repeat';  
	document.getElementById("menu_big_font"+n).style.color = "#412D11";
	if(document.getElementById("menu"+n).style.display=="block"){
		document.getElementById("image"+n).src = "/img/c_ar_blue_on_w.png";
	}else if(document.getElementById("menu"+n).style.display=="none"){
		document.getElementById("image"+n).src = "/img/c_ar_blue_off_w.png";
	}
}
*/
//+++++++++++++++++++++++++++++++++++++
function BigCatBacInAll(n){
	document.getElementById("menu_all").style.opacity=1;
}
function BigCatBacOutAll(n){
	document.getElementById("menu_all").style.opacity=0.6;
}
//+++++++++++++++++++++++++++++++++++++
function samplePlayerFormSubmit(){
    var w = window.open("about:blank","samplePlayerDisp","width=675,height=660,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
    setTimeout(function(){
        w.onLoad = samplePlayerOpenWindowSubmit();
    }, 1000);
}
function samplePlayerOpenWindowSubmit(){
    document.samplePlayerForm.target = "samplePlayerDisp";
    document.samplePlayerForm.method = "post";
    document.samplePlayerForm.action = "/player/sample.php?term=pc";
    document.samplePlayerForm.submit();
}
//-->
</script>

</head>

<body>

<?php
// 購入フローと倫理研修問題の場合はヘッダー非表示
if (strpos($_SERVER['SCRIPT_NAME'], '/settlement/') === false){
if (strpos($_SERVER['SCRIPT_NAME'], '/ethic_treaning/') === false){
?>
<a id="top" name="top"></a>
<div id="wrapper" class="hfeed">
<div id="headbd" style="height:auto;">
	<div id="header">
		<style type="text/css">
			div#header div#head_top_area{
			}
			div#header ul#head_menu{
				list-style:none;
			}
			div#header ul#head_menu li{
				float:left;
				/*width:192px;*/
				text-align:center;
			}
			#head_top_area{
				float:left;
				display: inline-block;
			}
			#head_center_area{
				float:left;
				display: inline-block;
				/*width:160px;*/
				font-size:12px;
				margin-top:16px;
			}
			.subnavi A{
				display: inline-block;
				line-height: 24px;
				height: 24px;
				color: #fff;
				background-color: #756B6B;
				text-decoration: none;
				width: 140px;
				text-align: center;
				border-radius: 4px;
				margin: 4px;
			}
		</style>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<div id="head_top_area">
			<div style="float:left;padding:16px 0px 20px 11px ;"><a href="<?php site_url(); ?>/"><img src="/img/logo.png" alt="On-Tap" /></a></div>
		</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<?php if(st_login_check()){ ?>
			<div id="head_center_area">
				<div>
					<p style="text-align:left;font-size:12px;margin:0;"><b><?php echo htmlspecialchars( $_SESSION['user']['name'], ENT_QUOTES, 'UTF-8').'様'; ?></b></p>
					<p style="text-align:left;font-size:12px;margin:0;"><b>あなたの日弁連の倫理研修義務年度は</b></p>
					<p style="text-align:right;font-size:12px;margin:0;"><b><?php if($_SESSION['user']['bar_association_duty_year']){echo $_SESSION['user']['bar_association_duty_year'];}else{echo '完了しています';} ?>度です。</b></p>
				</div>
			</div>
		<?php } ?>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
			<div style="float:left;padding:16px 0px 20px 11px ;">
				<img src="/img/jfbakun.png" alt="ジャフバ" height="56" />
			</div>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<div class="subnavi">
			<div>
				<div style="display: inline-block;float: left;">
					<a href="https://member.nichibenren.or.jp/" target="_blank">日弁連会員ページへ</a>
				</div>
				<div style="display: inline-block;float: left;">
					<a href="/logout/logout.php">ログアウト</a>
				</div>
			</div>
			<div>
				<div style="display: inline-block;float: left;">
					<a href="/howsite/">サイトの使い方</a>
			<!--
					<form name="samplePlayerForm" action="#" method="post" style="display:inline;"><a href="javascript:void(0)" onclick="samplePlayerFormSubmit()">サイトの使い方</a></form>
			-->
				</div>
				<div style="display: inline-block;float: left;">
					<a href="/inquiry/">ご意見・お問い合わせ</a>
				</div>
			</div>
			<!--
			<ul>
				<li><a href="<?php site_url(); ?>/settlement" style="color:red;"><img src="/img/h_cart_btn.png" alt="買い物かご" /></a><div style="text-align:right;margin:5px 0;"><form name="samplePlayerForm" action="#" method="post" style="display:inline;"><a href="javascript:void(0)" onclick="samplePlayerFormSubmit()"><img src="/img/site_mv.png" alt="このサイトの使い方（動画）" /></a></form></div></li>
				<li><a href="/logout/logout.php"><img src="/img/h_logout_btn.png" alt="ログアウト" /></a><div style="text-align:right;margin:5px 0;"><a href="/pdf/jfba-kenshu-manual.pdf" target="_blank"><img src="/img/manual_dl_btn.png" alt="マニュアルダウンロード" /></a></div></li>
			</ul>
			-->
		</div>
		<style type="text/css">
			.subnavi{
				width:300px;
			}
		</style>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
		<ul id="head_menu" style="clear:both;">
			<li class="gnav_li"><a href="<?php site_url(); ?>/"                class="gnav_a" ><span class="gnav_text">ホーム</span></a></li>
			<li class="gnav_li"><a href="<?php site_url(); ?>/search/"         class="gnav_a" ><span class="gnav_text">詳細検索</span></a></li>
			<li class="gnav_li"><a href="<?php site_url(); ?>/training-guide/" class="gnav_2a"><span class="gnav_2text">研修ガイド</span><span class="gnav_2sub">ステップアップガイド等</span></a></li>
			<li class="gnav_li"><a href="<?php site_url(); ?>/ranking/"        class="gnav_a" ><span class="gnav_text">人気講座ランキング</span></a></li>
			<li class="gnav_li_last"><a href="<?php site_url(); ?>/mypage/"    class="gnav_a" ><span class="gnav_text">マイページ</span></a></li>
		</ul>
		<style type="text/css">
			.gnav_li{
				display: block;
				width: 191px;
				height: 47px;
				background: url(/img/g_nav_off.png) no-repeat;width: 191px;display:block;height: 47px;
				color:#5D4D34;
			}
			.gnav_li:hover{
				display: block;
				width: 191px;
				height: 47px;
				background: url(/img/g_nav_on.png) no-repeat;width: 191px;display:block;height: 47px;
				color:#FFFFFF;
			}
			.gnav_li A{
				color:#5D4D34;
			}
			.gnav_li A:hover{
				color: #ffffff;
			}
			.gnav_a{
				color:#5D4D34;
				font-size: 16px;
				font-weight: bold;
				line-height: 40px;
				margin: 0;
				display: block;
				height: 100%;
				width: 100%;
				text-decoration: none;
			}
			.gnav_a:hover{
				color: #ffffff;
			}

			.gnav_2a{
				color:#5D4D34;
				font-size: 16px;
				font-weight: bold;
				line-height: 20px;
				margin: 0;
				display: block;
				height: 100%;
				width: 100%;
				text-decoration: none;
			}
			.gnav_2a:hover{
				color: #ffffff;
			}

			.gnav_li_last{
				display: block;
				width: 192px;
				height: 47px;
				background: url(/img/g_nav_last_off.png) no-repeat;width: 191px;display:block;height: 47px;
				color:#5D4D34;
			}
			.gnav_li_last:hover{
				display: block;
				width: 192px;
				height: 47px;
				background: url(/img/g_nav_last_on.png) no-repeat;width: 191px;display:block;height: 47px;
				color:#FFFFFF;
			}
			.gnav_li_last A{
				color:#5D4D34;
				font-size: 16px;
				font-weight: bold;
				line-height: 40px;
				margin: 0;
				display: block;
				height: 100%;
				width: 100%;
				text-decoration: none;
			}
			.gnav_li_last A:hover{
				color: #ffffff;
			}

			.gnav_text{
				width: 100%;
				display: inline-block;
			}
			.gnav_2text{
				width: 100%;
				display: inline-block;

				font-size: 16px;
				line-height: 24px;
				margin-top: 4px;
			}
			.gnav_2sub{
				width: 100%;
				display: inline-block;

				font-size: 8px;
				line-height: 8px;
				vertical-align: top;
			}
		</style>
		<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
	</div><!-- #header -->
	<!--<br style="clear:both;"/>-->
</div>
<?php
}
}
?>
<div id="main">
