<?php
class Ctte_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper('url');
	}
	
	public function get_ctte($year = null)
	{
		if($year === null)
			$year = $this->get_max_ctte_year();
		
		$this->db->select('name, year, course, age, spotted, mountain, tipple, mobile, picture_prefix, quote,
		                   (SELECT name FROM ctte_roles WHERE ctte_roles.id = role_id) as role,
											 (SELECT name FROM ctte_role_categories WHERE ctte_role_categories.id = 
											     (SELECT category_id FROM ctte_roles WHERE ctte_roles.id = role_id)) as category,
											 (SELECT CASE WHEN co_role > 0 THEN (SELECT email FROM ctte_roles WHERE ctte_roles.id = co_role)
                                                     ELSE (SELECT email FROM ctte_roles WHERE ctte_roles.id = role_id) END) as email,
											 (SELECT weight FROM ctte_role_categories WHERE ctte_role_categories.id = 
											     (SELECT category_id FROM ctte_roles WHERE ctte_roles.id = role_id)) as category_weight,
											 (SELECT weight FROM ctte_roles WHERE ctte_roles.id = role_id) as role_weight,
                       (SELECT CASE WHEN co_role > 0 THEN (SELECT name FROM ctte_roles WHERE ctte_roles.id = co_role) 
                                                     ELSE co_role END) as co_role');
		$this->db->where('year', $year);
		$this->db->where('deleted', 0);
		$this->db->order_by('category_weight', 'asc')->order_by('role_weight', 'asc');
		$query = $this->db->get('ctte_appointments');
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
	
	public function get_max_ctte_year()
	{
		$query = $this->db->select_max('year')->where('deleted', 0)->get('ctte_appointments');
		$result = $query->row_array();
		return $result['year'];
	}
  
  public function get_archive()
  {
    $query = $this->db->select('DISTINCT year', false)
                      ->where('deleted', 0)
                      ->order_by('year','asc')
                      ->get('ctte_appointments');
    return $query->result_array();
  }
}