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
                    <input type="text" class="form-control" id="datepicker" placeholder="Select date" onchange="changeTanggal()">
                  </div>
                  <?php echo date('Y/m/d', strtotime("27-02-2019")) ?>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Total Makan</label>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 1</th></tr>
                      <tr><td id='makan1'>0</td></tr>
                    </table>
                  </div>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 2</th></tr>
                      <tr><td id='makan2'>0</td></tr>
                    </table>
                  </div>
                  <div class="col-sm-2">
                    <table class="table table-bordered table-striped text-center" id="shf1">
                      <tr><th>Shift 3</th></tr>
                      <tr><td id='makan3'>0</td></tr>
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

    function changeTanggal() {
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
            success: function(data){
              $("#head").empty();
              $.each(data, function(i, item) {
                var newdiv1 = $( "<tr id='hello"+no+"'>"+
                  "<th scope='row' width='30%'>"+item[1]+" - "+item[2]+"</th>"+
                  "<td>"+item[3]+"</td><td>"+item[4]+"</td>"+
                  "</tr>");

                no+=1;
                $("#head").append(newdiv1);

              });
            }
          })
        }
      })
    }

  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>