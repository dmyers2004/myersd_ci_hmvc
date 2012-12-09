<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_Cli extends Cli_Controller {

	public function index() {
	}
	
	public function migrate() {
		$this->load->library('migration');
		
		if (!$this->migration->latest()) {
			show_error($this->migration->error_string());
		} else {
			die('Migration Complete'.chr(10));
		}
	}
	
} /* end controller */

