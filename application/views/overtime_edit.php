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
          Overtime Form - Edit
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
                    <div class="col-md-6">
                      <input type="text" name="no" placeholder="nomor dokumen" class="form-control" id="no_doc" value="<?php echo $isi[0]->id ?>" readonly>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <p>Tanggal :</p>
                      <input type="text" name="tgl" placeholder="select date" class="form-control" id="datepicker" value="<?php echo $isi[0]->tanggal ?>" disabled="">
                    </div>

                    <div class="col-md-3">
                      <p>Shift :</p>
                      <select class="form-control" id="shiftF" onchange="showJam()" disabled="">
                        <option value="" disabled selected>Shift</option>
                        <?php for ($i=1; $i <= 3; $i++) { 
                          if ($i == $isi[0]->shift)
                            echo '<option value="'.$i.'" selected>'.$i.'</option>';
                          else
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        } ?>
                        
                      </select>
                    </div>

                    <div class="col-md-3">
                      <p>&nbsp;</p>
                      <div class="checkbox" id="4group3">
                        <label class="text-center">
                          <div class="icheckbox_minimal-blue 
                          <?php if($isi[0]->status_shift == '4G') echo 'checked' ?>
                          " style="position: relative;"  aria-disabled="false">
                          <input type="checkbox" id="4group3s" class="minimal" disabled="">
                        </div>
                        <br>
                        4 Grup 3 Shift  

                      </label>
                    </div> 
                  </div>

                  <div class="col-md-12">
                    <b>Bagian :</b>
                    <select name="dep" class="form-control" id="dep" disabled="">
                      <option value="" disabled selected>Select Section</option>
                      <?php 
                      foreach ($dep as $key) {
                        if ($isi[0]->departemen == $key->id) {
                          echo "<option value='".$key->id."' name='".$key->id_departemen."' selected>".$key->nama."</option>";
                        } else {
                          echo "<option value='".$key->id."' name='".$key->id_departemen."'>".$key->nama."</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>

                  <div class="col-md-12">
                    <select name="sec" class="form-control" id="sec" disabled="">
                      <option value="" disabled selected>Select Sub Section</option>
                      <?php 
                      foreach ($sec as $key2) {
                        if ($isi[0]->section == $key2->id) {
                          echo "<option value='".$key2->id."' selected>".$key2->nama."</option>";
                        } else {
                          echo "<option value='".$key2->id."'>".$key2->nama."</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>

                  <div class="col-md-12">
                    <select name="subsec" class="form-control" id="subsec" disabled="">
                      <option value="" disabled selected>Select Group</option>
                      <?php 
                      foreach ($sub_sec as $key3) {
                        if ($isi[0]->sub_sec == $key3->id) {
                          echo "<option value='".$key3->id."' selected>".$key3->nama."</option>";
                        } else {
                          echo "<option value='".$key3->id."'>".$key3->nama."</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>

                </div>

              </div>
              <div class="col-md-6">

                <p>Keperluan :</p>
                <input type="text" name="kep" placeholder="keperluan" class="form-control" id="kep" value="<?php echo $isi[0]->keperluan ?>" disabled="">

                <p>Catatan :</p>
                <input type="text" name="cat" placeholder="Catatan" class="form-control" id="cat" value="<?php echo $isi[0]->catatan ?>" disabled="">
              </div>
            </form>
            <div class="col-md-12">
              <hr>
              <p class="pull-right"><b>Hari : </b><span id="textHari"> <?php echo $isi[0]->hari ?> </span></p>
            </div>
          </div>
        </div>

        <div class="box box-solid">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-group"></i> Peserta</h3>
            <div class="pull-right">
              <button class="btn btn-primary" id="print" onclick="tombol_print()" style="display: none;"><i class="fa fa-print"></i> Print</button>&nbsp&nbsp&nbsp
              <button id="submit" class="btn btn-primary" onclick="edit1()"><i class="fa fa-pencil"></i>&nbsp; Update</button>
            </div>

          </div>

          <input type="hidden" name="nodoc2" id="nodoc2" value="<?php echo $isi[0]->id ?>">
          <div class="box-body">
            <div class="col-md-12">
              <table class="table" border="0">
                <thead>
                  <tr>                    
                    <th > 
                      <b id="totalsemua">Total</b>
                    </th>
                    <th></th>
                    <th>Dari
                      <input type="text" name="dari" id="dari" class="form-control timepicker" onchange="dari()" value="<?php echo $isi[0]->dari ?>">
                    </th>
                    <th  name="jam0">Sampai
                      <input type="text" name="sampai" id="sampai" class="form-control timepicker" onchange="sampai()" value="<?php echo $isi[0]->sampai ?>">
                    </th>
                    <th><p id="jam0" hidden>0</p> <p id="jamfix">0</p></th>
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
                        <label><input type='checkbox' id='exfoodF' name="exFood"></label>
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
    var sec = 0;

    var no = 1;
    var hari = 'N';
    var shift3 = 1;
    arrNik = [];

    // var jam = document.getElementById('jam0');
    var trans = document.getElementById('tranF');
    var nikS = document.getElementById('nikF');
    var txtJam = document.getElementById('txtJam');

    

    $(document).ready(function()
    {
      append();

    })


    $('.datepicker').datepicker({
      autoclose: true,
      format: "dd-mm-yyyy",
      todayHighlight: true,
    });

    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      interval: 30,
    });

    //nik on enter
    // $('#nikF').bind("enterKey",function(e){
    //   appendRow();
    //   ali();
    // });
    // $('#nikF').keydown(function(e){
    //   if(e.keyCode == 13 || e.which == 9)
    //   {
    //     $(this).trigger("enterKey");
    //     console.log(no);
    //   }
    // });

    // function appendRow() {
    //   if ($('#no_doc').val() == "" || $('#datepicker').val() == "" || $('#kep').val() == "" || $('#nikF').val() == "" || $('#dep').find(':selected').prop('disabled') == true || $('#sec').find(':selected').prop('disabled') == true || $('#subsec').find(':selected').prop('disabled') == true || $('#shiftF').find(':selected').prop('disabled') == true){
    //     openDangerGritter();
    //     return false;
    //   }

    //   // var j = jam.innerHTML;
    //   var n = nikS.value;
    //   var dep = $("#dep").val();

    //   for (var z = 0; z < arrNik.length; z++) {
    //     if (arrNik[z] == n) {
    //       openDanger3Gritter();
    //       return false;
    //     }
    //   }

    //   var nama ="";
    //   var t1 = "";
    //   var t2 = "";
    //   var t3 = "";

    //   var cekM = "";
    //   var cekFd = "";

    //   $.ajax({
    //     type: 'POST',
    //     url: '<?php // echo base_url("ot/cek_nik") ?>',
    //     data: {
    //       'nik': n,
    //       'dep': dep
    //     },
    //     success: function (data) {
    //       if ($.parseJSON(data) == 0) {
    //         openDanger2Gritter();
    //         return false;
    //       }

    //       arrNik.push(n);

    //       $.ajax({
    //         type: 'POST',
    //         url: '<?php // echo base_url("home/ajax_get_nama") ?>',
    //         data: {
    //           'nik': n
    //         },
    //         success: function (data) {
    //         // the next thing you want to do 
    //         var sr = $.parseJSON(data);

    //         nama = sr[0][1];
    //         nik = sr[0][0];
    //         dariz = $('#dari').val();
    //         sampaiz = $('#sampai').val();

    //         if ($('#makanF').is(':checked')) {
    //           cekM="checked";
    //         }

    //         if ($('#exfoodF').is(':checked')) {
    //           cekFd="checked";
    //         }

    //         if ($('#transF option:selected').text() == '-') {
    //           t1="selected";
    //         }
    //         else if ($('#transF option:selected').text() == 'B') {
    //           t2="selected";
    //         }
    //         else if ($('#transF option:selected').text() == 'P') {
    //           t3="selected";
    //         }

    //         var newdiv1 = $( "<div class='col-md-12' style='margin-bottom: 5px' id='"+no+"'>"+
    //           "<div class='col-md-2'><input type='text' id='nik"+no+"' value='"+nik+"' class='form-control' readonly></div>"+
    //           "<div class='col-md-3'><p id='nama"+no+"'>"+nama+"</p></div><div class='col-md-1'><input class='form-control timepicker' value='"+dariz+"'  id='dari"+no+"' name='dari"+no+"' onchange='dariid("+no+")'></input></div>"+
    //           "<div class='col-md-1'><input class='form-control timepicker' id='sampai"+no+"'  name='sampai"+no+"' value='"+sampaiz+"' onchange='sampaiid("+no+")'></input></div><div class='col-md-1'><p id='jam"+no+"' hidden></p><p id='jamfix"+no+"'>0</p></div>"+
    //           "<div class='col-md-1'><select class='form-control' id='trans"+no+"'>"+
    //           "<option value='-' "+t1+">-</option><option value='B' "+t2+">B</option><option value='P' "+t3+">P</option></select></div>"+
    //           "<div class='col-md-1'><input type='checkbox' id='makan"+no+"' "+cekM+"></div>"+
    //           "<div class='col-md-1'><input type='checkbox' id='exfood"+no+"' "+cekFd+"></div>"+
    //           "<div class='col-md-1'><button class='btn btn-danger btn-xs' id='delete"+no+"' onclick='deleteRow(this); ali()'><i class='fa fa-minus'></i></button></div>"+
    //           "<input type='hidden' id='idJam"+no+"'></div>");

    //         $("#peserta").append(newdiv1).find('.timepicker').timepicker({
    //           showInputs: false,
    //           showMeridian: false,
    //           interval: 30,

    //         });

    //         var sampai = $('#sampai').val();
    //         var dari = $('#dari').val();
    //         var jam = $('#jam0').text();
    //         var jamfix = $('#jamfix').val();
    //         $('#jam'+no).text(jam);
    //         $('#jamfix'+no).val(jamfix).change();
    //         $('#sampai'+no).val(sampai).change();
    //         $('#dari'+no).val(dari);

    //         $('#nomor').val(no);

    //         no+=1;
    //         nomorali+=1;
    //         $('#totalsemua').text("Total : "+nomorali);
    //       }
    //     });

    //     }
    //   });

    // }

    function append() {
      <?php foreach ($isi as $key) { 
        if ($key->makan == 1)
          $cekM = 'checked';
        else 
          $cekM = '';

        if ($key->ext_food == 1) 
          $cekExF="checked";
        else
          $cekExF='';

        if ($key->transport == '-') {
          $t1='selected';
          $t2 = '';
          $t3 = '';
        }
        else if ($key->transport == 'B') {
          $t1 = '';
          $t2='selected';
          $t3 = '';
        }
        else if ($key->transport == 'P') {
          $t1 = '';
          $t2 = '';
          $t3='selected';
        }
        ?>

        var newdiv1 = $( "<div class='col-md-12' style='margin-bottom: 5px' id='"+no+"'>"+
          "<div class='col-md-2'><input type='text' id='nik"+no+"' value='<?php echo $key->nik ?>' class='form-control' readonly></div>"+
          "<div class='col-md-3'><p id='nama"+no+"'><?php echo $key->namaKaryawan ?></p></div><div class='col-md-1'><input class='form-control timepicker' value='<?php echo $key->dari ?>'  id='dari"+no+"' name='dari"+no+"' onchange='dariid("+no+")'></input></div>"+
          "<div class='col-md-1'><input class='form-control timepicker' id='sampai"+no+"'  name='sampai"+no+"' value='<?php echo $key->sampai ?>' onchange='sampaiid("+no+")'></input></div><div class='col-md-1'><p id='jam"+no+"' hidden></p><p id='jamfix"+no+"'><?php echo $key->jam ?></p></div>"+
          "<div class='col-md-1'><select class='form-control' id='trans"+no+"'>"+
          "<option value='-' <?php echo $t1 ?>>-</option><option value='B' <?php echo $t2 ?>>B</option><option value='P' <?php echo $t3 ?>>P</option></select></div>"+
          "<div class='col-md-1'><input type='checkbox' id='makan"+no+"' <?php echo $cekM ?>></div>"+
          "<div class='col-md-1'><input type='checkbox' id='exfood"+no+"' <?php echo $cekExF ?>></div>"+
          "<div class='col-md-1'><button class='btn btn-danger btn-xs' id='delete"+no+"' onclick='deleteRow(this); ali()'><i class='fa fa-minus'></i></button></div>"+
          "<input type='hidden' id='idJam"+no+"'></div>");

        $("#peserta").append(newdiv1).find('.timepicker').timepicker({
          showInputs: false,
          showMeridian: false,
          interval: 30,

        });

        var sampai = $('#sampai').val();
        var dari = $('#dari').val();
        var jam = $('#jam0').text();
        var jamfix = $('#jamfix').val();
        $('#jam'+no).text(jam);
        $('#jamfix'+no).val(jamfix).change();
        $('#sampai'+no).val(sampai).change();
        $('#dari'+no).val(dari);

        $('#nomor').val(no);

        no+=1;
        nomorali+=1;
        $('#totalsemua').text("Total : "+nomorali);
      <?php } ?>
    }


    function deleteRow(elem) {

      var ids = $(elem).parent('div').parent('div').attr('id');

      var oldid = ids;
      nomorali-=1;
      if (nomorali ==0){
        arrNik = [];
      }

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
      jQuery("#jamfix"+newid).attr("id","jamfix"+oldid);
      jQuery("#trans"+newid).attr("id","trans"+oldid);
      jQuery("#makan"+newid).attr("id","makan"+oldid);
      jQuery("#exfood"+newid).attr("id","exfood"+oldid);
      jQuery("#delete"+newid).attr("id","delete"+oldid);
      jQuery("#nomorauto"+newid).attr("id","nomorauto"+oldid);

      // console.log(no);

      $('#totalsemua').text("Total : "+nomorali);
      
      no-=1;
      var z = no - 1;

      for (var i =  ids; i <= z; i++) { 
        var newid = parseInt(i)  + 1;
        var oldid = newid - 1;
        jQuery("#"+newid).attr("id",oldid);
        jQuery("#nik"+newid).attr("id","nik"+oldid);
        jQuery("#nama"+newid).attr("id","nama"+oldid);
        jQuery("#dari"+newid).attr("id","dari"+oldid);
        jQuery("#sampai"+newid).attr("id","sampai"+oldid);
        jQuery("#jam"+newid).attr("id","jam"+oldid);
        jQuery("#jamfix"+newid).attr("id","jamfix"+oldid);
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
    

    function namadept() {
      var id = $('#dep').find('option:selected').attr("name");
      //alert(id);
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_namadept") ?>',
        data: {
          'id': id
        },
        success: function (data) {
           // alert(data)
           var s = $.parseJSON(data)
           $('#namadept2').text(s +" - Departemen");
         }
       });
    }

    function showSec() {
      var id = $('#dep').find(':selected')[0].value;

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url("home/ajax_over_section") ?>',
        data: {
          'id': id
        },
        dataType: 'json',
        success: function (data) {
            // the next thing you want to do 
            var $section = $('#sec');
            var $subsec = $('#subsec');

            $section.empty();
            $subsec.empty();
            $subsec.append('<option value="" disabled selected>Select Group</option>');

            $section.append('<option value="" disabled selected>'+ data[0][1] +'</option>');

            for (var i = 1; i < data.length; i++) {

              $section.append('<option id=' + data[i][0] + ' value=' + data[i][0] + '>' + data[i][1] + '</option>');
              
            }
            
            //manually trigger a change event for the contry so that the change handler will get triggered
            $section.change();
          }
        });
    }

    function showSubSec() {

      var id = $('#sec').find(':selected')[0].value;

      if (id == 7) {
        $('#libur2').css('display','block');
        sec = 7;
      }
      else {
        $('#libur2').css('display','none');
        sec = 0;
      }

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

            for (var i = 1; i < s.length; i++) {

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

  function edit1() {
    if ($('#nik1').length == 0) {
      openDangerGritter();
      return false;
    }

    var no_doc2 = document.getElementById('nodoc2').value;

    $.ajax({
      type: 'POST',
      url: '<?php echo base_url("ot/deleteSPL") ?>',
      data: {
        'nodoc2': no_doc2
      },
      success: function(data){
        for (var i = 1; i <= no; i++) {

          var nik1 = document.getElementById('nik'+i).value;

          var sampai = $('#sampai'+i).val();
          var dari = $('#dari'+i).val();

          var jamS = $("#jamfix"+i).text();

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
              'nodoc2': no_doc2,
              'nik': nik1,
              'dari': dari,
              'sampai': sampai,
              'jam': jamS,
              'trans': transS,
              'makan': makanS,
              'exfood': exfoodS,
              'id_jam': id_jam,
            },
            success: function(data){
              openSuccessGritter();
            }
          });
        }
      }
    });
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

    if (idShift == 3 && isChecked2) {
      var isChecked = $('#libur').is(':checked');
      $('#libur2').css('display','block');
      if(isChecked)
      {
        if (hari == "N")
          var hari2 = "L";
        else
          var hari2 = "L";
      }
      else
        var hari2 = "N";
    } 
    else if (idShift == 3 || isChecked2 || sec == 7) {
      var isChecked = $('#libur').is(':checked');
      $('#libur2').css('display','block');
      if(isChecked)
      {
        if (hari == "N")
          var hari2 = "L";
        else
          var hari2 = "L";
      }
      else
        var hari2 = "N";
    }

    else {
      $('#libur2').css('display','none');
      var hari2 = hari;

    }
    $("#textHari").text(hari2);
    console.log(hari2);

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
      if (s == "JN" || s == "N") {
        hari = "N";
        hari3 = "Normal";
      }
      else {
        hari = "L";
        hari3 = "Libur";
      }
      console.log("hari = "+hari);

      showJam();
    }
  });
}

