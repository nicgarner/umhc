<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

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
    $this->load->model("news_model");
		$this->load->model("page_model");
		$this->load->model("announcement_model");
    session_start();
  }
	
	public function index($page = 0)
	{
    $news_items_data = $this->news_model->get_news($page);
    $this->load->library('pagination');
    $config['base_url'] = base_url().'news';
    $config['total_rows'] = $news_items_data['number_of_news_items'];
    $config['per_page'] = 5;
    $config['uri_segment'] = 2;
    $config['num_links'] = 10;
    $config['cur_tag_open'] = '<div class="current">';
    $config['cur_tag_close'] = '</div>';
    $config['prev_tag_open'] = "";
    $config['prev_tag_close'] = "";
    $config['num_tag_open'] = "";
    $config['num_tag_close'] = "";
    $config['next_tag_open'] = "";
    $config['next_tag_close'] = "";
    
    $this->pagination->initialize($config); 
    
    $news_data["news"] = $news_items_data['news_items'];
    $news_data["links"] = $this->pagination->create_links();
    
    $page_data = $this->page_model->get_page("news");
    $news_data['page'] = $this->load->view('page/view', $page_data, true);
		
		$announcements["announcements"] = $this->announcement_model->get_announcements();
		$data["announcements"] = $this->load->view('announcements/view', $announcements, true);
		$data["contents"] = $this->load->view("news/index", $news_data, true);
		$data["title"] = "News";
		$data["parent"] = "";
		$data["navigation_pages"] = $this->page_model->get_navigation_pages();
    $this->load->helper('slideshow');
    $data["banner"] = getRandomImage("assets/images/slideshow/");
		
		$this->load->view('site', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */