<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
  {
    parent::__construct();
    $this->load->model("user_model");
		$this->load->model("page_model");
		session_start();
  }
	
	public function index()
	{
    if (!isset($_SESSION['username'])) {
      redirect('/admin');
      exit();
    }
		$users_data['users'] = $this->user_model->get_users();
		$data['contents'] = $this->load->view('user/index', $users_data, true);
		$data['title'] = 'Users';
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    $this->load->view('site', $data);
	}
  
  public function create()
	{
    if (!isset($_SESSION['username'])) {
      redirect('/admin');
      exit();
    }
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$form_data['title'] = 'Create new user';
		$form_data['submit'] = 'Create user';
		$form_data['form'] = 'new';
		
		$this->form_validation->set_rules("username", "<strong>Username</strong>", "trim|required|xss_clean|callback_username|is_unique[users.username]");
		$this->form_validation->set_rules("email_address", "<strong>Email address</strong>", "trim|required|xss_clean|valid_email");
    $this->form_validation->set_rules("first_name", "<strong>First name</strong>", "trim|required|xss_clean");
    $this->form_validation->set_rules("last_name", "<strong>Last name</strong>", "trim|required|xss_clean");
    $this->form_validation->set_rules("password", "<strong>Password</strong>", "trim|required|xss_clean");
    $this->form_validation->set_rules("confirm_password", "<strong>Confirm password</strong>", "trim|required|xss_clean|callback_password");
    
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('user/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$this->user_model->insert();
      redirect('/users');
		}
	}
  
  public function edit($username = null)
  {
    if (!isset($_SESSION['username'])) {
      redirect('/admin');
      exit();
    }
		
    if ($username == false)
      show_404();
    if ($username != $_SESSION['username'] && $_SESSION['role'] != 1)
      show_404();
      
    $this->load->helper('form');
		$this->load->library('form_validation');
		
		$form_data['user'] = $this->user_model->get_user($username);
		if (empty($form_data['user']) || !$form_data['user']['active'])
			show_404();
		
		$form_data['title'] = 'Edit user: '.$form_data['user']['username'];
		$form_data['submit'] = 'Save changes';
		$form_data['form'] = 'edit/'.$form_data['user']['username'];
    
		$this->form_validation->set_rules("email_address", "<strong>Email address</strong>", "trim|required|xss_clean|valid_email");
    $this->form_validation->set_rules("first_name", "<strong>First name</strong>", "trim|required|xss_clean");
    $this->form_validation->set_rules("last_name", "<strong>Last name</strong>", "trim|required|xss_clean");
    $this->form_validation->set_rules("password", "<strong>Password</strong>", "trim|xss_clean");
    $this->form_validation->set_rules("confirm_password", "<strong>Confirm password</strong>", "trim|xss_clean|callback_password");
		
    $data['title'] = $form_data['title'];
		$data['parent'] = 'Home';
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('user/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$this->user_model->update($form_data['user']['username']);
      redirect('/users');
		}
	}
  
  function username($str) {
    if (! preg_match("/^([a-zA-Z0-9_-])+$/i", $str)) {
      $this->form_validation->set_message('username', '%s may only contain letters, numbers, underscores and hyphens.');
      return false;
    }
    else
      return true;
  }
  
  function password($confirm) {
    if ($this->input->post('password') == $confirm)
      return true;
    else {
      $this->form_validation->set_message('password', 'Passwords do not match.');
      return false;
    }
  }
  
  public function activate($id = null)
  {
    if (!isset($_SESSION['username'])) {
      redirect('/admin');
      exit();
    }
    $this->user_model->activate($id);
    redirect("users");
  }
  
  public function deactivate($id = null)
  {
    if (!isset($_SESSION['username'])) {
      redirect('/admin');
      exit();
    }
    $this->user_model->deactivate($id);
    redirect("users");
  }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */