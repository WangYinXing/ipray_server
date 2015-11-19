<?php

//  application/core/MY_Controller.php
class Home_Controller extends CI_Controller {

  public function __construct(){
    parent::__construct();
    // do whatever here - i often use this method for authentication controller
    
    $this->viewData = array();

    //
    $this->load->library('responsehelper');
  }

  protected function initView($page, $desc, $param) {
  	$this->viewData['session'] = $this->session;

  	$this->viewData['page'] = $page;
  	$this->viewData['page_desc'] = $desc;
    $this->viewData['param'] = $param;
  }

  protected function loadView() {
  	$this->load->view('home', $this->viewData);
  }
}
?>