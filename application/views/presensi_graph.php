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
          Presence data
          <span class="text-purple">出勤データ</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <?php
          /* Mengambil query report*/
          if(!empty($persentase) && !empty($persentase_tidakMasuk) && !empty($kary)) {
            $arr3 = array();
            $result3 = array();

            foreach($persentase as $r3){
              $tgl3 = date('d F Y', strtotime($r3->tanggal));

              $arr3['name'] = 'Hadir';
              $arr3['y'] = (float) $r3->jml;

              array_push($result3, $arr3);
            }

            $arr5 = array();
            foreach($persentase_tidakMasuk as $r5){
              $arr5['name'] = 'Tidak Hadir';
              $arr5['y'] = (float) $r5->jml;

              array_push($result3, $arr5);
            }

            $arr4 = array();
            foreach($kary as $r4){
              $kurang = $r4->jml - $arr3['y'] - $arr5['y'];
              $arr4['name'] = 'Belum Hadir';
              $arr4['y'] = (float) $kurang;

              array_push($result3, $arr4);
            }
          }
          else {
            $tgl = null;
            $tgl3 = null;
            $result3[] = null;
          }
          ?>

          <div class="alert alert-warning alert-dismissible" id="notif" onclick="check()" style="display: none; cursor: pointer;">
            <h4><i class="icon fa fa-warning"></i> Data Hari ini belum diupload!</h4>
          </div>

          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">
                By Shift 
                <br><span class="text-purple">シフト別</span>
              </a></li>
              <!-- <li><a href="#tab_2" data-toggle="tab">Stat Persentase <span>(%)</span></a></li> -->

              <form action="" method="post" id="tglF">
                <li class="pull-right"><input type="text" class="form-control text-muted" placeholder="Select date" id="datepicker" onchange="postTgl()" name="txtTgl">
                </li>
              </form>

              <li class="pull-right"><label>Date : </label></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div id = "container" style = "width: 850px; margin: 0 auto"></div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div id = "container2" style = "width: 850px; margin: 0 auto"></div>
              </div>
              <!-- /.tab-pane -->
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
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
                            <th>Tanggal</th>
                            <th>NIK</th>
                            <th>Nama karyawan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Shift</th>
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

        <!-- </div> --> 

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

      $('#datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy"
      })
    });

    //---------CHART---------------

    $(function () {
      var processed_json = new Array();
      $.getJSON('<?php echo base_url("home/ajax_presensi_shift/")?>', function(data) {

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
              text: 'Total Karyawan'
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
                    ShowData(data[0].tgl, this.name);
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
            pointFormat: '<span style="color:{point.color}">Shift {point.name}</span>: <b>{point.y}</b> <br/>'
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


    function postTgl() {
      var url = "<?php echo base_url('home/ajax_presensi_shift') ?>";
      $.ajax({
        type: "POST",
        url: url,
        data: $("#tglF").serialize(),
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
                          text: 'Total Absent'
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
                                ShowData(s[0].tgl, this.name);
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

    function ShowData(tgl, by){
      tabel = $('#example2').DataTable();
      tabel.destroy();

      $('#myModal').modal('show');
      $('#example2').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true,
        "serverSide": true,
        "bInfo": false,
        "order": [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax_presensi_cari_g/')?>",            
          "type": "POST",
          "data": {
            tanggal:tgl,
            nik:'',
            nama:'',
            shift:by,
          }
        },
        "columnDefs": [
        {
          "targets": [ 0,1,2,3,4,5 ], //first column / numbering column
          "orderable": false, //set not orderable
        }]
      });
    }


    $(function () {
      $('#container2').highcharts({
        chart : {
         plotBackgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
       },
       title : {
         text: <?php echo json_encode($tgl3) ?>  
       },
       tooltip : {
         pointFormat: '<b>{point.y}</b>'
       },
       plotOptions : {
         pie: {
          allowPointSelect: true,
          cursor: 'pointer',

          dataLabels: {
           enabled: true,
           format: '<b>{point.name}</b> : {point.percentage:.1f}% ',
           style: {
            color: (Highcharts.theme && Highcharts.theme.contrastTextColor)||
            'black'
          }
        },
        showInLegend: true

      }
    },
    credits: {
      enabled: false
    },
    series : [{
     type: 'pie',
     name: 'percentage (%)',
     data: <?php echo json_encode($result3) ?>
   }]
 })
    });
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>