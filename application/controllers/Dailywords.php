<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Dailywords extends Api_Unit {
	public function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Dailywords', '', TRUE);
	}

	public function index () {

	}




/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/
	/*--------------------------------------------------------------------------------------------------------
		List DWords... 
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_list() {
		parent::validateParams(array("rp", "page"));

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Comments");

		$data = $this->Mdl_Dailywords->get_list(
			$_POST['rp'],
			$_POST['page'],
			isset($_POST['query']) ? $_POST['query'] : "",
			isset($_POST['qtype']) ? $_POST['qtype'] : "",
			isset($_POST['sortname']) ? $_POST['sortname'] : "",
			isset($_POST['sortorder']) ? $_POST['sortorder'] : "");

		parent::returnWithoutErr("DWrods has been listed successfully.", array(
			'page'=>$_POST['page'],
			'total'=>$this->Mdl_Dailywords->get_length(),
			'rows'=>$data,
		));
	}

	/*--------------------------------------------------------------------------------------------------------
		Create DWords... 
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_create() {
		parent::validateParams(array("contents"));

		$dword = $this->Mdl_Dailywords->create(array(
			'contents' => $_POST['contents'],
			'comment' => isset($_POST['comment']) ? $_POST['comment'] : "",
			'category' => isset($_POST['comment']) ? $_POST['category'] : 0,
			'tag' => isset($_POST['comment']) ? $_POST['tag'] : 0,
			));

		if ($dword == null)	parent::returnWithErr($this->Mdl_Dailywords->latestErr);

		/*
			Created successfully .... 
		*/
		parent::returnWithoutErr("DWords has been created successfully.", $dword);
	}

	/*--------------------------------------------------------------------------------------------------------
		Pick DWords up randomly... 
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_pickup() {
		$len = $this->Mdl_Dailywords->get_length();

		if ($len == 0) parent::returnWithErr("No Daily words to pick at this time.");

		$list = $this->Mdl_Dailywords->getAll();

		parent::returnWithoutErr("DWords has been created successfully.", $list[ rand(0, $len - 1) ]);
	}
}

?>