<?php
class Page_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper("url");
	}
	
	public function get_page($slug = null)
	{
		if ($slug == null)
		{
			$navigation_pages = $this->get_navigation_pages();
			$slug = $navigation_pages[0]["slug"];
		}
		elseif (is_numeric($slug))
		{
			$this->db->where("id",$slug);
			$query = $this->db->get("pages");
			$parent = $query->row_array();
			if (empty($parent))
			  return false;
			$slug = $parent["slug"];
		}
		$this->db->where("slug", $slug);
		$query = $this->db->get("pages");
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
	
	public function get_navigation_pages()
	{
		$this->db->where("deleted = 0 AND weight != 'NULL'")->order_by("weight", "asc");
		
		$query = $this->db->get("pages");
		return $query->result_array();
	}
	
	public function get_tiles()
	{
		$this->db->where("deleted", 0)->order_by("weight", "asc");
		$query = $this->db->get("tiles");
		return $query->result_array();
	}
  
  public function update($slug)
  {
    $this->db->set('content', $this->input->post('content', false));
    $this->db->where('slug', $slug)->update('pages');
    return $slug;
  }
	
}