<?php

class theme_plugin extends lex_plugin {

	public function default_action() {
		return '';
	}

	public function include_action() {
		return '';
	}

	public function add_action() {
		$name = $this->get('name');
		$complete = $this->get('complete',false);
		$media = $this->get('media','screen');
		
		if ($name) {
			$uri = CI()->theme->findAsset($name);
			if ($uri) {
				if ($complete) {
					if (substr($name,-4) == '.css') {
						return '<link href="'.$uri.'" media="'.$media.'" rel="stylesheet" type="text/css" />';
					} elseif (substr($name,-3) == '.js') {
						return '<script type="text/javascript" src="'.$uri.'"></script>';
					}
				} else {
					return $uri;
				}
			}
		}
		return '';
	}

	public function title_action() {
		$title = CI()->theme->title;
		$sub = CI()->theme->title_section;
		
		if (!empty($sub)) $title .= CI()->theme->divider.$sub;
		
		return $title;
	}

} /* end transform plugin */
