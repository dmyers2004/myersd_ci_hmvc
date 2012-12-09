<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* this is run backwards and when you use add it is added to the end */

$config['debug'] = true;

/* theme front end js/css/images & backend views */
$config['theme folder'] = 'themes';
$config['module folder'] = 'modules';

$config['default theme'] = 'default';

$config['search path'] = array();

$config['autoload'] = array(
	'css'		=> array(),
	'js'		=> array(),
	'meta'	=> array('Name'=>'Don Myers'),
	'extra'	=> array()
);

$config['title'] = 'My Site';
$config['section divider'] = ' / ';
