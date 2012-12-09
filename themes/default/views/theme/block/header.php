<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		{{ asset_meta }}

		<title>{{ theme.title }}</title>

		{{ theme.add name="css/reset.css" complete="true" }}
		{{ theme.add name="bootstrap/css/bootstrap.css" complete="true" }}
		{{ theme.add name="blueprint/grid.css" complete="true" }}
		{{ theme.add name="css/application.css" complete="true" }}
		{{ asset_css }}

		{{ theme.add name="jquery/jquery.1.8.2.js" complete="true" }}
		{{ theme.add name="bootstrap/js/bootstrap.js" complete="true" }}
		{{ theme.add name="js/application.js" complete="true" }}

		{{ asset_js }}
		{{ asset_extra }}
	</head>
	<body id="{{ body_id }}" class="{{ body_class }}">
		<div class="container">