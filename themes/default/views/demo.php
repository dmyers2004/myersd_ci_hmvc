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
<h2>HMVC Demo</h2>
<p>
 This simple page makes use of a "module" in the application directory, which contains its own
 Controller, Model, and two Views. The module path is added as a package path so those resources
 can be loaded as if they were directly in the application directory, even though they live in
 their own special subfolder hierarchy.
</p>
<p>
 The module in this case is a page template. The Controller offers two methods: one to load the
 page header, and one to load the page footer. The Model is a representation of the page menu near
 the top, which makes it easy to manipulate what shows up on the menu for various different pages.
 The views contain the HTML for the actual header and footer.
</p>
