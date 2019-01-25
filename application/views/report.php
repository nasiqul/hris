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
          Jumlah Kehadiran
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">      
        <?php
        /* Mengambil query report*/
        foreach($report1 as $result1){
        $tgl1[] = $result1->tanggal; //ambil tanggal
        $value1[] = (float) $result1->jml; //ambil jumlah
      }

      foreach($report2 as $result2){
        $tgl2[] = $result2->tanggal; //ambil tanggal
        $value2[] = (float) $result2->jml; //ambil jumlah
      }

      foreach($report3 as $result3){
        $tgl3[] = $result3->tanggal; //ambil tanggal
        $value3[] = (float) $result3->jml; //ambil jumlah
      }
      /* end mengambil query*/

      ?>

      <!-- Load chart dengan menggunakan ID -->
      <div id ="report"></div>
      <div id = "container"></div>
      <!-- END load chart -->
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
  <!-- Script untuk memanggil library Highcharts -->
  <script type="text/javascript">
    $(function () {       
        $('#report').highcharts({
          chart : {
         type: 'column'
       },
       title : {
         text: 'Presensi bulan Januari'   
       }, 
       xAxis : {
         categories: '<?php echo json_encode($tgl1); ?>'
       },
       yAxis : {
         min: 0,
         title: {
          text: 'Total fruit consumption'
        },
        stackLabels: {
          enabled: true,
          style: {
           fontWeight: 'bold',
           color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
         }
       }
     },
     legend : {
       align: 'right',
       x: -30,
       verticalAlign: 'top',
       y: 25,
       floating: true,

       backgroundColor: (
        Highcharts.theme && Highcharts.theme.background2) || 'white',
       borderColor: '#CCC',
       borderWidth: 1,
       shadow: false
     },   
     tooltip : {
       formatter: function () {
        return '<b>' + this.x + '</b><br/>' +
        this.series.name + ': ' + this.y + '<br/>' +
        'Total: ' + this.point.stackTotal;
      }
    },
    plotOptions : {
     column: {
      stacking: 'normal',
      dataLabels: {
       enabled: true,
       color: (Highcharts.theme && Highcharts.theme.dataLabelsColor)
       || 'white',
       style: {
        textShadow: '0 0 3px black'
      }
    }
  }
},
  credits : {
   enabled: false
  },
  series : [
  {
    name: 'Shift3',
    data: <?php echo json_encode($value3);?>
  }, 
  {
    name: 'Shift2',
    data: <?php echo json_encode($value2);?>
  }, 
  {
    name: 'Shift1',
    data: <?php echo json_encode($value1);?>      
  }
  ]
});
});
    </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>