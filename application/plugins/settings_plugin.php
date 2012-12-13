<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class settings_plugin extends Parser_plugin {

	public function default_action() {
		return '';
	}

	public function get_action() {
		$name = $this->get('name');
		$group = $this->get('group','config');
		
		return $this->CI->settings->get_setting($name,$group);
	}

} /* end transform plugin */
