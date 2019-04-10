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
                         Overtime Data
                         <span class="text-purple">残業データ</span>

                    </h1>
               </section>

               <!-- Main content -->
               <section class="content container-fluid">
                    <div class="alert alert-warning alert-dismissible" id="notif" onclick="check()" style="display: none; cursor: pointer;">
                         <h4><i class="icon fa fa-warning"></i> Data Bulan ini belum ada!</h4>
                    </div>

                    <div class="col-md-12">
                         <input type="hidden" id="tgls" value="<?php echo date('d-m-Y'); ?>">

                         <div class="nav-tabs-custom">
                              <ul class="nav nav-tabs">
                                   <!-- <li><a href="#tab_1" data-toggle="tab">
                                        By Bagian <br> <span class="text-purple">部門別</span></a>
                                   </li>

                                   <li><a href="#tab_2" data-toggle="tab">
                                        By Date <br> <span class="text-purple">日付別</span></a>
                                   </li> -->

<!-- <li><a href="#tab_3" data-toggle="tab">
By Dep <br> <span class="text-purple">???</span></a>
</li> -->

<li class="active"><a href="#tab_4" data-toggle="tab">
     By Dep 2 <br> <span class="text-purple">???</span></a>
</li>

<li><a href="#tab_5" data-toggle="tab" onclick="tiga_jam()">
     > 3 Jam / Hari<br> <span class="text-purple">???</span></a>
</li>

<li><a href="#tab_6" data-toggle="tab" onclick="empatbelas_jam()">
     > 14 Jam / Minggu<br> <span class="text-purple">???</span></a>
</li>

<li><a href="#tab_7" data-toggle="tab" onclick="tiga_dan_empatbelas_jam()">
     > 3 Jam  & 14 Jam<br> <span class="text-purple">???</span></a>
</li>

<li><a href="#tab_8" data-toggle="tab" onclick="limaenam_jam()">
     > 56 Jam / Bulan<br> <span class="text-purple">???</span></a>
</li>

</ul>
<div class="tab-content">



     <div class="tab-pane active" id="tab_4">
          <div class="row">
               <div class="col-md-3 pull-right">
                    <form action="" method="post" id="rati2">
                         <label>Month : </label>
                         <input type="text" class="form-control text-muted" placeholder="Select Month" id="datepicker2" onchange="date_change()" name="date2">
                    </form>
               </div>
          </div>
          <div id = "container4" style ="width: 90%;margin: 0 auto"></div>
     </div>
     <div class="tab-pane" id="tab_5">
          <p class="3_jam2"></p>
          <table class="table table-bordered table-responsive" style="width: 100%" id="3jam">
               <thead>
                    <tr>
                         <th>NIK</th>
                         <th>Nama Karyawan</th>
                         <th>Departemen</th>
                         <th>Section</th>
                         <th>Kode</th>
                         <th>Rata2 Jam</th>
                    </tr>
               </thead>
               <tbody></tbody>
          </table>
     </div>
     <div class="tab-pane" id="tab_6">
          <p class="14_jam2"></p>
          <table class="table table-bordered table-responsive" style="width: 100%" id="14jam">
               <thead>
                    <tr>
                         <th>NIK</th>
                         <th>Nama Karyawan</th>
                         <th>Departemen</th>
                         <th>Section</th>
                         <th>Kode</th>
                         <th>Rata2 Jam</th>
                    </tr>
               </thead>
               <tbody></tbody>
          </table>
     </div>
     <div class="tab-pane" id="tab_7">
          <p class="3_14jam2"></p>
          <table class="table table-bordered table-responsive" style="width: 100%" id="3_14jam">
               <thead>
                    <tr>
                         <th>NIK</th>
                         <th>Nama Karyawan</th>
                         <th>Departemen</th>
                         <th>Section</th>
                         <th>Kode</th>
                         <th>Rata2 Jam</th>
                    </tr>
               </thead>
               <tbody></tbody>
          </table>
     </div>
     <div class="tab-pane" id="tab_8">
          <p class="56jam2"></p>
          <table class="table table-bordered table-responsive" style="width: 100%" id="56jam">
               <thead>
                    <tr>
                         <th>NIK</th>
                         <th>Nama Karyawan</th>
                         <th>Departemen</th>
                         <th>Section</th>
                         <th>Kode</th>
                         <th>Rata2 Jam</th>
                    </tr>
               </thead>
               <tbody></tbody>
          </table>
     </div>
     <div class="tab-pane" id="tab_9">

     </div>
     <!-- /.tab-pane -->
     <!-- /.tab-pane -->
