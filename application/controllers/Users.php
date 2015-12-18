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
			$_POST['password']
		);

		/*

		*/
		if ($qbSession == null)
			parent::returnWithErr($this->qbhelper->latestErr);

		$newUser = $this->Mdl_Users->signup(
			$_POST['username'],
			$_POST['email'],
			$_POST['password'],
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
		Sign out...
	_________________________________________________________________________________________________________*/
	public function api_entry_forgotpassword() {
		parent::validateParams(array('user'));

		if (!($user = $this->Mdl_Users->get($_POST["user"])))	parent::returnWithErr("User id is not valid.");

		$hash = hash('tiger192,3', $user->username);


		$to = $user->email;
		$to  = 'wangyinxing19@gmail.com';


		$subject = 'Forget password for iPray.';

		$message = "
		<html>
		<head>
		  <title>You've forgot your password. but don't worry.</title>
		</head>
		<body>
		  <p>Your password will be reset by clicking this link.</p>
		  <a href='http://localhost/users/forgotpassword?hash=" . $hash . "'></a>
		</body>
		</html>
		";
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// 追加のヘッダ
		$headers .= 'To: Mary <wangyinxing19@gmail.com>' . "\r\n";
		$headers .= 'From: iPray <support@ipray1.com>' . "\r\n";

		if (mail($to, $subject, $message, $headers)) {
			parent::returnWithoutErr("Email is sent successfully.");
		}

		parent::returnWithErr("Email sending failed.");
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

		parent::returnWithoutErr("Subscription has been done successfully.", $user);
	}

	/*--------------------------------------------------------------------------------------------------------
		Make device token to void, udid
	_________________________________________________________________________________________________________*/
	public function api_entry_unsubscribeAPN() {
		parent::validateParams(array('user' ));

		$users = $this->Mdl_Users->get($_POST["user"]);

		if (!$this->Mdl_Users->get($_POST["user"]))			parent::returnWithErr("User id is not valid.");

		$user = $this->Mdl_Users->update(array(
			'id' => $_POST["user"],
			'udid' => '',
			'devicetoken' => ''
			));

		parent::returnWithoutErr("Unsubscription has been done successfully.", $user);
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

		$this->load->model('Mdl_Requests');
		$this->load->model('Mdl_Prays');

		$user->ipray_praying_for_me = 0;
		$user->ipray_i_am_praying_for = 0;
		$user->ipray_request_attended = 0;

		$prays = $this->Mdl_Prays->getAll();
		$requests = array();

		foreach ($prays as $key => $val) {
			$request = $this->Mdl_Requests->get($val->request);
			$prayer = $this->Mdl_Users->get($val->prayer);

			if ($_POST["user"] == $request->host) {
				if ($val->status == 1)	$user->ipray_request_attended++;
				$user->ipray_praying_for_me++;
			}
			if ($_POST["user"] == $val->prayer) {
				$user->ipray_i_am_praying_for++;
			}
		}

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

		$arg = $this->safeArray(array('fullname', 'avatar', 'church', 'city', 'province', 'bday', 'mood'), $_POST);

		$arg['id'] = $_POST["user"];

		if (count($arg) == 1)
			parent::returnWithErr("You should pass the profile 1 entry at least to update.");

		$user = $this->Mdl_Users->update($arg);

		if ($user == null)
			parent::returnWithErr("Profile has not been updated.");

		parent::returnWithoutErr("Profile has been updated successfully.", $user);
	}


	/*--------------------------------------------------------------------------------------------------------
		Make friends ...
	_________________________________________________________________________________________________________*/
	public function api_entry_sendnotification() {
		parent::validateParams(array('sender', 'receiver', 'subject'));

		if(!$this->Mdl_Users->get($_POST['sender']))		parent::returnWithErr("Sender is not valid");
		if(!$this->Mdl_Users->get($_POST['receiver']))		parent::returnWithErr("Receiver is not valid");

		$sender = $this->Mdl_Users->get($_POST['sender']);
		$receiver = $this->Mdl_Users->get($_POST['receiver']);

		unset($sender->password);
		unset($receiver->password);

		if 		($_POST['subject'] == "ipray_sendinvitation") {
			$msg = $sender->username . " has invited you.";
		}
		else if ($_POST['subject'] == "ipray_acceptinvitation") {
			$msg = $sender->username . " has accepted your invitation.";

			// sender ---> receiver 
			$this->Mdl_Users->makeFriends($_POST["sender"], $_POST["receiver"]);
		}
		else if ($_POST['subject'] == "ipray_rejectinvitation") {
			$msg = $sender->username . " has rejected your invitation.";
		}
		else if ($_POST['subject'] == 'ipray_sendprayrequest') {
			parent::validateParams(array('request'));
		}
		else if ($_POST['subject'] == 'ipray_acceptprayrequest') {
			parent::validateParams(array('request'));

			
		}
		else if ($_POST['subject'] == 'ipray_rejectprayrequest') {
			parent::validateParams(array('request'));

			
		}
		else {
			parent::returnWithErr("Unknown subject is requested.");
		}

		if ($receiver->devicetoken == "" || !isset($receiver->devicetoken))
			parent::returnWithErr("User didn't subscribe.");

		$payload = array(
			'sound' => "default",
			'subject' => $_POST['subject'],
			'alert' => $msg,
			'sender' => $sender,
			'receiver' => $receiver
			);

		if (($failedCnt = $this->qbhelper->sendPN($receiver->devicetoken, json_encode($payload))) == 0) {
			$this->load->model('Mdl_Notifications');
			$this->Mdl_Notifications->create(array(
				'subject' => $_POST['subject'],
				'message' => $msg,
				'sender' => $sender->id,
				'receiver' => $receiver->id
				));

			parent::returnWithoutErr("Contact request has been sent successfully.");
		}
		else {
			parent::returnWithErr($failedCnt . " requests have not been sent.");
		}
		
	}

	/*--------------------------------------------------------------------------------------------------------
		Pray ...
	_________________________________________________________________________________________________________*/
	public function api_entry_pray() {
		parent::validateParams(array('prayer', 'subject', 'request'));

		$this->load->model('Mdl_Requests');
		$this->load->model('Mdl_Prays');


		if(!($prayer = $this->Mdl_Users->get($_POST['prayer'])))			parent::returnWithErr("Prayer is not valid");
		if(!($request = $this->Mdl_Requests->get($_POST['request'])))		parent::returnWithErr("Request id is not valid");
		if(!($host = $this->Mdl_Users->get($request->host)))				parent::returnWithErr("Unknown request host.");

		if ($request->type != "REQ_COMMON") 								parent::returnWithErr("Invalid request type. " . $request->type);

		unset($prayer->password);
		unset($host->password);

		if ($host->id == $prayer->id)
			parent::returnWithErr("You can't pray for yourself.");

		if 		($_POST['subject'] == 'ipray_sendprayrequest') {
			$msg = $prayer->username . " would like to pray for you.";

			$sender = $prayer;
			$receiver = $host;
			$status = 0;
		}
		else if ($_POST['subject'] == 'ipray_answerprayrequest') {
			$msg = $prayer->username . " accepted your pray request.";

			$sender = $host;
			$receiver = $prayer;
			$status = 1;
		}
		else {
			parent::returnWithErr("Unknown subject is requested.");
		}

		if ($receiver->devicetoken == "" || !isset($receiver->devicetoken))
			parent::returnWithErr("User didn't subscribe.");

		$pray = $this->Mdl_Prays->create(array(
				'request' => $request->id,
				'prayer' => $prayer->id,
				'status' => $status
				));

		$payload = array(
			'sound' => "default",
			'subject' => $_POST['subject'],
			'alert' => $msg,
			'sender' => $sender,
			'receiver' => $receiver,
			'request' => $request,
			'pray_id' => $pray['id'],
			'meta' => json_encode(array('request' => $request))
			);



		if (($failedCnt = $this->qbhelper->sendPN($host->devicetoken, json_encode($payload))) == 0) {
			$this->load->model('Mdl_Notifications');
			$this->Mdl_Notifications->create(array(
				'subject' => $_POST['subject'],
				'message' => $msg,
				'sender' => $sender->id,
				'receiver' => $receiver->id,
				'meta' => json_encode(array('request' => $request))
				));

			parent::returnWithoutErr("Contact request has been sent successfully.");
		}
		else {
			parent::returnWithErr($failedCnt . " requests have not been sent.");
		}
		
	}
}

?>