<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* oop wrapper for CI views _ci_cached_vars */
/* to use my pages class and parser you must use this */
class Data extends ci_class {
	function __construct() {
		parent::__construct();
		if (!is_array($this->CI->load->_ci_cached_vars))
			$this->CI->load->_ci_cached_vars = array();
	}

  /* get a global view variable */
  function __get($name) {
    return $this->CI->load->_ci_cached_vars[$name];
  }

  /* set a global view variable */
  function __set($name,$value) {
    $this->CI->load->_ci_cached_vars[$name] = $value;
  }

  /* check if a global view variable is set */
  function __isset($name) {
    return isset($this->CI->load->_ci_cached_vars[$name]);
  }

  /* unset a global view variable */
  function __unset($name) {
    unset($this->CI->load->_ci_cached_vars[$name]);
  }
  
  function data($name=null,$value=null) {
  	if ($name == null) return $this->CI->load->_ci_cached_vars;
    $this->$name = $value;  
    return $this;
  }
} /* end class */
