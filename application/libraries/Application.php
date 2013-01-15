<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application {
	public $CI;
	public $config = array();

	public function __construct($config = array()) {
		$this->CI = get_instance();

		$this->config = $this->CI->load->settings(get_class());
	}

}
