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
          Overtime Data
          <small>Optional description</small>
        </h1>
      </section>

      <style type="text/css">
      th, td {
        padding: 5px;
      }
    </style>

    <!-- Main content -->
    <section class="content container-fluid">
      <div class="col-md-12">

        <div class="box box-solid">
          <div class="box-body">
            <form method="POST" id="master">
              <div class="col-md-6">
                <p>Nomor :</p>
                <input type="text" name="no" placeholder="nomor dokumen" class="form-control" id="no_doc" onchange="changeDoc()">

                
                <div class="row">
                  <div class="col-md-3">
                    <p>Tanggal</p>
                    <input type="text" name="tgl" placeholder="select date" class="form-control" id="datepicker">
                  </div>

                  <div class="col-md-3">
                    <p>Dari</p>
                    <input type="text" name="dariF" placeholder="dari" class="form-control timepicker" id="dariF">
                  </div>

                  <div class="col-md-3">
                    <p>Sampai</p>
                    <input type="text" name="sampaiF" placeholder="sampai" class="form-control timepicker" id="sampaiF">
                  </div>

                  <div class="col-md-3">
                    <p>Jam</p>
                    <input type="text" name="jamF" placeholder="Jam" class="form-control" id="jamF">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <p>Transport</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">

                    <select class='form-control' id='trans' name="trans">
                      <option value=''>-</option>
                      <option value='B'>B</option>
                      <option value='P'>P</option>
                    </select>
                  </div>

                  <div class="col-md-4 text-center">
                    <div class="checkbox">
                      <label><input type='checkbox' id='makan' name="makan">Makan</label>
                    </div>
                  </div>

                  <div class="col-md-4 text-center">
                    <div class="checkbox">
                      <label><input type='checkbox' id='exfood' name=exFood>Ext-Food</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">

                <p>Bagian :</p>
                <div class="row">
                  <div class="col-md-6">
                    <select name="dep" class="form-control" id="dep" onchange='showSec()'>
                      <option value="" disabled selected>Select Departemen</option>
                      <?php 
                      foreach ($dep as $key) {
                        echo "<option value='".$key->id."'>".$key->nama."</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select name="sec" class="form-control" id="sec">
                      <option value="" disabled selected>Select Section</option>
                    </select>
                  </div>
                </div>               

                <p>Keperluan :</p>
                <input type="text" name="kep" placeholder="keperluan" class="form-control" id="kep">

                <p>Catatan :</p>
                <input type="text" name="cat" placeholder="Catatan" class="form-control" id="cat">
              </div>
            </form>
            <div class="col-md-12">
              <br>
              <div class="form-group">
                <div class="input-group input-group-lg">
                  <div class="input-group-addon">
                    <i class="fa fa-barcode"></i>
                  </div>
                  <input type="text" class="form-control text-center" placeholder="Scan NIK Barcode here . ." id="nikF">
                </div>
                <!-- /.input group -->
              </div>
            </div>
          </div>
        </div>

        <div class="box box-solid">
          <div class="box-header">
            <h3 class="box-title">Peserta</h3>
            <div class="pull-right">
              <button id="submit" class="btn btn-success" onclick="submit1()"><i class="fa fa-check"></i> Simpan</button>
            </div>
          </div>
          <form method="POST" id="pesertaF" action="<?php echo base_url('ot/ot_member_submit'); ?>">
            <input type="hidden" name="nodoc2" id="nodoc2">
            <input type="hidden" name="nomor" id="nomor">
            <div class="box-body" id="peserta">
              <div class="col-md-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th width="17%">Nik</th>
                      <th width="26%">Nama</th>
                      <th width="8%">Dari</th>
                      <th width="8%">Sampai</th>
                      <th width="8%">Jam</th>
                      <th width="8%">Transport</th>
                      <th width="5%">Makan</th>
                      <th>Ext-Food</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>

              <!-- <a class="btn btn-success" onclick="cloneRow()"><i class="fa fa-plus"></i> Tambah</a>  -->
              <!-- <a class="btn btn-danger" onclick="deleteRow()"><i class="fa fa-minus"></i> Kurang</a> -->
            </div>
          </form>
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
  <script type="text/javascript">

    var no = 1;
    var dari = document.getElementById('dariF');
    var sampai = document.getElementById('sampaiF');

    var jam = document.getElementById('jamF');
    var trans = document.getElementById('trans');
    var nikS = document.getElementById('nikF');

    $(document).ready(function() {

      $('#datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy"
      });

      $('.timepicker').timepicker({
        showInputs: false,
        showMeridian: false,
        defaultTime: '16:00'
      });
    });

    //nik on enter
    $('#nikF').bind("enterKey",function(e){
      appendRow();
    });
    $('#nikF').keyup(function(e){
      if(e.keyCode == 13)
      {
        $(this).trigger("enterKey");
      }
    });

    function appendRow() {
      var d = dari.value;
      var s = sampai.value;
      var j = jam.value;
      var n = nikS.value;

      var nama ="";
      var t1 = "";
      var t2 = "";
      var t3 = "";

      var cekM = "";
      var cekFd = "";

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_get_nama") ?>',
        data: {
          'nik': n
        },
        success: function (data) {
            // the next thing you want to do 
            var sr = $.parseJSON(data);

            nama = sr[0][1];

            if ($('#makan').is(':checked')) {
              cekM="checked";
            }

            if ($('#exfood').is(':checked')) {
              cekFd="checked";
            }

            if ($('#trans option:selected').text() == '-') {
              t1="selected";
            }
            else if ($('#trans option:selected').text() == 'B') {
              t2="selected";
            }
            else if ($('#trans option:selected').text() == 'P') {
              t3="selected";
            }

            var newdiv1 = $( "<div class='col-md-12' style='margin-bottom: 5px'>"+
              "<div class='col-md-2'><input type='text' name='nik"+no+"' value='"+n+"' class='form-control' readonly></div>"+
              "<div class='col-md-3'><p name='nama"+no+"'>"+nama+"</p></div>"+
              "<div class='col-md-1'><input type='text' class='form-control timepicker' name='dari"+no+"' value='"+d+"'></div>"+
              "<div class='col-md-1'><input type='text' class='form-control timepicker' name='sampai"+no+"' value='"+s+"'></div>"+
              "<div class='col-md-1'><input type='text' class='form-control' name='jam"+no+"' value='"+j+"'></div>"+
              "<div class='col-md-1'><select class='form-control' name='trans"+no+"'>"+
              "<option value='-' "+t1+">-</option><option value='B' "+t2+">B</option><option value='P' "+t3+">P</option></select></div>"+
              "<div class='col-md-1'><input type='checkbox' name='makan"+no+"' "+cekM+"></div>"+
              "<div class='col-md-1'><input type='checkbox' name='exfood"+no+"' "+cekFd+"></div></div>" );

            $("#peserta").append(newdiv1);
            $('#nomor').val(no);
            no+=1;

            $('#nikF').val('');
            

          }
        });
    }

    // function cloneRow() {
    //   var row = document.getElementById("row0"); // find row to copy
    //   var table = document.getElementById("tableToModify"); // find table to append to
    //   var clone = row.cloneNode(true); // copy children too
    //   clone.id = "row"+no; // change id or other attributes/contents

    //   // var nik = $(this).find('#nik');
    //   // var nama = $(this).find('#nama');
    //   // var dari = $(this).find('#dari');
    //   // var sampai = $(this).find('#sampai');
    //   // var jam = $(this).find('#jam');
    //   // var trans = $(this).find('#trans');
    //   // var makan = $(this).find('#makan');
    //   // var exfood = $(this).find('#exfood');

    //   // nik.eq(0).attr('id', 'nik' + no);
    //   // nama.eq(0).attr('id', 'nama' + no);
    //   // dari.eq(0).attr('id', 'dari' + no);

    //   table.appendChild(clone); // add new row to end of table

    //   no+=1;
    // }

    // function deleteRow() {
    //   no-=1;
    //   var id = "#row"+no;
    //   $(id).remove();
    // }

    function showSec() {
      var id = $('#dep').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_section") ?>',
        data: {
          'id': id
        },
        success: function (data) {
            // the next thing you want to do 
            var s = $.parseJSON(data);
            var $section = $('#sec');

            $section.empty();
            for (var i = 0; i < s.length; i++) {
              $section.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }

            //manually trigger a change event for the contry so that the change handler will get triggered
            $section.change();
          }
        });
    }

    $('#dariF').change(function() {
      jamChanged();
    });

    $('#sampaiF').change(function() {
      jamChanged();
    });

    function jamChanged() {
    // alert(dari.value);

    var time1 = dari.value;
    var time2 = sampai.value;

    var hour=0;
    var minute=0;

    var splitTime1= time1.split(':');
    var splitTime2= time2.split(':');

    hour = parseInt(splitTime2[0])-parseInt(splitTime1[0]);
    minute = parseInt(splitTime2[1])-parseInt(splitTime1[1]);
    hour = hour + minute/60;

    minute = minute%60;

    //alert('sum of above time= '+hour+':'+minute);
    $('#jamF').val(hour);
  }

  function submit1() {
    var nodoc = document.getElementById('no_doc').value;

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_section") ?>',
        data: {
          'no_doc': nodoc
        }
        });
//    document.getElementById("master").submit();
    document.getElementById("pesertaF").submit();
  }

  function changeDoc() {
    var valu = document.getElementById('no_doc').value;
    $('#nodoc2').val(valu);
  }
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>