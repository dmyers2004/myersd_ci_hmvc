<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_Asset extends Public_Controller {

	public function index() {

		$this->theme->addTheme('redrock');
		
		$this->theme->addCss('bootstrap/css/bootstrap.css');
		$this->theme->addCss('bootstrap/css/min-bootstrap.css');
		$this->theme->addCss('css/test.css');
		$this->theme->addJs('jquery.bootstrap.growl.js');
		$this->theme->addJs('author','Don Myers');

		$this->parser->parse('example');
	}
	
	public function newtheme() {


		$this->parser->parse('example');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */