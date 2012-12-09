<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
init setup - patch some CI stuff
all in 1 location early
called by /application/config/autoload.php
*/

/* PHP Composer autoloader */
require(APPPATH.'../vendor/autoload.php');

/*
base class wrapper to make $this work in libs and stuff
*/
class ci_class {
	public $CI;
	
	public function __construct() {
		$this->CI = get_instance();
	}

  public function __call($name, $arguments) {
	  return call_user_func_array(array($this->CI, $name), $arguments);
  }
  
  public function __get($name) {
    if (isset($this->CI->$name)) return $this->CI->$name;

    return NULL;
  }

} /* end class */

/* create a wrapper function for CI super object */
function &CI() {
	return get_instance();
}

/* Include any CI 3.0 Modules autoload.php files */
$modules = glob(APPPATH.'modules/*');
foreach ($modules as $m) @include($m.'/config/autoload.php');

/* load the controller class and the controller extentions */
include(BASEPATH.'core/Controller.php');
include(APPPATH.'core/MY_Controller.php');
