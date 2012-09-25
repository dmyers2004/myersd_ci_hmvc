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

		// Load and run footer template
		$this->CI->load->controller('template/footer');
	}
}

/* End of file demo.php */
/* Location: ./application/controllers/demo.php */
