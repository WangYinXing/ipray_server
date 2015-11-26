<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Groups extends Mdl_Campus {
	function __construct() {
		$this->table = 'ipray_groups';
	}

	public function get_list($rp, $page, $query, $qtype, $sortname, $sortorder) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->order_by($sortname, $sortorder);

		if ($query != "" && $qtype != "") {
			$this->db->like($qtype, $query);
		}
		
		$this->db->limit($rp, $rp * ($page - 1));

		return $this->db->get()->result();
	}


	public function create($arg) {
		$this->load->model("Mdl_Users");

		$user = $this->Mdl_Users->get($arg['host']);

		if ($user == null) {
			$this->latestErr = "Host id is not valid.";
			return;
		}


		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('name', $arg['name']);

		if ($this->db->get()->num_rows() != 0) {
			$this->latestErr = "Name is already used by another group.";
			return;
		}


		$group = $this->db->insert($this->table, $arg);

		if ($group == 0) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($arg);
		}
		else {
			$this->latestErr = "";
		}

		$arg['id'] = $group;

		return $arg;
	}


	public function addUser($group, $user) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('id', $group);

		$ret = $this->db->get();

		if ($ret->num_rows() == 0) {
			$this->latestErr = "Group id is not valid.";
			return;
		}

		$targetGroup = $ret->result()[0];


		$members = array();

		/*
			This group is now empty. this is first push of user...
		*/
		if ($targetGroup->members != null) {
			$members = json_decode($targetGroup->members);
		}

		if (count($members) > 0) {
			foreach ($members as $val) {
				if ($val == $user) {
					$this->latestErr = "User already added.";
					return;
				}
			}
		}
	

		/*
			Now push a new user to group...
		*/
		array_push($members, $user);

		$this->db->update($this->table, array('members'=> json_encode($members)));

		$targetGroup->members = $members;

		return $targetGroup;
	}

	public function removeUser($group, $user) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('id', $group);

		$ret = $this->db->get();

		if ($ret->num_rows() == 0) {
			$this->latestErr = "Group id is not valid.";
			return;
		}

		$targetGroup = $ret->result()[0];


		$members = array();

		/*
			This group is now empty. not need to remove user..
		*/
		if ($targetGroup->members == null) {
			$this->latestErr = "User isn't added in this group before.";
			return;
		}
		else {
			$members = json_decode($targetGroup->members);
		}

		$key = array_search($user, $members);

		if (in_array($user, $members)) {
			unset($members[$key]);
		}
		else {
			$this->latestErr = "User isn't added in this group before.";
			return;
		}

		$this->db->update($this->table, array('members'=> json_encode($members)));

		$targetGroup->members = $members;

		return $targetGroup;
	}
}

?>