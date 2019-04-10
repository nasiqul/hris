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
          Outsourcing Data
          <span class="text-purple">質疑応答</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">

          <div class="box box-solid">
            <div class="box-body">
              <button class="btn btn-success" onclick="klik()"><i class="fa fa-plus"></i> New Entry</button>
              <br>
              <br>
              <table id="example1" class="table table-responsive table-striped table-bordered text-center" width="100%">
                <thead>
                  <tr>
                    <th>Periode</th>
                    <th>E Masuk</th>
                    <th>E Keluar</th>
                    <th>Jumlah Total</th>
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
              <form id="outsoure_tambah">
                <div class="modal-header">
                  <h4 style="float: right;" id="modal-title"></h4>
                  <h4 class="modal-title"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA</b></h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <input type="text" name="periode" class="form-control" id="periode" placeholder="pilih bulan">
                      <div class="row">
                        <div class="col-md-6">
                          <label>Total Masuk</label>
                          <input type="number" name="masuk" placeholder="jumlah masuk" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <label>Total Keluar</label>
                          <input type="number" name="keluar" placeholder="jumlah keluar" class="form-control">
                        </div>
                      </div>
                      <label>Total Outsourcing</label>
                      <input type="total" name="tot" placeholder="total outsource" class="form-control">

                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success pull-left"><i class="fa fa-check"></i> Simpan</button>
                  <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
              </form>
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
    $(document).ready(function() {
      var tabel1 = $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('karyawan_form/outsource_data')?>",            
          "type": "POST"
        }
      })

      $('#outsoure_tambah').submit(function(e){
        e.preventDefault(); 
        $.ajax({
         url:'<?php echo base_url("karyawan_form/add_outsource")?>',
         type:"post",
         data:new FormData(this),
         processData:false,
         contentType:false,
         cache:false,
         async:false,
         success: function(data){
          $('#myModal').modal('hide');
          openSuccessGritter();
          tabel1.ajax.reload();
        }
      });
      });
    })

    function klik() {
      $("#myModal").modal('show');
    }

    $('#periode').datepicker({
      autoclose: true,
      format: "mm-yyyy",
      viewMode: "months", 
      minViewMode: "months"
    });

    function openSuccessGritter(){
    jQuery.gritter.add({
      title: "Berhasil",
      text: "Data Berhasil Ditambahkan",
      class_name: 'growl-success',
      image: '<?php echo base_url()?>app/img/icon.png',
      sticky: false,
      time: '2000'
    });
  }
  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>