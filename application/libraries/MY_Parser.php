<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require('MY_Parser/Parser_plugin.php');

class MY_Parser extends CI_Parser {
	public $config;
	public $CI;
	public $cache;

	public function __construct() {
		$this->CI = get_instance();
		
		/* make the default ci parser like Lex */
		//$this->CI->load->config('parser',TRUE);
		//$this->config = $this->CI->config->item('parser');
		$this->config = $this->CI->settings->get_settings_by_group('parser');
		
		$this->set_delimiters('{{','}}');
		
		/* load cache */
		$this->cache = $this->CI->cache->get('plugins');
				
		if (!$this->cache) {
			/* system plugins */
			$path = glob(APPPATH.$this->config['plugin folder'].'/*.php');
			foreach ($path as $name) {
				$this->cache[substr(basename($name),0,-11)] = substr($name,strlen(APPPATH)-1);
			}
			
			/* module plugins */
			$paths = glob(APPPATH.$this->config['module folder'].'/*');
			foreach ($paths as $module) {
				$plugins = glob($module.'/'.$this->config['plugin folder'].'/*');
				foreach ($plugins as $plugin) {
					$this->cache[substr(basename($plugin),0,-11)] = substr($plugin,strlen(APPPATH)-1);
				}
			}
			$this->CI->cache->save('plugins',$this->cache,300);
		}
		
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

		$class = strtolower(array_shift($segs));
		$method = isset($segs[0]) ? implode('_',$segs).'_action' : 'default_action';
		$reply = '';

		/* cache this */
		if (isset($this->cache[$class])) {
			$plugin_file = APPPATH.$this->cache[$class];
		} else {
			$plugin_file = '###';
		}

		$class .= '_plugin';

		if (file_exists($plugin_file)) {
			include_once($plugin_file);
			$plugin = new $class($content,$attributes);
			if (method_exists($plugin, $method)) {
				$reply = call_user_func(array($plugin,$method));
			} elseif ($this->config['debug']) {
				$reply = 'Plugin Method Not Found '.$class.' '.$method;;
			}
		} elseif ($this->config['debug']) {
			$reply = 'Plugin Not Found '.$class;
		}

		return $reply;
	}
	
} /* end MY_Parser */