</div>
<!-- /.tab-content -->
</div>

<div class="modal fade" id="myModal">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">
               <div class="modal-header">
                    <h4 style="float: right;" id="modal-title"></h4>
                    <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
               </div>
               <div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                              <table id="example2" class="table table-striped table-bordered" style="width: 100%;"> 
                                   <thead>
                                        <tr>
                                             <th>Departemen</th>
                                             <th>Tanggal</th>
                                             <th>Nama karyawan</th>
                                             <th>Lembur (jam)</th>
                                             <th>Keperluan</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                   </tbody>
                                   <tfoot id="tableFootStock" style="background-color: #ddd">
                                        <th colspan="3" style="text-align: right;">Total : </th>
                                        <th colspan="2"></th>
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

<div class="modal fade" id="myModal2">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">
               <div class="modal-header">
                    <h4 style="float: right;" id="modal-title"></h4>
                    <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
               </div>
               <div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                              <table id="example3" class="table table-striped table-bordered" style="width: 100%;"> 
                                   <thead>
                                        <tr>
                                             <th>NIK</th>
                                             <th>Nama karyawan</th>
                                             <th>Departemen</th>
                                             <th>Section</th>
                                             <th>Kode</th>
                                             <th>Avg (jam)</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                   </tbody>
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


<div class="modal fade" id="myModal5">
     <div class="modal-dialog modal-lg">
          <div class="modal-content">
               <div class="modal-header">
                    <h4 style="float: right;" id="modal-title"></h4>
                    <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
               </div>
               <div class="modal-body">
                    <div class="row">
                         <div class="col-md-12">
                              <table id="example5" class="table table-striped table-bordered" style="width: 100%;"> 
                                   <thead>
                                        <tr>
                                             <th>NIK</th>
                                             <th>Nama karyawan</th>
                                             <th>Departemen</th>
                                             <th>Section</th>
                                             <th>Jam</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                   </tbody>
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
     var notif = document.getElementById("notif");
     var tgl = $("#tgls").val();
     $("#3_jam2").html("as");
     $("#14_jam2").html(tgl);
     $("#3_14jam2").html(tgl);
     $("#56_jam2").html(tgl);

     function check() {
          notif.style.display = "none";
     }

     $(document).ready(function() {

          $(window).on('hashchange', function(e){
               alert('Anda Pindah');
          });

          $('#datepicker').datepicker({
               autoclose: true,
               format: "mm-yyyy",
               viewMode: "months", 
               minViewMode: "months"
          });

          $('#datepicker3').datepicker({
               autoclose: true,
               format: "mm-yyyy",
               viewMode: "months", 
               minViewMode: "months"
          });

          $('#datepicker2').datepicker({
               autoclose: true,
               format: "mm-yyyy",
               viewMode: "months", 
               minViewMode: "months"
          });

     });

     function TampilModal(tgl, by) {
          tabel = $('#example2').DataTable();
          tabel.destroy();

          $('#myModal').modal('show');
          $('#example2').DataTable({
               "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
               "processing": true,
               "serverSide": true,
               "searching": true,
               "bPaginate": false,
               "bLengthChange": false,
               "bFilter": false,
               "bInfo": false,
               "order": [],
               "ajax": {
                    "url": "<?php echo base_url('ot/ajax_modal_g')?>",            
                    "type": "POST",
                    "data": {
                         tanggal:tgl,
                         departemen:by
                    }
               },
               "columnDefs": [
               {
"targets": [ 0,1,2,3,4 ], //first column / numbering column
"orderable": false, //set not orderable
}],
"footerCallback": function (tfoot, data, start, end, display) {
     var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$%,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
     };
     var api = this.api();
     var totalPlan = api.column(3).data().reduce(function (a, b) {
          return intVal(a)+intVal(b);
     }, 0)
     $(api.column(3).footer()).html(totalPlan.toLocaleString());

}
});

     }

//   CHARTT
//      $(function () {
//           var cat = new Array();
//           var tiga_jam = new Array();
//           var per_minggu = new Array();
//           var per_bulan = new Array();
//           var manam_bulan = new Array();

//           $.getJSON('<?php // echo base_url("ot/overtime_chart/")?>', function(data) {

//                for (i = 0; i < data.length; i++){
//                     cat.push(data[i][0]);
//                     tiga_jam.push(parseInt(data[i][1]));
//                     per_minggu.push(parseInt(data[i][2]));
//                     per_bulan.push(parseInt(data[i][3]));
//                     manam_bulan.push(parseInt(data[i][4]));
//                }

