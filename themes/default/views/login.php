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
<style type="text/css">
#notice { margin-top: 90px; background-color: #4a0202; border-radius: 5px; }
#error { margin-top: 10px; background-color: red; border-radius: 5px; }
#notice p, #error p { padding: 10px; color: white; }
#centered { width: 450px; margin: auto; }
#intro { margin-top: 20px; text-align: center; font-size: 20px; font-weight: bold; }
fieldset { margin-bottom: 100px; padding: 10px 20px; font-size: 16px; }
legend { margin-left: 3px; font-weight: bold; color: #de2600; }
label { display: inline-block; width: 150px; text-align: right; }
#buttons { text-align: center; }
input[type='submit'] { font-size: 16px; width: 80px; }
</style>
<div id="centered">
 <div id="notice"><?php echo ($notice ? '<p>'.$notice.'</p>' : ''); ?></div>
 <div id="error"><?php echo ($error ? '<p>'.$error.'</p>' : ''); ?></div>
 <p id="intro">Please login as admin/admin:</p>
 <form action="<?php echo $this->CI->uri->uri_string; ?>" method="post" accept-charset="utf-8">
  <fieldset style="border-radius: 5px;">
   <legend>Login</legend>
   <p>
    <label for="usernm">Username:</label>
    <input type="text" name="usernm" />
   </p>
   <p>
    <label for="passwd">Password:</label>
    <input type="password" name="passwd" />
   </p>
   <p id="buttons">
    <input type="submit" value="Login" />
   </p>
  </fieldset>
 </form>
</div>
