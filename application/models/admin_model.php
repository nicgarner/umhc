<?php
class Admin_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
    $this->load->library('encrypt');
		$this->load->helper("url");
	}
	
	public function verify_user($username, $password)
  {
    $query = $this->db->where('username', $username)
                      ->where('active', 1)
                      ->limit(1)
                      ->get('users');
    if ($query->num_rows > 0)
    {
      $user = $query->row_array();
      if ($this->encrypt->decode($user['password']) == $password)
        return $user;
      else
        return false;
    }
    else
      return false;
  }
  
  
		
}