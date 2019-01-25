<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>

<body class="hold-transition skin-blue sidebar-mini">
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
        Presensi
        <small>Optional description</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      <table id="example1" class="table table-responsive table-striped">
        <thead>
          <th>Tanggal</th>
          <th>NIK</th>
          <th>Nama</th>
          <th>Datang</th>
          <th>Pulang</th>
          <th>Shift</th>
        </thead>        
        <tbody>
        </tbody>
      </table>

    </section>
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
  var table;
  $(document).ready(function() {
    table = $('#example1').DataTable({
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "processing": true,
      "serverSide": true,
      "bInfo": false,
      "order": [],
      "ajax": {
            "url": "<?php echo base_url('home/ajax_detail')?>",            
            "type": "POST"
        },
      "columnDefs": [
      { 
          "targets": [ 0 ], //first column / numbering column
          "orderable": false, //set not orderable
      }]
    });

  })
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>