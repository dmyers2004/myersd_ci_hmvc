<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parser_plugin {
	public $args;
	public $content;
	public $CI;
	
	public function __construct($content='',$args=array()) {
		$this->CI = get_instance();
		$this->args = $args;
		$this->content = $content;
	}
	
	public function get($name,$default=NULL) {
		return (isset($this->args[$name])) ? $this->args[$name] : $default;
	}
}
