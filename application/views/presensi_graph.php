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
          Attendance Data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <?php
        /* Mengambil query report*/
        $arr = array();
        $result = array();
        foreach($tot as $r2){
          $tgl = date('d F Y', strtotime($r2->tanggal));

          $arr['name'] = $r2->shift;
          $arr['y'] = (int) $r2->jml;

          array_push($result, $arr);
        }

        $arr3 = array();
        $result3 = array();

        foreach($persentase as $r3){
          $tgl3 = date('d F Y', strtotime($r3->tanggal));

          $arr3['name'] = 'Masuk';
          $arr3['y'] = (float) $r3->jml;

          array_push($result3, $arr3);
        }

        $arr4 = array();
        foreach($kary as $r4){
          $kurang = $r4->jml - $arr3['y'];
          $arr4['name'] = 'Tidak masuk';
          $arr4['y'] = (float) $kurang;

          array_push($result3, $arr4);
        }
        ?>

        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Stat Persentase <span>(%)</span></a></li>
              <li><a href="#tab_2" data-toggle="tab">Stat By Shift</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div id = "container2" style = "width: 850px; margin: 0 auto"></div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div id = "container" style = "width: 850px; margin: 0 auto"></div>
              </div>
              <!-- /.tab-pane -->
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
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
            text: 'Total Karyawan'
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
          pointFormat: '<span style="color:{point.color}">Shift {point.name}</span>: <b>{point.y}</b> <br/>'
        },

        "series": [
        {
          "name": "By Shift",
          "colorByPoint": true,
          "data": <?php echo json_encode($result) ?>
        }
        ]
      })
    });


    $(function () {
      $('#container2').highcharts({
        chart : {
         plotBackgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
       },
       title : {
         text: <?php echo json_encode($tgl3) ?>  
       },
       tooltip : {
         pointFormat: '<b>{point.y}</b>'
       },
       plotOptions : {
         pie: {
          allowPointSelect: true,
          cursor: 'pointer',

          dataLabels: {
           enabled: true,
           format: '<b>{point.name}</b> : {point.percentage:.1f}% ',
           style: {
            color: (Highcharts.theme && Highcharts.theme.contrastTextColor)||
            'black'
          }
        },
        showInLegend: true

      }
    },
    credits: {
      enabled: false
    },
    series : [{
     type: 'pie',
     name: 'percentage (%)',
     data: <?php echo json_encode($result3) ?>
   }]
 })
    });
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>