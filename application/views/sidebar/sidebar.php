<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN</li>
      <!-- Optionally, you can add icons to the links -->
      
      <?php if ($this->session->userdata('nik')) { ?>

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
        <li class="treeview">
          <a href="#"><i class="fa fa-edit"></i> <span>Absent</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('home/absensi_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
            <li><a href="<?php echo base_url('home/absen'); ?>"><i class="fa fa-edit"></i> <span>Absent Data</span></a>
            </ul>
        </li>
        <li class="treeview">
        <a href="#"><i class="fa fa-line-chart"></i> <span>Overtime</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo base_url('home/ot_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
          <li><a href="<?php echo base_url('home/ot'); ?>"><i class="fa fa-male"></i> <span>Overtime Data</span></a></li>
          <li><a href="<?php echo base_url('home/ot_report'); ?>"><i class="fa fa-book"></i> Report</a></li>
          </ul>
        </li>
          <li><a href="<?php echo base_url('home/tanya'); ?>"><i class="fa fa-comments-o"></i> <span>Q & A List</span></a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url('login'); ?>" target="_blank">Login</a></li>
              <li><a href="<?php echo base_url('home/karyawan_coba'); ?>">Coba</a></li>
              <li><a href="<?php echo base_url('home/sess_destroy'); ?>">Sess_destroy</a></li>
              <li><a href="<?php echo base_url('home/overtime_user'); ?>">overtime_user</a></li>       
            </ul>
          </li>

        <?php }  else { ?>

          <li class="active"><a href="<?php echo base_url('home/overtime_user'); ?>"><i class="fa fa-line-chart"></i> <span>overtime_user</span></a></li>
        <?php } ?>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>