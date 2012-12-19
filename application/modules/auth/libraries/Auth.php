<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth {
  /* try to load and attach the profile if it's valid */
  public function __construct()
  {
  	public $CI;
  
    parent::__construct();
    $this->CI = get_instance();
    
    $this->CI->profile = new stdClass();

    $this->config->load('auth', TRUE);

    $this->load->model('m_user');
    $this->load->model('m_group');
    $this->load->model('m_group_access');
    $this->load->model('m_access');

    $temp_profile = $this->session->userdata('profile');

    if ($this->m_user->validate_profile($temp_profile)) {
      $this->CI->profile = $temp_profile;
    }
  }

  public function has_profile()
  {
    return $this->m_user->validate_profile();
  }

  /*
  manually set a profile
  clears the profile is it's invalid (null works to force the clearing)
  */
  public function set_profile($profile)
  {
    if (!$this->m_user->validate_profile($profile)) {
      $this->CI->profile = new stdClass();
      $this->session->set_userdata('profile',$this->CI->profile);
      $set = FALSE;
    } else {
      $this->CI->profile = $profile;
      $this->session->set_userdata('profile',$profile);
      $set = TRUE;
    }

    return $set;
  }

  /* test login against m_user and sets the profile if it passes */
  public function login($email=NULL,$password=NULL)
  {
    if (empty($email) || empty($password)) return FALSE;

    $pass = $this->m_user->get_by_login($email,$password);
    if ($pass === FALSE) return FALSE;

    return $this->set_profile($pass);
  }

  /* dump the profile and session always returns true */
  public function logout()
  {
    $this->CI->profile = NULL;
    $this->session->sess_destroy();

    return TRUE;
  }

  /*
  Check Access
  tests the current profile
  */
  public function has_role($role=NULL,$customprofile=NULL)
  {
    if (empty($role)) return FALSE;

    $profile = ($customprofile) ? $customprofile : $this->CI->profile;
    if (!$this->m_user->validate_profile($profile)) return FALSE;

    /* string, string|string (or), string&string (and) */
    $match = NULL;

    if (strpos($role,'|') !== FALSE) {
      $responds = FALSE;
      /* or each */
      $parts = explode('|',$role);
      foreach ($parts as $part) {
        $responds = $this->in_access($part,$profile->access) || $responds;
      }
      $match = $responds;
    }

    if (strpos($role,'&') !== FALSE) {
      $responds = TRUE;
      /* and each */
      $parts = explode('&',$role);
      foreach ($parts as $part) {
        $responds = $this->in_access($part,$profile->access) && $responds;
      }
      $match = $responds;
    }

    if ($match == NULL) {
      $match = $this->in_access($role,$profile->access);
    }

    return $match;
  }

  private function in_access($role,$access)
  {
    $exact = (substr($role,-1) == '*') ? FALSE : TRUE;

    if (!is_array($access)) return FALSE;

    if ($exact) {
      return in_array($role,$access);
    }

    $role = substr($role,0,-1);
    foreach ($access as $a) {
      if ($role == substr($a,0,strlen($role))) {
        return TRUE;
      }
    }

    return FALSE;
  }

  public function __get($name)
  {
    $CI =& get_instance();
    if (isset($CI->$name)) {
      return $CI->$name;
    }

    return NULL;
  }

} /* end class */
