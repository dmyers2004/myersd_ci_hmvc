<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class m_user extends CI_Model
{
  private $table = 'user';

  public $max_tries;
  public $advance_remember;
  public $key_length;

  /* in hours */
  public $active_timeframe;
  public $forgot_timeframe;
  public $remember_timeframe;

  public function __construct()
  {
    // Call the Model constructor
    parent::__construct();

    $this->max_tries = ($this->config->item('max_tries', 'auth')) ? $this->config->item('max_tries', 'auth') : 5;
    $this->advance_remember = ($this->config->item('advance_remember', 'auth')) ? $this->config->item('advance_remember', 'auth') : TRUE;
    $this->key_length = ($this->config->item('key_length', 'auth')) ? $this->config->item('key_length', 'auth') : 72;

    $this->active_timeframe = ($this->config->item('active_timeframe', 'auth')) ? $this->config->item('active_timeframe', 'auth') : 72;
    $this->forgot_timeframe = ($this->config->item('forgot_timeframe', 'auth')) ? $this->config->item('forgot_timeframe', 'auth') : 72;
    $this->remember_timeframe = ($this->config->item('remember_timeframe', 'auth')) ? $this->config->item('remember_timeframe', 'auth') : 336;

    /* convert hours to seconds */
    $this->active_timeframe = $this->active_timeframe * 3600;
    $this->forgot_timeframe = $this->forgot_timeframe * 3600;
    $this->remember_timeframe = $this->remember_timeframe * 3600;
  }

  /*
  returns an array of profiles key = primary id
  while running a query in a loop from a query isn't the fastest this in really only used for the Admin User GUI
  I can't see many other uses the system would want to load everyone's profile?
  */
  public function get_all()
  {
    $results = array();
    $query = $this->db->get($this->table);
    foreach ($query->result() as $row) {
      $results[$row->id] = $this->get_by($row->id);
    }

    return $results;
  }

  /* returns a profile */
  public function get_by_login($email=NULL,$password=NULL)
  {
    if (empty($email) || empty($password)) return FALSE;

    $salt_row = $this->get_by($email);
    if ($salt_row == FALSE) return FALSE;

    /* simple query to see if this login & password are valid and return false or primary id */
    $query = $this->db->select('id')->get_where($this->table,array('email'=>$email,'password'=>$this->hash_password($password.$salt_row->salt),'active'=>1));

    /* use this to get the login encoded password */
    //echo('***** LOGIN QUERY '.$this->db->last_query().chr(10));

    /* Fail if we don't get exactly 1 record */
    if ($query->num_rows() !== 1) return FALSE;

    $row = $query->row();
    if (!is_numeric($row->id)) return FALSE;

    return $this->get_by($row->id);
  }

  public function hash_password($password=NULL)
  {
    if (empty($password)) return FALSE;

    return sha1($password.$this->config->item('encryption_key'));
  }

  public function update($id,$data)
  {
    $data['modified'] = date('Y-m-d H:i:s',time());
    $this->db->update($this->table, $data, array('id' => $id));

    return $this->db->affected_rows();
  }

  public function insert($data)
  {
    $data['created'] = date('Y-m-d H:i:s',time());
    $this->db->insert($this->table, $data);

    return $this->db->insert_id();
 }

  public function delete($id)
  {
    $this->db->delete($this->table, array('id' => $id));

    return $this->db->affected_rows();
  }

  private function deactivate($activate)
  {
    if ($activate) $this->auth->set_profile(NULL);

    return FALSE;
  }

  /* returns a full profile */
  public function get_by($user_id,$active=FALSE,$activate=FALSE)
  {
    $profile = new stdClass();

    /* is it a email? */
    if (strpos($user_id,'@') !== FALSE) {
      $query = $this->db->get_where($this->table,array('email'=>$user_id),1);
      $row = $query->row();
      if (!count($row) || $row === FALSE) return $this->deactivate($activate);
      $user_id = $row->id;
    }

    /* is string (name) */
    if (!is_numeric($user_id)) {
      $query = $this->db->get_where($this->table,array('name'=>$user_id),1);
      $row = $query->row();
      if (!count($row) || $row === FALSE) return $this->deactivate($activate);
      $user_id = $row->id;
    }

    /* if it's still not a id then fail */
    if (!is_numeric($user_id)) return $this->deactivate($activate);

    /* or id should be something */
    $this->db->select('*, user.id as user_id, group.name as group_name, user.name as user_name, group.id as group_id,access.id as access_id');
    $this->db->join('group','group.id = user.group_id');
    $this->db->join('group_access','group_access.group_id = group.id');
    $this->db->join('access','access.id = group_access.access_id');
    $this->db->where('user.id',$user_id);

    if ($active) {
      $this->db->where('access.active',1);
    }

    $query = $this->db->get($this->table);
    if ($query->num_rows() == 0) return $this->deactivate($activate);

    $access = array();
    foreach ($query->result() as $row) {
      $profile = $row;
      $access[$row->access_id] = $row->resource;
    }
    /* clean up a few things */
    $profile->id = $row->user_id;
    $profile->group_id = $row->group_id;
    $profile->group = $row->group_name;
    $profile->name = $row->user_name;
    unset($profile->access_id);
    unset($profile->resource);
    $profile->access = $access;

    /* thank goodness we should only need this once when they log in! */

    if ($activate) {
      $this->auth->set_profile($profile);
    }

    return $profile;
  }

  /* return boolean */
  public function validate_profile($profile = NULL)
  {
    if (!is_object($profile)) {
      $profile = $this->get_by($profile);
    }

    if (!is_object($profile)) return FALSE;
    if (!isset($profile->access)) return FALSE;
    if (!is_array($profile->access)) return FALSE;
    if (!is_numeric($profile->id)) return FALSE;

    return TRUE;
  }

  /* return boolean */
  public function not_max_tries($email=NULL)
  {
    if (empty($email)) return FALSE;
    $row = $this->get_by($email,TRUE);

    if (!count($row) || $row === FALSE) return FALSE;

    if ($row->tries > $this->max_tries + 1) return FALSE;

    $this->update($row->id,array('tries'=>($row->tries + 1)));

    return TRUE;
  }

  /* return boolean */
  public function tries_reset($email=NULL)
  {
    if (empty($email)) return FALSE;
    $row = $this->get_by($email,TRUE);

    if (!count($row) || $row === FALSE) return FALSE;

    $this->update($row->id,array('tries'=>0));

    return TRUE;
  }

  /* return key */
  public function email_activate($email=NULL)
  {
    if (empty($email)) return FALSE;
    $row = $this->get_by($email);

    if (!count($row) || $row === FALSE) return FALSE;

    $activate_key = $this->make_key();

    $this->update($row->id,array('activate_key'=>$activate_key,'activate_timeframe'=>$this->ts($this->active_timeframe)));

    return $activate_key;
  }

  /* return boolean */
  public function check_email_activate($email=NULL,$key=NULL)
  {
    if (empty($email) || empty($key)) return FALSE;

    $row = $this->get_by_key('activate',$key,FALSE);
    if (!count($row) || $row === FALSE) return FALSE;

    if ($row->email != $email) return FALSE;
    if ($row->active = 1) return TRUE;

    $this->update($row->id,array('active'=>1,'activate_timeframe'=>0));

    return TRUE;
  }

  /* return key */
  public function forgot_password($email=NULL)
  {
    if (empty($email)) return FALSE;
    $row = $this->get_by($email,TRUE);
    if (!count($row) || $row === FALSE) return FALSE;

    $forgot_key = $this->make_key();
    $this->update($row->id,array('forgot_key'=>$forgot_key,'forgot_timeframe'=>$this->ts($this->forgot_timeframe)));

    return $forgot_key;
  }

  /* return boolean */
  public function check_forgot_password($key=NULL,$new=NULL)
  {
    if (empty($key)) return FALSE;

    $row = $this->get_by_key('forgot',$key);
    if (!count($row) || $row === FALSE) return FALSE;

    if (!empty($new)) {
      $salt_row = $this->get_by($row->id);
      if (!count($salt_row) || $row === FALSE) return FALSE;
      $salt = $salt_row->salt;

      $this->update($row->id,array('forgot_timeframe'=>0,'password'=>$this->hash_password($new.$salt)));
    }

    return TRUE;
  }

  /* return key */
  public function set_remember_me($id=NULL,$set=TRUE)
  {
    if (empty($id)) return FALSE;
    $remember_key = $this->make_key();

    $this->update($id,array('remember_key'=>$remember_key,'remember_timeframe'=>$this->ts($this->remember_timeframe)));

    if ($set) {
      $this->input->set_cookie('reminisce', $remember_key, $this->remember_timeframe);
    }

    return $remember_key;
  }

  /* return FALSE or user id */
  public function check_remember_me($key=NULL)
  {
    if (empty($key)) return FALSE;
    $row = $this->get_by_key('remember',$key);

    if (!count($row) || $row === FALSE) return FALSE;

    if ($this->advance_remember) {
      $this->update($row->id,array('remember_timeframe'=>$this->ts($this->remember_timeframe)));
    }

    /* set a new key for next time */
    $this->set_remember_me($row->id,TRUE);

    return $row->id;
  }

  /* private return user id or FALSE */
  private function get_by_key($type,$key,$active = TRUE)
  {
    $where = ($active) ? array($type.'_key'=>$key,'active'=>1,$type.'_timeframe > '=>$this->ts()) : array($type.'_key'=>$key,$type.'_timeframe > '=>$this->ts());
    $query = $this->db->get_where($this->table,$where);
    $row = $query->row();
    if (!count($row) || $row === FALSE) return FALSE;

    return $row;
  }

  private function make_key()
  {
    return substr(base64_encode(sha1(uniqid(php_uname('n'),true)).md5(uniqid(php_uname('n'),true))),0,$this->key_length);
  }

  private function ts($add_seconds=0)
  {
    return date('Y-m-d H:i:s',time() + $add_seconds);
  }

} /* end class */
