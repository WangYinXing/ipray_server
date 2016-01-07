<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Home_Controller {

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


	function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Dashboard', '', TRUE);
		$this->load->model('Mdl_Users');
		$this->load->model('Mdl_Requests');
		$this->load->model('Mdl_Prays');
	}

	public function index() {
		/*
		$data = array("error"=>"Opps you can't login ipray admin at this moment. sorry.");
		$this->load->view('invalidtoken',$data);
		return;
		*/
		parent::initView('dashboard', 'dashboard', 'dashboard',
			array(
				'registered_users' => $this->Mdl_Users->get_length(),
				'online_users' => $this->Mdl_Users->online_usercnt(),
				'requests' => count($this->Mdl_Requests->getAll()),
				'prays' => count($this->Mdl_Prays->getAll()),
				)
			);

		parent::loadView();
	}
}
?>