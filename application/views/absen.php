<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nik')) { redirect('home/overtime_user'); }?>

<style type="text/css">
#info {
  text-decoration: underline;
}
#info:hover {
  text-decoration:none;
}
</style>

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
          Absent data
          <small>Optional description</small>
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
            <form action="<?php echo base_url('home/absen'); ?>" method="POST">
              <div class="box-body">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="datepicker"><i class="fa fa-calendar"></i> <span>Date</span></label>
                    <input type="text" class="form-control" name="tanggal" id="datepicker" placeholder="Select date"
                    <?php if(isset($_SESSION['tanggal2'])) echo 'value="'.$_SESSION['tanggal2'].'"';?>>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="inputNik"><i class="fa fa-id-badge"></i> <span>NIK</span></label>
                    <input type="text" class="form-control" name="nik" id="inputNik" placeholder="NIK"
                    <?php if(isset($_SESSION['nik2'])) echo 'value="'.$_SESSION['nik2'].'"';?>>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="inputNama"><i class="fa fa-user"></i> <span>Name</span></label>
                    <input type="text" class="form-control" name="nama" id="inputNama" placeholder="Name"
                    <?php if(isset($_SESSION['nama2'])) echo 'value="'.$_SESSION['nama2'].'"';?>>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputShift"><i class="fa fa-briefcase "></i> <span>Absent</span></label>
                    <input type="text" class="form-control" name="absensi" id="inputAbsensi" placeholder="Absensi"
                    <?php if(isset($_SESSION['shift2'])) echo 'value="'.$_SESSION['shift2'].'"';?>>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> <span>Search</span></button>
                <a class="btn btn-warning" id="reset" href="<?php echo base_url('home/sess_destroy2') ?>" ><i class="fa fa-refresh"></i> Reset</a>
                <div class="pull-right">
                  <div class="col-md-12">
                    <a href="#" onclick="$('#myModal').modal('show');" id="info" style="color: #8a8c8e"><i class="fa fa-info"></i>&nbsp Informasi Kode Absensi</a>
                  </div>
                </div>

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
                  <tr>
                    <th>Date</th>
                    <th>NIK</th>
                    <th>Name</th>
                    <th>Bagian</th>
                    <th>Absent</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="modal fade" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 style="float: right;" id="modal-title"></h4>
                <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCT INDONESIA</b></h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-striped">
                      <tr>
                        <th colspan="4" class="text-center"><i class="fa fa-bullhorn"></i> Keterangan</th>
                      </tr>
                      <tr>
                        <td>CT</td>
                        <td>: Cuti Tahunan</td>
                        <td>Sn</td>
                        <td>: Cuti Khusus Saudara Kandung Nikah</td>
                      </tr>
                      <tr>
                        <td>CK</td>
                        <td>: Cuti Khusus Lainnya</td>
                        <td>N</td>
                        <td>: Cuti Khusus Menikah</td>
                      </tr>
                      <tr>
                        <td>Im</td>
                        <td>: Istri Melahirkan</td>
                        <td>SD</td>
                        <td>: Sakit dengan Surat Dokter</td>
                      </tr>
                      <tr>
                        <td>Km</td>
                        <td>: Cuti Khusus Kematian</td>
                        <td>I</td>
                        <td>: Ijin</td>
                      </tr>
                      <tr>
                        <td>K</td>
                        <td>: Cuti Pra-Lahir</td>
                        <td>A</td>
                        <td>: Alpha</td>
                      </tr>
                      <tr>
                        <td>M</td>
                        <td>: Cuti Pasca-Lahir</td>
                        <td>DL</td>
                        <td>: Dinas Luar</td>
                      </tr>
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

      // $('#example1 tfoot th').each( function () {
      //   var title = $(this).text();
      //   $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
      // });

      table = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax_absensi')?>",            
          "type": "POST"
        },
        "columnDefs" : [
        { "orderable": false, "targets": 3 }
        ],
        "orderCellsTop": true,
        "fixedHeader": true
      });

      // table.columns().every( function () {
      //   var that = this;

      //   $( 'input', this.footer() ).on( 'keyup change', function () {
      //     if ( that.search() !== this.value ) {
      //       that
      //       .search( this.value )
      //       .draw();
      //     }
      //   } );
      // } );

    })

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