<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme {
	public $assets = array('css'=>'','js'=>'','meta'=>'','extra'=>'');	
  public $cache = array();
  public $search = array();
  public $body_id = '';
  public $body_class = '';
  public $config = array();
  public $CI;
  public $plugins = array();

  public function __construct($config = array()) {
		$this->CI = get_instance();

    $this->config = $this->CI->load->settings(get_class());
    
    $this->plugins = array('theme','settings');

    /* run the autoloaders */
    foreach ($this->config['autoload']['css'] as $file) $this->addCss($file);
    foreach ($this->config['autoload']['js'] as $file) $this->addJs($file);
    foreach ($this->config['autoload']['meta'] as $key => $value) $this->addMeta($key,$value);
    foreach ($this->config['autoload']['extra'] as $content) $this->addExtra($content);
    		
    $class = trim(str_replace('controller','', strtolower($this->CI->router->fetch_class())),'_');
    $method = $this->CI->router->fetch_method();
		
		$this->body_id = $class.'_'.$method;
		$this->body_class = $class.' '.$method;

		$this->title = $this->config['title'];		
		$this->title_section = '';
		$this->divider = $this->config['section divider'];
		
		$this->search = $this->config['search path'];

		/* add the default as the "base" */
		$this->addDefault($this->config['default theme']);
		
		/* load cache */
		$this->cache = $this->CI->cache->get('theme_assets');
				
		if (!$this->cache) {
			$path = FCPATH.$this->config['theme folder'];
			$themes = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
			foreach ($themes as $key => $name) {
				if (substr(basename($name),0,1) != '.') {
					$this->cache[substr($key,strpos($key,'/',strlen($path)+1)+1)] = substr($name,strlen(FCPATH)-1);
				}
			}
			$this->CI->cache->save('theme_assets',$this->cache,300);
		}
		
  }

	public function setSection($name) {
		$this->title_section = $name;
	}

	public function addCss($name,$media='screen') {
  	$md5 = md5($name);
	
		if (!$this->assets['css'][$md5]) {
			$this->assets['css'][$md5] = $this->buildcss($name,$media);
		}

  	return $this;
	}
	
	public function insert($view,$args=array(),$rtn=true) {
		$args = array_merge_recursive($this->CI->load->_ci_cached_vars,$args);
		return $this->CI->load->view($view,$args,$rtn);
	}
	
	public function buildcss($name,$media='screen') {
 		$html = '';
 		$file = $this->findAsset($name);
 		
    if ($file != null) {
	    $html = '<link href="'.$file.'" media="'.$media.'" rel="stylesheet" type="text/css" />';
 		} elseif ($this->config['debug']) {
    	$html = '<!-- Link Not Found '.$name.' -->';
    }
	
		return $html;
	}
	
	public function addJs($name) {
  	$md5 = md5($name);
  	
		if (!$this->assets['js'][$md5]) { 
	 		$this->assets['js'][$md5] = $this->findAsset($name);	
		}

  	return $this;
	}

	public function buildjs($name) {
		$html = '';
 		$file = $this->findAsset($name);

 		if ($file != null) {
		  $html = '<script src="'.$file.'"></script>';
 		} elseif ($this->config['debug']) {
		  $html = '<!-- Script Not Found '.$name.' -->';
 		}
 		
 		return $html;
	}

	public function addExtra($content) {
		$this->assets['extra'][md5($content)] = $content; 		
 		return $this;
	}

  public function addMeta($name,$content) {
  	$md5 = md5($name);
  	$this->assets['meta'][md5($md5)] = $this->buildMeta($name,$content);
  	return $this;
  }

	public function buildMeta($name,$content) {
		return '<meta name="'.$name.'" content="'.$content.'"/>';
	}

  public function getImage($path) {
  	return findAsset($path);
  }
	
	/* !todo add sep functions */
	public function removeCss($name) {
		unset($this->assets['css'][md5($name)]);
		return $this;
	}
	
	public function removeJs($name) {
		unset($this->assets['js'][md5($name)]);	
		return $this;
	}
	
	public function removeMeta($name) {
		unset($this->assets['meta'][md5($name)]);	
		return $this;
	}
	
	public function removeExtra($name) {
		unset($this->assets['extra'][md5($name)]);	
		return $this;
	}
	
	private function addDefault($name) {
		$this->search = array(md5($name)=>$name) + $this->search;
		
		$theme = dirname(APPPATH).'/'.$this->config['theme folder'].'/'.$name.'/';
		$this->CI->load->add_theme_path($theme);

		return $this;
	}

	public function addTheme($name) {
		$this->search += array(md5($name)=>$name);
		
		$theme = dirname(APPPATH).'/'.$this->config['theme folder'].'/'.$name.'/';
		$this->CI->load->add_package_path($theme);
		
		return $this;
	}

	public function removePath($name) {
		unset($this->search[md5($name)]);
		return $this;
	}

	public function findAsset($file,$min=false) {
		/* external link / direct link */
    if (substr($file,0,4) == 'http' || substr($file,0,1) == '/') {
    	return $file;
    }
    
		if (array_key_exists($file,$this->cache)) {
			return $this->cache[$file];
		}
		
		if (array_key_exists($min,$this->cache) && $this->config['use min']) {
			return $this->cache[$min];
		}
		
		return null;
	}
	
	public function block($view,$name='body') {
		$this->attach_php_plugins();
		$this->CI->load->_ci_cached_vars[$name] = $this->CI->load->view($view,$this->CI->load->_ci_cached_vars,TRUE);
		return $this;
	}
	
	public function render($template='1column') {
		$this->attach_php_plugins();
		/* 1column, 2column-right, 2column-left, 3column */
		$this->CI->load->view('theme/'.$template,$this->CI->load->_ci_cached_vars);
		return $this;
	}

	public function js($name=null) {
		if ($name == null) {
	    return $this->get_wrapper('js');
		} else {
			return $this->buildjs($name);
		}
	}

	public function meta() {
    return $this->get_wrapper('meta');	
	}

	public function extra() {
    return $this->get_wrapper('extra');	
	}

	public function css($name=null,$media='screen') {
		if ($name == null) {
	    return $this->get_wrapper('css');
		} else {
			return $this->buildcss($name,$media);
		}
	}

	public function addPlugin($class_name='') {
		if (!empty($class_name)) {
			$this->plugins[] = $class_name;
		}
		
		return $this;
	}

	private function get_wrapper($which) {
    return trim(implode(chr(10),@(array)$this->assets[$which])).chr(10);	
	}
	
	public function attach_php_plugins() {
		$plugins = new stdclass;
		
		foreach ($this->plugins as $plugin) {
			if (class_exists($plugin,false)) {
				$plugins->$plugin = &$this->CI->$plugin;
			}
		}
		
		$this->CI->load->_ci_cached_vars[$this->config['plugin variable name']] = $plugins;
	}

	public function __get($name) {
		switch ($name) {
			case 'css':
				return $this->css();
			break;
			case 'js':
				return $this->js();
			break;
			case 'extra':
				return $this->extra();
			break;
			case 'meta':
				return $this->meta();
			break;
			case 'id':
				return $this->body_id;
			break;
			case 'class':
				return $this->body_class;
			break;
		}
	}

} /* end class */
