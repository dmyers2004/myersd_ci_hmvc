<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		{{ theme.meta }}
		<title>{{ theme.title }}</title>
		{{ theme.getcss file="css/reset.css" }}
		{{ theme.getcss file="bootstrap/css/bootstrap.css" }}
		{{ theme.getcss file="blueprint/grid.css" }}
		{{ theme.getcss file="css/application.css" }}
		{{ theme.css }}
		{{ theme.getjs file="jquery/jquery-1.7.2.min.js" }}
		{{ theme.getjs file="bootstrap/js/bootstrap.js" }}
		{{ theme.getjs file="js/application.js" }}
		{{ theme.js }}
		{{ theme.extra }}
	</head>
	<body id="{{ theme.body.id }}" class="{{ theme.body.class }}">
		<div class="container">
		{{ settings.get name="scope glue" group="parser" }}
		{{ settings.get name="email" }}
		{{ settings.get name="theme folder" group="theme" }}