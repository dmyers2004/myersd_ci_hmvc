<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<?=$plugin->theme->meta() ?>
		<title><?=$plugin->theme->title ?></title>
		<?=$plugin->theme->css('css/reset.css') ?>
		<?=$plugin->theme->css('bootstrap/css/bootstrap.css') ?>
		<?=$plugin->theme->css('blueprint/grid.css') ?>
		<?=$plugin->theme->css('css/application.css') ?>
		<?=$plugin->theme->css ?>
		<?=$plugin->theme->js('jquery/jquery-1.7.2.min.js') ?>
		<?=$plugin->theme->js('bootstrap/js/bootstrap.js') ?>
		<?=$plugin->theme->js('js/application.js') ?>
		<?=$plugin->theme->js ?>
		<?=$plugin->theme->extra ?>
	</head>
	<body id="<?=$plugin->theme->id ?>" class="<?=$plugin->theme->class ?>">
		<div class="container">