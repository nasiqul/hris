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
          Presence Data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-search"></i> <span>Filter Pencarian</span></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="<?php echo base_url('home/presensi'); ?>" method="POST">
              <div class="box-body">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputTanggal"><i class="fa fa-calendar"></i> <span>Tanggal</span></label>
                    <input type="date" class="form-control" name="tanggal" id="inputTanggal" 
                    <?php if(isset($_SESSION['tanggal'])) echo 'value="'.$_SESSION['tanggal'].'"';?>>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputNik"><i class="fa fa-id-badge"></i> <span>NIK</span></label>
                    <input type="text" class="form-control" name="nik" id="inputNik" placeholder="NIK"
                    <?php if(isset($_SESSION['nik'])) echo 'value="'.$_SESSION['nik'].'"';?>>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="inputNama"><i class="fa fa-user"></i> <span>Nama</span></label>
                    <input type="text" class="form-control" name="nama" id="inputNama" placeholder="Nama"
                    <?php if(isset($_SESSION['nama'])) echo 'value="'.$_SESSION['nama'].'"';?>>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputShift"><i class="fa fa-briefcase "></i> <span>Shift</span></label>
                    <input type="text" class="form-control" name="shift" id="inputShift" placeholder="Shift"
                    <?php if(isset($_SESSION['shift'])) echo 'value="'.$_SESSION['shift'].'"';?>>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> <span>Cari</span></button>
                <a class="btn btn-warning" id="reset" href="<?php echo base_url('home/session_destroy') ?>" >Reset</a>

              </div>
            </form>
          </div>
          <!-- /.box -->

        </div> 
        <div class="col-md-12">
          <table id="example1" class="table table-responsive table-striped">
            <thead>
              <th>Tanggal</th>
              <th>NIK</th>
              <th>Nama</th>
              <th>Datang</th>
              <th>Pulang</th>
              <th>Shift</th>
            </thead>        
            <tbody>
            </tbody>
          </table>
        </div>   

      </section>
      <!-- /.content -->

    <!-- <div class="modal fade" id="myModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                <input type="text" name="user_id" value="">
                <p>One fine body&hellip;</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
            /.modal-content -->
            <!-- </div> -->
            <!-- /.modal-dialog -->
            <!-- </div> --> 
          </div>
          <!-- /.content-wrapper -->
          <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
    <?php require_once(APPPATH.'views/footer/foot.php'); ?>
  </div>
  <!-- ./wrapper -->
  <script>
    var table;
    $(document).ready(function() {
      table = $('#example1').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true,
        "serverSide": true,
        "bInfo": false,
        "order": [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax')?>",            
          "type": "POST"
        }
      });

    })
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>