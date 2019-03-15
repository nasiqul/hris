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
          OT - Management Section
          <span class="text-purple">???</span>
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
            <h4><i class="icon fa fa-warning"></i> Data Tahun ini belum diupload!</h4>
          </div>

          
          <div class="box box-solid">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="pull-right">
                    Tahun : 
                    <input type="text" name="tahun" id="tahun" class="form-control" onchange="postTahun()">
                  </div>
                  <div class="pull-right" style="margin-right: 20px">
                    Section : 
                    <input type="text" name="section" id="section" class="form-control">
                  </div>
                </div>
                <div id="container" style ="width: 95%;margin: 0 auto"></div>
              </div>
            </div>
          </div>
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

      $('#tahun').datepicker({
        autoclose: true,
        format: "yyyy",
        viewMode: "years", 
        minViewMode: "years"
      })
    });

    //---------CHART---------------

    $(function () {
      // var processed_json = new Array();
      // var tahun = $("#tahun").val();
      // var section = $("#section").val();
      // $.getJSON('<?php // echo base_url("ot/ajax_ot_manaj/")?>'+tahun+'/'+section, function(data) {

      //   for (i = 0; i < data.length; i++){
      //     processed_json.push(data[i].name, data[i].data);

      //     if (data[i].name == null) {
      //       notif.style.display = "block";
      //     }
      //   }

      $('#container').highcharts({
        chart: {
          type: 'line'
        },
        title: {
          text: 'YEAR 20??'
        },
        xAxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
          title: {
            text: 'Temperature (°C)'
          }
        },
        legend: {
          enabled: false
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: false
            },
            enableMouseTracking: true
          },
          series: {
            marker: {
              enabled: false
            }
          }
        },
        series: [{
          "name": 'Ali',
          "data": [[7.0], [6.9], [9.5], [14.5], [18.4], 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
          "name": 'Nasiqul',
          "data": [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
      });
    });
    // })


    function postTahun() {
      var tahun = $("#tahun").val();
      var section = $("#section").val();

      var url = "<?php echo base_url('ot/ajax_ot_manaj/') ?>"+tahun+"/"+section+"";
      $.ajax({
        type: "POST",
        url: url,
        success: function(data) {
          var s = $.parseJSON(data);
          var processed_json = new Array();
          var seriesData = [];
          var nama;
                    // Populate series

                    for(i = 0; i < data.length; i++){
                      nama = data[i].name;
                    }

                    // for(i = 0; i < data.length; i++){
                    //   if(seriesData){
                    //     var currSeries = seriesData.filter(function(seriesObject){ return seriesObject.name == data[i].hpl;});
                    //     if(currSeries.length === 0){
                    //       seriesData[seriesData.length] = currSeries = {name: data[i].hpl, data: []};
                    //     } else {
                    //       currSeries = currSeries[0];
                    //     }
                    //     var index = currSeries.data.length;
                    //     currSeries.data[index] = data[i].actual;
                    //   } else {
                    //     seriesData[0] = {name: data[i].hpl, data: [data[i].actual]}
                    //   }

                    //   seriesData[i] = {name: data[i].hpl, data: [data[i].actual]}
                    // }


                    // for (i = 0; i < s.length; i++){

                    //   processed_json.push(s[i].name);

                    //   for (z = 0; z < s[i].data.length; z++) {

                    //   }
                    //   processed_json.push([{"name":s[i].name}]);

                    //   if (s[i].name == null) 
                    //     notif.style.display = "block";
                    //   else
                    //     notif.style.display = "none";
                    // }

                    $('#container').highcharts({
                      chart: {
                        type: 'line'
                      },
                      title: {
                        text: 'YEAR 20??'
                      },
                      xAxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                      },
                      yAxis: {
                        title: {
                          text: 'Temperature (°C)'
                        }
                      },
                      legend: {
                        enabled: false
                      },
                      plotOptions: {
                        line: {
                          dataLabels: {
                            enabled: false
                          },
                          enableMouseTracking: true
                        },
                        series: {
                          marker: {
                            enabled: false
                          }
                        }
                      },
                      series: s
                    });

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

</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>
