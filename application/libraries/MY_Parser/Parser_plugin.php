<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parser_plugin extends ci_class {
	public $args;
	public $content;
	
	public function __construct($content='',$args=array()) {
		parent::__construct();
		$this->args = $args;
		$this->content = $content;
	}
	
	public function get($name,$default=NULL) {
		return (isset($this->args[$name])) ? $this->args[$name] : $default;
	}
}
