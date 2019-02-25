<!DOCTYPE html>
<html>
<?php require_once(APPPATH.'views/header/head.php'); ?>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-purple layout-top-nav">
  <div class="wrapper">

    <!-- NAVBAR -->
    <?php require_once(APPPATH.'views/header/navbar-top.php'); ?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
      <div class="container-fluid">
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-sm-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" id="foto" alt="User profile picture">

                  <h3 class="profile-username text-center" id="nama"></h3>

                  <p class="text-muted text-center" id="nik"></p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Cuti yang dipakai</b> <a class="pull-right"><small class="label pull-right bg-yellow"><p id="rt"></p></small></a>
                    </li>
                  </ul>
                </div>
                <!-- /.box-body -->
              </div>

              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">About Me <?php echo $this->session->userdata("role"); ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <strong><i class="fa fa-map-marker margin-r-5"></i> Departemen</strong>

                  <p class="text-muted" id="dev"></p>

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Section</strong>

                  <p class="text-muted" id="dep"></p>

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Sub - Section</strong>

                  <p class="text-muted" id="sec"></p>

                  <strong><i class="fa fa-calendar margin-r-5"></i> Join Date</strong>

                  <p class="text-muted" id="tglMasuk"></p>

                  <strong><i class="fa fa-user margin-r-5"></i> Employee Status</strong>

                  <p class="text-muted" id="statusKerja"></p>

                  <strong><i class="fa fa-bullhorn margin-r-5"></i> Position</strong>

                  <p class="text-muted" id="posisi"></p>                  

                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.widget-user -->
            </div>
            <div class="col-sm-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-user"></i>&nbsp&nbsp<span>Attendance</span></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                 <table class="table table-responsive table-striped text-center" id="table1">
                   <thead>
                     <th>Period</th>
                     <th>Alpa</th>
                     <th>Ijin</th>
                     <th>Sakit</th>
                     <th>Cuti</th>
                     <th>Cuti Khusus</th>
                     <th>PC</th>
                     <th>T</th>
                     <th>Diciplinary Allowance</th>
                     <th>Lembur (Jam)</th>
                     <th>aksi</th>
                   </thead>
                   <tbody>
                   </tbody>
                   <tfoot  id="tableFootStock">
                    <th>Total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tfoot>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
          </div>
        </div>
        <!-- /.box -->

        <div class="modal fade" id="myModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <p>January 2019</p>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">

                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
        </section>
        <!-- /.content -->
      </div>
      <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->
    <?php require_once(APPPATH.'views/footer/foot2.php'); ?>
  </div>
  <!-- ./wrapper -->

  <script>


    $(document).ready(function() {

      $.ajax({
        url: "<?php echo base_url('client/ajax_client_data/')?>" ,
        type : "POST",
        dataType: 'json',
        success: function(data){
          $.each(data, function(i, value){
            $("#nik").text(data[i][0]);
            $("#nama").text(data[i][1]);
            $("#tglMasuk").text(data[i][2]);
            $("#statusKerja").text(data[i][3]);
            $("#posisi").text(data[i][4]);
            $("#dev").text(data[i][5]);
            $("#dep").text(data[i][6]);
            $("#sec").text(data[i][7]);
            $("#foto").attr("src","<?php echo base_url() ?>app/img/photo/"+data[i][8]+"");
            $("#fotoHead").attr("src","<?php echo base_url() ?>app/img/photo/"+data[i][8]+"");
          });
        }
      });

      var tabel = $('#table1').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "bLengthChange": false,
        "paging": false,
        "bInfo": false,
        "order": [],
        "ajax": {
          "url": "<?php echo base_url('client/ajax_client')?>",            
          "type": "POST"
        },
        "columnDefs": [
        {
          "targets": [0,1,2,3,4,5,6,7,8,9,10], //first column / numbering column
          "orderable": false,//set not orderable
        },
        {
          'targets': 4,
          'createdCell':  function (td, cellData, rowData, row, col) {
           $(td).attr('id', 'ct'); 
         }
       }],
       "footerCallback": function (tfoot, data, start, end, display) {
        var intVal = function ( i ) {
          return typeof i === 'string' ?
          i.replace(/[\$%,]/g, '')*1 :
          typeof i === 'number' ?
          i : 0;
        };
        var api = this.api();
        var totalPlan = api.column(4).data().reduce(function (a, b) {
          return intVal(a)+intVal(b);
        }, 0)
        $(api.column(4).footer()).html(totalPlan.toLocaleString());
        $('#rt').html("" + totalPlan);
        
      }
    });

    })

    function openModal() {
      $("#myModal").modal('show');
    }

    $('#detail').click(function(e) {
     var tr = $(this).closest('tr');    
   //get the real row index, even if the table is sorted 
   var index = tabel.fnGetPosition(tr[0]);
   //alert the content of the hidden first column 
   alert(tabel.fnGetData(index)[0]);
 });
</script>
</body>
</html>
