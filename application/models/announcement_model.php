<?php
class Announcement_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper("url");
	}
  
  public function get_announcements()
	{
		$this->db->select("id, content, DATE_FORMAT(start_date, '%D %M %Y') AS start, 
                                    DATE_FORMAT(end_date, '%D %M %Y') AS end", false)
             ->where(array('deleted'=>0, 'start_date <='=>date('Y-m-d'), 'end_date >='=>date('Y-m-d')))
		         ->order_by('end_date', 'asc');
		$query = $this->db->get('announcements');
		return $query->result_array();
	}
  
  public function get_old_announcements()
	{
		$this->db->select("id, content, DATE_FORMAT(start_date, '%D %M %Y') AS start, 
                                    DATE_FORMAT(end_date, '%D %M %Y') AS end", false)
             ->where(array('deleted'=>0, 'start_date <'=>date('Y-m-d'), 'end_date <'=>date('Y-m-d')))
		         ->order_by('end_date', 'desc');
		$query = $this->db->get('announcements');
		return $query->result_array();
	}
  
  public function get_future_announcements()
	{
		$this->db->select("id, content, DATE_FORMAT(start_date, '%D %M %Y') AS start, 
                                    DATE_FORMAT(end_date, '%D %M %Y') AS end", false)
             ->where(array('deleted'=>0, 'start_date >'=>date('Y-m-d'), 'end_date >'=>date('Y-m-d')))
		         ->order_by('start_date', 'desc');
		$query = $this->db->get('announcements');
		return $query->result_array();
	}
	
	
  public function get_announcement($id = null)
	{
		$this->db->select("DATE_FORMAT(start_date, '%d %m %Y') AS start_date, 
                       DATE_FORMAT(end_date, '%d %m %Y') AS end_date, content", false);
		$this->db->where('id', $id);
		$query = $this->db->get('announcements');
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
  
  private function parse_announcement_data()
  {
    $start_date_parts = split(" ", $this->input->post('start_date'));
    $start_date = $start_date_parts[2].'-'.$start_date_parts[1].'-'.$start_date_parts[0];
    $end_date_parts = split(" ", $this->input->post('end_date'));
    $end_date = $end_date_parts[2].'-'.$end_date_parts[1].'-'.$end_date_parts[0];
    
    $content =  $this->input->post('content');
    
    $this->db->set(array('start_date' => $start_date,
                         'end_date'   => $end_date,
                         'content'    => $content));
  }
  
  public function insert()
  {
    $this->parse_announcement_data();
    $this->db->set('created', date('Y-m-d H:i:s'));
    $this->db->insert('announcements');
  }
  
  public function update($id)
  {
    $this->parse_announcement_data();
    $this->db->where('id', $id)
             ->update('announcements');
  }
  
  public function delete($id)
  {
    $this->db->update('announcements', array('deleted' => 1), array('id' => $id));
  }
  
  public function expire($id)
  {
    $this->db->update('announcements', 
                      array('end_date' => date('Y-m-d',mktime(0,0,0,date('m'),date('d')-1,date('Y')))), 
                      array('id' => $id));
  }

}