<?php
class User_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper("url");
    $this->load->library('encrypt');
	}
	
	public function get_users()
	{
		$this->db->select("id, username, first_name, last_name, active");
		$this->db->order_by("active","desc")->order_by("first_name","asc");
		$query = $this->db->get("users");
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
	
	public function get_user($username)
	{
		$this->db->select("id, role, username, email_address, first_name, last_name, active");
		$this->db->where("username", $username);
		$query = $this->db->get("users");
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
  
  public function insert()
  {
    $this->db->set('username', $this->input->post('username'));
    $this->db->set('password', $this->encrypt->encode($this->input->post('password')));
    $this->db->set('email_address', $this->input->post('email_address'));
    $this->db->set('first_name', $this->input->post('first_name'));
    $this->db->set('last_name', $this->input->post('last_name'));
    $this->db->set('created', date('Y-m-d H:i:s'));
    $this->db->insert('users');
    
    return true;
  }
  
  public function update($username)
  {
    if ($this->input->post('password'))
      $this->db->set('password', $this->encrypt->encode($this->input->post('password')));
    $this->db->set('email_address', $this->input->post('email_address'));
    $this->db->set('first_name', $this->input->post('first_name'));
    $this->db->set('last_name', $this->input->post('last_name'));      
    $this->db->where('username', $username)->update('users');
    
    return true;
  }
  
  public function deactivate($username)
  {
    $this->db->update('users', array('active' => 0), array('username' => $username));
    return true;
  }
  
  public function activate($username)
  {
    $this->db->update('users', array('active' => 1), array('username' => $username));
    return true;
  }
		
}























