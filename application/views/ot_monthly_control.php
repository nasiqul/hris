<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>

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
     <span class="text-purple">月次残業管理</span>
   </h1>
 </section>

 <!-- Main content -->
 <section class="content container-fluid">
  <div class="col-md-12">

    <div class="box box-solid box-success">
      <div class="box-header">
        <h4 class="box-title">Filter</h4>
      </div>
      <div class="box-body">
        <form action="" method="post" id="rati2">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">Bagian :</label>
                <div class="col-sm-9">
                  <select name="bagian" class="form-control" id="bagian" onchange="postTgl() ">
                    <option value="0" >All</option>
                    <?php foreach ($section as $key) { ?>
                      <option value="<?php echo $key->departemen ?>"><?php echo $key->departemen ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label col-sm-3">Month : </label>
                <div class="col-sm-6">
                  <input type="text" class="form-control text-muted datepicker" placeholder="Select Month" id="bulan" onchange="postTgl(); progress_bar()" name="date2" value="<?php echo date('m-Y')?>">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="box box-solid">
      <div class="box-body">

       <div class="col-md-12">
        Direct (Production) <b id="persendirect2"></b> <b id="persendirect"></b> 
        <div class="progress" style="margin: 0 auto">
         <div class="progress-bar progress-bar-green progress-bar-striped active" id="progress_bar_prod" ></div>
       </div>
       <CENTER> <span class="progress-number" id="progress_number_delivery" style="font-weight:bold;"></span></CENTER>
       Indirect (PC, LOG, PCH, QA, CHM, STD, PE, MTC, MIS)   <b id="persenindirect2" ></b> <b id="persenindirect"></b>

       <div class="progress" style="margin: 0 auto">
         <div class="progress-bar progress-bar-yellow progress-bar-striped active" id="progress_bar_ofc"></div>
       </div>

       Pl (ACC, HR, GA, Canteen, Driver, Security)  <b id="persenpl2" ></b> <b id="persenpl"></b> 

       <div class="progress" style="margin: 0 auto">
         <div class="progress-bar progress-bar-blue progress-bar-striped active" id="progress_bar_pl"></div>
       </div>
       <CENTER> <span class="progress-number" id="progress_number_delivery" style="font-weight:bold;"></span></CENTER>

       <div id="container8" style="width: 100%"></div> 
     </div>
   </div>
 </div>   
</div>

<div class="modal fade" id="myModal">
 <div class="modal-dialog modal-lg">
  <div class="modal-content">
   <div class="modal-header">
    <h4 style="float: right;" id="modal-title"></h4>
    <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
  </div>
  <div class="modal-body">
    <div class="row">
     <div class="col-md-12">
      <table class="table table-striped table-responsive" style="width: 100%" id="example2">
       <thead>
         <tr>
           <th>No</th>
           <th>Tanggal</th>
           <th>NIK</th>
           <th>Nama</th>
           <th>Bagian</th>
           <th>Checkin</th>
           <th>Checkout</th>
           <th>Jam Lembur <br> (Jam)</th>
           <!-- <th>Aktual Lembur <br> (Jam)</th> -->
         </tr>
       </thead>
       <tbody></tbody>
     </table>
   </div>
 </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
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
  progressData();
})

 function progress_bar()
 {
  var bulan = $("#bulan").val();
  var bagian = $("#bagian").val();

  $.ajax({
   url:'<?php echo base_url("ot/presentase_g")?>',
   type:"post",
   data: {
    bulan : bulan,
    bagian : bagian
  },
  success: function(data){
    var s = $.parseJSON(data);
    ofc = s[0].budget;
    prod = s[1].budget;
    pl = s[2].budget;

    ofc2 = s[0].aktual;
    prod2 = s[1].aktual;
    pl2 = s[2].aktual;

    progressData();
  }
});
}

