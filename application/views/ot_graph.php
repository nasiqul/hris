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
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li><a href="#tab_1" data-toggle="tab">
                By Bagian <br> <span class="text-purple">部門別</span></a>
              </li>

              <li><a href="#tab_2" data-toggle="tab">
                By Date <br> <span class="text-purple">日付別</span></a>
              </li>

              <li><a href="#tab_3" data-toggle="tab">
                By Dep <br> <span class="text-purple">???</span></a>
              </li>

              <li class="active"><a href="#tab_4" data-toggle="tab">
                By Dep 2 <br> <span class="text-purple">???</span></a>
              </li>

              <!-- <li><a href="#tab_4" data-toggle="tab">
                > 3 Jam <br> <span class="text-purple">???</span></a>
              </li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane" id="tab_1">
                <div class="row">
                  <div class="col-md-3 pull-right">
                    <form action="" method="post" id="rati">
                      <label>Date : </label>
                      <input type="text" class="form-control text-muted" placeholder="Select date" id="datepicker" onchange="PostMonth()" name="sortBulan">
                    </form>
                  </div>
                </div>
                <div id="container" style ="margin: 0 auto"></div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-md-3 pull-right">
                    <form action="" method="post" id="rati">
                      <label>Year : </label>
                      <input type="text" class="form-control text-muted" placeholder="Select year" id="datepicker" onchange="PostMonth()" name="sortBulan">
                    </form>
                  </div>
                </div>
                <div id = "container2" style ="margin: 0 auto"></div>
              </div>
              <div class="tab-pane" id="tab_3">
                <div id = "container3" style ="margin: 0 auto"></div>
              </div>
              <div class="tab-pane active" id="tab_4">
                <div id = "container4" style ="margin: 0 auto"></div>
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
                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
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
                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
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
    })
   });

    $(function () {
      var processed_json = new Array();
      $.getJSON('<?php echo base_url("ot/ajax_ot_graph/")?>', function(data) {

        for (i = 0; i < data.length; i++){
          processed_json.push([data[i].name, data[i].y]);

          if (data[i].name == null) {
            notif.style.display = "block";
          }
        }

        $('#container').highcharts({
          chart: {
            type: 'column'
          },
          title: {
            text: data[0].tgl
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
                    TampilModal(data[0].tgl,this.name);
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
            "name": "By Shift",
            "colorByPoint": true,
            "data": processed_json
          }
          ]
        })
      })
    });


    function PostMonth(){
      var url = "<?php echo base_url('ot/ajax_ot_graph') ?>";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#rati").serialize(),
        success: function(data) {
          var s = $.parseJSON(data);
          var processed_json = new Array();
      // Populate series
      for (i = 0; i < s.length; i++){
        processed_json.push([s[i].name, s[i].y]);

        if (s[i].name == null) 
          notif.style.display = "block";
        else
          notif.style.display = "none";
      }

      $('#container').highcharts({
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
                  TampilModal(s[0].tgl, this.name);
                  // alert(s[0].tgl);
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
          "name": "By Absent",
          "colorByPoint": true,
          "data": processed_json
        }
        ]
      })

    }
  });

    }

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

// LINE CHART
$(function () {
  // $.getJSON('<?php //echo base_url("ot/ajax_ot_graph_bulan/")?>', function(data) {

  //   for (i = 0; i < data.length; i++){
  //     processed_json.push([data[i].name, data[i].y]);

  //     if (data[i].name == null) {
  //       notif.style.display = "block";
  //     }
  //   }

  $('#container2').highcharts({
    chart: {
      type: 'line'
    },
    title: {
      text: 'Overtime'
    },
    xAxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
      title: {
        text: 'Total Jam (Jam)'
      }
    },
    plotOptions: {
      line: {
        dataLabels: {
          enabled: true
        },
        enableMouseTracking: false
      }
    },
    credits: {
      enabled: false
    },
    series: [{
      name: 'MIS',
      data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
    }, {
      name: 'PE',
      data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
    }]

  });
})



$(function () {
  var cat = new Array();
  var tiga_jam = new Array();
  var per_minggu = new Array();
  var per_bulan = new Array();
  var manam_bulan = new Array();

  $.getJSON('<?php echo base_url("ot/overtime_chart/")?>', function(data) {

    for (i = 0; i < data.length; i++){
      cat.push(data[i][0]);
      tiga_jam.push(parseInt(data[i][1]));
      per_minggu.push(parseInt(data[i][2]));
      per_bulan.push(parseInt(data[i][3]));
      manam_bulan.push(parseInt(data[i][4]));
    }

    $('#container3').highcharts({
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
              show(data[0][5], this.category, this.series.name);
            }
          }
        }
      }
    },
    credits: {
      enabled: false
    },
    series: [{
      name: 'OT > 3 JAM / HARI',
      color: '#2598db',
      shadow: {
        color: '#2598db',
        width: 7,
        offsetX: 0,
        offsetY: 0
      },
      data: tiga_jam
    }, {
      name: 'OT > 14 JAM / MGG',
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
      name: 'OT > 3 dan > 14 Jam',
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
      name: 'OT > 56 JAM / BLN',
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
      name: 'OT > 3 JAM / HARI',
      color: '#2598db',
      shadow: {
        color: '#2598db',
        width: 7,
        offsetX: 0,
        offsetY: 0
      },
      data: tiga_jam
    }, {
      name: 'OT > 14 JAM / MGG',
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
      name: 'OT > 3 dan > 14 Jam',
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
      name: 'OT > 56 JAM / BLN',
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

</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>


