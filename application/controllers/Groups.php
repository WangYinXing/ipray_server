<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Groups extends Api_Unit {
	public function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Groups', '', TRUE);
	}

	public function index () {

	}




/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/

	/*--------------------------------------------------------------------------------------------------------
		Create group... 
		*** POST
	_________________________________________________________________________________________________________*/
  public function api_entry_list() {
  	parent::validateParams(array("rp", "page", "query", "qtype", "sortname", "sortorder"));

  	$this->load->model("Mdl_Users");
	
    $data = $this->Mdl_Groups->get_list(
      $_POST['rp'],
      $_POST['page'],
      $_POST['query'],
      $_POST['qtype'],
      $_POST['sortname'],
      $_POST['sortorder']);


    foreach ($data as $key => $val) {
    	$host = $val->host;
    	$val->host = $this->Mdl_Users->get($host);
    }
    
    echo json_encode(array(
      'page'=>$_POST['page'],
      'total'=>$this->Mdl_Groups->get_length(),
      'rows'=>$data,
    ));
  }

	public function api_entry_create() {
		parent::validateParams(array("host", "name", "church", "city", "province"));

		$group = $this->Mdl_Groups->create(array(
			'host' => $_POST['host'],
			'name' => $_POST['name'],
			'church' => $_POST['church'],
			'city' => $_POST['city'],
			'province' => $_POST['province'],
			//'icon' => $_POST['icon'],
			));

		if ($group == null)			parent::returnWithErr($this->Mdl_Groups->latestErr);

		/*
			Created successfully .... 
		*/
		parent::returnWithoutErr("Group has been created successfully.", $group);
	}

	/*--------------------------------------------------------------------------------------------------------
		Remove user from specific user... 
		*** Invite user
	_________________________________________________________________________________________________________*/
	public function api_entry_add_user() {
		parent::validateParams(array("group", "user"));

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Groups");

		if ($this->Mdl_Users->get($_POST["user"]) == null)		parent::returnWithErr("User id is not valid.");
		if ($this->Mdl_Groups->get($_POST["group"]) == null)	parent::returnWithErr("Group id is not valid.");

		if ($group = $this->Mdl_Groups->addUser(
			/*
			array(
				'group' => $_POST["group"],
				'user' => $_POST["user"],
				)
			)
			*/
			$_POST["group"], $_POST["user"]) == null)
			parent::returnWithErr($this->Mdl_Groups->latestErr);

		parent::returnWithoutErr("User has been added successfully.", $group);
	}
	/*--------------------------------------------------------------------------------------------------------
		Remove user from specific user... 
		*** Remove user
	_________________________________________________________________________________________________________*/
	public function api_entry_remove_user() {
		parent::validateParams(array("group", "user"));

		$this->load->model("Mdl_Users");
		$this->load->model("Mdl_Groups");

		if ($this->Mdl_Users->get($_POST["user"]) == null)		parent::returnWithErr("User id is not valid.");
		if ($this->Mdl_Groups->get($_POST["group"]) == null)	parent::returnWithErr("Group id is not valid.");

		if ($group = $this->Mdl_Groups->removeUser(
			/*
			array(
				'group' => $_POST["group"],
				'user' => $_POST["user"],
				)
			)
			*/
			$_POST["group"], $_POST["user"]) == null)
			parent::returnWithErr($this->Mdl_Groups->latestErr);

		parent::returnWithoutErr("User has been removed successfully.", $group);
	}
}

?>