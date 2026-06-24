<?php /* Smarty version 2.6.31, created on 2025-01-25 10:27:12
         compiled from member/zip_err.tpl */ ?>
<html>
<head>
<title>住所検索</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
		// 親ウィンドウの存在確認.
		function fnIsopener() {
			var ua = navigator.userAgent;
			if( !!window.opener ) {
				if( ua.indexOf('MSIE 4')!=-1 && ua.indexOf('Win')!=-1 ) {
					return !window.opener.closed;
				} else {
					return typeof window.opener.document == 'object';
				}
			} else {
				return false;
			}
		}
		function fnWinClose() {
			// 親ウィンドウの存在確認。.
			if(fnIsopener()) {
				//window.close();
				self.close();
				//window.opener.focus();
			} else {
				//window.close();
				self.close();
			}
		}
</script>
</head>
<body>
住所が存在しませんでした。
<script>
window.onload = function(){ fnWinClose(); }
</script>
</body>
</html>