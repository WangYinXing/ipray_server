<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminLogin extends Home_Controller {

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
		$this->load->model('Mdl_AdminUsers', '', TRUE);
	}

	public function index() {
	    if( $this->session->userdata('isLoggedIn') ) {
	        redirect('/dashboard');
	    } else {
	        $this->show_login(false);
	    }
	}

	function show_login( $show_error = false ) {
	    $data['error'] = $show_error;

	    $this->load->helper('form');
	    $this->load->view('adminlogin',$data);
	}

	public function login_user() {
		// Create an instance of the user model
		//$this->load->model('AdminUsers');

		// Grab the email and password from the form POST
		$username = $this->input->post('username');
		$pass  = $this->input->post('password');

		//Ensure values exist for email and pass, and validate the user's credentials
		if( $username && $pass && $this->Mdl_AdminUsers->login($username, $pass)) {
		  // If the user is valid, redirect to the main view
		  redirect('/dashboard');
		} else {
		  // Otherwise show the login screen with an error message.
		  $this->show_login(true);
		}
	}

	public function logout() {
		$this->Mdl_AdminUsers->destroy_session();

	    $this->show_login(false);
	}
}
