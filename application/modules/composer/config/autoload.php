<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'../vendor/autoload.php');

function &CI() {
	return get_instance();
}