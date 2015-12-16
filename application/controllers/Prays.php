<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Prays extends Api_Unit {
	public function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Prays', '', TRUE);
	}

	public function index () {

	}

/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/

	/*--------------------------------------------------------------------------------------------------------
		Remove user from specific user... 
		*** Invite user
	_________________________________________________________________________________________________________*/
	public function api_entry_list() {
		parent::validateParams(array("type", "user"));

		$this->load->model("Mdl_Requests");
		$this->load->model("Mdl_Users");

		if ($this->Mdl_Users->get($_POST["user"]) == null)		parent::returnWithErr("User id is not valid.");

		$prays = $this->Mdl_Prays->getAll();
		$requests = array();

		foreach ($prays as $key => $val) {
			$request = $this->Mdl_Requests->get($val->request);
			$prayer = $this->Mdl_Users->get($val->prayer);
			unset($prayer->password);

			$request->host = $this->Mdl_Users->get($request->host);
			unset($request->host->password);

			$request->pray_time = $val->updated_time;

			$request->status = $val->status;

			if ($_POST["type"] == "ipray_praying_for_me") {
				if ($_POST["user"] == $request->host->id) {
					$request->prayer = $prayer;
					array_push($requests, $request);
				}
			}
			else if ($_POST["type"] == "ipray_i_am_praying_for") {
				if ($_POST["user"] == $val->prayer) {
					array_push($requests, $request);
				}
			}
			else if ($_POST["type"] == "ipray_request_attended") {
				if ($_POST["user"] == $request->host && $val->status == 1) {
					$request->prayer = $prayer;
					array_push($requests, $request);
				}
			}
			else {
				parent::returnWithErr("Unknown type.");
			}
		}
		

		parent::returnWithoutErr("Successfully fetched.", $requests);
	}
}

?>