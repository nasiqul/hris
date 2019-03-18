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
#trs th, #shf1 th, #shf2 th, #shf3 th {
  background-color: #ddd;
}
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
          GA - Report
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">

        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <form class="form-horizontal">
                <div class="form-group">
                  <label for="datepicker" class="col-sm-2 control-label">Tanggal</label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="datepicker" placeholder="Select date" onchange="extrafood(); changeTanggal(); ">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Total Makan</label>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 1</th></tr>
                      <tr><td id='makan1' onclick="makan(1)">0</td></tr>
                    </table>
                  </div>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 2</th></tr>
                      <tr><td id='makan2' onclick="makan(2)">0</td></tr>
                    </table>
                  </div>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 3</th></tr>
                      <tr><td id='makan3' onclick="makan(3)">0</td></tr>
                    </table>
                  </div>
                </div>

                  <!-- ali -->
              <div class="form-group">
                  <label class="col-sm-2 control-label">Total Extra Food</label>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 1</th></tr>
                      <tr><td id='extmakan1' onclick="extmakan(1)">0</td></tr>
                    </table>
                  </div>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 2</th></tr>
                      <tr><td id='extmakan2' onclick="extmakan(2)">0</td></tr>
                    </table>
                  </div>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 3</th></tr>
                      <tr><td id='extmakan3' onclick="extmakan(3)">0</td></tr>
                    </table>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Transport</label>

                  <div class="col-sm-6">
                    <table class="table table-bordered table-striped text-center" id="trs">
                      <tr>
                        <th></th>
                        <th scope="col" width="30%">Bangil</th>
                        <th scope="col" width="30%">Pasuruan</th>
                      </tr>
                      <tbody id="head">

                      </tbody>
                    </table>
                  </div>
                </div>

              
              </form>
            </div>
          </div>
        </div>

         <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 style="float: right;" id="modal-title"></h4>
                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <table id="example2" class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                          <tr> 
                            <th>No</th>                           
                            <th>NIK</th>
                            <th>Nama karyawan</th>
                            <th>Departemen</th>
                            <th>Devisi</th>
                            <th>Section</th>
                            <th>Sub Section</th>
                            <th>Group</th>
                          </tr>
                        </thead>
                        <tbody id="makan1data">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
              </div>
            </div>
            <!-- /.modal-dialog -->
          </div>

          <div class="modal fade" id="myModal2">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 style="float: right;" id="modal-title"></h4>
                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <table id="example2" class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                          <tr> 
                            <th>No</th>                           
                            <th>NIK</th>
                            <th>Nama karyawan</th>
                            <th>Departemen</th>
                            <th>Devisi</th>
                            <th>Section</th>
                            <th>Sub Section</th>
                            <th>Group</th>
                          </tr>
                        </thead>
                        <tbody id="trans1data">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
              </div>
            </div>
            <!-- /.modal-dialog -->
          </div>

           <div class="modal fade" id="myModal3">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 style="float: right;" id="modal-title"></h4>
                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <table id="example2" class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                          <tr> 
                            <th>No</th>                           
                            <th>NIK</th>
                            <th>Nama karyawan</th>
                            <th>Departemen</th>
                            <th>Devisi</th>
                            <th>Section</th>
                            <th>Sub Section</th>
                            <th>Group</th>
                          </tr>
                        </thead>
                        <tbody id="extmakanfood">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
              </div>
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

      $('#datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy"
      });
    });

function makan(id) {
  $('#myModal').modal('show');
      var no = 1;
      var tanggal = $("#datepicker").val();
      $.ajax({
            url: "<?php echo base_url('ot/makan1/')?>",
            type : "POST",
            data: {
              tgl:tanggal,
              id:id
            },
            dataType: 'json',
            success: function(data){
              $("#makan1data").empty();
              $.each(data, function(i, item) {
                if ( item[0] !="-"){
                var newdiv1 = $( "<tr>"+                  
                  "<td>"+no+"</td><td>"+item[0]+"</td>"+
                  "<td>"+item[1]+"</td><td>"+item[2]+"</td>"+
                  "<td>"+item[3]+"</td><td>"+item[4]+"</td>"+
                  "<td>"+item[5]+"</td><td>"+item[6]+"</td>"+
                  "</tr>");
                no+=1;

                $("#makan1data").append(newdiv1);
              }
              });

            }
          })
}


