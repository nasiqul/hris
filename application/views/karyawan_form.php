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
          Employee
          <small>Tambah data</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" id="tab1"><i class="fa fa-user"></i>  Privacy</a></li>
              <li><a href="#devisiT" data-toggle="tab" id="tab2"><i class="fa fa-building"></i> Devision</a></li>
              <li><a href="#kerja" data-toggle="tab" id="tab3"><i class="fa fa-briefcase"></i> Employement</a></li>
              <li><a href="#admin" data-toggle="tab" id="tab4"><i class="fa fa-briefcase"></i> Administration</a></li>
            </ul>
            
            <div class="tab-content">
              <form id="submit">
                <div class="active tab-pane" id="activity">
                  <div class="box-body">


                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" id="nik" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control">
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tmptL">Tempat Lahir</label>
                            <input type="text" name="tmptL" id="tmptL" class="form-control">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tglL">Tanggal Lahir</label>
                            <input type="text" name="tglL" id="tglL" class="form-control">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12"><label>Jenis Kelamin</label></div>
                        <div class="form-group">
                          <div class="col-md-6">
                            <div class="radio">
                              <label><input type="radio" name="jk" id="laki" value="L">Laki - laki</label>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="radio">
                              <label><input type="radio" name="jk" id="perempuan" value="P">Perempuan</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="ktp">Nomor KTP</label>
                        <input type="text" name="ktp" id="ktp" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="Alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control">
                      </div>

                      <div class="form-group">
                        <label for="statusK">Status Keluarga</label>
                        <select id="statusK" class="form-control" name="statusK">
                          <option value="0">0</option>
                          <option value="K0">K0</option>
                          <option value="K1">K1</option>
                          <option value="K2">K2</option>
                          <option value="K3">K3</option>
                          <option value="Pk1">Pk1</option>
                          <option value="Pk2">Pk2</option>
                          <option value="Pk3">Pk3</option>
                          <option value="Tk">Tk</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" name="foto" id="foto">
                      </div>

                    </div>
                    <div class="col-md-12">
                      <a onclick="$('#tab2').trigger('click')" class="btn btn-primary pull-right">Next <i class="fa fa-arrow-right"></i></a>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="devisiT">
                  <div class="box-body">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="devisi">Devisi</label>
                        <select id="devisi" name="devisi" class="form-control" onchange='showDep()'>
                          <option value="" disabled selected>Select Devisi</option>
                          <?php 
                          foreach ($dev as $key) {
                            echo "<option value='".$key->id."'>".$key->nama."</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="departemen">Departemen</label>
                        <select id="departemen" name="departemen" class="form-control" onchange="showSec()">
                          <option value="" disabled selected>Select Departemen</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="section">Section</label>
                        <select id="section" name="section" class="form-control" onchange="showSubSec()">
                          <option value="" disabled selected>Select Section</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="subsection">Sub-Section</label>
                        <select id="subsection" name="subsection" class="form-control" onchange="showGroup()">
                          <option value="" disabled selected>Select Sub-Section</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="group">Group</label>
                        <select id="group" class="form-control" name="group">
                          <option value="" disabled selected>Select Group</option>
                        </select>
                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">
                        <label for="grade">Grade</label>
                        <select id="grade" class="form-control" onchange="showGrade()" name="grade">
                          <option value="" disabled selected>Select Kode Grade</option>
                          <?php 
                          foreach ($grade as $key) {
                            echo "<option value='".$key->kode_grade."' name='".$key->nama_grade."'>".$key->kode_grade."</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="namaG">Nama Grade</label>
                        <input type="text" id="namaG" class="form-control" readonly="" name="namaG">
                      </div>

                      <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select id="jabatan" class="form-control" name="jabatan">
                          <?php 
                          foreach ($jabatan as $key) {
                            echo "<option value='".$key->id."'>".$key->jabatan."</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="kode">Kode</label>
                        <select id="kode" class="form-control" name="kode">
                          <option value="" disabled selected>Select Kode</option>
                          <?php 
                          foreach ($kode as $key) {
                            echo "<option value='".$key->id."'>".$key->nama."</option>";
                          }
                          ?>
                        </select>
                      </div>

                    </div>
                    <div class="col-md-12">
                      <a onclick="$('#tab1').trigger('click')" class="btn btn-warning pull-left"><i class="fa fa-arrow-left"></i> Back</a>
                      <a onclick="$('#tab3').trigger('click')" class="btn btn-primary pull-right">Next <i class="fa fa-arrow-right"></i></a>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="kerja">
                  <div class="box-body">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="statusKar">Status Karyawan</label>
                        <select id="statusKar" class="form-control" name="statusKar">
                          <option value="Kontrak 1">Kontrak 1</option>
                          <option value="Kontrak 2">Kontrak 2</option>
                          <option value="Tetap">Tetap</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="pin">Pin</label>
                        <input type="text" id="pin" class="form-control" name="pin">
                      </div>

                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="tglM">Tanggal Masuk</label>
                        <input type="text" id="tglM" class="form-control" placeholder="Select date" name="tglM">
                      </div>

                      <div class="form-group">
                        <label for="cs">Cost Center</label>
                        <input type="text" id="cs" class="form-control" name="cs">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <a onclick="$('#tab2').trigger('click')" class="btn btn-warning pull-left"><i class="fa fa-arrow-left"></i> Back</a>
                      <a onclick="$('#tab4').trigger('click')" class="btn btn-primary pull-right">Next <i class="fa fa-arrow-right"></i></a>
                    </div>
                  </div>
                </div>


                <div class="tab-pane" id="admin">
                  <div class="box-body">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="hp">Nomor HP</label>
                        <input type="text" id="hp" class="form-control" name="hp">
                      </div>

                      <div class="form-group">
                        <label for="bpjstk">Nomor BPJS TK</label>
                        <input type="text" id="bpjstk" class="form-control" name="bpjstk">
                      </div>

                      <div class="form-group">
                        <label for="bpjskes">Nomor BPJS KES</label>
                        <input type="text" id="bpjskes" class="form-control" name="bpjskes">
                      </div>

                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="no_rek">Nomor Rekening</label>
                        <input type="text" id="no_rek" class="form-control" name="no_rek">
                      </div>

                      <div class="form-group">
                        <label for="npwp">Nomor NPWP</label>
                        <input type="text" id="npwp" class="form-control" name="npwp">
                      </div>

                      <div class="form-group">
                        <label for="jp">Nomor JP</label>
                        <input type="text" id="jp" class="form-control" name="jp">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <a onclick="$('#tab3').trigger('click')" class="btn btn-warning pull-left"><i class="fa fa-arrow-left"></i> Back</a>
                      <button class="btn btn-success pull-right" type="submit">Simpan <i class="fa fa-check"></i></button>
                    </div>
                  </div>
                </div>
              </form>
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
    $(document).ready(function(){
      $('#submit').submit(function(e){
        e.preventDefault(); 
        $.ajax({
         url:'<?php echo base_url("karyawan_form/add")?>',
         type:"post",
         data:new FormData(this),
         processData:false,
         contentType:false,
         cache:false,
         async:false,
         success: function(data){
          alert("Upload Image Successful.");
        }
      });
      });

    });

    function showDep() {
      var id_dev = $('#devisi').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("karyawan_form/ajax_dep") ?>',
        data: {
          'id': id_dev
        },
        success: function(data){

          var $dep = $('#departemen');
          var $sec = $('#section');
          var $subsec = $('#subsection');
          var $group = $('#group');

          $dep.empty();
          $sec.empty();
          $subsec.empty();
          $group.empty();

          $sec.append('<option value="" disabled selected>Select Section</option>');
          $subsec.append('<option value="" disabled selected>Select Sub Section</option>');
          $group.append('<option value="" disabled selected>Select Group</option>');

          var s = $.parseJSON(data);

          $dep.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

          for (var i = 1; i <= s.length -1; i++) {
            $dep.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
          }

      //manually trigger a change event for the contry so that the change handler will get triggered
      $dep.change();
    }
  });
    }

    function showSec() {
      var id_dep = $('#departemen').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("karyawan_form/ajax_sec") ?>',
        data: {
          'id': id_dep
        },
        success: function(data){

          var $sec = $('#section');
          var $subsec = $('#subsection');
          var $group = $('#group');

          $sec.empty();
          $subsec.empty();
          $group.empty();

          $subsec.append('<option value="" disabled selected>Select Sub Section</option>');
          $group.append('<option value="" disabled selected>Select Group</option>');

          var s = $.parseJSON(data);
          $sec.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

          if (s.length > 1) {
            for (var i = 1; i <= s.length -1; i++) {
              $sec.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }
          }

      //manually trigger a change event for the contry so that the change handler will get triggered
      $sec.change();
    }
  });
    }

    function showSubSec() {
      var id_sec = $('#section').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("karyawan_form/ajax_subsec") ?>',
        data: {
          'id': id_sec
        },
        success: function(data){

          var $ssec = $('#subsection');
          var $group = $('#group');

          $group.empty();
          $ssec.empty();

          $group.append('<option value="" disabled selected>Select Group</option>');

          var s = $.parseJSON(data);

          $ssec.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

          if (s.length > 1) {
            for (var i = 1; i <= s.length -1; i++) {
              $ssec.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }
          }

      //manually trigger a change event for the contry so that the change handler will get triggered
      $ssec.change();
    }
  });
    }

    function showGroup() {
      var id_gr = $('#subsection').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("karyawan_form/ajax_group") ?>',
        data: {
          'id': id_gr
        },
        success: function(data){

          var $gr = $('#group');

          $gr.empty();

          var s = $.parseJSON(data);

          $gr.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

          if (s.length > 1) {
            for (var i = 1; i <= s.length -1; i++) {
              $gr.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }
          }

      //manually trigger a change event for the contry so that the change handler will get triggered
      $gr.change();
    }
  });
    }

    function showGrade() {
      var id_grade = $('#grade').find(':selected').attr("name");
      var gradeN = $('#namaG');
      gradeN.val(id_grade);
    }

    function submitKaryawan() {
      var nik = $('#nik').val();
      var nama = document.getElementById('nama').value;
      var tmptL = document.getElementById('tmptL').value;
      var tglL = document.getElementById('tglL').value;
      var jk = $("input[name='jk']:checked").val();
      var ktp = document.getElementById('ktp').value;
      var alamat = document.getElementById('alamat').value;
      var statusK = $('#statusK').children("option:selected").val();

      var fileInput = document.getElementById('foto').value;
      //var foto = fileInput.files[0];

      var dev = $('#devisi').children("option:selected").val();
      var dep = $('#departemen').children("option:selected").val();
      var sec = $('#section').children("option:selected").val();
      var subsec = $('#subsection').children("option:selected").val();
      var group = $('#group').children("option:selected").val();
      var grade = $('#grade').children("option:selected").val();
      var ngrade = $('#namaG').val();
      var jab = $('#jabatan').children("option:selected").val();
      var kode = $('#kode').children("option:selected").val();
      var statusKar = $('#statusKar').children("option:selected").val();
      var pin = document.getElementById('pin').value;
      var tglM = document.getElementById('tglM').value;
      var cs = document.getElementById('cs').value;
      var hp = document.getElementById('hp').value;
      var bpjstk = document.getElementById('bpjstk').value;
      var bpjskes = document.getElementById('bpjskes').value;
      var no_rek = document.getElementById('no_rek').value;
      var npwp = document.getElementById('npwp').value;
      var jp = document.getElementById('jp').value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("karyawan_form/add") ?>',
        data: {
          'nik': nik,
          'nama' : nama,
          'tmptL' : tmptL,
          'tglL' : tglL,
          'jk' : jk,
          'ktp' : ktp,
          'alamat' : alamat,
          'statusK' : statusK,
      //'foto' : foto,
      'dev' : dev,
      'dep' : dep,
      'sec' : sec,
      'subsec' : subsec,
      'group' : group,
      'grade' : grade,
      'ngrade' : ngrade,
      'jab' : jab,
      'kode' : kode,
      'statusKar' : statusKar,
      'pin' : pin,
      'tglM' : tglM,
      'cs' : cs,
      'hp' : hp,
      'bpjstk' : bpjstk,
      'bpjskes' : bpjskes,
      'no_rek' : no_rek,
      'npwp' : npwp,
      'jp' : jp
    },
    success: function(data){
      openSuccessGritter();    
    }
  });
    }

    $('#tglL').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
    })

    $('#tglM').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
    })

    function openSuccessGritter(){
      jQuery.gritter.add({
        title: "Success",
        text: "Input Success",
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