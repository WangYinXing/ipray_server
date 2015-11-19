<?php
class Mdl_Campus extends CI_Model {



	function __construct() {
		parent::__construct();

		$this->latestErr = "";
	}

	public function getLatestError() {
		return $this->latestErr;
	}



}
?>