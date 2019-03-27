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
          Presence data
          <span class="text-purple">出勤データ</span>
        </h1>      
      </section>

      <!-- Main content -->
      <section class="content container-fluid">

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
                <button class="btn btn-success btn-sm pull-right" type="button" id="import" onclick="openModal()"><i class="fa fa-arrow-down"></i> import presensi</button>
              </div>
            </form>
          </div>

          <!-- /.box -->

        </div> 
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
            <table id="example1" class="table table-responsive table-striped">
              <thead>
                <th>Date</th>
                <th>NIK</th>
                <th>Name</th>
                <th>Arrive</th>
                <th>Leave</th>
                <th>Shift</th>
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
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>   

      <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-sm">
          <form method="post" action="<?php echo base_url('import_excel/upload3'); ?>" enctype="multipart/form-data" class="pull-right">
            <div class="modal-content">
              <div class="modal-header">
                <h4 style="float: right;" id="modal-title"></h4>
                <h4 class="modal-title"><b>Import Presensi</b> <b id="sifMakan"></b></h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <input type="file" name="file">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <input type="submit" name="submit" value="Import" class="btn btn-success btn-sm pull-right">
                <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.modal-dialog -->
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
    var table;
    $(document).ready(function() {

      table = $('#example1').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax')?>",            
          "type": "POST"
        },
        "orderCellsTop": true,
        "fixedHeader": true
      });

      table.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
          if ( that.search() !== this.value ) {
            that
            .search( this.value )
            .draw();
          }
        } );
      } );
    })

    function openModal() {
      $('#myModal').modal({backdrop: 'static', keyboard: false});
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