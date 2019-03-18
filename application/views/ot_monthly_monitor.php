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
                         Monthly Overtime Monitor 
                         <span class="text-purple">???</span>
                    </h1>
               </section>

               <!-- Main content -->
               <section class="content container-fluid">
                    <div class="col-md-12">

                         <div class="box box-solid">
                              <div class="box-body">
                                   <input type="text" class="form-control" id="select" placeholder="Fiscal">
                                   <div id="container"></div>
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

          $.getJSON('<?php echo base_url("ot/ajax_mountly/")?>', function(data) {

               var xCategories = [];
               var seriesPLBudget = [];
               var seriesProdBudget = [];
               var seriesOfcBudget = [];

               var seriesProdForecast = [];
               var seriesOfcForecast = [];
               var seriesPLForecast = [];
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

               for(i=0; i<data.length; i++){
                    if(data[i][4] == 'PRODUKSI'){
                         seriesProdBudget.push(data[i][1]);
                    }
                    if(data[i][4] == 'OFFICE'){
                         seriesOfcBudget.push(data[i][1]);
                    }

                    if(data[i][4] == 'PL'){
                         seriesPLBudget.push(data[i][1]);
                    }

                    if(data[i][4] == 'PRODUKSI'){
                         seriesProdForecast.push(data[i][3]);
                    }
                    if(data[i][4] == 'OFFICE'){
                         seriesOfcForecast.push(data[i][3]);
                    }
                    if(data[i][4] == 'PL'){
                         seriesPLForecast.push(data[i][1]);
                    }
               }

               var grphDates = new Array();
               var groupedObjects = new Array();
               $.each(data, function (ix, obj) {
                    var existingObj;
                    if ($.inArray(obj[0], grphDates) >= 0) {
                         var index = groupedObjects.map(function(o, i) { 
                              if(o[0] == obj[0])return i;
                         }).filter(isFinite);

                         groupedObjects[index][1] += obj[1];
                         groupedObjects[index][2] += obj[2];
                         groupedObjects[index][3] += obj[3];
                    } else {
                         groupedObjects.push(obj);
                         grphDates.push(obj[0]);
                    }
               });

               var accBudget = [];
               var accForecast = [];

               for(i=0; i<groupedObjects.length; i++){
                    accBudget.push(groupedObjects[i][1]);
                    accForecast.push(groupedObjects[i][3]);
               }

               $('#container').highcharts({
                    title: {
                         text: 'YEAR'
                    },
                    xAxis: {
                         categories: xCategories
                    },
                    plotOptions: {
                         column: {
                              stacking: 'normal',
                              minPointLength: 20
                         }
                    },
                    series: [ 
                    {
                         type: 'column',
                         name: 'Budget-Direct',
                         data: seriesOfcBudget,
                         stack: 'BUDGET'
                    }, 
                    {
                         type: 'column',
                         name: 'Buget-Indirect',
                         data: seriesProdBudget,
                         stack: 'BUDGET'
                    },
                    {
                         type: 'column',
                         name: 'Budget-PL',
                         data: seriesPLBudget,
                         stack: 'BUDGET'
                    }, 
                    {
                         type: 'column',
                         name: 'Forecast-Office',
                         data: seriesOfcForecast,
                         stack: 'FORECAST'
                    },
                    {
                         type: 'column',
                         name: 'Forecast-Production',
                         data: seriesProdForecast,
                         stack: 'FORECAST'
                    },
                    {
                         type: 'column',
                         name: 'Forecast-PL',
                         data: seriesPLForecast,
                         stack: 'FORECAST'
                    },
                    {
                         type: 'line',
                         name: 'BUDGET',
                         data: accBudget,
                         marker: {
                              lineWidth: 2,
                              lineColor: Highcharts.getOptions().colors[3],
                              fillColor: 'BLUE    '
                         }
                    },
                    {
                         type: 'line',
                         name: 'FORECAST',
                         data: accForecast,
                         marker: {
                              lineWidth: 2,
                              lineColor: Highcharts.getOptions().colors[3],
                              fillColor: 'RED'
                         }
                    }]
               })
          })
})

</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
Both of these plugins are recommended to enhance the
user experience. -->
</body>
</html>]