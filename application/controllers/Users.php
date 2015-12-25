<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//require 'mailgun-php/vendor/autoload.php';
//use Mailgun\Mailgun;


class Users extends Api_User {

	function __construct(){
		parent::__construct();

		$this->load->helper('url');
	}

	public function index() {
		parent::initView('users', 'users', 'Manage iprayees for CRUDing',
			array()
		);

		parent::loadView();
	}

	public function edit($arg) {
		$user = $this->Mdl_Users->get($arg);

		parent::initView('user_edit', 'users', 'Edit iprayee information.',
			$user
		);

		parent::loadView();
	}

	public function save() {
		$id = $_POST["id"];
		unset($_POST["id"]);

		$_POST["suspended"] = ($_POST["suspended"] == "on") ? 1 : 0;


		$this->Mdl_Users->updateEx($id, $_POST);

		redirect('/Users/', 'refresh');
	}

	public function del($arg) {
		$this->Mdl_Users->remove($arg);

		redirect('/Users/', 'refresh');
	}
}

?>