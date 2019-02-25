<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
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
              <table id="example1" class="table table-responsive table-striped table-bordered text-center" width="100%">
                <thead>
                  <tr>
                    <th>ID SPL</th>
                    <th>Date</th>
                    <th>NIK</th>
                    <th width="20%">Nama</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Lembur (jam)<br>Plan</th>
                    <th>Lembur (jam)<br>Actual</th>
                    <th>Diff</th>
                    <th>Final</th>
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
                <button class="btn btn-primary" onclick="tombol_print()"><i class="fa fa-print"></i> Print</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal modal-info fade" id="myModal2">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h4 style="float: right;" id="modal-title"></h4>
                <h4 class="modal-title">Confirm OK?</h4>
              </div>
              <div class="modal-body">
                <p id="tgl2"></p>
                <h4>Yakin Konfirmasi "<c id='id_ot'></c>" ? </h4>
                <p id="tot"></p>
              </div>
              <div class="modal-footer">
                <button class="btn btn-success pull-left" data-dismiss="modal" onclick="ok()"><i class="fa fa-thumbs-up"></i> OK</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Cancel</button>
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
    $(document).ready(function() {
      var table = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        "bInfo": false,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('ot/ajax_ot')?>",       
          "type": "GET"
        },
        "columnDefs": [
        {
              "targets": [ 10 ], //first column / numbering column
              "orderable": false, //set not orderable
            }
            ],
          });
    });

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

    function modalOpen(id,jam_lembur, tgl) {
      $('#myModal2').modal({backdrop: 'static', keyboard: false});
      $('#id_ot').text(id);
      $('#tgl2').text(tgl);
      $('#tot').text(jam_lembur);
    }

    function ok() {
      var nik = $('#id_ot').text();
      var tgl2 = $('#tgl2').text();
      var tot = $('#tot').text();
      $.ajax({
        url: "<?php echo base_url('ot/acc/')?>",
        type : "POST",
        data: {
          nik:nik,
          tgl:tgl2,
          tot:tot
        },
        success: function(data){
          $('#conf').text(tgl);
          openSuccessGritter();
        }
      })
    }


    function openSuccessGritter(){
      jQuery.gritter.add({
        title: "Success",
        text: "Confirmation Success",
        class_name: 'growl-success',
        image: '<?php echo base_url()?>app/img/ok.png',
        sticky: false,
        time: '2000'
      });
    }

    function jam(id,jam,nik) {
      var newdiv1 = $('<input type="text" id="txtPlan'+id+'" class="form-control" value="'+jam+'"/><button class"btn btn-default" onclick="applyJam('+id+','+nik+')" >ok</button>');

      $('#'+id).text('').append(newdiv1);

      $('#txtPlan').focus();
    }

    $('#txtPlan').bind("enterKey",function(e){
      alert("asd");
    });

    $('#txtPlan').keydown(function(e){
      if(e.keyCode == 13)
      {
        $(this).trigger("enterKey");
      }
    });

    function applyJam(id,nik,jam) {
      $.ajax({
        url: "<?php echo base_url('ot/changeJam/')?>",
        type : "POST",
        data: {
          nik:nik,
          id:id,
          jam:jam
        },
        success: function(data){
          document.getElementById("c"+nik+id).style.display = "none";
          document.getElementById("d"+nik+id).style.display = "none";
          openOKGritter();
          document.getElementById("f"+nik+id).innerHTML = jam;
        }
      })
    }

    function openOKGritter(){
      jQuery.gritter.add({
        title: "Berhasil",
        text: "Perubahan jam final berhasil",
        class_name: 'growl-success',
        image: '<?php echo base_url()?>app/img/icon.png',
        sticky: false,
        time: '2000'
      });
    }

  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>