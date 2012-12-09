<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class theme extends ci_class {
	public $assets = array('css'=>'','js'=>'','meta'=>'','extra'=>'');	
  public $config = array();
  public $cache = array();
  public $search = array();

  public function __construct($config = array()) {
    $this->CI = &get_instance();

    $this->CI->load->config('theme',TRUE);
		$this->config = array_merge($this->CI->config->item('theme'),$config);

    /* run the autoloaders */
    foreach ($this->config['autoload']['css'] as $file) $this->addAsset($file,false);
    foreach ($this->config['autoload']['js'] as $file) $this->addAsset($file,false);
    foreach ($this->config['autoload']['meta'] as $key => $value) $this->addMeta($key,$value,false);
    foreach ($this->config['autoload']['extra'] as $content) $this->addExtra($content,false);
    
    $this->updateData('js')->updateData('css')->updateData('meta')->updateData('extra');
		
		$this->data->body_id = $this->router->fetch_class().'_'.$this->router->fetch_method();
		$this->data->body_class = $this->router->fetch_class().' '.$this->router->fetch_method();

		$this->title = $this->config['title'];		
		$this->title_section = '';
		$this->divider = $this->config['section divider'];
		
		$this->search = $this->config['search path'];

		/* add the default as the "base" */
		$this->addDefault($this->config['default theme']);
  }

	public function setSection($name) {
		$this->title_section = $name;
	}

  public function addAsset($path,$update = true) {
  	$md5 = md5($path);
		if (isset($this->cache[$md5])) return $this;
		
		$ext = substr($path,-3);
 		$file = $this->findAsset($path);

		if ($ext == '.js') {
	 		if ($file != null) {
			  $this->assets['js'][$md5] = '<script src="'.$file.'"></script>';
	 		} elseif ($this->config['debug']) {
			  $this->assets['js'][$md5] = '<!-- Script Not Found '.$path.' -->';
	 		}
	 		if ($update) $this->updateData('js');
		} elseif ($ext == 'css') {
	    if ($file != null) {
		    $this->assets['css'][$md5] = '<link href="'.$file.'" media="'.(($content) ? $content : 'screen').'" rel="stylesheet" type="text/css" />';
	 		} elseif ($this->config['debug']) {
	    	$this->assets['css'][$md5] = '<!-- Link Not Found '.$path.' -->';
	    }
	 		if ($update) $this->updateData('css');
		}

  	return $this;
  }

	public function addExtra($content,$update = true) {
		$this->assets['extra'][md5($content)] = $content;
 		if ($update) $this->updateData('extra');
 		
 		return $this;
	}

  public function addMeta($key,$content,$update = true) {
  	$this->assets['meta'][$key] = '<meta name="'.$key.'" content="'.$content.'"/>';
	 	if ($update) $this->updateData('meta');

  	return $this;
  }

  
  public function getImage($path) {
  	return findAsset($path);
  }

	public function updateData($which) {
		$this->CI->load->vars(array('asset_'.$which=>trim(implode(chr(10),@(array)$this->assets[$which])).chr(10)));
		return $this;
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
		$this->search = array(md5($name)=>$name) + $this->search;
		
		$theme = dirname(APPPATH).'/'.$this->config['theme folder'].'/'.$name.'/';
		$this->CI->load->add_package_path($theme);
		
		return $this;
	}

	public function removePath($name) {
		unset($this->search[md5($name)]);
		return $this;
	}

	public function findAsset($file) {
		/* external link / direct link */
    if (substr($file,0,4) == 'http' || substr($file,0,1) == '/') return $file;

		/* needs cache! */
		foreach ($this->search as $folder)
			if (file_exists(FCPATH.$this->config['theme folder'].'/'.$folder.'/'.$file))
				return '/'.$this->config['theme folder'].'/'.$folder.'/'.$file;

/*
		$modules = glob(FCPATH.$this->config['module folder'].'/*');
		foreach ($modules as $folder) {
			if (file_exists($folder.'/'.$file)) {
				return '/'.str_replace(FCPATH,'',$folder.'/'.$file);
			}
		}
*/

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

} /* end class */
