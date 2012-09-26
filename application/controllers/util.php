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

class Util extends CI_Controller {
	/**
	 * Count the files in the routed directory
	 *
	 * @return	int		Directory file count
	 */
	public function dircount()
	{
		// Get the path and scan the directory
		$count = 0;
		$dir = $this->CI->router->fetch_path().'controllers/';
		foreach (scandir($dir) as $file)
		{
			// Check for php extension
			if (pathinfo($file, PATHINFO_EXTENSION) == 'php')
			{
				++$count;
			}
		}

		return $count;
	}

	/**
	 * Count the characters in an argument
	 *
	 * @param	string	Word to count
	 */
	public function arglen($word)
	{
		// Count the characters and append output (who needs a view?)
		$count = strlen($word);
		$this->CI->output->append_output('The argument "'.$word.'" has '.$count.' characters.');
	}

	/**
	 * Generate output on another stack level
	 */
	public function stacked()
	{
		// Push a new stack level
		$this->CI->output->stack_push();

		// Load a view for some output
		$this->CI->load->view('stacked', array('method' => __METHOD__));
	}
}

/* End of file util.php */
/* Location: ./application/controllers/util.php */
