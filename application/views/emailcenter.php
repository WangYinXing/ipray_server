<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<div style="height:1000px;" class="content-wrapper">
	<section class="content-header" style="color: white">
	    <h1>
	      <?php echo strtoupper($page); ?>
	      <small><?php echo $page_desc; ?></small>
	    </h1>
		<div class="grid-toolbar">
			<a type="button" class="btn-send btn btn-flat btn-girdtoolbar">SEND</a>
		</div>
		
	    <div class="box-body pad">
          <form>
	        <div class="form-group">
				<input placeholder="Subject" class="email-subject">
			</div>
            <textarea id="editor1" name="editor1" rows="10" cols="80">
            Hi.<br>
            Dear iPrayee.
            </textarea>
          </form>
        </div>
  	</section>
  	<!-- Content Header (Page header) -->

</div>

<script>
	$(function () {
	    // Replace the <textarea id="editor1"> with a CKEditor
	    // instance, using default configuration.
	    CKEDITOR.config.height = '600px';
	    CKEDITOR.replace('editor1');
	    //bootstrap WYSIHTML5 - text editor
	    //$(".textarea").wysihtml5();
  	});
</script>