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

class Controller_template extends Public_Controller {
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Call parent first
		parent::__construct();

		// Load menu if not already done
		$this->CI->load->model('menu');
	}

	/**
	 * Page header
	 */
	public function header()
	{
		// Get routed class name
		$class = get_class($this->CI->routed);

		// Add routed function name
		$class .= ' '.$this->CI->router->fetch_method();

		// Load header template
		$this->CI->load->view('header', array('class' => $class));
	}

	/**
	 * Page footer
	 */
	public function footer()
	{
		// Load footer template
		$this->CI->load->view('footer');
	}
	
	public function myers() {
		$this->CI->load->view('myers');
		$this->load->view('blog/cookies');
	}
	
}

/* End of file template.php */
/* Location: ./application/controllers/template.php */
