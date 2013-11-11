<?php
class News_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper("url");
		$this->load->helper("text");
	}
	
	public function get_news($page = 0)
	{
		$this->db->select("title, slug, content, DATE_FORMAT(created, '%D %M %Y') AS date", false);
		$this->db->where("deleted", 0);
		$this->db->order_by("created", "desc");
    $this->db->limit(5,$page);
		$query = $this->db->get("news");
		
		if (empty($query))
		  return false;
		else
    {
		  $data['news_items'] = $query->result_array();
      
      $this->db->select("id");
      $this->db->where("deleted", 0);
      $this->db->order_by("created", "desc");
      $query = $this->db->get("news");
      
      $data['number_of_news_items'] = $query->num_rows();
      
      return $data;
    }
	}
	
	public function get_latest_news($limit = 3)
	{
		$date = date("Y-m-d");
		$this->db->select("title, slug, content, DATE_FORMAT(created, '%D %M %Y') AS date", false);
		$this->db->where("deleted", 0)->order_by("created", "desc")->limit($limit,0);
		$query = $this->db->get("news");
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
		
}