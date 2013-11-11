<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Announcement extends CI_Controller {

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
    $this->load->model("announcement_model");
		$this->load->model("page_model");
    session_start();
  }
	
	public function index($year = null)
	{
    if (!isset($_SESSION['username']))
    {
      redirect('/admin');
      exit();
    }
    $announcements_data['future'] = $this->announcement_model->get_future_announcements();
    $announcements_data['current'] = $this->announcement_model->get_announcements();
    $announcements_data['old'] = $this->announcement_model->get_old_announcements();
		
		$data['contents'] = $this->load->view('announcements/index', $announcements_data, true);
		$data['title'] = "Configure announcements";
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
		
		$this->load->view('site', $data);
	}
  
  public function create()
	{
    if (!isset($_SESSION['username']))
    {
      redirect('/admin');
      exit();
    }
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$form_data['title'] = 'Create new announcement';
		$form_data['submit'] = 'Create announcement';
		$form_data['form'] = 'new';
		
		$this->form_validation->set_rules('content', 'announcement',
                                      'required');
    $this->form_validation->set_rules('start_date', 'start date',
                                      'required|callback_check_date|callback_date_not_past');
    $this->form_validation->set_rules('end_date', 'end date',
                                      'required|callback_check_date|callback_date_not_past|callback_check_dates');
    
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('announcements/edit', $form_data, true);
      $this->load->view('site', $data);
    }
		else
		{
			$this->announcement_model->insert();
      redirect('/announcements');
		}
	}
  
  public function edit($id = null)
  {
    if (!isset($_SESSION['username']))
    {
      redirect('/admin');
      exit();
    }
    $this->load->helper('form');
		$this->load->library('form_validation');
    
    $form_data['announcement'] = $this->announcement_model->get_announcement($id);
		if (empty($form_data['announcement']))
			show_404();
		
		$form_data['title'] = 'Edit announcement';
		$form_data['submit'] = 'Save changes';
		$form_data['form'] = 'edit/'.$id;
		
		$this->form_validation->set_rules('content', 'announcement',
                                      'required');
    $this->form_validation->set_rules('start_date', 'start date',
                                      'required|callback_check_date|callback_edit_date_not_past['.$form_data['announcement']['start_date'].']');
    $this->form_validation->set_rules('end_date', 'end date',
                                      'required|callback_check_date|callback_date_not_past|callback_check_dates');
		
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('announcements/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$this->announcement_model->update($id);
      redirect('/announcements');
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
  
  public function check_dates($end_date)
  {
    $srt_date = $this->input->post('start_date');
    if ($srt_date == null || $end_date == null)
      return true;
    else
      if ($this->check_date($srt_date) && $this->check_date($end_date))
      {
        $srt_date = substr($srt_date,6,4) . substr($srt_date,3,2) . substr($srt_date,0,2);
        $end_date = substr($end_date,6,4) . substr($end_date,3,2) . substr($end_date,0,2);
        if ($end_date < $srt_date)
        {
          $this->form_validation->set_message('check_dates', 'The start date must be before the end date.');
          return false;
        }
        else
          return true;
      }
      else
        return true;
  }
  
  public function date_not_past($date)
  {
    if ($date == null)
      return true;
    else
      if ($this->check_date($date))
      {
        $date = substr($date,6,4) . substr($date,3,2) . substr($date,0,2);
        if ($date < date('Ymd'))
        {
          $this->form_validation->set_message('date_not_past', 'The %s must be after today.');
          return false;
        }
        else
          return true;
      }
      else
        return true;
  }
  
  public function edit_date_not_past($new_date, $original_date)
  {
    if ($new_date == null || $original_date == null)
      return true;
    else
      if ($this->check_date($new_date) && $this->check_date($original_date))
        if ($new_date != $original_date)
        {
          $this->form_validation->set_message('edit_date_not_past', 'The %s must be unchanged ('.$original_date.')  or after today.');
          return false;
        }
        else
          return true;
      else
        return true;
  }
        
  
  public function delete($id = null)
  {
    if (!isset($_SESSION['username']))
    {
      redirect('/admin');
      exit();
    }
    $this->announcement_model->delete($id);
    redirect("/announcements");
  }
  
  public function expire($id = null)
  {
    if (!isset($_SESSION['username']))
    {
      redirect('/admin');
      exit();
    }
    $this->announcement_model->expire($id);
    redirect("/announcements");
  }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */