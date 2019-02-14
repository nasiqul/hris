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
                <p>Nomor Dokumen :</p>
                <?php 
                error_reporting(0);
                $dt = date('ym'); 
                if ($id_doc[0]->id) 
                  $ids = (substr($id_doc[0]->id, -4))+1;
                else
                  $ids = 1;
                
                $nod = sprintf('%04u', $ids);
                ?>
                <input type="text" name="no" placeholder="nomor dokumen" class="form-control" id="no_doc" value="<?php echo $dt; echo $nod ?>" readonly>

                <div class="row">
                  <div class="col-md-3">
                    <p>Tanggal</p>
                    <input type="text" name="tgl" placeholder="select date" class="form-control" id="datepicker">
                  </div>

                  <div class="col-md-3">
                    <p>Dari</p>
                    <input type="text" name="dariF" placeholder="dari" class="form-control timepicker" id="dariF" value="16:00">
                  </div>

                  <div class="col-md-3">
                    <p>Sampai</p>
                    <input type="text" name="sampaiF" placeholder="sampai" class="form-control timepicker" id="sampaiF" value="16:00">
                  </div>

                  <div class="col-md-3">
                    <p>Jam</p>
                    <input type="text" name="jamF" placeholder="Jam" class="form-control" id="jamF" value="0">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <p>Transport</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">

                    <select class='form-control' id='transF' name="trans">
                      <option value=''>-</option>
                      <option value='B'>B</option>
                      <option value='P'>P</option>
                    </select>
                  </div>

                  <div class="col-md-4 text-center">
                    <div class="checkbox">
                      <label><input type='checkbox' id='makanF' name="makan">Makan</label>
                    </div>
                  </div>

                  <div class="col-md-4 text-center">
                    <div class="checkbox">
                      <label><input type='checkbox' id='exfoodF' name=exFood>Ext-Food</label>
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
              <hr>
              <div class="form-group">
                <div class="input-group input-group-lg">
                  <div class="input-group-addon">
                    <i class="fa fa-barcode"></i>
                  </div>
                  <input type="text" class="form-control text-center" placeholder="Scan NIK Barcode here . ." id="nikF">
                </div>
                <!-- /.input group -->
              </div>
              <button id="reset" class="btn btn-default pull-right" onclick="reset()"><i class="fa fa-refresh"></i> Refresh</button>
            </div>
          </div>
        </div>

        <div class="box box-solid">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-group"></i> Peserta</h3>
            <div class="pull-right">
              <a id="print" class="btn btn-primary" href="<?php echo base_url('ot/print_preview'); ?>" target="_blank"><i class="fa fa-print"></i> Print</a>&nbsp&nbsp&nbsp
              <button id="submit" class="btn btn-success" onclick="submit1()"><i class="fa fa-check"></i> Simpan</button>
            </div>
          </div>
          
            <input type="hidden" name="nodoc2" id="nodoc2" value="<?php echo $dt; echo $nod ?>">
            <div class="box-body">
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
                      <th></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <div id="peserta">

              </div>
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
  <script type="text/javascript">

    var no = 1;
    var dari = document.getElementById('dariF');
    var sampai = document.getElementById('sampaiF');

    var jam = document.getElementById('jamF');
    var trans = document.getElementById('tranF');
    var nikS = document.getElementById('nikF');

    $(document).ready(function() {

      $('#datepicker').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy"
      });

      $('.timepicker').timepicker({
        showInputs: false,
        showMeridian: false
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
      if ($('#no_doc').val() == ""){
        openDangerGritter();
        return false;
      }
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
            nik = sr[0][0];

            if ($('#makanF').is(':checked')) {
              cekM="checked";
            }

            if ($('#exfoodF').is(':checked')) {
              cekFd="checked";
            }

            if ($('#transF option:selected').text() == '-') {
              t1="selected";
            }
            else if ($('#transF option:selected').text() == 'B') {
              t2="selected";
            }
            else if ($('#transF option:selected').text() == 'P') {
              t3="selected";
            }

            var newdiv1 = $( "<div class='col-md-12' style='margin-bottom: 5px'>"+
              "<div class='col-md-2'><input type='text' id='nik"+no+"' value='"+nik+"' class='form-control' readonly></div>"+
              "<div class='col-md-3'><p id='nama"+no+"'>"+nama+"</p></div>"+
              "<div class='col-md-1'><input type='text' class='form-control timepicker' id='dari"+no+"' value='"+d+"'></div>"+
              "<div class='col-md-1'><input type='text' class='form-control timepicker' id='sampai"+no+"' value='"+s+"'></div>"+
              "<div class='col-md-1'><input type='text' class='form-control' id='jam"+no+"' value='"+j+"'></div>"+
              "<div class='col-md-1'><select class='form-control' id='trans"+no+"'>"+
              "<option value='-' "+t1+">-</option><option value='B' "+t2+">B</option><option value='P' "+t3+">P</option></select></div>"+
              "<div class='col-md-1'><input type='checkbox' id='makan"+no+"' "+cekM+"></div>"+
              "<div class='col-md-1'><input type='checkbox' id='exfood"+no+"' "+cekFd+"></div>"+
              "<div class='col-md-1'><button class='btn btn-danger btn-xs' id='delete"+no+"' onclick='deleteRow(this)'><i class='fa fa-minus'></i></button></div></div>" );

            $("#peserta").append(newdiv1);
            $('#nomor').val(no);
            no+=1;

            $('#nikF').val('');

            openSuccessGritter();
            

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

    function deleteRow(elem) {
      $(elem).parent('div').parent('div').remove();      
    }

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
            var $section = $('#sec');

            $section.empty();

            var s = $.parseJSON(data);

            $section.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

            for (var i = 1; i <= s.length; i++) {
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
    var tgl = document.getElementById('datepicker').value;
    var dep = document.getElementById('dep').value;
    var sec = document.getElementById('sec').value;
    var kep = document.getElementById('kep').value;
    var cat = document.getElementById('cat').value;

    $.ajax({
      type: 'POST',
      url: '<?php echo base_url("ot/ot_submit") ?>',
      data: {
        'no': nodoc,
        'tgl': tgl,
        'dep': dep,
        'sec': sec,
        'kep': kep,
        'cat': cat
      },
      success: function(data){

        var no_doc2 = document.getElementById('nodoc2').value;

        for (var i = 1; i <= no; i++) {

          var nik1 = document.getElementById('nik'+i).value;
          var dariS = document.getElementById('dari'+i).value;
          var sampaiS = document.getElementById('sampai'+i).value;
          var jamS = document.getElementById('jam'+i).value;
          var transS = document.getElementById('trans'+i).value;
          var makanS = document.getElementById('makan'+i).value;
          var exfoodS = document.getElementById('exfood'+i).value;

          $.ajax({
            type: 'POST',
            url: '<?php echo base_url("ot/ot_member_submit") ?>',
            data: {
              'nodoc2': no_doc2,
              'nik': nik1,
              'dari': dariS,
              'sampai': sampaiS,
              'jam': jamS,
              'trans': transS,
              'makan': makanS,
              'exfood': exfoodS
            },
            success: function(data){
              openSuccessGritter();
            }
          });
        }
      }
    });
//    document.getElementById("master").submit();
}

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

function openDangerGritter(){
  jQuery.gritter.add({
    title: "Failed",
    text: "Ada Data yang Kosong",
    class_name: 'growl-danger',
    image: '<?php echo base_url()?>app/img/close.png',
    sticky: false,
    time: '2000'
  });
}

function reset() {
  location.reload();
  // $('#master').trigger("reset");
  // $("#peserta").empty();

}
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>