<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Dailywords extends Mdl_Campus {
	function __construct() {
		$this->table = 'ipray_dailywords';
	}

	public function create($arg) {
		if (!$this->db->insert($this->table, $arg)) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($arg);
			return;
		}

		$arg['id'] = $this->db->insert_id();

		return $arg;
	}

}

?>