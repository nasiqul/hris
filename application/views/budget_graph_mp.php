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
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
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

              <div class="alert alert-warning alert-dismissible" id="notif" onclick="check()" style="display: none; cursor: pointer;">
                <h4><i class="icon fa fa-warning"></i> Data Hari ini belum diupload!</h4>
              </div>
              <div class="col-md-12">
                <form action="" method="post" id="rati">
                  <label>Month : </label>
                  <input type="text" name="sortTgl" style="width:130px;" onchange="a()" id="datepicker" placeholder="Select month" class="form-control">
                </form>
              </div>
              <div class="col-md-12">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

                <table id="datatable">

                  <tr>
                    <th></th>
                    <th>Budget</th>
                    <th>Aktual</th>
                  </tr>
                  <?php foreach ($cc2 as $key) { ?>
                    <tr>
                      <th><?php echo "asd" ?></th>
                      <td><?php echo $key->budget ?></td>
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
   Highcharts.chart('container', {
    data: {
      table: 'datatable'
    },
    chart: {
      type: 'column'
    },
    title: {
      text: 'Data extracted from a HTML table in the page'
    },
    yAxis: {
      allowDecimals: false,
      title: {
        text: 'Units'
      }
    },
    tooltip: {
      formatter: function () {
        return '<b>' + this.series.name + '</b><br/>' +
        this.point.y + ' ' + this.point.name.toLowerCase();
      }
    }
  });
</script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>

