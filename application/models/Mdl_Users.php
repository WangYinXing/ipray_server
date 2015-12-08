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

	public function signup($username, $email, $password, $fullname, $church, $province, $city, $bday, $qbuser) {
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
			'password'=> md5($password),
			'fullname'=> $fullname,
			'church'=> $church,
			'province'=> $province,
			'city'=> $city,
			'bday'=> $bday,
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

	public function signin($qbid, $token) {
		$this->db->select("*");
		$this->db->from($this->table);

		$this->db->where("qbid", $qbid);
		$user = $this->db->get()->result()[0];


		$this->db->select("*");
		$this->db->where("qbid", $qbid);

		if (!$this->db->update($this->table, array('token'=> $token))) {
			return;
		}

		unset($user->password);
		//unset($user->updated_time);

		$user->token = $token;
		
		return $user;
	}

	public function signout($user) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where("id", $user);

		if (!$this->db->update($this->table, array('token'=> ''))) {
			return;
		}

		//unset($user->password);
		//unset($user->updated_time);

		//$user['token'] = '';

		//return $user;
	}

	public function update($arg) {
		$id = $arg['id'];

		unset($arg['id']);

		$this->db->select("*");
		$this->db->from($this->table);

		$this->db->where("id", $id);

		if (!$this->db->update($this->table, $arg)) {
			return;
		}

		$this->db->from($this->table);

		$this->db->where("id", $id);

		return $this->db->get()->result()[0];
	}
}

?>