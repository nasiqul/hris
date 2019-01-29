<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>HOME</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-industry"></i> <span>Employee</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('home/karyawan_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
            <li><a href="<?php echo base_url('home/karyawan'); ?>"><i class="fa fa-group"></i> <span>Employee Data</span></a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-eye"></i> <span>Presence</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('home/presensi_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
            <li><a href="<?php echo base_url('home/presensi'); ?>"><i class="fa fa-male"></i> <span>Presence Data</span></a>
          </ul>
        </li>
        <li><a href="<?php echo base_url('home/absen'); ?>"><i class="fa fa-edit"></i> <span>Attendance</span></a></li>
        <li><a href="<?php echo base_url('home/over'); ?>"><i class="fa fa-link"></i> <span>Overtime</span></a></li>
        <li><a href="<?php echo base_url('home/tanya'); ?>"><i class="fa fa-comments-o"></i> <span>Q & A List</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('login'); ?>">Login</a></li>
            <li><a href="<?php echo base_url('home/client'); ?>" target="_blank">Client</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>