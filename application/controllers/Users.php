<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Home_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Mdl_Users', '', TRUE);
	}

	public function index() {
		parent::initView('users', 'Manage iprayees for CRUDing');

		$data = $this->Mdl_Users->get_userlist();
		print_r( $data );

		parent::loadView();
	}

	public function api_entry_get_userlist() {
		$data = $this->Mdl_Users->get_userlist();
		

		echo json_encode(array(
			'page'=>1,
			'total'=>1,
			'rows'=>array(
				array(
				'username'=>'a',
				'email'=>'b',
				)
			)
		));
	}
}

?>