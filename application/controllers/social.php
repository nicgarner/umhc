<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social extends CI_Controller {

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
    $this->load->model("social_model");
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
			$page_data = $this->page_model->get_page('socials');
      $socials_data['page'] = $this->load->view('page/view', $page_data, true);
			$page_title = "Socials programme";
		}
		else
		{
		  $socials_data["page"] = array("content"=>$year);
			$page_title = "Socials programme " . $year . "/" . ($year+1);
		}
		
    $socials_data['archive'] = $this->social_model->get_archive();
		$socials_data['socials'] = $this->social_model->get_socials($year);
		
		$announcements["announcements"] = $this->announcement_model->get_announcements();
		$data["announcements"] = $this->load->view('announcements/view', $announcements, true);
		$data["contents"] = $this->load->view("social/index", $socials_data, true);
		$data["title"] = $page_title;
		$data["parent"] = "Programme";
		$data["navigation_pages"] = $this->page_model->get_navigation_pages();
    $this->load->helper('slideshow');
    $data["banner"] = getRandomImage("assets/images/slideshow/");
		
		$this->load->view('site', $data);
	}
  
  private function explode_slug($slug = null)
  {
    if ($slug == null)
		  return false;
		$slug_parts = explode("_", $slug);
		if (count($slug_parts) != 2)
			return false;
		
		$title = $slug_parts[0];
		$date_parts = explode("-", $slug_parts[1]);
		if (count($date_parts) != 3)
			return false;
		$date = $date_parts[2] . "-" . $date_parts[1] . "-" . $date_parts[0];
    
    return array('date' => $date, 'title' => $title);
  }
	
	public function view($slug = null)
	{
		$slug = $this->explode_slug($slug);
    if ($slug == null)
		  show_404();
		
		$social_data = $this->social_model->get_social($slug['title'], $slug['date']);
		if (empty($social_data))
			show_404();
		
		$announcements["announcements"] = $this->announcement_model->get_announcements();
		$data["announcements"] = $this->load->view('announcements/view', $announcements, true);
		$data["contents"] = $this->load->view("social/view", $social_data, true);
		$data["title"] = $social_data["title"] . ", " . $social_data["date"];
		$data["parent"] = "Programme";
		$data["navigation_pages"] = $this->page_model->get_navigation_pages();
    $this->load->helper('slideshow');
    $data["banner"] = getRandomImage("assets/images/slideshow/");
		
		$this->load->view('site', $data);
	}
  
  public function create()
	{
		$this->load->helper("form");
		$this->load->library('form_validation');
		
		$form_data['title'] = 'Create new social';
		$form_data['submit'] = 'Create social';
		$form_data['form'] = 'new';

    # build list of options for "image" list
    $this->load->helper('slideshow');
    $form_data['images'] = getImagesList('assets/images/socials/');
		
		$this->form_validation->set_rules("title", "<strong>Title</strong>", "required");
    $this->form_validation->set_rules("date", "<strong>Date</strong>", "required|callback_check_date");
    $this->form_validation->set_rules("time", "<strong>Time</strong>", "callback_check_time");
    
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Programme';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('social/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$year = $this->social_model->insert();
      redirect("socials/$year");
		}
	}
  
  public function edit($slug = null)
  {
    $this->load->helper('form');
		$this->load->library('form_validation');
    
    $slug = $this->explode_slug($slug);
    
    if ($slug == false)
      show_404();
		
    $form_data['social'] = $this->social_model->get_edit_social($slug['title'], $slug['date']);
		
    if (empty($form_data['social']))
			show_404();
    
		$form_data['title'] = 'Edit social: '.$form_data['social']['title'].', '.$form_data['social']['fdate'];
		$form_data['submit'] = 'Save changes';
		$form_data['form'] = 'edit/'.$slug['title'].'_'.$slug['date'];
    
		# build list of options for "image" list
    $this->load->helper('slideshow');
    $form_data['images'] = getImagesList('assets/images/socials/');
		
		$this->form_validation->set_rules("title", "<strong>Title</strong>", "required");
    $this->form_validation->set_rules("date", "<strong>Date</strong>", "required|callback_check_date");
    $this->form_validation->set_rules("time", "<strong>Time</strong>", "callback_check_time");
		
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Programme';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('social/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$year = $this->social_model->update($slug['title'], $slug['date']);
      redirect("socials/$year");
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
  
  public function check_time($time)
	{
		if ($time == null)
      return true;
    $time = explode(":", $time);
    if (count($time) == 2)
      if (is_numeric($time[0]) && is_numeric($time[1]))
        if ($time[0] < 0 || $time[0] > 23 || $time[1] < 0 || $time[1] > 59)
        {
          $this->form_validation->set_message('check_time', 'The %s field does not contain a valid time.');
          return false;
        }
        else
          return true;
      else
      {
        $this->form_validation->set_message('check_time', 'The %s field does not contain a valid time.');
        return false;
      }
    else
    {
      $this->form_validation->set_message('check_time', 'The %s field does not contain a valid time.');
      return false;
    }
	}
  
  public function delete($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->social_model->delete($slug['title'], $slug['date']);
    redirect("socials/$year");
  }
  
  public function restore($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->social_model->restore($slug['title'], $slug['date']);
    redirect("socials/$year");
  }
  
  public function cancel($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->social_model->cancel($slug['title'], $slug['date']);
    redirect("socials/$year");
  }
  
  public function uncancel($slug = null)
  {
    $slug = $this->explode_slug($slug);
    $year = $this->social_model->uncancel($slug['title'], $slug['date']);
    redirect("socials/$year");
  }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */