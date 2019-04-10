<!DOCTYPE html>
<html>
<?php require_once(APPPATH.'views/header/head.php'); ?>
<?php if (! $this->session->userdata('nikLogin')) { redirect('home/overtime_user'); }?>
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
            <div class="col-md-6">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-comments-o"></i>&nbsp;&nbsp;<span>Ajukan Pertanyaan</span></h3>
                  <!-- /.box-header -->
                  <!-- form start -->
                  <div class="box-body">
                    <div class="form-group">
                      <input type="text" id="tgl" class="form-control" readonly="" value="<?php echo date("d-m-Y") ?>">

                    </div>
                    <div class="form-group">

                      <textarea id="nama" class="form-control" placeholder="Pertanyaan" rows="5"></textarea>

                    </div>
                    <button class="btn btn-success pull-right" onclick="$('#myModal').modal('show');"><i class="fa fa-plus"></i> New Question</button>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-comments-o"></i>&nbsp;&nbsp;<span>Questions and Answers</span></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                 <table class="table table-responsive table-striped" id="table1">
                   <thead>
                     <th>Question Date</th>
                     <th>Question</th>
                     <th>Answer</th>
                     <th>Answer Date</th>
                     <th>Status</th>
                   </thead>
                   <tbody>
                   </tbody>
                 </table>
               </div>
               <!-- /.box-body -->
             </div>
           </div>
         </div>
         <!-- /.box -->
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
  var table;
  $(document).ready(function() {
    table = $('#table1').DataTable({
      "processing": true,
      "serverSide": true,
      "searching": false
      "bLengthChange": false,
      "bInfo": false,
      "order": [],
      // "ajax": {
      //       "url": "<?php echo base_url('home/ajax')?>",            
      //       "type": "POST"
      //   },
      "columnDefs": [
      {
          "targets": [ 0 ], //first column / numbering column
          "orderable": false, //set not orderable
        }]
      });

  })
</script>
</body>
</html>
