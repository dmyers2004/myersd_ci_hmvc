<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_Asset extends Public_Controller {

	public function index() {

		$this->theme->addTheme('redrock');
		
		$this->theme->addAsset('bootstrap/css/bootstrap.css');
		$this->theme->addAsset('bootstrap/css/min-bootstrap.css');
		$this->theme->addAsset('css/test.css');
		$this->theme->addAsset('jquery.bootstrap.growl.js');
		$this->theme->addAsset('author','Don Myers');

		$this->parser->parse('example');
	}
	
	public function newtheme() {


		$this->parser->parse('example');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */