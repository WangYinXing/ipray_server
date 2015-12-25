<head>
  <script src="/assets/Flexigrid-master/js/flexigrid.js"></script>
  <script src="/assets/dist/js/formvalidation.js"></script>

<!--
  <script src="/assets/quickblox/quickblox.min.js"></script>

  <script src="/assets/quickblox/qbconfig.js"></script>
  <script src="/assets/quickblox/qbinit.js"></script>
-->
</head>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Edit user info..
      <small>Edit user info manually</small>
    </h1>
  </section>

  <div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">User info</h3>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <?php echo form_open('Users/save', array("id"=>"save_user_form")); ?>
    <input type="hidden" name="id" value='<?= $param->id ?>'>
    <div class="row">
      <div class="col-md-6">
        <div class="box-body">
          <div class="form-group"><label>User name</label><input name="username" type="text" value='<?= $param->username ?>' class="form-control" placeholder="Password"></div>
          <div class="form-group"><label >Email address</label><input name="email" type="email" value='<?= $param->email ?>' class="form-control" placeholder="Enter email"></div>
          <div class="form-group"><label >Full name</label><input name="fullname" type="text" value='<?= $param->fullname ?>' class="form-control" placeholder="Full name"></div>
          
        </div>
      </div>
      <div class="col-md-6">
        <div class="box-body">
          <div class="form-group"><label >Church name</label><input name="church" type="text" value='<?= $param->church ?>' class="form-control" placeholder="Church name"></div>
          <div class="form-group"><label >Province</label><input name="province" type="text" value='<?= $param->province ?>' class="form-control" placeholder="Province name"></div>
          <div class="form-group"><label >City</label><input name="city" type="text" value='<?= $param->city ?>' class="form-control" placeholder="City name"></div>
          <div class="form-group"><label>Birthday</label>
            <div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
              <input type="text" class="form-control bday">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="box-footer" style="text-align:right">
      <div class="form-group">
        <p>
          <input name="suspended" type="checkbox" id="test1" <?php if ($param->suspended == 1) echo "checked"; ?> />
          <label for="test1">Ban this iPrayee.</label>
        </p>
      </div>
      <button type="submit" class="btn btn-flat btn-girdtoolbar">SAVE</button>
    </div>
  </form>
</div>


<!--
    Javascript calls.....
-->
<script>
$(function() {
  $(document).ready(function(evt) {
      var date = new Date();

     $( ".bday" ).datepicker({format: 'yyyy-mm-dd'});
     $('.bday').datepicker('update', '<?= $param->bday ?>');

     $("#save_user_form").submit(function(evt) {
        if (!validate("input[name='username']", "text", "Please input username correctly.")) { evt.preventDefault(); return;}
        //if (!validate("input[name='email']", "email", "Please input Email address correctly.")) { evt.preventDefault(); return;}
        if (!validate("input[name='fullname']", "text", "Please input Full name correctly.")) { evt.preventDefault(); return;}
        if (!validate("input[name='church']", "text", "Please input Church name correctly.")) { evt.preventDefault(); return;}
        if (!validate("input[name='province']", "text", "Please input Province name correctly.")) { evt.preventDefault(); return;}
        if (!validate("input[name='city']", "text", "Please input City name correctly.")) { evt.preventDefault(); return;}
        
     });
  });
});
</script>