function dariid(id)
{
  var id = id;
  var sampai1 = $('#sampai'+id).val();
  var dari1 = $('#dari'+id).val();

  var sampaipost ="";
  if (sampai1.split(":")[0]=="0") {
    sampaipost = "24:"+sampai1.split(":")[0];
  }else{
    sampaipost = sampai1;
  }
  var daripost ="";
  if (dari1.split(":")[0]=="23") {
    daripost = "0:"+dari1.split(":")[0];
  }else{
    daripost = dari1;
  }
  var tgl = $('#datepicker').val();
  var shift = $("#shiftF").find(':selected')[0].value;


  jam = sampai1.split(":")[0] - dari1.split(":")[0];
  menit = sampai1.split(":")[1] - dari1.split(":")[1];

  menit = menit.toString().length<2?'0'+menit:menit;
  if (menit<0){
    jam--;
    menit = 60 + menit;        
  }

  jam = jam.toString().length<2?'0'+jam:jam;
  if( jam < 0){
    ab = jam + 24;
  }else
  {
    ab = jam ;
  }


  $('#jam0').text(ab+"."+menit);


  $.ajax({
    type: 'GET',
    url: '<?php echo base_url("ot/get_break") ?>',
    data: {
      'tgl': tgl,
      'dari': daripost,
      'sampai': sampaipost,
      'shift': shift
    },
    success: function (data) {

      var jam = $.parseJSON(data);
      var istirahat = jam[0][0];
      var jam2 = $('#jam0').text();

      var jamasli = (jam2.split(".")[0]*60)*60;
      var menitasli = jam2.split(".")[1]*60;

      var  jamtotal = jamasli + menitasli;
      var  jamfix = jamtotal - istirahat;
      var  jamsatuan = secondsTimeSpanToHMS(jamfix);

      var jamsatuanfix = jamsatuan.split(":")[0];
      var menitsatuanfix = jamsatuan.split(":")[1];

      if (menitsatuanfix >= 0 && menitsatuanfix < 16){
        menitsatuanfix = 0;
      }else if (menitsatuanfix >= 16 && menitsatuanfix <= 45){
        menitsatuanfix = 5;
      }else{
        menitsatuanfix = 0;
        jamsatuanfix=parseInt(jamsatuanfix)+1;
      }

      var jamsatuanfix2 = jamsatuanfix+"."+menitsatuanfix

      $('#jamfix'+id).text(jamsatuanfix2);

    }
  }); 

}

