<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class EmailCenter extends Api_Unit {
	
	public function __construct() {
		parent::__construct();

		//$this->load->model('Mdl_Prays');
		$this->load->library('Emailsender');
	}

	public function index($param = array()) {
		if (!isset($param['content'])) {
			$param['content'] = "Hi.Dear ";
		}

		parent::initView('emailcenter', 'emailcenter', 'Manage media such as images and videos',
			$param
		);

		parent::loadView();
	}

	public function send() {
		$emailContent = $_POST['editor1'];
		$subject = $_POST['subject'];

		if (!count($bcc)) {
			//
		}

		$this->Emailsender->send($bcc, $subject, $emailContent);
	}
}