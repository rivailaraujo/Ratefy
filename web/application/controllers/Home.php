<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
	}
	
	public function index()
    {
        if ($this->session->userdata("user_id")) {
            $data = array(
                "scripts" => array(
                    "util.js",
                    "restrict.js",
                )
			);
			$this->template->show("home.php", $data);
        } else {
            $data = array(
                "scripts" => array(
					"owl.carousel.min.js",
					"theme-scripts.js",
					"cadastro.js",
					"util.js"
				),
			);
			$this->template->show("home.php", $data);
            //echo password_hash("admin", PASSWORD_DEFAULT);

            //$this->load->model("users_model");
            //print_r($this->users_model->get_user_data("admin"));
        }
    }

}
