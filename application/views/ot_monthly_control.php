<!DOCTYPE html>
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
                         Monthly Overtime Control 
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
                                             <input type="text" class="form-control text-muted datepicker" placeholder="Select Month" id="bulan" onchange="postTgl()" name="date2">
                                        </form>
                                   </div>
                                   <div class="col-md-12">
                                       Production
                                       <div class="progress" style="margin: 0 auto">
                                        <div class="progress-bar progress-bar-green progress-bar-striped active" id="progress_bar_prod" ></div>
                                   </div>
                                   <CENTER> <span class="progress-number" id="progress_number_delivery" style="font-weight:bold;"></span></CENTER>
                                   Office

                                   <div class="progress" style="margin: 0 auto">
                                        <div class="progress-bar progress-bar-yellow progress-bar-striped active" id="progress_bar_ofc"></div>
                                   </div>

                                   Pl

                                   <div class="progress" style="margin: 0 auto">
                                        <div class="progress-bar progress-bar-blue progress-bar-striped active" id="progress_bar_pl"></div>
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
<script>
     var prod = 0;
     var ofc = 0;
     var pl = 0;

     var prod2 = 0;
     var ofc2 = 0;
     var pl2 = 0;
     $(document).ready(function() {
          progress_bar();
    })

     function progress_bar()
     {
          $.ajax({
          url:'<?php echo base_url("ot/presentase_g")?>',
          type:"post",
          data: $("#bulan").serialize(),
          success: function(data){
               var s = $.parseJSON(data);
               ofc = s[0].budget;
               prod = s[1].budget;
               pl = s[2].budget;

               ofc2 = s[0].aktual;
               prod2 = s[1].aktual;
               pl2 = s[2].aktual;
          }
     });
     }

     function progressData()
     {
          alert(prod2);
          var direct = ((ofc2/ofc)*100).toFixed(2) + '%';
          $('#progress_bar_prod').html(direct);
          $('#progress_bar_prod').css('width', (ofc2/ofc)*100 + '%');
          $('#progress_bar_prod').css('color', 'WHITE');
          $('#progress_bar_prod').css('font-weight', 'bold');
          $('#progress_bar_prod').css('font-size', '12pt');
          $('#progress_bar_prod').css('line-height', '20px');

          var indirect = ((prod2/prod)*100).toFixed(2) + '%';
          $('#progress_bar_ofc').html(indirect);
          $('#progress_bar_ofc').css('width', (prod2/prod)*100 + '%');
          $('#progress_bar_ofc').css('color', 'WHITE');
          $('#progress_bar_ofc').css('font-weight', 'bold');
          $('#progress_bar_ofc').css('font-size', '12pt');
          $('#progress_bar_ofc').css('line-height', '20px');


          var plData = ((pl2/pl)*100).toFixed(2) + '%';
          $('#progress_bar_pl').html(plData);
          $('#progress_bar_pl').css('width', (pl2/pl)*100 + '%');
          $('#progress_bar_pl').css('color', 'WHITE');
          $('#progress_bar_pl').css('font-weight', 'bold');
          $('#progress_bar_pl').css('font-size', '12pt');
          $('#progress_bar_pl').css('line-height', '20px');
     }

     $(function () {
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
                         title: {
                              text: 'Total Jam'
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
                              minPointLength: 5
                         }
                    },
                    series: seriesData
               });
          });
     });


function postTgl() {
 var url = "<?php echo base_url('ot/overtime_chart_per_dep') ?>";
 $.ajax({
   type: "POST",
   url: url,
   data: $("#bulan").serialize(),
   success: function(data) {
     progress_bar();
     progressData();
     var data = $.parseJSON(data);

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
               title: {
                    text: 'Total Jam'
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
                    minPointLength: 5
               }
          },
          series: seriesData
     });


}
});
}

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