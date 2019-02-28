<header class="main-header">

  <!-- Logo -->
  <a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>M I S</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b style="font-size: 37px">M I R A I</b></span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <?php if ($this->session->userdata('nik')) { ?>

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img id="fotoHead2" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php $nik2 = $this->session->userdata('nik'); echo $nik2;?> </span>&nbsp<i class="fa fa-sort-down"> </i>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url()?>app/img/image-user.png" class="img-circle" alt="User Image">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('home/logout'); ?>" class="btn btn-danger btn-flat">Sign out &nbsp<i class="fa fa-sign-out"></i></a>
                </div>
              </li>
            </ul>
          </li>
        <?php }  else { ?> 
          <li class="user user-menu"><a href="<?php echo base_url('login'); ?>"><i class="fa fa-lock"></i> Login</a></li>
          <?php } ?>
        </ul>
      </div>
    </nav>
  </header>
  <script type="text/javascript">
    $("#fotoHead2").attr("src","<?php echo base_url() ?>app/img/image-user.png");
  </script>