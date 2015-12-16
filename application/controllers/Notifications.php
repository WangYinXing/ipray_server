<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Notifications extends Api_Unit {
	public function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Notifications', '', TRUE);
	}

	public function index () {

	}


/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/
	/*--------------------------------------------------------------------------------------------------------
		list entry
		*** POST
	_________________________________________________________________________________________________________*/
	public function api_entry_list() {
		parent::validateParams(array("user"));

		$this->load->model('Mdl_Users');

		if (!($user = $this->Mdl_Users->get($_POST["user"])))	parent::returnWithErr("User id is not valid.");

		$notis = $this->Mdl_Notifications->fetch($_POST["user"]);

		foreach ($notis as $val) {
			$val->sender = $this->Mdl_Users->get($val->sender);
			$val->receiver = $this->Mdl_Users->get($val->receiver);
		}

		return parent::returnWithoutErr("Notifications are successfully fetched.", $notis);
	}

	public function api_entry_remove() {
		parent::validateParams(array("noti"));

		if (!$this->Mdl_Notifications->get($_POST["noti"]))		parent::returnWithErr("Invalid notification.");

		$this->Mdl_Notifications->remove($_POST["noti"]);

		return parent::returnWithoutErr("Notification is deleted successfully.");
	}
}

?>