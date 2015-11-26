<?php

//  application/core/MY_Controller.php
class Api_Unit extends Home_Controller {

  public function __construct(){
    parent::__construct();

    //
    $this->load->library('Resphelper');
  }

  public function returnWithErr($err) {
    exit($this->resphelper->makeResponseWithErr($err));
  }

  public function returnWithoutErr($msg, $data) {
    exit($this->resphelper->makeResponse($msg, $data));
  }

  public function validateParams($requiredParams) {
    foreach ($requiredParams as $val) {
      if (!isset($_POST[$val])) {
        exit($this->resphelper->makeResponseWithErr("[" . $val . "] param is required. Please make sure that you have this parameter in HTTP body."));
      }
    }
  }

}
?>