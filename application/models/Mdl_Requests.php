<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Mdl_Requests extends Mdl_Campus {

	function __construct() {
		$this->table = 'ipray_requests';
	}

	public function create($arg) {
		$this->load->model("Mdl_Users");

		$user = $this->Mdl_Users->get($arg['host']);

		if ($user == null) {
			$this->latestErr = "Host id is not valid.";
			return;
		}

		$this->db->insert($this->table, $arg);
		$request = $this->db->insert_id();

		if ($request == 0) {
			$this->latestErr = "Failed to create excute sql with : " . json_encode($arg);
		}
		else {
			$this->latestErr = "";
		}

		$arg['id'] = $request;

		return $arg;
	}

	public function like($arg) {
		$this->latestErr = "";

		$request = $this->get($arg["request"]);

		if ($request == null) {
			$this->latestErr = "Request is not valid...";
			return;
		}

		if ($arg["like"] == 1) {
			$ret = $this->addToStrArray($arg["user"], $request->likes);

			if (!$ret['succeed']) {
				$this->latestErr = "User is added already.";
				return;
			}
		}
		else if ($arg["like"] == 0) {
			$ret = $this->removeFromStrArray($arg["user"], $request->likes);

			if (!$ret['succeed']) {
				$this->latestErr = "User hasn't been added ever.";
				return;
			}
		}

		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('id', $arg["request"]);
		$this->db->update($this->table, array('likes'=> json_encode($ret['array'])));

		$request->likes = json_encode($ret['array']);

		return $request;
	}


	public function pray($arg) {
		$this->latestErr = "";

		$request = $this->get($arg["request"]);

		if ($request == null) {
			$this->latestErr = "Request is not valid...";
			return;
		}

		$ret = $this->addToStrArray($arg["user"], $request->prayers);

		if (!$ret['succeed']) {
			$this->latestErr = "This user started to prayed already.";
			return;
		}
		

		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('id', $arg["request"]);
		$this->db->update($this->table, array('prayers'=> json_encode($ret['array'])));

		$request->prayers = json_encode($ret['array']);

		return $request;
	}

	public function share($arg) {
		$this->latestErr = "";

		$request = $this->get($arg["request"]);

		if ($request == null) {
			$this->latestErr = "Request is not valid...";
			return;
		}

		$ret = $this->addToStrArray($arg["user"], $request->shares);

		if (!$ret['succeed']) {
			$this->latestErr = "This user shared already.";
			return;
		}
		

		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->where('id', $arg["request"]);
		$this->db->update($this->table, array('shares'=> json_encode($ret['array'])));

		$request->shares = json_encode($ret['array']);

		return $request;
	}

}

?>