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
<!DOCTYPE html>
<html>
 <head>
  <style type="text/css">
   body { font-family: 'Arial', 'Helvetica', sans-serif; }
   #content { width: 80%; margin: 0 auto; }
   #header, #footer { padding: 10px; color: white; background-color: #4a0202; }
   #header { font-size: 42px; font-weight: bold; font-style: italic; vertical-align: top; position: relative; border-top-left-radius: 7px; border-top-right-radius: 7px; }
   #header p { color: #de2600; font-size: 32px; position: absolute; right: 15px; bottom: 0; }
   #footer { margin-top: 20px; font-size: 15px; border-radius: 7px; }
   #footer span { font-weight: bold; font-style: italic; position: relative; bottom: 5px; }
   #footer p { float: right; line-height: 0; }
   #logo { display: inline-block; background-color: white; padding: 5px; border-radius: 5px; }
   #menu { margin-bottom: 20px; padding: 10px; background-color: #de2600; border-bottom-left-radius: 7px; border-bottom-right-radius: 7px; font-size: 18px; }
   #menu ul { list-style: none; height: 20px; margin: 0; }
   #menu li { margin: 0; padding: 0; float: left; text-align: center; }
   #menu a { color: white; text-decoration: none; padding: 0 20px; }
   #menu a:hover { text-decoration: underline; }
  </style>
 </head>
 <body>
  <div id="content">
   <div id="header">
    <span id="logo"><img src="http://codeigniter.com/user_guide/images/ci_logo_flame.jpg" alt="Logo" /></span>
	CodeIgniter HMVC Demo
	<p>Welcome to the <?php echo $class; ?> page!</p>
   </div>
   <div id="menu">
    <ul>
<?php foreach ($this->CI->menu as $label => $url): ?>
     <li><a href="<?php echo $url; ?>"><?php echo $label; ?></a></li>
<?php endforeach; ?>
	</ul>
   </div>
