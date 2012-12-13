<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * site settings
 *
 * @package		CI Settings
 * @author		Eric Barnes <http://ericlbarnes.com>
 * @copyright	Copyright (c) Eric Barnes
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Settings Library
 *
 * Used to manage site settings
 *
 * @package		CI Settings
 * @subpackage	Libraries
 */
class Settings
{
	/**
	 * Global CI Object
	 */
	private $_ci;

	/**
	 * Settings array used to pass settings to template
	 *
	 * @access 	private
	 * @var 	array
	 */
	public $settings = array();


	// ------------------------------------------------------------------------

	/**
	 * Constructor assign CI instance
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->_ci =& get_instance();
		self::get_settings();
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Settings
	 *
	 * Get all the auto loaded settings from the db.
	 *
	 * @return	array
	 */
	public function get_settings()
	{
		// If the array is not empty we already have them.
		if ( ! empty ($this->settings))
		{
			return $this->settings;
		}

		if ( ! $this->_ci->db->table_exists('settings'))
		{
			return FALSE;
		}

		/* load and cache them all */
		$query = $this->_ci->db->select('option_group,option_name,option_value')->from('settings')->order_by('option_group')->where('option_active',1)->get();;

		if ($query->num_rows() == 0)
		{
			return FALSE;
		}

		foreach ($query->result() as $row) {
			$this->settings[$row->option_group][$row->option_name] = $row->option_value;
		}

		return $this->settings;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Setting (Notice Singular)
	 *
	 * Used to pull out one specific setting from the settings table.
	 *
	 * Here is an example:
	 * <code>
	 * <?php
	 * $this->settings->get_setting('site_name');
	 * ?>
	 * </code>
	 *
	 * @access	public
	 * @param	string
	 * @return	mixed
	 */
	public function get_setting($option_name = '',$option_group = NULL)
	{
		if ( ! $option_name)
		{
			return FALSE;
		}
		
		$ary = $this->settings;
		if ($option_group) {
			if (isset($this->settings[$option_group])) {
				$ary = $this->settings[$option_group];
			}
		}

		// First check the auto loaded settings
		if (isset($ary[$option_name]))
		{
			return $ary[$option_name];
		}

		// Still nothing. How about config?
		// This will return FALSE if not found.
		if ($option_group) {
			$value = $this->_ci->config->item($option_name,$option_group);	
		} else {
			$value = $this->_ci->config->item($option_name);	
		}

		return $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Settings By Group
	 *
	 * Get all the settings from one group
	 *
	 * @param	string
	 * @return	object
	 */
	public function get_settings_by_group($option_group = '',$override=array()) {
		if (!$option_group) {
			return FALSE;
		}

		$final_config = array();
		
		if ($option_group != null) {
			$this->_ci->load->config($option_group,TRUE);
			
			$settings = (is_array(@$this->settings[$option_group])) ? $this->settings[$option_group] : array();
			$final_config = array_merge($this->_ci->config->item($option_group),$settings,$override);
		}
		return $final_config;	
	}

	// ------------------------------------------------------------------------

	/**
	 * Edit Setting
	 *
	 * @param	string $option_name
	 * @param	string $option_value
	 * @return	bool
	 */
	public function edit_setting($option_name, $option_value)
	{
		$this->_ci->db->where('option_name', $option_name);
		$this->_ci->db->update('settings', array('option_value' => $option_value));

		$this->settings[$option_name] = $option_value;

		if ($this->_ci->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete Setting by group
	 *
	 * @param	string $option_group
	 * @return	bool
	 */
	public function delete_settings_by_group($option_group)
	{
		$this->_ci->db->delete('settings', array('option_group' => $option_group));

		$this->settings[$option_group] = NULL;

		if ($this->_ci->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Add Setting
	 *
	 * Add a new setting but first check and make sure it doesn't exist already exit.
	 *
	 * @param	string $option_name
	 * @param	string $option_value
	 * @param	string $option_group
	 * @param	string $auto_load
	 * @return	bool
	 */
	public function add_setting($option_name, $option_value = '', $option_group = 'config', $option_active = 1)
	{
		// Check and make sure it isn't already added.
		$this->_ci->db->select('option_value')
					->from('settings')
					->where('option_name', $option_name)
					->where('option_group', $option_group);

		$query = $this->_ci->db->get();

		if ($query->num_rows() > 0)
		{
			return $this->edit_setting($option_name, $option_value);
		}

		// Now insert it
		$data = array(
			'option_name' => $option_name,
			'option_value' => $option_value,
			'option_group' => $option_group,
			'option_active' => $option_active
		);

		$this->_ci->db->insert('settings', $data);
		
		$this->get_settings();

		if ($this->_ci->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	public function disable_settings_by_group($option_group = '')
	{
		if (!$option_group) {
			return FALSE;
		}
		$this->db->where('option_group', $option_group);
		$this->db->update('settings', array('option_active'=>0));

		if ($this->_ci->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}
	
	public function enable_settings_by_group($option_group = ''	)
	{
		if (!$option_group) {
			return FALSE;
		}
		$this->db->where('option_group', $option_group);
		$this->db->update('settings', array('option_active'=>1));

		if ($this->_ci->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete Setting
	 *
	 * @param	string $option_group
	 * @return	bool
	 */
	public function delete_setting($option_name)
	{
		$this->_ci->db->delete('settings', array('option_name' => $option_name));

		$this->settings[$option_name];
	
		if ($this->_ci->db->affected_rows() == 0)
		{
			return FALSE;
		}

		return TRUE;
	}
}

/* End of file Settings.php */