<?php
class Social_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper("url");
	}
	
	public function get_socials($year = null)
	{
		if ($year == null)
			if (date("n") >= 9)
			  $year = date("Y");
			elseif (date("n") == 8 && date("d") >= 25)
			  $year = date("Y");
			else
			  $year = date("Y") - 1;
		$next_year = $year+1;
		
		$this->db->select("title, location, slug, facebook, email, image, cancelled, deleted, 
		                   DATE_FORMAT(date, '%Y%m%d') AS testdate, DATE_FORMAT(date, '%D %M') AS date, 
		                   DATE_FORMAT(date, '%d-%m-%Y') AS slug_date, TIME_FORMAT(time, '%H:%i') AS time", false);
		$this->db->where("date >=", "$year-09-01");
		$this->db->where("date <=", "$next_year-08-30");
    
    if (!isset($_SESSION['username']))
      $this->db->where("deleted", 0);
		
    if (isset($_SESSION['username']))
      $this->db->order_by("deleted", "asc");
    
		$this->db->order_by("testdate", "asc");
		$query = $this->db->get("socials");
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
	
	public function get_social($slug, $date)
	{
		$this->db->select("title, location, facebook, map, email, cancelled, contact,
		                   DATE_FORMAT(date, '%D %M') AS date, TIME_FORMAT(time, '%H:%i') AS time", false);
		$this->db->where("slug", $slug);
		$this->db->where("date", $date);
		$this->db->where("deleted", 0);
		$query = $this->db->get("socials");
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
  
  public function get_edit_social($slug, $date)
	{
		$this->db->select("title, location, facebook, map, email, cancelled, contact, email, image,
		                   DATE_FORMAT(date, '%d %m %Y') AS date, TIME_FORMAT(time, '%H:%i') AS time,
                       DATE_FORMAT(date, '%D %M %Y') AS fdate", false);
		$this->db->where("slug", $slug);
		$this->db->where("date", $date);
		$query = $this->db->get("socials");
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
	
	public function get_upcoming_socials($limit = 3)
	{
		$date = date("Y-m-d");
		$this->db->select("title, location, slug, facebook, email, image, cancelled,
		                   DATE_FORMAT(date, '%Y%m%d') AS testdate, DATE_FORMAT(date, '%D %M') AS date, 
		                   DATE_FORMAT(date, '%d-%m-%Y') AS slug_date, TIME_FORMAT(time, '%H:%i') AS time", false);
		$this->db->where("date >=", $date)->where("deleted", 0)->order_by("testdate", "asc")->limit($limit);
		$query = $this->db->get("socials");
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
  
  public function get_archive()
  {
    $query = $this->db->select('DISTINCT CASE WHEN DATE_FORMAT(date, "%c") < 9 
                                              THEN DATE_FORMAT(date, "%Y") - 1
                                              ELSE DATE_FORMAT(date, "%Y")
                                         END as year', false)
                      ->where('deleted', 0)
                      ->order_by('year','asc')
                      ->get('socials');
    return $query->result_array();
  }
  
  private function parse_social_data()
  {
    $slug = url_title($this->input->post('title'), 'dash', true);
    
    $date_parts = split(" ", $this->input->post('date'));
    $date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
    
    $time = ($this->input->post('time') == null ? '00:00:00:' : $this->input->post('time').':00');
    $location = ($this->input->post('location') == null ? null : $this->input->post('location'));
    $image = ($this->input->post('image') == 'none' ? null : $this->input->post('image'));
    $map = ($this->input->post('map') == null ? null : $this->input->post('map'));
    $email = ($this->input->post('email') ==  null  ? null : $this->input->post('email'));
    if ($this->input->post('facebook') == null || $this->input->post('facebook') == 'http://')
      $facebook = null;
    else
      $facebook = $this->input->post('facebook');
    
    $this->db->set(array('title'    => $this->input->post('title'),
                         'slug'     => $slug,
                         'date'     => $date,
                         'time'     => $time,
                         'location' => $location,
                         'image'    => $image,
                         'email'    => $email,
                         'map'      => $map,
                         'facebook' => $facebook));
  }
  
  public function insert()
  {
    $this->parse_social_data();
    $this->db->set('created', date('Y-m-d H:i:s'));
    $this->db->insert('socials');
    
    if ($this->input->post('options') == 'add_another')
      return 'new';
    else
      if (substr($this->input->post('date'),3,2) >= 9)
        return substr($this->input->post('date'),6,4);
      else
        return substr($this->input->post('date'),6,4) - 1;
  }
  
  public function update($title, $date)
  {
    $this->parse_social_data();
    $this->db->where('slug', $title)->where('date', $date)->update('socials');
    
    if ($this->input->post('options') == 'add_another')
      return null;
    else
      if (substr($this->input->post('date'),3,2) >= 9)
        return substr($this->input->post('date'),6,4);
      else
        return substr($this->input->post('date'),6,4) - 1;
  }
  
  public function delete($title, $date)
  {
    $this->db->update('socials', array('deleted' => 1), array('slug' => $title, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
  
  public function restore($title, $date)
  {
    $this->db->update('socials', array('deleted' => 0), array('slug' => $title, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
  
  public function cancel($title, $date)
  {
    $this->db->update('socials', array('cancelled' => 1), array('slug' => $title, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
  
  public function uncancel($title, $date)
  {
    $this->db->update('socials', array('cancelled' => 0), array('slug' => $title, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
		
}