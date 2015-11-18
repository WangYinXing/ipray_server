<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Users extends CI_Model {
	public function get_userlist() {
		$this->db->select("*");
		$this->db->from("ipray_users");

		return $this->db->get()->result();
	}
}

?>