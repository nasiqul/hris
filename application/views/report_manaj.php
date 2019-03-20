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
          Attendance Rate
          <span class="text-purple">出勤率</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <?php
          //check if
          if(!empty($report1) && !empty($report2)) {

            foreach($report1 as $result1){
            $bln = date('F Y', strtotime($result1->tanggal));
            $tgl1[] = date('d M Y', strtotime($result1->tanggal)); //ambil tanggal
            $value1[] = (float) $result1->jml; //ambil jumlah
          }

          foreach($report2 as $result2){
            // $tgl2[] = $result2->tanggal; //ambil tanggal
            $value2[] = (float) $result2->jml; //ambil jumlah
          }


          foreach ($value1 as $key) {
            $total[] = $value1 + $value2;
          }

          /* end mengambil query*/
        }
        else {
          echo '<div class="alert alert-warning alert-dismissible" data-dismiss="alert" aria-hidden="true">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4><i class="icon fa fa-warning"></i> Data Bulan ini belum diupload!</h4>
          </div>';
          $tgl1[] = null;
          $value1[] = null;
          $value2[] = null;
          $total[] = null;

          if ($this->session->userdata("bulan"))
           $b = $this->session->userdata("bulan");
         else
          $b = date('F Y');

        $bln = date('F Y', strtotime($b));
      }
      $str = '\'Attendance in '.$bln.'\'';

      ?>
      <div class="box box-solid">
        <div class="box-body">
         <form action="" method="post" id="dateFilter">
          <label>Date : </label>
          <input type="text" name="tgl" style="width:130px;" onchange="PostMonth()" id="datepicker" placeholder="Select Period" class="form-control">
        </form>
        <div id ="report" style = "width: 1000px; margin: 0 auto"></div>
      </div>
    </div>
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
              <table id="example9" class="table table-striped table-bordered" style="width: 100%;"> 
                <thead>
                  <tr>
                    <th>Tanggal</th>
                    <th>NIK</th>
                    <th>Nama karyawan</th>
                    <th>Section</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
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

  <!-- Load chart dengan menggunakan ID -->
  <!-- END load chart -->
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
  <!-- Script untuk memanggil library Highcharts -->
  <script type="text/javascript">
    $(document).ready(function() {

      $('#datepicker').datepicker({
        autoclose: true,
        format: "mm-yyyy",
        viewMode: "months",
        minViewMode: "months"
      })
    });


    $(function () {       
      $('#report').highcharts({
        chart : {
         type: 'column'
       },
       title : {
         text: <?php echo $str ?>   
       }, 
       xAxis : {
         categories: <?php echo json_encode($tgl1); ?>
       },
       yAxis : {
         min: 0,
         title: {
          text: 'Employee Total (percentage)'
        },
        stackLabels: {
          enabled: true,
          style: {
           fontWeight: 'bold',
           color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
         }
       }
     },
     legend : {
       align: 'center',
       verticalAlign: 'bottom',
       x: 0,
       y: 0,

       backgroundColor: (
        Highcharts.theme && Highcharts.theme.background2) || 'white',
       borderColor: '#CCC',
       borderWidth: 1,
       shadow: false
     },   
     tooltip: {
      pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>'
    },
    plotOptions : {
     column: {
      stacking: 'percent',
      dataLabels: {
       enabled: true,
       color: (Highcharts.theme && Highcharts.theme.dataLabelsColor)
       || 'white',
       style: {
        textShadow: '0 0 3px black'
      }
    },
    cursor: 'pointer',
    point: {
      events: {
        click:function(event) {
          showDetail(event.point.category);
        }
      }
    },
  },
  series : {
    dataLabels:{
      enabled:true,
      formatter:function() {
        var pcnt = this.y;
        return Highcharts.numberFormat(pcnt);
      }
    }
  }
},
credits : {
 enabled: false
},
series : [{
  name: 'Tidak Hadir',
  data: <?php echo json_encode($value2) ?>
}, {
  name: 'Hadir',
  data: <?php echo json_encode($value1) ?>
}]
});
    });


    function PostMonth(){

      var tanggal2 = $("#datepicker").val();
      var tgl = "01-"+tanggal2;

      $.ajax({

        type: "POST", 
        url : "<?php echo base_url('management_mp/attendance')?>" ,       
        data: {
          bulan:tgl
        },
        success: function(data) {
          window.location.href = "<?php echo base_url('management_mp/attendance')?>";
        }
      });
    }

    function showDetail(tgl) {
      tabel = $('#example9').DataTable();
      tabel.destroy();

      $('#myModal2').modal('show');

      $('#example9').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true,
        "serverSide": true,
        "searching": true,
        "bLengthChange": true,
        "order": [],
        "ajax": {
          "url": "<?php echo base_url('home/report_detail/')?>",            
          "type": "GET",
          "data": {
            tgl : tgl
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