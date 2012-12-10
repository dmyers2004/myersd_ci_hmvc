<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controller_Prowl extends Public_Controller
{
 	function index()
 	{
		$this->load->library('prowl');
		
		$result = $this->prowl->add('There has been a issue at the plant');
		
		print_r($result);
 	}
}

// add($application = "php-prowl",$event,$priority = 0,$description,$url="")