//                $('#container3').highcharts({
//                     chart: {
//                          type: 'line',
//                          backgroundColor : "#3d3f3f",   
//                     },
//                     legend: {
//                          align: 'right',
//                          verticalAlign: 'top',
//                          layout: 'vertical',
//                          x: 0,
//                          y: 100,
//                          itemStyle: {
//                               color: '#FFF'
//                          },
//                          itemHoverStyle: {
//                               color: '#DDD'
//                          },
//                          itemHiddenStyle: {
//                               color: '#616363'
//                          }
//                     },
//                     exporting : {
//                          enabled : true
//                     },
//                     title: {
//                          text: data[0][5],
//                          style: {
//                               color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
//                          }
//                     },
//                     xAxis: {
//                          gridLineWidth: 1,
//                          gridLineColor: "#7a7c7c",
//                          categories: cat,
//                          labels: {
//                               style: {
//                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
//                               }
//                          }
//                     },
//                     yAxis: {
//                          min:0,
//                          gridLineColor: "#7a7c7c",
//                          title: {
//                               text: 'Jumlah (orang)',
//                               style: {
//                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
//                               }
//                          },
//                          labels: {
//                               style: {
//                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
//                               }
//                          }
//                     },
//                     plotOptions: {
//                          line: {
//                               dataLabels: {
//                                    enabled: true,
//                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
//                               },
//                               enableMouseTracking: true
//                          },
//                          series: {
//                               cursor: 'pointer',
//                               point: {
//                                    events: {
//                                         click: function(e) {  
//                                              show(data[0][5], this.category, this.series.name);
//                                         }
//                                    }
//                               }
//                          }
//                     },
//                     credits: {
//                          enabled: false
//                     },
//                     series: [{
//                          name: 'OT > 3 JAM / HARI',
//                          color: '#2598db',
//                          shadow: {
//                               color: '#2598db',
//                               width: 7,
//                               offsetX: 0,
//                               offsetY: 0
//                          },
//                          data: tiga_jam
//                     }, {
//                          name: 'OT > 14 JAM / MGG',
//                          color: '#f2ad96',
//                          shadow: {
//                               color: '#f2ad96',
//                               width: 7,
//                               offsetX: 0,
//                               offsetY: 0
//                          },
//                          data: per_minggu
//                     },
//                     {
//                          name: 'OT > 3 dan > 14 Jam',
//                          color: '#f90031',
//                          shadow: {
//                               color: '#f90031',
//                               width: 7,
//                               offsetX: 0,
//                               offsetY: 0
//                          },
//                          data: per_bulan
//                     },
//                     {
//                          name: 'OT > 56 JAM / BLN',
//                          color: '#d756f7',
//                          shadow: {
//                               color: '#d756f7',
//                               width: 7,
//                               offsetX: 0,
//                               offsetY: 0
//                          },
//                          data: manam_bulan
//                     }]

//                });
// });
// })

function show(tgl, cat, kode) {
     tabel = $('#example3').DataTable();
     tabel.destroy();

     $('#myModal2').modal('show');

     $('#example3').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_ot_g_detail/')?>",            
               "type": "GET",
               "data": {
                    tgl : tgl,
                    kode : kode,
                    cat: cat
               }
          }
     });
}

