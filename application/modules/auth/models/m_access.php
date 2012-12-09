<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class m_access extends CI_Model
{
  private $table = 'access';

  public function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  public function get()
  {
    $query = $this->db->get($this->table);

    return $query->results();
  }

  public function get_by_name($name)
  {
    $query = $this->db->get_where($this->table, array('name' => $name), 1);

    return $query->row();
  }

  public function get_by_id($id)
  {
    $query = $this->db->get_where($this->table, array('id' => $id), 1);

    return $query->row();
  }

  public function update($id,$data)
  {
    $data['modified'] = date('Y-m-d H:i:s',time());
    $this->db->update($this->table, $data, array('id' => $id));
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
  }

} /* end class */
