<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>HTML5 Basic Template</title>
		<link href="assets/css/site.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="assets/js/jquery.1.8.2.js"></script>
		<script type="text/javascript" src="assets/js/site.js"></script>
{{ asset_js }}
{{ asset_css }}
	</head>
	<body>
		<h1>HTML5 Basic Template</h1>
		<p>This is a <a href="">sample</a> template.</p>
		{{ transform.uppercase name="navigation" }}
			A bunch of stuff
		{{ /transform.uppercase }}
		{{ transform name="navigation" }}
			A bunch of stuff
		{{ /transform }}
	</body>
</html>
