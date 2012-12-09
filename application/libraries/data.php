<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* oop wrapper for CI views _ci_cached_vars */
/* to use my pages class and parser you must use this */
class Data {
  /* get a global view variable */
  function __get($name) {
    return CI()->load->_ci_cached_vars[$name];
  }

  /* set a global view variable */
  function __set($name,$value) {
    CI()->load->_ci_cached_vars[$name] = $value;
  }

  /* check if a global view variable is set */
  function __isset($name) {
    return isset(CI()->load->_ci_cached_vars[$name]);
  }

  /* unset a global view variable */
  function __unset($name) {
    unset(CI()->load->_ci_cached_vars[$name]);
  }
  
  function data($name=null,$value=null) {
  	if ($name == null) return CI()->load->_ci_cached_vars;
    $this->$name = $value;  
    return $this;
  }

} /* end class */
