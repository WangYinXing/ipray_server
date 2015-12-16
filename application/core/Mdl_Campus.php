<?php
class Mdl_Campus extends CI_Model {



	function __construct() {
		parent::__construct();

		$this->latestErr = "";
	}

	public function getLatestError() {
		return $this->latestErr;
	}

	public function get($id) {
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where("id", $id);

		$users = $this->db->get();

		if ($users->num_rows() == 1) {
			return $users->result()[0];

		}

		return null;
	}

	public function getAll($field = "", $val = "") {
		$this->db->select("*");
		$this->db->from($this->table);

		if ($field != "" && $val != "")
			$this->db->where($field, $val);

		$users = $this->db->get();

		if ($users->num_rows() == 0)
			return;

		return $users->result();
	}

	public function getAllEx($conditions) {
		$this->db->select("*");
		$this->db->from($this->table);

		$this->db->where($conditions);

		$users = $this->db->get();

		if ($users->num_rows() == 0)
			return;

		return $users->result();
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

	public function get_length() {
		$this->db->select("id");
		$this->db->from($this->table);
		return $this->db->get()->num_rows();
	}

	public function remove($id) {
		$this->db->delete($this->table, array('id' => $id));
	}

	public function addToStrArray($val, $strArray) {
		$result = array();

		if ($strArray == null) {
			array_push($result, $val);

			return array("array" => $result, "succeed" => true);
		}

		$result = json_decode($strArray);

		$key = array_search($val, $result);

		if (in_array($val, $result)) {
			return array("array" => $result, "succeed" => false);
		}

		array_push($result, $val);

		return array("array" => $result, "succeed" => true);
	}

	public function removeFromStrArray($val, $strArray) {
		$result = array();

		if ($strArray == null) {
			return array("array" => $result, "succeed" => false);
		}

		$result = json_decode($strArray);

		if (!in_array($val, $result)) {
			return array("array" => $result, "succeed" => false);
		}

		$key = array_search($val, $result);

		unset($result[$key]);

		return array("array" => $result, "succeed" => true);
	}

}
?>