<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Users extends CI_Model {

	function __construct() {
		$this->table = 'ipray_users';
	}

	public function get_userlist($rp, $page, $query, $qtype, $sortname, $sortorder) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->order_by($sortname, $sortorder);

		if ($query != "" && $qtype != "") {
			$this->db->like($qtype, $query);
		}
		
		$this->db->limit($rp, $rp * ($page - 1));

		return $this->db->get()->result();
	}

	public function get_usercnt() {
		$this->db->select("id");
		$this->db->from($this->table);
		return $this->db->get()->num_rows();
	}

	protected function signup_user($email, $username, $password) {
		$this->select("*");
		$this->db->from($this->table);
		$this->db->where('email', $email);

		if ($this->db->get()->num_rows() != 0) {
			return "email is already used by another iprayee.";
		}

		$data = array(
			'email'=>$email,
			'username'=>$username,
			'password'=>md5($password),
		);

		$this->db->insert($this->table, $data);
	}

	protected function signin_user($email, $username, $password) {

	}
}

?>