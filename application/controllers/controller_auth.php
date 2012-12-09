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

class Controller_Auth extends Public_Controller {
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Call parent first
		parent::__construct();

		// Load session driver
		$this->CI->load->driver('Session', array('sess_driver' => 'native'));

		// Add package path for our template module
		// This could also be done in config/autoload.php:
		// $autoload['packages'] = array(realpath(dirname(__FILE__).'/../modules/page/'));
		$module = realpath(dirname(__FILE__).'/../modules/page/');
		$this->CI->load->add_package_path($module);
	}

	/**
	 * Check for authentication
     *
     * This method gets called from another controller
     * to ensure the user is logged in or present a login
     * form if not.
     *
     * When the form is required, the view is loaded and
     * the method exits, bypassing the other controller code.
     * Otherwise, we just return to the normally scheduled
     * programming.
	 */
	public function required()
   	{
		// Check for auth
		if ($this->CI->session->userdata('authenticated'))
		{
			// OK - load menu and add logout
			$this->CI->load->model('menu');
			$this->CI->menu->Logout = $this->CI->config->site_url('auth/logout');

			// Return to routed controller
			return;
		}

		// None - check for form submission
		if (isset($_POST['usernm']) && isset($_POST['passwd']))
		{
			// Credentials submitted - validate
			if ($_POST['usernm'] == 'admin' && $_POST['passwd'] == 'admin')
			{
				// Good to go - set session var
                // When we return here, the auth check above will pass
				$this->CI->session->set_userdata('authenticated', TRUE);
			}
			else
			{
				// Set error for login form
                // When we return here, it will get picked up below
				$this->CI->session->set_flashdata('error', 'Invalid username/password');
			}

            // Redirect to cover POST
            $url = $this->CI->config->site_url($this->CI->uri->uri_string());
            $this->CI->load->view('redirect', array('url' => $url));
            exit;
		}

        // Get any notice or error
        $notice = $this->CI->session->flashdata('notice');
        $error = $this->CI->session->flashdata('error');

		// Display login form inside template
		$this->CI->load->controller('template/header');
		$this->CI->load->view('login', array('notice' => $notice, 'error' => $error));
		$this->CI->load->controller('template/footer');

		// Exit so routed controller doesn't run
		exit;
	}

    /**
     * Logout
     *
     * This method gets called directly by URL to logout the
     * user. It then redirects them back to the login screen
     * with a nice message.
     */
	public function logout()
	{
		// Clear authentication
		$this->CI->session->set_userdata('authenticated', FALSE);

        // Set notice for next page
        $this->CI->session->set_flashdata('notice', 'You have successfully logged out');

		// Redirect back to admin
		$this->CI->load->view('redirect', array('url' => $this->CI->config->site_url('admin')));
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
