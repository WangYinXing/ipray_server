<div style="height:1000px;" class="content-wrapper">
	<section class="content-header req-content" style="color: white">
	    <h1>
	      <?php echo strtoupper($page); ?>
	      <small><?php echo $page_desc; ?></small>
	    </h1>
	    <div class="row">
	    	<div class="col-md-6">
			    <div class="form-group">
			    	<label>MOTIVE</label>
			    	<div class="req-content"><?= $param['request']->motive ?></div>
			    </div>
			    <div class="form-group">
			    	<label>DETAIL</label>
			    	<div class="req-content"><?= $param['request']->detail ?></div>
			    </div>
			    <div class="form-group">
			    	<label>MEDIA</label>
			    	<div class="req-content">
			    	<?= $param['requestMediaHTML'] ?>
			    	</div>
			    </div>
			</div>
		<div class="row">
	    	<div class="col-md-6">
			    <div>
			    	<label>Comments(<?= $param['commentCount'] ?>)</label>
			    	<?= $param['commentsHTML'] ?>
			    </div>
			</div>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-default btn-flat">Action</button>
			<button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="#">Action</a></li>
				<li><a href="#">Another action</a></li>
				<li><a href="#">Something else here</a></li>
				<li class="divider"></li>
				<li><a href="#">Separated link</a></li>
			</ul>
		</div>
  	</section>
  	<!-- Content Header (Page header) -->

</div>