$(function () {
     var cat = new Array();
     var tiga_jam = new Array();
     var per_minggu = new Array();
     var per_bulan = new Array();
     var manam_bulan = new Array();

     $.getJSON('<?php echo base_url("ot/overtime_chart2/")?>', function(data) {

          for (i = 0; i < data.length; i++){
               cat.push(data[i][0]);
               tiga_jam.push(parseInt(data[i][1]));
               per_minggu.push(parseInt(data[i][2]));
               per_bulan.push(parseInt(data[i][3]));
               manam_bulan.push(parseInt(data[i][4]));
          }

          tgl = data[0][5];
          console.log(tgl);

          $('#container4').highcharts({
               chart: {
                    type: 'line',
                    backgroundColor : "#3d3f3f",   
               },
               legend: {
                    itemStyle: {
                         color: '#FFF'
                    },
                    itemHoverStyle: {
                         color: '#DDD'
                    },
                    itemHiddenStyle: {
                         color: '#616363'
                    }
               },
               exporting : {
                    enabled : true,
                    buttons: {
                         contextButton: {
                              align: 'right',
                              x: -25
                         }
                    }
               },
               title: {
                    text: data[0][5],
                    style: {
                         color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
               },
               xAxis: {
                    gridLineWidth: 1,
                    gridLineColor: "#7a7c7c",
                    categories: cat,
                    labels: {
                         style: {
                              color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                         }
                    }
               },
               yAxis: {
                    min:0,
                    gridLineColor: "#7a7c7c",
                    title: {
                         text: 'Jumlah (orang)',
                         style: {
                              color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                         }
                    },
                    labels: {
                         style: {
                              color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                         }
                    }
               },
               plotOptions: {
                    line: {
                         dataLabels: {
                              enabled: true,
                              color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                         },
                         enableMouseTracking: true
                    },
                    series: {
                         cursor: 'pointer',
                         point: {
                              events: {
                                   click: function(e) {  
                                        show2(data[0][5], this.category, this.series.name);
                                   }
                              }
                         }
                    }
               },
               credits: {
                    enabled: false
               },
               series: [{
                    name: 'OT>3 JAM / HARI',
                    color: '#2598db',
                    shadow: {
                         color: '#2598db',
                         width: 7,
                         offsetX: 0,
                         offsetY: 0
                    },
                    data: tiga_jam
               }, {
                    name: 'OT>14 JAM / MGG',
                    color: '#f2ad96',
                    shadow: {
                         color: '#f2ad96',
                         width: 7,
                         offsetX: 0,
                         offsetY: 0
                    },
                    data: per_minggu
               },
               {
                    name: 'OT>3 & >14 JAM',
                    color: '#f90031',
                    shadow: {
                         color: '#f90031',
                         width: 7,
                         offsetX: 0,
                         offsetY: 0
                    },
                    data: per_bulan
               },
               {
                    name: 'OT>56 JAM / BLN',
                    color: '#d756f7',
                    shadow: {
                         color: '#d756f7',
                         width: 7,
                         offsetX: 0,
                         offsetY: 0
                    },
                    data: manam_bulan
               }]

          });
});
})

function date_change() {
     tgl = $('#datepicker2').val();
     $("#3_jam2").text(tgl);
     $("#14_jam2").text(tgl);
     $("#3_14jam2").text(tgl);
     $("#56_jam2").text(tgl);

     $('#tgls').val('10-'+tgl);
     console.log(tgl);

     var url = "<?php echo base_url('ot/overtime_chart2') ?>";
     $.ajax({
          type: "POST",
          url: url,
          data: {
               tgl:tgl
          },
          success: function(data) {
               var s = $.parseJSON(data);

               var cat = new Array();
               var tiga_jam = new Array();
               var per_minggu = new Array();
               var per_bulan = new Array();
               var manam_bulan = new Array();

// Populate series
for (i = 0; i < s.length; i++){
     cat.push(s[i][0]);
     tiga_jam.push(parseInt(s[i][1]));
     per_minggu.push(parseInt(s[i][2]));
     per_bulan.push(parseInt(s[i][3]));
     manam_bulan.push(parseInt(s[i][4]));

}

$('#container4').highcharts({
     chart: {
          type: 'line',
          backgroundColor : "#3d3f3f",   
     },
     legend: {
          align: 'right',
          verticalAlign: 'top',
          layout: 'vertical',
          x: 0,
          y: 100,
          itemStyle: {
               color: '#FFF'
          },
          itemHoverStyle: {
               color: '#DDD'
          },
          itemHiddenStyle: {
               color: '#616363'
          }
     },
     exporting : {
          enabled : true
     },
     title: {
          text: s[0][5],
          style: {
               color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
          }
     },
     xAxis: {
          gridLineWidth: 1,
          gridLineColor: "#7a7c7c",
          categories: cat,
          labels: {
               style: {
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
               }
          }
     },
     yAxis: {
          min:0,
          gridLineColor: "#7a7c7c",
          title: {
               text: 'Jumlah (orang)',
               style: {
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
               }
          },
          labels: {
               style: {
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
               }
          }
     },
     plotOptions: {
          line: {
               dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
               },
               enableMouseTracking: true
          },
          series: {
               cursor: 'pointer',
               point: {
                    events: {
                         click: function(e) {  
                              show2(s[0][5], this.category, this.series.name);
                         }
                    }
               }
          }
     },
     credits: {
          enabled: false
     },
     series: [{
          name: 'OT>3 JAM / HARI',
          color: '#2598db',
          shadow: {
               color: '#2598db',
               width: 7,
               offsetX: 0,
               offsetY: 0
          },
          data: tiga_jam
     }, {
          name: 'OT>14 JAM / MGG',
          color: '#f2ad96',
          shadow: {
               color: '#f2ad96',
               width: 7,
               offsetX: 0,
               offsetY: 0
          },
          data: per_minggu
     },
     {
          name: 'OT>3 & >14 JAM',
          color: '#f90031',
          shadow: {
               color: '#f90031',
               width: 7,
               offsetX: 0,
               offsetY: 0
          },
          data: per_bulan
     },
     {
          name: 'OT>56 JAM / BLN',
          color: '#d756f7',
          shadow: {
               color: '#d756f7',
               width: 7,
               offsetX: 0,
               offsetY: 0
          },
          data: manam_bulan
     }]

});

}
})
}


