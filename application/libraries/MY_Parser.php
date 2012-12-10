<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require('MY_Parser/Parser_plugin.php');

class MY_Parser extends CI_Parser {

	public function __construct() {
		/* make the default ci parser like Lex */
		$this->set_delimiters('{{','}}');
	}

	protected function _parse($template, $data, $return = FALSE)
	{
		if ($template === '')
		{
			return FALSE;
		}
		
		$data = array_merge($this->CI->load->_ci_cached_vars,$data);
		
		$lex = new Lex\Parser();
		$lex->scopeGlue($this->config['scope glue']);
		$template = $lex->parse($template,$data,array($this,'plugin_handler'),$this->config['allow php']);

		if ($return === FALSE)
		{
			$this->CI->output->append_output($template);
		}
		
		return $template;
	}
	
	/* new */
	public function plugin_handler($name, $attributes, $content) {
		if (strpos($name,$this->config['scope glue']) !== false) {
			$segs = explode($this->config['scope glue'],$name);
		} else {
			$segs[0] = $name;
		}

		$class = $segs[0];
		$method = isset($segs[1]) ? $segs[1] : 'default';
		$method .= '_action';
		$reply = '';
		
		$plugin_file = $this->plugin_locator($class);
		
		if (file_exists($plugin_file)) {
			include_once($plugin_file);
			$plugin = new $class($content,$attributes);
			if (method_exists($plugin, $method)) {
				$reply = call_user_func(array($plugin,$method));
			}
		} elseif ($this->config['debug']) {
			$reply = 'Plugin Not Found '.$class.' '.$method;;
		}
	
		return $reply;
	}

	private function plugin_locator($name) {
		/* first try the system plugin folder */
		if (file_exists(APPPATH.$this->config['plugin folder'].'/'.$name.'.php')) {
			return APPPATH.$this->config['plugin folder'].'/'.$name.'.php';
		}
	
		/* now try the module plugin folders */
		$folders = glob(APPPATH.$this->config['module folder'].'/*');
		foreach ($folders as $folder) {
			if (file_exists($folder.'/'.$this->config['plugin folder'].'/'.$name.'.php')) {
				return $folder.'/'.$this->config['plugin folder'].'/'.$name.'.php';
			}
		}
		
		return null;
	}
	
} /* end MY_Parser */
