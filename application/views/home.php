<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<?php 
/*
	header and side menu....
*/
	$this->view('header');

	?>


<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

		<?php
			$this->view('banner');
			$this->view('sidemenu');

		/*
			Load page contents....
		*/
			$this->view($view);
		?>


		<?php $this->view('footer') ?>
	</div>
</body>
</html>