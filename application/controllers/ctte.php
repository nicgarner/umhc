<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ctte extends CI_Controller {

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
    $this->load->model("ctte_model");
		$this->load->model("page_model");
		$this->load->model("announcement_model");
    session_start();
  }
	
	public function index($year = null)
	{
		if($year === null)
			$max_ctte_year = $this->ctte_model->get_max_ctte_year();
		else
		  $max_ctte_year = 0;
			
		if($year == null || $year == $max_ctte_year)
		{
			$page_data = $this->page_model->get_page("committee");
      $ctte_data['page'] = $this->load->view('page/view', $page_data, true);
			$page_title = "Committee";
		}
		else
		{
		  $ctte_data["page"] = array("content"=>$year);
			$page_title = "Committee " . $year . "/" . ($year+1);
		}
		
		$ctte_data["ctte_members"] = $this->ctte_model->get_ctte($year);
    $ctte_data['archive'] = $this->ctte_model->get_archive();
		
		$announcements["announcements"] = $this->announcement_model->get_announcements();
		$data["announcements"] = $this->load->view('announcements/view', $announcements, true);
		$data["contents"] = $this->load->view("ctte/index", $ctte_data, true);
		$data["title"] = $page_title;
		$data["parent"] = "Info";
		$data["navigation_pages"] = $this->page_model->get_navigation_pages();
    $this->load->helper('slideshow');
    $data["banner"] = getRandomImage("assets/images/slideshow/");
		
		$this->load->view('site', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */