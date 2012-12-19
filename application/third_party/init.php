<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
init setup - patch some CI stuff
all in 1 location early
called by /application/config/autoload.php
*/

/* PHP Composer autoloader */
require(APPPATH.'../vendor/autoload.php');

/* Include any Modules autoload.php files */
$modules = glob(APPPATH.'modules/*');
foreach ($modules as $m) @include($m.'/config/autoload.php');
