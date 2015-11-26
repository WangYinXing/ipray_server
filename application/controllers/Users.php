<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Api_Unit {

	function __construct(){
		parent::__construct();

		$this->load->model('Mdl_Users', '', TRUE);
		$this->load->library('Qbhelper');
	}

	public function index() {
		parent::initView('users', 'Manage iprayees for CRUDing',
			array()
		);

		parent::loadView();
	}

/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/

	/*--------------------------------------------------------------------------------------------------------
	User list for admin panel...
	_________________________________________________________________________________________________________*/
	public function api_entry_list() {
		$data = $this->Mdl_Users->get_list(
			$_POST['rp'],
			$_POST['page'],
			$_POST['query'],
			$_POST['qtype'],
			$_POST['sortname'],
			$_POST['sortorder']);

		echo json_encode(array(
			'page'=>$_POST['page'],
			'total'=>$this->Mdl_Users->get_length(),
			'rows'=>$data,
		));
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign up...
	_________________________________________________________________________________________________________*/
	public function api_entry_signup() {
		parent::validateParams(array("username", "email", "password"));

		$qbToken = $this->qbhelper->generateSession();

		if ($qbToken == null || $qbToken == "")							parent::returnWithErr("Generating QB session has been failed.");

		$qbSession = $this->qbhelper->signupUser(
			$qbToken,
			$_POST['username'],
			$_POST['email'],
			md5($_POST['password'])
		);

		/*

		*/
		if ($qbSession == null)
			parent::returnWithErr($this->qbhelper->latestErr);

		$newUser = $this->Mdl_Users->signup(
			$_POST['username'],
			$_POST['email'],
			md5($_POST['password']),
			$qbSession
		);

		if ($newUser == null) {
			parent::returnWithErr($this->qbhelper->latestErr);
		}

		/*
			Now we should register qb user at first.....
		*/
		parent::returnWithoutErr("User has been created successfully.", $newUser);
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign in...
	_________________________________________________________________________________________________________*/
	public function api_entry_signin() {
		$user = $this->Mdl_Users->signin_user($_POST['email'], md5($_POST['password']));

		if ($user == null) {
			parent::returnWithErr("Login detail incorrect.");
		}

		$qbToken = $this->qbhelper->generateSession();

		if ($qbToken == null || $qbToken == "")
			parent::returnWithErr("Generating QB session has been failed.");


		$qbUser = $this->qbhelper->signinUser(
			$qbToken,
			$_POST['email'],
			md5($_POST['password'])
		);

		if ($qbUser == null)
			parent::returnWithErr($this->qbhelper->latestErr);

		$qbUser->token = $qbToken;

		$user = $this->Mdl_Users->signin_user($_POST['email'], md5($_POST['password']), $qbUser);

		if ($user == null)
			parent::returnWithErr("Login failed. QB signin failed.");


		parent::returnWithoutErr("Login succeed.", $user);
	}
}

?>