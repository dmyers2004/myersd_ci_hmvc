<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class controller_settings extends Public_Controller {

	public function index() {
		//$ary = $this->settings->get_settings_by_group('parser',array('scope glue'=>':'));
		$ary = $this->settings->get_settings_by_group('parser');

		$this->settings->add_setting('new value',123);

		echo '<pre>';
		print_r($ary);
	}

}