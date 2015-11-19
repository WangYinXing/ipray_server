<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Storage extends Home_Controller{
	function __construct() {
		parent::__construct();
	}

	public function index() {
		parent::initView('storage', 'Manage media such as images and videos',
			array()
		);

		parent::loadView();
	}
}

?>