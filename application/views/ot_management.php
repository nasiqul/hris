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
          OT Management By Section
          <span class="text-purple">部門別残業管理</span>
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
                    <select name="tahun" class="form-control" id="tahun" onchange="postTahun()">
                      <?php 
                      foreach ($fiskal as $key) { 
                        if ($key->tanggal < date('Y-m-d')) {
                          echo '<option value="'.$key->fiskal.'" selected>'.$key->fiskal.'</option>';
                        } else {
                          echo '<option value="'.$key->fiskal.'">'.$key->fiskal.'</option>';
                        }
                        ?>
                        
                      <?php } ?>
                    </select>
                  </div>
                  <div class="pull-right" style="margin-right: 20px">

                    Bagian :

                    <select name="section" class="form-control" id="section" onchange="postTahun()">
                      <?php foreach ($section as $key) { ?>
                        <option value="<?php echo $key->id_cc ?>"><?php echo $key->name ?></option>
                      <?php } ?>
                    </select>


                  </div>
                  <div id="progressbar2" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
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

      $("#progressbar2").hide();
    });

    //---------CHART---------------

    $(function () {
      postTahun();
    });
    // })


    function postTahun() {
      $('#load').css('display','block');
      var tahun = $("#tahun").val();
      var section = $("#section").val();

      var url = "<?php echo base_url('ot/ajax_ot_manaj/') ?>"+tahun+"/"+section+"";
      $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        beforeSend: function () {
          $('#progressbar2').show();
        },
        complete: function () {
          $("#progressbar2").hide();
        },
        success: function(data) {
         var processed_json = new Array();
         var seriesData = [];
         var xCategories = [];
         var seriesData = [];
         var i, cat;
         var title = data[0][0][5];

         for(i = 0; i < data[0].length; i++){
          cat = data[0][i][0];
          if(xCategories.indexOf(cat) === -1){
           xCategories[xCategories.length] = cat;
         }
       }

       for(i = 0; i < data[0].length; i++){
        if(seriesData){
          var currSeries = seriesData.filter(function(seriesObject){ return seriesObject.name == data[0][i][3];});
          if(currSeries.length === 0){
            seriesData[seriesData.length] = currSeries = {name: data[0][i][3], data: []};
          }
          else {
            currSeries = currSeries[0];
          }
          var index = currSeries.data.length;
          currSeries.data[index] = data[0][i][4];
        }
        else {
          seriesData[0] = {name: data[0][i][3], data: [intVal(data[0][i][4])]}
        }
      }

      var target = [];
      for (var i = 0; i < data[1].length; i++) {
        target.push(data[1][i][4]);
      }
        // Populate series
        seriesData.push({type: 'spline', name: 'Max OT', data: target, color: 'red', dashStyle: 'dash'});


        $('#container').highcharts({
          chart: {
            type: 'spline'
          },
          title: {
            text: 'YEAR '+title
          },
          xAxis: {
            categories: xCategories
          },
          yAxis: {
            title: {
              text: 'Total Jam'
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
              },
              lineWidth: 1
            }
          },
          credits:{
            enabled:false
          },
          series: seriesData
        });
        $('#load').css('display','none');
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
