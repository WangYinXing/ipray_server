<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Comments extends Mdl_Campus {

	function __construct() {
		$this->table = 'ipray_comments';
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

}

?>