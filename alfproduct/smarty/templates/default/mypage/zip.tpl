<html>
<head>
<title>住所検索</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
	//<![CDATA[
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
		// 郵便番号入力呼び出し.
		//function fnCallAddress(php_url, tagname1, tagname2, input1, input2) {
		function fnCallAddress(php_url, tagname1, tagname2, input1 ) {
			zip1 = document.form1[tagname1].value;
			zip2 = document.form1[tagname2].value;
			if(zip1.length == 3 && zip2.length == 4) {
				//url = php_url + "?zip1=" + zip1 + "&zip2=" + zip2 + "&input1=" + input1 + "&input2=" + input2;
				url = php_url + "?zip1=" + zip1 + "&zip2=" + zip2 + "&input1=" + input1;
				window.open(url,"nomenu","width=500,height=350,scrollbars=yes,resizable=yes,toolbar=no,location=no,directories=no,status=no");
			} else {
				alert("郵便番号を正しく入力して下さい。");
			}
		}
		// 郵便番号から検索した住所を渡す.
		function fnPutAddress(input1, input2) {
		//function fnPutAddress(input1) {
			// 親ウィンドウの存在確認。.
			if(fnIsopener()) {
				if(document.form1['state'].value != "") {
					// 項目に値を入力する.
					state_id = document.form1['state'].value;
					town = document.form1['city'].value + document.form1['town'].value;
					window.opener.document.form1[input1].selectedIndex = state_id;
					window.opener.document.form1[input2].value = town;
				}
				self.close();
			} else {
				self.close();
			}
		}
	//]]>
</script>
</head>
<body>
<form name="form1" id="form1" method="post" >
<input type="hidden" name="state" value="<!--{$id|escape}-->" />
<input type="hidden" name="city" value="<!--{$city|escape}-->" />
<input type="hidden" name="town" value="<!--{$town|escape}-->" />
</form>
<script>
window.onload = function(){ fnPutAddress('<!--{$input1|escape}-->','<!--{$input2|escape}-->'); }
</script>
</body>
</html>