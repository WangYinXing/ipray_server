<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

	
	<!-- Bootstrap 3.3.5 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">

	<script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skin-custom.css">
</head>



<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo base_url(); ?>assets/dist/img/iprayadminlogo.png" />
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">

   <?php echo validation_errors(); ?>
   <?php echo form_open('AdminLogin/login_user'); ?>
   <?php if ($error) { echo "<p style='color:#FF8264'>Failed to login.</p>"; } ?>
      <div class="form-group has-feedback">
        <input name="username" type="text" class="form-control" placeholder="User name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div style="display:none" class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    <!-- /.social-auth-links -->
<!--
    <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a>
-->
  </div>
  <script>
	  $(function () {
	    $('input').iCheck({
	      checkboxClass: 'icheckbox_square-blue',
	      radioClass: 'iradio_square-blue',
	      increaseArea: '20%' // optional
	    });
	  });
  </script>
  <!-- /.login-box-body -->
</div>
</body>
</html>