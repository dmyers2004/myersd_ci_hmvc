<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst. It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

/**
 * Logging Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Logging
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/errors.html
 */
class CI_Log {

	/**
	 * Path to save log files
	 *
	 * @var string
	 */
	protected $_log_path;

	/**
	 * Level of logging
	 *
	 * @var int
	 */
	protected $_threshold		= 1;

	/**
	 * Highest level of logging
	 *
	 * @var int
	 */
	protected $_threshold_max	= 0;

	/**
	 * Array of threshold levels to log
	 *
	 * @var array
	 */
	protected $_threshold_array	= array();

	/**
	 * Format of timestamp for log files
	 *
	 * @var string
	 */
	protected $_date_fmt		= 'Y-m-d H:i:s';

	/**
	 * Whether or not the logger can write to the log files
	 *
	 * @var bool
	 */
	protected $_enabled		= TRUE;

	/**
	 * Predefined logging levels
	 *
	 * @var array
	 */
	protected $_levels		= array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4);

	/**
	 * Configure Logging class
	 *
	 * This function lets the core object configure logging so it is not
	 * dependent on CI_Config being loaded before it can be used.
	 *
	 * @return	void
	 */
	public function configure($config)
	{
		$this->_log_path = (isset($config['log_path']) && ! empty($config['log_path'])) ?
			$config['log_path'] : APPPATH.'logs/';

		if ( ! is_dir($this->_log_path) OR ! is_really_writable($this->_log_path))
		{
			$this->_enabled = FALSE;
		}

		if (isset($config['log_threshold']))
		{
			if (is_numeric($config['log_threshold']))
			{
				$this->_threshold = (int) $config['log_threshold'];
			}
			elseif (is_array($config['log_threshold']))
			{
				$this->_threshold = $this->_threshold_max;
				$this->_threshold_array = array_flip($config['log_threshold']);
			}
		}

		if (isset($config['log_date_format']) && ! empty($config['log_date_format']))
		{
			$this->_date_fmt = $config['log_date_format'];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}

		$level = strtoupper($level);

		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
			&& ! isset($this->_threshold_array[$this->_levels[$level]]))
		{
			return FALSE;
		}

		$filepath = $this->_log_path.'log-'.date('Y-m-d').'.php';
		$message = '';

		if ( ! file_exists($filepath))
		{
			$newfile = TRUE;
			$message .= '<'."?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		}

		if ( ! $fp = @fopen($filepath, 'a+'))
		{
			return FALSE;
		}

		$message .= $level.' '.($level === 'INFO' ? ' -' : '-').' '.date($this->_date_fmt).' --> '.$msg."\n";

		flock($fp, LOCK_EX);
		fwrite($fp, $message);
		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === TRUE)
		{
			@chmod($filepath, FILE_WRITE_MODE);
		}

		return TRUE;
	}

}

/* End of file Log.php */
/* Location: ./system/libraries/Log.php */
