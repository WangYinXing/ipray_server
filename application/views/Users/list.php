<header>
  <script src="/assets/Flexigrid-master/js/flexigrid.js"></script>
  <script src="/assets/dist/js/tableinit.js"></script>
<!--
  <script src="/assets/quickblox/quickblox.min.js"></script>

  <script src="/assets/quickblox/qbconfig.js"></script>
  <script src="/assets/quickblox/qbinit.js"></script>
-->
</header>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo strtoupper($page); ?>
      <small><?php echo $page_desc; ?></small>
    </h1>
  </section>
  <div class="grid-toolbar">
    <button type="button" class="btn-edit btn btn-flat btn-girdtoolbar">EDIT</button>
    <button type="button" class="btn-delete btn btn-flat btn-girdtoolbar">DELETE</button>
  </div>

  <div id="main-table"></div>
</div>


<!--
    Javascript calls.....
-->
<script>
$(function() {
  $(document).ready(function() {

    /*
      Init flexigrid and QB....
    */
    initTable();
    //initQB();

     

  });
});
</script>