<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel hidden">
      <div class="pull-left image">
        <img src="<?php echo base_url('assets/dist/img/user2-160x160.jpg'); ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>
          <?php echo $session->userdata('username')?>
        </p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">WE TRUST IN OUR GOD</li>
      <li <?php if ($page == 'dashboard') echo "class='active'";?>>
        <a href="dashboard">
          <i class="fa fa-dashboard"></i> <span>DASHBOARD</span>
        </a>
      </li>
      <li <?php if ($page == 'users') echo "class='active'";?>>
        <a href="users">
          <i class="fa fa-user"></i> <span>iPRAYEES</span>
          <small class="label pull-right bg-green">new</small>
        </a>
      </li>
      <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>ABOUT US</span></a></li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>