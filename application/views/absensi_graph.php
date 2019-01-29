<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>

<body class="hold-transition skin-blue sidebar-mini">
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
          Attendance data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <?php
        /* Mengambil query report*/
        $arr = array();
        $result = array();
        foreach($absen as $r2){
          $tgl = date('d F Y', strtotime($r2->tanggal));

          $arr['name'] = $r2->shift;
          $arr['y'] = (int) $r2->jml;

          array_push($result, $arr);
        }
        ?>

        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="box">
            <div class="box-body">
              <div id = "container" style = "width: 850px; margin: 0 auto"></div>
            </div>
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.modal-dialog -->
        <!-- </div> --> 

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
    //---------CHART---------------

    $(function () {
      $('#container').highcharts({
        chart: {
          type: 'column'
        },
        title: {
          text: <?php echo json_encode($tgl) ?>
        },
        xAxis: {
          type: 'category'
        },
        yAxis: {
          title: {
            text: 'Total Attendance'
          }

        },
        legend: {
          enabled: false
        },
        plotOptions: {
          series: {
            borderWidth: 0,
            dataLabels: {
              enabled: true,
              format: '{point.y}'
            }
          }
        },
        credits: {
          enabled: false
        },

        tooltip: {
          headerFormat: '',
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
        },

        "series": [
        {
          "name": "By Attendance",
          "colorByPoint": true,
          "data": <?php echo json_encode($result) ?>
        }
        ]
      })
    });
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>