<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class controller_cook extends Cli_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('cli');
	}

	public function index() {
		$this->cli->write('This is a test','red');
	}
	
	public function model($name='') {
		$this->cli->write('Model '.$name,'red');
	}

}