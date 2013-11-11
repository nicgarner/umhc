<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('page_model');
    $this->load->model('admin_model');
    $this->load->model('user_model');
    session_start();
  }
  
  public function index()
  {
    if (!isset($_SESSION['username']))
      $this->login();
    else
    {
      $data['title'] = 'Admin';
      $data['parent'] = 'Home';
      $data['navigation_pages'] = $this->page_model->get_navigation_pages();
      $data['contents'] = $this->load->view('admin/index', '', true);
      $this->load->view('site', $data);
    }
  }
  
  public function login()
  {
    $this->load->helper("form");
		$this->load->library('form_validation');
    
    $this->form_validation->set_rules('username', 'username', 'required');
    $this->form_validation->set_rules('password', 'password', 'required|callback_verify_user');
    
		$data['title'] = 'Log in';
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
    if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('admin/login', '', true);
      $this->load->view('site', $data);
    }
		else
		{
      $user = $this->user_model->get_user($this->input->post('username'));
      $_SESSION['username'] = $this->input->post('username');
      $_SESSION['role'] = $user['role'];
      redirect('admin');
		}
  }
   
  public function verify_user($password)
	{
		$user = $this->admin_model->verify_user($this->input->post("username"), $password);
		if($user)
			return TRUE;
		else
		{
      $this->form_validation->set_message("verify_user", "Your login details are incorrect.");
			return FALSE;
		}
	}
  
  public function logout()
  {
    session_destroy();
    redirect('');
  }
   
}