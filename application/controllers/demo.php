<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

class Demo extends CI_Controller {
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Call parent first
		parent::__construct();

		// Add package path for our template module
		// This could also be done in config/autoload.php:
		// $autoload['packages'] = array(realpath(dirname(__FILE__).'/../modules/page/'));
		$module = realpath(dirname(__FILE__).'/../modules/page/');
		$this->CI->load->add_package_path($module);
	}

	/**
	 * Default handler
	 */
	public function index()
   	{
		// Load and run header template
		$this->CI->load->controller('template/header');

		// Load view
		$this->CI->load->view('demo');

		// Load and run footer template
		$this->CI->load->controller('template/footer');
	}

	/**
	 * Get some information from another controller
	 */
	public function info()
	{
		$args = array();

		// Here, we call another Controller and get the return value of the method.
		$args['count'] = $this->CI->load->controller('util/dircount', '', TRUE);

		// Now we will call a method with an argument and capture the output
		$this->CI->load->controller_output($args['out'], 'util/arglen/Supercalifragilisticexpialidocious');

		// Next, we'll check and see if a route is valid
		$args['baduri'] = 'page/absent/call';
		$args['badroute'] = $this->CI->router->validate_route($args['baduri']);

		// Finally, check a valid route
		$args['gooduri'] = 'util/stacked';
		$args['goodroute'] = $this->CI->router->validate_route($args['gooduri']);

		// Load and run header template
		$this->CI->load->controller('template/header');

		// Load view in next stack level
		$this->CI->output->stack_push();
		$this->CI->load->view('info', $args);

		// If the good route was valid, call it
		if ($args['goodroute'])
		{
			// If we pass the router stack from validate_route,
			// we don't have to resolve the route all over again
			$this->CI->load->controller($args['goodroute']);
		}

		// Load and run footer template in final stack level
		$this->CI->output->stack_push();
		$this->CI->load->controller('template/footer');

		// When the output gets displayed, all those stack levels will get collapsed
	}
}

/* End of file demo.php */
/* Location: ./application/controllers/demo.php */
