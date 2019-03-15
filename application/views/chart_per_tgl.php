<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
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
          Budget data
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="box box-solid">
            <div class="box-body">

              <div class="col-md-12">
                <form action="<?php echo base_url('home/budget_chart') ?>" method="post" id="rati">
                  <label>Month : </label>
                  <input type="text" name="tgl2" style="width:130px;" onchange="rati.submit()" id="tgl2" placeholder="Select month" class="form-control datepicker">
                </form>
              </div>
              <div class="col-md-12">
                <div id="container8" style="width: 100%"></div>

                <table id="datatable" hidden="">

                  <tr>
                    <th></th>
                    <th>Budget</th>
                    <th>Aktual</th>
                  </tr>
                  <?php foreach ($cc as $key) { ?>
                    <tr>
                      <th><?php echo $key->name ?></th>
                      <td><?php echo $key->budget * $key->jumlah ?></td>
                      <td><?php echo $key->aktual ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
              <!-- nav-tabs-custom -->
            </div> 
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
   $(function () {
    var cat = new Array();
    var tiga_jam = new Array();
    var per_minggu = new Array();
    var per_bulan = new Array();
    var manam_bulan = new Array();

    $.getJSON('<?php echo base_url("ot/overtime_chart_per_dep/")?>', function(data) {

    // var s = $.parseJSON(data);

    // var seriesData = [];
    // var cat = [];
    // var nama = [];
    // // Populate series

    // for(i = 0; i < data.length; i++){

    //   cat.push(data[i][0]);
    //   // seriesData.push(data[i][1]);
    // }

    // for (var i = 0; i < data.length; i++) {
    //   seriesData[i] = {name: data[i][2], data: [data[i][1]]}
    // }

    // for (var i = 0; i < data.length; i++) {
    //   nama = data[];
    //   for (var z = 0; z < Things.length; z++) {
    //     Things[z]
    //   }
    // }

    // for (i = 0; i < data.length; i++){
    //   cat.push(data[i][0]);
    //   tiga_jam.push(parseInt(data[i][1]));
    //   per_minggu.push(parseInt(data[i][2]));
    //   per_bulan.push(parseInt(data[i][3]));
    //   manam_bulan.push(parseInt(data[i][4]));
    // }

    $('#container8').highcharts({
      chart: {
        type: 'column'
      },
      title: {
        text: 'Stacked column chart'
      },
      xAxis: {
        categories: [3, 4, 4, 2, 5,3, 4, 4, 2, 5,3, 4, 4, 2, 5,3, 4, 4, 2, 5,3, 4, 4]
      },
      yAxis: {
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
      legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
      },
      tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
      },
      plotOptions: {
        column: {
          stacking: 'normal',
          dataLabels: {
            enabled: true,
            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
          }
        }
      },
      series: data

    });
  });
  });
</script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>

