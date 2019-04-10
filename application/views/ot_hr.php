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
          Overtime Data - HR
          (???)
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-search"></i> <span>Search Filter</span></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="<?php echo base_url('home/presensi'); ?>" method="POST">
              <div class="box-body">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputTanggal"><i class="fa fa-calendar"></i> <span>Date</span></label>
                    <input type="text" name="tanggal" id="tanggal" class="form-control datepicker" placeholder="Select date">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputNik"><i class="fa fa-id-badge"></i>  <span>Section</span><!--  <b id="namadept2"> </b> --></label>
                    <select name="dep" class="form-control" id="dep1" onchange='showSec();namadept()'>
                      <option value="" disabled selected>Select Section</option>
                      <?php 
                      foreach ($dep as $key) {
                        echo "<option value='".$key->id."' name='".$key->id_departemen."'>".$key->nama."</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="inputNama"><i class="fa fa-user"></i> <span>Sub Section</span></label>
                    <select name="sec" class="form-control" id="sec1" onchange='showSubSec()'>
                      <option value="" disabled selected>Select Sub Section</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputShift"><i class="fa fa-briefcase "></i> <span>Group</span></label>
                    <select name="subsec" class="form-control" id="subsec1">
                      <option value="" disabled selected>Select Group</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a class="btn btn-primary" onclick="tanggal()"><i class="fa fa-search"></i> <span>Search</span></a>
              </div>
            </form>
          </div>
          <!-- /.box -->

        </div> 

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
                <h4 class="modal-title">PT. YAMAHA MUSICAL PRODUCTS INDONESIA</h4>
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
                      <p>: <c id="sec"></c> - <c id="subsec"></c> - <c id="group"></p>
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

          <div class="modal fade" id="myModal2">
            <div class="modal-dialog modal-sm">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title">Yakin hapus "<b id="id2"></b>" ?</h3>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-danger pull-left" onclick="hapus()"><i class="fa fa-trash"></i> Hapus</button>
                  <button type="button" class="btn btn-warning pull-right" data-dismiss="modal"><i class="fa fa-mail-forward"></i> Tidak</button>
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
     tabel2 = $('#example1').DataTable();
     tabel2.destroy();

     tabel2 = $('#example1').DataTable({
      "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "processing"    : true,
      "serverSide"    : true,
      'order'         : [],
      "ajax": {
        "url": "<?php echo base_url('ot/ajax_ot_hr')?>",
        "type": "GET",
        "data" : { 
          sub:'asd',
          subsec:'asd',
          group:'asd'
        }
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
          $("#sec").text(s[0][3]);
          $("#subsec").text(s[0][4]);
          $("#group").text(s[0][7]);
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

   function tanggal() {
     tabel2 = $('#example1').DataTable();
     tabel2.destroy();
     var tanggal = $('#tanggal').val();
     var sub = $('#dep1').find(':selected')[0].value;
     var subsec = $('#sec1').find(':selected')[0].value;
     var group = $('#subsec1').find(':selected')[0].value;

     tabel2 = $('#example1').DataTable({
      "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "processing"    : true,
      "serverSide"    : true,
      'order'         : [],
      "ajax": {
        "url": "<?php echo base_url('ot/ajax_ot_hr')?>",
        "type": "GET",
        'data' : { tanggal : tanggal,
          sub:sub,
          subsec:subsec,
          group:group
        }
      }
    });


   }

   $('.datepicker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd"
  });

   function namadept() {
    var id = $('#dep1').find('option:selected').attr("name");
      //alert(id);
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_namadept") ?>',
        data: {
          'id': id
        },
        success: function (data) {
           // alert(data)
           var s = $.parseJSON(data)
           $('#namadept2').text(s +" - Departemen");
         }
       });
    }

    function showSec() {
      var id = $('#dep1').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_section") ?>',
        data: {
          'id': id
        },
        success: function (data) {
            // the next thing you want to do 
            var $section = $('#sec1');

            $section.empty();

            var s = $.parseJSON(data);

            $section.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

            for (var i = 1; i <= s.length; i++) {
              $section.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }
            
            //manually trigger a change event for the contry so that the change handler will get triggered
            $section.change();
          }
        });
    }

    function showSubSec() {
      var id = $('#sec1').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_subsection") ?>',
        data: {
          'id': id
        },
        success: function (data) {
            // the next thing you want to do 
            var $subsec = $('#subsec1');

            $subsec.empty();

            var s = $.parseJSON(data);

            $subsec.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

            for (var i = 1; i <= s.length; i++) {
              $subsec.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }
            
            //manually trigger a change event for the contry so that the change handler will get triggered
            $subsec.change();
          }
        });
    }


    function modal_open(id) {
      $('#myModal2').modal({backdrop: 'static', keyboard: false});
      $('#id2').text(id);
    }

    function hapus() {
      var id = $('#id2').text();

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("ot/delete_ot") ?>',
        data: {
          'id': id
        },
        success: function (data) {
          openDeleteGritter(id);
          $('#myModal2').modal('hide');

          tabel2 = $('#example1').DataTable();
          tabel2.destroy();

          tabel2 = $('#example1').DataTable({
            "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "processing"    : true,
            "serverSide"    : true,
            'order'         : [],
            "ajax": {
              "url": "<?php echo base_url('ot/ajax_ot_user')?>",
              "type": "GET",
              "data" : { 
                sub:'asd',
                subsec:'asd',
                group:'asd'
              }
            }
          });
        }
      });
    }


    function openDeleteGritter(id){
      jQuery.gritter.add({
        title: "Berhasil",
        text: "Hapus data "+id+" berhasil",
        class_name: 'growl-danger',
        image: '<?php echo base_url()?>app/img/close.png',
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