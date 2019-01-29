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
        Page Header
        <small>Optional description</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

      asd
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
  $(function () {
    $('#example1').DataTable({
      'serverside':true,
      'processing':true
    })
  })
</script>
<?php require_once(APPPATH.'views/footer/foot.php'); ?>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>