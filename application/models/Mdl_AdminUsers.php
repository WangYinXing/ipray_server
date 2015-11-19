<?php

Class Mdl_AdminUsers extends Mdl_Campus {
	function login($username, $password) {
		$this->db->select("*");
		$this->db->from("ipray_admins");

		$this->db->where("username", $username);
		$this->db->where("password", MD5($password));

		$login = $this->db->get()->result();

		// The results of the query are stored in $login.
	    // If a value exists, then the user account exists and is validated
	    if ( is_array($login) && count($login) == 1 ) {
	        // Set the users details into the $details property of this class
	        $this->details = $login[0];
	        // Call set_session to set the user's session vars via CodeIgniter
	        $this->set_session();
	        return true;
	    }

	    return false;
	}

	function set_session() {
	    // session->set_userdata is a CodeIgniter function that
	    // stores data in a cookie in the user's browser.  Some of the values are built in
	    // to CodeIgniter, others are added (like the IP address).  See CodeIgniter's documentation for details.
	    $this->session->set_userdata( array(
	            'id'=>$this->details->id,
	            'username'=> $this->details->username,
	            'email'=>$this->details->email,
	            'avatar'=>$this->details->avatar,
	            'tagline'=>$this->details->tagline,
	            'isAdmin'=>$this->details->isAdmin,
	            'teamId'=>$this->details->teamId,
	            'isLoggedIn'=>true
	        )
	    );
	}

	function destroy_session() {
	    // session->set_userdata is a CodeIgniter function that
	    // stores data in a cookie in the user's browser.  Some of the values are built in
	    // to CodeIgniter, others are added (like the IP address).  See CodeIgniter's documentation for details.
	    $this->session->unset_userdata('id');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('avatar');
		$this->session->unset_userdata('tagline');
		$this->session->unset_userdata('isAdmin');
		$this->session->unset_userdata('teamId');

		$this->session->set_userdata(array('isLoggedIn'=>false));

		$this->session->sess_destroy();
	}
}
?>