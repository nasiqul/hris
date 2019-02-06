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
          Absent data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="box">
            <div class="box-body">

              <div class="alert alert-warning alert-dismissible" data-dismiss="alert" aria-hidden="true">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Data Hari ini belum diupload!</h4>
              </div>

              <form action="" method="post" id="rati">
                <label>Date : </label>
                <input type="text" name="sortTgl" style="width:130px;" onchange="a()" id="datepicker" placeholder="Select date" class="form-control">
              </form>
              <div id ="container" style = "width: 850px; margin: 0 auto"></div>
            </div>
          </div>
          <!-- nav-tabs-custom -->
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
  <script >
    //---------CHART---------------
    $(function () {
      var processed_json = new Array();
      $.getJSON('<?php echo base_url("home/ajax_absen_g/")?>', function(data) {

        for (i = 0; i < data.length; i++){
          processed_json.push([data[i].name, data[i].y]);
        }
        
        // Populate series

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

                    var split2 = data[0].tgl.split("-");
                    var split3 = [split2[1],split2[0],split2[2]].join("/");

                    ShowModal(split3, this.name);
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
      });
    });

    function a() {
      var url = "<?php echo base_url('home/ajax_absen_g') ?>";
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
                                ShowModal(s[0].tgl, this.name);
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


    function ShowModal(tgl, by){
      var t = tgl.split("-");
      var tanggal = new Date(t[2], t[1]-1, t[0]);
      var absensi = by;
      var day = ("0"+tanggal.getDate()).slice(-2);
      var month = ("0"+(tanggal.getMonth()+1)).slice(-2);
      var year = tanggal.getFullYear();
      var tanggal2 = [day,month,year].join("/");

      $.ajax({

        type: "POST", 
        url : "<?php echo base_url('home/absen/')?>" ,       
        data: {
          tanggal:tanggal2,
          nik:'',
          nama:'',
          absensi:absensi,
        },
        success: function(data) {
          window.location.href = "<?php echo base_url('home/absen/')?>";
        }
      });
    }

    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
    })
  </script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>