function dari()
{

 var sampai1 = $('#sampai').val();
 var sampaipost ="";
 if (sampai1.split(":")[0]=="0") {
  sampaipost = "24:"+sampai1.split(":")[0];
}else{
  sampaipost = sampai1;
}
var dari1 = $('#dari').val();
var daripost ="";
if (dari1.split(":")[0]=="23") {
  daripost = "0:"+dari1.split(":")[0];
}else{
  daripost = dari1;
}
var tgl = $('#datepicker').val();
var shift = $("#shiftF").find(':selected')[0].value;


jam = sampai1.split(":")[0] - dari1.split(":")[0];
menit = sampai1.split(":")[1] - dari1.split(":")[1];

menit = menit.toString().length<2?'0'+menit:menit;
if (menit<0){
  jam--;
  menit = 60 + menit;        
}

jam = jam.toString().length<2?'0'+jam:jam;
if( jam < 0){
  ab = jam + 24;
}else
{
  ab = jam ;
}


$('#jam0').text(ab+"."+menit);


$.ajax({
  type: 'GET',
  url: '<?php echo base_url("ot/get_break") ?>',
  data: {
    'tgl': tgl,
    'dari': daripost,
    'sampai': sampaipost,
    'shift': shift
  },
  success: function (data) {

    var jam = $.parseJSON(data);
    var istirahat = jam[0][0];
    var jam2 = $('#jam0').text();

    var jamasli = (jam2.split(".")[0]*60)*60;
    var menitasli = jam2.split(".")[1]*60;

    var  jamtotal = jamasli + menitasli;
    var  jamfix = jamtotal - istirahat;
    var  jamsatuan = secondsTimeSpanToHMS(jamfix);

    var jamsatuanfix = jamsatuan.split(":")[0];
    var menitsatuanfix = jamsatuan.split(":")[1];

    if (menitsatuanfix >= 0 && menitsatuanfix < 16){
      menitsatuanfix = 0;
    }else if (menitsatuanfix >= 16 && menitsatuanfix <= 45){
      menitsatuanfix = 5;
    }else{
      menitsatuanfix = 0;
      jamsatuanfix=parseInt(jamsatuanfix)+1;
    }

    var jamsatuanfix2 = jamsatuanfix+"."+menitsatuanfix
    


    $('#jamfix').text(jamsatuanfix2);
    var dari = $('#dari').val();
    for (var i = 1; i < no; i++) {
      $('#dari'+i).val(dari);
      $('#jam'+i).text(jam2);
      $('#jamfix'+i).text(jamsatuanfix2);
    }
  }
});     

}
function secondsTimeSpanToHMS(s) {
    var h = Math.floor(s/3600); //Get whole hours
    s -= h*3600;
    var m = Math.floor(s/60); //Get remaining minutes
    s -= m*60;
    return h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); //zero padding on minutes and seconds
  }

  function sampai()
  {
    var sampai1 = $('#sampai').val();   
    var dari1 = $('#dari').val();
    var sampaipost ="";
    var daripost ="";
    if (sampai1.split(":")[0]=="0") {
      sampaipost = "24:"+sampai1.split(":")[0];
    }else{
      sampaipost = sampai1;
    }

    if (dari1.split(":")[0]=="23") {
      daripost = "0:"+dari1.split(":")[0];
    }else{
      daripost = dari1;
    }
    
    var tgl = $('#datepicker').val();
    var shift = $("#shiftF").find(':selected')[0].value;


    jam = sampai1.split(":")[0] - dari1.split(":")[0];
    menit = sampai1.split(":")[1] - dari1.split(":")[1];

    menit = menit.toString().length<2?'0'+menit:menit;
    if (menit<0){
      jam--;
      menit = 60 + menit;        
    }

    jam = jam.toString().length<2?'0'+jam:jam;
    if( jam < 0){
      ab = jam + 24;
    }else
    {
      ab = jam ;
    }


    $('#jam0').text(ab+"."+menit);


    $.ajax({
      type: 'GET',
      url: '<?php echo base_url("ot/get_break") ?>',
      data: {
        'tgl': tgl,
        'dari': daripost,
        'sampai': sampaipost,
        'shift': shift
      },
      success: function (data) {

        var jam = $.parseJSON(data);
        var istirahat = jam[0][0];
        var jam2 = $('#jam0').text();

        var jamasli = (jam2.split(".")[0]*60)*60;
        var menitasli = jam2.split(".")[1]*60;

        var  jamtotal = jamasli + menitasli;
        var  jamfix = jamtotal - istirahat;
        var  jamsatuan = secondsTimeSpanToHMS(jamfix);

        var jamsatuanfix = jamsatuan.split(":")[0];
        var menitsatuanfix = jamsatuan.split(":")[1];

        if (menitsatuanfix >= 0 && menitsatuanfix < 16){
          menitsatuanfix = 0;
        }else if (menitsatuanfix >= 16 && menitsatuanfix <= 45){
          menitsatuanfix = 5;
        }else{
          menitsatuanfix = 0;
          jamsatuanfix=parseInt(jamsatuanfix)+1;
        }

        var jamsatuanfix2 = jamsatuanfix+"."+menitsatuanfix



        $('#jamfix').text(jamsatuanfix2);
        var sampai = $('#sampai').val();
        for (var i = 1; i < no; i++) {
          $('#sampai'+i).val(sampai);
          $('#jam'+i).text(jam2);
          $('#jamfix'+i).text(jamsatuanfix2);
        }
      }
    });

  }

  

  function sampaiid(id)
  {
    var sampai1 = $('#sampai'+id).val();
    var dari1 = $('#dari'+id).val();
    var sampaipost ="";
    var daripost ="";
    if (sampai1.split(":")[0]=="0") {
      sampaipost = "24:"+sampai1.split(":")[0];
    }else{
      sampaipost = sampai1;
    }

    if (dari1.split(":")[0]=="23") {
      daripost = "0:"+dari1.split(":")[0];
    }else{
      daripost = dari1;
    }
    
    var tgl = $('#datepicker').val();
    var shift = $("#shiftF").find(':selected')[0].value;


    jam = sampai1.split(":")[0] - dari1.split(":")[0];
    menit = sampai1.split(":")[1] - dari1.split(":")[1];

    menit = menit.toString().length<2?'0'+menit:menit;
    if (menit<0){
      jam--;
      menit = 60 + menit;        
    }

    jam = jam.toString().length<2?'0'+jam:jam;
    if( jam < 0){
      ab = jam + 24;
    }else
    {
      ab = jam ;
    }


    $('#jam0').text(ab+"."+menit);


    $.ajax({
      type: 'GET',
      url: '<?php echo base_url("ot/get_break") ?>',
      data: {
        'tgl': tgl,
        'dari': daripost,
        'sampai': sampaipost,
        'shift': shift
      },
      success: function (data) {

        var jam = $.parseJSON(data);
        var istirahat = jam[0][0];
        var jam2 = $('#jam0').text();

        var jamasli = (jam2.split(".")[0]*60)*60;
        var menitasli = jam2.split(".")[1]*60;

        var  jamtotal = jamasli + menitasli;
        var  jamfix = jamtotal - istirahat;
        var  jamsatuan = secondsTimeSpanToHMS(jamfix);

        var jamsatuanfix = jamsatuan.split(":")[0];
        var menitsatuanfix = jamsatuan.split(":")[1];

        if (menitsatuanfix >= 0 && menitsatuanfix < 16){
          menitsatuanfix = 0;
        }else if (menitsatuanfix >= 16 && menitsatuanfix <= 45){
          menitsatuanfix = 5;
        }else{
          menitsatuanfix = 0;
          jamsatuanfix=parseInt(jamsatuanfix)+1;
        }

        var jamsatuanfix2 = jamsatuanfix+"."+menitsatuanfix



        $('#jamfix'+id).text(jamsatuanfix2);
        var sampai = $('#sampai').val();

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
