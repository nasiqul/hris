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
          Overtime Form
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
                <div class="row">
                  <div class="col-md-4">No Dokumen :</div>
                  <?php 
                  error_reporting(0);
                  $dt = date('ym'); 
                  if ($id_doc[0]->id) 
                    $ids = (substr($id_doc[0]->id, -4))+1;
                  else
                    $ids = 1;

                  $nod = sprintf('%04u', $ids);
                  $kode = $dt.$nod;
                  ?>
                  <div class="col-md-6">
                    <input type="text" name="no" placeholder="nomor dokumen" class="form-control" id="no_doc" value="<?php echo $kode ?>" readonly>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <p>Tanggal :</p>
                    <input type="text" name="tgl" placeholder="select date" class="form-control" id="datepicker" onchange="getHari()">
                  </div>

                  <div class="col-md-3">
                    <p>Shift :</p>
                    <select class="form-control" id="shiftF" onchange="showJam()">
                      <option value="" disabled selected>Shift</option>
                      <option value="1"> 1</option>
                      <option value="2"> 2</option>
                      <option value="3"> 3</option>
                    </select>
                  </div>

                  <div class="col-md-3">
                    <p>&nbsp;</p>
                    <div class="checkbox" id="4group3">
                      <label class="text-center">
                        <input type="checkbox" id="4group3s" class="minimal"><br> 4 Grup 3 Shift
                      </label>
                    </div>
                  </div>

                  <div class="col-md-1">
                    <p>&nbsp;</p>
                    <div class="checkbox" id="libur2" style="display: none">
                      <label class="text-center">
                        <input type="checkbox" id="libur" class="minimal"><br> Libur
                      </label>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <p>Bagian :</p>
                      <select name="dep" class="form-control" id="dep" onchange='showSec()'>
                        <option value="" disabled selected>Select Departemen</option>
                        <?php 
                        foreach ($dep as $key) {
                          echo "<option value='".$key->id."'>".$key->nama."</option>";
                        }
                        ?>
                      </select>
                  </div>

                  <div class="col-md-12">
                      <select name="sec" class="form-control" id="sec" onchange='showSubSec()'>
                        <option value="" disabled selected>Select Section</option>
                      </select>
                  </div>

                  <div class="col-md-12">
                      <select name="subsec" class="form-control" id="subsec">
                        <option value="" disabled selected>Select Sub Section</option>
                      </select>
                  </div>

                </div>

              </div>
              <div class="col-md-6">

                <p>Keperluan :</p>
                <input type="text" name="kep" placeholder="keperluan" class="form-control" id="kep">

                <p>Catatan :</p>
                <input type="text" name="cat" placeholder="Catatan" class="form-control" id="cat">
              </div>
            </form>
            <div class="col-md-12">
              <hr>
              <button id="reset" class="btn btn-success" onclick="reset()"><i class="fa fa-plus"></i> New </button>
            </div>
          </div>
        </div>

        <div class="box box-solid">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-group"></i> Peserta</h3>
            <div class="pull-right">
              <button class="btn btn-primary" id="print" onclick="tombol_print()" style="display: none;"><i class="fa fa-print"></i> Print</button>&nbsp&nbsp&nbsp
              <button id="submit" class="btn btn-success" onclick="submit1()"><i class="fa fa-check"></i> Simpan</button>
            </div>

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

          <input type="hidden" name="nodoc2" id="nodoc2" value="<?php echo $dt; echo $nod ?>">
          <div class="box-body">
            <div class="col-md-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>
                      <b id="totalsemua">Total</b>
                    </th>
                    <th>
                    </th>
                    <th colspan="2" name="jam0">
                      <select class="form-control" id="jamF2" onchange="hitungJam(this.id,$(this).parent().attr('name'));gantiJam();">
                        <option value="" disabled selected>Select Jam</option>
                      </select>
                    </th>
                    <th><p id="jam0">0</p></th>
                    <th>
                      <select class='form-control' id='transF' name="trans" onchange="gantiTrans()">
                        <option value='-'>-</option>
                        <option value='B'>B</option>
                        <option value='P'>P</option>
                      </select>
                    </th>
                    <th>
                      <div class="checkbox">
                        <label><input type='checkbox' id='makanF' name="makan" value="1"></label>
                      </div>
                    </th>
                    <th>
                      <div class="checkbox">
                        <label><input type='checkbox' id='exfoodF' name=exFood></label>
                      </div>
                    </th>
                  </tr>
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
              <!-- <div class='col-md-12' style='margin-bottom: 5px' id='0'>
              <div class='col-md-2'><input type='text' id='nik0' class='form-control' readonly></div>
              <div class='col-md-3'><p id='nama0'></p></div>
              <div class='col-md-2' id='sJam0' name='jam0'><select id='jamL0' class='form-control'></select></div><div class='col-md-1'><p id='jam0'></p></div>
              <div class='col-md-1'><select class='form-control' id='trans0'>
              </select></div>
              <div class='col-md-1'><input type='checkbox' id='makan0'></div>
              <div class='col-md-1'><input type='checkbox' id='exfood0'></div>
              <div class='col-md-1'><button class='btn btn-danger btn-xs' id='delete0'><i class='fa fa-minus'></i></button></div>
              <input type='hidden' id='idJam0'></div> -->

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
    var nomorali = 0;
    var idDoc;

    var no = 1;
    var hari = 'N';
    var shift3 = 1;
    arrNik = [];

    var jam = document.getElementById('jam0');
    var trans = document.getElementById('tranF');
    var nikS = document.getElementById('nikF');
    var txtJam = document.getElementById('txtJam');

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
       ali();
    });
    $('#nikF').keydown(function(e){
      if(e.keyCode == 13 || e.which == 9)
      {
        $(this).trigger("enterKey");
        console.log(no);
      }
    });

    function appendRow() {
      if ($('#no_doc').val() == "" || $('#datepicker').val() == "" || $('#kep').val() == "" || $('#nikF').val() == "" || $('#dep').find(':selected').prop('disabled') == true){
        openDangerGritter();
        return false;
      }

      var j = jam.innerHTML;
      var n = nikS.value;
      var dep = $("#dep").val();

      for (var z = 0; z < arrNik.length; z++) {
        if (arrNik[z] == n) {
          openDanger3Gritter();
          return false;
        }
      }

      var nama ="";
      var t1 = "";
      var t2 = "";
      var t3 = "";

      var cekM = "";
      var cekFd = "";

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("ot/cek_nik") ?>',
        data: {
          'nik': n,
          'dep': dep
        },
        success: function (data) {
          if ($.parseJSON(data) == 0) {
            openDanger2Gritter();
            return false;
          }

          arrNik.push(n);

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

            var newdiv1 = $( "<div class='col-md-12' style='margin-bottom: 5px' id='"+no+"'>"+
              "<div class='col-md-2'><input type='text' id='nik"+no+"' value='"+nik+"' class='form-control' readonly></div>"+
              "<div class='col-md-3'><p id='nama"+no+"'>"+nama+"</p></div>"+
              "<div class='col-md-2' id='sJam"+no+"' name='jam"+no+"'><select id='jamL"+no+"' class='form-control' onchange='changeJams("+no+")'></select></div><div class='col-md-1'><p id='jam"+no+"'>"+j+"</p></div>"+
              "<div class='col-md-1'><select class='form-control' id='trans"+no+"'>"+
              "<option value='-' "+t1+">-</option><option value='B' "+t2+">B</option><option value='P' "+t3+">P</option></select></div>"+
              "<div class='col-md-1'><input type='checkbox' id='makan"+no+"' "+cekM+"></div>"+
              "<div class='col-md-1'><input type='checkbox' id='exfood"+no+"' "+cekFd+"></div>"+
              "<div class='col-md-1'><button class='btn btn-danger btn-xs' id='delete"+no+"' onclick='deleteRow(this); ali()'><i class='fa fa-minus'></i></button></div>"+
              "<input type='hidden' id='idJam"+no+"'></div>");

            $("#peserta").append(newdiv1);

            var $options = $("#jamF2 > option").clone();
            $('#jamL'+no).append($options);
            var idB = $('#jamF2').find(':selected')[0].value;
            $('#jamL'+no).val(idB);

            var jamZ = $("#jam0").text();

            var selects = $("#jamF2").find(':selected')[0].id;

            $('#idJam'+no).val(selects);

            $('#nomor').val(no);

            $('#nikF').val('');

            openSuccessGritter();

            no+=1;
            nomorali+=1;
            $('#totalsemua').text("Total : "+nomorali);
          }
        });

        }
      });

    }

    function deleteRow(elem) {
      
      var ids = $(elem).parent('div').parent('div').attr('id');

      var oldid = ids;

      no-=1;
      
      var removed = arrNik.splice(parseInt(ids) - 1,1);
      console.log(arrNik);

      $(elem).parent('div').parent('div').remove();

      var newid = parseInt(ids) + 1;
      jQuery("#"+newid).attr("id",oldid);
      jQuery("#nik"+newid).attr("id","nik"+oldid);
      jQuery("#nama"+newid).attr("id","nama"+oldid);
      jQuery("#dari"+newid).attr("id","dari"+oldid);
      jQuery("#sampai"+newid).attr("id","sampai"+oldid);
      jQuery("#jam"+newid).attr("id","jam"+oldid);
      jQuery("#trans"+newid).attr("id","trans"+oldid);
      jQuery("#makan"+newid).attr("id","makan"+oldid);
      jQuery("#exfood"+newid).attr("id","exfood"+oldid);
      jQuery("#delete"+newid).attr("id","delete"+oldid);
      jQuery("#nomorauto"+newid).attr("id","nomorauto"+oldid);

      console.log(no);
      nomorali-=1;
      $('#totalsemua').text("Total : "+nomorali);
      

      var z = no - 1;

      for (var i =  ids; i <= z; i++) { 
        var newid = parseInt  + 1;
        var oldid = newid - 1;
        jQuery("#"+newid).attr("id",oldid);
        jQuery("#nik"+newid).attr("id","nik"+oldid);
        jQuery("#nama"+newid).attr("id","nama"+oldid);
        jQuery("#dari"+newid).attr("id","dari"+oldid);
        jQuery("#sampai"+newid).attr("id","sampai"+oldid);
        jQuery("#jam"+newid).attr("id","jam"+oldid);
        jQuery("#trans"+newid).attr("id","trans"+oldid);
        jQuery("#makan"+newid).attr("id","makan"+oldid);
        jQuery("#exfood"+newid).attr("id","exfood"+oldid);
        jQuery("#delete"+newid).attr("id","delete"+oldid);
        jQuery("#nomorauto"+newid).attr("id","nomorauto"+oldid);
        // var a = $('#nomorauto'+newid).text();
        // $("#nomorauto"+newid).text(a);
      }

    }

    function ali() {
      var aa = nomorali;
      // alert(aa);
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

    function showSubSec() {
      var id = $('#sec').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_subsection") ?>',
        data: {
          'id': id
        },
        success: function (data) {
            // the next thing you want to do 
            var $subsec = $('#subsec');

            $subsec.empty();

            var s = $.parseJSON(data);

            $subsec.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

            for (var i = 1; i <= s.length; i++) {
              $subsec.append('<option id=' + s[i][0] + ' value=' + s[i][0] + '>' + s[i][1] + '</option>');
            }
            
            //manually trigger a change event for the contry so that the change handler will get triggered
            $subsec.change();
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
    $('#txtJam').text(hour+" Jam");
  }

  function submit1() {
    if ($('#nik1').length == 0) {
      openDangerGritter();
      return false;
    }
    var nodoc = document.getElementById('no_doc').value;
    var tgl = document.getElementById('datepicker').value;
    var dep = document.getElementById('dep').value;
    var sec = document.getElementById('sec').value;
    var subsec = document.getElementById('subsec').value;
    var kep = document.getElementById('kep').value;
    var cat = document.getElementById('cat').value;

    $.ajax({
      type: 'POST',
      url: '<?php echo base_url("ot/get_id") ?>',
      data: {
        'tgl': tgl,
      },
      success: function(data){
        var s = $.parseJSON(data);
        
        idDoc = parseInt(s)+1;
        
        document.getElementById('no_doc').value = idDoc;

        $.ajax({
          type: 'POST',
          url: '<?php echo base_url("ot/ot_submit") ?>',
          data: {
            'no': idDoc,
            'tgl': tgl,
            'dep': dep,
            'sec': sec,
            'kep': kep,
            'cat': cat,
            'subsec' : subsec
          },
          success: function(data){

            var no_doc2 = document.getElementById('nodoc2').value;

            for (var i = 1; i <= no; i++) {

              var nik1 = document.getElementById('nik'+i).value;

              var jamD = $("#jamL"+i+" option:selected").text();

              var jm = jamD.split(' - ');

              var jamS = $("#jam"+i).text();

              var e = document.getElementById("trans"+i);
              var transS = e.options[e.selectedIndex].value;

              if ($('#makan'+i).is(':checked'))
                makanS="1";
              else
                makanS="0";

              if ($('#exfood'+i).is(':checked'))
                exfoodS="1";
              else
                exfoodS="0";

              var id_jam = $("#idJam"+i).val();

              $.ajax({
                type: 'POST',
                url: '<?php echo base_url("ot/ot_member_submit") ?>',
                data: {
                  'nodoc2': idDoc,
                  'nik': nik1,
                  'dari': jm[0],
                  'sampai': jm[1],
                  'jam': jamS,
                  'trans': transS,
                  'makan': makanS,
                  'exfood': exfoodS,
                  'id_jam': id_jam,
                },
                success: function(data){
                  openSuccessGritter();
                  $('#submit').css("display", "none");
                  $('#print').css("display", "block");
                }
              });
            }
          }
        });
      }
    })
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

  function openDanger3Gritter(){
    jQuery.gritter.add({
      title: "Failed",
      text: "Karyawan sudah diinput",
      class_name: 'growl-danger',
      image: '<?php echo base_url()?>app/img/close.png',
      sticky: false,
      time: '2000'
    });
  }

  function openDanger2Gritter(){
    jQuery.gritter.add({
      title: "Failed",
      text: "Karyawan tidak terdaftar pada Bagian",
      class_name: 'growl-danger',
      image: '<?php echo base_url()?>app/img/close.png',
      sticky: false,
      time: '2000'
    });
  }

  function reset() {
    location.reload();
  }

  function tombol_print() {
    var tanggal = document.getElementById('datepicker').value;
    
    var url = "<?php echo base_url('ot/print_preview/'); ?>"+idDoc+"/"+tanggal;

    window.open(url,'_blank');
  }

  function showJam() {
    var idShift = $('#shiftF').find(':selected')[0].value;
    var isChecked2 = $('#4group3s').is(':checked');

    if (idShift == 3 || isChecked2) {
      var isChecked = $('#libur').is(':checked');
      $('#libur2').css('display','block');
      if(isChecked)
      {
        if (hari == "JN")
          var hari2 = "JL";
        else
          var hari2 = "L";
      }
      else
        var hari2 = hari;

      console.log(hari2);
    }

    else {
      $('#libur2').css('display','none');
      var hari2 = hari;
    }


    $.ajax({
      type: 'POST',
      url: '<?php echo base_url("ot/ajax_over_jam") ?>',
      data: {
        'id': idShift,
        'hari' : hari2,
      },
      success: function (data) {
            // the next thing you want to do 
            var $jamS = $('#jamF2');

            $jamS.empty();

            var s = $.parseJSON(data);

            $jamS.append('<option value="" disabled selected>'+ s[0][1] +'</option>');

            for (var i = 1; i <= s.length; i++) {
              $jamS.append('<option id=' + s[i][0] + ' value='+s[i][0]+' name='+s[i][3]+'>' +s[i][1]+" - "+s[i][2]+ '</option>');
            }
            
            //manually trigger a change event for the contry so that the change handler will get triggered
            $jamS.change();
          }
        });
  }

  $('#libur').on('ifChanged', function() {
    showJam();
  });

  $('#4group3s').on('ifChanged', function() {
    showJam();
  });

  function hitungJam(jam,id2) {
    var jam = jam;

    var jamX = $('#'+jam).find(':selected').attr("name");

    var jamB = document.getElementById(id2);

    jamB.innerHTML = jamX;

  }

  function gantiJam() {
    for(i= 1; i <= no; i++){
      var selects = $("#jamF2").find(':selected')[0].id;
      var jamZ = $("#jam0").text();
      $('#idJam'+i).val(selects);
    //$("#jamIsi"+i).eq(i).val($(select).val());

    var idB = $('#jamF2').find(':selected')[0].value;
    $('#jamL'+i).val(idB);

    $("#jam"+i).text(jamZ);

  }
}

function gantiTrans() {
  for(i= 1; i <= no; i++){
    var jamX = $("#transF").find(':selected')[0].value;

    //$("#jamIsi"+i).eq(i).val($(select).val());
    $("#trans"+i).val(jamX);

  }
}

$('#makanF').change(function () {
  for(i= 1; i <= no; i++){
    if ($('#makanF').is(':checked')) {
      $("#makan"+i).prop('checked', true);

    }
    else{
      $("#makan"+i).prop('checked', false);
    }
  }
});

$('#exfoodF').change(function () {
  for(i= 1; i <= no; i++){
    if ($('#exfoodF').is(':checked')) {
      $("#exfood"+i).prop('checked', true);
    }
    else{
      $("#exfood"+i).prop('checked', false);
    }
  }
});

function getHari() {
  var tanggals = $("#datepicker").val();

  $.ajax({
    type: 'POST',
    url: '<?php echo base_url("ot/ajax_hari") ?>',
    data: {
      'tgl': tanggals
    },
    success: function (data) {
      // the next thing you want to do 
      var s = $.parseJSON(data);
      hari = s;
      console.log(hari);

      showJam();
    }
  });
}

function changeJams(id) {
  var hasilJam = $("#jamL"+id).find('option:selected').attr("name");
  var selects = $("#jamL"+id).find(':selected')[0].id;
  var jamZ = $("#jam0").text();

  $("#jam"+id).text(hasilJam);
  $('#idJam'+id).val(selects);

}

$('input[type="checkbox"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-purple'
})
</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
   </body>
   </html>