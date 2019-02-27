<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nik')) { redirect('home/overtime_user'); }?>

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
          Total Presence
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <?php
          //check if
          if(!empty($report1) && !empty($report2) && !empty($report3)) {

            foreach($report1 as $result1){
              $bln = date('F Y', strtotime($result1->tanggal));
            $tgl1[] = date('d M Y', strtotime($result1->tanggal)); //ambil tanggal
            $value1[] = (float) $result1->jml; //ambil jumlah
          }

          foreach($report2 as $result2){
            // $tgl2[] = $result2->tanggal; //ambil tanggal
            $value2[] = (float) $result2->jml; //ambil jumlah
          }

          foreach($report3 as $result3){
            // $tgl3[] = $result3->tanggal; //ambil tanggal
            $value3[] = (float) $result3->jml; //ambil jumlah
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
          $value3[] = null;

          if ($this->session->userdata("bulan"))
           $b = $this->session->userdata("bulan");
         else
          $b = date('F Y');

        $bln = date('F Y', strtotime($b));
      }
      $str = '\'Presence in '.$bln.'\'';

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
          text: 'Employee Total'
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
     tooltip : {
       formatter: function () {
        return '<b>' + this.x + '</b><br/>' +
        this.series.name + ': ' + this.y + '<br/>' +
        'Total: ' + this.point.stackTotal;
      }
    },
    plotOptions : {
     column: {
      stacking: 'normal',
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
            alert(event.point.category);
        }
      }
    },
  }
},
credits : {
 enabled: false
},
series : [{
  name: 'Shift3',
  data: <?php echo json_encode($value3) ?>
}, {
  name: 'Shift2',
  data: <?php echo json_encode($value2) ?>
}, {
  name: 'Shift1',
  data: <?php echo json_encode($value1) ?>
}]
});
    });


    function PostMonth(){

      var tanggal2 = $("#datepicker").val();
      var tgl = "01-"+tanggal2;

      $.ajax({

        type: "POST", 
        url : "<?php echo base_url('home/')?>" ,       
        data: {
          bulan:tgl
        },
        success: function(data) {
          window.location.href = "<?php echo base_url('home/')?>";
        }
      });
    }
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>