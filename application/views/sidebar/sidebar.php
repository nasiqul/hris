<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN</li>
      <?php if (!isset($i) && !isset($z) && $this->session->userdata('role') == '1'): ?>
      <!-- Optionally, you can add icons to the links -->
      <li class="<?php if($menu2 == 'home') echo 'active'?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>HOME</span></a></li>
    <?php endif ?>

    <?php if ($this->session->userdata('nikLogin') && !isset($i) && !isset($z)) { 
      $username = $this->session->userdata('nikLogin');

      foreach ($parentMenu as $kunci) { ?>

        <li class="treeview <?php if($menu2 == $kunci->parent_menu) echo 'active' ?>">
          <a href="#"><i class="fa fa-cubes"></i> <span><?php echo $kunci->parent_menu ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php 
            $sidebar2 = $this->home_model->getMenu2($username, $kunci->parent_menu);
            foreach ($sidebar2 as $key3) { 
              if($menu == $key3->nama_menu) $set = 'active'; else $set = '';
              echo '<li class="'.$set.'"><a href="'.base_url($key3->url).'"><i class="'.$key3->icon.'"></i>'.$key3->nama_menu.'</a></li>';
            }  ?>
          </ul>
        </li>
        
      <?php } ?>

      <!-- UNTUK MANAGEMENT DARI MIRAI -->

    <?php } else if(isset($i)) { ?>
      <li class="header">Manpower Overtime</li>
      <li class="<?php if($menu == 'OT-new') echo 'active'?>"><a href="<?php echo base_url('management'); ?>"><i class="fa fa-bar-chart"></i><span>Monthly Overtime Control</span></a></li>
      <li class="<?php if($menu == 'ovrMon') echo 'active'?>"><a href="<?php echo base_url('management/monthlyMon'); ?>"><i class="fa fa-bar-chart"></i><span>Overtime Monthly Monitor</span></a></li>
      <li class="<?php if($menu == 'OT-m') echo 'active'?>"><a href="<?php echo base_url('management/ot_m'); ?>"><i class="fa fa-bar-chart"></i><span>OT Management By Section</span></a></li>
      <li class="<?php if($menu == 'ovrR2') echo 'active'?>"><a href="<?php echo base_url('management/ot_report2'); ?>"><i class="fa fa-book"></i><span>OT Management By NIK</span></a></li>
      <li class="<?php if($menu == 'ovrMo') echo 'active'?>"><a href="<?php echo base_url('management/monthly'); ?>"><i class="fa fa-book"></i><span>Monthly Overtime Summary</span></a></li>
      <li class="<?php if($menu == 'ovrMoC') echo 'active'?>"><a href="<?php echo base_url('management/overtime_control'); ?>"><i class="fa fa-bar-chart"></i><span>Overtime Control</span></a></li>

    <?php } else if(isset($z)) { ?>
      <li class="header">ManPower Information</li>
      <li class="<?php if($menu == 'empG1') echo 'active'?>"><a href="<?php echo base_url('management_mp/'); ?>"><i class="fa fa-bar-chart"></i><span>Manpower By Status Kerja</span></a></li>
      <li class="<?php if($menu == 'empG2') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_gender'); ?>"><i class="fa fa-pie-chart"></i><span>Manpower By Gender</span></a></li>
      <li class="<?php if($menu == 'empG3') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_grade'); ?>"><i class="fa fa-bar-chart"></i><span>Manpower By Grade</span></a></li>
      <li class="<?php if($menu == 'empG4') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_dept'); ?>"><i class="fa fa-bar-chart"></i><span>Manpower By Departemen</span></a></li>
      <li class="<?php if($menu == 'empG5') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_jabatan'); ?>"><i class="fa fa-bar-chart"></i><span>Manpower By Jabatan</span></a></li>
      <li class="header"></span>Attendance Information</span></li>
      <li class="<?php if($menu == 'home') echo 'active'?>"><a href="<?php echo base_url('management_mp/attendance'); ?>"><i class="fa fa-bar-chart"></i><span>Presence by Month</span></a></li>
      <li class="<?php if($menu == 'prG') echo 'active'?>"><a href="<?php echo base_url('management_mp/presensi'); ?>"><i class="fa fa-bar-chart"></i><span>Presence</span></a></li>
      <li class="<?php if($menu == 'absG') echo 'active'?>"><a href="<?php echo base_url('management_mp/absen'); ?>"><i class="fa fa-bar-chart"></i><span>Absence</span></a></li>

    <?php } ?>
  </ul>
  <!-- /.sidebar-menu -->
</section>
<!-- /.sidebar -->
</aside>