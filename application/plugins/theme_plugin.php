<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class theme_plugin extends Parser_plugin {

	public function default_action() {
		return '';
	}

	public function include_action() {
		$file = $this->get('file');
		$data = array_merge($this->load->_ci_cached_vars,$this->args);
		return $this->CI->parser->parse($file,$data,TRUE);
	}
	
	public function css_action() {
		return $this->CI->theme->getCss();
	}
	public function js_action() {
		return $this->CI->theme->getJs();
	}
	public function meta_action() {
		return $this->CI->theme->getMeta();
	}
	public function extra_action() {
		return $this->CI->theme->getExtra();
	}

	public function getcss_action() {
		$file = $this->get('file');
		$min = $this->get('min');
		$media = $this->get('media','screen');	
		$uri = $this->CI->theme->findAsset($file,$min);
		if ($uri) {
			$html = '<link href="'.$uri.'" media="'.$media.'" rel="stylesheet" type="text/css" />';
		} elseif ($this->CI->theme->config['debug']) {
			$html = '<!-- Can\'t CSS find '.$file.' -->';
		}
		return $html;
	}

	public function getjs_action() {
		$file = $this->get('file');
		$min = $this->get('min');
		$uri = $this->CI->theme->findAsset($file,$min);
		if ($uri) {
			$html = '<script type="text/javascript" src="'.$uri.'"></script>';
		} elseif ($this->CI->theme->config['debug']) {
			$html = '<!-- Can\'t JS find '.$file.' -->';
		}
		return $html;
	}

	public function body_id_action() {
		return $this->CI->theme->body_id;
	}
	
	public function body_class_action() {
		return $this->CI->theme->body_class;
	}

	public function title_action() {
		$title = $this->CI->theme->title;
		$sub = $this->CI->theme->title_section;
		
		if (!empty($sub)) $title .= $this->CI->theme->divider.$sub;
		
		return $title;
	}

} /* end transform plugin */
