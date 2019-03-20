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
                         Employee Monthly Overtime 
                         <span class="text-purple">社員別月次残業まとめ</span>
                    </h1>
               </section>

               <!-- Main content -->
               <section class="content container-fluid">
                    <div class="col-md-12">

                         <div class="box box-primary box-solid">
                          <div class="box-header">
                            <div class="box-title">
                              <span id="nama1"></span>
                             [<?php echo $nik;?>] 
                          </div>
                          </div>
                              <div class="box-body">
                                   <div id="container"></div>
                                   <?php $period = date('Y',strtotime('01 '.urldecode($tgl))); ?>
                              </div>
                         </div>   
                    </div>

                    <div class="modal fade" id="myModal">
                         <div class="modal-dialog">
                            <div class="modal-content">
                               <div class="modal-header">
                                  <h4 style="float: right;" id="modal-title"></h4>
                                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
                             </div>
                             <div class="modal-body">
                                  <div class="row">
                                     <div class="col-md-12">
                                        NIK : <c id="nik"></c><br>
                                        Nama : <c id="nama"></c><br>
                                        Bulan : <c id="bulan"></c>
                                        <table class="table table-striped table-responsive" style="width: 100%" id="example2">
                                           <thead>
                                              <tr>
                                                 <th>Tanggal</th>
                                                 <th>jam Lembur</th>
                                            </tr>
                                       </thead>
                                       <tbody></tbody>
                                       <tfoot style="background-color: rgb(149,206,255)">
                                         <th>Total</th>
                                         <th></th>
                                    </tfoot>
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
     var nama;
     var nik;
     var tgl;
     $(function () {
          var cat = new Array();

          $.getJSON('<?php echo base_url("ot/report_g/".$nik."/".$period)?>', function(data) {

               var xCategories = [];
               var seriesData = [];
               var i;

               var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/.[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                    i : 0;
               };

               for(i = 0; i < data.length; i++){
                    cat.push(data[i][1]);
                    if(xCategories.indexOf(cat) === -1){
                         xCategories[xCategories.length] = cat;
                    }
                    seriesData.push(data[i][2])
               }

               nama = data[0][3];
               $("#nama1").text(nama);
               nik = data[0][0];
               tgl = data[0][1];

               // for(i = 0; i < data.length; i++){
               //      if(seriesData){
               //           var currSeries = seriesData.filter(function(seriesObject){ return seriesObject.name == data[i][1];});
               //           if(currSeries.length === 0){
               //                seriesData[seriesData.length] = currSeries = {name: data[i][1], data: []};
               //           } else {
               //                currSeries = currSeries[0];
               //           }
               //           var index = currSeries.data.length;
               //           currSeries.data[index] = data[i][2];
               //      } else {
               //           seriesData[0] = {name: data[i][1], data: [intVal(data[i][2])]}
               //      }
               // }

               Highcharts.chart('container', {
                    chart: {
                         type: 'column'
                    },
                    title: {
                         text: '2019'
                    },
                    xAxis: {
                         categories: cat
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
                         },
                         series: {
                            cursor: 'pointer',
                            events: {
                             click: function (event) {
                              detail2(event.point.category);
                         }
                    }
               }
          },
          series: [
          {
             "name": "Monthly Overtime Total",
             "colorByPoint": true,
             "data": seriesData
        }]
   });
          });
     });


     function detail2(tgl2) {
      $('#nik').text(nik);
      $('#nama').text(nama);
      $('#bulan').text(tgl2);

      table2 = $('#example2').DataTable();
      table2.destroy();
      //alert(nik+" "+tgl);
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
          "url": "<?php echo base_url('ot/ajax_ot_report_details_2')?>",
          "type": "GET",
          "data" : {
            nik : nik,
            period : tgl2,
       }
  },
  "columnDefs" : [
  {
     'targets': 1,
     'createdCell':  function (td, cellData, rowData, row, col) {
      $(td).attr('id', 'ct'); 
 }
}],
"footerCallback": function (tfoot, data, start, end, display) {
   var intVal = function ( i ) {
     return typeof i === 'string' ?
     i.replace(/[\$%,]/g, '')*1 :
     typeof i === 'number' ?
     i : 0;
};
var api = this.api();
var totalPlan = api.column(1).data().reduce(function (a, b) {
     return intVal(a)+intVal(b);
}, 0)
$(api.column(1).footer()).html(totalPlan.toFixed(0).toLocaleString());
}
});
 }


</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
Both of these plugins are recommended to enhance the
user experience. -->
</body>
</html>]