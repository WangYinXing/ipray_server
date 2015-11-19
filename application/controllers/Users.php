<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Home_Controller {

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
		$qbToken = $this->qbhelper->generateSession();

		if ($qbToken == null || $qbToken == "")
			exit($this->resphelper->makeResponseWithErr("Generating QB session has been failed."));

		$qbSession = $this->qbhelper->signupUser(
			$qbToken,
			$_POST['username'],
			$_POST['email'],
			md5($_POST['password'])
		);

		/*

		*/
		if ($qbSession == null) {
			exit($this->resphelper->makeResponseWithErr("QB user creation failed."));
		}

		$newUser = $this->Mdl_Users->signup_user(
			$_POST['username'],
			$_POST['email'],
			md5($_POST['password']),
			$qbSession
		);

		if ($newUser == null) {
			exit($this->resphelper->makeResponseWithErr($this->Mdl_Users->latestErr));
		}

		/*
			Now we should register qb user at first.....
		*/
		exit($this->resphelper->makeResponse($newUser, "User has been created successfully."));
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign in...
	_________________________________________________________________________________________________________*/
	public function api_entry_signin_user() {
		$user = $this->Mdl_Users->signin_user($_POST['email'], md5($_POST['password']));

		if ($user == null) {
			exit($this->resphelper->makeResponseWithErr("Login detail incorrect."));
		}

		$qbToken = $this->qbhelper->generateSession();

		if ($qbToken == null || $qbToken == "")
			exit($this->resphelper->makeResponseWithErr("Generating QB session has been failed."));


		$qbUser = $this->qbhelper->signinUser(
			$qbToken,
			$_POST['email'],
			md5($_POST['password'])
		);

		if ($qbUser == null) {
			exit($this->resphelper->makeResponseWithErr($this->qbhelper->latestErr));
		}

		$qbUser->token = $qbToken;

		$user = $this->Mdl_Users->signin_user($_POST['email'], md5($_POST['password']), $qbUser);

		if ($user == null) {
			exit($this->resphelper->makeResponseWithErr("Login failed. QB signin failed."));
		}

		exit($this->resphelper->makeResponse("Login succeed.", $user));
	}
}

?>