<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<link rel="stylesheet" href="<?php echo base_url()?>app/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<?php if (! $this->session->userdata('nikLogin')) { redirect('home/overtime_user'); }?>

<style type="text/css">
  #info {
    text-decoration: underline;
  }
  #info:hover {
    text-decoration:none;
  }
</style>

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
          Overtime Report
          <span class="text-purple">残業報告</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">

        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <label>Export per Hari:</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input onchange="exporta();" type="text" name="tgl" id="tgl" class="form-control datepicker" placeholder="select date">
                    <span class="input-group-btn">
                      <a class="btn btn-warning btn-md" id="exportid" href=""><i class="fa fa-save"></i> &nbsp;Export</a>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Export Date range:</label>

                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="daterange" onchange="getexportDate()">
                      <span class="input-group-btn">
                        <a class="btn btn-warning btn-md" id="exportrange" href=""><i class="fa fa-save"></i> &nbsp;Export</a>
                      </span>
                    </div>
                    <!-- /.input group -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 style="float: right;" id="modal-title"></h4>
                <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    NIK : <c id="nik"></c><br>
                    Nama : <c id="nama"></c><br>
                    Bulan : <c id="bulan"></c>
                    <table class="table table-striped table-responsive" style="width: 100%" id="example2">
                      <thead>
                        <tr>
                          <th>Tanggal</th>
                          <th>jam Lembur</th>
                          <th>Satuan</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
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
    var table;
    $(document).ready(function() {

      table = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('ot/ajax_ot_report')?>",
          "type": "GET"
        },
        "columnDefs": [
        {
          "targets": [ 4,5,6 ], //first column / numbering column
          "orderable": false, //set not orderable
        }
        ],
      });

      $('#daterange').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
        }
      });
    })

    function detail(nik,tgl,nama) {
      $('#nik').text(nik);
      $('#nama').text(nama);
      $('#bulan').text(tgl);
      table2 = $('#example2').DataTable();
      table2.destroy();
      //alert(nik+" "+tgl);
      $("#myModal").modal('show');
      table2 = $('#example2').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        "searching": false,
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('ot/ajax_ot_report_details')?>",
          "type": "GET",
          "data" : {
            nik : nik,
            period : tgl,
          }
        }
      });
    }

    function exporta() {
     var tgl = $("#tgl").val();
     var url = "<?php echo base_url('ot/exportexcelhr/'); ?>"+tgl;
     $("#exportid").prop("href", url);
   }

   function getexportDate() {
     var dt = $("#daterange").val();
     var date = dt.split(" - ");

     var url = "<?php echo base_url('ot/exportexcelsplrange/'); ?>"+date[0]+"/"+date[1];
     $("#exportrange").prop("href", url);
     console.log(url);

   }

   $('.datepicker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd"
  });


</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>