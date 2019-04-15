<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nikLogin')) { redirect('home/overtime_user'); }?>

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
          Overtime Control New
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">

          <div class="box box-solid">
            <div class="box-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    Select Date : 
                    <input type="text" class="form-control datepicker" id="tgl" onchange="drawChart()">
                  </div>
                  <div class="col-md-12">
                    <div id="container" style="width: 100%; margin: 0px auto; height: 550px"></div>
                  </div>
                </div>
              </div>
              <br><br>
            </div>
          </div>

          <div class="box box-solid">
            <div class="box-body">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-3">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="font-size: 60px; color: #f76111">
                          <span class="description-percentage" id="tot_budget"></span>
                        </h5>      
                        <span class="description-text" style="font-size: 35px;">Total Budget<br><span class="text-purple">???</span></span>   
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="font-size: 60px; ">
                          <span class="description-percentage" id="tot_act" style="color: #7300ab"></span>
                        </h5>      
                        <span class="description-text" style="font-size: 35px;">Total Actual<br><span class="text-purple">???</span></span>   
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="font-size: 60px;">
                          <span class="description-percentage text-green" id="tot_diff"></span>
                        </h5>      
                        <span class="description-text" style="font-size: 35px;">Difference<br><span class="text-purple">???</span></span>   
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="description-block border-right">
                        <h5 class="description-header" style="font-size: 60px;">
                          <span class="description-percentage text-yellow" id="avg"></span>
                        </h5>      
                        <span class="description-text" style="font-size: 35px;">Average<br><span class="text-purple">???</span></span>   
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="myModal">
           <div class="modal-dialog modal-lg">
            <div class="modal-content">
             <div class="modal-header">
              <h4 style="float: right; " id="modal-title"></h4>
              <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
            </div>
            <div class="modal-body">
              <div class="row">
               <div class="col-md-12">
                <table class="table table-bordered table-stripped table-responsive" style="width: 100%" id="example2">
                 <thead>
                   <tr>
                     <th>No</th>
                     <th>NIK</th>
                     <th>Nama</th>
                     <th>Lembur (jam)</th>
                   </tr>
                 </thead>
                 <tbody id="tabelDetail"></tbody>
                 <tfoot>
                   <th>
                    <td colspan="2" style="font-weight: bold; size: 25px; text-align: right;">TOTAL </td>
                    <td id="tot" style="font-weight: bold; size: 25px"></td>
                  </th>
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
    var tot_budget = 0;
    var tot_act = 0;
    var tot_diff = 0;
    $(function () {
      drawChart();
    })

    $('body').toggleClass('sidebar-collapse');

    function drawChart() {
      var tgl = $("#tgl").val();
      $.ajax({
       type: "POST",
       url: "<?php echo base_url('ot/overtime_chart_control') ?>",
       data: {
        tgl : tgl
      },
      dataType: 'json',
      success: function(data) {
        tot_budget = 0;
        tot_act = 0;
        tot_diff = 0;
        avg = 0;
        var xCategories = [];
        var seriesDataBudget = [];
        var seriesDataAktual = [];
        var cat;

        for(var i = 0; i < data[0].length; i++){
          cat = data[0][i][1];
          tot_budget += data[0][i][2];
          tot_act += data[0][i][3];
          seriesDataBudget.push(data[0][i][2]);
          seriesDataAktual.push(data[0][i][3]);
          if(xCategories.indexOf(cat) === -1){
           xCategories[xCategories.length] = cat;
         }
       }

       tot_diff = tot_budget - tot_act;

       tot_budget = Math.round(tot_budget * 100) / 100;
       tot_act = Math.round(tot_act * 100) / 100;
       tot_diff = Math.round(tot_diff * 100) / 100;

       var tot_budget2 = tot_budget.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
       var tot_act2 = tot_act.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

       var tot_diff2 = tot_diff.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

       $("#tot_budget").text(tot_budget2);
       $("#tot_act").text(tot_act2);

       if (tot_diff > 0) {
        $('#tot_diff').removeClass('text-red').addClass('text-green');
        $("#tot_diff").html("<i class='fa fa-caret-up'></i> "+tot_diff2);
      }
      else {
        $('#tot_diff').removeClass('text-green').addClass('text-red');
        $("#tot_diff").html("<i class='fa fa-caret-down'></i> "+tot_diff2);
      }
      avg = tot_act / data[1];
      avg = Math.round(avg * 100) / 100;
      $("#avg").html(avg);

      var interval = Math.ceil(300/10);


      Highcharts.chart('container', {
        chart: {
          type: 'column'
        },
        title: {
          text: '<span style="font-size: 2vw;">Overtime Control</span><br><span style="color: rgba(96, 92, 168);">'+ data[0][0][4] +'</span>'
        },
        credits:{
          enabled:false
        },
        yAxis: {
          tickInterval: 10,
          min:0,
          allowDecimals: false,
          title: {
            text: 'Jumlah Lembur (jam)'
          }
        },
        xAxis: {
          labels: {
            style: {
              color: 'rgba(75, 30, 120)',
              fontSize: '12px',
              fontWeight: 'bold'
            }
          },
          categories: xCategories
        },
        tooltip: {
          formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
            this.point.y + ' ' + this.series.name.toLowerCase();
          }
        },
        plotOptions: {
          series: {
            pointPadding: 0.93,
            cursor: 'pointer',
            point: {
              events: {
                click: function () {
                  modalTampil(this.category, data[0][0][4]);
                }
              }
            },
            minPointLength: 3,
            dataLabels: {
              allowOverlap: true,
              enabled: true,
              y: -25,
              style: {
                color: 'black',
                fontSize: '13px',
                textOutline: false,
                fontWeight: 'bold',
              },
              rotation: -90
            },
            pointWidth: 17,
            pointPadding: 0,
            borderWidth: 0,
            groupPadding: 0
          }
        },
        series: [{
          name: 'Overtime Budget',
          data: seriesDataBudget,
          color: "#f76111"
        }, {
          name: 'Overtime Actual',
          data: seriesDataAktual,
          color: "#7300ab"
        }]
      });
    }
  })
    }

    function modalTampil(costCenter, date) {
      $.ajax({
       type: "POST",
       url: "<?php echo base_url('ot/overtime_control_detail') ?>",
       data: {
        cc : costCenter,
        tgl : date
      },
      dataType: 'json',
      success: function(data) {
        $("#myModal").modal('show');
        $("#tabelDetail").empty();
        var no = 1;
        var jml = 0;
        $("#modal-title").text(costCenter);

        $.each(data, function(i, item) {
          if (item[0] != ""){
            var newdiv1 = $( "<tr>"+                  
              "<td>"+no+"</td><td>"+item[0]+"</td>"+
              "<td>"+item[1]+"</td><td>"+item[2]+"</td>"+
              "</tr>");
            no++;
            jml += item[2];

            $("#tabelDetail").append(newdiv1);
          }
        });

        $("#tot").text(jml);
      }
    })
    }

    $('.datepicker').datepicker({
     autoclose: true,
     format: "dd-mm-yyyy"
   });

 </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>
