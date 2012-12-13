<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class theme extends ci_class {
	public $assets = array('css'=>'','js'=>'','meta'=>'','extra'=>'');	
  public $cache = array();
  public $search = array();
  public $body_id = '';
  public $body_class = '';

  public function __construct($config = array()) {
		parent::__construct();
    $this->load_config('theme');

    /* run the autoloaders */
    foreach ($this->config['autoload']['css'] as $file) $this->addCss($file);
    foreach ($this->config['autoload']['js'] as $file) $this->addJs($file);
    foreach ($this->config['autoload']['meta'] as $key => $value) $this->addMeta($key,$value);
    foreach ($this->config['autoload']['extra'] as $content) $this->addExtra($content);
    		
    $class = substr($this->router->fetch_class(),11);
    $method = $this->router->fetch_method();
		
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
			foreach ($themes as $key => $name)
				if (substr(basename($name),0,1) != '.')
					$this->cache[substr($key,strpos($key,'/',strlen($path)+1)+1)] = substr($name,strlen(FCPATH)-1);
			$this->CI->cache->save('theme_assets',$this->cache,300);
		}
		
  }

	public function setSection($name) {
		$this->title_section = $name;
	}

	public function addCss($name,$media='screen') {
  	$md5 = md5($name);
	
		if (!$this->assets['css'][$md5]) { 
	 		$file = $this->findAsset($name);
	
	    if ($file != null) {
		    $this->assets['css'][$md5] = '<link href="'.$file.'" media="'.$media.'" rel="stylesheet" type="text/css" />';
	 		} elseif ($this->config['debug']) {
	    	$this->assets['css'][$md5] = '<!-- Link Not Found '.$name.' -->';
	    }
		}

  	return $this;
	}
	
	public function addJs($name) {
  	$md5 = md5($name);
  	
		if (!$this->assets['js'][$md5]) { 
	 		$file = $this->findAsset($name);
	
	 		if ($file != null) {
			  $this->assets['js'][$md5] = '<script src="'.$file.'"></script>';
	 		} elseif ($this->config['debug']) {
			  $this->assets['js'][$md5] = '<!-- Script Not Found '.$name.' -->';
	 		}
		}

  	return $this;
	}

	public function addExtra($content) {
		$this->assets['extra'][md5($content)] = $content; 		
 		return $this;
	}

  public function addMeta($key,$content) {
  	$this->assets['meta'][md5($key)] = '<meta name="'.$key.'" content="'.$content.'"/>';
  	return $this;
  }

  public function getImage($path) {
  	return findAsset($path);
  }
	
	/* !todo add sep functions */
	public function removeCss($name) {
		
	}
	
	public function removeJs($name) {
	
	}
	
	public function removeMeta($name) {
	
	}
	
	public function removeExtra($name) {
	
	}
	
	public function removeAsset($path) {
		$md5 = md5($path);
		unset($this->cache[$md5]);
		$ext = substr($path,-3);
		if ($ext == '.js') {
			unset($this->assets['js'][$md5]);		
	 		$this->updateData('js');
		} elseif ($ext == 'css') {		
			unset($this->assets['css'][$md5]);
	 		$this->updateData('css');
		} else {
			unset($this->assets['meta'][$md5]);
			unset($this->assets['extra'][$md5]);
	 		$this->updateData('meta');
	 		$this->updateData('extra');
		}
		
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

	public function findAsset($file,$min) {
		/* external link / direct link */
    if (substr($file,0,4) == 'http' || substr($file,0,1) == '/') return $file;
		
		if (array_key_exists($file,$this->cache)) return $this->cache[$file];
		if (array_key_exists($min,$this->cache) && $this->config['use min']) return $this->cache[$min];
		
		return null;
	}
	
	public function block($view,$name='body') {
		$this->load->_ci_cached_vars[$name] = $this->parser->parse($view,$this->load->_ci_cached_vars,TRUE);
		return $this;
	}
	
	public function render($template='1column') {
		/* 1column, 2column-right, 2column-left, 3column */
		$this->parser->parse('theme/'.$template,$this->load->_ci_cached_vars);
		return $this;
	}

	public function get_wrapper($which) {
    return trim(implode(chr(10),@(array)$this->assets[$which])).chr(10);	
	}

	public function getJs() {
    return $this->get_wrapper('js');	
	}

	public function getMeta() {
    return $this->get_wrapper('meta');	
	}

	public function getExtra() {
    return $this->get_wrapper('extra');	
	}

	public function getCss() {
    return $this->get_wrapper('css');	
	}

} /* end class */
