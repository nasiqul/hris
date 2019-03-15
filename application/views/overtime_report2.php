<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nik')) { redirect('home/overtime_user'); }?>

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
              <table id="example1" class="table table-responsive table-striped" style="width: 100%">
                <thead>
                  <tr>
                    <th>Period</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Bagian</th>
                    <th>Total</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 style="float: right;" id="modal-title"></h4>
                <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
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
                        </tr>
                      </thead>
                      <tbody></tbody>
                      <tfoot style="background-color: blue">
                        <th>Total</th>
                        <th></th>
                      </tfoot>
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
          "url": "<?php echo base_url('ot/ajax_ot_report_d')?>",
          "type": "GET"
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
        },
        "columns": [
        { "data": 2 },
        { "data": 3 },
        { "data": 4 }
        ],
      });
    }


    function detail2(nik,tgl,nama) {
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
          "url": "<?php echo base_url('ot/ajax_ot_report_details_2')?>",
          "type": "GET",
          "data" : {
            nik : nik,
            period : tgl,
          }
        },
        "columnDefs" : [
        {
          'targets': 1,
          'createdCell':  function (td, cellData, rowData, row, col) {
           $(td).attr('id', 'ct'); 
         }
       }],
       "footerCallback": function (tfoot, data, start, end, display) {
        var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$%,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
        };
        var api = this.api();
        var totalPlan = api.column(1).data().reduce(function (a, b) {
          return intVal(a)+intVal(b);
        }, 0)
        $(api.column(1).footer()).html(totalPlan.toLocaleString());
      }
    });
    }

  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>