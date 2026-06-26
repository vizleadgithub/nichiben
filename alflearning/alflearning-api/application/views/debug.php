<?
	function parseArray($arr){
		if(count($arr)){
			return preg_replace(array('/^.*?\((.+)\)/is', '/^[\r\n]*?/is', '/[\r\n]*?$/is',  '/(\r\n|\r|\n)/is'), array('$1', '', '', '<br />') , print_r($arr, true));
		}
		else{
			return 'none';
		}
	}
?>
<!doctype html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>API test debug view</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="/static/css/common.css" type="text/css" />
</head>
<body>
	<div class="wrapper">
		<h1>Request</h1>
		<ul>
			<li>
				<h2>PATH</h2>
				<div><?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?></div>
			</li>
			<li>
				<h2>Query[GET]</h2>
				<div><?= htmlspecialchars(parseArray($_GET), ENT_QUOTES, 'UTF-8'); ?></div>
			</li>
			<li>
				<h2>Query[POST]</h2>
				<div><?= htmlspecialchars(parseArray($_POST), ENT_QUOTES, 'UTF-8'); ?></div>
			</li>
		</ul>

		<h1>Response Variable</h1>
		<? $this->load->library('dBug', $data); ?>
	</div>
</body>
</html>
