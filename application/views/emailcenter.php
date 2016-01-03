<script src="/assets/plugins/ckeditor/ckeditor.js"></script>
<div style="height:1000px;" class="content-wrapper">
	<section class="content-header" style="color: white">
	    <h1>
	      <?php echo strtoupper($page); ?>
	      <small><?php echo $page_desc; ?></small>
	    </h1>
		
		
	    <div class="box-body pad">
          <?php echo form_open('EmailCenter/send', array("id" => "emailcomposer")); ?>
          	<div class="grid-toolbar">
				<input type="submit" class="btn-send btn btn-flat btn-girdtoolbar" value="SEND"></input>
			</div>
	        <div class="form-group">
				<input name="subject" placeholder="Subject" class="email-subject">
			</div>
            <textarea id="editor1" name="editor1" rows="10" cols="80">
            <?= $param['content'] ?>
            </textarea>
            <input name="emailbody" type="hidden" />
          </form>
        </div>
  	</section>
  	<!-- Content Header (Page header) -->

</div>

<script>
	$(function () {
	    // Replace the <textarea id="editor1"> with a CKEditor
	    // instance, using default configuration.
	    CKEDITOR.config.height = '500px';
	    CKEDITOR.config.uiColor = '#6CA590';
	    CKEDITOR.replace('editor1');
	    //bootstrap WYSIHTML5 - text editor
	    //$(".textarea").wysihtml5();
  	});
</script>