<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class assets {
  protected $CI;

	public $assets = array();	
  public $config = array();
  public $cache = array();

  public function __construct($config = array()) {
    $this->CI = &get_instance();

    $this->CI->load->config('assets',TRUE);
		$this->config = array_merge($this->CI->config->item('assets'),$config);

    /* run the autoloaders */
    foreach ($this->config['autoload']['css'] as $file) $this->add($file);
    foreach ($this->config['autoload']['js'] as $file) $this->add($file);
    foreach ($this->config['autoload']['meta'] as $name=>$content) $this->add($name,$content);
    foreach ($this->config['autoload']['extra'] as $content) $this->add($content);
    $this->updateData('js')->updateData('css')->updateData('meta')->updateData('extras');
  }

  public function addAsset($path,$content=null,$update = true) {
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
		} elseif ($content != null) {
	    $this->assets['meta'][$md5] = '<meta name="'.$path.'" content="'.$content.'"/>';
	 		if ($update) $this->updateData('meta');
		} else {
			$this->assets['extra'][$md5] = $path;
	 		if ($update) $this->updateData('extra');
		}

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

	public function addTheme($name) {
		$theme = APPPATH .$this->config['theme folder'].'/'.$name.'/';
		$this->config['search path'][md5($name)] = $name;
		return $this;
	}

	public function removePath($name) {
		unset($this->config['search path'][md5($name)]);
		return $this;
	}

	public function findAsset($file) {
		/* external link / direct link */
    if (substr($file,0,4) == 'http' || substr($file,0,1) == '/') return $file;

		/* needs cache! */
		$temp = array_reverse(array_merge(array($this->config['default theme']),$this->config['search path']));
		foreach ($temp as $folder)
			if (file_exists(FCPATH.$this->config['theme folder'].'/'.$folder.'/'.$file))
				return '/'.$this->config['theme folder'].'/'.$folder.'/'.$file;

		$modules = glob(FCPATH.$this->config['module folder'].'/*');
		foreach ($modules as $folder) {
			if (file_exists($folder.'/'.$file)) {
				return '/'.str_replace(FCPATH,'',$folder.'/'.$file);
			}
		}

		return null;
	}

} /* end class */
