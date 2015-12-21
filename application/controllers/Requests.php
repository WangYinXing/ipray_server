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
		$this->load->model("Mdl_Prays");
		$this->load->model("Mdl_Comments");

		$data = $this->Mdl_Requests->get_list(
			$_POST['rp'],
			$_POST['page'],
			$_POST['query'],
			$_POST['qtype'],
			$_POST['sortname'],
			$_POST['sortorder']);

		foreach ($data as $key => $val) {
			$val->host = $this->Mdl_Users->get($val->host);
			unset($val->host->password);

			$val->comments = $comments = $this->Mdl_Comments->getAll("request", $val->id);

			$val->prayers = $this->Mdl_Prays->getAll("request", $val->id);

			if (count($comments) == 0)
				continue;

			foreach ($comments as $key => $val) {
				$user = $this->Mdl_Users->get($val->commenter);

				$val->commenter = array(
					'qbid' => $user->qbid,
					'id' => $user->id,
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
		parent::validateParams(array("type", "host"));

		if ($_POST["type"] == "REQ_COMMON")				parent::validateParams(array("motive", "detail", "anonymous"));
		else if ($_POST["type"] == "REQ_FEED") {
			parent::validateParams(array("mediatype"));

			if ($_POST["mediatype"] == "VIDEO" || $_POST["mediatype"] == "IMG") 	parent::validateParams(array("mediaurl"));
			else if ($_POST["mediatype"] == "TEXT") 								parent::validateParams(array("detail"));
			else parent::returnWithErr("Unknown media type.");
		}
		else {
			parent::returnWithErr("Unknown request type.");
		}

		$request = $this->Mdl_Requests->create($this->safeArray(array('host', 'motive', 'detail', 'anonymous', 'type', 'mediatype', 'mediaurl'), $_POST));

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

		if ($_POST["like"] != 0 && $_POST["like"] != 1) {
			parent::returnWithErr("[like] should be '0' or '1'.");
		}

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Requests");

		if (!$this->Mdl_Users->get($_POST['user']))				parent::returnWithErr("User id is not valid.");
		if (!$this->Mdl_Requests->get($_POST['request']))		parent::returnWithErr("Request id is not valid.");


		if (($request = $this->Mdl_Requests->like(
			array(
				'request' => $_POST['request'],
				'user' => $_POST['user'],
				'like' => $_POST['like']
				)
			)) == null)	parent::returnWithErr($this->Mdl_Requests->latestErr);

		parent::returnWithoutErr("User liked or disliked successfully.", $request);
	}

	/*--------------------------------------------------------------------------------------------------------
		Like to request...
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_share() {
		parent::validateParams(array("request", "user"));

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Requests");

		if (!$this->Mdl_Users->get($_POST['user']))				parent::returnWithErr("User id is not valid.");
		if (!$this->Mdl_Requests->get($_POST['request']))		parent::returnWithErr("Request id is not valid.");


		if (($request = $this->Mdl_Requests->share(
			array(
				'request' => $_POST['request'],
				'user' => $_POST['user']
				)
			)) == null)	parent::returnWithErr($this->Mdl_Requests->latestErr);

		parent::returnWithoutErr("User sharing is recorded successfully.", $request);
	}
}

?>