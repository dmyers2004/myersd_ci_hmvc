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
?>
<h2>Admin</h2>
<p>
 This is a password-protected area which takes advantage of a shared authentication controller.
</p>
<p>
 The controller gets called at the top of each page which requires authentication. If the user is
 not logged in, the auth controller displays the login form and exits, bypassing the rest of the
 routed controller's code.
</p>
<p>
 When the form gets submitted, it returns to the same URL originally requested, where the auth
 controller steps in and handles the POST. On success the session is marked authenticated, and on
 error an error message is set for the next request. Then the auth controller redirects the browser
 back to the same URL again, replacing the POST with a plain GET request.
</p>
<p>
 When the request comes back, either the session is marked authenticated and control is returned to
 the routed controller, or the login form is displayed again with the error message. Either way, the
 browser back button will not resubmit the login form, since the submission has been covered up.
</p>
