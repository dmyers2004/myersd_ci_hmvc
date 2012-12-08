<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* oop wrapper for CI views _ci_cached_vars */
/* to use my pages class and parser you must use this */
class data {
  protected $CI;

  function __construct() {
    $this->CI =& get_instance(); /* get instance of CI */
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
    $this->CI->load->_ci_cached_vars[$name] = $value;  
    return $this;
  }

} /* end class */
