<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Requests extends Api_Unit {
	public function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Requests', '', TRUE);
	}

	public function index () {

	}




/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/

	public function api_entry_list() {
		parent::validateParams(array("rp", "page", "query", "qtype", "sortname", "sortorder"));

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Comments");

		$data = $this->Mdl_Requests->get_list(
			$_POST['rp'],
			$_POST['page'],
			$_POST['query'],
			$_POST['qtype'],
			$_POST['sortname'],
			$_POST['sortorder']);

		foreach ($data as $key => $val) {
			$val->comments = $comments = $this->Mdl_Comments->getAll("request", $val->id);

			if (count($comments) == 0)
				continue;

			foreach ($comments as $key => $val) {
				$user = $this->Mdl_Users->get($val->commenter);

				$val->commenter = array(
					'username' => $user->username,
					'email' => $user->email
					);
			}
		}

		parent::returnWithoutErr("Request has been listed successfully.", array(
			'page'=>$_POST['page'],
			'total'=>$this->Mdl_Requests->get_length(),
			'rows'=>$data,
		));
	}

	/*--------------------------------------------------------------------------------------------------------
		Create Request... 
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_create() {
		parent::validateParams(array("host", "motive", "detail", "anonymous"));

		$request = $this->Mdl_Requests->create(array(
			'host' => $_POST['host'],
			'motive' => $_POST['motive'],
			'detail' => $_POST['detail'],
			'anonymous' => $_POST['anonymous']
			));

		if ($request == null)	parent::returnWithErr($this->Mdl_Requests->latestErr);

		/*
			Created successfully .... 
		*/
		parent::returnWithoutErr("Request has been created successfully.", $request);
	}


	/*--------------------------------------------------------------------------------------------------------
		Comment to request...
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_comment() {
		parent::validateParams(array("request", "user", "comment"));

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Requests");

		if (!$this->Mdl_Users->get($_POST['user']))				parent::returnWithErr("User id is not valid.");
		if (!$this->Mdl_Requests->get($_POST['request']))		parent::returnWithErr("Request id is not valid.");

		$this->load->model("Mdl_Comments");


		if (($comment = $this->Mdl_Comments->create(array(
			'request' => $_POST['request'],
			'commenter' => $_POST['user'],
			'comment' => $_POST['comment'],
			))) == null)	parent::returnWithErr($this->Mdl_Comments->latestErr);

		parent::returnWithoutErr("User commented successfully.", $comment);
	}

	/*--------------------------------------------------------------------------------------------------------
		Like to request...
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_like() {
		parent::validateParams(array("request", "user", "like"));

		if ($_POST["like"] != "0" || $_POST["like"] != "1") {
			parent::returnWithErr("[like] should be '0' or '1'.");
		}

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Requests");

		if (!$this->Mdl_Users->get($_POST['user']))				parent::returnWithErr("User id is not valid.");
		if (!$this->Mdl_Requests->get($_POST['request']))		parent::returnWithErr("Request id is not valid.");


		if (($group = $this->Mdl_Requests->likeUser(
			array(
				'request' => $_POST['request'],
				'user' => $_POST['user'],
				'like' => $_POST['like']
				)
			)) == null)	parent::returnWithErr($this->Mdl_Requests->latestErr);

		parent::returnWithoutErr("User liked or disliked successfully.", $group);
	}
}

?>