function progressData()
{
  
  if (ofc != 0) {
   var direct = ((ofc2/ofc)*100).toFixed(2) + '%';
   if( ((ofc2/ofc)*100).toFixed(0) > 100){
    $('#progress_bar_prod').removeClass('progress-bar-green').addClass('progress-bar-red');
  }
  $('#persendirect').html("( "+direct+" )");
  $('#persendirect2').html(ofc2+" / "+ofc+" Hours");
  $('#progress_bar_prod').html(direct);
  $('#progress_bar_prod').css('width', (ofc2/ofc)*100 + '%');
  $('#progress_bar_prod').css('color', 'WHITE');
  $('#progress_bar_prod').css('font-weight', 'bold');
  $('#progress_bar_prod').css('font-size', '12pt');
  $('#progress_bar_prod').css('line-height', '20px');
}
else{
  $('#progress_bar_prod').css('width', '0%');
  $('#progress_bar_prod').html("0 %");
  $('#persenindirect').html("( 0 % )");
  $('#persenindirect2').html("0 / 0 Hours");
}


if (prod != 0)
{
  var indirect = ((prod2/prod)*100).toFixed(2) + '%';
  if( ((prod2/prod)*100).toFixed(0) > 100){
    $('#progress_bar_ofc').removeClass('progress-bar-yellow').addClass('progress-bar-red');
  }
  $('#persenindirect').html("( "+indirect+" )");
  $('#persenindirect2').html(prod2+" / "+prod+" Hours");
  $('#progress_bar_ofc').html(indirect);
  $('#progress_bar_ofc').css('width', (prod2/prod)*100 + '%');
  $('#progress_bar_ofc').css('color', 'WHITE');
  $('#progress_bar_ofc').css('font-weight', 'bold');
  $('#progress_bar_ofc').css('font-size', '12pt');
  $('#progress_bar_ofc').css('line-height', '20px');
}
else {
 $('#progress_bar_ofc').css('width','0%');
 $('#progress_bar_ofc').html("0 %");
 $('#persendirect').html("( 0 % )");
 $('#persendirect2').html("0 / 0 Hours");
}

if (pl != 0)
{
  var plData = ((pl2/pl)*100).toFixed(2) + '%';

  if( ((pl2/pl)*100).toFixed(0) > 100){
    $('#progress_bar_pl').removeClass('progress-bar-blue').addClass('progress-bar-red');
  }
  $('#persenpl').html("( "+plData+" )");
  $('#persenpl2').html(pl2+" / "+pl+" Hours");
  $('#progress_bar_pl').html(plData);
  $('#progress_bar_pl').css('width', (pl2/pl)*100 + '%');
  $('#progress_bar_pl').css('color', 'WHITE');
  $('#progress_bar_pl').css('font-weight', 'bold');
  $('#progress_bar_pl').css('font-size', '12pt');
  $('#progress_bar_pl').css('line-height', '20px');
}
else{             
 $('#progress_bar_pl').css('width', '0%');
 $('#progress_bar_pl').html("0 %");
 $('#persenpl').html("( 0 % )");
 $('#persenpl2').html("0 / 0 Hours");
}
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
   var title = $("[name=date2]").val();

   var intVal = function ( i ) {
    return typeof i === 'string' ?
    i.replace(/.[\$,]/g, '')*1 :
    typeof i === 'number' ?
    i : 0;
  };

  for(i = 0; i < data[0].length; i++){
    cat = data[0][i][0];
    if(xCategories.indexOf(cat) === -1){
     xCategories[xCategories.length] = cat;
   }
 }

 for(i = 0; i < data[0].length; i++){
  if(seriesData){
   var currSeries = seriesData.filter(function(seriesObject){ return seriesObject.name == data[0][i][1];});
   if(currSeries.length === 0){
    seriesData[seriesData.length] = currSeries = {name: data[0][i][1], data: []};
  } else {
    currSeries = currSeries[0];
  }
  var index = currSeries.data.length;
  currSeries.data[index] = data[0][i][2];
} else {
 seriesData[0] = {name: data[0][i][1], data: [intVal(data[0][i][2])]}
}
}
   
    var grphDates = new Array();
    var groupedObjects = new Array();
    $.each(data[0], function (ix, obj) {
      var existingObj;
      if ($.inArray(obj[0], grphDates) >= 0) {
       var index = groupedObjects.map(function(o, i) { 
        if(o[0] == obj[0])return i;
      }).filter(isFinite);

       groupedObjects[index][2] += obj[2];
     } else {
       groupedObjects.push(obj);
       grphDates.push(obj[0]);
     }
    });

    cumulativeData = [0];

    accData = [];
    for(i=0; i<groupedObjects.length; i++){
      accData.push(groupedObjects[i][2]);
    }

    accData.forEach(function(elementToAdd, index) {
      var newElement = cumulativeData[index] + elementToAdd;
      cumulativeData.push(newElement);
    });
    cumulativeData.shift();

    var d =  data[1][0][0];
    var res = d.split("-");

        cumulativeData2 = [0];
        accData2 = [];
        for(i=0; i < xCategories.length; i++){
          accData2.push(data[1][0][2]);
        }


        accData2.forEach(function(elementToAdd2, index) {
          var newElement2 = cumulativeData2[index] + elementToAdd2;
          cumulativeData2.push(newElement2);
        });

        cumulativeData2.shift();
        for (var i = 0; i <= cumulativeData2.length; i++) {
         // alert(cumulativeData2[i]+" ");
        }


               seriesData.push({type: 'line', name: 'Cumulative Budget', data: cumulativeData2});
               seriesData.push({type: 'line', name: 'Cumulative Actual', data: cumulativeData});

               Highcharts.setOptions({
                colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4', '#303133', '#db2929','#d1cccc','#9a3dd3','#236330']});


               Highcharts.chart('container8', {
                chart: {
                 type: 'column'
               },
               title: {
                 text: $("[name=date2]").val()
               },
               xAxis: {
                 categories: xCategories
               },
               yAxis: {
                 title: {
                  text: 'Total Jam',
                  style: {
                   fontWeight: 'normal',
                   fontSize: 15
                 }
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
            },
            series: {
             cursor: 'pointer',
             events: {
               click: function (event) {
                 details(this.name,event.point.category, title);
               }
             }
           }
         },
         series: seriesData
       });
             });
});


