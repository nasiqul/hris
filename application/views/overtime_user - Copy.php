<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>

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
          Overtime Data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">

        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <a class="btn btn-success" href="<?php echo base_url('home/overtime_form') ?>"> <i class="fa fa-plus"></i> New Entry</a>
              <br>
              <br>
              <table id="example1" class="table table-responsive table-striped">
                <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>No. SPL</th>
                    <th>Tanggal</th>
                    <th width="10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal fade" id="myModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 style="float: right;" id="modal-title"></h4>
                <h4 class="modal-title">PT. YAMAHA MUSICAL PRODUCT INDONESIA</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="col-md-4">
                      <p>Hari</p>
                      <p>Tanggal</p>
                      <p>Bagian</p>
                    </div>
                    <div class="col-md-8">
                      <p>: <c id="hari"></c></p>
                      <p>: <c id="tgl"></c></p>
                      <p>: <c id="dep"></c> - <c id="sec"></c></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <p>Keperluan : </p>
                    <input type="text" class="form-control" readonly id="kep" style="height:70px;">
                  </div>

                  <div class="col-md-12">
                    <br>
                    <table class="table table-hover" id="example2" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>NIK</th>
                          <th>Nama</th>
                          <th>Dari</th>
                          <th>Sampai</th>
                          <th>Jam</th>
                          <th>Trans</th>
                          <th>Makan</th>
                          <th>E.Food</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="4"><b>B = Bangil, P = Pasuruan</b></td>
                          <td><c class="pull-right">Total :</c></td>
                          <td class="text-center"></td>
                          <td>Jam</td>
                        </tr>
                      </tfoot>
                    </table>
                    <p>Catatan :</p>
                    <input type="text" class="form-control" readonly id="cat" style="height:70px;">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <a id="exportid" href="<?php echo base_url('ot/createXLS/19030008') ?>" class="btn btn-warning">Export to Excel</a>
                <button class="btn btn-primary" onclick="tombol_print()"><i class="fa fa-print"></i> Print</button>
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
          "url": "<?php echo base_url('ot/ajax_ot_user')?>",
          "type": "GET"
        }
      });
    })

    function detail_spl(id) {
      tabel = $('#example2').DataTable();
      tabel.destroy();
      $.ajax({
        url: "<?php echo base_url('ot/ajax_spl_data/')?>",
        type : "POST",
        data: {
          id:id
        },
        success: function(data){
          var s = $.parseJSON(data);

          $('#myModal').modal('show');
          $("#modal-title").text("No : " + s[0][0]);
          var id2 = s[0][0];
          
          $("#hari").text(s[0][1]);
          $("#tgl").text(s[0][2]);
          $("#dep").text(s[0][3]);
          $("#sec").text(s[0][4]);
          $("#kep").val(s[0][5]);
          $("#cat").val(s[0][6]);


          $('#example2').DataTable({
            "processing"    : true,
            "serverSide"    : true,
            "searching": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            'order'         : [],
            "ajax": {
              "url": "<?php echo base_url('ot/ajax_spl_data2')?>",       
              "type": "GET",
              'async' : false,
              'data' : { id : id2 }
            },
            "columns": [
            { "data": 0 },
            { "data": 3 },
            { "data": 4 },
            { "data": 5 },
            { "data": 6 },
            { "data": 7 },
            { "data": 8 },
            { "data": 9 },
            { "data": 10 }
            ],
            "columnDefs": [
            {
              "targets": [ 5,6,7,8 ], //first column / numbering column
              "orderable": false, //set not orderable
            }
            ],
            "footerCallback": function (tfoot, data, start, end, display) {
              var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$%,]/g, '')*1 :
                typeof i === 'number' ?
                i : 0;
              };
              var api = this.api();
              var totalPlan = api.column(5).data().reduce(function (a, b) {
                return intVal(a)+intVal(b);
              }, 0)
              $(api.column(5).footer()).html(totalPlan.toLocaleString());
            }
          })
        }
      });
    }

    function tombol_print() {
      var str = $("#modal-title").text();
      var tanggal = $("#tgl").text();
      var s = str.split(" ");
      var url = "<?php echo base_url('ot/print_preview/'); ?>"+s[2]+"/"+tanggal;

      window.open(url,'_blank');
    }

    function exporta($id) {
    var id = $id;      
     var url = "<?php echo base_url('ot/exportexcel/'); ?>"+id;
     // alert(url)
      $("#exportid").prop("href", url)
    }

    $('.datepicker').datepicker({
      autoclose: true,
      format: "dd-mm-yyyy"
    });

  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>