<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailinglist extends CI_Controller {

	public function __construct()
  {
    parent::__construct();
		$this->load->model('page_model');
		$this->load->model('announcement_model');
    session_start();
  }
  
  public function index($action = null)
	{
    $this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
    
    $page_data = $this->page_model->get_page('mailing-list');
    $form_data['page'] = $this->load->view('page/view', $page_data, true);
    $form_data['action'] = $action;
    
    $announcements['announcements'] = $this->announcement_model->get_announcements();
    $this->load->helper('slideshow');
    $data['title'] = 'Mailing List';
		$data['parent'] = 'Mailing List';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    $data['banner'] = getRandomImage('assets/images/slideshow/');
		$data['announcements'] = $this->load->view('announcements/view', $announcements, true);
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('list/form', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$action = $this->input->post('action');
      if ($this->majordomo_mail($action, $this->input->post('email')) == false)
        $action = 'fail';
      redirect("mailing-list/$action");
		}
	}
  
  function majordomo_mail($action = 'subscribe', $email)
  {
    if ($email == null)
      return false;
    return mail("umhc_club-request@umhc.org.uk", $action, "umhc", "From: ".$email);
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
