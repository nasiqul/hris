[<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nik')) { redirect('home/overtime_user'); }?>

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
                         Monthly Overtime Summary 
                         <span class="text-purple">???</span>
                    </h1>
               </section>

               <!-- Main content -->
               <section class="content container-fluid">
                    <div class="col-md-12">

                         <div class="box box-solid">
                              <div class="box-body">
                                   <div class="col-md-3 pull-right">
                                        <form action="" method="post" id="rati2">
                                             <label>Month : </label>
                                             <input type="text" class="form-control text-muted datepicker" placeholder="Select Month" id="bulan" onchange="date_change()" name="date2">
                                        </form>
                                   </div>
                                   <div class="col-md-12">
                                       Production
                                       <div class="progress" style="margin: 0 auto">
                                        <div class="progress-bar progress-bar-green progress-bar-striped active" id="progress_bar_prod"></div>
                                   </div>
                                   <CENTER> <span class="progress-number" id="progress_number_delivery" style="font-weight:bold;"></span></CENTER>
                                   Office

                                   <div class="progress" style="margin: 0 auto">
                                        <div class="progress-bar progress-bar-blue progress-bar-striped active" id="progress_bar_ofc"></div>
                                   </div>
                                   <CENTER> <span class="progress-number" id="progress_number_delivery" style="font-weight:bold;"></span></CENTER>

                                   <div id="container8" style="width: 100%"></div> 
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
<script>$(function () {
     var cat = new Array();
     var tiga_jam = new Array();
     var per_minggu = new Array();
     var per_bulan = new Array();
     var manam_bulan = new Array();

     $.getJSON('<?php echo base_url("ot/overtime_chart_per_dep/")?>', function(data) {

          var xCategories = [];
          var seriesData = [];
          var i, cat;

          var intVal = function ( i ) {
               return typeof i === 'string' ?
               i.replace(/.[\$,]/g, '')*1 :
               typeof i === 'number' ?
               i : 0;
          };

          for(i = 0; i < data.length; i++){
               cat = data[i][0];
               if(xCategories.indexOf(cat) === -1){
                    xCategories[xCategories.length] = cat;
               }
          }

          for(i = 0; i < data.length; i++){
               if(seriesData){
                    var currSeries = seriesData.filter(function(seriesObject){ return seriesObject.name == data[i][1];});
                    if(currSeries.length === 0){
                         seriesData[seriesData.length] = currSeries = {name: data[i][1], data: []};
                    } else {
                         currSeries = currSeries[0];
                    }
                    var index = currSeries.data.length;
                    currSeries.data[index] = data[i][2];
               } else {
                    seriesData[0] = {name: data[i][1], data: [intVal(data[i][2])]}
               }
          }

          Highcharts.chart('container8', {
               chart: {
                    type: 'column'
               },
               title: {
                    text: 'Bulan'
               },
               xAxis: {
                    categories: xCategories
               },
               yAxis: {
                    type: 'logarithmic',
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
                         },
                         minPointLength: 5
                    }
               },
               series: seriesData
          });
     });
});

$('.datepicker').datepicker({
     autoclose: true,
     format: "mm-yyyy",
     viewMode: "months", 
     minViewMode: "months"
});


</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
Both of these plugins are recommended to enhance the
user experience. -->
</body>
</html>]