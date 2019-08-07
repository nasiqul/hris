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
          Presence data
          <span class="text-purple">出勤データ</span>
        </h1>      
      </section>
      <!-- Main content -->
      <section class="content container-fluid">

        <div class="col-md-12">
          <div class="alert alert-success alert-dismissible" id="notif2" onclick="check(this)" style="cursor: pointer;" <?php if (!isset($_SESSION['status'])) echo "hidden"; else if ($_SESSION['status'] != 'sukses') echo "hidden"; ?>>
            <h4><i class="icon fa fa-thumbs-o-up"></i> Import Berhasil</h4>
          </div>

          <div class="alert alert-danger alert-dismissible" id="notif3" onclick="check(this)" style="cursor: pointer;" <?php if (!isset($_SESSION['status'])) echo "hidden"; else if ($_SESSION['status'] != 'gagal') echo "hidden"; ?>>
            <h4><i class="icon fa fa-thumbs-o-down"></i> Import Gagal</h4>
          </div>
        </div>

        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-search"></i> <span>Search Filter</span></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="<?php echo base_url('home/presensi'); ?>" method="POST">
              <div class="box-body">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputTanggal"><i class="fa fa-calendar"></i> <span>Date</span></label>
                    <input type="text" class="form-control" name="tanggal" id="datepicker" placeholder="Select date"
                    <?php if(isset($_SESSION['tanggal'])) echo 'value="'.$_SESSION['tanggal'].'"';?>>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputNik"><i class="fa fa-id-badge"></i> <span>NIK</span></label>
                    <input type="text" class="form-control" name="nik" id="inputNik" placeholder="NIK"
                    <?php if(isset($_SESSION['nik3'])) echo 'value="'.$_SESSION['nik3'].'"';?>>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="inputNama"><i class="fa fa-user"></i> <span>Name</span></label>
                    <input type="text" class="form-control" name="nama" id="inputNama" placeholder="Name"
                    <?php if(isset($_SESSION['nama'])) echo 'value="'.$_SESSION['nama'].'"';?>>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputShift"><i class="fa fa-briefcase "></i> <span>Shift</span></label>
                    <input type="text" class="form-control" name="shift" id="inputShift" placeholder="Shift"
                    <?php if(isset($_SESSION['shift'])) echo 'value="'.$_SESSION['shift'].'"';?>>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> <span>Search</span></button>
                <a class="btn btn-warning" id="reset" href="<?php echo base_url('home/session_destroy') ?>" ><i class="fa fa-refresh"></i> Reset</a>
                <button class="btn btn-success btn-sm pull-right" type="button" id="import2" onclick="openModal2()"  style="margin-right: 3px"><i class="fa fa-arrow-down"></i> input presensi driver</button>
                <a href="<?php echo base_url() ?>app/excel/os_drv_presensi.xls" class="pull-right" style="margin-right: 10px; text-decoration: underline;">Format</a>
              </div>
            </form>
          </div>

          <!-- /.box -->
        </div> 

        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <!-- <form method="POST" action="<?php echo base_url('import_excel/export_excel_presensi/') ?>">
                <input type="text" name="tgl" id="tgl" class="form-control datepicker">
                <button id="export" type="submit" class="btn btn-sm btn-default">Export to Excel</button>
              </form> -->
              <table id="example1" class="table table-responsive table-striped" width="100%">
                <thead>
                  <th>Date</th>
                  <th>NIK</th>
                  <th>Name</th>
                  <th>Bagian</th>
                  <th>Datang</th>
                  <th>Pulang</th>
                </thead>        
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>   

        <div class="modal fade" id="modal-default">
          <div class="modal-dialog modal-sm">
            <form method="post" action="<?php echo base_url('import_excel/upload_drv_os'); ?>" enctype="multipart/form-data" class="pull-right">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Input Presensi Driver Outsource</h4>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-12" style="margin-bottom : 5px">
                        <input type="file" name="file_drv" id="file_drv">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Import</button>
                  </div>
                </form> 
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
    var no = 2;
    var table;
    $(document).ready(function() {
      // $("#loading").hide();

      table = $('#example1').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax_outsource')?>",            
          "type": "POST"
        },
        "orderCellsTop": true,
        "fixedHeader": true
      });
    })

    function openModal2() {
      $('#modal-default').modal({backdrop: 'static', keyboard: false});
    }

    function check(elem) {
      elem.style.display = "none";
    }


    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
    })

    $('.datepicker').datepicker({
      autoclose: true,
      format: "mm-yyyy",
      viewMode: "months", 
      minViewMode: "months"
    })

    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      defaultTime: '0:00',
    });
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>