function extmakan(id) {
  $('#myModal3').modal('show');
      var no = 1;
      var tanggal = $("#datepicker").val();
      $.ajax({
            url: "<?php echo base_url('ot/extrafood1/')?>",
            type : "POST",
            data: {
              tgl:tanggal,
              id:id
            },
            dataType: 'json',
            success: function(data){
              $("#extmakanfood").empty();
              $.each(data, function(i, item) {
                if ( item[0] !="-"){
                var newdiv1 = $( "<tr>"+                  
                  "<td>"+no+"</td><td>"+item[0]+"</td>"+
                  "<td>"+item[1]+"</td><td>"+item[2]+"</td>"+
                  "<td>"+item[3]+"</td><td>"+item[4]+"</td>"+
                  "<td>"+item[5]+"</td><td>"+item[6]+"</td>"+
                  "</tr>");
                no+=1;

                $("#extmakanfood").append(newdiv1);
              }
              });

            }
          })
}

function extrafood() {
  var no = 1;
      var tanggal = $("#datepicker").val();
      $.ajax({
            url: "<?php echo base_url('ot/ga_by_tgl_food/')?>",
            type : "POST",
            data: {
              tgl:tanggal
              
            },
            dataType: 'json',
              success: function(data){
          //var s2 = $.parseJSON(data);
          // alert(data[0][1]);
          if (data[0][0] != "gagal") {
            $("#extmakan1").text(data[0][1]);
            $("#extmakan2").text(data[0][2]);
            $("#extmakan3").text(data[0][3]); 
          }
          else {
            $("#extmakan1").text("0");
            $("#extmakan2").text("0");
            $("#extmakan3").text("0"); 
          }
            }
          })
}

function trans(dari,sampai,id) {
  $('#myModal2').modal('show');
  // alert(id);
      var no = 1;
      var tanggal = $("#datepicker").val();
      $.ajax({
            url: "<?php echo base_url('ot/trans1/')?>",
            type : "POST",
            data: {
              tgl:tanggal,
              dari:dari,
              sampai:sampai,
              id:id
            },
            dataType: 'json',
            success: function(data){
              
              $("#trans1data").empty();
              $.each(data, function(i, item) {
                if ( item[0] !="-"){
                var newdiv1 = $( "<tr>"+                  
                  "<td>"+no+"</td><td>"+item[0]+"</td>"+
                  "<td>"+item[1]+"</td><td>"+item[2]+"</td>"+
                  "<td>"+item[3]+"</td><td>"+item[4]+"</td>"+
                  "<td>"+item[5]+"</td><td>"+item[6]+"</td>"+
                  "</tr>");
                no+=1;

                $("#trans1data").append(newdiv1);
                }
              });
            }
          })
}


    function changeTanggal() {
      $("#extmakanfood").empty();
      $("#makan1data").empty();
      $("#trans1data").empty();
      var no = 1;
      var tanggal = $("#datepicker").val();
      $.ajax({
        url: "<?php echo base_url('ot/ga_by_tgl/')?>",
        type : "POST",
        data: {
          tgl:tanggal
        },
        success: function(data){
          var s = $.parseJSON(data);
          if (s[0] != "gagal") {
            $("#makan1").text(s[0][1]);
            $("#makan2").text(s[0][2]);
            $("#makan3").text(s[0][3]); 
          }
          else {
            $("#makan1").text("0");
            $("#makan2").text("0");
            $("#makan3").text("0"); 
          }
              
          $.ajax({
            url: "<?php echo base_url('ot/ga_by_tgl_trans/')?>",
            type : "POST",
            data: {
              tgl:tanggal
            },
            dataType: 'json',
            success: function(data2){
              $("#head").empty();
              $.each(data2, function(i, item) {
                var newdiv1 = $( "<tr id='hello"+no+"'>"+
                  "<th scope='row' width='30%'>"+item[1]+" - "+item[2]+"</th>"+
                  "<td onclick='trans(\""+item[1]+"\",\""+item[2]+"\",\"b\")'>"+item[3]+"</td><td onclick='trans(\""+item[1]+"\",\""+item[2]+"\",\"p\")'>"+item[4]+"</td>"+
                  "</tr>");

                no+=1;
                $("#head").append(newdiv1);

              });

              
            }
          });

            }
          })
        }

    
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>