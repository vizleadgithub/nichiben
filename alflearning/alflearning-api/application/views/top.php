<!doctype html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>API test</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="/static/css/common.css" type="text/css" />
</head>
<body>
	<div class="wrapper">
		<h1>Partner server -> ALFLearening</h1>
		<ul>
			<li>
				<h2>login check</h2>
				<form action="/login" method="post">
					<label>session_id</label><input type="text" name="session_id" value="" />
					<label>debug</label>
					<input type="radio" name="debug" value="1">:ON
					<input type="radio" name="debug" value="0" checked="checked">:OFF
					<input type="submit" value="login" />
				</form>
			</li>
		</ul>
	</div>
</body>
</html>
