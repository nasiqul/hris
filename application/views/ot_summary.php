<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nik')) { redirect('home/overtime_user'); }?>

<body class="hold-transition skin-purple sidebar-mini">
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
          Monthly Overtime Summary 
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="row">
                <div class="col-md-4">
                  <label>Period : </label>
                  <input type="text" class="form-control text-muted datepicker" placeholder="Select Month" id="bulan" onchange="postTgl();" name="tgl">
                </div>
              </div>
              <br>
              <table id="example1" class="table table-responsive table-striped" style="width: 100%">
                <thead>
                  <tr>
                    <th>Period</th>
                    <th>CC Name</th>
                    <th>Overtime Total <br> (A)</th>
                    <th>Emp. Total <br> (B)</th>
                    <th>OT (AVG) <br> (A/B)</th>
                    <th>OT (min)</th>
                    <th>OT (max)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>   
        </div>

      </section>
      <!-- /.content -->
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
    $(document).ready(function() {
      var tabel1 = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('ot/ajax_summary')?>",            
          "type": "POST"
        }
      })
    })

    function postTgl() {
      var tabel1 = $('#example1').DataTable();
      var tgl = $('#bulan').val();
      tabel1.destroy();
      tabel1 = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('ot/ajax_summary')?>",            
          "type": "POST",
          data: {
            tgl:tgl
          }
        }
      })
    }

    $('.datepicker').datepicker({
      autoclose: true,
      viewMode: "months", 
      minViewMode: "months",
      format: "mm-yyyy"
    });
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>