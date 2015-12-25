<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class EmailCenter extends Api_Unit {
	public function __construct() {
		parent::__construct();

		$this->load->model('Mdl_Prays', '', TRUE);
	}

	public function index() {
		redirect('/EmailCenter/send/', 'refresh');
	}

	public function send($param = array()) {
		parent::initView('emailcenter', 'emailcenter', 'Manage media such as images and videos',
			array(
				
				)
		);

		parent::loadView();
	}
}