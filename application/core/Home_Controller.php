<?php

//  application/core/MY_Controller.php
class Home_Controller extends CI_Controller {
  // Controller name.... such as Requests, Users ... 
  protected $ctrlName = "undefined";

  public function __construct(){
    parent::__construct();
    // do whatever here - i often use this method for authentication controller

    /*
      Load model class from class name...
    */
    
    $this->viewData = array();
  }

  protected function initView($view, $page, $desc, $param) {
  	$this->viewData['session'] = $this->session;

  	$this->viewData['view'] = $view;
    $this->viewData['page'] = $page;
  	$this->viewData['page_desc'] = $desc;
    $this->viewData['param'] = $param;
  }

  protected function loadView() {
  	$this->load->view('home', $this->viewData);
  }
}
?>