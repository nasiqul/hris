<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!-- HEADER -->
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nikLogin')) { redirect('login'); }?>

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
          Master
          <span class="text-purple">???</span>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" onclick="head12('EDIT DEVISI','devisi','tdevisi',''); tabel('tdevisi')">DEVISI</a></li>
                <li><a href="#tab_2" data-toggle="tab"  onclick="head12('EDIT DEPARTEMEN','departemen','tdep','devisi','id_devisi'); tabel('tdep')" >DEPARTEMEN</a></li>
                <li><a href="#tab_3" data-toggle="tab"  onclick="head12('EDIT SECTION','section','tsec','departemen','id_departemen'); tabel('tsec')" >SECTION</a></li>
                <li><a href="#tab_4" data-toggle="tab"  onclick="head12('EDIT SUB SECTION','sub_section','tsub','section','id_sec'); tabel('tsub')" >SUB SECTION</a></li>
                <li><a href="#tab_5" data-toggle="tab"  onclick="head12('EDIT GROUP','group1','tgrup','sub_section','id_sub'); tabel('tgrup')" >GROUP</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                  <table class="table" width="100%" id="tdevisi">
                    <thead>
                      <tr>
                        <th>Nama</th>
                        <th>Jumlah Departemen</th>
                        <th>Induk</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                 <table class="table" width="100%" id="tdep">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Jumlah Section</th>
                      <th>Induk</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
               <table class="table" width="100%" id="tsec">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Jumlah Sub Section</th>
                    <th>Induk</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
            <!-- /.tab-pane -->
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_4">
             <table class="table" width="100%" id="tsub">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Jumlah Grup</th>
                  <th>Induk</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <!-- /.tab-pane -->
          <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_5">
           <table class="table" width="100%" id="tgrup">
            <thead>
              <tr>
                <th>Nama</th>
                <!-- <th>Jumlah</th> -->
                <th>Induk</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <!-- /.tab-pane -->

        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>

  </div>
</div>
</section>
<!-- /.content -->
</div>
<div class="modal fade" id="devisi" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="edittext">Default Modal</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="tb" id="tb" value="">
          <input type="hidden" name="detail12" id="detail12" value="">
          <label>Name :</label>
          <input type="hidden" name="id" id="id" class="form-control"><br>
          
          <input type="text" name="nama" id="nama" class="form-control">
          <label>Induk :</label>
          <select class="form-control select2" style="width: 100%" id="select_induk">
           <option disabled>pilih</option>
         </select>
       </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="save_devisi(this.name)" id="btnklik" name="devisi">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
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
    var headt ="EDIT DEVISI";
    var tmodal ="";

    $(document).ready(function() {
      $('#tdevisi').DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('master/ajax_devisi/12')?>",            
          "type": "POST"
        },
        "columnDefs": [
        {
          "targets": [ 1,2,3 ], //first column / numbering column
          "orderable": false, //set not orderable
        }]
      })
      $('.select2').select2({
      });

    })

    function tabel(id) {  
      var table = $('#'+id).DataTable(); 
      table.destroy();

      $('#'+id).DataTable({
        "lengthMenu"    : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing"    : true,
        "serverSide"    : true,
        'order'         : [],
        "ajax": {
          "url": "<?php echo base_url('master/ajax_devisi/')?>"+id,            
          "type": "POST"
        },
        "columnDefs": [
        {
          "targets": [ 1,2], //first column / numbering column
          "orderable": false, //set not orderable
        }]
      })
    }

    function selek(sel) {
     var nama = $('#detail12').val();
     var sel = $('#'+sel).text();
     $("#select_induk").empty();
     $.ajax({
      type: 'GET',
      url: '<?php echo base_url("master/get_select") ?>',
      data: {
        'nama': nama,
      },
      dataType: "json",
      success: function (data) {
        for(i = 0; i < data.length; i++){
          if (sel == data[i][1]) {
            $("#select_induk").append("<option value="+data[i][0]+" selected>"+data[i][1]+"</option>");

          }else {
            $("#select_induk").append("<option value="+data[i][0]+">"+data[i][1]+"</option>");

          }
        }
      }
    })
   }

   function head12(id,db,tmodal1,selek,tb) {
    headt = id;
    $("#select_induk").empty();
    $('#btnklik').prop('name',db);
    $('#detail12').val(selek);
    $('#tb').val(tb);
    tmodal = tmodal1;
  }

  function devisi(id) {

    var nama = $('#'+id).text();
    $('#edittext').text(headt);
    $('#nama').val(nama);
    $('#id').val(id);
    $('#devisi').modal('show');
  }

  function save_devisi(name) {
    var tb = $('#tb').val();
    var nama = $('#nama').val();
    var id = $('#id').val();
    var induk = $('#select_induk').find(':selected')[0].value;

    $.ajax({
      type: 'POST',
      url: '<?php echo base_url("master/get_devisi") ?>',
      data: {
        'nama': nama,
        'id': id.substr(3,3),
        'dbtabel':name,
        'induk':induk,
        'tb':tb


      },
      success: function (data) {
       $('#devisi').modal('hide');
       $('#'+tmodal).DataTable().ajax.reload();

     }
   });

  }
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>