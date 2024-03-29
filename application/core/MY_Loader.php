<?php
/* override the protected */
class MY_Loader extends CI_Loader {
	public $_ci_cached_vars;

	/*
	place a this path after modules but before the core stuff
	This is for view files but you can over ride almost anything
	*/
	public function add_theme_path($path) {
		// Resolve path
		$path = CodeIgniter::resolve_path($path);
		$strlen = strlen(APPPATH);

		$new = array();
		foreach ($this->_ci_library_paths as $key => $value) {
			if (strlen($value) == $strlen)
				$new[] = $path;
			$new[] = $value;
		}
		$this->_ci_library_paths = $new;

		$new = array();
		foreach ($this->_ci_mvc_paths as $key => $value) {
			if (strlen($key) == $strlen)
				$new[$path] = true;
			$new[$key] = $value;
		}
		$this->_ci_mvc_paths = $new;

		$new = array();
		foreach ($this->CI->config->_config_paths as $key => $value) {
			$new[] = $value;
			if (strlen($value) == $strlen) {
				$new[] = $path;
			}
		}
		$this->CI->config->_config_paths = $new;
	}
	
	public function settings($group=null) {
		if ($group == null) return;
	
		/* first load file based config */
		$this->CI->config->load($group, TRUE, TRUE);
		$file_array = $this->CI->config->item($group);
	
		/* safety check */
		$file_array = (!is_array($file_array)) ? array() : $file_array;
		
		/* then load database config */
		$db_array = $this->CI->settings->get_settings_by_group($group);
	
		/* safety check */
		$db_array = (!is_array($db_array)) ? array() : $db_array;
		
		/* then merge them (db over file) and return */
		return array_merge_recursive($file_array,$db_array);
	}
	
}