function show2(tgl, cat, kode) {
     tabel = $('#example3').DataTable();
     tabel.destroy();

     $('#myModal2').modal('show');

     $('#example3').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_ot_g_detail2/')?>",            
               "type": "GET",
               "data": {
                    tgl : tgl,
                    kode : kode,
                    cat: cat
               }
          }
     });
}

function tiga_jam() {
     var tabel0 = $('#3jam').DataTable();
     tabel0.destroy();
     var ttanggal = $("#tgls").val();

     tabel0 = $('#3jam').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_ot_jam/')?>",            
               "type": "GET",
               "data": {
                    tgl2 : ttanggal,
                    cat: "3jam"
               }
          }
     })
}


function empatbelas_jam() {
     var tabel9 = $('#14jam').DataTable();
     tabel9.destroy();
     var ttanggal = $("#tgls").val();

     tabel9 = $('#14jam').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_ot_jam/')?>",            
               "type": "GET",
               "data": {
                    tgl2 : ttanggal,
                    cat: "14jam"
               }
          }
     })
}


function tiga_dan_empatbelas_jam() {
     var tabel8 = $('#3_14jam').DataTable();
     tabel8.destroy();
     var ttanggal = $("#tgls").val();

     tabel8 = $('#3_14jam').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_ot_jam/')?>",            
               "type": "GET",
               "data": {
                    tgl2 : ttanggal,
                    cat: "3_14jam"
               }
          }
     })
}


function limaenam_jam() {
     var tabel7 = $('#56jam').DataTable();
     tabel7.destroy();
     var ttanggal = $("#tgls").val();

     tabel7 = $('#56jam').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_ot_jam/')?>",            
               "type": "GET",
               "data": {
                    tgl2 : ttanggal,
                    cat: "56jam"
               }
          }
     })
}

function gantiTgl() {
     var tanggal2 = $("#datepicker3").val();
     var ttanggal2 = $("#tgls2").val('10-'+tanggal2);

     $.ajax({
          type: "POST",
          url: "<?php echo base_url('home/ajax_dep_over/') ?>"+$("#tgls2").val()+"",
          success: function(data) {
               var s = $.parseJSON(data);
               var processed_json5 = new Array();
// Populate series
for (i = 0; i < s.length; i++){
     processed_json5.push([s[i].name, parseFloat(s[i].y)]);

}

if (s[0].name == null) {
     notif.style.display = "block";
}

$('#container2').highcharts({
     chart: {
          type: 'column'
     },
     title: {
          text: s[0].tgl
     },
     xAxis: {
          type: 'category'
     },
     yAxis: {
          title: {
               text: 'Jumlah Lembur (Jam)'
          }
     },
     legend: {
          enabled: false
     },
     plotOptions: {
          series: {
               cursor: 'pointer',
               point: {
                    events: {
                         click: function () {
                              TampilKaryawan(s[0].tgl, this.name);
                         }
                    }
               },
               borderWidth: 0,
               dataLabels: {
                    enabled: true,
                    format: '{point.y}'
               }
          }
     },
     credits: {
          enabled: false
     },

     tooltip: {
          headerFormat: '',
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> <br/>'
     },

     "series": [
     {
          "name": "By Date",
          "colorByPoint": true,
          "data": processed_json5
     }
     ]
})

}
});
}

function TampilKaryawan(tgl,bagian) {
     tabel = $('#example5').DataTable();
     tabel.destroy();

     $('#myModal5').modal('show');

     $('#example5').DataTable({
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "processing": true,
          "serverSide": true,
          "searching": true,
          "bLengthChange": true,
          "order": [],
          "ajax": {
               "url": "<?php echo base_url('ot/ajax_dp_g_detail/')?>",
               "type": "GET",
               "data": {
                    tgl : tgl,
                    bagian : bagian
               }
          }
     });
}


</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
Both of these plugins are recommended to enhance the
user experience. -->
</body>
</html>


