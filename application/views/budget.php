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
          Cost Center Budget
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
                <form action="<?php echo base_url();?>import_excel/upload/" method="post" enctype="multipart/form-data">
                  <button class="btn btn-sm btn-primary pull-right" type="submit"> <i class="fa fa-plus"></i> Import Budget</button>
                  <input type="file" name="file" class="btn pull-right" accept=".csv" />
                </form>
                <br>
                <br>

              <table id="example1" class="table table-responsive table-striped table-hover table-bordered">
                <thead>
                  <tr>
                    <th>ID CC</th>
                    <th>Period</th>
                    <th>Budget</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
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
          "url": "<?php echo base_url('budget/ajax_budget')?>",            
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