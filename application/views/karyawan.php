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
          Employee Data
          <small>Optional description</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <br>
        <div class="col-md-12">
          <table id="example1" class="table table-responsive table-striped">
            <thead>
              <tr>
                <th>NIK</th>
                <th>Name</th>
                <th>Department</th>
                <th>Sec/Group</th>
                <th>Entry Date</th>
                <th>Employee Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

        <div class="modal fade" id="myModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <section class="content-header">
                  <h1>
                    Employee Details
                  </h1>
                </section>
                <section class="content container-fluid">
                  <div class="row">
                    <div class="col-md-4">
                      <!-- general form elements -->
                      <div class="box box-primary">
                        <div class="box-body box-profile">
                          <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url()?>app/img/user1-128x128.jpg" alt="User profile picture">

                          <h3 class="profile-username text-center" id="nama"></h3>

                          <p class="text-muted text-center" id="nik"></p>
                          <p class="text-center"><small class="label bg-green">Active</small></p>
                        </div>
                        <!-- /.box-body -->
                      </div>
                      <!-- /.box -->
                    </div>
                    <div class="col-md-8">
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#activity" data-toggle="tab"><i class="fa fa-user"></i>  Privacy</a></li>
                          <li><a href="#devisi" data-toggle="tab"><i class="fa fa-building"></i> Devision</a></li>
                          <li><a href="#kerja" data-toggle="tab"><i class="fa fa-briefcase"></i> Employement</a></li>
                          <li><a href="#admin" data-toggle="tab"><i class="fa fa-briefcase"></i> Administration</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="active tab-pane" id="activity">
                            <div class="box-header"><i class="fa fa-user"></i> DATA PRIBADI</div>

                            <div class="box-body">
                              <div class="col-md-6">
                                <p class="text-muted">Tempat Lahir</p>
                                <p id="tempatLahir"></p>


                                <p class="text-muted">Tanggal Lahir</p>
                                <p id="tanggalLahir"></p>

                                <p class="text-muted">Jenis Kelamin</p>
                                <p id="jk"></p>
                              </div>
                              <div class="col-md-6">
                                <p class="text-muted">Alamat</p>
                                <p id="alamat"></p>

                                <p class="text-muted">Status Keluarga</p>
                                <p id="sKeluarga"></p>
                              </div>
                            </div>
                          </div>

                          <div class="tab-pane" id="devisi">
                            <div class="box-header"><i class="fa fa-building"></i> DATA DEVISI</div>

                            <div class="box-body">
                              <div class="col-md-6">
                                <p class="text-muted">Departement/subSec</p>
                                <p id="deb"></p>

                                <p class="text-muted">Sec/Group</p>
                                <p id="group"></p>

                                <p class="text-muted">Kode</p>
                                <p id="kode"></p>
                              </div>
                              <div class="col-md-6">
                                <p class="text-muted">Grade</p>
                                <p id="namaGrade"></p><p id="grade"></p>

                                <p class="text-muted">Jabatan</p>
                                <p id="jabatan"></p>
                              </div>
                            </div>
                          </div>

                          <div class="tab-pane" id="kerja">
                            <div class="box-header"><i class="fa fa-briefcase"></i> DATA KERJA</div>

                            <div class="box-body">
                              <div class="col-md-6">
                                <p class="text-muted">Status Karyawan</p>
                                <p id="statKaryawan"></p>

                                <p class="text-muted">Tanggal Masuk</p>
                                <p id="tglMasuk"></p>

                              </div>

                              <div class="col-md-6">
                                <p class="text-muted">Pin</p>
                                <p id="pin"></p>

                                <p class="text-muted">Cost Center</p>
                                <p id="costC"></p>

                              </div>
                            </div>
                          </div>


                          <div class="tab-pane" id="admin">
                            <div class="box-header"><i class="fa fa-briefcase"></i> DATA ADMINISTRASI</div>

                            <div class="box-body">
                              <div class="col-md-6">
                                <p class="text-muted">Nomor Handphone</p>
                                <p id="hp"></p>

                                <p class="text-muted">Nomor Rekening</p>
                                <p id="rek"></p>

                                <p class="text-muted">Nomor KTP</p>
                                <p id="ktp"></p>

                                <p class="text-muted">Nomor NPWP</p>
                                <p id="npwp"></p>
                              </div>

                              <div class="col-md-6">
                                <p class="text-muted">No. BPJS TK</p>
                                <p id="bpjstk"></p>

                                <p class="text-muted">No. BPJS KES</p>
                                <p id="bpjskes"></p>

                                <p class="text-muted">No. JP</p>
                                <p id="jp"></p>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger pull-left"><i class="fa fa-times"></i> Terminasi</button>
                <button type="button" class="btn btn-warning pull-left" id="edit"><i class="fa fa-pencil"></i> Edit Data</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
          <!-- </div> --> 

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
          "url": "<?php echo base_url('home/ajax_emp')?>",            
          "type": "POST"
        },
        "columns": [
        { "data": 0 },
        { "data": 1 },
        { "data": 2 },
        { "data": 3 },
        { "data": 4 },
        { "data": 5 }
        ]
      })
    })

    $('#openModal').click(function(){
      $("#myModal").modal('show');
    })

    function gotData(data) {
      doMe.call(data)
    }

    function ShowModal(i){
      var a = i;
      var id = $('#tes'+a).data("id");

      $.ajax({
        url: "<?php echo base_url('home/ajax_emp_by_nik/')?>"+ id ,
        type : "GET",
        dataType: 'json',
        success: function(data){
          $.each(data, function(i, value){
            $("#pin").text(data[i][0]);
            $("#costC").text(data[i][2]);
            $("#foto").text(data[i][3]);
            $("#nama").text(data[i][4]);
            $("#nik").text(data[i][1]);
            $("#tempatLahir").text(data[i][15]);
            $("#tanggalLahir").text(data[i][16]);
            $("#alamat").text(data[i][17]);
            $("#sKeluarga").text(data[i][14]);

            if (data[i][9] == "L") {
              $("#jk").text('Laki - laki');
            }
            else if (data[i][9] == "P") {
              $("#jk").text('Perempuan');
            }

            $("#deb").text(data[i][5]);
            $("#group").text(data[i][6]);
            $("#kode").text(data[i][7]);
            $("#grade").text("("+data[i][11]+")");
            $("#namaGrade").text(data[i][12]);
            $("#jabatan").text(data[i][13]);
            $("#statKaryawan").text(data[i][10]);
            $("#tglMasuk").text(data[i][8]);
            $("#hp").text(data[i][18]);
            $("#rek").text(data[i][20]);
            $("#ktp").text(data[i][19]);
            $("#npwp").text(data[i][24]);
            $("#bpjstk").text(data[i][21]);
            $("#bpjskes").text(data[i][23]);
            $("#jp").text(data[i][22]);
          });

        }
      });

      $('#myModal').modal('show');
    }

    $('#edit').on('click',function() {

     var tmpt = $('#tempatLahir').text();
     $('#tempatLahir').text('').append($('<input />',
     {
      'value' : tmpt, 
      'type' : 'text', 
      'class' : 'form-control', 
      'id' : 'txtTempat'}
      ));
     $('#txtTempat').focus();

     var tanggalLahir = $('#tanggalLahir').text();
     $('#tanggalLahir').text('').append($('<input />',
     {
      'value' : tanggalLahir, 
      'type' : 'date', 
      'class' : 'form-control', 
      'id' : 'txttanggalLahir'}
      ));

   })

 </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>