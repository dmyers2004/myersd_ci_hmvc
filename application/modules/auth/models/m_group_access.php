<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class m_group_access extends CI_Model
{
  private $table = 'group_access';

  public function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  public function insert($group,$access)
  {
    if (!is_numeric($group)) {
      $group_rec = $this->m_group->get_by_name($group);
      $group = $group_rec->id;
    }
    if (!is_numeric($access)) {
      $access_rec = $this->m_access->get_by_name($access);
      $access = $access_rec->id;
    }

    $data = array('group_id'=>$group,'access_id'=>$access);

    $this->db->delete($this->table, $data);
    $this->db->insert($this->table, $data);
  }

  public function delete_by_group($group)
  {
    if (!is_numeric($group)) {
      $group_rec = $this->m_group->get_by_name($group);
      $group = $group_rec->id;
    }

    $this->db->delete($this->table, array('group_id'=>$group));
  }

  public function delete_by_access($access)
  {
    if (!is_numeric($access)) {
      $access_rec = $this->m_access->get_by_name($access);
      $access = $access_rec->id;
    }

    $this->db->delete($this->table, array('group_id'=>$access));
  }

} /* end class */
