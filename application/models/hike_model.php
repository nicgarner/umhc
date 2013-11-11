<?php
class Hike_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->helper("url");
	}
	
	public function get_hikes($year = null)
	{
		if ($year == null)
			if (date("n") >= 9)
			  $year = date("Y");
			elseif (date("n") == 8 && date("d") >= 25)
			  $year = date("Y");
			else
			  $year = date("Y") - 1;
		$next_year = $year+1;
		
		$this->db->select("DATE_FORMAT(date, '%Y%m%d') AS testdate, DATE_FORMAT(date, '%D %M') AS date, slug, 
		                   DATE_FORMAT(end_date, '%D %M') AS end_date, DATE_FORMAT(date, '%d-%m-%Y') AS slug_date,  
											 (SELECT name FROM hike_types WHERE hike_types.id = hikes.type) as type, location, area, 
											 notes, email, image, cancelled, deleted", false);
		$this->db->where("date >=", "$year-09-14");
		$this->db->where("date <=", "$next_year-09-15");
		
		if (!isset($_SESSION['username']))
		  $this->db->where("deleted", 0);
			
		if (isset($_SESSION['username']))
		  $this->db->order_by("deleted", "asc");
      
		$this->db->order_by("testdate", "asc");
			$query = $this->db->get("hikes");
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
	
	public function get_hike($location, $date)
	{
		$this->db->select("DATE_FORMAT(date, '%D %M %Y') AS date, DATE_FORMAT(end_date, '%D %M %Y') AS end_date,  
											 location, area, notes, (SELECT name FROM hike_types WHERE hike_types.id = hikes.type) as type,
											 email, image, cancelled", false);
		$this->db->where("slug", $location);
		$this->db->where("date", $date);
		$this->db->where("deleted", 0);
		$query = $this->db->get("hikes");
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
  
  public function get_edit_hike($location, $date)
	{
		$this->db->select("DATE_FORMAT(date, '%d %m %Y') AS date, DATE_FORMAT(end_date, '%d %m %Y') AS end_date,  
											 DATE_FORMAT(date, '%D %M %Y') AS fdate, location, area, notes, type, email, image", false);
		$this->db->where('slug', $location);
		$this->db->where('date', $date);
		$query = $this->db->get('hikes');
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
	
	public function get_upcoming_hikes($limit = 3)
	{
		$date = date("Y-m-d");
		$this->db->select("DATE_FORMAT(date, '%Y%m%d') AS testdate, DATE_FORMAT(date, '%D %M') AS date, 
		                   DATE_FORMAT(end_date, '%D %M') AS end_date,  DATE_FORMAT(date, '%d-%m-%Y') AS slug_date, 
											 location, area, notes, email, image, cancelled, slug,
											 (SELECT name FROM hike_types WHERE hike_types.id = hikes.type) as type", false);
		$this->db->where("date >=", $date)->where("deleted", 0)->order_by("testdate", "asc")->limit($limit);
		$query = $this->db->get("hikes");
		
		if (empty($query))
		  return false;
		else
		  return $query->result_array();
	}
  
  public function get_latest_hike_with_email()
  {
		$date = date("Y-m-d");
		$this->db->select("DATE_FORMAT(date, '%d-%m-%Y') AS slug_date, slug", false)
             ->where('date >=', $date)
             ->where('deleted', 0)
             ->where('cancelled', 0)
             ->where('email !=', 'null')
             ->order_by('date', 'desc')
             ->limit(1);
		$query = $this->db->get('hikes');
		
		if (empty($query))
		  return false;
		else
		  return $query->row_array();
	}
  
  public function get_archive()
  {
    $query = $this->db->select('DISTINCT CASE WHEN DATE_FORMAT(date, "%c") < 9 
                                              THEN DATE_FORMAT(date, "%Y") - 1
                                              ELSE DATE_FORMAT(date, "%Y")
                                         END as year', false)
                      ->where('deleted', 0)
                      ->order_by('year','asc')
                      ->get('hikes');
    return $query->result_array();
  }
  
  public function get_types()
  {
    $query = $this->db->where('deleted', 0)->order_by('weight', 'asc')->get('hike_types');
    return $query->result_array();
  }
  
  private function parse_hike_data()
  {
    $slug = url_title($this->input->post('location'), 'dash', true);
    
    $date_parts = split(" ", $this->input->post('date'));
    $date = $date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0];
    
    if ($this->input->post('end_date') != null)
    {
      $end_date_parts = split(" ", $this->input->post('end_date'));
      $end_date = $end_date_parts[2].'-'.$end_date_parts[1].'-'.$end_date_parts[0];
    }
    else
      $end_date = null;
    
    $image = ($this->input->post('image') == 'none' ? null : $image = $this->input->post('image'));
    $notes = ($this->input->post('notes') ==  null  ? null : $this->input->post('notes'));
    $email = ($this->input->post('email') ==  null  ? null : $this->input->post('email'));
    
    $this->db->set(array('date'     => $date,
                         'end_date' => $end_date,
                         'location' => $this->input->post('location'),
                         'slug'     => $slug,
                         'area'     => $this->input->post('area'),
                         'notes'    => $notes,
                         'type'     => $this->input->post('type'),
                         'image'    => $image,
                         'email'    => $email));
  }
  
  public function insert()
  {
    $this->parse_hike_data();
    $this->db->set('created', date('Y-m-d H:i:s'));
    $this->db->insert('hikes');
    
    if ($this->input->post('options') == 'add_another')
      return null;
    else
      if (substr($this->input->post('date'),3,2) > 9)
        return substr($this->input->post('date'),6,4);
	  else if (substr($this->input->post('date'),3,2) == 9 && substr($this->input->post('date'),0,2) >= 15)
	    return substr($this->input->post('date'),6,4);
      else
        return substr($this->input->post('date'),6,4) - 1;
  }
  
  public function update($location, $date)
  {
    $this->parse_hike_data();
    $this->db->where('slug', $location)->where('date', $date)->update('hikes');
    
    if ($this->input->post('options') == 'add_another')
      return null;
    else
      if (substr($this->input->post('date'),3,2) > 9)
        return substr($this->input->post('date'),6,4);
	  else if (substr($this->input->post('date'),3,2) == 9 && substr($this->input->post('date'),0,2) >= 15)
	    return substr($this->input->post('date'),6,4);
      else
        return substr($this->input->post('date'),6,4) - 1;
  }
  
  public function delete($location, $date)
  {
    $this->db->update('hikes', array('deleted' => 1), array('slug' => $location, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
  
  public function restore($location, $date)
  {
    $this->db->update('hikes', array('deleted' => 0), array('slug' => $location, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
  
  public function cancel($location, $date)
  {
    $this->db->update('hikes', array('cancelled' => 1), array('slug' => $location, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
  
  public function uncancel($location, $date)
  {
    $this->db->update('hikes', array('cancelled' => 0), array('slug' => $location, 'date' => $date));
    
    if (substr($date,5,2) >= 9)
      return substr($date,0,4);
    else
      return substr($date,0,4) - 1;
  }
		
}