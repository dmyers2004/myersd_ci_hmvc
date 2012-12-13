<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
base class wrapper to make $this work in libs and stuff
*/
class ci_class {
	public $CI;
	public $config;
	
	public function __construct() {
		$this->CI = get_instance();
	}

	public function load_config($config='') {
		$this->config = $this->CI->settings->get_settings_by_group($config);
	}

  public function __call($name, $arguments) {
	  return call_user_func_array(array($this->CI, $name), $arguments);
  }
  
  public function __get($name) {
    if (isset($this->CI->$name)) return $this->CI->$name;
    return NULL;
  }
} /* end class */