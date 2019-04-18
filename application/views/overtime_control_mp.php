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
        <h1> Overtime Control MP <span class="text-purple">???</span>
          <div class="col-md-2 pull-right">
            <div class="input-group date">
              <div class="input-group-addon bg-green" style="border-color: green">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control datepicker" id="tgl" onchange="drawChart()" placeholder="Select date" style="border-color: green">
            </div>
          </div>
        </h1>
        <small style="font-size: 15px; color: #88898c"><i class="fa fa-history"></i> Last updated : <?php echo date('d M Y',strtotime($tgl2[0]->tanggal)) ?> </small>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-solid">
              <div class="box-body">
                <div id="container" style="width: 100%; margin: 0px auto; height: 550px"></div>
              </div>
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
    $('body').toggleClass('sidebar-collapse');
    $(function () {
      drawChart();
    })

    function drawChart() {
      var tgl = $("#tgl").val();
      $.ajax({
       type: "POST",
       url: "<?php echo base_url('ot/overtime_chart_control_mp') ?>",
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
          seriesDataAktual.push(data[i][3 ]);
          if(xCategories.indexOf(cat) === -1){
           xCategories[xCategories.length] = cat;
         }
       }

       Highcharts.chart('container', {
        chart: {
          spacingTop: 10,
          type: 'column'
        },
        title: {
          text: '<span style="font-size: 2vw;">Overtime ManPower</span><br><span style="color: rgba(96, 92, 168);">'+ data[0][4] +'</span>'
        },
        credits:{
          enabled:false
        },
        yAxis: {
          tickInterval: 10,
          min:0,
          allowDecimals: false,
          title: {
            text: 'Amount of Overtime (hours)'
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
            pointPadding: 0.93,
            cursor: 'pointer',
            point: {
              events: {
                click: function () {
                  modalTampil(this.category, data[0][0][4]);
                }
              }
            },
            minPointLength: 3,
            dataLabels: {
              allowOverlap: true,
              enabled: true,
              y: -25,
              style: {
                color: 'black',
                fontSize: '13px',
                textOutline: false,
                fontWeight: 'bold',
              },
              rotation: -90
            },
            pointWidth: 15,
            pointPadding: 0,
            borderWidth: 0,
            groupPadding: 0.1
          }
        },
        series: [{
          name: 'Overtime Budget',
          data: seriesDataBudget,
          color: "#f76111"
        }, {
          name: 'Overtime Actual',
          data: seriesDataAktual,
          color: "#7300ab"
        }]
      });
     }
   })
    }

    $('.datepicker').datepicker({
      <?php $tgl_max = date('d-m-Y',strtotime($tgl2[0]->tanggal)) ?>
     autoclose: true,
     format: "dd-mm-yyyy",
     endDate: '<?php echo $tgl_max ?>',

   });
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>