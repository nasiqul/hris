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
          Employee Report
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <table class="table table-bordered">
               <tr id="head" bgcolor="#ddd">
                <th bgcolor="#ddd" ></th>
              </tr>
              <tr id="totalemp">
                <th bgcolor="#ddd" >Total</th>
              </tr>
              <tr id="kehadiran">
                <th bgcolor="#ddd" >% Kehadiran</th>
              </tr>
              <tr id="aktif">
                <th bgcolor="#ddd">Aktif</th>
              </tr>
              <tr id="non">
                <th bgcolor="#ddd">Non-aktif</th>
              </tr>
              <tr id="working">
                <th bgcolor="#ddd">Total overtime (working day)</th>
              </tr>
              <tr id="holiday">
                <th bgcolor="#ddd">Total overtime (Holiday)</th>
              </tr>
              <tr id="avg">
                <th bgcolor="#ddd">Avg. Overtime</th>
              </tr>
              <tr id="56">
                <th bgcolor="#ddd">Lembur Normal Lembur normal > 56 Jam per Bulan</th>
              </tr>
              <tr id="3">
                <th bgcolor="#ddd">Lembur Normal > 3 Jam per Hari</th>
              </tr>
              <tr id="14">
                <th bgcolor="#ddd">Lembur Normal > 14 Jam per Minggu</th>
              </tr>
              <tr id="3dan14">
                <th bgcolor="#ddd">Lembur Normal > 3 Jam per Hari & <br> Lembur normal > 14 Jam per Minggu</th>
              </tr>
            </table>

            <br>
            
            <h4><b>Total Jam Kerja</b></h4>
            <table class="table table-bordered">
             <tr bgcolor="#ddd">
              <th>Bulan</th>
              <th>Hari Kerja</th>
              <th>Total MP <br> YMPI</th>
              <th>Jam Kerja</th>
              <th>Total Jam Lembur</th>
              <th>Jam Kerja Total</th>
              <th>CT</th>
              <th>SD</th>
              <th>I</th>
              <th>A</th>
              <th>Total Absen</th>
              <th>Jam <br> Ketidakhadiran</th>
              <th>(%) Kehadiran</th>
            </tr>
            <tbody id="total_jam_isi">
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
      $.ajax({
        type : "GET",
        url: "<?php echo base_url('home/get_total_emp/') ?>",
        dataType: 'json',
        success: function(data){
          $.each(data.all_emp, function(i, value){
            $('#head').append($('<th>'+data.all_emp[i][0]+'</th>'));
            $('#kehadiran').append($('<td>'+data.all_emp[i][1]+'</td>'));
            $('#totalemp').append($('<td>'+data.all_emp[i][2]+'</td>'));
            $('#aktif').append($('<td>'+data.all_emp[i][3]+'</td>'));
            $('#non').append($('<td>'+data.all_emp[i][4]+'</td>'));
            $('#working').append($('<td>'+data.all_emp[i][5]+'</td>'));
            $('#holiday').append($('<td>'+data.all_emp[i][6]+'</td>'));
            $('#avg').append($('<td>'+data.all_emp[i][7]+'</td>'));
            $('#3').append($('<td>'+data.all_emp[i][8]+'</td>'));
            $('#14').append($('<td>'+data.all_emp[i][9]+'</td>'));
            $('#3dan14').append($('<td>'+data.all_emp[i][10]+'</td>'));
            $('#56').append($('<td>'+data.all_emp[i][11]+'</td>'));
          });

          $.each(data.jam_kerja, function(i, value){
            $('#total_jam_isi').append($('<tr><td>'+data.jam_kerja[i][0]+'</td>'+
              '<td>'+data.jam_kerja[i][1]+'</td>'+
              '<td>'+data.jam_kerja[i][2]+'</td>'+
              '<td>'+data.jam_kerja[i][3]+'</td>'+
              '<td>'+data.jam_kerja[i][4]+'</td>'+
              '<td>'+data.jam_kerja[i][5]+'</td>'+
              '<td>'+data.jam_kerja[i][6]+'</td>'+
              '<td>'+data.jam_kerja[i][7]+'</td>'+
              '<td>'+data.jam_kerja[i][8]+'</td>'+
              '<td>'+data.jam_kerja[i][9]+'</td>'+
              '<td>'+data.jam_kerja[i][10]+'</td>'+
              '<td>'+data.jam_kerja[i][11]+'</td>'+
              '<td>'+data.jam_kerja[i][12]+' %</td>'+
              '</tr>'));
          });
        }
      });
    })
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>