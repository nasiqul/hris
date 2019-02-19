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
          Employee Data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <?php
        /* Mengambil query report*/
        $arr2 = array();
        $result2 = array();
        foreach($status as $r1){

          $arr2['name'] = $r1->statusKaryawan;
          $arr2['y'] = (int) $r1->jml;

          array_push($result2, $arr2);
        }

        $arr = array();
        $result = array();
        foreach($gender as $r2){

          if ($r2->jk == "L") {
            $arr['name'] = "Male";
          }

          if ($r2->jk == "P") {
            $arr['name'] = "Female";
          }
          $arr['y'] = (float) $r2->jml;

          array_push($result, $arr);
        }

        $arr3 = array();
        $result3 = array();
        foreach($grade as $r3){

          $arr3['name'] = $r3->grade;
          $arr3['y'] = (int) $r3->jml;

          array_push($result3, $arr3);
        }

        $arr4 = array();
        $result4 = array();
        foreach($kode as $r4){

          $arr4['name'] = $r4->kode;
          $arr4['y'] = (int) $r4->jml;

          array_push($result4, $arr4);
        }

        $arr5 = array();
        $result5 = array();
        foreach($posisi as $r5){

          $arr5['name'] = $r5->jabatan;
          $arr5['y'] = (int) $r5->jml;

          array_push($result5, $arr5);
        }
        ?>

        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Stat By Status</a></li>
              <li><a href="#tab_2" data-toggle="tab">Stat By Gender</a></li>
              <li><a href="#tab_3" data-toggle="tab">Stat By Grade</a></li>
              <li><a href="#tab_4" data-toggle="tab">Stat By Departemen</a></li>
              <li><a href="#tab_5" data-toggle="tab">Stat By Position</a></li>
              <li><a href="#tab_6" data-toggle="tab">Stat By Dep</a></li>
              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div id = "container" style = "width: 850px; margin: 0 auto"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div id = "container2" style = "width: 750px; margin: 0 auto"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <div id = "container3" style = "width: 850px; margin: 0 auto"></div>
              </div>

              <div class="tab-pane" id="tab_4">
                <div id = "container4" style = "width: 850px; margin: 0 auto"></div>
              </div>

              <div class="tab-pane" id="tab_5">
                <div id = "container5" style = "width: 850px; margin: 0 auto"></div>
              </div>

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.modal-dialog -->
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
    //---------CHART---------------

    $(function () {
      $('#container').highcharts({
        chart: {
          type: 'column'
        },
        title: {
          text: ''
        },
        xAxis: {
          type: 'category'
        },
        yAxis: {
          type: 'logarithmic',
          title: {
            text: 'Total Employee'
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
                  ShowData(this.name,'','','');
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
          "name": "By Status",
          "colorByPoint": true,
          "data": <?php echo json_encode($result2) ?>
        }
        ]
      })
    });

    $(function () {
      $('#container2').highcharts({
        chart : {
         plotBackgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
       },
       title : {
         text: 'By Gender'   
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
     name: 'By Gender',
     data: <?php echo json_encode($result) ?>
   }]
 })
    });

    $(function () {
      $('#container3').highcharts({
        chart: {
          type: 'column'
        },
        title: {
          text: ''
        },
        xAxis: {
          type: 'category'
        },
        yAxis: {
          type: 'logarithmic',
          title: {
            text: 'Total Employee'
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
                  ShowData('',this.name,'','');
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
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
        },

        "series": [
        {
          "colorByPoint": true,
          name: 'By Grade',
          "data": <?php echo json_encode($result3) ?>
        }
        ]
      })
    });


    $(function () {
      $('#container4').highcharts({
        chart: {
          type: 'column'
        },
        title: {
          text: ''
        },
        xAxis: {
          type: 'category'
        },
        yAxis: {
          type: 'logarithmic',
          title: {
            text: 'Total Employee'
          }

        },
        legend: {
          enabled: false
        },
        plotOptions: {
          series: {
            borderWidth: 0,
            cursor: 'pointer',
            point: {
              events: {
                click: function () {
                  ShowData('','',this.name,'');
                }
              }
            },
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
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
        },

        "series": [
        {
          "name": "By Departemen",
          "colorByPoint": true,
          "data": <?php echo json_encode($result4) ?>
        }
        ]
      })
    });


    $(function () {
      $('#container5').highcharts({
        chart: {
          type: 'column'
        },
        title: {
          text: ''
        },
        xAxis: {
          type: 'category'
        },
        yAxis: {
        type: 'logarithmic',
          title: {
            text: 'Total Employee'
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
                  ShowData('','','',this.name);
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
          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
        },

        "series": [
        {
          "colorByPoint": true,
          "name": "By Position",
          "data": <?php echo json_encode($result5) ?>
        }
        ]
      })
    });

    function ShowData(status,grade,dep,pos){
      $.ajax({
        type: "POST", 
        url : "<?php echo base_url('home/karyawan/')?>" ,       
        data: {
          status:status,
          grade:grade,
          dep:dep,
          pos:pos
        },
        success: function(data) {
          window.location.href = "<?php echo base_url('home/karyawan/')?>";
        }
      });
    }

    function ShowData2(bulan){
      $.ajax({
        type: "POST", 
        url : "<?php echo base_url('home/karyawan/')?>" ,       
        data: {
          bulan:bulan
        },
        success: function(data) {
          window.location.href = "<?php echo base_url('home/karyawan/')?>";
        }
      });
    }
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>