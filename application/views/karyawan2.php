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
          Employee Data
          <span class="text-purple">従業員データ</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <br>
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <a class="btn btn-success" href="<?php echo base_url('home/karyawan_t'); ?>"><i class="fa fa-plus"></i> New Entry</a>
              <a class="btn btn-warning" href="<?php echo base_url('home/reset_karyawan'); ?>"><i class="fa fa-refresh"></i> Reload Data</a>
              <br>
              <br>
              <table id="example1" class="table table-responsive table-striped">
                <thead>
                  <tr>
                    <th>NIK</th>
                    <th>Name</th>
                    <th>Devisi</th>
                    <th>Departemen</th>
                    <th>Entry Date</th>
                    <th>Employee Status</th>
                    <th>Status</th>
                    
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
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
                          <img class="profile-user-img img-responsive img-circle" id="foto" alt="User profile picture">

                          <h3 class="profile-username text-center" id="nama"></h3>

                          <p class="text-muted text-center" id="nik"></p>
                          <p class="text-center" id="status"></p>
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
                                <p class="text-muted">Devisi</p>
                                <p id="dev"></p>

                                <p class="text-muted">Departemen</p>
                                <p id="dep"></p>

                                <p class="text-muted">Section</p>
                                <p id="sec">cc</p>

                                <p class="text-muted">Sub Section</p>
                                <p id="sub-sec">scc</p>

                              </div>
                              <div class="col-md-6">
                                <p class="text-muted">Group</p>
                                <p id="group">gr</p>

                                <p class="text-muted">Kode</p>
                                <p id="kode"></p>

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
    var jks;
    var keluarga = [];
    var devisi = [];
    var departemen = [];
    var section = [];
    var sub_section = [];
    var group = [];

    $(document).ready(function() {
      $('#example1').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('home/ajax_emp_coba')?>",            
          "type": "POST"
        },
        "columns": [
        { "data": 0 },
        { "data": 1 },
        { "data": 2 },
        { "data": 3 },
        { "data": 4 },
        { "data": 5 },
        { "data": 6 }
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
        url: "<?php echo base_url('home/ajax_emp_by_nik_coba/')?>"+ id ,
        type : "GET",
        dataType: 'json',
        success: function(data){
          $.each(data, function(i, value){
            $("#pin").text(data[i][0]);
            $("#costC").text(data[i][2]);
            $("#foto").attr("src","<?php echo base_url() ?>app/img/photo/"+data[i][3]+"");
            $("#nama").text(data[i][4]);
            $("#nik").text(data[i][1]);
            $("#tempatLahir").text(data[i][16]);
            $("#tanggalLahir").text(data[i][17]);
            $("#alamat").text(data[i][18]);
            $("#sKeluarga").text(data[i][15]);

            if (data[i][10] == "L") {
              $("#jk").text('Laki - laki');
              jks = data[i][10];
            }
            else if (data[i][10] == "P") {
              $("#jk").text('Perempuan');
              jks = data[i][10];
            }

            $("#dev").text(data[i][5]);
            $("#dep").text(data[i][6]);
            $("#sec").text(data[i][7]);
            $("#kode").text(data[i][8]);
            $("#grade").text("("+data[i][12]+")");
            $("#namaGrade").text(data[i][11]);
            $("#jabatan").text(data[i][14]);
            $("#statKaryawan").text(data[i][11]);
            $("#tglMasuk").text(data[i][9]);
            $("#hp").text(data[i][19]);
            $("#rek").text(data[i][21]);
            $("#ktp").text(data[i][20]);
            $("#npwp").text(data[i][25]);
            $("#bpjstk").text(data[i][22]);
            $("#bpjskes").text(data[i][24]);
            $("#jp").text(data[i][23]);

            if (data[i][26] == "Aktif")
            {
              $("#status").empty().append('<small class="label bg-green">'+data[i][26]+'</small>');
            }
            else
            {
              $("#status").empty().append('<small class="label bg-red">'+data[i][26]+'</small>');
            }

            var id_dev = data[i][27];
            var id_dep = data[i][28];
            var id_sec = data[i][29];
            var id_sub_sec = data[i][30];

            //----------------- AJAX DEPARTEMEN -----------
            $.ajax({
              type : "POST",
              url: "<?php echo base_url('home/ajax_dep/') ?>",
              data : { 
                'id' : id_dev 
              },
              dataType: 'json',
              success: function(data){
                $.each(data, function(i, value){
                  departemen.push(data[i]);
                });
              }
            });

            //----------------- AJAX SECTION -----------
            $.ajax({
              type : "POST",
              url: "<?php echo base_url('home/ajax_sec/') ?>",
              data : { 
                'id' : id_dep 
              },
              dataType: 'json',
              success: function(data){
                $.each(data, function(i, value){
                  section.push(data[i]);
                });
              }
            });

            //----------------- AJAX SUB SECTION -----------
            $.ajax({
              type : "POST",
              url: "<?php echo base_url('home/ajax_sub_sec/') ?>",
              data : { 
                'id' : id_sec 
              },
              dataType: 'json',
              success: function(data){
                $.each(data, function(i, value){
                  sub_section.push(data[i]);
                });
              }
            });

            //----------------- AJAX GROUP -----------
            $.ajax({
              type : "POST",
              url: "<?php echo base_url('home/ajax_group/') ?>",
              data : { 
                'id' : id_sub_sec 
              },
              dataType: 'json',
              success: function(data){
                $.each(data, function(i, value){
                  group.push(data[i]);
                });
              }
            });

          });
        }
      });

      // ---------------- AJAX KELUARGA ----------------
      $.ajax({
        url: "<?php echo base_url('home/ajax_emp_keluarga/')?>" ,
        type : "GET",
        dataType: 'json',
        success: function(data){
          $.each(data, function(i, value){
            keluarga.push(data[i][0]);
          });
        }
      });      

      // --------------- AJAX DEVISI -------------------
      $.ajax({
        url: "<?php echo base_url('home/ajax_dev/')?>" ,
        type : "GET",
        dataType: 'json',
        success: function(data){
          $.each(data, function(i, value){
            devisi.push(data[i]);
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

     var jk = $('#jk').text();
     if (jks == "L") {
      $('#jk').text('').append($('<select class="form-control" id="txtJK"> <option value="L" selected>Laki - laki</option> <option value="P">Perempuan</option> </select>'));
    }
    else if (jks == "P"){
      $('#jk').text('').append($('<select class="form-control" id="txtJK"> <option value="L">Laki - laki</option> <option value="P" selected>Perempuan</option> </select>'));
    }

    var alamat = $('#alamat').text();
    $('#alamat').text('').append($('<textarea class="form-control" id="txtAlamat">'+alamat+'</textarea>'));

    
    // ----------------- Select Option Keluarga ------------------

    var keluarga2 = $('#sKeluarga').text();

    var list = [];
    var options = [];

    for (var i = 0; i < keluarga.length; i++) {
      list.push(i) ;
      if (keluarga2 == keluarga[i])
        options.push('<option value="'+keluarga[i]+'" selected>'+keluarga[i]+'</option>');
      
      else
        options.push('<option value="'+keluarga[i]+'">'+keluarga[i]+'</option>');
    }

    $('#sKeluarga').text('').append($('<select id="SelectKeluarga" class="form-control"></select>'));

    $("#SelectKeluarga").html(options.join(''));

    // ------------------------ Select Option Devisi ----------------

    var devisi2 = $('#dev').text();

    var list2 = [];
    var options2 = [];

    for (var i = 0; i < devisi.length; i++) {
      list2.push(i) ;
      if (devisi2 == devisi[i][1])
        options2.push('<option value="'+devisi[i][0]+'" selected>'+devisi[i][1]+'</option>');
      
      else
        options2.push('<option value="'+devisi[i][0]+'">'+devisi[i][1]+'</option>');
    }

    $('#dev').text('').append($('<select id="SelectDevisi" class="form-control"></select>'));

    $("#SelectDevisi").html(options2.join(''));

    // --------------------- Select Option Departemen ------------------

    var departemen2 = $('#dep').text();

    var list3 = [];
    var options3 = [];


    for (var i = 0; i < departemen.length; i++) {
      list3.push(i) ;
      if (departemen2 == departemen[i][1])
        options3.push('<option value="'+departemen[i][0]+'" selected>'+departemen[i][1]+'</option>');
      
      else
        options3.push('<option value="'+departemen[i][0]+'">'+departemen[i][1]+'</option>');
    }

    $('#dep').text('').append($('<select id="SelectDepartemen" class="form-control"></select>'));

    $("#SelectDepartemen").html(options3.join(''));

    //------------------ Select Option Section -----------------

    var section2 = $('#sec').text();

    var list4 = [];
    var options4 = [];

    for (var i = 0; i < section.length; i++) {
      list4.push(i) ;
      if (section2 == section[i][1])
        options4.push('<option value="'+section[i][0]+'" selected>'+section[i][1]+'</option>');
      else
        options4.push('<option value="'+section[i][0]+'">'+section[i][1]+'</option>');
    }

    $('#sec').text('').append($('<select id="SelectSection" class="form-control"></select>'));

    $("#SelectSection").html(options4.join(''));

  })


    $('#myModal').on('hidden.bs.modal', function () {
      keluarga = [];
      devisi = [];
      departemen = [];
      section = [];
      sub_section = [];
      group = [];
    })


  </script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>