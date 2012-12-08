<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class asset extends CI_Controller {

	public function index() {
		$this->assets->addTheme('rockstar');
		
		$this->assets->addAsset('bootstrap/css/bootstrap.css');
		$this->assets->addAsset('bootstrap/css/min-bootstrap.css');
		$this->assets->addAsset('css/test.css');
		$this->assets->addAsset('jquery.bootstrap.growl.js');
		$this->assets->addAsset('author','Don Myers');

		$this->parser->parse('example');
	}
	
	public function newtheme() {
		//$this->load->library('assets');

		$this->assets->addTheme('redrock');

		$this->parser->parse('example');
		

	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */