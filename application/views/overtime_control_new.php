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
          Overtime Control New
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">

          <div class="box box-solid">
            <div class="box-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    Select Date : 
                    <input type="text" class="form-control datepicker" id="tgl" onchange="drawChart()">
                  </div>
                  <div class="col-md-12">
                    <div id="container"></div>
                  </div>
                </div>
              </div>
              <br><br>
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
    $(function () {
      drawChart();
    })

    function drawChart() {
      var tgl = $("#tgl").val();
      $.ajax({
       type: "POST",
       url: "<?php echo base_url('ot/overtime_chart_control') ?>",
       data: {
        tgl : tgl
      },
      dataType: 'json',
      success: function(data) {
       var xCategories = [];
       var seriesDataBudget = [];
       var seriesDataAktual = [];
       var cat;

       for(var i = 0; i < data.length; i++){
        cat = data[i][1];
        seriesDataBudget.push(data[i][2]);
        seriesDataAktual.push(data[i][3]);
        if(xCategories.indexOf(cat) === -1){
         xCategories[xCategories.length] = cat;
       }
     }

     var interval = Math.ceil(300/10);


     Highcharts.chart('container', {
      chart: {
        type: 'column'
      },
      title: {
        text: '<span style="font-size: 2vw;">Overtime Control</span><br><span style="color: rgba(96, 92, 168);">'+ data[0][4] +'</span>'
      },
      credits:{
        enabled:false
      },
      yAxis: {
        tickInterval: 10,
        min:0,
        allowDecimals: false,
        title: {
          text: 'Jumlah Lembur (jam)'
        }
      },
      xAxis: {
        labels: {
          style: {
            color: 'rgba(75, 30, 120)',
            fontSize: '12px',
            fontWeight: 'bold'
          }
        },
        categories: xCategories
      },
      tooltip: {
        formatter: function () {
          return '<b>' + this.series.name + '</b><br/>' +
          this.point.y + ' ' + this.series.name.toLowerCase();
        }
      },
      plotOptions: {
        series: {
          cursor: 'pointer',
          point: {
            events: {
              click: function () {
                modalTampil(this.category, data[0][4]);
              }
            }
          }
        }
      },
      series: [{
        name: 'Overtime Budget',
        data: seriesDataBudget
      }, {
        name: 'Overtime Actual',
        data: seriesDataAktual
      }]
    });
   }
 })
    }

    function modalTampil(costCenter, date) {
      $.ajax({
       type: "POST",
       url: "<?php echo base_url('ot/overtime_control_detail') ?>",
       data: {
        cc : costCenter,
        tgl : date
      },
      success: function(data) {
        
      }
    })
    }

    $('.datepicker').datepicker({
     autoclose: true,
     format: "dd-mm-yyyy"
   });

 </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>