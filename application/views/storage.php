<div style="height:1000px;" class="content-wrapper">
	<section class="content-header">
	    <h1>
	      <?php echo strtoupper($page); ?>
	      <small><?php echo $page_desc; ?></small>
	    </h1>
	    <?= $param["paginationHTML"] ?>
		<div class="table req-list">
			<div class="table-row req-list-header">
				<div class="table-cell" style="width:100px">AUTHOR</div>
				<div class="table-cell">MEDIA</div>
				<div class="table-cell" style="width:200px">MOTIVE</div>
				<div class="table-cell">DESCRIPTION</div>
			</div>
			<?php
			for ($i=0; $i<count($param["requestsHTML"]); $i++) {
				$req = $param["requestsHTML"][$i];
				echo $req;
			?>
			<?php } ?>
		</div>
		<?= $param["paginationHTML"] ?>
  	</section>
  	<!-- Content Header (Page header) -->

</div>