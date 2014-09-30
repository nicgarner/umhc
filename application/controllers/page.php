<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
  
	public function __construct()
  {
    parent::__construct();
    $this->load->model("page_model");
    $this->load->model("hike_model");
    $this->load->model("social_model");
    $this->load->model("news_model");
    $this->load->model("announcement_model");
    session_start();
  }
	
	public function index()
	{
		$this->read();
	}
  
  public function homepage_qr_redirect()
	{
		redirect();
	}
	
	public function read($slug = null)
	{
    $this->load->helper('slideshow');
		$page = $this->page_model->get_page($slug);
		if (empty($page))
		  show_404();
		if ($page["title"] == "Home")
		{
			$page["tiles"] = $this->page_model->get_tiles();
			$upcoming_hikes["hikes"] = $this->hike_model->get_upcoming_hikes(2);
			$upcoming_socials["socials"] = $this->social_model->get_upcoming_socials(2);
			// $twitter["news"] = $this->news_model->get_latest_news(3);    # news has been replaced by Twitter
			$page["tiles"][1]["extra"] = $this->load->view('hike/upcoming', $upcoming_hikes, true);
			$page["tiles"][2]["extra"] = $this->load->view('social/upcoming', $upcoming_socials, true);
			$page["tiles"][3]["extra"] = $this->load->view('news/twitter', null, true);
      $page['banner'] = getSlideshow('assets/images/slideshow/', 6000);
		}
		else
      $page["banner"] = getRandomImage("assets/images/slideshow/");
    
		$announcements["announcements"] = $this->announcement_model->get_announcements();
		$data["announcements"] = $this->load->view('announcements/view', $announcements, true);
		$data["contents"] = $this->load->view('page/view', $page, true);
		$data["title"] = $page["title"];
		if ($page["parent"])
		{
			$parent = $this->page_model->get_page($page["parent"]);
		  $data["parent"] = $parent["title"];
		}
		else
		  $data["parent"] = null;
		
		$data["navigation_pages"] = $this->page_model->get_navigation_pages();
		
		$this->load->view('site', $data);
	}
	
  public function edit($slug = null)
  {
    if (!isset($_SESSION['username']))
    {
      redirect('/admin');
      exit();
    }
    
    $this->load->helper('form');
		$this->load->library('form_validation');
		
    if ($slug ==  null)
      show_404();
		
		$form_data['page'] = $this->page_model->get_page($slug);
		if (empty($form_data['page']))
			show_404();
		
		$form_data['title'] = 'Edit page: '.$form_data['page']['title'];
		$form_data['submit'] = 'Save changes';
		$form_data['form'] = 'edit/'.$slug;
    
		$this->form_validation->set_rules("content", "<strong>Content</strong>", "required");
		
    $data['title'] = $form_data['title'];
		
    if ($form_data['page']['parent'] == NULL)
      $data['parent'] = $form_data['page']['title'];
    else
    {
      $parent = $this->page_model->get_page($form_data['page']['parent']);
		  $data['parent'] = $parent['title'];
    }
    
		$data['navigation_pages'] = $this->page_model->get_navigation_pages();
    
		if ($this->form_validation->run() === false)
    {
      $data['contents'] = $this->load->view('page/edit', $form_data, true);
      $this->load->view("site", $data);
    }
		else
		{
			$slug = $this->page_model->update($slug);
      redirect($slug);
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */