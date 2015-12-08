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
		parent::validateParams(array("username", "email", "password", "fullname", "church", "province", "city", "bday"));

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
			$_POST['fullname'],
			$_POST['church'],
			$_POST['province'],
			$_POST['city'],
			$_POST['bday'],
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
		parent::validateParams(array('qbid', 'token'));

		$users = $this->Mdl_Users->getAll("qbid", $_POST["qbid"]);

		if (count($users) == 0)
			parent::returnWithErr("QBID is not valid. maybe not found corresponding user from qbid.");
		
		$user = $this->Mdl_Users->signin($_POST["qbid"], $_POST["token"]);

		parent::returnWithoutErr("Signin succeed.", $user);
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign out...
	_________________________________________________________________________________________________________*/
	public function api_entry_signout() {
		parent::validateParams(array('user'));

		if (!$this->Mdl_Users->get($_POST["user"]))	parent::returnWithErr("User id is not valid.");
		
		$this->Mdl_Users->signout($_POST["user"]);

		//if ($user == null)			parent::returnWithErr("Invalidation token failed.");

		parent::returnWithoutErr("Signout succeed.");
	}

	/*--------------------------------------------------------------------------------------------------------
		Submit device token, udid
	_________________________________________________________________________________________________________*/
	public function api_entry_subscribeAPN() {
		parent::validateParams(array('user', 'udid', 'devicetoken'));

		$users = $this->Mdl_Users->get($_POST["user"]);

		if (!$this->Mdl_Users->get($_POST["user"]))			parent::returnWithErr("User id is not valid.");

		$user = $this->Mdl_Users->update(array(
			'id' => $_POST["user"],
			'udid' => $_POST["udid"],
			'devicetoken' => $_POST["devicetoken"]
			));

		parent::returnWithoutErr("Updated APN info successfully.", $user);
	}


	/*--------------------------------------------------------------------------------------------------------
		Get profile ..
	_________________________________________________________________________________________________________*/
	public function api_entry_getprofilefromqbid() {
		parent::validateParams(array('qbid'));

		$user = $this->Mdl_Users->getAll("qbid", $_POST["qbid"]);

		if (count($user) == 0  || $user[0] == null)
			parent::returnWithErr("QBID is not valid.");

		unset($user[0]->password);

		parent::returnWithoutErr("User profile fetched successfully.", $user[0]);
	}

	/*--------------------------------------------------------------------------------------------------------
		Get profile from qbid ..
	_________________________________________________________________________________________________________*/
	public function api_entry_getprofile() {
		parent::validateParams(array('user'));

		$user = $this->Mdl_Users->get($_POST["user"]);

		if ($user == null)
			parent::returnWithErr("User id is not valid.");

		unset($user->password);

		parent::returnWithoutErr("User profile fetched successfully.", $user);
	}

	/*--------------------------------------------------------------------------------------------------------
		Set profile ..
	_________________________________________________________________________________________________________*/
	public function api_entry_setprofile() {
		parent::validateParams(array('user'));

		$user = $this->Mdl_Users->get($_POST["user"]);

		if ($user == null)
			parent::returnWithErr("User id is not valid.");

		$arg = $this->safeArray(array('fullname', 'avatar', 'church', 'city', 'province', 'bday'), $_POST);

		$arg['id'] = $_POST["user"];

		if (count($arg) == 1)
			parent::returnWithErr("You should pass the profile 1 entry at least to update.");

		$user = $this->Mdl_Users->update($arg);

		if ($user == null)
			parent::returnWithErr("Profile has not been updated.");

		parent::returnWithoutErr("Profile has been updated successfully.", $user);
	}


	/*--------------------------------------------------------------------------------------------------------
		send contact request...
	_________________________________________________________________________________________________________*/
	public function api_entry_sendcontactrequest() {
		parent::validateParams(array('sender', 'receiver'));

		if(!$this->Mdl_Users->get($_POST['sender']))		parent::returnWithErr("Sender is not valid");
		if(!$this->Mdl_Users->get($_POST['receiver']))		parent::returnWithErr("Receiver is not valid");

		//$qbToken = $this->qbhelper->generateSession();

		//if ($qbToken == null || $qbToken == "")			parent::returnWithErr("Generating QB session has been failed.");

		$sender = $this->Mdl_Users->get($_POST['sender']);
		$receiver = $this->Mdl_Users->get($_POST['receiver']);

		//$deviceToken = '98c348ad62372c6460218cfa879b5359852c16ee9d78af979ed8058d5bcba65f';

		$this->qbhelper->sendPN($receiver->devicetoken, "You have been invited from " + $sender->username);

		//$qbToken = $this->qbhelper->sendPN($qbToken, $user->qbid);




		parent::returnWithoutErr("Contact request has been sent succeed.", $user);
	}
}

?>