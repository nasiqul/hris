<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- NAVBAR -->
  <?php require_once(APPPATH.'views/header/navbar.php'); ?>
  <!-- SIDEBAR -->
  <?php require_once(APPPATH.'views/sidebar/sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
              <div class="box box-primary">
                <div class="form-group">
                    <label>Date :</label>

                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="myInputTextField">
                    </div>
                    <!-- /.input group -->
                    <label>Nama :</label>

                    <div class="input-group date">
                      <input type="text" class="form-control pull-right" id="myInputTextField2">
                    </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
      <!-- <form>
        <input class="form-control" type="date" name="date">
      </form> -->

      <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr><td>Tanggal</td><td>NIK</td><td>Nama</td><td>Datang</td><td>Pulang</td><td>Status</td><td>Shift</td></tr>
        </thead>
        <tbody>
          <?php error_reporting(0); ?>
              <?php foreach ($presensi as $key) : 
                  $tgl = explode(" ", $key['scan_date']);
                  $jam = explode("_", $key['date']);
                ?>
                <tr>
                  <td><?php echo "2019-01-07"; ?></td>
                  <td><a data-toggle="modal" href="#myModal" data-userid="<?php echo $key['nama']; ?>"><?php echo $key['nik']; ?></a></td>
                  <td><?php echo $key['nama']; ?></td>
                  <td><?php if (($jam[1] - $jam[0]) > 15) echo $jam[1]; else echo $jam[0]; ?></td>
                  <td>
                    <?php if (count($jam) > 1) 
                        echo end($jam); ?>
                  </td>
                  <td>
                    -
                  </td>
                  <td>
                    <?php 
                      $d = date('H:i',strtotime($jam[0]));
                      if ($d >= 5 && $d <= 9)
                        echo "Shift 1";
                      else if ($d >= 11 && $d <= 18)
                        echo "Shift 2";
                      else if ($d >= 20 && $d <= 23)
                        echo "Shift 3";
                    ?>
                    
                  </td>
                </tr>
              <?php endforeach; ?> 
        </tbody>
      </table>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>

  $(document).ready(function() {
    var table = $('#example1').DataTable({
      'serverside':true,
      'processing':true
    });
     
   $('#myInputTextField').keyup(function(){
      table
        .columns( 0 )
        .search( this.value )
        .draw();
    });

   $('#myInputTextField2').keyup(function(){
      table
        .columns( 2 )
        .search( this.value )
        .draw();
    });

  });
</script>
<?php require_once(APPPATH.'views/footer/foot.php'); ?>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>