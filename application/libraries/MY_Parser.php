<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Parser extends CI_Parser {
	protected $CI;
	protected $config;
	
	public function __construct() {
		$this->CI = get_instance();

    $this->CI->load->config('parser',TRUE);
		$this->config = $this->CI->config->item('parser');

		/* make the default like Lex */
		$this->set_delimiters($this->config['left tag'],$this->config['right tag']);
	}

	protected function _parse($template, $data, $return = FALSE)
	{
		if ($template === '')
		{
			return FALSE;
		}
		
		$lex = new Lex\Parser();
		$lex->scopeGlue($this->config['scope glue']);
		$template = $lex->parse($template,$this->CI->load->_ci_cached_vars,array($this,'plugin_handler'),$this->config['allow php']);

		if ($return === FALSE)
		{
			$this->CI->output->append_output($template);
		}
		
		if (is_string($return))
		{
    	$this->CI->load->_ci_cached_vars[$return] = $template;
		}

		return $template;
	}
	
	public function plugin_handler($name, $attributes, $content) {
		$segs = explode($this->config['scope glue'],$name);
		$class = $segs[0];
		$method = isset($segs[1]) ? $segs[1] : 'default';
		$method .= '_action';
		
		$plugin_file = $this->locator($class);
		if ($plugin_file) {
			include_once($plugin_file);
			$plugin_name = $class.'_plugin';
			$plugin = new $plugin_name($content,$attributes);
			if (method_exists($plugin, $method)) {
				return call_user_func(array($plugin,$method));
			}
		}
		
		/* debug on return something useful */
		if ($this->config['debug']) {
			return '['.$name.']['.print_r($attributes,true).']['.$content.']';
		}
	
		/* not found and debugging off */
		return '';
	}

	private function locator($name) {
		/* first try the system plugin folder */
		if (file_exists(APPPATH.$this->config['plugin folder'].'/'.$name.'.php')) {
			return APPPATH.$this->config['plugin folder'].'/'.$name.'.php';
		}
	
		/* now try the module folders */
		$folders = glob(APPPATH.$this->config['module folder'].'/*');
		foreach ($folders as $folder) {
			if (file_exists($folder.'/'.$this->config['plugin folder'].'/'.$name.'.php')) {
				return $folder.'/'.$this->config['plugin folder'].'/'.$name.'.php';
			}
		}
		
		return null;
	}

	
} /* end MY_Parser */

/* add on plugin parent class */
class lex_plugin {
	public $args;
	public $content;
	
	public function __construct($content='',$args=array()) {
		$this->args = $args;
		$this->content = $content;
	}
	
	public function get($name,$default=NULL) {
		return (isset($this->args[$name])) ? $this->args[$name] : $default;
	}
}
