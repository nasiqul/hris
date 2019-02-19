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
          <small>Optional description</small>
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
              <li class="active"><a href="#tab_1" data-toggle="tab">Stat By Bagian</a></li>
              <li><a href="#tab_2" data-toggle="tab">Stat By Date</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="col-md-3 pull-right">
                  <form action="" method="post" id="rati">
                    <label>Date : </label>
                    <input type="text" class="form-control text-muted" placeholder="Select date" id="datepicker" onchange="PostMonth()" name="sortBulan">
                  </form>
                </div>
                <div id="container" style ="margin: 0 auto"></div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div id = "container2" style = "width: 850px; margin: 0 auto"></div>
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
      $.getJSON('<?php echo base_url("ot/ajax_ot_graph_bulan/")?>', function(data) {

        for (i = 0; i < data.length; i++){
          processed_json.push([data[i].name, data[i].y]);

          if (data[i].name == null) {
            notif.style.display = "block";
          }
        }

        $('#container2').highcharts({

          title: {
            text: 's[0].tgl'
          },

          yAxis: {
            title: {
              text: 'Number of Employees'
            }
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
          },

          plotOptions: {
            series: {
              label: {
                connectorAllowed: false
              },
              pointStart: 2010
            }
          },

          series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
          }, {
            name: 'Manufacturing',
            data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
          }, {
            name: 'Sales & Distribution',
            data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
          }, {
            name: 'Project Development',
            data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
          }, {
            name: 'Other',
            data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
          }],

          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom'
                }
              }
            }]
          }

        });
      });
    })
    
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>