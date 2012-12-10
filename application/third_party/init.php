<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
init setup - patch some CI stuff
all in 1 location early
called by /application/config/autoload.php
*/

/* PHP Composer autoloader */
require(APPPATH.'../vendor/autoload.php');
require('ci_class.php');

/* create a wrapper function for CI super object */
function &CI() {
	return get_instance();
}

/* Include any CI 3.0 Modules autoload.php files */
$modules = glob(APPPATH.'modules/*');
foreach ($modules as $m) @include($m.'/config/autoload.php');

/*
load the controller class and the controller extensions
not sure why the MY Controller with multi extended controller classes doesn't work any more? CI 3.0?
*/
include(BASEPATH.'core/Controller.php');
include(APPPATH.'core/MY_Controller.php');
