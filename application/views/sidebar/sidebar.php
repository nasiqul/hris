<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN</li>
      <!-- Optionally, you can add icons to the links -->
      <li class="<?php if($menu == 'home') echo 'active'?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>HOME</span></a></li>
      
      <?php if ($this->session->userdata('nik')) { ?>
      <li class="treeview <?php if($menu == 'emp' || $menu == 'empG' || $menu == 'OutS') echo 'active'?>">
        <a href="#"><i class="fa fa-industry"></i> <span>Employee</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?php if($menu == 'empG') echo 'active'?>"><a href="<?php echo base_url('home/karyawan_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
          <li class="<?php if($menu == 'emp') echo 'active'?>"><a href="<?php echo base_url('home/karyawan'); ?>"><i class="fa fa-group"></i> <span>Employee Data</span></a></li>
          <li class="<?php if($menu == 'OutS') echo 'active'?>"><a href="<?php echo base_url('home/outSource'); ?>"><i class="fa fa-odnoklassniki"></i> <span>Outsource Employee</span></a></li>
          <li class="<?php if($menu == 'emp2') echo 'active'?>"><a href="<?php echo base_url('home/karyawan_2'); ?>"><i class="fa fa-odnoklassniki"></i> <span>Employee2</span></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($menu == 'pr' || $menu == 'prG') echo 'active'?>">
        <a href="#"><i class="fa fa-eye"></i> <span>Presence</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?php if($menu == 'prG') echo 'active'?>"><a href="<?php echo base_url('home/presensi_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
          <li class="<?php if($menu == 'pr') echo 'active'?>"><a href="<?php echo base_url('home/presensi'); ?>"><i class="fa fa-male"></i> <span>Presence Data</span></a>
          </ul>
        </li>
        <li class="treeview <?php if($menu == 'abs' || $menu == 'absG') echo 'active'?>">
          <a href="#"><i class="fa fa-edit"></i> <span>Absence</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if($menu == 'absG') echo 'active'?>"><a href="<?php echo base_url('home/absensi_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
            <li class="<?php if($menu == 'abs') echo 'active'?>"><a href="<?php echo base_url('home/absen'); ?>"><i class="fa fa-edit"></i> <span>Absence Data</span></a>
            </ul>
        </li>
        <li class="treeview <?php if($menu == 'ovr' || $menu == 'ovrR' || $menu == 'cc' || $menu == 'GAreport' || $menu == 'ovrG' || $menu == 'ovrR2' || $menu == 'OT-m' || $menu == 'ovrMo' || $menu == 'ovrMoC' || $menu == 'ovrMon') echo 'active'?>">
        <a href="#"><i class="fa fa-line-chart"></i> <span>Overtime</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?php if($menu == 'ovr') echo 'active'?>"><a href="<?php echo base_url('home/ot'); ?>"><i class="fa fa-male"></i> <span>Overtime Data</span></a></li>
          <li class="<?php if($menu == 'ovrG') echo 'active'?>"><a href="<?php echo base_url('home/ot_graph'); ?>"><i class="fa fa-bar-chart"></i> Graph</a></li>
          <li><a href="<?php echo base_url('home/budget_chart'); ?>"> <i class="fa fa-bar-chart"></i> Budget Total</a></li>
          <li><a href="<?php echo base_url('home/budget_chart_mp'); ?>"> <i class="fa fa-bar-chart"></i> Budget Total / MP</a></li>
          <li class="<?php if($menu == 'ovrMoC') echo 'active'?>"><a href="<?php echo base_url('home/monthlyC'); ?>"><i class="fa fa-bar-chart"></i> Monthly Control</a></li>
          <li class="<?php if($menu == 'ovrMon') echo 'active'?>"><a href="<?php echo base_url('home/monthlyMon'); ?>"><i class="fa fa-bar-chart"></i> Monthly Monitor</a></li>
          <li class="<?php if($menu == 'OT-m') echo 'active'?>"><a href="<?php echo base_url('home/ot_m'); ?>"><i class="fa fa-bar-chart"></i>Management Section</a></li>
          <li class="<?php if($menu == 'ovrR') echo 'active'?>"><a href="<?php echo base_url('home/ot_report'); ?>"><i class="fa fa-book"></i> Report</a></li>
          <li class="<?php if($menu == 'ovrR2') echo 'active'?>"><a href="<?php echo base_url('home/ot_report2'); ?>"><i class="fa fa-book"></i> Employee Monthly OT</a></li>
          <li class="<?php if($menu == 'ovrMo') echo 'active'?>"><a href="<?php echo base_url('home/monthly'); ?>"><i class="fa fa-book"></i> Monthly Summary</a></li>
          <li class="<?php if($menu == 'cc') echo 'active'?>"><a href="<?php echo base_url('budget'); ?>"><i class="fa fa-cc"></i>Cost - Center Budget</a></li>
          <li class="<?php if($menu == 'GAreport') echo 'active'?>"><a href="<?php echo base_url('home/report_GA'); ?>"><i class="fa fa-cubes"></i>GA - Report</a></li>
          </ul>
        </li>
          <li class="<?php if($menu == 'qa') echo 'active'?>"><a href="<?php echo base_url('home/tanya'); ?>"><i class="fa fa-comments-o"></i> <span>Q & A List</span></a></li>
          <li><a href="<?php echo base_url('home/ot_graph'); ?>">  </a></li>
          <!-- <li class="treeview">
            <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php //echo base_url('login'); ?>">Login</a></li>
              <li><a href="<?php// echo base_url('home/karyawan_coba'); ?>">Coba</a></li>
              <li><a href="<?php// echo base_url('home/sess_destroy'); ?>">Sess_destroy</a></li>
              <li><a href="<?php //echo base_url('home/overtime_user'); ?>">overtime_user</a></li>       
            </ul>
          </li> -->

        <?php }  else { ?>
          <li><a href="<?php echo base_url('home/budget_chart'); ?>"> Budget Total</a></li>
          <li><a href="<?php echo base_url('home/budget_chart_mp'); ?>"> Budget Total / MP</a></li>
          <!-- <li class="<?php if($menu == 'empG') echo 'active'?>"><a href="<?php echo base_url('home/karyawan_graph'); ?>"><i class="fa fa-bar-chart"></i> Employee Graph</a></li>
          <li class="<?php if($menu == 'prG') echo 'active'?>"><a href="<?php echo base_url('home/presensi_graph'); ?>"><i class="fa fa-bar-chart"></i> Presence Graph</a></li>
          <li class="<?php if($menu == 'absG') echo 'active'?>"><a href="<?php echo base_url('home/absensi_graph'); ?>"><i class="fa fa-bar-chart"></i> Absence Graph</a></li>
          <li class="<?php if($menu == 'ovrG') echo 'active'?>"><a href="<?php echo base_url('home/ot_graph'); ?>"><i class="fa fa-bar-chart"></i> Overtime Graph</a></li> -->
          <li class="<?php if($menu == 'ovrU') echo 'active'?>"><a href="<?php echo base_url('home/overtime_user'); ?>"><i class="fa fa-line-chart"></i> <span>overtime_user</span></a></li> 
        <?php } ?>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>