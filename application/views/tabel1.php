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
          Question and Answer
          <span class="text-purple">質疑応答</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">

          <div class="box box-solid">
            <div class="box-body">
              <table class="table table-bordered table-hover table-striped">
               <tr>
                    <th></th>
                    <th>Hari Kerja</th>
                    <th>Total Lembur</th>
                    <th>total karyawan</th>
                    <th>Karyawan Non-Aktif</th>
                    <th>Karyawan Aktif</th>
                    <th>Jam ketidakhadiran</th>
                    <th>jam Kerja Total</th>
                    <th>(%) Presentase Kehadiran</th>
               </tr>
               <?php $no = 0; $no2 = 0; foreach ($prs as $key) { ?>

                    <tr>
                         <th><?php echo date('F',strtotime($key->tanggal)) ?></th>
                         <td><?php echo $key->hari_kerja ?></td>
                         <td><?php echo $key->total_lembur ?></td>
                         <td>
                              <?php 
                              $no += $key->total_keluar;
                              $tot = $key->tot - $no;
                              echo $tot;
                              ?>
                         </td>
                         <td><?php echo $key->total_keluar ?></td>
                         <td><?php echo $key->totalMasuk ?></td>
                         <td><?php echo $key->jam_ketidakhadiran ?></td>
                         <td>
                              <?php 
                              $jm_kerja = ($key->hari_kerja*$tot*8) + $key->total_lembur;
                              echo $jm_kerja;
                              ?>
                         </td>
                         <td>
                              <?php 
                              $prsn = ROUND(($jm_kerja - $key->jam_ketidakhadiran) / $jm_kerja * 100, 3);
                              echo $prsn." %";
                              ?>
                         </td>
                    </tr>

               <?php } ?>

          </table>

          <table class="table">
               <tr>
                    <th>bulan</th>
                    <th>aktif</th>
                    <th>non-aktif</th>
                    <th>Total Karyawan</th>
               </tr>
               <?php foreach ($prs2 as $key2) { ?>
                    <tr>
                         <td><?php echo $key2->periode ?></td>
                         <td><?php echo $key2->aktif ?></td>
                         <td><?php echo $key2->non_aktif ?></td>
                         <td><?php echo $key2->total_karyawan ?></td>
                    </tr>
               <?php } ?>
          </table>
            </div>
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
    $(document).ready(function() {
      $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax_qa')?>",            
          "type": "POST"
        }
      })
    })
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>