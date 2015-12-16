<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Prays extends Mdl_Campus {

	function __construct() {
		$this->table = 'ipray_prays';
	}

	public function create($arg) {
		$this->latestErr = "";

		if (!$this->db->insert($this->table, $arg)) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($arg);
			return;
		}

		$arg['id'] = $this->db->insert_id();

		return $arg;
	}

	public function changeStatus($request, $prayer, $status) {
		$this->latestErr = "";

		$this->db->from($this->table);
		$this->db->where(array('request' => $request, 'prayer' => $prayer));

		$this->db->update($this->table, array('status' => $status));
	}

	

}

?>