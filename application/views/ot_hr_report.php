<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nikLogin')) { redirect('home/overtime_user'); }?>

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
          HR - Report Overtime
          <span class="text-purple">???</span>

          <div class="col-md-2 pull-right">
            <div class="input-group date">
              <div class="input-group-addon bg-green" style="border-color: green">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control datepicker" id="tgl" onchange="buattable()" placeholder="Select date" style="border-color: green">
            </div>
          </div>
          <div class="pull-right">
            <button class="btn btn-warning btn-md pull-right" id="exportid" href="" onclick="exportData('tgl')">Export by Date</button>
            <button class="btn btn-warning btn-md pull-right" id="exportid" style="margin-right: 5px" href="" onclick="exportData('nik')">Export by NIK</button>

          </div>

        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">

          <div class="box box-solid">
            <div class="box-body">
              <table id="example1" class="table table-responsive table-striped">
                <thead>
                  <tr>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Bagian</th>
                    <th>Aktual</th>
                    <th>Satuan</th>
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
    var tabel = $('#example1').DataTable();
    $(document).ready(function() {
      buattable();
    })

    function buattable() {
      tabel.destroy();
      var tgl = $("#tgl").val();
      tabel = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('ot/ajax_hr_data')?>",            
          "type": "GET",
          "data" : {
            "tgl" : tgl
          }
        },
        "columnDefs": [
        {
          "targets": [ 3 ], //first column / numbering column
          "orderable": false, //set not orderable
        }]
      })
    }

    function exportData(func) {
      if($("#tgl").val())
      {
        var tgl = $("#tgl").val();
      }
      else {
        var tgl = 1;
      }

      if (func == 'tgl') {
        var url = "<?php echo base_url('import_excel/exportOvertime/') ?>"+ tgl;
      } else if (func == 'nik') {
        var url = "<?php echo base_url('import_excel/exportOvertime2/') ?>"+ tgl;
      }

      window.location.replace(url);
      // alert(url);
    }

    $('.datepicker').datepicker({
     autoclose: true,
     format: "mm-yyyy",
     viewMode: "months",
     minViewMode: "months"
   });
 </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>