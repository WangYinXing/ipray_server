<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Users extends Mdl_Campus {

	function __construct() {
		$this->table = 'ipray_users';
	}
	
	public function online_usercnt() {
		$this->db->select("id");
		$this->db->from($this->table);
		$this->db->where('token', 'a');

		return $this->db->get()->num_rows();
	}

	public function signup($username, $email, $password, $qbuser) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('email', $email);

		if ($this->db->get()->num_rows() != 0) {
			$this->latestErr = "email is already used by another iprayee.";
			return null;
		}

		$data = array(
			'email'=> $email,
			'username'=> $username,
			'password'=> $password,
			'qbid'=> $qbuser->id,
		);

		if (!$this->db->insert($this->table, $data)) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($data);
			return;
		}

		$data['id'] = $this->db->insert_id();

		unset($data['password']);

		return $data;
	}

	public function signin_user($email, $password, $qbuser = null) {
		$this->db->select("*");
		$this->db->from($this->table);

		$this->db->where("email", $email);
		$this->db->where("password", $password);

		$user = $this->db->get()->result();

		if ( is_array($user) && count($user) == 1 ) {
			$user = $user[0];

			if ($qbuser == null) {
				return $user;
			}

			/*
				Copy token from QB user...
			*/
			$user->token = $qbuser->token;

			$this->db->where('id', $user->id);
			$this->db->update($this->table, array('token'=> $qbuser->token));

			/*
				Prevent to send password to client...
			*/
			unset($user->password);

			return $user;
		}

		return null;
	}
}

?>