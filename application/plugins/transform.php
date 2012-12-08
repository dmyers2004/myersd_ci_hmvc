<?php

class transform_plugin extends lex_plugin {

	public function default_action() {
		return 'Default Replacement';
	}

	public function uppercase_action() {
		return strtoupper($this->content);
	}

	public function lowercase_action() {
		return strtolower($this->content);
	}

} /* end transform plugin */