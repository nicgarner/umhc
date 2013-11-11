<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hike extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
  {
    parent::__construct();
    $this->load->model("hike_model");
		$this->load->model("page_model");
		$this->load->model("announcement_model");
    session_start();
  }
	
	public function index($year = null)
	{
		if (date("n") >= 9)
			$current_year = date("Y");
		else
			$current_year = date("Y") - 1;
		
		if($year == null || $year == $current_year)
		{
			$page_data = $this->page_model->get_page("hikes");
      $hikes_data['page'] = $this->load->view('page/view', $page_data, true);
			$page_title = "Hikes programme";
		}
		else
		{
		  $hikes_data['page'] = array('content'=>$year);
			$page_title = 'Hikes programme ' . $year . '/' . ($year+1);
		}
    
    $hikes_data['archive'] = $this->hike_model->get_archive();
		$hikes_data['hikes'] = $this->hike_model->get_hikes($year);
		
		$announcements['announcements'] = $this->announcement_model->get_announcements();
		$data['announcements'] = $this->load->view('announcements/view', $announcements, true);
		$data['contents'] = $this->load->view('hike/index', $hikes_data, true);
		$data['title'] = $page_title;
		$data['parent'] = 'Programme';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    $this->load->helper('slideshow');
    $data['banner'] = getRandomImage('assets/images/slideshow/');
		
		$this->load->view('site', $data);
	}
	
  private function explode_slug($slug = null)
  {
    if ($slug == null)
		  return false;
		$slug_parts = explode("_", $slug);
		if (count($slug_parts) != 2)
			return false;
		
		$location = $slug_parts[0];
		$date_parts = explode("-", $slug_parts[1]);
		if (count($date_parts) != 3)
			return false;
		$date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];
    
    return array('date' => $date, 'location' => $location);
  }
  
  
	public function view($slug = null)
	{
		$slug = $this->explode_slug($slug);
    if ($slug == false)
      show_404();
		
		$hike_data = $this->hike_model->get_hike($slug['location'], $slug['date']);
		if (empty($hike_data))
			show_404();
		
		$announcements["announcements"] = $this->announcement_model->get_announcements();
		$data["announcements"] = $this->load->view('announcements/view', $announcements, true);
    $data["contents"] = $this->load->view("hike/view", $hike_data, true);
		$data["title"] = $hike_data["location"] . ", " . $hike_data["date"];
		$data["parent"] = "Programme";
		$data["navigation_pages"] = $this->page_model->get_navigation_pages();
    $this->load->helper('slideshow');
    $data["banner"] = getRandomImage("assets/images/slideshow/");
		
		$this->load->view('site', $data);
	}

  public function latest()
  {
    $hike = $this->hike_model->get_latest_hike_with_email();
    if ($hike)
      redirect('hikes/' . $hike['slug'] . '_' . $hike['slug_date']);
    else
      redirect('hikes');
  }
  
  public function create()
	{
		$this->load->helper("form");
		$this->load->library('form_validation');
		
		$form_data['title'] = 'Create new hike';
		$form_data['submit'] = 'Create hike';
		$form_data['form'] = 'new';
    
		# build array of options for "type" menu
		$types_data = $this->hike_model->get_types();
		$types = array();
		foreach ($types_data as $type)
		  $types[$type['id']] = $type['name'];
		$form_data['types'] = $types;

    # build list of options for "image" list
    $this->load->helper('slideshow');
    $form_data['images'] = getImagesList('assets/images/hikes/');
		
		$this->form_validation->set_rules("location", "<strong>Location</strong>", "required");
    $this->form_validation->set_rules("area", "<strong>Area</strong>", "required");
    $this->form_validation->set_rules("date", "<strong>Date</strong>", "required|callback_check_date");
    $this->form_validation->set_rules("end_date", "<strong>End date</strong>", "callback_check_date");
    
    
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Programme';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('hike/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$year = $this->hike_model->insert();
      if ($year == null)
        redirect('hikes/new');
      else
        redirect("hikes/$year");
		}
	}
  
  public function edit($slug = null)
  {
    $this->load->helper('form');
		$this->load->library('form_validation');
		
    $slug = $this->explode_slug($slug);
    if ($slug == false)
      show_404();
		
		$form_data['hike'] = $this->hike_model->get_edit_hike($slug['location'], $slug['date']);
		if (empty($form_data['hike']))
			show_404();
		
		$form_data['title'] = 'Edit hike: '.$form_data['hike']['location'].', '.$form_data['hike']['fdate'];
		$form_data['submit'] = 'Save changes';
		$form_data['form'] = 'edit/'.$slug['location'].'_'.$slug['date'];
    
		# build array of options for "type" menu
		$types_data = $this->hike_model->get_types();
		$types = array();
		foreach ($types_data as $type)
		  $types[$type['id']] = $type['name'];
		$form_data['types'] = $types;

    # build list of options for "image" list
    $this->load->helper('slideshow');
    $form_data['images'] = getImagesList('assets/images/hikes/');
		
		$this->form_validation->set_rules("location", "<strong>Location</strong>", "required");
    $this->form_validation->set_rules("area", "<strong>Area</strong>", "required");
    $this->form_validation->set_rules("date", "<strong>Date</strong>", "required|callback_check_date");
    $this->form_validation->set_rules("end_date", "<strong>End date</strong>", "callback_check_date");
		
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Programme';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('hike/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$year = $this->hike_model->update($slug['location'], $slug['date']);
      if ($year == null)
        redirect('hikes/new');
      else
        redirect("hikes/$year");
		}
	}
  
  public function check_date($date)
	{
		if ($date == null)
      return true;
    $date = explode(" ", $date);
    if (count($date) == 3)
    {
      $day = $date[0]; $month = $date[1]; $year = $date[2];
      if (is_numeric($month) && is_numeric($day) && is_numeric($year))
        if (checkdate($month, $day, $year))
          return true;
        else
        {
          $this->form_validation->set_message('check_date', 'The %s field does not contain a valid date.');
          return false;
        }
      else
      {
        $this->form_validation->set_message('check_date', 'The %s field does not contain a valid date.');
        return false;
      }
    }
    else
    {
      $this->form_validation->set_message('check_date', 'The %s field does not contain a valid date.');
      return false;
    }
	}
  
  public function delete($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->hike_model->delete($slug['location'], $slug['date']);
    redirect("hikes/$year");
  }
  
  public function restore($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->hike_model->restore($slug['location'], $slug['date']);
    redirect("hikes/$year");
  }
  
  public function cancel($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->hike_model->cancel($slug['location'], $slug['date']);
    redirect("hikes/$year");
  }
  
  public function uncancel($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->hike_model->uncancel($slug['location'], $slug['date']);
    redirect("hikes/$year");
  }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */