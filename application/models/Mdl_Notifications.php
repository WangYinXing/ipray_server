<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Notifications extends Mdl_Campus {

	function __construct() {
		$this->table = 'ipray_notifications';
	}

	public function create($arg) {
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

	public function fetch($user) {
		$this->db->select("*");
		$this->db->from($this->table);
		
		//$this->db->where('sender', $user);
		$this->db->where('receiver', $user);

		return $this->db->get()->result();
	}
}

?>