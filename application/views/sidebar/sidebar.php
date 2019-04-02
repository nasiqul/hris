<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <?php if (!isset($i) && !isset($z)): ?>
      <li class="header">MAIN</li>
      <!-- Optionally, you can add icons to the links -->
      <li class="<?php if($menu2 == 'home') echo 'active'?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>HOME</span></a></li>
    <?php endif ?>

    <?php if ($this->session->userdata('nik')) { 
      $username = $this->session->userdata('nik');
      ?>
      <li class="treeview <?php if($menu2 == 'Employee') echo 'active' ?>">
        <a href="#"><i class="fa fa-industry"></i> <span>Employee</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php 
          $sidebar1 = $this->home_model->getMenu2($username, "Employee");
          foreach ($sidebar1 as $key2) { 
            if($menu == $key2->nama_menu) $set = 'active'; else $set = '';
            echo '<li class="'.$set.'"><a href="'.base_url($key2->url).'"><i class="'.$key2->icon.'"></i>'.$key2->nama_menu.'</a></li>';
          }  ?>
        </ul>
      </li>
      <li class="treeview <?php if($menu2 == 'Presence') echo 'active' ?>">
        <a href="#"><i class="fa fa-eye"></i> <span>Presence</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php 
          $sidebar2 = $this->home_model->getMenu2($username, "Presence");
          foreach ($sidebar2 as $key3) { 
            if($menu == $key3->nama_menu) $set = 'active'; else $set = '';
            echo '<li class="'.$set.'"><a href="'.base_url($key3->url).'"><i class="'.$key3->icon.'"></i>'.$key3->nama_menu.'</a></li>';
          }  ?>
        </ul>
      </li>
      <li class="treeview <?php if($menu2 == 'Absence') echo 'active' ?>">
        <a href="#"><i class="fa fa-edit"></i> <span>Absence</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php 
          $sidebar3 = $this->home_model->getMenu2($username, "Absence");
          foreach ($sidebar3 as $key4) { 
            if($menu == $key4->nama_menu) $set = 'active'; else $set = '';
            echo '<li class="'.$set.'"><a href="'.base_url($key4->url).'"><i class="'.$key4->icon.'"></i>'.$key4->nama_menu.'</a></li>';
          }  ?>
        </ul>
      </li>
      <li class="treeview <?php if($menu2 == 'Overtime') echo 'active' ?>">
        <a href="#"><i class="fa fa-line-chart"></i> <span>Overtime</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <?php 
          $sidebar4 = $this->home_model->getMenu2($username, "Overtime");
          foreach ($sidebar4 as $key5) { 
            if($menu == $key5->nama_menu) $set = 'active'; else $set = '';
            echo '<li class="'.$set.'"><a href="'.base_url($key5->url).'"><i class="'.$key5->icon.'"></i>'.$key5->nama_menu.'</a></li>';
          }  ?>
        </ul>
      </li>
      <li class="<?php if($menu == 'qa') echo 'active'?>"><a href="<?php echo base_url('home/tanya'); ?>"><i class="fa fa-comments-o"></i> <span>Q & A List</span></a></li>
      <li><a href="<?php echo base_url('home/ot_graph'); ?>">  </a></li>

        <!-- UNTUK MANAGEMENT DARI MIRAI -->
        
        <?php } else if(isset($i)) { ?>
          <li class="header">Manpower Overtime</li>
          <li class="<?php if($menu == 'ovrMoC') echo 'active'?>"><a href="<?php echo base_url('management'); ?>"><i class="fa fa-bar-chart"></i>Monthly Overtime Control</a></li>
          <li class="<?php if($menu == 'ovrMon') echo 'active'?>"><a href="<?php echo base_url('management/monthlyMon'); ?>"><i class="fa fa-bar-chart"></i>Overtime Monthly Monitor</a></li>
          <li class="<?php if($menu == 'OT-m') echo 'active'?>"><a href="<?php echo base_url('management/ot_m'); ?>"><i class="fa fa-bar-chart"></i>OT Management By Section</a></li>
          <li class="<?php if($menu == 'ovrR2') echo 'active'?>"><a href="<?php echo base_url('management/ot_report2'); ?>"><i class="fa fa-book"></i>OT Management By NIK</a></li>
          <li class="<?php if($menu == 'ovrMo') echo 'active'?>"><a href="<?php echo base_url('management/monthly'); ?>"><i class="fa fa-book"></i> Monthly Overtime Summary</a></li>

        <?php } else if(isset($z)) { ?>
          <li class="header">ManPower Information</li>
          <li class="<?php if($menu == 'empG1') echo 'active'?>"><a href="<?php echo base_url('management_mp/'); ?>"><i class="fa fa-bar-chart"></i>Manpower By Status Kerja</a></li>
          <li class="<?php if($menu == 'empG2') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_gender'); ?>"><i class="fa fa-bar-chart"></i>Manpower By Gender</a></li>
          <li class="<?php if($menu == 'empG3') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_grade'); ?>"><i class="fa fa-bar-chart"></i>Manpower By Grade</a></li>
          <li class="<?php if($menu == 'empG4') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_dept'); ?>"><i class="fa fa-bar-chart"></i>Manpower By Departemen</a></li>
          <li class="<?php if($menu == 'empG5') echo 'active'?>"><a href="<?php echo base_url('management_mp/karyawan_graph_jabatan'); ?>"><i class="fa fa-bar-chart"></i>Manpower By Jabatan</a></li>
          <li class="header">Attendance Information</li>
          <li class="<?php if($menu == 'home') echo 'active'?>"><a href="<?php echo base_url('management_mp/attendance'); ?>"><i class="fa fa-bar-chart"></i> Presence by Month</a></li>
          <li class="<?php if($menu == 'prG') echo 'active'?>"><a href="<?php echo base_url('management_mp/presensi'); ?>"><i class="fa fa-bar-chart"></i> Presence</a></li>
          <li class="<?php if($menu == 'absG') echo 'active'?>"><a href="<?php echo base_url('management_mp/absen'); ?>"><i class="fa fa-bar-chart"></i> Absence</a></li>

        <?php }  else { ?>
          <li class="<?php if($menu == 'ovrU') echo 'active'?>"><a href="<?php echo base_url('home/overtime_user'); ?>"><i class="fa fa-line-chart"></i> <span>overtime_user</span></a></li> 
        <?php } ?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>