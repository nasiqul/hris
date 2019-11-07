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
            <h4><i class="icon fa fa-thumbs-o-up"></i> Upload Berhasil</h4>
          </div>

          <div class="alert alert-danger alert-dismissible" id="notif3" onclick="check(this)" style="cursor: pointer;" <?php if (!isset($_SESSION['status'])) echo "hidden"; else if ($_SESSION['status'] != 'gagal') echo "hidden"; ?>>
            <h4><i class="icon fa fa-thumbs-o-down"></i> Upload Gagal</h4>
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
                <div class="col-md-2">
                  <div class="form-group">
                    <label><i class="fa fa-calendar"></i> <span>Date From</span></label>
                    <input type="text" class="form-control" name="tanggal_from" id="datepicker" placeholder="Select date from" required
                    <?php if(isset($_SESSION['tanggal_from'])) echo 'value="'.$_SESSION['tanggal_from'].'"';?>>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label><i class="fa fa-calendar"></i> <span>Date To</span></label>
                    <input type="text" class="form-control" name="tanggal_to" id="datepicker2" placeholder="Select date to" required
                    <?php if(isset($_SESSION['tanggal_to'])) echo 'value="'.$_SESSION['tanggal_to'].'"';?>>
                  </div>
                </div>

                <div class="col-md-2">
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
                <button class="btn btn-success btn-sm pull-right" type="button" id="import" onclick="openModal()"><i class="fa fa-arrow-down" ></i> import presensi</button>
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
                      <input type="file" name="file" id="file">
                      <span style="font-size: 2em" id="loading"><i class="fa fa-refresh fa-spin"></i> Importing ...</span>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="submit" id="submit" name="submit" value="Import" class="btn btn-success btn-sm pull-right">
                  <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.modal-dialog -->
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
    var no = 2;
    var table;
    $(document).ready(function() {
      $("#loading").hide();

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
    })

    function openModal() {
      $('#myModal').modal({backdrop: 'static', keyboard: false});
    }

    function openModal2() {
      $('#modal-default').modal({backdrop: 'static', keyboard: false});
    }

    function check(elem) {
      elem.style.display = "none";
    }

    function tambah(id,lop) {
      var id = id;

      var lop = "";

      if (id == "tambah"){
        lop = "lop";
      }else{
        lop = "lop2";
      }

      var divdata = $("<div id='"+no+"' class='col-md-12' style='margin-bottom : 5px'> <div class='col-xs-4' style='padding:1;'> <input type='text' class='form-control' id='nik"+no+"' name='nik"+no+"' placeholder='Enter NIK' required> </div> <div class='col-xs-2' style='padding:1;'> <input type='text' id='in"+no+"' name='in"+no+"' class='form-control timepicker' > </div> <div class='col-xs-2' style='padding:1;'> <input type='text' id='out"+no+"' name='out"+no+"' class='form-control timepicker' > </div> <div class='col-xs-2' style='padding:1;'> <input type='text' id='sif"+no+"' name='sif"+no+"' class='form-control ' placeholder='Keterangan' > </div><div class='col-xs-2' style='padding:0;'>&nbsp;<button onclick='kurang(this,\""+lop+"\");' class='btn btn-danger'><i class='fa fa-close'></i> </button> <button type='button' onclick='tambah(\""+id+"\",\""+lop+"\"); ' class='btn btn-success'><i class='fa fa-plus' ></i></button></div></div>");

      $("#"+id).append(divdata).find('.timepicker').timepicker({
        showInputs: false,
        showMeridian: false,
        defaultTime: '0:00',
      });;
      document.getElementById(lop).value = no;
      no+=1;

    }

    function kurang(elem,lop) {
      var lop = lop;
      var ids = $(elem).parent('div').parent('div').attr('id');
      var oldid = ids;
      $(elem).parent('div').parent('div').remove();
      var newid = parseInt(ids) + 1;
      jQuery("#"+newid).attr("id",oldid);
      jQuery("#nik"+newid).attr("name","nik"+oldid);
      jQuery("#in"+newid).attr("name","in"+oldid);

      jQuery("#nik"+newid).attr("id","nik"+oldid);
      jQuery("#in"+newid).attr("id","in"+oldid);

      jQuery("#out"+newid).attr("name","out"+oldid);
      jQuery("#out"+newid).attr("id","out"+oldid);

      jQuery("#sif"+newid).attr("name","sif"+oldid);
      jQuery("#sif"+newid).attr("id","sif"+oldid);

      no-=1;
      var a = no -1;

      for (var i =  ids; i <= a; i++) { 
        var newid = parseInt(i) + 1;
        var oldid = newid - 1;
        jQuery("#"+newid).attr("id",oldid);
        jQuery("#nik"+newid).attr("name","nik"+oldid);
        jQuery("#in"+newid).attr("name","in"+oldid);

        jQuery("#nik"+newid).attr("id","nik"+oldid);
        jQuery("#in"+newid).attr("id","in"+oldid);

        jQuery("#out"+newid).attr("name","out"+oldid);
        jQuery("#out"+newid).attr("id","out"+oldid);

        jQuery("#sif"+newid).attr("name","sif"+oldid);
        jQuery("#sif"+newid).attr("id","sif"+oldid);

      // alert(i)
    }

    document.getElementById(lop).value = a;
  }

  function hide() {
    $("#submit").prop('disabled','true');
    $("#file").prop('disabled','true');
    $("#loading").show();
  }

  function saveDriver() {
    alert('Driver simpan');
  }

  $('#datepicker').datepicker({
    autoclose: true,
    format: 'dd/mm/yyyy',
  })

  $('#datepicker2').datepicker({
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