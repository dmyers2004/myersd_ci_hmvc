<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* for some reason CI Controller isn't loaded at this point */

class MY_Controller extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
}

class Admin_Controller extends MY_Controller {
	public function __construct()
	{
			parent::__construct();
	}
}

class Public_Controller extends MY_Controller {
	public function __construct()
	{
			parent::__construct();
	}
}

class Auth_Controller extends MY_Controller {
	public function __construct()
	{
			parent::__construct();
	}
}

class AJAX_Controller extends MY_Controller {
	public function __construct()
	{
			parent::__construct();
	}
}

class Cli_Controller extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->input->is_cli_request())
			show_404('Page Not Found');
	}
}