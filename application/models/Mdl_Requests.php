<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Requests extends Mdl_Campus {

	function __construct() {
		$this->table = 'ipray_requests';
	}

	public function create($arg) {
		$this->load->model("Mdl_Users");

		$user = $this->Mdl_Users->get($arg['host']);

		if ($user == null) {
			$this->latestErr = "Host id is not valid.";
			return;
		}

		$this->db->insert($this->table, $arg);
		$request = $this->db->insert_id();

		if ($request == 0) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($arg);
		}
		else {
			$this->latestErr = "";
		}

		$arg['id'] = $request;

		return $arg;
	}

	public function like($arg) {
		
	}

}

?>