function postTgl() {
 var url = "<?php echo base_url('ot/overtime_chart_per_dep') ?>";
 var bulan = $("#bulan").val();
 var bagian = $("#bagian").val();
 $.ajax({
   type: "POST",
   url: url,
   data: {
    bulan : bulan,
    bagian : bagian
  },
  success: function(data) {
   progressData();
   progress_bar();
   var data = $.parseJSON(data);
   var title = $("[name=date2]").val();

   var xCategories = [];
   var seriesData = [];
   var i, cat;

   var intVal = function ( i ) {
    return typeof i === 'string' ?
    i.replace(/.[\$,]/g, '')*1 :
    typeof i === 'number' ?
    i : 0;
  };

  for(i = 0; i < data[0].length; i++){
    cat = data[0][i][0];
    if(xCategories.indexOf(cat) === -1){
     xCategories[xCategories.length] = cat;
   }
 }

 for(i = 0; i < data[0].length; i++){
  if(seriesData){
   var currSeries = seriesData.filter(function(seriesObject){ return seriesObject.name == data[0][i][1];});
   if(currSeries.length === 0){
    seriesData[seriesData.length] = currSeries = {name: data[0][i][1], data: []};
  } else {
    currSeries = currSeries[0];
  }
  var index = currSeries.data.length;
  currSeries.data[index] = data[0][i][2];
} else {
 seriesData[0] = {name: data[0][i][1], data: [intVal(data[0][i][2])]}
}
}

var grphDates = new Array();
var groupedObjects = new Array();
$.each(data[0], function (ix, obj) {
  var existingObj;
  if ($.inArray(obj[0], grphDates) >= 0) {
   var index = groupedObjects.map(function(o, i) { 
    if(o[0] == obj[0])return i;
  }).filter(isFinite);

   groupedObjects[index][2] += obj[2];
 } else {
   groupedObjects.push(obj);
   grphDates.push(obj[0]);
 }
});

cumulativeData = [0];

accData = [];
for(i=0; i<groupedObjects.length; i++){
  accData.push(groupedObjects[i][2]);
}

accData.forEach(function(elementToAdd, index) {
  var newElement = cumulativeData[index] + elementToAdd;
  cumulativeData.push(newElement);
});
cumulativeData.shift();

    var d =  data[1][0][0];
    var res = d.split("-");

        cumulativeData2 = [0];
        accData2 = [];
        for(i=0; i < xCategories.length; i++){
          accData2.push(data[1][0][2]);
        }


        accData2.forEach(function(elementToAdd2, index) {
          var newElement2 = cumulativeData2[index] + elementToAdd2;
          cumulativeData2.push(newElement2);
        });

        cumulativeData2.shift();
        for (var i = 0; i <= cumulativeData2.length; i++) {
         // alert(cumulativeData2[i]+" ");
        }


               seriesData.push({type: 'line', name: 'Cumulative Budget', data: cumulativeData2});


               seriesData.push({type: 'line', name: 'Cumulative Overtime', data: cumulativeData})

               Highcharts.chart('container8', {
                chart: {
                 type: 'column'
               },
               title: {
                 text: title
               },
               xAxis: {
                 categories: xCategories
               },
               yAxis: {
                 title: {
                  text: 'Total Jam',
                  style: {
                   fontWeight: 'bold'
                 }
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
            },
            series: {
             cursor: 'pointer',
             events: {
               click: function (event) {
                 details(this.name,event.point.category, title);
                                   // alert(this.name + ' clicked ' + event.point.category + title);
                                 }
                               }
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

function details(kode, tgl, blnthn) {
 table2 = $('#example2').DataTable();
 table2.destroy();

 var tanggal = tgl+"-"+blnthn;

 $("#myModal").modal('show');
 table2 = $('#example2').DataTable({
  "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
  "processing"    : true,
  "serverSide"    : true,
  "searching": false,
  "bPaginate": false,
  "bLengthChange": false,
  "bFilter": false,
  "bInfo": false,
  'order'         : [],
  "ajax": {
    "url": "<?php echo base_url('ot/ajax_ot_monthly_c')?>",
    "type": "GET",
    "data" : {
      kode : kode,
      tgl : tanggal,
    }
  }
})
}


</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
Both of these plugins are recommended to enhance the
user experience. -->
</body>
</html>