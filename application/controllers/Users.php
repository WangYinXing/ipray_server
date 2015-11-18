<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Home_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_Users', '', TRUE);
	}

	public function index() {
		parent::initView('users', 'Manage iprayees for CRUDing');

		parent::loadView();
	}

/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/

	/*--------------------------------------------------------------------------------------------------------
	User list for admin panel...
	_________________________________________________________________________________________________________*/
	public function api_entry_get_userlist() {
		$data = $this->Mdl_Users->get_userlist(
			$_POST['rp'],
			$_POST['page'],
			$_POST['query'],
			$_POST['qtype'],
			$_POST['sortname'],
			$_POST['sortorder']);

		echo json_encode(array(
			'page'=>$_POST['page'],
			'total'=>$this->Mdl_Users->get_usercnt(),
			'rows'=>$data,
		));
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign up...
	_________________________________________________________________________________________________________*/
	public function api_entry_signup_user() {
		$this->Mdl_Users->signup_user();

		/*
			Now we should register qb user at first.....
		*/
		
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign in...
	_________________________________________________________________________________________________________*/
	public function api_entry_signin_user() {
		$this->Mdl_Users->signin_user();
	}
}

?>