<?php

Class Users extends Home_Controller {
	public function index() {
		parent::initView('users', 'Manage iprayees for CRUDing');

		$this->load->model('Mdl_Users');
		parent::loadView();
	}
}

?>