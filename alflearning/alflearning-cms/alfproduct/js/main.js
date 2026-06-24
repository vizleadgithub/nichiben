$(function(){
	//table listの操作
	$("table.list tr:even td").css('background', '#F6F6F3');
	$("table.list2 tr:even td").css('background', '#F6F6F3');
	$("table.list td").bind('click', function(){
		var _href = $(this).parent().find('a').attr('href');
		if(_href){
			window.location.href = _href;
		}
	})
	$("table.list td").hover(function() {
		$(this).parent().find('td').addClass('hover');
	}, function() {
		$(this).parent().find('td').removeClass('hover');
	});
	$("table.list2 td").hover(function() {
		$(this).parent().find('td').addClass('hover');
	}, function() {
		$(this).parent().find('td').removeClass('hover');
	});

	//table formの操作
	$("table.form tr:even td").css('background', '#F6F6F3');
	$("table.form tr:even th").css('background', '#F6F6F3');
});

//----------------------------------------------
//ログアウトボタン押下
//----------------------------------------------
function logout_confirm(url, msg){
	if(window.confirm(msg)){
		location.href = url + 'login_page/logout';
	}
}
